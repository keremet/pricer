<?php
	include '../template/jstree/basetree.php';
	function showProduct($is_file, $id){
		global $db;
		$r = '<div style="width:320px; height:800px; overflow:auto;"><table border=1>
        <tr><td>Дата<td>Сумма<td>Действия';
		$stmt = $db->prepare(
			"SELECT id, date_buy, price
			 FROM ".DB_TABLE_PREFIX."consumption
			 WHERE clsf_id = ?
			 ORDER BY date_buy DESC"
		);
		$stmt->execute(array($id));
		while($consumption = $stmt->fetch()){
			$r .= '<tr><td>'.$consumption['date_buy'].'<td>'.$consumption['price']
				.'<td><button onclick="DeleteСonsumption('.$consumption['id'].');">Удалить</button>';
		}
		$r .= '</table></div>';
		return array('content' => $r, 'product_id' => $id);
	}
	
	doTreeOperation(DB_TABLE_PREFIX.'consumption_clsf', null, 'showProduct');
?>
