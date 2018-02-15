<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<?
include('../template/connect.php');
	
if(!$_REQUEST['clsf_id'])
	die('Не указан расход');

if(!$_REQUEST['price'])
	die('Не введена цена');

$price = str_replace(',', '.', $_REQUEST['price']);

if(!is_numeric($price))
	die('Неправильный формат цены');

$pieces = explode("/", $_REQUEST['date_buy']);
$date_buy = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
$clsf_id = $_REQUEST['clsf_id'];

$stmt = $db->prepare("INSERT INTO ".DB_TABLE_PREFIX."consumption (clsf_id,price,date_buy) VALUES (?,?,?)"); 
if(!$stmt->execute(array($clsf_id, $price, $date_buy))){
	print_r($stmt->errorInfo()); echo '<br>';
	die('Ошибка при добавлении цены');
}
echo 'Цена успешно добавлена';

$stmt = $db->prepare(
	"SELECT cur_clsf.id_hi, hi_clsf.name
	 FROM ".DB_TABLE_PREFIX."consumption_clsf cur_clsf
	   LEFT JOIN ".DB_TABLE_PREFIX."consumption_clsf hi_clsf on hi_clsf.id = cur_clsf.id_hi
	 WHERE cur_clsf.id = ?"
);
$stmt->execute(array($clsf_id));
$row = $stmt->fetch();

?>
<br>
<? if($row['id_hi']!=null){ ?>
<a href=profile_mob.php?clsf_id=<?=$row['id_hi']?>><?=$row['name']?></a>
<? }else{ ?>
<a href=profile_mob.php>На главный классификатор</a>	
<? } ?>
</body>
</html>
