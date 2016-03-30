<?
session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');

function doNull($s){
	return ($s == '')?null:$s;
}

$stmt = $db->prepare("SELECT id FROM pr_shops WHERE name = ? and id != ?");
$stmt->execute(array($_REQUEST['shop_name'], $_REQUEST['id']));
if($stmt->fetch())
	die('Магазин с таким названием уже есть');

if (isset($_REQUEST['id'])) {
	$stmt = $db->prepare("UPDATE pr_shops SET name = ?, address = ? WHERE id = ?");
	if(!$stmt->execute(array($_REQUEST['shop_name'], doNull($_REQUEST['address']), $_REQUEST['id']))){
		echo 'Ошибка изменения магазина'; print_r($stmt->errorInfo());
		exit();
	}
	echo "<script>alert('Магазин изменен');document.location.href='index.php';</script>";
} else {
	$stmt = $db->prepare("INSERT pr_shops(name, address, main_clsf_id, creator) values(?, ?, ?, ?)");
	if(!$stmt->execute(array($_REQUEST['shop_name'], doNull($_REQUEST['address']), $_REQUEST['main_clsf_id'], $_SESSION['user']['id']))){
		echo 'Ошибка добавления магазина'; print_r($stmt->errorInfo());
		exit();
	}
	echo "<script>alert('Магазин добавлен');document.location.href='index.php';</script>";
}

?>
