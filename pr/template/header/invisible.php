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
include($GLOBALS['site_settings']['root_path'].'/template/db_connect.php');
include($GLOBALS['site_settings']['root_path'].'/template/functions.php');
/*if($_REQUEST['user'] == 'exit'){
	unset($_SESSION['user']);
	header('Location: '.$GLOBALS['site_settings']['site_folder']);
	exit();
}*/
header( 'Content-Type: text/html; charset=utf-8' );
/*include($GLOBALS['site_settings']['root_path'].'/template/settings.php');
include($GLOBALS['site_settings']['root_path'].'/template/db_connect.php');
include($GLOBALS['site_settings']['root_path'].'/template/functions.php');*/
?>