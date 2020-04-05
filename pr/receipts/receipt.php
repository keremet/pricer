<?
	session_start();
	$show_login = ($_SESSION['user_show_login'] == "1");
?>
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
			, r.fiscalDriveNumber
			, r.buyerAddress, r.addressToCheckFiscalSign
			, r.kktRegId, r.operationType
			, r.shiftNumber, r.ecashTotalSum, r.nds18
			, r.userInn, r.taxationType
			, r.cashTotalSum, r.operator, r.senderAddress
			, r.receiptCode, r.fiscalSign, r.nds10
			, r.fiscalDocumentNumber, r.requestNumber, r.ndsNo
		 FROM receipt r
		    JOIN users u on r.user_id = u.id
		    JOIN users u_ins on r.ins_user_id = u_ins.id
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
		<tr><td>Продавец:<td><?=$rowR['user']?>
		<tr><td>Место покупки:<td><?=$rowR['retailPlaceAddress']?>
<? if($show_login) {?>
		<tr><td>Покупатель:<td><?=$rowR['login']?>
		<tr><td>Ввел:<td><?=$rowR['ins_login']?>
		<tr><td>Дата и время ввода:<td><?=$rowR['dtInsert']?>
<? } ?>
		<tr><td>Касса:<td><?=$rowR['fiscalDriveNumber']?>
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
		 FROM receipt_item i
		 LEFT JOIN receipt_modifier m on m.item_id = i.id
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
<p align="left"><table border="0" cellpadding="0" cellspacing="2">
<?
	function tr_out($label, $v) {
		if( !is_null($v) && $v !=='' )
			echo '<tr><td>'.$label.':<td>'.$v;
	}
	tr_out('buyerAddress', $rowR['buyerAddress']);
	tr_out('addressToCheckFiscalSign', $rowR['addressToCheckFiscalSign']);
	tr_out('kktRegId', $rowR['kktRegId']);
	tr_out('operationType', $rowR['operationType']);
	tr_out('shiftNumber', $rowR['shiftNumber']);
	tr_out('По карте', money_to_str($rowR['ecashTotalSum']));
	tr_out('Наличными', money_to_str($rowR['cashTotalSum']));
	tr_out('Кассир', $rowR['operator']);
	tr_out('taxationType', $rowR['taxationType']);
	tr_out('НДС 18%', money_to_str($rowR['nds18']));
	tr_out('НДС 10%', money_to_str($rowR['nds10']));
	tr_out('НДС', money_to_str($rowR['ndsNo']));
	tr_out('ИНН продавца', $rowR['UserInn']);
	tr_out('senderAddress', $rowR['senderAddress']);
	tr_out('receiptCode', $rowR['receiptCode']);
	tr_out('fiscalSign', $rowR['fiscalSign']);
	tr_out('fiscalDocumentNumber', $rowR['fiscalDocumentNumber']);
	tr_out('requestNumber', $rowR['requestNumber']);
?>
	</table>
<br/>
Чек в неразобранном формате:<br/>
<?=$rowR['rawReceipt']?>
</body>
</html>
