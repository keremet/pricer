<?session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user']['id']==null){
	echo json_encode(array('result' => 'Требуется авторизация', 'error' => '1'));
	die();
}
if(!isset($_REQUEST['ed_izm'])){
	echo json_encode(array('result' => 'Не указана единица измерения', 'error' => '1'));
	die();
}

function doKolvo($s){
	if($s == '')
		return null;
	return str_replace(',', '.', $s);
}

function strOrNull($s){
	return ($s == '')?null:$s;
}

$stmt = $db->prepare("SELECT id FROM ".DB_TABLE_PREFIX."products WHERE name = ?".(isset($_REQUEST['id'])?" and (id != ?)":""));
if(isset($_REQUEST['id']))
	$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['id']));
else
	$stmt->execute(array($_REQUEST['product_name']));
if($stmt->fetch()){
	echo json_encode(array('result' => 'Товар с таким названием уже есть', 'error' => '1'));
	die();
}

if($_REQUEST['barcode'] != ''){
	$stmt = $db->prepare("SELECT id FROM ".DB_TABLE_PREFIX."products WHERE barcode = ?".(isset($_REQUEST['id'])?" and (id != ?)":""));
	if(isset($_REQUEST['id']))
		$stmt->execute(array($_REQUEST['barcode'], $_REQUEST['id']));
	else
		$stmt->execute(array($_REQUEST['barcode']));
	if($stmt->fetch()){
		echo json_encode(array('result' => 'Товар с таким штрихкодом уже есть', 'error' => '1'));
		die();
	}
}

if (isset($_REQUEST['id'])) {
	$stmt = $db->prepare("UPDATE ".DB_TABLE_PREFIX."products SET name = ?, ed_izm_id = ?, in_box = ?, barcode = ? WHERE id = ?");
	if(!$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['ed_izm'], doKolvo($_REQUEST['in_box']), strOrNull($_REQUEST['barcode']), $_REQUEST['id']))){
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
		$fullPath = $_SERVER['DOCUMENT_ROOT'].'/pricer/uploaded/'.$_FILES['image']['name'];
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
			$stmt = $db->prepare("UPDATE ".DB_TABLE_PREFIX."products SET photo = ? WHERE id = ?");
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
		$result['photo'] = $photoFileName;
	echo json_encode($result);
	exit();
} else {
	$stmt = $db->prepare("INSERT ".DB_TABLE_PREFIX."products(name, ed_izm_id, in_box, barcode, main_clsf_id, creator) values(?, ?, ?, ?, ?, ?)");
	if(!$stmt->execute(array($_REQUEST['product_name'], $_REQUEST['ed_izm'], doKolvo($_REQUEST['in_box']), strOrNull($_REQUEST['barcode']), $_REQUEST['main_clsf_id'], $_SESSION['user']['id']))){
		echo json_encode(array('result' => 'Ошибка добавления товара.', 'error' => '1'));
		//echo 'Ошибка добавления товара'; print_r($stmt->errorInfo());
		exit();
	}
	//echo "<script>alert('Товар добавлен');document.location.href='index.php';</script>";
	$result = array('result' => 'Товар добавлен', 'name' => $_REQUEST['product_name'], 'id' => $db->lastInsertId(), 'error' => '0');
	echo json_encode($result);
	exit();
}?>
