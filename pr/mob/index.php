<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <h1>Выберите магазин</h1>
 <?php
	include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
	include($GLOBALS['site_settings']['root_path'].'/template/settings.php');
	include($GLOBALS['site_settings']['root_path'].'/template/db_connect.php');
	$table = $db->select("SELECT id, concat_ws(' - ', name, network, address, town) as shop FROM pr_shops order by name");
	foreach($table as $k => $v){
		echo '<p><a href="prices.php?shopid='.$v['id'].'">'.$v['shop'].'</a></p>';
	}
 ?>
</body>
</html>
