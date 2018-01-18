<?php
	define('PRODUCTS_TABLE_NAME', 'pr_consumption_clsf');

	include '../template/jstree/basetree.php';
	function showProduct($is_file, $id){
		global $db;
		if($_REQUEST['suf']=='prices'){
			$r .= '<table border=1><tr><td>Цена<td>Магазин<td>Дата';
			$stmt = $db->prepare(
				"SELECT s.name, po1.date_buy, min(price) pr
				FROM pr_product_offers po1, pr_shops s
				WHERE po1.product = ?
				  and (po1.shop, po1.date_buy) in (
					SELECT shop, max(date_buy)
					FROM pr_product_offers
					WHERE product = ?
					GROUP BY shop
				  )
				  and po1.shop = s.id
				GROUP BY s.name, po1.date_buy
				ORDER BY pr"
			);
			$stmt->execute(array($id, $id));
			while($price = $stmt->fetch()){
				$r .= '<tr><td>'.$price['pr'].'<td>'.$price['name'].'<td>'.$price['date_buy'];
			}
			$r .= '</table>';
		}else{
			$readonly = ($_SESSION['user']['id']==null);
			
			$stmt = $db->prepare(
				"SELECT `a`.name, photo, `b`.name as ed_izm, ed_izm_id, in_box
				FROM " . PRODUCTS_TABLE_NAME . " `a`" . 
				"LEFT JOIN `pr_ed_izm` `b` on `b`.id = `a`.ed_izm_id
				WHERE `a`.id = ?"
			);
			$stmt->execute(array($id));
			if(!($product = $stmt->fetch())){
				return array('content' => 'Товар не найден');
			}
			
			$r = '<form id="form_product" action="" method="post"'
				 .'onsubmit="$.ajax({
						  type: \'POST\',
						  processData: false,
						  contentType: false,
						  url: \'../products/save.php\',
						  data:  new FormData(this),
						  success: function(data) {
							result = JSON.parse(data); 
							if(result[\'photo\']){
								$(\'#form_product\').find(\'img\').attr(\'src\', result[\'photo\']);
								$(\'#form_product\').find(\'a\').attr(\'href\', result[\'photo\']);
							}
							if(result[\'error\'] == \'0\')
								$(\'#message_product\').css(\'color\', \'green\').text(result[\'result\']);
							else
								$(\'#message_product\').css(\'color\', \'red\').text(result[\'result\']);
						  }
					}); return false;" enctype = "multipart/form-data"><div id="message_product"></div>'			
			  .'Название товара*<br>'
			  .'<input '.(($readonly)?'readonly':'').' required type="text" name="product_name" value="'.htmlspecialchars($product['name']).'"><br><br>';
			if($product['photo']){
				$ph = '/pricer/uploaded/'.$product['photo'];
			}else{
				$ph = '/pricer/images/noph_prod.png';
			}

			$r .= '<div style=" height: 140px; width: 140px; background-color: #EDEDED; border: 2px solid #AAAAAA; position: relative; display: inline-block;">'
			  .'<a class="fancybox" href="'.$ph.'"><img style=" max-width: 140px; max-height: 100%; margin:auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0;" src="'.$ph.'"></a>'
			  .'</div><br>';

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
			<input '.(($readonly)?'readonly':'').' type="text" name="in_box" value="'.$product['in_box'].'">';
				$r .= '<br><br><a target="_blank" href="../analytics/?product[]='.$id.'">Перейти к ценам</a>';
			if(!$readonly){
				$r .= '<br><br><input type="submit" value="Изменить товар">';
				
			$r .= '<input type="hidden" name="id" value="'.$id.'">';
			}
			$r .= '</form>';
		}
		return array('content' => $r, 'product_id' => $id);
	}
	
	doTreeOperation('', PRODUCTS_TABLE_NAME, 'showProduct');
?>
