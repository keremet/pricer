<?php
include('../template/connect.php');
$stmt = $db->prepare("INSERT INTO pr_product_offers (product,shop,price,creator,date_buy) VALUES (?, ?, ?, ?, CURDATE())");
if($stmt->execute(array($_POST['product_id'], $_POST['shop_id'], $_POST['price'], $_POST['token']))){
	echo json_encode(array('res' => 'OK'));
}else{
	echo json_encode(array('res' => 'Error '.$stmt->errorInfo()[2]));
}
?>

