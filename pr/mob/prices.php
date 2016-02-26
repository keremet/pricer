<html>
<head>
	<meta charset="utf-8">
</head>
<body>
 <form method=post action="save.php">
	<p><input type="submit" value="Отправить">	
  <?php
	include('../template/db_connect.php');

	echo '<h1>'.$db->selectCell("SELECT concat_ws(' - ', name, network, address, town) as shop FROM pr_shops where id = {?}", array($_GET['shopid'])).'</h1>';	
	echo '<input type="hidden" name="shopid" value="'.$_GET['shopid'].'">';
	
	foreach($db->select("SELECT id, name FROM pr_products order by name") as $k => $v){
		echo '<p>'.$v['name'].' <input name="prod'.$v['id'].'" type="text" size="10">';
	}
  ?>
 </form>
</body>
</html>
