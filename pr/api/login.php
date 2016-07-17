<?php
include('../template/connect.php');
$stmt = $db->prepare("SELECT id FROM pr_users where login = ? and password = ?");
$stmt->execute(array($_POST['login'], $_POST['password']));	
if($token = $stmt->fetchColumn()){
	echo json_encode(array('token' => $token));
}else{
	echo json_encode(array('error' => 'User not found'));
}
/*
 * sha256, токен 20 символов(буквы, цифры разного регистра)
 * Инфа о пользователях(ФИО)
 * */
?>
