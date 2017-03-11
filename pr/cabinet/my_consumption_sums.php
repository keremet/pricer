<?//session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

//~ if($_SESSION['user']['id']==null)
	//~ die('Требуется авторизация');



function consSums($idClsf, $parentIndex, &$consSumP){
	global $db;
	
	$stmt = $db->prepare(
	"SELECT name
	 FROM pr_consumption_clsf
	 WHERE id = ?"
	);
	$stmt->execute(array($idClsf));
	$consName = $stmt->fetchColumn();
	
	$stmt = $db->prepare(
	"SELECT ifnull(sum(price), 0)
	 FROM pr_consumption
	 WHERE clsf_id = ?"
	);
	$stmt->execute(array($idClsf));
	$consSum = $stmt->fetchColumn();
	
	
	
	$stmt = $db->prepare(
	"SELECT id, name
	 FROM pr_consumption_clsf
	 WHERE id_hi = ?
	 ORDER BY name"
	);
	$stmt->execute(array($idClsf));
	$detRows = "";
	$i = 1;
	foreach($stmt->fetchAll() as $k => $v){
		$detRows .= consSums($v['id'], $parentIndex.$i.'.', $consSum);
		$i++;
	}
	$consSumP += $consSum;
	return 
"<tr>
<td>$parentIndex</td>
<td>$consName</td>
<td align='right'>$consSum</td>
</tr>$detRows";
}
$s=0;
?>
<html>
<body>
<table border=1>
	<thead>
		<tr>
			<th class="header">Индекс</th>
			<th class="header">Расход</th>
			<th class="header">Сумма</th>
		</tr>
	</thead>
	<tbody>
	<?=consSums(1, '', $s);?>
	</tbody>
</table>
</body>
</html>
