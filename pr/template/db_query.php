<?
mysql_connect("localhost", $GLOBALS['site_settings']['db']['user_name'], $GLOBALS['site_settings']['db']['user_password']);
mysql_set_charset( 'utf8' );
mysql_select_db($GLOBALS['site_settings']['db']['db_name']) or die(mysql_error());
		
$query = "SELECT login FROM users WHERE name = 'users'";
$rs = mysql_query($query);
if($rs){
	while($row = mysql_fetch_array($rs)) {
		echo '<pre>'; print_r($row ); echo '</pre>';
	}
}
?>



SELECT `id`, `name` FROM `product_props` WHERE `name` IN ('Упаковка','Количество штук в упаковке')