<?
	define('OK', "{\"status\":\"OK\"}");
	define('ERROR', "{\"error\":\"ERROR\"}");		

	include '../template/connect.php';


	if($_POST['operation'] === 'get') {
		
		$stmt = $db->query('SELECT `name`, `inn`, `address`, `date` FROM `pr_items_unknown`');
		if($stmt) {
			$n = $stmt->rowCount();
			$arr = array();
			foreach($stmt as $row) {
				$arr[] = array('name' => $row['name'],
							   'inn' => $row['inn'],
							   'address' => $row['address'],
							   'date' => $row['date']);
			}
			
			print json_encode($arr);
		}
		else
			print ERROR;
		
		exit;
	}

	include '../template/header.php';
	
	headerOut('Неизвестные товары', array('prod'));
	
	if($_SESSION['user']['id']) {
	
	include '../template/oft_table.php';
	include '../template/jstree/jstree.php';

?>

<table>
<tr>
<td valign="top">
<?
	putTree('prod', '');
?>
</td>
<td valign="top">
<?
	oftTable::init('Неизвестные товары', 'itemsTable');

	oftTable::header(array('Название', 'ИНН', 'Адрес', 'Добавлен', 'Действия'));

	
	oftTable::end();
?>
</td>
</tr>
</table>

<script type='text/javascript'>
	
	var prodId = undefined;
	var table;
	var data;
	
	function tableRefresh() {
		
		jQuery.ajax({
			url:     'unknown_products.php', //Адрес подгружаемой страницы
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
						cell.innerHTML = d.date;
						
						cell = row.insertCell();
						cell.innerHTML = "<input type='submit' value='Добавить' onclick='beginAddRule(document.getElementById(\"itemsTable\").rows[" + (i + 1) + "], " + i + ")'>";
					}
				}
				else
					alert("Ошибка загрузки товаров");
			}
		});
		
	}
	
	function beginAddRule(row, ind) {
		if(prodId === undefined)
			return;
		
		name = data[ind].name;
		inn = data[ind].inn;
		jQuery.ajax({
			url:     'translation_table.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {operation:'item_add', id: prodId, name: name, inn: inn}, 
			success: function(result) {
				if(result && JSON.parse(result).status == "OK")
					tableRefresh();
				else
					alert("Ошибка добавления товара в таблицу трансляции");
			}
		});
	}
	
	function product_select(id){
		prodId = id;
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