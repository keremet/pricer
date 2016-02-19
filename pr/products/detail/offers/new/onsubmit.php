<?
unset($table);
$errors = array();
$pieces = explode("/", $_REQUEST['date_buy']);
$date = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['product_offers']." (product,shop,price,creator,date_buy) VALUES ({?},{?},{?},{?},{?})";
$offer_id = $db->query($query, array($_REQUEST['product'],$_REQUEST['shop'],$_REQUEST['price'],$_SESSION['user']['id'],$date));

if(count($errors) <= 0){

}
if(count($errors) > 0){
	$alert = implode(", ", $errors);
	echo "<script>alert('Ошибка: ".$alert."');</script>";
}else{
	//$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['products']." (name,address,user) VALUES ({?},{?},{?})";
	//$product_id = $db->query($query, array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_SESSION['user']['id']));
	if($offer_id){
		$product_id = (int) $_REQUEST['detail'];
		echo "<script>alert('Предложение добавлено!');"; 
		echo "document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/products/?detail=".$product_id."';</script>";
	}else{
		echo "<script>alert('Неизвестная ошибка');</script>";
	}
}
?>