<pre><?//print_r($table)?></pre>
<!--table class="borders"><tr><th>Название</th><th>Адрес</th></tr-->
<?foreach($table as $k => $v){?>
	<div style="border: 2px solid #AAAAAA; margin: 10px; padding: 10px; display: inline-block; vertical-align: top; width: 200px;">
	<?if($v['image']){?>
		<div style="height: 200px; width: 200px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">
			<a class="fancybox" href="<?=$v['image']?>">
				<img style=" max-width: 200px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="<?=$v['image']?>">
			</a>
		</div>
	<?}else{?>
		<div style="height: 200px; width: 200px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">
			<a href="<?=$GLOBALS['site_settings']['site_folder'].'/products/?detail='.$v['id']?>">
				<img style="max-width: 200px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="<?=$GLOBALS['site_settings']['site_folder']?>/images/no_photo.gif">
			</a>
		</div>
	<?}?>
		<a href="<?=$GLOBALS['site_settings']['site_folder'].'/products/?detail='.$v['id']?>">
			<h2 style="word-break: break-all;"><?=$v['name']?></h2>
		</a>
		<?if($_SESSION['user']['id']){?>
			<a class="fancybox" href="#new_offer_form" onclick="$('#offer_new_product_name').html('<?=htmlspecialchars($v['name'], ENT_QUOTES)?>'); $('#offer_new_product_id').attr('value', '<?=$v['id']?>');">
				<img style="width: 50px;" title="Добавить новую цену" src="<?=$GLOBALS['site_settings']['site_folder']?>/images/price.jpg">
			</a>
		<?}?>
	</div>
<?}
include($_SERVER['DOCUMENT_ROOT'].'/'.$GLOBALS['site_settings']['site_folder'].'/products/detail/offers/new/logic.php');?>
<!--table-->