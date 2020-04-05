<head>
    <meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
    <title>Отчет по оптимальности покупок</title>
</head>
<?php
    include "../template/oft_table.php";
    include "../template/connect.php";

    oftTable::init("Покупки пользователя за последний месяц");
    oftTable::header(array("Товар", "Магазин", "Дата", "Цена", "Кол-во", "Кол-во ед. изм", "Цена за ед. изм", "Цена за ед. изм 2", "Товар 2", "Магазин 2", "Цена 2", "Экономия"));
    $stmt = $db->prepare(
        "SELECT p.name product_name, s.name shop_name, f.date_buy, f.price, round(f.price/ifnull(p.in_box, 1), 2) price_ei, f.amount, f.product, f.shop, f.amount*ifnull(p.in_box, 1) amount_ei
         FROM fact f
            JOIN products p ON p.id = f.product
            JOIN shops s ON s.id = f.shop 
         WHERE f.creator=? and f.date_buy > NOW() - INTERVAL 1 MONTH and f.amount>0
         ORDER BY f.date_buy DESC
        ");
    $stmtOptim = $db->prepare(
        "SELECT p.name product_name, s.name shop_name, f.price, round(f.price/ifnull(p.in_box, 1), 2) price_ei
         FROM fact f
            JOIN products p ON p.id = f.product
            JOIN shops s ON s.id = f.shop 
         WHERE f.product in (
                SELECT ?
                UNION
                SELECT pp.product_id
                FROM equ_products pc
                    JOIN equ_products pp ON pp.equ_clsf_id = pc.equ_clsf_id
                WHERE pc.product_id = ?
            )
            AND (f.product != ? OR f.shop != ?)
            AND f.date_buy > NOW() - INTERVAL 1 MONTH
            AND round(f.price/ifnull(p.in_box, 1), 2) < ?
         ORDER BY round(f.price/ifnull(p.in_box, 1), 2)
        ");
    $stmt->execute(array($_GET['user_id']));
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
        $stmtOptim->execute(array($row['product'], $row['product'], $row['product'], $row['shop'], $row['price_ei']));
        $price_ei_optim = " ";
        $product_name_optim = " ";
        $shop_name_optim = " ";
        $price_optim = " ";
        $ekon = " ";
        if ($rowOptim = $stmtOptim->fetch()) {
            $price_ei_optim = $rowOptim['price_ei'];
            $product_name_optim = $rowOptim['product_name'];
            $shop_name_optim = $rowOptim['shop_name'];
            $price_optim = $rowOptim['price'];
            $ekon = round($row['amount_ei']*($row['price_ei'] - $rowOptim['price_ei']), 2);
        }
        oftTable::row(array($row['product_name'], $row['shop_name'], $row['date_buy'], $row['price'], $row['amount'], $row['amount_ei'], $row['price_ei'], $price_ei_optim, $product_name_optim, $shop_name_optim, $price_optim, $ekon));
    }

    oftTable::end();
?>
</body>
</html>
