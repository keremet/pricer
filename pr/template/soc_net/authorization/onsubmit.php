<?
$stmt = $db->prepare("SELECT u.id, g.del_anothers_receipts
                      FROM ".DB_TABLE_PREFIX."users u
                        JOIN ".DB_TABLE_PREFIX."user_group g ON g.id = u.group_id
                      WHERE u.login = ? AND u.password = ?");
$login = trim(htmlspecialchars($_REQUEST['login']));
$stmt->execute(array($login, trim($_REQUEST['password'])));
$rowU = $stmt->fetch();
if(!$rowU)
	die("<script>alert('Ошибка: Неверный логин или пароль');</script>");

echo "<script>document.location.href='".
    (($login == "keremet")?"../cabinet/index.php":"../smart_form/").
    "';</script>";
$_SESSION['user']['id'] = $rowU['id'];
$_SESSION['user_del_anothers_receipts'] = $rowU['del_anothers_receipts'];
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
?>
