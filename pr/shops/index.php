<?
include '../template/header.php';
headerOut('Ценовичок - Магазины', 'Ценовичок - Магазины', 'Ценовичок - аналитика цен на товары', 'Киров, цены, продукты', '..', 'Магазины');
?>
	<h1>Магазины</h1>
	<table class="main"><tr><th>Название</th><th>Сеть</th><th>Город</th><th>Адрес</th></tr>
	<?
	foreach($db->query("SELECT name, network, town, address FROM pr_shops") as $v){
		echo '<tr><td>'.$v['name'].'</td><td>'.$v['network'].'</td><td>'.$v['town'].'</td><td>'.$v['address'].'</td></tr>';
	}
	?>
	</table>
	<?include('new/logic.php');?>
<?include('../template/footer.php');?>
