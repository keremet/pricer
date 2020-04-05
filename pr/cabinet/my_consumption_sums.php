<?//session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

//~ if($_SESSION['user_id']==null)
	//~ die('Требуется авторизация');



function consSums($idClsf, $parentIndex, &$consSumP){
	global $db;
	
	$stmt = $db->prepare(
	"SELECT name
	 FROM consumption_clsf
	 WHERE id = ?"
	);
	$stmt->execute(array($idClsf));
	$consName = $stmt->fetchColumn();
	
	$stmt = $db->prepare(
	"SELECT ifnull(sum(price), 0)
	 FROM consumption
	 WHERE clsf_id = ? and date_buy >= STR_TO_DATE('01-05-2017','%d-%m-%Y')"
	);
	$stmt->execute(array($idClsf));
	$consSum = $stmt->fetchColumn();
	
	
	
	$stmt = $db->prepare(
	"SELECT id, name
	 FROM consumption_clsf
	 WHERE id_hi = ?
	 ORDER BY name"
	);
	$stmt->execute(array($idClsf));
	$detRows = "";
	$i = 1;
	$consSumChild = 0;
	foreach($stmt->fetchAll() as $k => $v){
		$detRows .= consSums($v['id'], $parentIndex.$i.'.', $consSumChild);
		$i++;
	}
	$consSumP += $consSum + $consSumChild;
	
	$stmt = $db->prepare(
		"INSERT into tmp_consumption_sums(`index`, `name`, `sum_own`, `sum_child`)
		 VALUES(?, ?, ?, ?)"
	);
	$stmt->execute(array($parentIndex, $consName, $consSum, $consSumChild));
}
$s=0;

//Очистка временной таблицы
$stmt = $db->prepare("DELETE FROM tmp_consumption_sums");
$stmt->execute();

consSums(1, '', $s);
?>
<html>
<body>
<table border=1>
	<thead>
		<tr>
			<th class="header">Индекс</th>
			<th class="header">Расход</th>
			<th class="header">Сумма общая</th>
			<th class="header">Сумма расхода</th>
			<th class="header">Сумма дочерняя</th>
		</tr>
	</thead>
	<tbody>
	<?
	$stmt = $db->prepare(
	"SELECT `index`, `name`, sum_own, sum_child, sum_own + sum_child sum_all
	 FROM tmp_consumption_sums
	 ORDER BY sum_all DESC"
	);
	$stmt->execute();
	foreach($stmt->fetchAll() as $k => $v){
		echo "<tr>"
			."<td>".$v['index']."</td>"
			."<td>".$v['name']."</td>"
			."<td align='right'>".$v['sum_all']."</td>"
			."<td align='right'>".$v['sum_own']."</td>"
			."<td align='right'>".$v['sum_child']."</td>"
			."</tr>";
	}	
	?>
	</tbody>
</table>
</body>
</html>
