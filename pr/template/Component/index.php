<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Ценовичок - Продукты';
$GLOBALS['site_settings']['META']['TITLE'] = 'Ценовичок - Продукты';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Ценовичок - аналитика цен на товары';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	<h1>Продукты</h1>
	<?include('list/logic.php');?>
	<?include('new/logic.php');?>
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>