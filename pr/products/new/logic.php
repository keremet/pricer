<?
if($_SESSION['user']['id']){
	include('options.php');
	if($_REQUEST['new_product'] == 'Добавить товар')
		include('onsubmit.php');
	include('view.php');
}
?>