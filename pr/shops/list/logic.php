<?
include('options.php');
$table = $db->select("SELECT id, name, creator, text, address, photo, network, town, date_change FROM ".$GLOBALS['site_settings']['db']['tables']['shops'], array());
if($table){
	$users_query = array();
	foreach($table as $k => $v){
		if((!$_SESSION['users_props'][$v['user']]) && (!in_array($v['user'], $users_query))){
			$users_query[] = $v['user'];
		}
	}
	if(count($users_query) > 0){
		$query = "SELECT `name` FROM `".$GLOBALS['site_settings']['db']['tables']['users']."` WHERE `id` = {?}";
	}
	include('view.php');
}
?>
