<?

$GLOBALS['site_settings']['current_address'] = $_SERVER['REQUEST_URI']; // адрес текущей страницы

$GLOBALS['site_settings']['server'] = $_SERVER['SERVER_NAME']; // Текущий домен

//	<Настройки подключения к БД

	$GLOBALS['site_settings']['db']['db_name'] = 'pr'; //имя БД

	$GLOBALS['site_settings']['db']['user_name'] = 'pr'; //имя пользователя БД

	$GLOBALS['site_settings']['db']['user_password'] = 'pr_password'; //пароль пользователя БД

	$GLOBALS['site_settings']['db']['host'] = 'localhost'; //месторасположение БД

//		<названия таблиц

		$GLOBALS['site_settings']['db']['tables']['users'] = 'pr_users'; 

		$GLOBALS['site_settings']['db']['tables']['shops'] = 'pr_shops'; 

		$GLOBALS['site_settings']['db']['tables']['products'] = 'pr_products';

		$GLOBALS['site_settings']['db']['tables']['product_props'] = 'pr_product_props';

		$GLOBALS['site_settings']['db']['tables']['product_props_values'] = 'pr_product_props_values';

		$GLOBALS['site_settings']['db']['tables']['product_props_rel'] = 'pr_product_props_rel';

		$GLOBALS['site_settings']['db']['tables']['product_offers'] = 'pr_product_offers';

		$GLOBALS['site_settings']['db']['tables']['images'] = 'pr_images';

		$GLOBALS['site_settings']['db']['tables']['product_images'] = 'pr_product_images';

		$GLOBALS['site_settings']['db']['tables']['user_images'] = 'pr_user_images';

//		/>

//	/>

$GLOBALS['site_settings']['img_path'] = '/template/files/images/';

$GLOBALS['site_settings']['reserved_prop_names']= array('Упаковка', 'Масса товара в упаковке (г.)', 'Количество штук в упаковке'); 



//Сокращения

$GLOBALS['site_settings']['db']['tables']['product-props_rel'] = $GLOBALS['site_settings']['db']['tables']['product-props_relation'];

$GLOBALS['site_settings']['db']['t'] = $GLOBALS['site_settings']['db']['tables'];

$GLOBALS['s_s'] = $GLOBALS['site_settings'];

?>
