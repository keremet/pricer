<?
	include '../template/header.php';
	headerOut('Товар');

	$product_id = (int) $_REQUEST['id'];
	$stmt = $db->prepare(
		"SELECT p.name, p.photo, e.name as ed_izm, p.ed_izm_id, p.in_box
		 FROM ".DB_TABLE_PREFIX."products p
		 LEFT JOIN ".DB_TABLE_PREFIX."ed_izm e on e.id = p.ed_izm_id
		 WHERE p.id = ?"
	);
	$stmt->execute(array($product_id));
	if(!($product = $stmt->fetch())){
		echo 'Товар не найден';
		include('../template/footer.php');
		exit;
	}?>
	
	<h1><?=htmlspecialchars ($product['name'])?></h1><br><br>
	<?$ph = ($product['photo'])?
		'../uploaded/'.$product['photo']:'../images/noph_prod.png';?>
	<div style=" height: 140px; width: 140px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">
		<a class="fancybox" href="<?=$ph ?>">
			<img style=" max-width: 140px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="<?=$ph ?>">
		</a>
	</div><br>
	Единица измерения: <?=$product['ed_izm'] ?><br>
	Количество единиц измерения в товаре: <?=$product['in_box'] ?><br>
	<a target="_blank" href="../analytics/?product[]=<?=$product_id?>&send=Применить+фильтр">Перейти к ценам</a>
<?
	include('../template/footer.php');
?>
