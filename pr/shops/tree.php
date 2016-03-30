<?php
	include '../template/jstree/basetree.php';

	function showProduct($is_file, $id){
		global $db;
		$readonly = ($_SESSION['user']['id']==null);
		if($is_file){
			$stmt = $db->prepare(
				"SELECT name, address
				FROM pr_shops
				WHERE id = ?"
			);
			$stmt->execute(array($id));
			if(!($shop = $stmt->fetch())){
				return 'Магазин не найден';
			}
		}else{
			if($readonly)
				return '';
		}
		
		$r = '<form action="newonsubmit.php" method="post" enctype = "multipart/form-data">'
		  .'Название магазина*<br>'
		  .'<input '.(($readonly)?'readonly':'').' required type="text" name="shop_name" value="'.$shop['name'].'"><br><br>'
		  .'Адрес<br>'
		  .'<input '.(($readonly)?'readonly':'').' type="text" name="address" value="'.$shop['address'].'">';
		
		if(!$readonly){
			$r .= '<br><br><input type="submit" value="'.(($is_file)?'Изменить':'Добавить').' магазин">';
			if($is_file)
				$r .= '<input type="hidden" name="id" value="'.$id.'">';
			else
				$r .= '<input type="hidden" name="main_clsf_id" value="'.$id.'">';
		}
		$r .= '</form>';
		return $r;
	}

	doTreeOperation('pr_shops_main_clsf', 'pr_shops', 'showProduct');
?>
