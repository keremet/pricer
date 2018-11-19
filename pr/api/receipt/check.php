<?php
	include "../../template/connect.php";
	require_once('receipt_nalog.php');

	$stmtS = $db->prepare(
		"SELECT fiscalDriveNumber, fiscalDocumentNumber, fiscalSign
			, DATE_FORMAT(dateTime, '%Y-%m-%dT%H:%i:%s') dt
			, totalSum
		 FROM ".DB_TABLE_PREFIX."receipt
		 WHERE id = ?
		 ");
	$stmtS->execute(array($_GET['id']));
	$row = $stmtS->fetch();
	if(!$row)
		die("Чек не найден в БД Ценовичка");
		
	$rec = new ReceiptNalog();
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
?> 
