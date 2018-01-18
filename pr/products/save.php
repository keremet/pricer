<?session_start();

define('PRODUCTS_TABLE_NAME', 'pr_consumption_clsf');
define('UPLOADS_FOLDER_PATH', 'pricer/uploaded/');

header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null){
	echo json_encode(array('result' => 'Требуется авторизация', 'error' => '1'));
	die();
}
function doKolvo($s){
	if($s == '')
		return null;
	return str_replace(',', '.', $s);
}

$stmt = $db->prepare("SELECT id FROM " . PRODUCTS_TABLE_NAME . " WHERE name = ? and id != ?");
$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['id']));
if($stmt->fetch()){
	echo json_encode(array('result' => 'Товар с таким названием уже есть', 'error' => '1'));
	die();
}
if (isset($_REQUEST['id'])) {
	$stmt = $db->prepare("UPDATE " . PRODUCTS_TABLE_NAME . " SET name = ?, ed_izm_id = ?, in_box = ? WHERE id = ?");
	if(!$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['ed_izm'], doKolvo($_REQUEST['in_box']), $_REQUEST['id']))){
		echo json_encode(array('result' => 'Ошибка изменения товара.', 'error' => '1'));
		//echo 'Ошибка изменения товара'; print_r($stmt->errorInfo());
		exit();
	}
	if($_FILES['image']['name']){
		if ($_FILES['image']['size'] > 1048576){
			echo json_encode(array('result' => 'Слишком большой размер файла', 'error' => '1'));
			die();
		}

		$pieces = explode(".", $_FILES['image']['name']);
		if(!in_array(mb_strtolower($pieces[count($pieces) - 1]), array('png', 'bmp', 'gif', 'jpg', 'jpeg')) ){
			echo json_encode(array('result' => 'Недопустимый тип файла', 'error' => '1'));
			die();
		}
		if($_FILES['image']['error'] != 0){
			echo json_encode(array('result' => 'Ошибка при загрузке изображения', 'error' => '1'));
			die();
		}
		$photoFileName = UPLOADS_FOLDER_PATH . $_FILES['image']['name'];
		$fullPath = $_SERVER['DOCUMENT_ROOT'].$photoFileName;
		if(file_exists($fullPath)){
			function on_file_exist($path){
				$result = array();
				$pieces = explode(".", $path);
				$ext = array_pop($pieces);
				$pieces[0] = implode('.', $pieces);
				$pieces[1] = $ext;
				$pieces2 = explode("_", $pieces[0]);
				$num =  array_pop($pieces2);
				//echo 'окончание названия существующего файла'.$num.'<br>';
				if(ctype_digit($num)){
					//echo 'окончание цифровое <br>';
					$new_num = (int) $num + 1;
					$new_path = implode('_', $pieces2).'_'.$new_num.'.'.$pieces[1];
					//echo 'новое название файла: '.$new_path.'<br>';
				}else{
					$new_path = $pieces[0].'_0.'.$pieces[1];
				}
				if (file_exists($new_path)) {
					//echo 'новое название файла занято <br>';
					$result = on_file_exist($new_path);
				}else{
					//echo 'новое название файла свободно <br>';
					$result = $new_path;
				}
				return $result;
			}
			$fullPath = on_file_exist($fullPath);
			//echo json_encode(array('result' => 'Файл с таким именем уже загружен', 'error' => '1'));
			//die();
		}
		if(move_uploaded_file($_FILES['image']['tmp_name'], $fullPath)){
			$pieces = explode("/", $fullPath);
			$photoFileName = array_pop($pieces);
			$stmt = $db->prepare("UPDATE " . PRODUCTS_TABLE_NAME . " SET photo = ? WHERE id = ?");
			if(!$stmt->execute(array($photoFileName, $_REQUEST['id']))){
				echo json_encode(array('result' => 'Ошибка изменения фото товара.', 'error' => '1'));
				//echo 'Ошибка изменения фото товара'; print_r($stmt->errorInfo());
				exit();
			}			
		}
		//TODO: Дописать удаление предыдущей картинки
	}
	$result = array('result' => 'Товар успешно изменён', 'name' => $_REQUEST['product_name'], 'id' => $db->lastInsertId(), 'error' => '0');
	if($_FILES['image']['name'])
		$result['photo'] = '/pricer/uploaded/'.$photoFileName;
	echo json_encode($result);
	exit();
} else {
	$stmt = $db->prepare("INSERT INTO " . PRODUCTS_TABLE_NAME . " (name, ed_izm_id, in_box, id_hi, creator) values(?, ?, ?, ?, ?)");
	if(!$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['ed_izm'], doKolvo($_REQUEST['in_box']), $_REQUEST['id_hi'], $_SESSION['user']['id']))){
		echo json_encode(array('result' => 'Ошибка добавления товара.', 'error' => '1'));
		//echo 'Ошибка добавления товара'; print_r($stmt->errorInfo());
		exit();
	}
	//echo "<script>alert('Товар добавлен');document.location.href='index.php';</script>";
	$result = array('result' => 'Товар добавлен', 'name' => $_REQUEST['product_name'], 'id' => $db->lastInsertId(), 'error' => '0');
	echo json_encode($result);
	exit();
}?>
