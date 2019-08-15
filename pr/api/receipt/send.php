<?php
include('../../template/connect.php');

/**
* Склонение числительных
* @param int $numberof — склоняемое число
* @param string $value — первая часть слова (можно назвать корнем)
* @param array $suffix — массив возможных окончаний слов
* @return string
*
*/
function numberof($numberof, $value, $suffix)
{
	$keys = array(2, 0, 1, 1, 1, 2);
	$mod = $numberof % 100;
	$suffix_key = $mod > 4 && $mod < 20 ? 2 : $keys[min($mod%10, 5)];

	return $value . $suffix[$suffix_key];
}

$entityBody = file_get_contents('php://input');
//$entityBody = file_get_contents('post.txt');

//file_put_contents('test.txt', $entityBody);

$stmt = $db->prepare(
	"SELECT id
	 FROM ".DB_TABLE_PREFIX."users
	 WHERE login = ? and password = ?
	");

$stmt->execute(array($_GET['login'], $_GET['passwd']));
$row = $stmt->fetch();
if(!$row)
	die("Неверный логин или пароль");

$user_id = $row['id'];
$ins_user_id = $user_id;

if (isset($_GET['buyer_login']) && ($_GET['buyer_login']!='')) {
	$stmt = $db->prepare(
	"SELECT id
	 FROM ".DB_TABLE_PREFIX."users
	 WHERE login = ?
	");
	
	$stmt->execute(array($_GET['buyer_login']));
	$row = $stmt->fetch();
	if(!$row)
		die("Неверный логин покупателя");
		
	$user_id = $row['id'];
}


$rowCount = 0;
$stmt = $db->prepare(
"INSERT INTO ".DB_TABLE_PREFIX."receipt (dateTime, totalSum, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign, user_id, ins_user_id) 
 VALUES (STR_TO_DATE(?, '%Y%m%dT%H%i%s'), ?, ?, ?, ?, ?, ?)
");
if ($stmt==FALSE)
	die('prepare failed');
foreach(explode(';', $entityBody) as $qrCode){
	if($qrCode){
		$p = array();
		$dbg_s = "";
		foreach(explode('&', $qrCode) as $param){
			list($key, $value) = explode('=', $param);
			if($key == 's'){
				$dbg_s = $value;
				$value = str_replace('.', '', $value);
			}
			$p[$key] = $value;
		}

		if((!array_key_exists('t', $p)) || (!array_key_exists('s', $p)) || (!array_key_exists('fn', $p))
			|| (!array_key_exists('i', $p)) || (!array_key_exists('fp', $p))){
			echo "Ошибочный QR-код $qrCode\n";
		}else if($stmt->execute(array($p['t'], $p['s'], $p['fn'], $p['i'], $p['fp'], $user_id, $ins_user_id))){
			$rowCount += $stmt->rowCount();
		}else{
			$errInfo = $stmt->errorInfo();
			if($errInfo[1] == 1062){
				list($d, $t) = explode('T', $p['t']);
				echo substr($d, 6, 2).".".substr($d, 4, 2).".".substr($d, 2, 2)." ".
					 substr($t, 0, 2).":".substr($t, 2, 2).":".substr($t, 4, 2)." ".
					 $dbg_s." уже загружен\n";
			}else{
				echo "Ошибка при загрузке $qrCode: ";
				print_r($errInfo);
			}
		}
	}
}

echo numberof($rowCount, 'Загружен', array('', 'о', 'о'))." $rowCount ".numberof($rowCount, 'чек', array('', 'а', 'ов'));
?>
