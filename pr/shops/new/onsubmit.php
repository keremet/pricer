<?
$errors = array();
/*if(!$_REQUEST['shop_name']){
	$shop_name = '';
	if($_REQUEST['shop_network']){
		$shop_name .= $_REQUEST['shop_network'];
		name_is = 1;
	}elseif(){
		if(){
	}
}else{
	$shop_name = $_REQUEST['shop_name'];
}*/
$query = "SELECT `id` FROM `".$GLOBALS['site_settings']['db']['tables']['shops']."` WHERE `name` = {?} AND `address` = {?} AND `network` = {?} AND `town` = {?}";
// проверка на уникальность создаваемого магазина. Проверяется совокупность названия, сети, города и адреса.
if(!$_REQUEST['shop_address'] && !$_REQUEST['shop_network'] && !$_REQUEST['shop_town'] && !$_REQUEST['shop_name']){
	$errors[] = 'Вы должны ввести название магазина, название сети, город или адрес магазина';
}else{
	$id = $db->selectCell($query, array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_REQUEST['shop_network'],$_REQUEST['shop_town']));
	if($id){
		$errors[] = 'Такой магазин уже есть';
	}
}
if(count($errors) > 0){
	$alert = implode(", ", $errors);
	echo "<script>alert('Ошибка: ".$alert."');</script>";
}else{
	$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['shops']." (name,address,creator,network,town,text) VALUES ({?},{?},{?},{?},{?},{?})";
	$shop_id = $db->query($query, array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_SESSION['user']['id'],$_REQUEST['shop_network'],$_REQUEST['shop_town'],$_REQUEST['shop_text']));
	if($shop_id)
		echo "<script>alert('Магазин добавлен!'); document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/shops/';</script>";
	else
		echo "<script>alert('Неизвестная ошибка');</script>";
	unset($_REQUEST);
}
?>