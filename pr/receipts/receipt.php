<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Чек</title>
</head>
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="receipt_list.php">Список чеков</a>
</table>
<br/>
<?php
function money_out($v) {
	if($v == null)
		return "";

	$s = "";
	$s = ($v % 10).$s;
	$v = (int)($v/10);
	$s = ".".($v % 10).$s;
	$v = (int)($v/10);
	do {
		$s = ($v % 10).$s;
		$v = (int)($v/10);
	} while ($v > 0);
	return "<p align=\"right\">".$s."</p>";
}

	include "../template/oft_table.php";
	include "../template/connect.php";
	
	oftTable::init('Товары');
	oftTable::header(array('Товар'
			, 'Цена', 'Кол-во', 'Ст-ть', 'Сумма скидки'
			, 'Название скидки', 'markupName'
			, 'НДС 10%', 'НДС 18%', 'НДС'
		));
	$stmt = $db->prepare(
		"SELECT i.sum, i.nds10, i.name, i.price, i.nds18, i.id, i.quantity, i.ndsNo
			, m.discountName, m.markupName, m.discountSum
		 FROM ".DB_TABLE_PREFIX."receipt_item i
		 LEFT JOIN ".DB_TABLE_PREFIX."receipt_modifier m on m.item_id = i.id
		 WHERE i.receipt_id = ?
		 ");
	$stmt->execute(array($_GET['id']));
	while ($row = $stmt->fetch()) {
		oftTable::row(array($row['name']
			, money_out($row['price']), "<p align=\"right\">".$row['quantity']."</p>", money_out($row['sum']), money_out($row['discountSum'])
			, $row['discountName'], $row['markupName']	
			, money_out($row['nds10']), money_out($row['nds18']), $row['ndsNo']
			));
	}
        
	oftTable::end();
?> 
</body>
</html>
