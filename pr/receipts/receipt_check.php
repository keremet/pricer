<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Проверка чека</title>
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
	require_once('receipt_nalog.php');

	$stmt = $db->prepare(
		"SELECT fiscalDriveNumber, fiscalDocumentNumber, fiscalSign
			, DATE_FORMAT(dateTime, '%Y-%m-%dT%H:%i:%s') dt
			, totalSum
		 FROM ".DB_TABLE_PREFIX."receipt
		 WHERE id = ?
		 ");
	$stmt->execute(array($_GET['id']));
	
	$rec = new ReceiptNalog();
	if ($row = $stmt->fetch()) {
		$data = $rec->check($row['fiscalDriveNumber'], $row['fiscalDocumentNumber'], $row['fiscalSign'], $row['dt'], $row['totalSum']);
		echo "Результат проверки: '".$data."'";
	}
	else echo "Чек не найден в БД Ценовичка";
?> 
</body>
</html>
