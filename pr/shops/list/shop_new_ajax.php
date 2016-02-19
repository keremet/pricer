<?
//echo '<pre>'; print_r($table); echo '</pre>'
?>
<table class="main"><tr><th>Название</th><th>Адрес</th></tr>
<?foreach($table as $k => $v){
	echo '<tr><td>'.$v['name'].'</td><td>'.$v['address'].'</td></tr>';
}?>
</table>