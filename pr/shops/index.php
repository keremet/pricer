<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Магазины';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Магазины';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	<h1>Магазины</h1>
	<table class="main"><tr><th>Название</th><th>Сеть</th><th>Город</th><th>Адрес</th></tr>
	<?
	foreach($db->query("SELECT name, network, town, address FROM pr_shops") as $v){
		echo '<tr><td>'.$v['name'].'</td><td>'.$v['network'].'</td><td>'.$v['town'].'</td><td>'.$v['address'].'</td></tr>';
	}
	?>
	</table>
	<?include('new/logic.php');?>
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>
