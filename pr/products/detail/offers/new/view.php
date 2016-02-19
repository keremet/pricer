<?if($GLOBALS['site_settings']['current_address'] != $GLOBALS['site_settings']['site_folder'].'/products/'){?>
	<p><a class="fancybox" href="#new_offer_form">Добавить новое предложение</a></p>
<?}?>
<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/input_calendar/tcal.js"></script>
<link rel="stylesheet" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/input_calendar/tcal.css"/>
<div id="new_offer_form" style="width:400px;display: none;"><!--style="display: none;"-->
	<h2>Добавить предложение</h2>
	<form action="" method="post">
		Товар : 
		<b><big id="offer_new_product_name"><?=$product['name']?></big></b><br><br>
		Цена* :<br>
		<input required type="number" name="price" step="0.01" placeholder="Цена" value="<?=$_REQUEST['price']?>"><br><br>
		Магазин* :<br>
		<select name="shop">
			<?foreach($shops_table as $k => $v){
				$shop_name = '';
				if($v['name']) $shop_name .= $v['name']; else $shop_name .= '...';
				$shop_name .= ' - ';
				if($v['network']) $shop_name .= $v['network']; else $shop_name .= '...';
				$shop_name .= ' - ';
				if($v['town']) $shop_name .= $v['town']; else $shop_name .= '...';
				$shop_name .= ' - ';
				if($v['address']) $shop_name .= $v['address']; else $shop_name .= '...';
				echo '<option value="'.$v['id'].'">'.$shop_name.'</option>';
			}?>
		</select>
		<br><br>
		Дата покупки в формате "дд.мм.гггг"* :<br>
		<input type="text" class="tcal" name="date_buy" value="<?=date("d/m/Y")?>"><br><br>
		<input type="submit" name="new_offer" value="Добавить предложение" style="bottom: 0; position: absolute;"><br><br>
		<input id="offer_new_product_id" type="hidden" name="product" value="<?=$_REQUEST['detail']?>">
	</form>
</div>