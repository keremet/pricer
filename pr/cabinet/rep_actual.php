<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Отчет по актуальности цен</title>
</head>
<?php
	include "../template/oft_table.php";
	include "../template/connect.php";

	oftTable::init("Последние даты ввода цен товаров в магазинах");
	oftTable::header(array("Товар", "Магазин", "Дата"));
	$stmt = $db->prepare(
        "SELECT p.name product_name, s.name shop_name, max_date_buy
         FROM (
           SELECT DISTINCT product, shop, max(date_buy) max_date_buy
           FROM ".DB_TABLE_PREFIX."fact 
           GROUP BY product, shop
         ) A 
          JOIN ".DB_TABLE_PREFIX."products p ON p.id = A.product 
          JOIN ".DB_TABLE_PREFIX."shops s ON s.id = A.shop 
         ORDER BY max_date_buy DESC
        ");
	$stmt->execute();
	while ($row = $stmt->fetch()) {
		oftTable::row(array($row['product_name'], $row['shop_name'], $row['max_date_buy']));
	}
        
	oftTable::end();
?>
</body>
</html>
