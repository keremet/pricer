<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Список чеков</title>
</head>
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="../analytics/index.php?exit=1">Выход</a>
	<td align="left"><a href="receipt_load.php">Загрузка</a>
	<td align="left"><a href="stats.php">Статистика покупок товаров</a>
	<td align="left"><a href="receipt_add.php">Добавить чек</a>
</table>
<br/>
<p align="right">Внимание! Данные по чекам из налоговой приходят с задержкой</p>
<?php
    session_start();
    $show_login = ($_SESSION['user_show_login'] == "1");
	include "../template/oft_table.php";
	include "../template/connect.php";
	include "money_out.php";
	

	oftTable::init('Чеки');
	$header_arr = array('Время'
		,'Сумма'
		,'Продавец'
		,'Место покупки'
		);
	if($show_login)
		$header_arr[] = 'Пользователь';
	oftTable::header($header_arr);
	$stmt = $db->prepare(
		"SELECT r.id, DATE_FORMAT(r.dateTime, '%d-%m-%Y %H:%i:%s') dt
			, r.totalSum
			, r.user
			, r.retailPlaceAddress".
			(($show_login)?", u.login":"").
		" FROM receipt r".
		    (($show_login)?" JOIN users u on r.user_id = u.id":"").
		 ((isset($_GET['user_id']))?" WHERE r.user_id = ?":"").   
		 " ORDER BY r.dateTime desc
		 ");
	if(isset($_GET['user_id']))
		$stmt->execute(array($_GET['user_id']));
	else
		$stmt->execute();
	while ($row = $stmt->fetch()) {
		$row_arr = array('<a href=receipt.php?id='.$row['id'].'>'.$row['dt'].'</a>'
			, money_out($row['totalSum'])
			, $row['user']
			, $row['retailPlaceAddress']
			);
		if($show_login)
			$row_arr[] = $row['login'];
		oftTable::row($row_arr);
	}
        
	oftTable::end();
?> 
</body>
</html>
