<?
try {
	$db = new PDO('mysql:host=localhost;dbname=pr', 'pr', 'pr_password');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
	$db->query("SET lc_time_names = 'ru_RU'");
	$db->query("SET NAMES 'utf8'");
} catch (PDOException $e) {
	print "Ошибка подключения к БД<br/>";
	die();
}
?>
