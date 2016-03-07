<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Ценовичок';
$GLOBALS['site_settings']['META']['TITLE'] = 'Ценовичок';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Ценовичок - аналитика цен на товары';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
	<?
	/*echo '<pre>'; print_r($db); echo '</pre>';
		echo '<pre>'; print_r($_SERVER); echo '</pre>';
	echo '<pre>'; print_r($GLOBALS['site_settings']); echo '</pre>';
	echo '<pre>'; print_r($_SESSION); echo '</pre>';*/
	//rp($GLOBALS);
	?>
<h2 style="color: #7A918B; margin: 0 5px;">Доработки на 11.2.2016.</h2>
<div style="margin:-10px 300px 10px 5px; border: 2px solid #7A918B; border-radius: 0 7px 7px 7px; padding: 0 5px">
	<p><ol><li>1. В разделе аналитики таблицу цен можно сортировать по любому параметру по возрастанию или по убыванию</li>
<li>2. Появилась возможность запоминать параметры доступа, чтобы не авторизоваться каждый раз.</li></ol>

</p>
	<i>11.2.2016.</i>
</div>

<h2 style="color: #7A918B; margin: 0 5px;">Доработки на 8 февраля</h2>
<div style="margin:-10px 300px 10px 5px; border: 2px solid #7A918B; border-radius: 0 7px 7px 7px; padding: 0 5px">
	<p>Теперь цены можно вводить прямо из списка товаров</p>
	<i>8.2.2016.</i>
</div>

<h2 style="color: #7A918B; margin: 0 5px;">Готова умная форма</h2>
<div style="margin:-10px 300px 10px 5px; border: 2px solid #7A918B; border-radius: 0 7px 7px 7px; padding: 0 5px">
	<p>Теперь вводить данные стало ещё проще. Готова умная форма: <a href="smart_form/">ссылка</a></p>
	<i>11.1.2016.</i>
</div>

<h2 style="color: #7A918B; margin: 0 5px;">Доработки на 21 декабря</h2>
<div style="margin:-10px 300px 10px 5px; border: 2px solid #7A918B; border-radius: 0 7px 7px 7px; padding: 0 5px">
	<p>Добавлена возможность редактировать свои данные в личном кабинете</p>
	<i>21.12.2015.</i>
</div>
	
<h2 style="color: #7A918B; margin: 0 5px;">Доработки на 11 декабря</h2>
<div style="margin:-10px 300px 10px 5px; border: 2px solid #7A918B; border-radius: 0 7px 7px 7px; padding: 0 5px">
	<ul>
		<li>1. Добавлена возможность выбора изображения для товара и личного аватара пользователя.</li> 
		<li>2. Доработан дизайн списка товаров</li>
		<li>3. Разрабатывается комплексная форма, которая позволит максимально удобно добавлять контент на сайт.</li>
	</ul>
	<i>11.12.2015.</i>
</div>

<h2 style="color: #7A918B; margin: 0 5px;">Доработки на 21 октября</h2>
<div style="margin:-10px 300px 10px 5px; border: 2px solid #7A918B; border-radius: 0 7px 7px 7px; padding: 0 5px">
	<ul>
		<li>1. Исправлен баг, не позволявший вводить цену с копейками. </li> 
		<li>2. В форме добавления предложения товара добавлено новое поле - дата покупки.</li>
	</ul>
	<i>21.10.2015.</i>
</div>

<h2 style="color: #7A918B; margin: 0 5px;">Сайт официально объявляется открытым!</h2>
<div style="margin:-10px 300px 10px 5px; border: 2px solid #7A918B; border-radius: 0 7px 7px 7px; padding: 0 5px">
	<p>Сегодня протестирован и запущен в эксплуатацию основной функционал сайта.</p> 
	<p>Запланировано ещё много доработок, и сайт будет постоянно улучшаться.</p>
	<i>18.10.2015.</i>
</div>


<h2 style="color: #7A918B; margin: 0 5px;">Готова страница товара детально</h2>
<div style="margin:-10px 300px 10px 5px; border: 2px solid #7A918B; border-radius: 0 7px 7px 7px; padding: 0 5px">
	<i>15.10.2015.</i>
</div>

<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>