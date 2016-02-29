<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Продукты';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Продукты';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');
if($_SESSION['user']['id']==null)
	die('Требуется авторизация');
?>
	<h1>Добавление продукта</h1>
	<form action="newonsubmit.php" method="post" enctype = 'multipart/form-data'>
		Название* :<br>
		<input required type="text" name="product_name" placeholder="Название товара" value=""><br><br>
		Фото (изображение не больше 1 Мб):<br>
		<input type="file" name="image" /><br><br>
		<span><input type="radio" name="PROP_VALUE[0][]" checked="checked" onclick="$('#packing').css('display', '');" value="Упакованный">Упакованный</span>
		<span><input type="radio" name="PROP_VALUE[0][]" onclick="$('#packing').css('display', 'none');" value="Развесной">Развесной</span><br><br>
		<div id="packing">
			Вес упаковки:<br>
			<input type="text" name="PROP_VALUE[1][]" placeholder="Масса товара в упаковке (г.)" value=""><br><br>
			Количество штук в упаковке:<br>
			<input type="text" name="PROP_VALUE[2][]" placeholder="Количество штук в упаковке" value=""><br><br>
		</div>
		<br><br>
		<input type="submit" name="new_product" value="Добавить товар"><br><br>
		<input type="hidden" name="PROP_NAME[0]" value="Упаковка">
		<input type="hidden" name="PROP_NAME[1]" value="Масса товара в упаковке (г.)">
		<input type="hidden" name="PROP_NAME[2]" value="Количество штук в упаковке">
	</form>

<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>
