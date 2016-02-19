<p><a class="fancybox" href="#new_shop_form">Добавить новый товар</a></p>
<div id="new_shop_form" style="width:400px;display: none;"><!--style="display: none;"-->
	<h2>Добавление нового товара</h2>
	<form action="" method="post">
		Название* :<br>
		<input required type="text" name="product_name" placeholder="Название товара" value="<?=$_REQUEST['product_name']?>"><br><br>
		
		<input type="submit" name="new_product" value="Добавить товар"><br><br>
	</form>
</div>
