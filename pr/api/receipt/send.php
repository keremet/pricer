<?php
include('../../template/connect.php');
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

$res = '';
$rowCount = 0;
$stmt = $db->prepare(
"INSERT INTO ".DB_TABLE_PREFIX."receipt (dateTime, totalSum, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign, user_id) 
 VALUES (STR_TO_DATE(?, '%Y%m%dT%H%i%s'), ?, ?, ?, ?, ?)
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
		//print_r($p);

		if((!array_key_exists('t', $p)) || (!array_key_exists('s', $p)) || (!array_key_exists('fn', $p))
			|| (!array_key_exists('i', $p)) || (!array_key_exists('fp', $p))){
			$res .= 'ERR формат QR-кода ';
		}else if($stmt->execute(array($p['t'], $p['s'], $p['fn'], $p['i'], $p['fp'], $user_id))){
			$res .= 'OK ';
			$rowCount += $stmt->rowCount();
		}else{
			$errInfo = $stmt->errorInfo();
			if($errInfo[1] == 1062){
				list($d, $t) = explode('T', $p['t']);
				echo substr($d, 6, 2).".".substr($d, 4, 2).".".substr($d, 2, 2)." ".
					 substr($t, 0, 2).":".substr($t, 2, 2).":".substr($t, 4, 2)." ".
					 $dbg_s." уже добавлен\n";
				$res .= 'WARN ';
			}else{
				$res .= 'ERR ';
				print_r($errInfo);
			}
		}
	}
}

echo ($res)?$res." добавлено $rowCount чеков":'Данные не обработаны';
?>
