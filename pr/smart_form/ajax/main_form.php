<?
session_start();
include('../../db/connect.php');

if(!$_REQUEST['price']){
	die('не введена цена');
}elseif(!is_numeric($_REQUEST['price'])){
	die('неправильный формат цены');
}

if(!trim($_REQUEST['product_name']) && !$_REQUEST['product_id']){
	die('товар не выбран и не введено название нового товара');
}

if(!trim($_REQUEST['shop_address']) && !trim($_REQUEST['shop_network']) && !trim($_REQUEST['shop_town']) && !trim($_REQUEST['shop_name']) && !trim($_REQUEST['shop_id']) ){
	die('не выбран магазин и не введены параметры нового магазина');
}
if($_REQUEST['product_id']){ // если товар выбран 
	$product_id = $_REQUEST['product_id'];
}else{ // если товар не выбран, а введено название
	// поиск товара
	$stmt = $db->prepare("SELECT id FROM pr_products WHERE name = ?");
	$stmt->execute(array($_REQUEST['product_name']));		
	if(!($product_id = $stmt->fetchColumn())){ // если товара с таким именем нет, то создаём его
		$stmt = $db->prepare("INSERT INTO pr_products (name,creator) VALUES (?, ?)");
		if(!$stmt->execute(array($_REQUEST['product_name'],$_SESSION['user']['id']))){
			print_r($stmt->errorInfo()); echo '<br>';
			die('Ошибка при добавлении товара');
		}
		$product_id = $db->lastInsertId();
	}
}

if(!$product_id)
	die('Нет id товара');

if($_REQUEST['shop_id']){// если магазин выбран
	$shop_id = $_REQUEST['shop_id'];
}else{ // если магазин не выбран
	$stmt = $db->prepare("SELECT id FROM pr_shops WHERE name = ? AND address = ? AND network = ? AND town = ?");
	$stmt->execute(array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_REQUEST['shop_network'],$_REQUEST['shop_town']));
	if(!($shop_id = $stmt->fetchColumn())){// если магазина с такими параметрами нет, то создаём его
		$stmt = $db->prepare("INSERT INTO pr_shops (name,address,creator,network,town,text) VALUES (?, ?, ?, ?, ?, ?)");
		if(!$stmt->execute(array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_SESSION['user']['id'],$_REQUEST['shop_network'],$_REQUEST['shop_town'],$_REQUEST['shop_text']))){
			print_r($stmt->errorInfo()); echo '<br>';
			die('Ошибка при добавлении магазина');
		}
		$shop_id = $db->lastInsertId();
	}
}

if(!$shop_id)
	die('Нет id магазина');


$pieces = explode("/", $_REQUEST['date_buy']);
$date_buy = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
$stmt = $db->prepare("INSERT INTO pr_product_offers (product,shop,price,creator,date_buy) VALUES (?,?,?,?,?)"); 
if(!$stmt->execute(array($product_id,$shop_id,$_REQUEST['price'],$_SESSION['user']['id'],$date_buy))){
	print_r($stmt->errorInfo()); echo '<br>';
	die('Ошибка при добавлении цены');
}
echo 'Цена успешно добавлена';
?> 
