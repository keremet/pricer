<?
include('../../db/connect.php');

$stmt = $db->prepare("SELECT id, price FROM pr_product_offers WHERE shop = ? AND product = ? ORDER BY date_buy DESC LIMIT 1");
$stmt->execute(array($_REQUEST['shop'],$_REQUEST['product']));
if($price = $stmt->fetch()){
	echo json_encode($price);
}
?>
