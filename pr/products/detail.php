<?
include '../template/header.php';
headerOut('Ценовичок - Товары', 'Ценовичок - Товары', 'Ценовичок - аналитика цен на товары', 'Киров, цены, продукты', '..', 'Товары');

function showProduct($product_name, $product_photo, $ed_izm, $ed_izm_id, $in_box, $min_kolvo, $readonly){
	?>	
	<form action="newonsubmit.php" method="post" enctype = 'multipart/form-data'>
		Название товара*<br>
		<input <?=($readonly)?'readonly':''?> required type="text" name="product_name" value="<?=$product_name?>"><br><br><?
		if($product_photo){
			echo '<div style=" height: 140px; width: 140px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">';
			echo '<a class="fancybox" href="'.$product_photo.'"><img style=" max-width: 140px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="'.$product_photo.'"></a>';
			echo '</div>';
		} 
		if(!$readonly){?>
		Фото (изображение не больше 1 Мб)<br>
		<input type="file" name="image" /><br><br>
		<?}?>
		Единица измерения *<br>
		<? if($readonly){?>
			<input readonly type="text" name="ed_izm" value="<?=$ed_izm?>">
		<? } else {?>
			<select id="ed_izm" name="ed_izm">
				<option selected disabled>Выберите единицу измерения...</option>
				<?
				global $db;
				foreach($db->query("SELECT id, name FROM pr_ed_izm order by id") as $v){
					echo '<option '.(($v['id']==$ed_izm_id)?'selected':'').' value="'.$v['id'].'">'.$v['name'].'</option>';
				}
				?>
			</select>
		<? } ?>
		<br><br>
		Количество товара в одной упаковке<br>
		<input <?=($readonly)?'readonly':''?> type="text" name="in_box" value="<?=$in_box?>"><br><br>
		Минимальное количество товара, которое можно купить(в ед. изм.)<br>
		<input <?=($readonly)?'readonly':''?> type="text" name="min_kolvo" value="<?=$min_kolvo?>"><br><br>
		<br><br><?
			if(!$readonly){
				echo '<input type="submit" value="'.(is_null($product_name)?'Добавить':'Изменить').' товар"><br><br>';
				if(!is_null($product_name))
					echo '<input type="hidden" name="id" value="'.$_REQUEST['id'].'">';
			}
		?>		
	</form><?
	if((!$readonly) && (!is_null($product_name))){?>
		<form action="delete.php" method="post">
			<input type="submit" value="Удалить товар">
			<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
		</form>	
	<?}
}

if(isset($_REQUEST['id'])){
	$stmt = $db->prepare(
		"SELECT pr_products.name, photo, pr_ed_izm.name as ed_izm, ed_izm_id, in_box, min_kolvo
		FROM pr_products
		LEFT JOIN pr_ed_izm on pr_ed_izm.id = pr_products.ed_izm_id
		WHERE pr_products.id = ?"
	);
	$stmt->execute(array($_REQUEST['id']));
	if(!($product = $stmt->fetch())){
		die("<script>alert('Неверный адрес страницы!'); document.location.href='index.php';</script>");
	}
	showProduct($product['name'], $product['photo'], $product['ed_izm'], $product['ed_izm_id'], $product['in_box'], $product['min_kolvo'], is_null($_SESSION['user']['id']));
}else{
	echo "<h1>Добавление продукта</h1>";
	showProduct(null, null, null, null, null, null, false);
}
?>
<p><a href='index.php'>Назад к списку товаров</a></p>
<? include('../template/footer.php'); ?>

