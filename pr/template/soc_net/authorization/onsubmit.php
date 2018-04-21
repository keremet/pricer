<?
$errors = array();
$stmt = $db->prepare("SELECT id FROM ".DB_TABLE_PREFIX."users WHERE login = ? AND password = ?");
$login = trim(htmlspecialchars($_REQUEST['login']));
$stmt->execute(array($login, trim($_REQUEST['password'])));
if(!($user_id = $stmt->fetchColumn())){
	$errors[] = 'Неверный логин или пароль';
}
if(count($errors) > 0){
	$alert = implode(", ", $errors);
	echo "<script>alert('Ошибка: ".$alert."');</script>";
}else{
	echo "<script>document.location.href='".
		(($login == "keremet")?"../cabinet/index.php":"../smart_form/").
		"';</script>";
	$_SESSION['user']['id'] = $user_id;
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
