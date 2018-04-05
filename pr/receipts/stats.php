<?

define('OK', "{\"status\":\"OK\"}");
define('ERROR', "{\"error\":\"ERROR\"}");

include '../template/connect.php';

if(isset($_POST['operation']))
{
	$op = $_POST['operation'];
	
	if(isset($_POST['date_from'])) {
		$pieces = explode("/", $_POST['date_from']);
		$dateFrom = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
	}
	if(isset($_POST['date_to'])) {
		$pieces = explode("/", $_POST['date_to']);
		$dateTo = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
	}
	
	$filter = isset($dateFrom) && isset($dateTo);
	
	if($op == 'get') {
		
		$params = array();
		$itemId = isset($_POST['itemId']) ? $_POST['itemId'] : false;
		
		if($itemId) {
			
			$req = "SELECT
				`b`.`user_id` AS `buyer_id`,
				`c`.`login` AS `buyer`,
				`b`.`dateTime` AS `date`,
				`a`.`name` AS `name`,
				`a`.`id` AS `pr_id`,
				null AS `group_id`,
				if(`b`.`sum` IS NULL, 0, `b`.`sum`) AS `sum`
				FROM ".DB_TABLE_PREFIX."products `a` 
				LEFT JOIN ".DB_TABLE_PREFIX."receipt_purchases `b` ON `a`.`id` = `b`.`product_id`
				LEFT JOIN ".DB_TABLE_PREFIX."users `c` ON `b`.`user_id` = `c`.`id`"
				.($filter ? 'WHERE `b`.`dateTime` BETWEEN DATE(?) AND DATE(?) ' : '');
			
		}
		else {
			
			$req = "SELECT
				`b`.`user_id` AS `buyer_id`,
				`a`.`name` AS `name`,
				`a`.`id` AS `pr_id`,
				null AS `group_id`,
				if(`b`.`sum` IS NULL, 0, `b`.`sum`) AS `sum`
				FROM ".DB_TABLE_PREFIX."products `a` 
				LEFT JOIN ".DB_TABLE_PREFIX."receipt_purchases `b` ON `a`.`id` = `b`.`product_id`
				LEFT JOIN ".DB_TABLE_PREFIX."users `c` ON `b`.`user_id` = `c`.`id` "
				.($filter ? 'WHERE `b`.`dateTime` BETWEEN DATE(?) AND DATE(?) ' : '');
			
		}
		
		if($filter) {
			$params[] = $dateFrom;
			$params[] = $dateTo;
		}
		
		$stmt = $db->prepare($req);
		if($stmt->execute($params)) {
			
			$res = array();
			
			$root = array(
						   'name' => 'Всего',
						   'pr_id' => '-1',
						   'buyer' => null,
						   'date' => null,
						   'buy_num' => 0,
						   'buyers_num' => 0,
						   'buyers' => [],
						   'sum' => 0,
						   'children' => []);
			
			$i = 0;
			foreach($stmt as $row) {
				$empty = $row['buyer_id'] === null;
				$res[] = array(
						   'name' => $row['name'],
						   'pr_id' => $row['pr_id'],
						   'buyer' => $row['buyer'],
						   'date' => $row['date'],
						   'group_id' => $row['group_id'],
						   'buy_num' => $empty ? 0 : 1,
						   'buyers_num' => 0,
						   'buyers' => ($empty ? [] : 
												array($row['buyer_id'] => false)),
						   'sum' => $row['sum'],
						   'children' => []);
			}
			
			$arr = array();
			foreach($res as &$val) {
				$id = $val['pr_id'];
				$empty = count($val['buyers']) == 0;
				
				if(array_key_exists($id, $arr)) {
					
					if(!$empty)
						$arr[$id]['buyers'][key($val['buyers'])] = true;
					
					$arr[$id]['buy_num'] += $val['buy_num'];
					$arr[$id]['sum'] += $val['sum'];
				}
				else
					$arr[$id] = $val;
			}
			
			if($itemId) {
				
				foreach($arr as &$val) {
					$par = $val['group_id'];
					if($par === null) {
						$root['children'][] = &$val;
						$val['group_id'] = '-1';
					}
					else
						$arr[$par]['children'][] = &$val;
				}
				
				$ids = array();
				getChildrenIds($root, $itemId, $ids);
				$n = count($res);
				for($i = 0; $i < $n; $i++) {
					unset($res[$i]['children'], 
						  $res[$i]['buyers'],
						  $res[$i]['group_id']);
					if($res[$i]['sum'] == 0 || !array_key_exists($res[$i]['pr_id'], $ids)) {
						array_splice($res, $i, 1); 
						$i--; $n--;
					}
				}
			}
			else {
				$res = array();
				foreach($arr as &$val) {
					$par = $val['group_id'];
					if($par === null) {
						$root['children'][] = &$val;
						$val['group_id'] = '-1';
					}
					else
						$arr[$par]['children'][] = &$val;
					
					$res[] = &$val;
				}
				countNodeSums($root);
				for($i = 0; $i < $n; $i++) {
					unset($res[$i]['children'], 
						  $res[$i]['buyers'],
						  $res[$i]['group_id']);
				}
				array_unshift($res, $root);
			}
			
			print json_encode($res);
		}
		else
			print ERROR;
	}
	else
		print ERROR;
	
	exit;
}

function getChildrenIds(&$node, $id, &$res) {
	
	if($node['pr_id'] === $id) {
		$res[$id] = true;
		foreach($node['children'] as &$val)
			getChildrenIds($val, $val['pr_id'], $res);
	}
	else {
		foreach($node['children'] as &$val)
			getChildrenIds($val, $id, $res);
	}
}

function countNodeSums(&$node) {
	foreach($node['children'] as &$val) {
		countNodeSums($val);
		$node['sum'] += $val['sum'];
		
		foreach($val['buyers'] as $usrId => $checked)
			$node['buyers'][$usrId] = true;
		$node['buy_num'] += $val['buy_num'];
	}
	$node['buyers_num'] = count($node['buyers']);
}

	include '../template/header.php';
	include '../template/oft_table.php';

	headerOut('Статистика покупок товаров', array('prod'));
	
	if($_SESSION['user']['id']) {

?>

<script type="text/javascript" src="../template/input_calendar/tcal.js"></script>
<link rel="stylesheet" href="../template/input_calendar/tcal.css"/>
<table align="center">
<tr>
<td>
	
	Период:&nbsp
	<input type="checkbox" name="filter[]" value="date" style="display:none">
	от&nbsp;<input type="text" readonly class="tcal" id="date_from" value="">
	&nbsp;до&nbsp;<input type="text" readonly class="tcal" id="date_to" value="">
	<input type="submit" name="send" value="Фильтровать" onclick="applyFilter(document.getElementById('date_from').value, document.getElementById('date_to').value)">
<br>


</td>
</tr>
<tr>
<td>
<?
		

oftTable::init('Статистика покупок товаров', 'itemsTable');

oftTable::end();
?>
</td>
</tr>
</table>

<script type="text/javascript">

	var table;
	var data;
	var fromFilter = null, toFilter = null, filterApplied = false;
	var state = 0; 
	var lastItemId = null;
	
	function applyFilter(fromVal, toVal) {
		
		fromFilter = fromVal;
		toFilter = toVal;
		filterApplied = true;
		switch(state) {
			case 0:
				tableRefresh();
				break;
			case 1:
				showItemStats(lastItemId);
				break;
		}
	}

	function tableRefresh() {
		
		var params = {operation:'get'};
		if(filterApplied) {
			params.date_from = fromFilter;
			params.date_to = toFilter;
		}
		
		jQuery.ajax({
			url:     'stats.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: params, 
			success: function(result) {
				if(result) {
					data = JSON.parse(result);
					if(data.error) {
						alert("Ошибка выполнения запроса");
						return;
					}
					
					state = 0;
					while(table.rows.length > 0)
						table.deleteRow(0);
					
					oftTableHeader(document.getElementById('itemsTable'), 'Товар/группа', 'Количество покупок', 'Количество участников', 'Итого затрат');
					for(var i = 0; i < data.length; i++) {
						var d = data[i];
						var row = table.insertRow();
						var cell = row.insertCell();
						cell.innerHTML = d.name;
						
						cell = row.insertCell();
						cell.innerHTML = d.buy_num;
						
						cell = row.insertCell();
						cell.innerHTML = "<a href=# onclick=\"showItemStats(\'" + d.pr_id + "\')\">" +  d.buyers_num + "</a>";
						
						cell = row.insertCell();
						cell.innerHTML = d.sum;
					}
				}
				else
					alert("Ошибка загрузки товаров");
			}
		});
		
	}
	
	function showItemStats(itemId) {
		lastItemId = itemId;
		
		var params = {operation:'get', itemId: itemId};
		if(filterApplied) {
			params.date_from = fromFilter;
			params.date_to = toFilter;
		}
		
		jQuery.ajax({
			url:     'stats.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: params, 
			success: function(result ) {
				if(result) {
					data = JSON.parse(result);
					if(data.error) {
						alert("Ошибка выполнения запроса");
						return;
					}
					
					state = 1;
					while(table.rows.length > 0)
						table.deleteRow(0);
					
					var row = table.insertRow().innerHTML = "<a href=# onclick=\"tableRefresh()\">\<-назад</a>";
					
					oftTableHeader(document.getElementById('itemsTable'), 'Товар', 'Время', 'Участник', 'Сумма');
					for(var i = 0; i < data.length; i++) {
						var d = data[i];
						row = table.insertRow();
						
						cell = row.insertCell();
						cell.innerHTML = d.name;
						
						cell = row.insertCell();
						cell.innerHTML = d.date;
						
						cell = row.insertCell();
						cell.innerHTML = d.buyer;
						
						cell = row.insertCell();
						cell.innerHTML = d.sum;
					}
				}
				else
					alert("Ошибка загрузки товаров");
			}
		});
		
	}
	
	$(document).ready(function() {
		table = document.getElementById('itemsTable');
		tableRefresh();
	});
	
</script>

<? 
	}else
		print "Необходима авторизация";
include('../template/footer.php'); ?>
