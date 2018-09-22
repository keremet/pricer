<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Чек в формате JSON</title>
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

	$stmtS = $db->prepare(
		"SELECT rawReceipt, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign
		 FROM ".DB_TABLE_PREFIX."receipt
		 WHERE id = ?
		 ");
	$stmtS->execute(array($_GET['id']));
	if ($row = $stmtS->fetch()) {
		if ($row['rawReceipt'] == '') {
			$rec = new ReceiptNalog();
			$data = $rec->get($row['fiscalDriveNumber'], $row['fiscalDocumentNumber'], $row['fiscalSign']);
			
			$stmtU = $db->prepare(
				"UPDATE ".DB_TABLE_PREFIX."receipt
				 SET rawReceipt = ?
				 WHERE id = ?
				 ");
			$stmtU->execute(array($data, $_GET['id']));
			echo "Данные из налоговой: ".$data;
		}else echo "Значение из БД Ценовичка: ".$row['rawReceipt'];
	} else echo "Чек не найден в БД Ценовичка";
?> 
</body>
</html>
