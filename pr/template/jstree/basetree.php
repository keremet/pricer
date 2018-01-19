<?php
	session_start();
	include('../template/connect.php');
	
	function checkRights(){
		if($_SESSION['user']['id']==null)
			throw new Exception('Требуется авторизация');
	}

	function doTreeOperation($tbl_dir, $tbl_term, $showTerm, $rootText = 'Корень'){
		global $db;
		$res = array();
		$id = $_GET['id'];
		if($id == '-1')
			$id = NULL;
		
		try {
			switch($_GET['operation']){
			case "get_node":
				if($_GET['id']=="#"){
					$qres = $db->query("select `id` from `$tbl_term`");
					$res[] = array('text' => $rootText, 
								   'children' => $qres->rowCount() != 0,  
								   'id' => '-1', 
								   'icon' => 'folder', 
								   'state' => array('opened' => 'true'));
				}else{
					$stmt = $db->prepare(
						"SELECT 
						`a`.`id` AS `id`,
						`a`.`name` AS `name`,
						(
							SELECT COUNT(*) != 0
							FROM `$tbl_term` `b` 
							WHERE `b`.`id_hi` = `a`.`id`
						) `is_group`
						FROM `$tbl_term` `a`" .
						($id == NULL ? "WHERE `a`.`id_hi` IS NULL" : "WHERE `a`.`id_hi` = ?"));
					$stmt->execute(array($id));
					foreach($stmt as $v)
						$res[] = array('text' => $v['name'], 
									   'children' => $v['is_group'] == 1 ? true : false,
									   'id' => $v['id'], 
									   'icon' => $v['is_group'] ? 'folder' : 'file');
				}
				break;
			case "create_node":
				checkRights();
				$stmt = $db->prepare("insert into $tbl_term(name, id_hi)values(?, ?)");
				$id = $_GET['id'];
				if($id == '-1')
					$id = null;
				$stmt->execute(array($_GET['text'], $id));
				$res = array('id' => $db->lastInsertId());
				break;
			case "rename_node":
				checkRights();
				$stmt = $db->prepare("update $tbl_term set name=? where id=?");
				$stmt->execute(array($_GET['text'], $_GET['id']));
				break;
			default:
				switch($_GET['operation']){
					case "copy_node":
						checkRights();
						$parent = $_GET['parent'];
						if($parent == '-1')
							$parent = NULL;
						$stmt = $db->prepare("insert into $tbl_term(name, ed_izm_id, in_box, id_hi, creator) 
										      select name, ed_izm_id, in_box, ?, ? from $tbl_term where id=?");
						$stmt->execute(array($parent, $_SESSION['user']['id'], $id));
						break;
					case "move_node":
						checkRights();
						$parent = $_GET['parent'];
						if($parent == '-1')
							$parent = NULL;
						
						$stmt = $db->prepare("update $tbl_term set id_hi=? where id=?");
						$stmt->execute(array($parent, $id));
						break;
					case "delete_node":
						checkRights();
						$stmt = $db->prepare("delete from $tbl_term where id=?");
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
