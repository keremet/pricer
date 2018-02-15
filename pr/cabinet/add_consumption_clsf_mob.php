<?
include('../template/connect.php');
if(!$_POST['clsf_name'])
	die('Не указан расход');

$clsf_name = $_POST['clsf_name'];

if($clsf_name=='')
	die('Имя расхода не может быть пустым');

$clsf_id = $_POST['clsf_id'];

$stmt = $db->prepare("INSERT INTO ".DB_TABLE_PREFIX."consumption_clsf (name, id_hi) VALUES (?, ?)"); 
if(!$stmt->execute(array($clsf_name, $clsf_id))){
	print_r($stmt->errorInfo()); echo '<br>';
	die('Ошибка при добавлении расхода');
}
	
header('Location: profile_mob.php?clsf_id='.$clsf_id);
?>
