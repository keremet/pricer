<?session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user_id']==null)
	die('Требуется авторизация');

$stmt = $db->prepare("INSERT ".DB_TABLE_PREFIX."fdn_to_shop(fiscalDriveNumber, shop_id) values(?, ?)");
if(!$stmt->execute(array($_REQUEST['fdn'], $_REQUEST['shop_id']))){
	print_r($stmt->errorInfo());
	die('Ошибка добавления кассы');
}

echo 'Касса добавлена';
