<?php
	session_start();
	include('../template/connect.php');
	
	function showProduct($is_file, $id){
		global $db;
		$readonly = ($_SESSION['user']['id']==null);
		if($is_file){
			$stmt = $db->prepare(
				"SELECT pr_products.name, photo, pr_ed_izm.name as ed_izm, ed_izm_id, in_box, min_kolvo
				FROM pr_products
				LEFT JOIN pr_ed_izm on pr_ed_izm.id = pr_products.ed_izm_id
				WHERE pr_products.id = ?"
			);
			$stmt->execute(array($id));
			if(!($product = $stmt->fetch())){
				return 'Товар не найден';
			}
		}else{
			if($readonly)
				return '';
		}
		
		$r = '<form action="newonsubmit.php" method="post" enctype = "multipart/form-data">'
		  .'Название товара*<br>'
		  .'<input '.(($readonly)?'readonly':'').' required type="text" name="product_name" value="'.$product['name'].'"><br><br>';
		if($product['photo']){
			$r .= '<div style=" height: 140px; width: 140px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">'
			  .'<a class="fancybox" href="'.$product['photo'].'"><img style=" max-width: 140px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="'.$product['photo'].'"></a>'
			  .'</div><br>';
		}
		if($readonly){
			$r .= '<br>';
		}else{
			$r .= 'Фото (изображение не больше 1 Мб)<br><input type="file" name="image" /><br><br>';
		}
		$r .= 'Единица измерения<br>';
		if($readonly){
			$r .= '<input readonly type="text" name="ed_izm" value="'.$product['ed_izm'].'">';
		} else {
			$r .= '<select id="ed_izm" name="ed_izm">
				<option selected disabled>Выберите единицу измерения...</option>';
				foreach($db->query("SELECT id, name FROM pr_ed_izm order by id") as $v){
					$r .= '<option '.(($v['id']==$product['ed_izm_id'])?'selected':'').' value="'.$v['id'].'">'.$v['name'].'</option>';
				}
			$r .= '</select>';
		}
		$r .= '<br><br>Количество единиц измерения в товаре<br>
		<input '.(($readonly)?'readonly':'').' type="text" name="in_box" value="'.$product['in_box'].'"><br><br>'
		.'Минимальное количество товара, которое можно купить(в ед. изм.)<br>
		<input '.(($readonly)?'readonly':'').' type="text" name="min_kolvo" value="'.$product['min_kolvo'].'">';
		
		if(!$readonly){
			$r .= '<br><br><input type="submit" value="'.(($is_file)?'Изменить':'Добавить').' товар">';
			if($is_file)
				$r .= '<input type="hidden" name="id" value="'.$id.'">';
			else
				$r .= '<input type="hidden" name="main_clsf_id" value="'.$id.'">';
		}
		$r .= '</form>';
		return $r;
	}

	function checkRights(){
		if($_SESSION['user']['id']==null)
			throw new Exception('Требуется авторизация');
	}

	$res = array();
	try {
		switch($_GET['operation']){
		case "get_node":
			if($_GET['id']=="#"){
		//		$res[] = array('text' => 'Мои товары', 'children' => true,  'id' => '2', 'icon' => 'folder');
				foreach($db->query("select id, name from pr_products_main_clsf where id_hi is null") as $v){
					$res[] = array('text' => $v['name'], 'children' => true,  'id' => $v['id'], 'icon' => 'folder');
				}
			}else{
				$stmt = $db->prepare("select id, name from pr_products_main_clsf where id_hi=? order by name");
				$stmt->execute(array($_GET['id']));
				while($v = $stmt->fetch()){
					$res[] = array('text' => $v['name'], 'children' => true,  'id' => $v['id'], 'icon' => 'folder');
				}
				$stmt = $db->prepare("select id, name from pr_products where main_clsf_id=? order by name");
				$stmt->execute(array($_GET['id']));
				while($v = $stmt->fetch()){
					$res[] = array('text' => $v['name'], 'children' => false,  'id' => 'f'.$v['id'], 'type' => 'file', 'icon' => 'file');
				}
			}
			break;
		case "create_node":
			checkRights();
			$stmt = $db->prepare("insert into pr_products_main_clsf(name, id_hi)values(?, ?)");
			$stmt->execute(array($_GET['text'], $_GET['id']));
			$res = array('id' => $db->lastInsertId());
			break;
		case "rename_node":
			checkRights();
			$stmt = $db->prepare("update pr_products_main_clsf set name=? where id=?");
			$stmt->execute(array($_GET['text'], $_GET['id']));
			break;
		default:
			$is_file = (substr($_GET['id'], 0, 1) == 'f');
			$id = $is_file?substr($_GET['id'], 1):$_GET['id'];
			switch($_GET['operation']){
				case "copy_node":
					checkRights();
					$stmt = $db->prepare($is_file?
						"insert into pr_products(name, ed_izm_id, in_box, min_kolvo, main_clsf_id, creator) 
							select name, ed_izm_id, in_box, min_kolvo, ?, ? from pr_products where id=?"
						:"insert into pr_products_main_clsf(name, id_hi) select name, ? from pr_products_main_clsf where id=?");
					$stmt->execute($is_file?
						array($_GET['parent'], $_SESSION['user']['id'], $id)
						:array($_GET['parent'], $id));
					break;
				case "move_node":
					checkRights();
					$stmt = $db->prepare($is_file?
						"update pr_products set main_clsf_id=? where id=?"
						:"update pr_products_main_clsf set id_hi=? where id=?");
					$stmt->execute(array($_GET['parent'], $id));
					break;
				case "delete_node":
					checkRights();
					$stmt = $db->prepare($is_file?
						"delete from pr_products where id=?"
						:"delete from pr_products_main_clsf where id=?");
					if(!$stmt->execute(array($id))){
						throw new Exception('Ошибка удаления '.$stmt->errorInfo());
					}
					$res = array('status' => 'OK');
					break;
				case "get_content":
					$res = array('content' => showProduct($is_file, $id));
					break;
			}
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($res);
	}
	catch (Exception $e) {
		header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
		header('Status:  500 Server Error');
		echo $e->getMessage();
	}
?>
