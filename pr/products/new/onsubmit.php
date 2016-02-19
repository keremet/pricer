<?
unset($table);
$errors = array();

$query = "SELECT `id` FROM `".$GLOBALS['site_settings']['db']['tables']['products']."` WHERE `name` = {?}";
$id = $db->selectCell($query, array($_REQUEST['product_name']));
if($id){
	$errors[] = 'Товар с таким названием уже есть';
}
if(count($errors) <= 0){
	//	<Проверка свойств на существование
	//rp($_REQUEST['PROP_NAME']);
	//if(is_array($_REQUEST['PROP_NAME'])){
		$prop_names = array_unique($_REQUEST['PROP_NAME']);
		//rp($prop_names);
		$prop_names = array_filter ($prop_names);
		//rp($prop_names);
		//<Удаляем свойства, у которых нет непустых значений
		foreach($prop_names as $k => $v){
			$arr = array_filter ($_REQUEST['PROP_VALUE'][$k]);
			if(count($arr) == 0){
				unset($prop_names[$k]);
			}
		}
		//rp($prop_names);
		//>
		//$prop_names_str = implode(",", $prop_names);
		//echo '$prop_names_str: '.$prop_names_str;
		if((is_array($prop_names)) && (count($prop_names) > 0)){
			$query = "SELECT `id`, `name` FROM `".$GLOBALS['site_settings']['db']['tables']['product_props']."` WHERE `name` IN (";
			$ipn = 0;
			//	Добавляем вопросики за каждое свойство.
			foreach($prop_names as $k => $v){
				if($ipn != 0){
					$query .= ", ";
				}else $ipn = 1;
				$query .= "{?}";
			}
			//	/Добавляем вопросики за каждое свойство.
			$query .= ")";
			//echo '<br>query: '.$query.'<br>';
			// На заметку: для использования в классе $table = $db->select($query, $new_arr); ключи массива $new_arr должны быть только стандартные: 0, 1, 2 и т.д.
			$new_arr = array_values($prop_names); //сбрасываем ключи массива. меняем на 0, 1 и т.д.
			$pres_props_array = $db->select($query, $new_arr);
			//echo '<br>Существующие свойства :<br>';
			//rp($pres_props_array);
		}
	//}

	$pres_props = $pres_props_array;
	//	Проверка свойств на существование>
	// <Получаем список введённых сущ. зн-й сущ. свойств
	$no_pres_props = $prop_names;
	$pres_props_ids = array(); //список id существующих свойств
	if(is_array($pres_props_array) && is_array($prop_names)){
		foreach ($pres_props_array as $k => $v){
			$key = array_search($v['name'], $prop_names); 
			$pres_props_ids[$key] = $v['id'];
			unset($no_pres_props[$key]);
		}
	}
	/*echo '<br>$pres_props_ids: ';
	rp($pres_props_ids);
	echo '<br>$no_pres_props: ';
	rp($no_pres_props);*/

	//<добавляем в базу несуществующие св-ва
	foreach($no_pres_props as $k => $v){
		$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['product_props']." (name,creator) VALUES ({?},{?})";
		$prop_id = $db->query($query, array($v,$_SESSION['user']['id']));
		//echo '';
		if($prop_id){
			$pres_props_array[] = array('id' => $prop_id, 'name' => $v);
		}
	}
	//>
	/*echo '<br>$pres_props_array: <br>';
	rp($pres_props_array);*/

	$query_arr = array();
	$query = "SELECT `id`, `name`, `property` FROM `".$GLOBALS['site_settings']['db']['tables']['product_props_values']."` WHERE ";
	$i = 0;
	foreach($pres_props_ids as $k => $v){
		$query_arr[] = $v;
		if($i != 0){
			$query .= " OR";
		}else $i = 1;
		$query .= " (`property` = {?} AND `name` IN (";
		$i2 = 0;
		foreach($_REQUEST['PROP_VALUE'][$k] as $k2 => $v2){
			if(strlen($v2) > 0){
				$query_arr[] = $v2;
				if($i2 != 0){
					$query .= ", ";
				}else $i2 = 1;
				$query .= "{?}";
			}
		}
		$query .= "))";
	}
	/*echo '<br>query: '.$query.'<br>';
	echo '<br>$query_arr: ';
	rp($query_arr);
	echo '<br>';*/

	$pres_props_vals_array = $db->select($query, $query_arr);
	if(!is_array($pres_props_vals_array)){
		$pres_props_vals_array = array();
	}
	/*echo '<br>Существующие значения свойств: <br>';
	rp($pres_props_vals_array);*/
	$no_pres_props_vals_array = array();
	if(is_array($_REQUEST['PROP_VALUE'])){
		foreach($_REQUEST['PROP_VALUE'] as $k => $v){
			if($prop_names[$k]){
				$prop_name = $prop_names[$k];
				if(is_array($pres_props_array)) {
					foreach ($pres_props_array as $k2 => $v2){
						if($v2['name'] == $prop_name)
						$prop_id = $v2['id'];
					}
					foreach ($v as $k2 => $v2){
						if(count($v2) > 0){
							$Y = 0;
							if(is_array($pres_props_vals_array)){
								foreach($pres_props_vals_array as $k3 => $v3){
									if(($v3['property'] == $prop_id) && ($v3['name'] == $v2)){
										$Y = 1;
									}
								}
								if($Y == 0){
									$no_pres_props_vals_array[] = array('property' => $prop_id, 'name' => $v2);
								}
							}
						}
					}
				}
			}
		}
	}
	/*echo '<br>Не существующие значения свойств: <br>';
	rp($no_pres_props_vals_array);*/
	 //<добавляем в БД несущ. зн-я св-в
	if((is_array($no_pres_props_vals_array)) && (count($no_pres_props_vals_array) > 0)){
		foreach ($no_pres_props_vals_array as $k => $v){
			if($v['name']){
				$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['product_props_values']." (name,property,creator) VALUES ({?},{?},{?})";
				$val_id = $db->query($query, array($v['name'],$v['property'],$_SESSION['user']['id']));
				//echo '';
				if($val_id){
					$pres_props_vals_array[] = array('id' => $val_id, 'name' => $v['name'], 'property' => $v['property']);
				}
			}
		}
	}
	/*echo '<br>Существующие значения свойств после добавления: <br>';
	rp($pres_props_vals_array);*/
	//>
	$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['products']." (name,creator) VALUES ({?},{?})";
	$product_id = $db->query($query, array($_REQUEST['product_name'],$_SESSION['user']['id']));
	if($product_id){
		if(is_array($pres_props_vals_array)){
			foreach($pres_props_vals_array as $k => $v){
				$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['product_props_rel']." (product,property,value,creator) VALUES ({?},{?},{?},{?})";
				//echo '$query'.$query.'<br>';
				$rel_id = $db->query($query, array($product_id,$v['property'],$v['id'],$_SESSION['user']['id']));
			}
		}
		if($_FILES['image']){
			$white_list = array('png', 'bmp', 'gif', 'jpg', 'jpeg');
			if(!is_array(LoadFile('image', $white_list, 1048576, $_SERVER['DOCUMENT_ROOT'].'/'.$GLOBALS['site_settings']['site_folder'].$GLOBALS['site_settings']['img_path']))){
				$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['images']." (path,alt,title,creator) VALUES ({?},{?},{?},{?})";
				$image_id = $db->query($query, array($GLOBALS['site_settings']['site_folder'].$GLOBALS['site_settings']['img_path'].$_FILES['image']['name'],'','',$_SESSION['user']['id']));
				if($image_id){
					$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['product_images']." (product,image,alt,title,main,creator) VALUES ({?},{?},{?},{?},{?},{?})";
					$image_rel_id = $db->query($query, array($product_id,$image_id,'','',1,$_SESSION['user']['id']));
					//rp(array($query, array($user_id,$image_id,'','',1,$_SESSION['user']['id'])));
				}
			}
		}
		/*if($_REQUEST['product_type']){
			$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['product-props_relation']." (product,property,value,creator) VALUES ({?},{?},{?},{?})";
			$rel_id = $db->query($query, array($product_id,1,$_REQUEST['product_type'],$_SESSION['user']['id']));
		}
		if($_REQUEST['ves_upakovki']){
			$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['product-props_relation']." (product,property,value,creator) VALUES ({?},{?},{?},{?})";
			$rel_id = $db->query($query, array($product_id,1,$_REQUEST['product_type'],$_SESSION['user']['id']));
		}*/
	}
}
if(count($errors) > 0){
	$alert = implode(", ", $errors);
	echo "<script>alert('Ошибка: ".$alert."');</script>";
}else{
	//$query = "INSERT INTO ".$GLOBALS['site_settings']['db']['tables']['products']." (name,address,user) VALUES ({?},{?},{?})";
	//$product_id = $db->query($query, array($_REQUEST['shop_name'],$_REQUEST['shop_address'],$_SESSION['user']['id']));
	if($product_id){
		echo "<script>alert('Товар добавлен!');</script>"; 
		//echo "document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/products/';</script>";
	}else{
		echo "<script>alert('Неизвестная ошибка');</script>";
	}
}
?>