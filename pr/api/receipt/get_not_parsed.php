<?php
include('../../template/connect.php');

foreach($db->query(
   "SELECT id, checked, if(rawReceipt is Null, '0', '1') rawLoaded
	FROM ".DB_TABLE_PREFIX."receipt r
	WHERE NOT EXISTS (
		SELECT 1 
		FROM ".DB_TABLE_PREFIX."receipt_item i
		WHERE i.receipt_id = r.id
	)") as $row)
  echo $row['id']." ".$row['checked']." ".$row['rawLoaded']."\n";
?>
