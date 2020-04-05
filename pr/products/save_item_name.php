<?session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user_id']==null)
	die('Требуется авторизация');

$stmt = $db->prepare("INSERT receipt_item_name_to_product(name, product_id) values(trim(?), ?)");
if(!$stmt->execute(array($_REQUEST['item_name'], $_REQUEST['product_id']))){
	print_r($stmt->errorInfo());
	die('Ошибка добавления названия в чеке');
}

echo 'Название в чеке добавлено';
