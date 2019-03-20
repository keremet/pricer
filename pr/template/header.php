<? 
session_start();
if($_REQUEST['exit'] == '1'){
    unset($_SESSION['user_id']);
    unset($_SESSION['user_del_anothers_receipts']);
    unset($_SESSION['user_del_anothers_consumptions']);
    unset($_SESSION['user_del_anothers_shop_links']);
    unset($_SESSION['user_del_anothers_product_links']);
    unset($_SESSION['user_del_anothers_shops']);
    unset($_SESSION['user_del_anothers_products']);
    unset($_SESSION['user_edt_anothers_shops']);
    unset($_SESSION['user_edt_anothers_products']);
    unset($_SESSION['user_upload_receipts_from_file']);
    unset($_SESSION['user_download_backup']);
}
header('Content-Type: text/html; charset=utf-8');
include 'connect.php';
function headerOut($curmenu, $treesuf = null){
$tabTitle = 'Ценовичок - '.$curmenu;
 ?>
<html>
	<head>
		<title><?=$tabTitle?></title>
		<meta name="Title" content="<?=$tabTitle?>">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="description" content="Ценовичок - аналитика цен на товары">
		<meta name="keywords" content="Киров, цены, продукты">

		<script type="text/javascript" src="../template/fancybox/lib/jquery-1.10.1.min.js"></script>

		<script type="text/javascript" src="../template/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

		<script type="text/javascript" src="../template/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>

		<script type="text/javascript" src="../template/fancybox/script.js"></script>

		<link rel="stylesheet" type="text/css" href="../template/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

		<script type="text/javascript" src="../template/tablesorter/js/jquery.tablesorter.min.js"></script>
		<script type="text/javascript" src="../template/js/default.js"></script>

		<script type="text/javascript" src="../template/js/script.js"></script>

		<link rel="stylesheet" href="../template/css/default.css"/>

		<link rel="stylesheet" href="../template/css/style.css"/>
		
		<link rel="stylesheet" href="../template/tablesorter/default.css"/>
		<link rel="stylesheet" href="../template/tablesorter/jquery.tabs.css"/>
		<link rel="stylesheet" href="../template/tablesorter/jquery.tabs-ie.css"/>
		<?
		if(!is_null($treesuf)) {
		?>
		<link rel="stylesheet" href="../template/jstree/themes/default/style.min.css" />
		<? foreach($treesuf as $suf){?>
		<style>
                .jstree-default a { white-space:normal !important; height: auto; }
                .jstree-anchor { height: auto !important; }
                .jstree-default li > ins { vertical-align:top; }
                .jstree-leaf { height: auto; }
                .jstree-leaf a{ max-width:248px; height: auto !important; }                    
		html, body {font-size:10px; font-family:Verdana;}
		#container<?=$suf?> { min-width:320px; margin:0px auto 0 auto; background:white; border-radius:0px; padding:0px; overflow:hidden; }
		#tree<?=$suf?> { float:left; min-width:319px; max-width:324px; border-right:1px solid silver; overflow:auto; padding:5px 0; }
		#data<?=$suf?> { margin-left:326px; }
		#data<?=$suf?> { font: normal normal normal 12px/18px 'Consolas', monospace !important; }
		#tree<?=$suf?> .folder { background:url('../template/jstree/file_sprite.png') right bottom no-repeat; }
		#tree<?=$suf?> .file { background:url('../template/jstree/file_sprite.png') 0 0 no-repeat; }
		</style>
		<?
		}
		}
		?>
	</head>

	<body>

		<div id="wrap" class="container clr">

			<header id="masthead" class="site-header clr" role="banner">
					<a style="display: table-cell;" title="Ценовичок - аналитика цен на товары" href="../">
						<img alt="" style="padding: 20px; height: 80px;" src="../images/logo.jpg">
					</a>
					<a style="display: table-cell; text-decoration: none; margin-top: 0px; vertical-align: middle;" title="Ценовичок - аналитика цен на товары" href="../">
						<span style="color: #842816; text-decoration: none;" >
							<b>
								<span style="font-size: 46px;">Ценовичок&nbsp;-&nbsp;</span>
								<span style="font-size: 34px; margin-bottom: 0;"> аналитика цен на товары</span>
							</b>
						</span>
					</a>
			</header>
			<div id="navbar" class="navbar clr">
				<nav id="site-navigation" class="navigation main-navigation clr" role="navigation">
					<div class="menu-top-main-container">
						<ul id="menu-top-main" class="nav-menu dropdown-menu">
					<?
					foreach (array(
						'Аналитика' => '/analytics/',
						'Ввод данных' => '/smart_form/',
						'Цены товаров' => '/prices/'
					) as $k => $v){
						echo '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home'
						  .(($curmenu == $k)?' current-menu-item':'')
						  .'">
							<a href="..'.$v.'">'.$k.'</a>
						</li>';
					}
					if ($_SESSION['user_id']){
						echo '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home'
						.(($curmenu == "Личный кабинет")?' current-menu-item':'')
						.'">
							<a class="fancybox" href="../cabinet/">Личный кабинет</a>
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
			</div>

			<div id="main" class="site-main row clr fitvids">
<? } ?>
