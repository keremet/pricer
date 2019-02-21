<?
	define('OK', "{\"status\":\"OK\"}");
	define('ERROR', "{\"error\":\"ERROR\"}");		

	include '../template/connect.php';


	if($_POST['operation'] === 'get') {
		
		$stmt = $db->query('SELECT r.user, r.userInn, r.retailPlaceAddress, r.dateTime, r.id, u.login
					FROM '.DB_TABLE_PREFIX.'receipt r
                      JOIN '.DB_TABLE_PREFIX.'users u on r.user_id = u.id
					WHERE not exists(
					 SELECT 1
					 FROM '.DB_TABLE_PREFIX.'receipt_to_shop s
					 WHERE s.inn = r.userInn 
						AND ((s.name = r.user) OR ( s.name is null AND r.user is null ))
						AND ((s.address = r.retailPlaceAddress) OR ( s.address is null AND r.retailPlaceAddress is null ))
					)
					ORDER BY userInn');
		if($stmt) {
			$n = $stmt->rowCount();
			$arr = array();
			foreach($stmt as $row) {
				$arr[] = array('name' => $row['user'],
							   'inn' => $row['userInn'],
							   'address' => $row['retailPlaceAddress'],
							   'id' => $row['id'],
							   'login' => $row['login'],
							   'dateTime' => $row['dateTime']);
			}
			
			print json_encode($arr);
		}
		else
			print ERROR;
		
		exit;
	}

	include '../template/header.php';
	
	headerOut('Неизвестные магазины', array('shop'));
	
	if($_SESSION['user']['id']) {
	
	include '../template/oft_table.php';
	include '../template/jstree/jstree.php';

?>

<table>
<tr>
<td valign="top">
<?
	putTree('shop', '../shops/');
?>
</td>
<td valign="top">
<?
	oftTable::init('Неизвестные магазины', 'itemsTable');

	oftTable::header(array('Название', 'ИНН', 'Адрес', 'Добавлен', 'Действия'));

	
	oftTable::end();
?>
</td>
</tr>
</table>

<script type='text/javascript'>
	
	var shopId = undefined;
	var table;
	var data;
	
	function tableRefresh() {
		
		jQuery.ajax({
			url:     'unknown_shops.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {operation:'get'}, 
			success: function(result) {
				if(result) {
					data = JSON.parse(result);
					if(data.error) {
						alert("Ошибка выполнения запроса");
						return;
					}
					
					while(table.rows.length > 1)
						table.deleteRow(1);
					for(var i = 0; i < data.length; i++) {
						var d = data[i];
						var row = table.insertRow();
						var cell = row.insertCell();
						cell.innerHTML = d.name;
						
						cell = row.insertCell();
						cell.innerHTML = d.inn;
						
						cell = row.insertCell();
						cell.innerHTML = d.address;
						
						cell = row.insertCell();
						cell.innerHTML = "<a href=receipt.php?id=" + d.id + ">" + d.dateTime + "</a> " + d.login;
						
						cell = row.insertCell();
						cell.innerHTML = "<input type='submit' value='Добавить' onclick='beginAddRule(" + d.id + ")'>";
					}
				}
				else
					alert("Ошибка загрузки магазинов");
			}
		});
		
	}
	
	function beginAddRule(receiptId) {
		if(shopId === undefined){
			alert("Выберите магазин");
			return;
		}
		
		jQuery.ajax({
			url:     'known_shops_item_add.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {id: shopId, receiptId: receiptId}, 
			success: function(result) {
				if(result && JSON.parse(result).status == "OK")
					tableRefresh();
				else
					alert("Ошибка добавления магазина в таблицу трансляции");
			}
		});
	}
	
	function shop_select(id){
		shopId = id;
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
