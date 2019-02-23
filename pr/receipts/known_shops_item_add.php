<?
session_start();
define('OK', "{\"status\":\"OK\"}");

include '../template/connect.php';
		
if(isset($_POST['id']) && isset($_POST['receiptId'])) {
    $id = (int)$_POST['id'];
    
    $stmt = $db->prepare(
        'INSERT INTO '.DB_TABLE_PREFIX.'receipt_to_shop (shop_id, inn, name, address, user_id)
         SELECT ?, userInn, user, retailPlaceAddress, ?
         FROM '.DB_TABLE_PREFIX.'receipt
         WHERE id = ?');

    if($stmt->execute(array($id, $_SESSION['user_id'], $_POST['receiptId'])))
        print OK;
    else {
        $errInfo = $stmt->errorInfo();
        print "{\"error\":\"ERROR inserting record:".$errInfo[2]."\"}";
    }
}
else
    print "{\"error\":\"ERROR in params\"}";
?>
