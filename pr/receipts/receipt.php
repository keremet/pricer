<?session_start();?>
<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Чек</title>
<script type="text/javascript" src="../template/fancybox/lib/jquery-1.10.1.min.js"></script>
<script>
	function receipt_del(id){
		if (!confirm('Удалить чек?'))
			return;
		jQuery.ajax({
			url:     'receipt_del.php',
			type:     "POST",
			dataType: "html",
			data: {id: id}, 
			success: function(result) {
				alert(result);
				document.location.href = 'receipt_list.php';
			}
		});
	}
</script>
</head>
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="../analytics/index.php?exit=1">Выход</a>
	<td align="left"><a href="receipt_list.php">Список чеков</a>
	<td align="left"><a href="receipt_anlt.php?id=<?=$_GET['id']?>">Отчет по оптимальности покупки</a>
</table>
<br/>
<?php
	include "../template/oft_table.php";
	include "../template/connect.php";
	include "money_out.php";
	
	$stmt = $db->prepare(
	"SELECT DATE_FORMAT(r.dateTime, '%d-%m-%Y %H:%i:%s') dt
			, r.totalSum
			, r.user
			, r.retailPlaceAddress
			, u.login
			, r.rawReceipt
			, r.user_id
			, u_ins.login ins_login
			, DATE_FORMAT(r.dtInsert, '%d-%m-%Y %H:%i:%s') dtInsert
		 FROM ".DB_TABLE_PREFIX."receipt r 
		    JOIN ".DB_TABLE_PREFIX."users u on r.user_id = u.id 
		    JOIN ".DB_TABLE_PREFIX."users u_ins on r.ins_user_id = u_ins.id 
		 WHERE r.id = ?"
	 );
	$stmt->execute(array($_GET['id']));
	$rowR = $stmt->fetch();
	if(!$rowR)
		die("Чек не найден");
?> 
	<table border="0" cellpadding="0" cellspacing="2">
		<tr><td>Дата и время чека:<td><?=$rowR['dt']?>
		<tr><td>Сумма:<td><?=money_to_str($rowR['totalSum'])?>
		<tr><td>User:<td><?=$rowR['user']?>
		<tr><td>retailPlaceAddress:<td><?=$rowR['retailPlaceAddress']?>
		<tr><td>Покупатель:<td><?=$rowR['login']?>
		<tr><td>Ввел:<td><?=$rowR['ins_login']?>
		<tr><td>Дата и время ввода:<td><?=$rowR['dtInsert']?>
	</table>
	<?
	oftTable::init('Товары');
	oftTable::header(array('Товар'
			, 'Цена', 'Кол-во', 'Ст-ть', 'Сумма скидки'
			, 'Название скидки', 'markupName'
			, 'НДС 10%', 'НДС 18%', 'НДС'
		));
	$stmt = $db->prepare(
		"SELECT i.sum, i.nds10, i.name, i.price, i.nds18, i.id, i.quantity, i.ndsNo
			, m.discountName, m.markupName, m.discountSum
		 FROM ".DB_TABLE_PREFIX."receipt_item i
		 LEFT JOIN ".DB_TABLE_PREFIX."receipt_modifier m on m.item_id = i.id
		 WHERE i.receipt_id = ?
		 ");
	$stmt->execute(array($_GET['id']));
	while ($row = $stmt->fetch()) {
		oftTable::row(array($row['name']
			, money_out($row['price']), "<p align=\"right\">".$row['quantity']."</p>", money_out($row['sum']), money_out($row['discountSum'])
			, $row['discountName'], $row['markupName']	
			, money_out($row['nds10']), money_out($row['nds18']), money_out($row['ndsNo'])
			));
	}
        
	oftTable::end();
	if(($_SESSION['user_del_anothers_receipts'] == '1') || ($rowR['user_id'] == $_SESSION['user_id'])){ ?> 
		<p align="left"><button onclick="receipt_del('<?=$_GET['id']?>');">Удалить</button> <? 
	}
?> 
<br/>
<p align="left">Чек в неразобранном формате:<br/>
<?=$rowR['rawReceipt']?>
</body>
</html>
