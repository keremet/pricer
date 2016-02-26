<?
include('options.php');
$query = "SELECT `id`, `shop`, `creator`, `price`, `date_buy` FROM `pr_product_offers` WHERE `product` = {?}";
$product_id = (int) $_REQUEST['detail'];
$table_offers = $db->select($query, array($product_id));

$shops_ids = array();
if(is_array($table_offers)){ 
	foreach($table_offers as $k => $v){
		//<приведение даты к нужному формату
		$pieces = explode("-", $v['date_buy']);
		$table_offers[$k]['date_buy'] = $pieces[2].'/'.$pieces[1].'/'.$pieces[0];
		//>приведение даты к нужному формату
		if(!in_array($v['shop'], $shops_ids)){
			$shops_ids[] = $v['shop'];
		}
	}
}

$query = "SELECT `id`, `name` FROM `pr_shops` WHERE `id` IN (";
$i = 0;
foreach($shops_ids as $k => $v){
	if($i != 0){
		$query .= ", ";
	}else $i = 1;
	$query .= "{?}";
}
$query .= ")";
$shops_array = $db->select($query, $shops_ids);
include('view.php');
?>