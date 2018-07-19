<?php
include('../../template/connect.php');
$entityBody = file_get_contents('php://input');
//$entityBody = file_get_contents('post.txt');

//file_put_contents('test.txt', $entityBody);

$login = $_GET['login'];
$passwd = $_GET['passwd'];

$res = '';
$rowCount = 0;
$stmt = $db->prepare(
"INSERT INTO ".DB_TABLE_PREFIX."receipt (dateTime, totalSum, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign, user_id) 
 SELECT STR_TO_DATE(?, '%Y%m%dT%H%i%s'), ?, ?, ?, ?, id 
 FROM ".DB_TABLE_PREFIX."users
 WHERE login = ? and password = ?");
if ($stmt==FALSE)
	die('prepare failed');
foreach(explode(';', $entityBody) as $qrCode){
	if($qrCode){
		$p = array();
		foreach(explode('&', $qrCode) as $param){
			list($key, $value) = explode('=', $param);
			if($key == 's')
				$value = str_replace('.', '', $value);
			$p[$key] = $value;
		}
		//print_r($p);

		if($stmt->execute(array($p['t'], $p['s'], $p['fn'], $p['i'], $p['fp'], $login, $passwd))){
			$res .= 'OK ';
			$rowCount += $stmt->rowCount();
		}else{
			$res .= 'ERR ';
			print_r($stmt->errorInfo());
		}
	}
}

echo ($res)?$res." добавлено $rowCount чеков":'Данные не обработаны';
?>
