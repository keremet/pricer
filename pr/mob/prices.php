<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <form method=post action="save.php">
	<p><input type="submit" value="Отправить">	
  <?php
	include('../template/connect.php');
	
	$stmt = $db->prepare("SELECT concat_ws(' - ', name, address) FROM ".DB_TABLE_PREFIX."shops where id = ?");
	$stmt->execute(array($_GET['shopid']));
	echo '<h1>'.$stmt->fetchColumn().'</h1>';	
	echo '<input type="hidden" name="shopid" value="'.$_GET['shopid'].'">';
	
	foreach($db->query("SELECT id, name FROM ".DB_TABLE_PREFIX."products order by name") as $row){
		echo '<p>'.$row['name'].' <input name="prod'.$row['id'].'" type="text" size="10">';
	}
  ?>
 </form>
</body>
</html>
