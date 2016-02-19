<p><a class="fancybox" href="#new_shop_form">Добавить новый магазин</a></p>
<div id="new_shop_form" style="width:400px;display: none;"><!--style="display: none;"-->
	<h2>Добавление нового магазина</h2>
	<form action="" method="post">
		Название :<br>
		<input type="text" name="shop_name" placeholder="Название магазина" value="<?=$_REQUEST['shop_name']?>"><br><br>
		Торговая сеть :<br>
		<input type="text" name="shop_network" placeholder="Торговая сеть" value="<?=$_REQUEST['shop_network']?>"><br><br>
		Город :<br>
		<input type="text" name="shop_town" placeholder="Город" value="<?=$_REQUEST['shop_town']?>"><br><br>
		Адрес:<br>
		<textarea name="shop_address" placeholder="Адрес магазина"><?=$_REQUEST['shop_address']?></textarea><br><br>
		Комментарий:<br>
		<textarea name="shop_text" placeholder="Комментарий"><?=$_REQUEST['shop_text']?></textarea><br><br>
		<input type="submit" name="new_shop" value="Добавить магазин"><br><br>
	</form>
</div>
