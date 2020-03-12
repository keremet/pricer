<?
session_start();
include('../template/connect.php');
	
if(!$_REQUEST['product_id'])
	die('Не указан товар');

if(!$_REQUEST['price'])
	die('не введена цена');

$price = str_replace(',', '.', $_REQUEST['price']);

if(!is_numeric($price))
	die('неправильный формат цены');

$pieces = explode("/", $_REQUEST['date_buy']);
$date_buy = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];

$stmt = $db->prepare("INSERT INTO consumption (clsf_id,price,date_buy) VALUES (?,?,?)"); 
if(!$stmt->execute(array($_REQUEST['product_id'], $price, $date_buy))){
	print_r($stmt->errorInfo()); echo '<br>';
	die('Ошибка при добавлении цены');
}
echo 'Цена успешно добавлена';
?> 
