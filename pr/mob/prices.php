<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <form method=post action="save.php">
	<p><input type="submit" value="Отправить">	
  <?php
	include('../db/connect.php');
	
	$stmt = $db->prepare("SELECT concat_ws(' - ', name, network, address, town) FROM pr_shops where id = ?");
	$stmt->execute(array($_GET['shopid']));
	echo '<h1>'.$stmt->fetchColumn().'</h1>';	
	echo '<input type="hidden" name="shopid" value="'.$_GET['shopid'].'">';
	
	foreach($db->query("SELECT id, name FROM pr_products order by name") as $row){
		echo '<p>'.$row['name'].' <input name="prod'.$row['id'].'" type="text" size="10">';
	}
  ?>
 </form>
</body>
</html>
