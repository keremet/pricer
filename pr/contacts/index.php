<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Контакты';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Контакты';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	<h1>Контакты</h1>
	<p>email для обратной связи - <a href="mailto:kirovprices@mail.ru">kirovprices@mail.ru</a></p>
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>