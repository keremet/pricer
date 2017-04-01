<?
session_start();
include('../template/connect.php');

if($_REQUEST['name'] == 'text'){
	$stmt = $db->prepare("UPDATE pr_users SET text = ? WHERE id = ?");
	if(!$stmt->execute(array(trim(htmlspecialchars($_REQUEST['value'])), $_SESSION['user']['id'])))
		die('Ошибка обновления информации о себе');
}else{
	if(strlen($_REQUEST['value']) == 0)
		die('Пустое значение недопустимо');

	if($_REQUEST['name'] == 'login'){
		$stmt = $db->prepare("SELECT id FROM pr_users WHERE id != ? and login = ?");
		$stmt->execute(array($_SESSION['user']['id'], trim(htmlspecialchars($_REQUEST['value']))));
		if($stmt->fetch())
			die('логин занят');

		$stmt = $db->prepare("UPDATE pr_users SET login = ? WHERE id = ?");
		if(!$stmt->execute(array(trim(htmlspecialchars($_REQUEST['value'])), $_SESSION['user']['id'])))
			die('Ошибка обновления логина');
	}elseif($_REQUEST['name'] == 'email'){
/*		if (!preg_match("/^[\w]{1}[\w-\.]*@[\w-]+\.[a-z]{2,4}$/i",$_REQUEST['value']))
			die('Некорректный email');
		Проверка не корректная!!
		*/
		$stmt = $db->prepare("SELECT 1 FROM pr_users WHERE id != ? and email = ?");
		$stmt->execute(array($_SESSION['user']['id'], htmlspecialchars($_REQUEST['value'])));
		if($stmt->fetch())
			die('email занят');
		
		$stmt = $db->prepare("UPDATE pr_users SET email = ? WHERE id = ?");
		if(!$stmt->execute(array(trim(htmlspecialchars($_REQUEST['value'])), $_SESSION['user']['id'])))
			die('Ошибка обновления email');
	}elseif($_REQUEST['name'] == 'name'){
		$stmt = $db->prepare("UPDATE pr_users SET name = ? WHERE id = ?");
		if(!$stmt->execute(array(trim(htmlspecialchars($_REQUEST['value'])), $_SESSION['user']['id'])))
			die('Ошибка обновления Ф.И.О.');
	}
}
?>
