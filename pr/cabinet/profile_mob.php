<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<h1>Ввод расходов
	<?php
		define('USER_MAIN_CLSF', '1');
		//~ define('USER_MAIN_CLSF', '133');
		include('../template/connect.php');
		$clsf_id = (isset($_GET['clsf_id']))?$_GET['clsf_id']:USER_MAIN_CLSF;
		$stmt = $db->prepare(
			"SELECT name, id_hi
			 FROM pr_consumption_clsf
			 WHERE id = ?"
		);
		$stmt->execute(array($clsf_id));
		$row = $stmt->fetch();
		echo ' - '. $row['name'];
	?>
	</h1>
	<form method=post action="save_consumption_mob.php">
		Дата:<input name="date_buy" value="<?=date("d/m/Y")?>">
		Стоимость:<input size=8  type="text" name="price">			
		<input type="submit" value="Добавить">
		<input type="hidden" name="clsf_id" value="<?=$clsf_id?>">
	</form>
	<?php
		if($clsf_id != USER_MAIN_CLSF){
			echo '<p><a href="?clsf_id='.$row['id_hi'].'">..</a></p>';
		}
		
		$stmt = $db->prepare(
			"SELECT id, name
			 FROM pr_consumption_clsf
			 WHERE id_hi = ?
			 ORDER BY name"
		);
		$stmt->execute(array($clsf_id));
		while($row = $stmt->fetch()){
			echo '<p><a href="?clsf_id='.$row['id'].'">'.$row['name'].'</a></p>';
		}
	?>
	<form method=post action="add_consumption_clsf_mob.php">
		<input name="clsf_name">
		<input type="submit" value="Добавить название расхода">
		<input type="hidden" name="clsf_id" value="<?=$clsf_id?>">
	</form>
	<br><a href=my_consumption.php>Проверка ввода</a>
</body>
</html>
