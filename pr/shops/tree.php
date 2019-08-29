<?php
	include '../template/jstree/basetree.php';

	function showProduct($is_file, $id){
		global $db;
		$readonly = ($_SESSION['user_id']==null);
		if($is_file){
			$stmt = $db->prepare(
				"SELECT name, address
				FROM ".DB_TABLE_PREFIX."shops
				WHERE id = ?"
			);
			$stmt->execute(array($id));
			if(!($shop = $stmt->fetch())){
				return array('content' => 'Магазин не найден');
			}
		}else{
			if($readonly)
				return array('content' => '');
		}
		
		$r = '<form action="" method="post"'
		  .' onsubmit="$.post(\'../shops/save.php\','
		  .' $(this).serialize(),'
		  .' function(data){'
				.'var obj = $.parseJSON(data);'
				.'if(obj.id){'
					.'$(\'#treeshop\').jstree(true).refresh();'
					.'shop_select(obj.id);'
				.'}else{'
					.'alert(data);'
				.'}'
			.'}); return false;" enctype = "multipart/form-data">'				
		  .'Название магазина*<br>'
		  .'<input '.(($readonly)?'readonly':'').' required type="text" name="shop_name" value="'.htmlspecialchars ($shop['name']).'"><br><br>'
		  .'Адрес<br>'
		  .'<input '.(($readonly)?'readonly':'').' type="text" name="address" value="'.htmlspecialchars ($shop['address']).'">';
		
		if(!$readonly){
			$r .= '<br><br><input type="submit" value="'.(($is_file)?'Изменить':'Добавить').' магазин">';
			if($is_file)
				$r .= '<input type="hidden" name="id" value="'.$id.'">';
			else
				$r .= '<input type="hidden" name="main_clsf_id" value="'.$id.'">';
		}
		$r .= '</form>';

		if((!$readonly) && $is_file){
			$r .= '<hr>';
			$stmtFdn = $db->prepare(
				"SELECT fiscalDriveNumber
				FROM ".DB_TABLE_PREFIX."fdn_to_shop
				WHERE shop_id = ?"
			);
			$stmtFdn->execute(array($id));
			while ($fdn = $stmtFdn->fetch()) {
				$r .= $fdn['fiscalDriveNumber'].'<br>';
			}
			$r .= '<input type="text" name="shop_fdn" id="shop_fdn"><br><input type="submit" value="Добавить ID кассы" onclick="addFdn(); return false;">';
		}

		return ($is_file)?
			array('content' => $r, 'shop_id' => $id)
			:array('content' => $r, 'shop_id' => '');
	}

	doTreeOperation(DB_TABLE_PREFIX.'shops_main_clsf', DB_TABLE_PREFIX.'shops', 'showProduct');
?>
