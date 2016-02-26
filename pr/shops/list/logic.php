<?
include('options.php');
$table = $db->select("SELECT id, name, creator, text, address, photo, network, town, date_change FROM pr_shops");
if($table){
	$users_query = array();
	foreach($table as $k => $v){
		if((!$_SESSION['users_props'][$v['user']]) && (!in_array($v['user'], $users_query))){
			$users_query[] = $v['user'];
		}
	}
	if(count($users_query) > 0){
		$query = "SELECT name FROM pr_users WHERE id = {?}";
	}
	include('view.php');
}
?>
