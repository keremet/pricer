<?php 
	session_start();
	$now = new DateTime();
	$filename = "my_purchases" . $now->format('Y_m_d_H_i_s') . ".csv";
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . $filename);

	include "../template/connect.php";
	include "../receipts/money_out.php";

	echo "Дата|fiscalDriveNumber|Название магазина|Адрес магазина|Название магазина в чеке|Адрес магазина в чеке|Товар|Единица измерения|В упаковке|Товар в чеке|Цена|Количество\n";
	$stmt = $db->prepare(
        "SELECT DATE_FORMAT(r.dateTime, '%d.%m.%Y %H:%i:%s') dt, r.fiscalDriveNumber, s.name s_name, s.address, r.user, r.retailPlaceAddress, p.name p_name, e.name e_name, p.in_box, i.name, i.price, i.quantity
	     FROM pricer_receipt r 
	     LEFT JOIN pricer_fdn_to_shop f2s ON f2s.fiscalDriveNumber = r.fiscalDriveNumber
	     LEFT JOIN pricer_shops s ON s.id = f2s.shop_id
		 JOIN pricer_receipt_item i ON i.receipt_id = r.id
		 LEFT JOIN pricer_receipt_item_name_to_product in2p ON in2p.name = i.name
		 LEFT JOIN pricer_products p ON p.id = in2p.product_id
		 LEFT JOIN pricer_ed_izm e ON e.id = p.ed_izm_id
		 WHERE r.user_id=?
		 ORDER BY r.dateTime desc
        ");
	$stmt->execute(array($_SESSION['user_id']));
	while ($row = $stmt->fetch()) {
		echo $row['dt'].'|'.$row['fiscalDriveNumber'].'|'.$row['s_name'].'|'.$row['address'].'|'.$row['user'].'|'.$row['retailPlaceAddress'].'|'.$row['p_name'].'|'.$row['e_name'].'|'.$row['in_box'].'|'.$row['name'].'|'
			.str_replace('.', ',', money_to_str($row['price'])).'|'
			.str_replace('.', ',', $row['quantity'])."\n";
	}
?>
