<?
$menu_items = array(
	'Новости' => '/', 
	'Товары' => '/products/', 
	'Магазины' => '/shops/', 
	'Умная форма' => '/smart_form/', 
	'Аналитика' => '/analytics/', 
	'Контакты' => '/contacts/', 
);
?>

<nav id="site-navigation" class="navigation main-navigation clr" role="navigation">
	<div class="menu-top-main-container">
		<ul id="menu-top-main" class="nav-menu dropdown-menu">
	<?

	foreach ($menu_items as $k => $v){

		echo '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home';
		if (((substr($GLOBALS['site_settings']['current_address'], 0, strlen($GLOBALS['site_settings']['site_folder'].$v)) == $GLOBALS['site_settings']['site_folder'].$v) && ($v != '/')) || (($v == '/') && ($GLOBALS['site_settings']['current_address'] == $GLOBALS['site_settings']['site_folder'].$v))){
			echo ' current-menu-item';
		}
		echo '">
			<a href="'.$GLOBALS['site_settings']['site_folder'].$v.'">'.$k.'</a>
		</li>';
	}
		if ($_SESSION['user']['id']){
			echo '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home';
			if (((substr($GLOBALS['site_settings']['current_address'], 0, strlen($GLOBALS['site_settings']['site_folder'].'/cabinet/')) == $GLOBALS['site_settings']['site_folder'].'/cabinet/') && ('/cabinet/' != '/')) || (('/cabinet/' == '/') && ($GLOBALS['site_settings']['current_address'] == $GLOBALS['site_settings']['site_folder'].'/cabinet/'))){
				echo ' current-menu-item';
			}
			echo '">
				<a class="fancybox" href="'.$GLOBALS['site_settings']['site_folder'].'/cabinet/">Личный кабинет</a>
			</li>';
		}else{
			echo '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home">
				<a class="fancybox" href="#auth_form">Авторизация</a>
			</li>';
		}
	?>
		</ul>
	</div>
</nav>
