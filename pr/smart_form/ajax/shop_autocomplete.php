<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
include($GLOBALS['site_settings']['root_path'].'/template/header/invisible.php');

if(strlen($_REQUEST['name']) > 0){
	$query = "SELECT `id`, `name`, `network`, `town`, `address` FROM `pr_shops` WHERE `";
	$query .= substr($_REQUEST['var'], 5); 
	$query .= "` LIKE {?}";
	$table = $db->select($query, array('%'.$_REQUEST['name'].'%'));
	//echo $query;
	if(is_array($table)){
		foreach ($table as $k => $v){?>
			<div style="width: 100%; border: 1px solid black;">
				<a href="" onclick="shop_select('<?=$v['id']?>', '<?=htmlspecialchars($v['name'], ENT_QUOTES)?>', '<?=htmlspecialchars($v['network'], ENT_QUOTES)?>', '<?=htmlspecialchars($v['town'], ENT_QUOTES)?>', '<?=htmlspecialchars($v['address'], ENT_QUOTES)?>'); return false;" >
					<?$first = 0;
					if($v['name']){
						$first = 1;
						echo '<b>'.$v['name'].'</b>';
					}
					if($v['network']){
						if($first == 1){
							echo ', ';
						}
						$first = 1;
						echo '<span class="network">'.$v['network'].'</span>';
					}
					if($v['town']){
						if($first == 1){
							echo ', ';
						};
						$first = 1;
						echo '<span class="town">'.$v['town'].'</span>';
					}				
					if($v['address']){
						if($first == 1){
							echo ', ';
						}
						$first = 1;
						echo '<span class="address">'.$v['address'].'</span>';
					}?>
				</a>
			</div>
		<?}
	}
}else{
	echo '';
}
?> 