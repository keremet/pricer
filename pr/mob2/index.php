<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <h1>Выберите магазин</h1>
 <?php
	include('../template/connect.php');

	foreach($db->query(
			"SELECT id, concat_ws(' - ', name, address) as shop 
			 FROM shops
			 where id in (2, 1, 20, 30, 31,  5, 32, 4)
			 order by name"
		) as $row){
		echo '<p><a href="prices.php?shopid='.$row['id'].'">'.$row['shop'].'</a></p>';
	}
 ?>
</body>
</html>
