<?
$errors = array();

$query = "SELECT `id` FROM `pr_products` WHERE `name` = {?}";
$id = $db->selectCell($query, array($_REQUEST['product_name']));
if($id){
	$errors[] = 'Товар с таким названием уже есть';
}

if(count($errors) > 0){
	$alert = implode(", ", $errors);
	echo "<script>alert('Ошибка: ".$alert."');</script>";
}else{
	$query = "INSERT INTO pr_shops (name,address,user) VALUES ({?},{?},{?})";
	$user_id = $db->query($query, array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_SESSION['user']['id']));
	if($user_id)
		echo "<script>alert('Товар добавлен!'); document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/products/';</script>";
	else
		echo "<script>alert('Неизвестная ошибка');</script>";
}
?>