<?php
	include '../template/jstree/basetree.php';
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
				return array('content' => 'Товар не найден');
			}
		}else{
			if($readonly)
				return array('content' => '');
		}
		
		$r = ((isset($_GET['smart_form']))
				?'<form id="form_product" action="" method="post" onsubmit="$.post(\'../smart_form/ajax/product_new.php\', $(this).serialize(), function(data){var obj = $.parseJSON(data); if(obj.id){product_select(obj.id, obj.name);$(\'.fancybox-close\').click();}else{alert(data);}}); return false;" enctype = "multipart/form-data">'
				:'<form action="newonsubmit.php" method="post" enctype = "multipart/form-data">')
		  .'Название товара*<br>'
		  .'<input '.(($readonly)?'readonly':'').' required type="text" name="product_name" value="'.htmlspecialchars ($product['name']).'"><br><br>';
		if($product['photo']){
			$r .= '<div style=" height: 140px; width: 140px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">'
			  .'<a class="fancybox" href="'.$product['photo'].'"><img style=" max-width: 140px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="'.$product['photo'].'"></a>'
			  .'</div><br>';
		}
		if($readonly){
			$r .= '<br>';
		}else{
			$r .= 'Фото (изображение не больше 1 Мб)<br><input type="file" style="width: 150px;" name="image" /><br><br>';
		}
		$r .= 'Единица измерения<br>';
		if($readonly){
			$r .= '<input readonly type="text" name="ed_izm" value="'.$product['ed_izm'].'">';
		} else {
			$r .= '<select id="ed_izm" name="ed_izm" style="width: 150px;" >
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
		if($is_file)
			$r .= '<br><br><a target="_blank" href="../analytics/?product[]='.$id.'">Перейти к ценам</a>';
		if(!$readonly){
			$r .= '<br><br><input type="submit" value="'.(($is_file)?'Изменить':'Добавить').' товар">';
			if($is_file)
				$r .= '<input type="hidden" name="id" value="'.$id.'">';
			else
				$r .= '<input type="hidden" name="main_clsf_id" value="'.$id.'">';
		}
		$r .= '</form>';
		return ($is_file)?
			array('content' => $r, 'product_id' => $id, 'product_name' => $product['name'])
			:array('content' => $r, 'product_id' => '', 'product_name' => '');
	}
	
	doTreeOperation('pr_products_main_clsf', 'pr_products', 'showProduct');
?>
