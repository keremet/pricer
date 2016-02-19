<?
if($_SESSION['user']['id']){
	include('options.php');
	if($_REQUEST['new_shop'] == 'Добавить товар')
		include('onsubmit.php');
	include('view.php');
}
?>