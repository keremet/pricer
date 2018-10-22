<?session_start();
header( "Content-Type: text/html; charset=utf-8" );

if($_SESSION['user']['id']==null)
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
	
	if ($_POST['id']!=null) {
		if ($_POST['oper_type'] == 'delete') {
			execStmt("DELETE FROM ".DB_TABLE_PREFIX."receipt WHERE id = ?", array($_POST['id']));
		} else {
            execStmt("UPDATE ".DB_TABLE_PREFIX."receipt SET ....
					  WHERE id = ? and user_id = ?",
                            array($_POST['date_cor'].$_POST['time']
                             ,$_POST['summa']
                             ,$_POST['fdn']
                             ,$_POST['fdoc']
                             ,$_POST['fs']
                             ,$_POST['id']
                             ,$_SESSION['user']['id'])); 
		}
	} else {
		execStmt("INSERT INTO ".DB_TABLE_PREFIX."receipt (dateTime, totalSum, fiscalDriveNumber, fiscalDocumentNumber, fiscalSign, user_id)
                          VALUES (STR_TO_DATE(?, '%d%m%Y%H%i%s'), ?, ?, ?, ?, ?)",
			array($_POST['date_cor'].$_POST['time']
                             ,$_POST['summa']
                             ,$_POST['fdn']
                             ,$_POST['fdoc']
                             ,$_POST['fs']
                             ,$_SESSION['user']['id']));                       
	}
?>
