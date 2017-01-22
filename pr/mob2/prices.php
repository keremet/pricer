<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <form method=post action="../mob/save.php">
	<p><input type="submit" value="Отправить">	
  <?php
	include('../template/connect.php');
	
	$stmt = $db->prepare("SELECT concat_ws(' - ', name, address) FROM pr_shops where id = ?");
	$stmt->execute(array($_GET['shopid']));
	echo '<h1>'.$stmt->fetchColumn().'</h1>';	
	echo '<input type="hidden" name="shopid" value="'.$_GET['shopid'].'">';
	
	foreach($db->query(
			"SELECT id, name
			 FROM pr_products
			 where id in (10, 278, 221, 12, 293, 9, 269, 155, 24, 7, 124, 91, 4, 38, 60, 3, 253, 288, 212, 44)
			 order by name"
		) as $row){
		echo '<p>'.$row['name'].' <input name="prod'.$row['id'].'" type="text" size="10">';
	}
  ?>
 </form>
</body>
</html>
