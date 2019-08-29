<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Чеки с привязкой кассы к магазину</title>
</head>
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="../analytics/index.php?exit=1">Выход</a>
</table>
<br/>
<?php
	include "../template/oft_table.php";
	include "../template/connect.php";
	include "money_out.php";
	

	oftTable::init('Чеки c привязкой кассы к магазину');
	oftTable::header(array('Время'
		,'Сумма','fiscalDriveNumber'
		,'Название магазина','Адрес', 'Пользователь'
		));
	$stmt = $db->prepare(
		"SELECT r.id, DATE_FORMAT(r.dateTime, '%d-%m-%Y %H:%i:%s') dt
			, r.totalSum, r.fiscalDriveNumber
			, s.name, s.address, u.login
		 FROM ".DB_TABLE_PREFIX."receipt r 
		    JOIN ".DB_TABLE_PREFIX."users u on r.user_id = u.id 
		    JOIN ".DB_TABLE_PREFIX."fdn_to_shop l on l.fiscalDriveNumber = r.fiscalDriveNumber
		    JOIN ".DB_TABLE_PREFIX."shops s on s.id = l.shop_id ".
		 ((isset($_GET['user_id']))?"WHERE r.user_id = ?":"").   
		 " ORDER BY r.dateTime desc
		 ");
	if(isset($_GET['user_id']))
		$stmt->execute(array($_GET['user_id']));
	else
		$stmt->execute();
	while ($row = $stmt->fetch()) {
		oftTable::row(array('<a href=receipt.php?id='.$row['id'].'>'.$row['dt'].'</a>'
			, money_out($row['totalSum']), $row['fiscalDriveNumber']
			, $row['name'], $row['address'], $row['login']
			));
	}
        
	oftTable::end();
?> 
</body>
</html>
