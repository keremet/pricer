<?session_start();
header( "Content-Type: text/html; charset=utf-8" );

if($_SESSION['user']['id']==null)
	die("Требуется авторизация");
?>

<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Разбор чека</title>
</head>
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="receipt_list.php">Список чеков</a>
</table>
<br/>
<?php
	include "../template/connect.php";

	$stmtS = $db->prepare(
		"SELECT rawReceipt
		 FROM ".DB_TABLE_PREFIX."receipt
		 WHERE id = ?
		 ");
	$stmtS->execute(array($_GET['id']));
	$row = $stmtS->fetch();
	if(!$row)
		die("Чек не найден в БД Ценовичка");

	if ($row['rawReceipt'] == '')
		die("JSON не извлечен из БД ФНС");
		
	$doc = json_decode($row['rawReceipt'], true);
	
	parseReceipt($doc, $_GET['id']);
  
///////////

function parseReceipt($d, $id)
{
	global $db;
	
	$receipt = $d['document']['receipt'];

	$db->beginTransaction();
	$stmt = $db->prepare(
		'UPDATE '.DB_TABLE_PREFIX.'receipt 
		 SET buyerAddress = ?, addressToCheckFiscalSign = ?, rawData = ?, 
			kktRegId = ?, user = ?, operationType = ?, 
			shiftNumber = ?, ecashTotalSum = ?, nds18 = ?, retailPlaceAddress = ?, 
			userInn = ?, taxationType = ?, cashTotalSum = ?, operator = ?, 
			senderAddress = ?, receiptCode = ?, nds10 = ?, requestNumber = ?, ndsNo = ?
		 WHERE id = ?');

	$exec_prms = createQueryParams($receipt
		, array('buyerAddress', 'addressToCheckFiscalSign', 'rawData',
			 'kktRegId', 'user', 'operationType',
			 'shiftNumber', 'ecashTotalSum', 'nds18', 'retailPlaceAddress',
			 'userInn', 'taxationType', 'cashTotalSum', 'operator',
			 'senderAddress', 'receiptCode', 'nds10', 'requestNumber', 'ndsNo')
		, 'receipt');
	$exec_prms[] = $id;
	if(!$stmt->execute($exec_prms)
	) {
		$err_arr = $stmt->errorInfo();
		logMessage("Ошибка при разборе чека"
			, "Код ANSI ".$err_arr[0]." код из драйвера ".$err_arr[1]
			 ."<br> сообщение ".$err_arr[2]);
		$db->rollBack();
		return;
	}
	
	if(array_key_exists('items', $receipt)) {
		if(addItems($receipt['items'], $id) != 0){
			$db->rollBack();
			return;				
		}
	}
	$db->commit();
	logMessage("Разбор чека завершен успешно", '');
}

function addItems($items, $receiptId)
{
	global $db;
	
	foreach($items as $i){
		$stmt = $db->prepare(
			'INSERT INTO '.DB_TABLE_PREFIX.'receipt_item (sum, nds10, name, quantity, price, nds18, ndsNo, receipt_id)
			 VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
		$exec_prms = createQueryParams($i
			, array('sum', 'nds10', 'name', 'quantity', 'price', 'nds18', 'ndsNo')
			, 'item');
		$exec_prms[] = $receiptId;
		if(!$stmt->execute($exec_prms)) {
			$err_arr = $stmt->errorInfo();
			logMessage("Ошибка при добавлении товара"
				, "Код ANSI ".$err_arr[0]." код из драйвера ".$err_arr[1]
				 ."<br> сообщение ".$err_arr[2]);
			return 1;
		}

		if(array_key_exists('modifiers', $i)) {
			$itemId = $db->lastInsertId();
			foreach($i['modifiers'] as $m){
				$stmt = $db->prepare(
					'INSERT INTO '.DB_TABLE_PREFIX.'receipt_modifier (discountName, discountSum, markupName, item_id)
					 VALUES (?, ?, ?, ?)');
				$exec_prms = createQueryParams($m
					, array('discountName', 'discountSum', 'markupName')
					, 'modifier');
				$exec_prms[] = $itemId;
				if(!$stmt->execute($exec_prms)) {
					$err_arr = $stmt->errorInfo();
					logMessage("Ошибка при добавлении модификатора"
						, "Код ANSI ".$err_arr[0]." код из драйвера ".$err_arr[1]
						 ."<br> сообщение ".$err_arr[2]);
					return 1;
				}
			}
		}
	}
    
    return 0;
}


/**
 * Формирует массив параметров для запроса на добавление строки в таблицу по указанным полям ассоциативного массива.
 * В случае если какой-либо из тэгов отстутствует в исходном массиве, вместо него подставляет значение по умолчанию для данного типа.
 * Все несовпадения выводит в лог.
 * @param $arr Ассоциативный массив с данными.
 * @param $tags Массив строк-названий тегов.
 * @param $loc Местоположение тегов для логирования.
 * @return Возвращает массив параметров для передачи в execute.
 */
function createQueryParams($arr, $tags, $loc = "?")
{
    $n = count($tags);
    for($i = 0; $i < $n; $i++)
        $res[$i] = null;

    foreach($arr as $key => $value)
    {
        $srch = array_search($key, $tags);
        if($srch !== false) 
            $res[$srch] = $value;
        else {
            if(!in_array($key, array('items', 'modifiers')))
                tagIsUnknownLog($loc, $key);
        }
    }

    return $res;
}

function tagIsUnknownLog($location, $tagName)
{
    logMessage("Неизвестный тег в json файле", "$location => $tagName");
}

function tagNotExistsLog($location, $tagName)
{
    logMessage("Ожидаемый в json файле тег остутствует", "$location => $tagName");
}

function logMessage($title, $msg)
{
    $data = "$title<br>$msg<br><hr>";
    //fwrite(STDOUT, $data);
    echo $data;
}
?>
