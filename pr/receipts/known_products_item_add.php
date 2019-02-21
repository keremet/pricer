<?
session_start();
define('OK', "{\"status\":\"OK\"}");

include '../template/connect.php';
		
if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['inn'])) {   
    $stmt = $db->prepare('INSERT INTO '.DB_TABLE_PREFIX.'receipt_item_to_product (product_id, name, inn, user_id) VALUES (?, ?, ?, ?)');   
    if($stmt->execute(array($_POST['id'], $_POST['name'], $_POST['inn'], $_SESSION['user']['id'])))
        print OK;
    else {
        $errInfo = $stmt->errorInfo();
        print "{\"error\":\"ERROR inserting record:".$errInfo[2]."\"}";
    }
}
else
    print "{\"error\":\"ERROR in params\"}";
?>
