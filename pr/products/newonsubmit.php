<?
session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');

function doNull($s){
	return ($s == '')?null:$s;
}

$stmt = $db->prepare("SELECT id FROM pr_products WHERE name = ? and id != ?");
$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['id']));
if($stmt->fetch())
	die('Товар с таким названием уже есть');

if (isset($_REQUEST['id'])) {
	$stmt = $db->prepare("UPDATE pr_products SET name = ?, ed_izm_id = ?, in_box = ?, min_kolvo = ? WHERE id = ?");
	if(!$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['ed_izm'], doNull($_REQUEST['in_box']), doNull($_REQUEST['min_kolvo']), $_REQUEST['id']))){
		echo 'Ошибка изменения товара'; print_r($stmt->errorInfo());
		exit();
	}
	if($_FILES['image']['name']){
		if ($_FILES['image']['size'] > 1048576)
			die('слишком большой размер файла');

		$pieces = explode(".", $_FILES['image']['name']);
		if(!in_array(mb_strtolower($pieces[count($pieces) - 1]), array('png', 'bmp', 'gif', 'jpg', 'jpeg')) )
			die('недопустимый тип файла');
		
		if($_FILES['image']['error'] != 0)
			die('ошибка при загрузке изображения');
		
		$photoFileName = '/pr/uploaded/'.$_FILES['image']['name'];
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$photoFileName))
			die('Файл с таким именем уже загружен');
		if(move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].$photoFileName)){
			$stmt = $db->prepare("UPDATE pr_products SET photo = ? WHERE id = ?");
			if(!$stmt->execute(array($photoFileName, $_REQUEST['id']))){
				echo 'Ошибка изменения фото товара'; print_r($stmt->errorInfo());
				exit();
			}			
		}
	}
	echo "<script>alert('Товар изменен');document.location.href='index.php';</script>";
} else {
	$stmt = $db->prepare("INSERT pr_products(name, ed_izm_id, in_box, min_kolvo) values(?, ?, ?, ?)");
	if(!$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['ed_izm'], doNull($_REQUEST['in_box']), doNull($_REQUEST['min_kolvo'])))){
		echo 'Ошибка добавления товара'; print_r($stmt->errorInfo());
		exit();
	}
	echo "<script>alert('Товар добавлен');document.location.href='index.php';</script>";
}

?>
