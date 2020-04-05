<?session_start();
header( "Content-Type: text/html; charset=utf-8" );

if($_SESSION['user_id']==null)
	die("Требуется авторизация");


	include "../template/connect.php";
	function execStmt($qry, $arr) {
		global $db;
		$stmt = $db->prepare($qry);
		if (!$stmt->execute($arr)) {
?> 
<html>
	<head>
		<meta charset="utf-8">
	</head>
                <body>
			Ошибка 	<? print_r($stmt->errorInfo()); ?> 
                </body>
                       </html>		
<?
		} else {
			header('Location: receipt_list.php');
		}
	}
	$cor_time = $_POST['time'];
	if (strlen($cor_time) == 4)
		$cor_time .= '00';

	if ($_POST['id']!=null) {
		if ($_POST['oper_type'] == 'delete') {
			execStmt("DELETE FROM receipt WHERE id = ?", array($_POST['id']));
		} else {
            execStmt("UPDATE receipt SET ....
					  WHERE id = ? and user_id = ?",
                            array($_POST['date_cor'].$cor_time
                             ,$_POST['summa']
                             ,$_POST['fdn']
                             ,$_POST['fdoc']
                             ,$_POST['fs']
                             ,$_POST['id']
                             ,$_SESSION['user_id'])); 
		}
	} else {
		execStmt("INSERT INTO receipt (dateTime, totalSum, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign, user_id, ins_user_id)
                          VALUES (STR_TO_DATE(?, '%d%m%Y%H%i%s'), ?, ?, ?, ?, ?, ?)",
			array($_POST['date_cor'].$cor_time
                             ,$_POST['summa']
                             ,$_POST['fdn']
                             ,$_POST['fdoc']
                             ,$_POST['fs']
                             ,$_SESSION['user_id']
                             ,$_SESSION['user_id']));                       
	}
?>
