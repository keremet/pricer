<?
	include '../template/header.php';
	headerOut('Магазин');

	$shop_id = (int) $_REQUEST['id'];
	$stmt = $db->prepare(
		"SELECT name, address
		 FROM shops
		 WHERE id = ?"
	);
	$stmt->execute(array($shop_id));
	if(!($shop = $stmt->fetch())){
		echo 'Магазин не найден';
		include('../template/footer.php');
		exit;
	}?>
	
	<h1><?=htmlspecialchars ($shop['name'])?></h1><br><br>
	Адрес: <?=$shop['address'] ?><br>
	<a target="_blank" href="../analytics/?shops[]=<?=$shop_id?>&send=Применить+фильтр">Перейти к аналитике</a>
<?
	include('../template/footer.php');
?>
