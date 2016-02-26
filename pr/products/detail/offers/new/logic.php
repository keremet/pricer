<?
if($_SESSION['user']['id']){
	include('options.php');
	$query = "SELECT `id`, `name`, `creator`, `text`, `network`, `town`, `address`, `photo`, `date_change` FROM `pr_shops`";
	$shops_table = $db->select($query, array());
	if($_REQUEST['new_offer'] == 'Добавить предложение')
		include('onsubmit.php');
	include('view.php');
}
?>
