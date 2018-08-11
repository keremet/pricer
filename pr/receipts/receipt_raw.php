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
	require_once('receipt_nalog.php');

	$stmt = $db->prepare(
		"SELECT rawReceipt, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign
		 FROM ".DB_TABLE_PREFIX."receipt
		 WHERE id = ?
		 ");
	$stmt->execute(array($_GET['id']));
	
	if ($row['rawReceipt']=='') {
		$rec = new ReceiptNalog("+79991004950", "503814");
		$data = $rec->get($row['fiscalDriveNumber'], $row['fiscalDocumentNumber'], $row['fiscalSign']);
	}
	echo $data;
?> 
</body>
</html>
