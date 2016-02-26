<?
$errors = array();
$query = "SELECT `id`, `name`, `login`, `text`, `email` FROM `pr_users` WHERE `login` = {?} AND `password` = {?}";
$row = $db->selectRow($query, array(trim(htmlspecialchars($_REQUEST['login'])), trim($_REQUEST['password'])));
if(!$row['id']){
	$errors[] = 'Неверный логин или пароль';
}
if(count($errors) > 0){
	$alert = implode(", ", $errors);
	echo "<script>alert('Ошибка: ".$alert."');</script>";
}else{
	echo "<script>alert('Вы успешно авторизованы!'); document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/';</script>";
	$_SESSION['user'] = $row;
	if($_REQUEST['remember'] == 'Y'){
		setcookie('user_login', trim(htmlspecialchars($_REQUEST['login'])), time() + 3600 * 24 * 30, '/');
		setcookie('user_password', trim($_REQUEST['password']), time() + 3600 * 24 * 30, '/');
	}else{
		setcookie('user_login', '', time() - 1, '/');
		setcookie('user_password', '', time() - 1, '/');
	}
	foreach($_REQUEST as $k => $v){
		unset($v);
	}
}

?>