for i in `curl http://orv.org.ru/pricer/api/receipt/get_not_parsed.php`
do echo $i
curl http://orv.org.ru/pricer/receipts/receipt_check.php?id=$i
sleep 5;
curl http://orv.org.ru/pricer/receipts/receipt_raw.php?id=$i
sleep 5;
curl http://orv.org.ru/pricer/receipts/receipt_raw.php?id=$i
sleep 5;
curl http://orv.org.ru/pricer/receipts/receipt_parse.php?id=$i
sleep 5;
done
