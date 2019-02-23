<?

define('OK', "{\"status\":\"OK\"}");
define('ERROR', "{\"error\":\"ERROR\"}");

include '../template/connect.php';

if(isset($_POST['operation']))
{
	$op = $_POST['operation'];
	
	if($op == 'get') {
		
		$id = isset($_POST['cl_id']) ? $_POST['cl_id'] : false;
		$stmt = $db->prepare('SELECT r2s.id, r2s.inn, r2s.name name_in_rcp, r2s.address, r2s.shop_id, s.name
							  FROM '.DB_TABLE_PREFIX.'receipt_to_shop r2s
                              JOIN '.DB_TABLE_PREFIX.'shops s ON s.id = r2s.shop_id
					          WHERE ' . ($id ? "`r2s`.`shop_id` = ?" : '1'));
		$params = array();
		if($id)
			$params[] = $id;
					
		if($stmt->execute($params)) {
			
			$n = $stmt->rowCount();
			$arr = array();
			foreach($stmt as $row) {
				$arr[] = array('id' => $row['id'],
							   'inn' => $row['inn'],
							   'name_in_rcp' => $row['name_in_rcp'],
							   'address' => $row['address'],
							   'shop_id' => $row['shop_id'],
							   'name' => $row['name']);
			}
			
			print json_encode($arr);
		}
		else
			print ERROR;
	
	}
	else if($op == 'get_path') {
		
		/*if(isset($_POST['id'])) {
			$id = $_POST['id'];
			$stmt = $db->prepare('select '.DB_TABLE_PREFIX.'get_shop_path(?) AS `path`');
			
			if($stmt->execute(array($id))) {
				$path = $stmt->fetch()['path'];
				if(empty($path))
					print '"path":[]';
				else {
					$path .= $id;
					$arr = explode('/', $path);
					
					$pathArr = array('path' => $arr);
					print json_encode($pathArr);
				}
			}
			else
				print ERROR;
		}
		else */
			print ERROR;
		
	}
	else if($op == 'item_remove') {
		
		if(isset($_POST['id'])) {
			
			$id = $_POST['id'];
			$stmt = $db->prepare('DELETE FROM '.DB_TABLE_PREFIX.'receipt_to_shop WHERE id=?');
			
			if($stmt->execute(array($id)))
				print OK;
			else
				print ERROR;
		}
		else
			print ERROR;
	}
	else
		print ERROR;
	
	exit;
}

include '../template/header.php';
include '../template/oft_table.php';
include '../template/jstree/jstree.php';

headerOut('Соответствие магазина', array('shop'));

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
	if($_SESSION['user_id']) {
		
		oftTable::init('Выбранный магазин:', 'selectedItemTable');

		oftTable::header(array('Название в чеке', 'ИНН', 'Адрес', 'Действия'));
		oftTable::end();
		
		oftTable::init('Соответствие магазина', 'itemsTable');

		oftTable::header(array('Название в чеке', 'ИНН', 'Адрес', 'Название', 'Действия'));
		oftTable::end();
?>

</td>
</tr>
</table>

<script type="text/javascript">

	var table, upTable;
	var data;
	var curNodeId;

	function tableRefresh() {
		
		jQuery.ajax({
			url:     'known_shops.php', //Адрес подгружаемой страницы
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
						cell.innerHTML = d.name_in_rcp;
						
						var cell = row.insertCell();
						cell.innerHTML = d.inn;
						
						cell = row.insertCell();
						cell.innerHTML = d.address;
						
						cell = row.insertCell();
						cell.innerHTML = "<a href=\"#\" onclick=\"showTreeItem('" + d.cl_id + "')\">" + d.name + "</a>";
						
						cell = row.insertCell();
						cell.innerHTML = "<input type='submit' value='Удалить' onclick='removeRule(" + d.id + ", " + d.shop_id + ")'>";
					}
				}
				else
					alert("Ошибка загрузки магазинов");
			}
		});
		
	}
	
	function showTreeItem(id) {
		var inst = $.jstree.reference("#treeprod");
		var node = inst.get_node(id);
		if(node === false) {
			curNodeId = id;
			jQuery.ajax({
			url:     'known_shops.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {operation:'get_path', id: id}, 
			success: function(result) {
				var path = JSON.parse(result);
				if(path.error) {
					alert("Ошибка выполнения запроса");
					return;
				}
				path = path.path;
				var n = path.length - 1;
				for(var i = 0; i <= n; i++) {
					node = inst.get_node(path[i]);
					if(node) {
						if(i != n && !node.state.opened) {
							inst.open_node(node, function(obj) {
								if(obj && id == curNodeId)
									showTreeItem(id);
							});
							return;
						}
					}
					else {
						alert("Магазин не найден");
						return;
					}
				}
			}
			});
		}
		else {
			if(!node.state.selected) {
				inst.deselect_all();
				inst.select_node(node);
			}
		}
	}
	
	function removeRule(id, shop_id) {
		
		jQuery.ajax({
			url:     'known_shops.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {operation:'item_remove', id: id}, 
			success: function(result) {
				if(result && JSON.parse(result).status == "OK") {
					tableRefresh();
					shop_select(shop_id);
				}
				else
					alert("Ошибка удаления элемента");
			}
		});
		
	}
	
	function shop_select(id) {
		
		jQuery.ajax({
			url:     'known_shops.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {operation:'get', cl_id: id}, 
			success: function(result) {
				if(result) {
					data = JSON.parse(result);
					if(data.error) {
						alert("Ошибка выполнения запроса");
						return;
					}
					
					while(upTable.rows.length > 1)
						upTable.deleteRow(1);
					for(var i = 0; i < data.length; i++) {
						var d = data[i];
						var row = upTable.insertRow();
						var cell = row.insertCell();
						cell.innerHTML = d.name_in_rcp;
						
						var cell = row.insertCell();
						cell.innerHTML = d.inn;
						
						cell = row.insertCell();
						cell.innerHTML = d.address;
						
						cell = row.insertCell();
						cell.innerHTML = "<input type='submit' value='Удалить' onclick='removeRule(" + d.id + ", " + d.shop_id + ")'>";
					}
				}
				else
					alert("Ошибка загрузки магазинов");
			}
		});
		
	}
	
	$(document).ready(function() {
		table = document.getElementById('itemsTable');
		upTable = document.getElementById('selectedItemTable');
		tableRefresh();
	});
	
</script>


<? 
	}else
		print "Необходима авторизация";
include('../template/footer.php'); ?>

