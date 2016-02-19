<?
$errors = array();
if($_REQUEST['password1'] != $_REQUEST['password2']){
	$errors[] = 'пароли не совпадают';
}

$query = "SELECT `id` FROM `".$GLOBALS['site_settings']['db']['tables']['users']."` WHERE `login` = {?}";
$id = $db->selectCell($query, array($_REQUEST['new_login']));
if($id){
	$errors[] = 'логин занят';
}

$id = false;
$query = "SELECT `id` FROM `".$GLOBALS['site_settings']['db']['tables']['users']."` WHERE `email` = {?}";
$id = $db->selectCell($query, array($_REQUEST['email']));
/*
метод selectCell Выбирает запись только если есть только одна запись, удовлетворяющая фильтру. Если записей несколько, не выбирается не одна.
*/
if($id){
	$errors[] = 'email занят';
}
/*
if(){
	$errors[] = 'пароль содержит недопустимые символы';
}
if(){
	$errors[] = 'пароль короче минимальной длины';
}
*/

if(count($errors) > 0){
	$alert = implode(", ", $errors);
	echo "<script>alert('Ошибка: ".$alert."');</script>";
}else{
	$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['users']." (name,login,password,text,email) VALUES ({?},{?},{?},{?},{?})";
	$user_id = $db->query($query, array(htmlspecialchars(trim($_REQUEST['name'])),trim(htmlspecialchars($_REQUEST['new_login'])),trim(htmlspecialchars($_REQUEST['password1'])),trim(htmlspecialchars($_REQUEST['text'])),trim(htmlspecialchars($_REQUEST['email']))));
	if($user_id){
		echo "<script>alert('Вы успешно зарегистрированы!'); document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/cabinet/';</script>";
		$_SESSION['user'] = array('id' => $user_id, 'name' => $_REQUEST['name'], 'login' => $_REQUEST['new_login'], 'text' => $_REQUEST['text'], 'email' => $_REQUEST['email']);
		if($_FILES['image']){
			$white_list = array('png', 'bmp', 'gif', 'jpg', 'jpeg');
			if(!is_array(LoadFile('image', $white_list, 1048576, $_SERVER['DOCUMENT_ROOT'].'/'.$GLOBALS['site_settings']['site_folder'].$GLOBALS['site_settings']['img_path']))){
				$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['images']." (path,alt,title,creator) VALUES ({?},{?},{?},{?})";
				$image_id = $db->query($query, array($GLOBALS['site_settings']['site_folder'].$GLOBALS['site_settings']['img_path'].$_FILES['image']['name'],'','',$_SESSION['user']['id']));
				if($image_id){
					$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['user_images']." (user,image,alt,title,main,creator) VALUES ({?},{?},{?},{?},{?},{?})";
					$image_rel_id = $db->query($query, array($user_id,$image_id,'','',1,$_SESSION['user']['id']));
					//rp(array($query, array($user_id,$image_id,'','',1,$_SESSION['user']['id'])));
				}
			}
		}
	}else
		echo "<script>alert('Неизвестная ошибка');</script>";
		//rp($_FILES); rp($_REQUEST);
}
?>