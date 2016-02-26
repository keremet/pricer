<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<?php
	include('../template/db_connect.php');

	foreach($_POST as $k => $v){
		if((substr($k, 0, 4)=='prod') && $v != ''){
			$offer_id = $db->query("INSERT INTO pr_product_offers (product,shop,price,creator,date_buy) VALUES ({?},{?},{?},1, CURDATE())", 
				array(substr($k, 4), $_POST['shopid'], $v/*, $_SESSION['user']['id']*/));

			if($offer_id){
				echo 'Цена '.substr($k, 4).' - '.$v.' успешно добавлена<br>';
			}else{
				echo mysql_error();
			}
//			echo substr($k, 4).' - '.$v.'<br>';
		}
	}
?>
</body>
</html>
