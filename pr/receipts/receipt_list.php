<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Список чеков</title>
</head>
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="receipt_load.htm">Загрузка</a>
</table>
<br/>
<?php
	include "../template/oft_table.php";
	include "../template/connect.php";
	
	oftTable::init('Чеки');
	oftTable::header(array('Время'
		,'totalSum','fiscalDriveNumber'
		,'kktRegId','user','operationType'
		,'shiftNumber', 'ecashTotalSum'
		,'retailPlaceAddress', 'userInn', 'taxationType'
		,'cashTotalSum', 'operator'
		,'receiptCode', 'fiscalSign'
		,'fiscalDocumentNumber', 'requestNumber'
		,'buyerAddress', 'senderAddress','addressToCheckFiscalSign'
		, 'nds18', 'nds10', 'ndsNo'
		));
	$stmt = $db->prepare(
		"SELECT id, DATE_FORMAT(dateTime, '%d-%m-%Y %H:%i:%s') dt
			, buyerAddress, totalSum, addressToCheckFiscalSign, fiscalDriveNumber
			, kktRegId, user, operationType
			, shiftNumber, ecashTotalSum, nds18
			, retailPlaceAddress, userInn, taxationType
			, cashTotalSum, operator, senderAddress
			, receiptCode, fiscalSign, nds10
			, fiscalDocumentNumber, requestNumber, ndsNo
		 FROM rcp_receipt
		 ORDER BY dateTime desc
		 ");
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		oftTable::row(array('<a href=receipt.php?id='.$row['id'].'>'.$row['dt'].'</a>'
			, $row['totalSum'], $row['fiscalDriveNumber']
			, $row['kktRegId'], $row['user'], $row['operationType']
			, $row['shiftNumber'], $row['ecashTotalSum']
			, $row['retailPlaceAddress'], $row['userInn'], $row['taxationType']
			, $row['cashTotalSum'], $row['operator']
			, $row['receiptCode'], $row['fiscalSign']
			, $row['fiscalDocumentNumber'], $row['requestNumber']
			, $row['buyerAddress'], $row['senderAddress'], $row['addressToCheckFiscalSign']
			, $row['nds18'], $row['nds10'], $row['ndsNo']		
			));
	}
        
	oftTable::end();
?> 
</body>
</html>
