<?php
	session_start();
	include('../template/connect.php');
	
	function checkRights(){
		if($_SESSION['user']['id']==null)
			throw new Exception('Требуется авторизация');
	}

	function doTreeOperation($tbl_dir, $tbl_term, $showTerm){
		global $db;
		$res = array();
		try {
			switch($_GET['operation']){
			case "get_node":
				if($_GET['id']=="#"){
			//		$res[] = array('text' => 'Мои товары', 'children' => true,  'id' => '2', 'icon' => 'folder');
					foreach($db->query("select id, name from $tbl_dir where id_hi is null") as $v){
						$res[] = array('text' => $v['name'], 'children' => true,  'id' => $v['id'], 'icon' => 'folder', 'state' => array('opened' => 'true'));
					}
				}else{
					$stmt = $db->prepare("select id, name from $tbl_dir where id_hi=? order by name");
					$stmt->execute(array($_GET['id']));
					while($v = $stmt->fetch()){
						$res[] = array('text' => $v['name'], 'children' => true,  'id' => $v['id'], 'icon' => 'folder');
					}
					$stmt = $db->prepare("select id, name from $tbl_term where main_clsf_id=? order by name");
					$stmt->execute(array($_GET['id']));
					while($v = $stmt->fetch()){
						$res[] = array('text' => $v['name'], 'children' => false,  'id' => 'f'.$v['id'], 'type' => 'file', 'icon' => 'file');
					}
				}
				break;
			case "create_node":
				checkRights();
				$stmt = $db->prepare("insert into $tbl_dir(name, id_hi)values(?, ?)");
				$stmt->execute(array($_GET['text'], $_GET['id']));
				$res = array('id' => $db->lastInsertId());
				break;
			case "rename_node":
				checkRights();
				$stmt = $db->prepare("update $tbl_dir set name=? where id=?");
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
							:"insert into $tbl_dir(name, id_hi) select name, ? from pr_products_main_clsf where id=?");
						$stmt->execute($is_file?
							array($_GET['parent'], $_SESSION['user']['id'], $id)
							:array($_GET['parent'], $id));
						break;
					case "move_node":
						checkRights();
						$stmt = $db->prepare($is_file?
							"update $tbl_term set main_clsf_id=? where id=?"
							:"update $tbl_dir set id_hi=? where id=?");
						$stmt->execute(array($_GET['parent'], $id));
						break;
					case "delete_node":
						checkRights();
						$stmt = $db->prepare($is_file?
							"delete from $tbl_term where id=?"
							:"delete from $tbl_dir where id=?");
						if(!$stmt->execute(array($id))){
							throw new Exception('Ошибка удаления '.$stmt->errorInfo());
						}
						$res = array('status' => 'OK');
						break;
					case "get_content":
						$res = $showTerm($is_file, $id);
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
	}
?>
