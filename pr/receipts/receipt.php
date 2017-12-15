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
	include "../template/oft_table.php";
	include "../template/connect.php";
	
	oftTable::init('Товары');
	oftTable::header(array('name'
			, 'price', 'quantity', 'sum', 'discountSum'
			, 'discountName', 'markupName'
			, 'nds10', 'nds18', 'ndsNo'
		));
	$stmt = $db->prepare(
		"SELECT i.sum, i.nds10, i.name, i.price, i.nds18, i.id, i.quantity, i.ndsNo
			, m.discountName, m.markupName, m.discountSum
		 FROM rcp_item i
		 LEFT JOIN rcp_modifier m on m.item_id = i.id
		 WHERE i.receipt_id = ?
		 ");
	$stmt->execute(array($_GET['id']));
	while ($row = $stmt->fetch()) {
		oftTable::row(array($row['name']
			, $row['price'], $row['quantity'], $row['sum'], $row['discountSum']
			, $row['discountName'], $row['markupName']	
			, $row['nds10'], $row['nds18'], $row['ndsNo']
			));
	}
        
	oftTable::end();
?> 
</body>
</html>
