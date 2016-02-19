<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Новости';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Новости';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты, новости';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	Новости
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>