<?
session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');

if (isset($_REQUEST['id'])) {
	$stmt = $db->prepare("DELETE FROM pr_product_offers WHERE id = ?");
	if(!$stmt->execute(array($_REQUEST['id']))){
		echo 'Ошибка удаления цены'; print_r($stmt->errorInfo());
		exit();
	}
	echo "Цена удалена";
}


?>
