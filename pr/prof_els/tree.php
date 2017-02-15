<?php
	include '../template/jstree/basetree.php';
	function showProduct($is_file, $id){
		global $db;
		$r = '<table border=1><tr><td>Дата<td>Сумма';
		$stmt = $db->prepare(
			"SELECT date_buy, price
			 FROM pr_consumption
			 WHERE prof_el = ? and creator = ?
			 ORDER BY date_buy DESC"
		);
		$stmt->execute(array($id, $_SESSION['user']['id']));
		while($consumption = $stmt->fetch()){
			$r .= '<tr><td>'.$consumption['date_buy'].'<td>'.$consumption['price'];
		}
		$r .= '</table>';
		return array('content' => $r, 'product_id' => $id);
	}
	
	doTreeOperation('pr_prof_els_main_clsf', null, 'showProduct');
?>
