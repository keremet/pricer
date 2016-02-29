<?
function rp($value){
	echo '<pre>'; print_r($value); echo '</pre>';
}

function LoadFile_MakeNewName($short_name, $ext, $target_folder){ //рекурсивная функция, подбирающая новое имя для уже существующего файла
	if(file_exists($target_folder.$short_name.'.'.$ext)){
		return LoadFile_MakeNewName($short_name.'_', $ext, $target_folder);
	}else return $short_name.'.'.$ext;
}

function LoadFile_FileExists(&$errors, &$variable, $short_name, $ext, $target_folder){ // функция, срабатывающая, если файл с таким именем уже есть
	//return $errors;
	//$errors[] = 'файл уже существует';
	$variable['name'] = LoadFile_MakeNewName($short_name.'_', $ext, $target_folder);
	return false;
}

function LoadFile($variable, $allowed_types, $max_size, $target_folder){
	$errors = array();
	if(!$_FILES[$variable]['name']){
		$errors[] = 'нет файла';
	}else{
		$size = $_FILES[$variable]['size'];
		if ($size > $max_size){
			$errors[] = 'слишком большой размер файла';
		}
		$pieces = explode(".", $_FILES[$variable]['name']);
		if(!in_array(mb_strtolower($pieces[1]), $allowed_types) ){
			$errors[] = 'недопустимый тип файла';
		}
		if(file_exists($target_folder.$_FILES[$variable]['name'])){
			LoadFile_FileExists($errors, $_FILES[$variable], $pieces[0], $pieces[1], $target_folder);
		}
		if($_FILES[$variable]['error'] != 0){
			$errors[] = 'неизвестная ошибка';
		}
	}
	if(!count($errors) > 0){
		$uploadfile = $target_folder.$_FILES[$variable]['name'];
		if(move_uploaded_file($_FILES[$variable]['tmp_name'], $uploadfile)){
			return $target_folder.$_FILES[$variable]['name'];
		}
	}else{
		return $errors;
	}
}

function Img_Select($arFilter, $arSelect = false){
	global $db;
	if(is_array($arFilter['user'])){ // если фильтр по нескольким юзерам

	}elseif($arFilter['user']){ // если фильтр по одному юзеру
		if($arFilter['main']){ // eсли выбрать надо только главное фото
			$query = "SELECT `id`, `image` FROM `pr_user_images` WHERE `user` = {?} AND `main` = {?}";
			$filter = array($arFilter['user'], $arFilter['main']);
			$ar_rel = $db->selectRow($query, $filter);
			$query = "SELECT ";
			if(is_array($arSelect)){
				$first = 1;
				foreach ($arSelect as $k => $v){
					if($first == 1) $first = 0;
					else $query .= ', ';
					$query .= '`'.$v.'`';
				}
			}else $query .= '*';
			$query .= "FROM `pr_images` WHERE `id` = {?}";
			$ar_image = $db->selectRow($query, array($ar_rel['image']));
		}else{
			$query = "SELECT `id`, `image` FROM `pr_user_images` WHERE `id` = {?}";
			$ar_rel = $db->select($query, array($arFilter));
		}
	}
	if(is_array($arFilter['product'])){ // если фильтр по нескольким юзерам

	}elseif($arFilter['product']){ // если фильтр по одному юзеру
		if($arFilter['main']){ // eсли выбрать надо только главное фото
			$query = "SELECT `id`, `image` FROM `pr_product_images` WHERE `product` = {?} AND `main` = {?}";
			$filter = array($arFilter['product'], $arFilter['main']);
			$ar_rel = $db->selectRow($query, $filter);
			$query = "SELECT ";
			if(is_array($arSelect)){
				$first = 1;
				foreach ($arSelect as $k => $v){
					if($first == 1) $first = 0;
					else $query .= ', ';
					$query .= '`'.$v.'`';
				}
			}else $query .= '*';
			$query .= "FROM `pr_images` WHERE `id` = {?}";
			$ar_image = $db->selectRow($query, array($ar_rel['image']));
		}else{
			$query = "SELECT `id`, `image` FROM `pr_product_images` WHERE `id` = {?}";
			$ar_rel = $db->select($query, array($arFilter));
		}
	}
	return $ar_image;
}
?>
