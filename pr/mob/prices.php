<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <form method=post action="save.php">
	<p><input type="submit" value="Отправить">	
  <?php
	include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
	include($GLOBALS['site_settings']['root_path'].'/template/settings.php');
	include($GLOBALS['site_settings']['root_path'].'/template/db_connect.php');
	echo '<h1>'.$db->selectCell("SELECT concat_ws(' - ', name, network, address, town) as shop FROM pr_shops where id = {?}", array($_GET['shopid'])).'</h1>';	
	echo '<input type="hidden" name="shopid" value="'.$_GET['shopid'].'">';
	
	$products = $db->select("SELECT id, name FROM pr_products order by name");
	foreach($products as $k => $v){
		echo '<p>'.$v['name'].' <input name="prod'.$v['id'].'" type="text" size="10">';
	}
  ?>
 </form>
</body>
</html>
