<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
include($GLOBALS['site_settings']['root_path'].'/template/header/invisible.php');
if(strlen($_REQUEST['name']) > 0){
	$query = "SELECT `id`, `name` FROM `".$GLOBALS['site_settings']['db']['tables']['products']."` WHERE `";
	$query .= substr($_REQUEST['var'], 8); 
	$query .= "` LIKE {?}";
	$table = $db->select($query, array('%'.$_REQUEST['name'].'%'));
	//echo $query;
	if(is_array($table)){
		foreach ($table as $k => $v){?>
			<div style="width: 100%; border: 1px solid black;">
				<a href="" onclick="product_select('<?=$v['id']?>', '<?=htmlspecialchars($v['name'], ENT_QUOTES)?>'); return false;" >
					<?$first = 0;
					if($v['name']){
						$first = 1;
						echo '<b>'.$v['name'].'</b>';
					}?>
				</a>
			</div>
		<?}
	}
}else{
	echo '';
}
?> 