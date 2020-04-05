<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Аналитика по чеку</title>
</head>
<?php
	include "../template/oft_table.php";
	include "../template/connect.php";
	include "money_out.php";

function print_prices($stmtProd, $stmtPrices, $product_id){
    $stmtProd->execute(array($product_id));
    if($prod = $stmtProd->fetch()){
        oftTable::init($prod['name']." (".$product_id.")");
    }
    
    $stmtPrices->execute(array($product_id, $product_id));
    oftTable::header(array("Цена ед. изм.", "Цена", "Магазин", "Дата"));
    while($price = $stmtPrices->fetch()){
        oftTable::row(array(money_out(round($price['pr']/$prod['in_box'], 2)*100), money_out($price['pr']*100), $price['name'], $price['date_buy']));
    }
    oftTable::end();
}
	$stmt = $db->prepare(
        "SELECT i.name, round(i.price/100, 2) price, round(i.price/100/ifnull(p.in_box, 1), 2) price_ei, i2p.product_id
         FROM receipt r
           JOIN receipt_item i ON i.receipt_id = r.id
           JOIN receipt_item_to_product i2p ON r.userInn = i2p.inn and i.name = i2p.name
           JOIN products p ON p.id = i2p.product_id
         WHERE r.id = ?
		 ");
	$stmt->execute(array($_GET['id']));
    
    $stmtEquProd = $db->prepare(
        "SELECT pp.product_id
         FROM equ_products pc
            JOIN equ_products pp ON pp.equ_clsf_id = pc.equ_clsf_id AND pp.product_id != pc.product_id
         WHERE pc.product_id = ?
        ");
    
    $stmtProd = $db->prepare(
        "SELECT name, ifnull(in_box, 1) in_box
         FROM products
         WHERE id = ?
        ");
    
    $stmtPrices = $db->prepare(
        "SELECT s.name, po1.date_buy, min(price) pr
        FROM fact po1, shops s
        WHERE po1.product = ?
          and (po1.shop, po1.date_buy) in (
            SELECT shop, max(date_buy)
            FROM fact
            WHERE product = ?
            GROUP BY shop
          )
          and po1.shop = s.id
        GROUP BY s.name, po1.date_buy
        ORDER BY pr"
    );
    
	while ($row = $stmt->fetch()) {
        echo "<center><b>Название в чеке: ".$row['name']."</b><br/>";
        echo "Цена в чеке: <b>".$row['price_ei']." (".$row['price'].")</b><br/>";
        print_prices($stmtProd, $stmtPrices, $row['product_id']);
        
        $stmtEquProd->execute(array($row['product_id']));
        if($stmtEquProd->rowCount() > 0)
            echo "<br/>Эквивалентные товары<br/>";
        while ($rowEquProd = $stmtEquProd->fetch()) {
            print_prices($stmtProd, $stmtPrices, $rowEquProd['product_id']);
        }
        echo "<br/><hr><br/>";
	}

	oftTable::init("Неизвестные товары");
	oftTable::header(array("Товар"));
	$stmt = $db->prepare(
        "SELECT i.name
         FROM receipt r
           JOIN receipt_item i ON i.receipt_id = r.id
         WHERE r.id = ?
         AND NOT EXISTS(
             SELECT 1
             FROM receipt_item_to_product i2p
             WHERE r.userInn = i2p.inn and i.name = i2p.name
         )
		 ");
	$stmt->execute(array($_GET['id']));
	while ($row = $stmt->fetch()) {
		oftTable::row(array($row['name']));
	}
        
	oftTable::end();
?>
</body>
</html>
