<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
include($GLOBALS['site_settings']['root_path'].'/template/header/invisible.php');

$errors = array();
$query = "SELECT `id` FROM `".$GLOBALS['site_settings']['db']['tables']['products']."` WHERE `name` = {?}";
// проверка на уникальность создаваемого магазина. Проверяется совокупность названия, сети, города и адреса.
if(!$_REQUEST['product_name']){
	$errors[] = 'Вы должны ввести название товара';
}else{
	$id = $db->selectCell($query, array($_REQUEST['product_name']));
	if($id){
		$errors[] = 'Такой товар уже есть';
	}
}
if(count($errors) > 0){
	$alert = implode(", ", $errors);
	//echo "<script>alert('Ошибка: ".$alert."');</script>";
	echo json_encode(array('errors' => $alert));
}else{
	$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['products']." (name,creator) VALUES ({?},{?})";
	$product_id = $db->query($query, array($_REQUEST['product_name'],$_SESSION['user']['id']));
	if($product_id)
		echo json_encode(array('product_name' => $_REQUEST['product_name'], 'id' => $product_id, 'type' => 'product'));
	else
		echo json_encode(array('errors' => 'Неизвестная ошибка'));
	unset($_REQUEST);
}
?>