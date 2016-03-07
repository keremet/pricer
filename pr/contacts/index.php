<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Ценовичок - Контакты';
$GLOBALS['site_settings']['META']['TITLE'] = 'Ценовичок - Контакты';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Ценовичок - аналитика цен на товары';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	<h1>Контакты</h1>
	<p>email для обратной связи - <a href="mailto:kirovprices@mail.ru">kirovprices@mail.ru</a></p>
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>