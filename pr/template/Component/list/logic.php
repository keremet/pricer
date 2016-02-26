<?
include('options.php');
$query = "SELECT `id`, `name`, `user`, `text`, `photo`, `date_change` FROM `pr_shops`";
$table = $db->select($query, array());
$users_query = array();
foreach($table as $k => $v){
	if((!$_SESSION['users_props'][$v['user']]) && (!in_array($v['user'], $users_query))){
		$users_query[] = $v['user'];
	}
}
if(count($users_query) > 0){
	$query = "SELECT `name` FROM `pr_users` WHERE `id` = {?}";
}
?>
<pre><?print_r($users_query)?></pre>
<?
include('view.php');
?>
