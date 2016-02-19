<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
include($GLOBALS['site_settings']['root_path'].'/template/header/invisible.php');
//print_r($GLOBALS);
//print_r($_REQUEST);
if($_REQUEST['name'] == 'text'){
	$query = "UPDATE `".$GLOBALS['site_settings']['db']['tables']['users']."` SET `text`={?} WHERE `id` = {?}";
	$id = $db->query($query, array(trim(htmlspecialchars($_REQUEST['value'])), $_SESSION['user']['id']));
}elseif(strlen($_REQUEST['value']) > 0){
	if($_REQUEST['name'] == 'login'){
		$query = "SELECT `id` FROM `".$GLOBALS['site_settings']['db']['tables']['users']."` WHERE `login` = {?}";
		$id = $db->selectCell($query, array(trim(htmlspecialchars($_REQUEST['value']))));
		if($id){
			echo 'логин занят';
		}else{
			$query = "UPDATE `".$GLOBALS['site_settings']['db']['tables']['users']."` SET `login` = {?} WHERE `id` = {?}";
			$id = $db->query($query, array(trim(htmlspecialchars($_REQUEST['value']), $_SESSION['user']['id'])));
		}
	}elseif($_REQUEST['name'] == 'email'){
		if (!preg_match("/^[\w]{1}[\w-\.]*@[\w-]+\.[a-z]{2,4}$/i",$_REQUEST['value'])){
			echo 'Некорректный email';
		}else{
			$query = "SELECT `id` FROM `".$GLOBALS['site_settings']['db']['tables']['users']."` WHERE `".$_REQUEST['name']."` = {?}";
			$id_email = $db->selectCell($query, array(htmlspecialchars($_REQUEST['value'])));
			/*
			метод selectCell Выбирает запись только если есть только одна запись, удовлетворяющая фильтру. Если записей несколько, не выбирается не одна.
			*/
			if($id_email){
				echo 'email занят';
			}else{
				$query = "UPDATE `".$GLOBALS['site_settings']['db']['tables']['users']."` SET `email` = {?} WHERE `id` = {?}";
				$id = $db->query($query, array(trim(htmlspecialchars($_REQUEST['value']), $_SESSION['user']['id'])));
			}
		}
	}elseif($_REQUEST['name'] == 'name'){
		$query = "UPDATE `".$GLOBALS['site_settings']['db']['tables']['users']."` SET `name`={?} WHERE `id` = {?}";
		$id = $db->query($query, array(trim(htmlspecialchars($_REQUEST['value']), $_SESSION['user']['id'])));
	}
}else{
	echo 'Пустое значение недопустимо';
}
//print_r($_REQUEST);
?>