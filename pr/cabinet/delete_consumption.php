<?
session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');

if (isset($_REQUEST['id'])) {
	$stmt = $db->prepare("DELETE FROM pr_consumption WHERE id = ?");
	if(!$stmt->execute(array($_REQUEST['id']))){
		echo 'Ошибка удаления расхода'; print_r($stmt->errorInfo());
		exit();
	}
	$count = $stmt->rowCount();
	if($count == 1){
		echo "Расход удален";
	}elseif($count < 1){
		echo "Расход не найден";
	}else{
		echo "Дублирование индекса. Удалено больше 1 строки.";
	}
}
?>
