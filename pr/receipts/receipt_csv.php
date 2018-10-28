<?php
	header('Content-Type: text/html; charset=utf-8'); 
	include "../template/connect.php";
	function money_out($v) {
		if($v == null)
			return "";

		$s = "";
		$s = ($v % 10).$s;
		$v = (int)($v/10);
		$s = ",".($v % 10).$s;
		$v = (int)($v/10);
		do {
			$s = ($v % 10).$s;
			$v = (int)($v/10);
		} while ($v > 0);
		return $s;
	}
	
	$stmt = $db->prepare(
		"SELECT DATE_FORMAT(r.dateTime, '%d-%m-%Y') dt,
			r.user, u.login, i.name, i.quantity, i.price
		 FROM ".DB_TABLE_PREFIX."receipt r 
		    JOIN ".DB_TABLE_PREFIX."users u on r.user_id = u.id 
		    JOIN ".DB_TABLE_PREFIX."receipt_item i on i.receipt_id = r.id ".
		 ((isset($_GET['user_id']))?"WHERE r.user_id = ?":"").   
		 " ORDER BY r.dateTime desc
		 ");
	if(isset($_GET['user_id']))
		$stmt->execute(array($_GET['user_id']));
	else
		$stmt->execute();
	while ($row = $stmt->fetch()) {
		echo $row['dt'].";".$row['user'].";".$row['name'].";".str_replace(".", ",", $row['quantity']).";".money_out($row['price']).";".$row['login']."\n";
	}
?> 
