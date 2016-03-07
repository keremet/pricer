<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Товары';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Товары';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	<h1>Товары</h1>
	<?
	foreach($db->query("SELECT id, name, photo FROM pr_products order by name") as $v){ ?>
		<div style="border: 2px solid #AAAAAA; margin: 10px; padding: 10px; display: inline-block; vertical-align: top; width: 200px;">
		<div style="height: 200px; width: 200px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">
		<?if($v['photo']){?>
				<a class="fancybox" href="<?=$v['photo']?>">
					<img style=" max-width: 200px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="<?=$v['photo']?>">
		<?}else{?>
				<a href="detail.php?id=<?=$v['id']?>">
					<img style="max-width: 200px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="../images/no_photo.gif">
		<?}?>
				</a>
		</div>
			<a href="detail.php?id=<?=$v['id']?>">
				<h2 style="word-break: break-all;"><?=$v['name']?></h2>
			</a>
		</div>
	<?}
	if($_SESSION['user']['id']){?>
		<form action="detail.php" method="get">
			<button type="submit">Добавить продукт</button>
		</form>
	<?}
include('../template/footer/index.php');?>
