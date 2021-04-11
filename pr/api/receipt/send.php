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
	 FROM users
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
	 FROM users
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
"INSERT INTO receipt (dateTime, totalSum, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign, user_id, ins_user_id) 
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

echo numberof($rowCount, 'Загружен', array('', 'о', 'о'))." $rowCount ".numberof($rowCount, 'чек', array('', 'а', 'ов'))."\n";

$stmt = $db->prepare(
"SELECT count(1)
 FROM receipt
 WHERE ins_user_id = ?
");

$stmt->execute(array($ins_user_id));
$all_user_receipts = $stmt->fetchColumn();
echo 'Всего Вами ' . numberof($all_user_receipts, 'загружен', array('', 'о', 'о'))." $all_user_receipts ".numberof($all_user_receipts, 'чек', array('', 'а', 'ов'));

if (1 == $rowCount) {
	$stmt = $db->prepare(
		"SELECT CONCAT(s.name, ' - ', s.address)
		 FROM fdn_to_shop f2s
		   JOIN shops s ON s.id=f2s.shop_id
		 WHERE f2s.fiscalDriveNumber = ?"
	);
	$stmt->execute(array($p['fn']));
	$shop = $stmt->fetchColumn();
	echo ($shop) ? "\nМагазин $shop" : "\nID кассы не привязан к магазину";
}
	
if (1 == $user_id) {
	$stmt = $db->prepare(
		"SELECT 1
		 FROM equ_products e
		 WHERE e.equ_clsf_id = 5 AND NOT EXISTS (
			SELECT 1 FROM fact f
			WHERE f.product = e.product_id AND f.creator = 1 AND f.amount is not null AND f.date_buy >= NOW() - INTERVAL 3 MONTH
		 )"
	);
	$stmt->execute();
	if ($stmt->fetch())
		echo "\nПора менять зубную щётку";
}
?>
