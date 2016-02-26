<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <h1>Выберите магазин</h1>
 <?php
	include('../template/db_connect.php');

	foreach($db->select("SELECT id, concat_ws(' - ', name, network, address, town) as shop FROM pr_shops order by name") as $k => $v){
		echo '<p><a href="prices.php?shopid='.$v['id'].'">'.$v['shop'].'</a></p>';
	}
 ?>
</body>
</html>
