<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<?php
	include('../template/connect.php');

	$stmt = $db->prepare("INSERT INTO ".DB_TABLE_PREFIX."product_offers (product,shop,price,creator,date_buy) VALUES (?, ?, ?, 1, CURDATE())");
	foreach($_POST as $k => $v){
		if((substr($k, 0, 4)=='prod') && $v != ''){
			if(!is_numeric($v)){
				echo 'Цена '.substr($k, 4).' - '.$v.' не является числом<br>';
				continue;
			}
			if(!$stmt->execute(array(substr($k, 4), $_POST['shopid'], $v/*, $_SESSION['user']['id']*/))){
				echo 'Ошибка на цене '.substr($k, 4).' - '.$v.': '; print_r($stmt->errorInfo()); echo '<br>';
				continue;
			}
			echo 'Цена '.substr($k, 4).' - '.$v.' успешно добавлена<br>';
		}
	}
?>
</body>
</html>
