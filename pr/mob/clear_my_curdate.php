<?php
	include('../template/connect.php');
	$db->query("delete from ".DB_TABLE_PREFIX."product_offers where creator=1 and date_buy = CURDATE()");
?>
