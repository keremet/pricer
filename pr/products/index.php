<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Продукты';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Продукты';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	<h1>Продукты</h1>
	<?if(!$_REQUEST['detail']){
		foreach($db->query("SELECT id, name, creator, photo, date_change FROM pr_products order by name") as $v){ ?>
			<div style="border: 2px solid #AAAAAA; margin: 10px; padding: 10px; display: inline-block; vertical-align: top; width: 200px;">
			<?if($v['photo']){?>
				<div style="height: 200px; width: 200px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">
					<a class="fancybox" href="<?=$v['photo']?>">
						<img style=" max-width: 200px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="<?=$v['photo']?>">
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
			</div>
		<?}
		if($_SESSION['user']['id']){?>
			<form action="new.php" method="get">
				<button type="submit">Добавить продукт</button>
			</form>
		<?}
	}else{
		$stmt = $db->prepare("SELECT name, photo FROM pr_products WHERE id = ?");
		$stmt->execute(array($_REQUEST['detail']));
		if(!($product = $stmt->fetch())){
			die("<script>alert('Неверный адрес страницы!'); document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/products/';</script>");
		}


		echo '<h2>'.$product['name'].'</h2>';
		if($product['photo']){
			echo '<div style=" height: 140px; width: 140px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">';
			echo '<a class="fancybox" href="'.$product['photo'].'"><img style=" max-width: 140px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="'.$product['photo'].'"></a>';
			echo '</div>';
		}

		?>
		<p><a href='http://<?=$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']?>/products/'>Назад к списку товаров</a></p>
		<?
	}
	?>
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>
