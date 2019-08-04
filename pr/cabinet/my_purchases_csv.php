<?php 
	session_start();
	$now = new DateTime();
	$filename = "my_purchases" . $now->format('Y_m_d_H_i_s') . ".csv";
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . $filename);

	include "../template/connect.php";
	include "../receipts/money_out.php";

	echo "Дата|ИНН магазина|Название магазина|Адрес магазина|Товар|Цена|Количество\n";
	$stmt = $db->prepare(
        "SELECT DATE_FORMAT(r.dateTime, '%d.%m.%Y %H:%i:%s') dt, r.userInn, r.user, r.retailPlaceAddress, i.name, i.price, i.quantity
	     FROM pricer_receipt r 
		 JOIN pricer_receipt_item i ON i.receipt_id = r.id
		 WHERE r.user_id=?
		 ORDER BY r.dateTime desc
        ");
	$stmt->execute(array($_SESSION['user_id']));
	while ($row = $stmt->fetch()) {
		echo $row['dt'].'|'.$row['userInn'].'|'.$row['user'].'|'.$row['retailPlaceAddress'].'|'.$row['name'].'|'
			.str_replace('.', ',', money_to_str($row['price'])).'|'
			.str_replace('.', ',', $row['quantity'])."\n";
	}
?>
