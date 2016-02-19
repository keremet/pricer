<?
include('options.php');

$table = $db->select("SELECT id, name, creator, text, photo, date_change FROM ".$GLOBALS['site_settings']['db']['tables']['products'], array());
foreach($table as $k => $v){
	$ar_image = Img_Select(array('product' => $v['id'], 'main' => 1, array('path')));
	$table[$k]['image'] = $ar_image['path'];
}
$users_query = array();
/*foreach($table as $k => $v){
	if((!$_SESSION['users_props'][$v['user']]) && (!in_array($v['user'], $users_query))){
		$users_query[] = $v['user'];
	}
}*/
if(count($users_query) > 0){
	$query = "SELECT `name` FROM `".$GLOBALS['site_settings']['db']['tables']['users']."` WHERE `id` = {?}";
}
?>
<pre><?//print_r($users_query)?></pre>
<?
include('view.php');
?>
