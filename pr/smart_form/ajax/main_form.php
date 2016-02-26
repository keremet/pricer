<?

include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');

include($GLOBALS['site_settings']['root_path'].'/template/header/invisible.php');



//rp($_REQUEST);

$errors = array();

if(!$_REQUEST['price']){
	$errors[] = 'не введена цена';
}elseif(!is_numeric($_REQUEST['price'])){
	$errors[] = 'неправильный формат цены';
}

if(!trim($_REQUEST['product_name']) && !$_REQUEST['product_id']){

	$errors[] = 'товар не выбран и не введено название нового товара';

}

if(!trim($_REQUEST['shop_address']) && !trim($_REQUEST['shop_network']) && !trim($_REQUEST['shop_town']) && !trim($_REQUEST['shop_name']) && !trim($_REQUEST['shop_id']) ){

	$errors[] = 'не выбран магазин и не введены параметры нового магазина';

}



if(!count($errors) > 0){

	if(!$_REQUEST['product_id']){ // если товар не выбран, а введено название

		// <создание товара

		$query = "SELECT `id` FROM `pr_products` WHERE `name` = {?}";

		$product_id = $db->selectCell($query, array($_REQUEST['product_name']));

		if(!$product_id){ // если товара с таким именем нет, то создаём его

			$query = "INSERT INTO pr_products (name,creator) VALUES ({?},{?})";

			$product_id = $db->query($query, array($_REQUEST['product_name'],$_SESSION['user']['id']));

		}

		// создание товара>

	}else{// если товар выбран

		$product_id = $_REQUEST['product_id'];

	}

	if(!$_REQUEST['shop_id']){ // если магазин не выбран

		$query = "SELECT `id` FROM `pr_shops` WHERE `name` = {?} AND `address` = {?} AND `network` = {?} AND `town` = {?}";

		$shop_id = $db->selectCell($query, array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_REQUEST['shop_network'],$_REQUEST['shop_town']));

		if(!$shop_id){// если магазина с такими параметрами нет, то создаём его

			$query = "INSERT INTO pr_shops (name,address,creator,network,town,text) VALUES ({?},{?},{?},{?},{?},{?})";

			$shop_id = $db->query($query, array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_SESSION['user']['id'],$_REQUEST['shop_network'],$_REQUEST['shop_town'],$_REQUEST['shop_text']));

		}

	}else{// если магазин выбран

		$shop_id = $_REQUEST['shop_id'];

	}

	if($product_id && $shop_id){

		$pieces = explode("/", $_REQUEST['date_buy']);

		$date_buy = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];

		$query = "INSERT INTO pr_product_offers (product,shop,price,creator,date_buy) VALUES ({?},{?},{?},{?},{?})"; 

		$offer_id = $db->query($query, array($product_id,$shop_id,$_REQUEST['price'],$_SESSION['user']['id'],$date_buy));

		if($offer_id){

			echo 'Цена успешно добавлена';

		}else{

			echo mysql_error();
			//echo 'неизвестная ошибка';

		}

	}else{

		echo 'неизвестная ошибка, нет id магазина или товара';

	}

}else{

	echo implode(", ", $errors);

}



?> 
