<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
include($GLOBALS['site_settings']['root_path'].'/template/header/invisible.php');

$errors = array();
$query = "SELECT `id`, `price` FROM `".$GLOBALS['site_settings']['db']['tables']['product_offers']."` WHERE `shop` = {?} AND `product` = {?} ORDER BY date_buy DESC LIMIT 1";
$price = $db->selectRow($query, array($_REQUEST['shop'],$_REQUEST['product']));
if($price){
	echo json_encode($price);
}
?>