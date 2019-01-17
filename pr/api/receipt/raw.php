<?php
	include "../../template/connect.php";
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
			if (($data != "daily limit reached for the specified user") && ($data != "No available logins")) {
				$stmtU = $db->prepare(
					"UPDATE ".DB_TABLE_PREFIX."receipt
					 SET rawReceipt = ?
					 WHERE id = ?
					 ");
				$stmtU->execute(array($data, $_GET['id']));
			}
			echo "Данные из налоговой: '".$data."'";
		}else echo "Значение из БД Ценовичка: ".$row['rawReceipt'];
	} else echo "Чек не найден в БД Ценовичка";
?> 
