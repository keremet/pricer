<?
session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');

$stmt = $db->prepare("delete FROM pr_products WHERE id = ?");
if(!$stmt->execute(array($_REQUEST['id']))){
	echo 'Ошибка удаление товара'; print_r($stmt->errorInfo());
	exit();
}
//TODO: Дописать удаление картинки

echo "<script>alert('Товар удален');document.location.href='index.php';</script>";
?>
