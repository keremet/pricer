<?session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');

$stmt = $db->prepare(
"SELECT po.date_buy, po.price, po.amount, ".DB_TABLE_PREFIX."products.name as product_name, ".DB_TABLE_PREFIX."shops.name as shop_name
  , ".DB_TABLE_PREFIX."getBasePrice(po.product, po.date_buy, ifnull(".DB_TABLE_PREFIX."products.in_box, 1)) price_b
FROM ".DB_TABLE_PREFIX."fact po, ".DB_TABLE_PREFIX."shops, ".DB_TABLE_PREFIX."products
WHERE po.creator = ? and po.amount > 0
  and po.shop = ".DB_TABLE_PREFIX."shops.id
  and po.product = ".DB_TABLE_PREFIX."products.id
ORDER BY po.date_buy desc"
);
$stmt->execute(array($_SESSION['user']['id']));
?>
<html>
<body>
<table border=1>
	<thead>
		<tr>
			<th class="header">Дата покупки</th>
			<th class="header">Товар</th>
			<th class="header">Магазин</th>
			<th class="header">Цена</th>
			<th class="header">Баз. цена</th>
			<th class="header">Мин. цена</th>
			<th class="header">Кол-во</th>
			<th class="header">Экономия</th>
			<th class="header">Резерв экономии</th>
		</tr>
	</thead>
	<tbody>
	<?	
	$sum_econ = 0;
	foreach($stmt->fetchAll() as $k => $v){
		$econ = ($v['price_b'])?$v['amount']*($v['price_b'] - $v['price']):null;
		$sum_econ += $econ;
	?>
		<tr>
			<td><?=$v['date_buy']?></td>
			<td><?=$v['product_name']?></td>
			<td><?=$v['shop_name']?></td>
			<td><?=$v['price']?></td>
			<td><?=$v['price_b']?></td>
			<td>-</td>
			<td><?=$v['amount']?></td>			
			<td><?=$econ?></td>
			<td>-</td>
		</tr>
	<?}
	?>
	<tr>
		<td>
		<td>
		<td>
		<td>
		<td>
		<td>
		<td>Итого:</td>			
		<td><?=$sum_econ?></td>	
		<td>-</td>		
	</tr>
	</tbody>
</table>
</body>
</html>
