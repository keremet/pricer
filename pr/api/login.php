<?php
include('../template/connect.php');
$stmt = $db->prepare("SELECT id FROM users where login = ? and password = ?");
$stmt->execute(array($_POST['login'], $_POST['password']));	
if($token = $stmt->fetchColumn()){
	echo "1";
}else{
	echo "0";
}
/*
 * sha256, токен 20 символов(буквы, цифры разного регистра)
 * Инфа о пользователях(ФИО)
 * */
?>
