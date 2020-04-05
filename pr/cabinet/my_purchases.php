<?php session_start();?>
<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Покупки по данным из чеков</title>
</head>
<?php
	include "../template/oft_table.php";
	include "../template/connect.php";
	include "../receipts/money_out.php";

	oftTable::init("Покупки по данным из чеков");
	oftTable::header(array("Дата", "ИНН магазина", "Название магазина", "Адрес магазина", "Товар", "Цена", "Количество"));
	$stmt = $db->prepare(
        "SELECT DATE_FORMAT(r.dateTime, '%d-%m-%Y %H:%i:%s') dt, r.userInn, r.user, r.retailPlaceAddress, i.name, i.price, i.quantity
	     FROM receipt r 
		 JOIN receipt_item i ON i.receipt_id = r.id
		 WHERE r.user_id=?
		 ORDER BY r.dateTime desc
        ");
	$stmt->execute(array($_SESSION['user_id']));
	while ($row = $stmt->fetch()) {
		oftTable::row(array($row['dt'], $row['userInn'], $row['user'], $row['retailPlaceAddress'], $row['name'], money_out($row['price']), $row['quantity']));
	}
        
	oftTable::end();
?>
</body>
</html>
