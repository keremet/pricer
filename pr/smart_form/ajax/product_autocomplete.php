<?
include('../../template/connect.php');

if(strlen($_REQUEST['name']) == 0){
	die('');
}
$stmt = $db->prepare("SELECT id, name FROM pr_products WHERE name like ?");
if(!$stmt->execute(array('%'.$_REQUEST['name'].'%'))){
	print_r($stmt->errorInfo());
	die('Ошибка исполнения запроса');	
}

while ($v = $stmt->fetch()){?>
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

?> 
