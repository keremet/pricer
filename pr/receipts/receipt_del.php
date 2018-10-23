<?
session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');

if (isset($_REQUEST['id'])) {
	$stmt = $db->prepare("DELETE FROM ".DB_TABLE_PREFIX."receipt WHERE id = ? and user_id = ?");
	if(!$stmt->execute(array($_REQUEST['id'], $_SESSION['user']['id']))){
		echo 'Ошибка удаления чека'; print_r($stmt->errorInfo());
		exit();
	}
	$count = $stmt->rowCount();
	if($count == 1){
		echo "Чек удален";
	}elseif($count < 1){
		echo "Чек не найден";
	}else{
		echo "Дублирование индекса. Удалено больше 1 строки.";
	}
}
?>
