<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Список чеков</title>
</head>
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="../analytics/index.php?exit=1">Выход</a>
	<td align="left"><a href="receipt_load.php">Загрузка</a>
	<td align="left"><a href="unknown_products.php">Неизвестные товары</a>
	<td align="left"><a href="unknown_shops.php">Неизвестные магазины</a>
	<td align="left"><a href="known_products.php">Соответствие товара</a>
	<td align="left"><a href="known_shops.php">Соответствие магазина</a>
	<td align="left"><a href="stats.php">Статистика покупок товаров</a>
	<td align="left"><a href="receipt_add.php">Добавить чек</a>
</table>
<br/>
<?php
    session_start();
    $show_login = ($_SESSION['user_show_login'] == "1");
	include "../template/oft_table.php";
	include "../template/connect.php";
	include "money_out.php";
	

	oftTable::init('Чеки');
	$header_arr = array('Время'
		,'totalSum','fiscalDriveNumber'
		,'kktRegId','user','operationType'
		,'shiftNumber', 'ecashTotalSum'
		,'retailPlaceAddress', 'userInn', 'taxationType'
		,'cashTotalSum', 'operator'
		,'receiptCode', 'fiscalSign'
		,'fiscalDocumentNumber', 'requestNumber'
		,'buyerAddress', 'senderAddress','addressToCheckFiscalSign'
		, 'nds18', 'nds10', 'ndsNo'
		);
	if($show_login)
		$header_arr[] = 'login';
	oftTable::header($header_arr);
	$stmt = $db->prepare(
		"SELECT r.id, DATE_FORMAT(r.dateTime, '%d-%m-%Y %H:%i:%s') dt
			, r.buyerAddress, r.totalSum, r.addressToCheckFiscalSign, r.fiscalDriveNumber
			, r.kktRegId, r.user, r.operationType
			, r.shiftNumber, r.ecashTotalSum, r.nds18
			, r.retailPlaceAddress, r.userInn, r.taxationType
			, r.cashTotalSum, r.operator, r.senderAddress
			, r.receiptCode, r.fiscalSign, r.nds10
			, r.fiscalDocumentNumber, r.requestNumber, r.ndsNo".
			(($show_login)?", u.login":"").
		" FROM ".DB_TABLE_PREFIX."receipt r".
		    (($show_login)?" JOIN ".DB_TABLE_PREFIX."users u on r.user_id = u.id":"").
		 ((isset($_GET['user_id']))?" WHERE r.user_id = ?":"").   
		 " ORDER BY r.dateTime desc
		 ");
	if(isset($_GET['user_id']))
		$stmt->execute(array($_GET['user_id']));
	else
		$stmt->execute();
	while ($row = $stmt->fetch()) {
		$row_arr = array('<a href=receipt.php?id='.$row['id'].'>'.$row['dt'].'</a>'
			, money_out($row['totalSum']), $row['fiscalDriveNumber']
			, $row['kktRegId'], $row['user'], $row['operationType']
			, $row['shiftNumber'], money_out($row['ecashTotalSum'])
			, $row['retailPlaceAddress'], $row['userInn'], $row['taxationType']
			, $row['cashTotalSum'], $row['operator']
			, $row['receiptCode'], $row['fiscalSign']
			, $row['fiscalDocumentNumber'], $row['requestNumber']
			, $row['buyerAddress'], $row['senderAddress'], $row['addressToCheckFiscalSign']
			, money_out($row['nds18']), money_out($row['nds10']), money_out($row['ndsNo'])
			);
		if($show_login)
			$row_arr[] = $row['login'];
		oftTable::row($row_arr);
	}
        
	oftTable::end();
?> 
</body>
</html>
