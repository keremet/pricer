<?php
	include('../template/connect.php');
	$db->query("delete from pr_product_offers where creator=1 and date_buy = CURDATE()");
?>
