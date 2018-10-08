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
	include "../template/connect.php";
	require_once('receipt_nalog.php');

	$stmtS = $db->prepare(
		"SELECT fiscalDriveNumber, fiscalDocumentNumber, fiscalSign
			, DATE_FORMAT(dateTime, '%Y-%m-%dT%H:%i:%s') dt
			, totalSum
		 FROM ".DB_TABLE_PREFIX."receipt
		 WHERE id = ?
		 ");
	$stmtS->execute(array($_GET['id']));
	
	$rec = new ReceiptNalog();
	if ($row = $stmtS->fetch()) {
		$data = $rec->check($row['fiscalDriveNumber'], $row['fiscalDocumentNumber'], $row['fiscalSign'], $row['dt'], $row['totalSum']);
		echo "Результат проверки: '".$data."'";
		if ($data == '') {
			$stmtU = $db->prepare(
				"UPDATE ".DB_TABLE_PREFIX."receipt
				 SET checked = 1
				 WHERE id = ?
				 ");
			$stmtU->execute(array($_GET['id']));
		}
	}
	else echo "Чек не найден в БД Ценовичка";
?> 
