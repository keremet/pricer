<?php
	include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
	include($GLOBALS['site_settings']['root_path'].'/template/settings.php');
	include($GLOBALS['site_settings']['root_path'].'/template/db_connect.php');
	$db->query("delete from pr_product_offers where creator=1 and date_buy = CURDATE()");
?>
