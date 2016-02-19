<pre><?//print_r($table)?></pre>
<?//rp($shops_array);?>
<?if((count($table_offers) > 0) && (is_array($table_offers))){?>
	<h2>Предложения товара</h2>
	<table class="main"><tr><th>Магазин</th><th>Цена</th><th>Дата</th></tr>
	<?foreach($table_offers as $k => $v){
		$shop_name = '';
		foreach($shops_array as $k2 => $v2){
			if($v2['id'] == $v['shop']) $shop_name = $v2['name'];
		}
		
		echo '<tr><td>'.$shop_name.'</td><td>'.$v['price'].'</td><td>'.$v['date_buy'].'</td></tr>';
	}
}?>
<table>
