<?

session_start();

/*if($_REQUEST['user'] == 'exit'){

	unset($_SESSION['user']);

	header('Location: '.$GLOBALS['site_settings']['site_folder']);

	exit();

}*/

header( 'Content-Type: text/html; charset=utf-8' );

$jquery_fancybox_used = true;

include($GLOBALS['site_settings']['root_path'].'/template/settings.php');
include($GLOBALS['site_settings']['root_path'].'/db/connect.php');
?>

<html>

	<head>

		<title>

			<?=$GLOBALS['site_settings']['TAB_TITLE']?>

		</title>

		<meta name="Title" content="<?=$GLOBALS['site_settings']['META']['TITLE']?>">

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<meta name="description" content="<?=$GLOBALS['site_settings']['META']['DESCRIPTION']?>">

		<meta name="keywords" content="<?=$GLOBALS['site_settings']['META']['KEYWORDS']?>">

		<?if($jquery_fancybox_used){?>

			<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/fancybox/lib/jquery-1.10.1.min.js"></script>

			<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

			<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>

			<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/fancybox/script.js"></script>

			<link rel="stylesheet" type="text/css" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

		<?}?>
		<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/tablesorter/js/jquery.tablesorter.min.js"></script>
		<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/js/default.js"></script>

		<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/js/script.js"></script>

		<link rel="stylesheet" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/css/default.css"/>

		<link rel="stylesheet" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/css/style.css"/>
		
		<link rel="stylesheet" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/tablesorter/default.css"/>
		<link rel="stylesheet" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/tablesorter/jquery.tabs.css"/>
		<link rel="stylesheet" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/tablesorter/jquery.tabs-ie.css"/>

	</head>

	<body>

		<div id="wrap" class="container clr">

			<header id="masthead" class="site-header clr" role="banner">

				<!--div style="float: left; display: inline-block; color: #D31316; font-weight: bold;"-->

					<!-- rel="home" title="–¶–µ–Ω–æ–≤–∏—á–æ–∫ - –∞–Ω–∞–ª–∏—Ç–∏–∫–∞ —Ü–µ–Ω –Ω–∞ —Ç–æ–≤–∞—Ä—ã" href="<?=$GLOBALS['site_settings']['site_folder']?>/">

						<div class="logo" style="display: inline-block; float: left;">

							<img alt="" style="height: 180px; margin: 20px" src="<?=$GLOBALS['site_settings']['site_folder'];?>/images/logo.jpg">

						</div>

					</a-->

					<a style="display: inline-block;" title="–¶–µ–Ω–æ–≤–∏—á–æ–∫ - –∞–Ω–∞–ª–∏—Ç–∏–∫–∞ —Ü–µ–Ω –Ω–∞ —Ç–æ–≤–∞—Ä—ã" href="<?=$GLOBALS['site_settings']['site_folder']?>/">

						<img alt="" style="padding: 20px; height: 80px;" src="<?=$GLOBALS['site_settings']['site_folder'];?>/images/logo.jpg">

						<span style="position: absolute; color: #842816; text-decoration: none;" >

							<b><span style="display: inline-block; font-size: 46px; margin-top: 20px;">–¶–µ–Ω–æ–≤–∏—á–æ–∫&nbsp;-&nbsp;</span><span style="display: inline-block; font-size: 34px; margin-bottom: 0;"> –∞–Ω–∞–ª–∏—Ç–∏–∫–∞ —Ü–µ–Ω –Ω–∞ —Ç–æ–≤–∞—Ä—ã</span></b>

							<!--br><span style="display: inline-block; margin-bottom: 0; font-size: 20px;">–í—Ç–æ—Ä–æ–π —Ä—è–¥</span-->

						</span>

					</a>

				<!--/div-->

				<!--a align="center" title="–û–†–í - –û–±—â–µ—Å—Ç–≤–æ —Ä–∞–±–æ—á–µ–π –≤–∑–∞–∏–º–æ–≤—ã—Ä—É—á–∫–∏" target="_blank" href="http://orv.org.ru/">

					<div class="logo" style="display: inline-block; float: right;">

						<img alt="" style="height: 250px; margin: 20px" src="<?=$GLOBALS['site_settings']['site_folder'];?>/images/orv.jpg">

					</div>

				</a-->

			</header>
			<?

//echo $_COOKIE['user_login'];?>
			<div id="navbar" class="navbar clr">
				<?if($_REQUEST['user'] == 'exit'){	//echo "<script>alert ('exit');</script>";
					unset($_SESSION['user']);
					//setcookie('user_login', '', time() + 3600 * 24 * 30);
					//setcookie('user_password', '', time() - 1);
					//setcookie('autorisation', '', time() - 1);
					setcookie('user_login', '', time() - 1, '/');
					setcookie('user_password', '', time() - 1, '/');
					$no_user_autorise = 'Y';
					//header('Location: '.$GLOBALS['site_settings']['site_folder']);
					//echo "<script>document.location.href='http://".$GLOBALS['site_settings']['server'].$GLOBALS['site_settings']['site_folder']."/';</script>";
				}elseif(strlen($_COOKIE['user_login']) > 0 && strlen($_COOKIE['user_password']) > 0 && $no_user_autorise != 'Y' && !$_SESSION['user']['id']){ //≈ÒÎË ‚ ÍÛÍ‡ı ÂÒÚ¸ ÎÓ„ËÌ Ë Ô‡ÓÎ¸ ÔÓÎ¸ÁÓ‚‡ÚÂÎˇ, Ë ˛ÁÂ ÌÂ ‡‚ÚÓËÁÓ‚‡Ì, ÚÓ ‡‚ÚÓËÁÛÂÏ Â„Ó.
					$stmt = $db->prepare("SELECT id FROM pr_users WHERE login = ? AND password = ?");
					$stmt->execute(array(trim(htmlspecialchars($_COOKIE['user_login'])), trim($_COOKIE['user_password'])));
					if(!($user_id = $stmt->fetchColumn())){
						$_SESSION['user']['id'] = $user_id;
						setcookie("user_login", $_COOKIE['user_login'], time() + 3600 * 24 * 30, '/');
						setcookie("user_password", $_COOKIE['user_password'], time() + 3600 * 24 * 30, '/');
					}else{
						setcookie('user_login', '', time() - 1, '/');
						setcookie('user_password', '', time() - 1, '/');
						$no_user_autorise = 'Y';
					}
				}
				include($GLOBALS['site_settings']['root_path'].'/template/header/menu_head/index.php');?>

			</div>

			<div id="main" class="site-main row clr fitvids">
