<pre><?//print_r($table)?></pre>
<!--table class="borders"><tr><th>Название</th><th>Адрес</th></tr-->
<?
//rp($table);
echo '<h2>'.$product['name'].'</h2>';
if($product['image']){
	echo '<div style=" height: 140px; width: 140px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">';
	echo '<a class="fancybox" href="'.$product['image'].'"><img style=" max-width: 140px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="'.$product['image'].'"></a>';
	echo '</div>';
}
		
echo '<div>';
foreach($product_props_array as $k => $v){
	echo '<b>'.$k.'</b><ol>';
	foreach($v as $k2 => $v2){
		echo '<li>'.$v2.'</li>';
	}
	echo '</ol>';
}
echo '</div>';
include('offers/list/logic.php');
include('offers/new/logic.php');

?>
<p><a href='http://<?=$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']?>/products/'>Назад к списку товаров</a></p>