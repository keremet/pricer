<?

define('OK', "{\"status\":\"OK\"}");
define('ERROR', "{\"error\":\"ERROR\"}");

include '../template/connect.php';

if(isset($_POST['operation']))
{
	$op = $_POST['operation'];
	
	if($op == 'get') {
		
		$id = isset($_POST['cl_id']) ? $_POST['cl_id'] : false;
		$stmt = $db->prepare('SELECT `a`.`id` AS `id`, 
						   `a`.`product_id` AS `cl_id`, 
						   `a`.`name` AS `name`, 
						   `a`.`inn` AS `inn`,
						   `b`.`name` AS `cl_name`
					FROM '.DB_TABLE_PREFIX.'receipt_item_to_product `a`
					JOIN '.DB_TABLE_PREFIX.'products `b` ON `a`.`product_id` = `b`.`id`
					WHERE ' . ($id ? "`a`.`product_id` = ?" : '1'));
		$params = array();
		if($id)
			$params[] = $id;
					
		if($stmt->execute($params)) {
			
			$n = $stmt->rowCount();
			$arr = array();
			foreach($stmt as $row) {
				$arr[] = array('id' => $row['id'],
							   'cl_id' => $row['cl_id'],
							   'name' => $row['name'],
							   'inn' => $row['inn'],
							   'cl_name' => $row['cl_name']);
			}
			
			print json_encode($arr);
		}
		else
			print ERROR;
	
	}
	else if($op == 'get_path') {
		
		/*if(isset($_POST['id'])) {
			$id = $_POST['id'];
			$stmt = $db->prepare('select '.DB_TABLE_PREFIX.'get_product_path(?) AS `path`');
			
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
			$stmt = $db->prepare('DELETE FROM '.DB_TABLE_PREFIX.'receipt_item_to_product WHERE id=?');
			
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

headerOut('Соответствие товара', array('prod'));

?>

<table>
<tr>
<td valign="top">

<?
putTree('prod', '../products/');
?>

</td>
<td valign="top">
<?
	if($_SESSION['user_id']) {
		
		oftTable::init('Выбранный товар:', 'selectedItemTable');

		oftTable::header(array('ИНН', 'Наименование', 'Товар(Классификатор)', 'Действия'));
		oftTable::end();
		
		oftTable::init('Соответствие товара', 'itemsTable');

		oftTable::header(array('ИНН', 'Наименование', 'Товар(Классификатор)', 'Действия'));
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
			url:     'known_products.php', //Адрес подгружаемой страницы
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
						cell.innerHTML = d.inn;
						
						cell = row.insertCell();
						cell.innerHTML = d.name;
						
						cell = row.insertCell();
						cell.innerHTML = "<a href=\"#\" onclick=\"showTreeItem('" + d.cl_id + "')\">" + d.cl_name + "</a>";
						
						cell = row.insertCell();
						cell.innerHTML = "<input type='submit' value='Удалить' onclick='removeRule(" + d.id + ")'>";
					}
				}
				else
					alert("Ошибка загрузки товаров");
			}
		});
		
	}
	
	function showTreeItem(id) {
		var inst = $.jstree.reference("#treeprod");
		var node = inst.get_node(id);
		if(node === false) {
			curNodeId = id;
			jQuery.ajax({
			url:     'known_products.php', //Адрес подгружаемой страницы
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
						alert("Товар не найден");
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
	
	function removeRule(id) {
		
		jQuery.ajax({
			url:     'known_products.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {operation:'item_remove', id: id}, 
			success: function(result) {
				if(result && JSON.parse(result).status == "OK") {
					tableRefresh();
					product_select(id);
				}
				else
					alert("Ошибка удаления элемента");
			}
		});
		
	}
	
	function product_select(id) {
		
		jQuery.ajax({
			url:     'known_products.php', //Адрес подгружаемой страницы
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
						cell.innerHTML = d.inn;
						
						cell = row.insertCell();
						cell.innerHTML = d.name;
						
						cell = row.insertCell();
						cell.innerHTML = "<a href=\"#\" onclick=\"showTreeItem('" + d.cl_id + "')\">" + d.cl_name + "</a>";
						
						cell = row.insertCell();
						cell.innerHTML = "<input type='submit' value='Удалить' onclick='removeRule(" + d.id + ")'>";
					}
				}
				else
					alert("Ошибка загрузки товаров");
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

