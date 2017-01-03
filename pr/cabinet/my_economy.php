<?session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');

$stmt = $db->prepare(
"SELECT po.date_buy, po.price, po.amount, pr_products.name as Товар, pr_shops.name as Магазин
  , pr_getBasePrice(po.product, po.date_buy, ifnull(pr_products.in_box, 1)) price_b
FROM pr_product_offers po, pr_shops, pr_products
WHERE po.creator = ? and po.amount > 0
  and po.shop = pr_shops.id
  and po.product = pr_products.id
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
			<td><?=$v['Товар']?></td>
			<td><?=$v['Магазин']?></td>
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
