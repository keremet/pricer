<?
if($_SESSION['user']['id']){
	if($_REQUEST['new_shop'] == 'Добавить магазин')
		include('onsubmit.php');
	include('view.php');
}
?>