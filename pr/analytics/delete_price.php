<?
session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user_id']==null)
	die('Требуется авторизация');

if (isset($_REQUEST['id'])) {
	$stmt = $db->prepare("DELETE FROM ".DB_TABLE_PREFIX."product_offers WHERE id = ? and creator=?");
	if(!$stmt->execute(array($_REQUEST['id'], $_SESSION['user_id']))){
		echo 'Ошибка удаления цены'; print_r($stmt->errorInfo());
		exit();
	}
	$count = $stmt->rowCount();
	if($count == 1){
		echo "Цена удалена";
	}elseif($count < 1){
		echo "Цена не найдена";
	}else{
		echo "Дублирование индекса. Удалено больше 1 строки.";
	}
}
?>
