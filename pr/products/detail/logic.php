<?
include('options.php');
$product_id = (int) $_REQUEST['detail'];
$query = "SELECT `name` FROM `".$GLOBALS['site_settings']['db']['tables']['products']."` WHERE `id` = {?}";
$product = $db->selectRow($query, array($product_id));
if(!$product['name']){
	$errors[] = 'Неверный адрес страницы';
	echo "<script>alert('Неверный адрес страницы!'); document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/products/';</script>";
}
$ar_image = Img_Select(array('product' => $product_id, 'main' => 1, array('path')));
$product['image'] = $ar_image['path'];
// <получаем из БД значения свойств для данного товара
	$query = "SELECT `id`, `property`, `value`, `date_change` FROM `".$GLOBALS['site_settings']['db']['tables']['product_props_rel']."` WHERE `product` = {?}";
	//echo '$query: '.$query;
	$relations = $db->select($query, array($product_id));
// >
/*$users_query = array();
foreach($table as $k => $v){
	if((!$_SESSION['users_props'][$v['user']]) && (!in_array($v['user'], $users_query))){
		$users_query[] = $v['user'];
	}
}
if(count($users_query) > 0){
	$query = "SELECT `name` FROM `".$GLOBALS['site_settings']['db']['tables']['users']."` WHERE `id` = {?}";
}*/

/*foreach($table as $k => $v){
	
}*/
$props_id = array();
$vals_id = array();
foreach($relations as $k => $v){
	if(!in_array($v['property'], $props_id)){
		$props_id[] = $v['property'];
	}
	if(!in_array($v['value'], $vals_id)){
		$vals_id[] = $v['value'];
	}
}
$query = "SELECT `id`, `name` FROM `".$GLOBALS['site_settings']['db']['tables']['product_props']."` WHERE `id` IN (";
$i = 0;
//	Добавляем вопросики за каждое свойство.
foreach($props_id as $k => $v){
	if($i != 0){
		$query .= ", ";
	}else $i = 1;
	$query .= "{?}";
}
$query .= ")";
$props_array = $db->select($query, $props_id);

$query = "SELECT `id`, `name`, `property` FROM `".$GLOBALS['site_settings']['db']['tables']['product_props_values']."` WHERE `id` IN (";
$i = 0;
//	Добавляем вопросики за каждое значение свойств.
foreach($vals_id as $k => $v){
	if($i != 0){
		$query .= ", ";
	}else $i = 1;
	$query .= "{?}";
}
$query .= ")";
$vals_array = $db->select($query, $vals_id);

$product_props_array = array();
if(is_array($vals_array)){
	foreach($vals_array as $k => $v){
		$key = '';
		if(is_array($props_array)){
			foreach($props_array as $k2 => $v2){
				if($v2['id'] == $v['property']){
					$key = $k2;
				}
			}
		}
		$product_props_array[$props_array[$key]['name']][] = $v['name'];
	}
}
//rp($product_props_array);
?>
<pre><?//print_r($users_query)?></pre>
<?
include('view.php');
?>
