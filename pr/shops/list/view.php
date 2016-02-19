<?
//echo '<pre>'; print_r($table); echo '</pre>'
?>
<table class="main"><tr><th>Название</th><th>Сеть</th><th>Город</th><th>Адрес</th></tr>
<?foreach($table as $k => $v){
	echo '<tr><td>'.$v['name'].'</td><td>'.$v['network'].'</td><td>'.$v['town'].'</td><td>'.$v['address'].'</td></tr>';
}?>
</table>