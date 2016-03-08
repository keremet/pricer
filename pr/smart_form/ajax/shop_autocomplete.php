<?
include('../../template/connect.php');

function param2field($param){
	switch(substr($param, 5)){
		case 'name': return 'name';
		case 'network': return 'network';
		case 'town': return 'town';
		case 'address': return 'address';
		default: die('');
	}
}

if(strlen($_REQUEST['name']) == 0){
	die('');
}
$stmt = $db->prepare("SELECT id, name, network, town, address FROM pr_shops WHERE ".param2field($_REQUEST['var'])." like ?");
if(!$stmt->execute(array('%'.$_REQUEST['name'].'%'))){
	print_r($stmt->errorInfo());
	die('Ошибка исполнения запроса');	
}

while ($v = $stmt->fetch()){?>
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
?> 
