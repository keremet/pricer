<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Чеки без привязки кассы к магазину</title>
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
	

	oftTable::init('Чеки без привязки кассы к магазину');
	oftTable::header(array('Время'
		,'Сумма','fiscalDriveNumber'
		,'user','retailPlaceAddress', 'Пользователь'
		));
	$stmt = $db->prepare(
		"SELECT r.id, DATE_FORMAT(r.dateTime, '%d-%m-%Y %H:%i:%s') dt
			, r.totalSum, r.fiscalDriveNumber
			, r.user, r.retailPlaceAddress, u.login
		 FROM receipt r 
		    JOIN users u on r.user_id = u.id 
		 WHERE NOT EXISTS(
			SELECT 1 FROM fdn_to_shop l
			WHERE l.fiscalDriveNumber = r.fiscalDriveNumber
		 )".
		 ((isset($_GET['user_id']))?"AND r.user_id = ?":"").   
		 " ORDER BY r.dateTime desc
		 ");
	if(isset($_GET['user_id']))
		$stmt->execute(array($_GET['user_id']));
	else
		$stmt->execute();
	while ($row = $stmt->fetch()) {
		oftTable::row(array('<a href=receipt.php?id='.$row['id'].'>'.$row['dt'].'</a>'
			, money_out($row['totalSum']), $row['fiscalDriveNumber']
			, $row['user'], $row['retailPlaceAddress'], $row['login']
			));
	}
        
	oftTable::end();
?> 
</body>
</html>
