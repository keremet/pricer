<?
include('../template/connect.php');

$stmt = $db->prepare("SELECT price, date_buy FROM ".DB_TABLE_PREFIX."fact WHERE shop = ? AND product = ? ORDER BY date_buy DESC LIMIT 1");
$stmt->execute(array($_REQUEST['shop'],$_REQUEST['product']));
if($price = $stmt->fetch()){
	echo json_encode($price);
}
?>
