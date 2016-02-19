<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Продукты';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Продукты';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	<h1>Добавление продукта</h1>
	<?include('new/logic.php');
	//rp($_REQUEST)?>
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>