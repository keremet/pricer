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
				return array('content' => 'Магазин не найден');
			}
		}else{
			if($readonly)
				return array('content' => '');
		}
		
		$r = ((isset($_GET['smart_form']))
				?'<form action="newonsubmit.php" method="post" onsubmit="$.post(\'../smart_form/ajax/shop_new.php\', $(this).serialize(), function(data){var obj = $.parseJSON(data); if(obj.id){shop_select(obj.id, obj.name, \'\', \'\', obj.address);$(\'.fancybox-close\').click();}else{alert(data);}}); return false;" enctype = "multipart/form-data">'
				:'<form action="newonsubmit.php" method="post" enctype = "multipart/form-data">')
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
		return ($is_file)?
			array('content' => $r, 'shop_id' => $id)
			:array('content' => $r, 'shop_id' => '');
	}

	doTreeOperation('pr_shops_main_clsf', 'pr_shops', 'showProduct');
?>
