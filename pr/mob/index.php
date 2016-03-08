<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <h1>Выберите магазин</h1>
 <?php
	include('../template/connect.php');

	foreach($db->query("SELECT id, concat_ws(' - ', name, network, address, town) as shop FROM pr_shops order by name") as $row){
		echo '<p><a href="prices.php?shopid='.$row['id'].'">'.$row['shop'].'</a></p>';
	}
 ?>
</body>
</html>
