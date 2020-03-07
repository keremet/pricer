<?php
include('../../template/connect.php');

foreach($db->query(
   "SELECT id, checked, if(rawReceipt is Null or rawReceipt='', '0', '1') rawLoaded, if(exists(SELECT 1 FROM receipt_user u where u.user_id=r.user_id),1,0) u_acc_exists
	FROM receipt r
	WHERE NOT EXISTS (
		SELECT 1 
		FROM receipt_item i
		WHERE i.receipt_id = r.id
	) AND (rawReceipt is Null or rawReceipt != 'the ticket was not found')
	ORDER BY u_acc_exists DESC, id") as $row)
  echo $row['id']." ".$row['checked']." ".$row['rawLoaded']."\n";
?>
