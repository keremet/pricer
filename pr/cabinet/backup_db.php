<?php
    $backup_type = $_GET['type'];

    session_start();

    if($_SESSION['user_download_backup'] != "1"){
        header( 'Content-Type: text/html; charset=utf-8' );
        die('Требуется авторизация или нет прав');
    }
    
    // database dump PHP © $continue$ - 2019 year
    $now = new DateTime();
    $filename = "backup_pricer_" . $backup_type . $now->format('Y_m_d_H_i_s') . ".sql";
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    include('../template/connect.php');

    $table_name = array(
        DB_TABLE_PREFIX.'ed_izm',
        DB_TABLE_PREFIX.'products_main_clsf',
        DB_TABLE_PREFIX.'products',

        DB_TABLE_PREFIX.'products_equ_clsf',
        DB_TABLE_PREFIX.'equ_products',

        DB_TABLE_PREFIX.'town',
        DB_TABLE_PREFIX.'network',
        DB_TABLE_PREFIX.'shops_main_clsf',
        DB_TABLE_PREFIX.'shops',

        DB_TABLE_PREFIX.'receipt_item_to_product',
        DB_TABLE_PREFIX.'receipt_to_shop'
    );
    switch($backup_type)
    {
    case 'settings':
        break;
    case 'all':
        array_unshift($table_name , DB_TABLE_PREFIX.'users');
        array_unshift($table_name , DB_TABLE_PREFIX.'user_group');

        $table_name[] = DB_TABLE_PREFIX.'consumption_clsf';
        $table_name[] = DB_TABLE_PREFIX.'consumption';

        $table_name[] = DB_TABLE_PREFIX.'product_offers';

        $table_name[] = DB_TABLE_PREFIX.'receipt';
        $table_name[] = DB_TABLE_PREFIX.'receipt_item';
        $table_name[] = DB_TABLE_PREFIX.'receipt_modifier';
        $table_name[] = DB_TABLE_PREFIX.'receipt_user';
        break;
    default:
        die('Unknown backup type');
    };

    function child_out($id_hi, $name) {
        global $db;
        
        $stmtSC = $db->prepare("SELECT * FROM $name WHERE id_hi = ?");
        $stmtSC->execute(array($id_hi));
        while($value = $stmtSC->fetch())
        {
            echo "," . PHP_EOL . "(";
            $iCol = 0;
            foreach($value as $k => $val)
            {
                if($iCol++ > 0)
                    echo ", ";
                if($iCol == $user_col)
                {
                    echo "1";
                    continue;
                }
                echo is_null($val) ? "null" : $db->quote($val);
            }
            echo ")";
            child_out($value['id'], $name);
        }               
    }

    foreach($table_name as $name)
    {
        $stmtS = $db->prepare("SELECT * FROM $name");
        $stmtS->execute();
        if($stmtS->rowCount() == 0)
            continue;

        $user_col = -1;
        $is_tree = false;
        $stmt = $db->prepare("DESCRIBE $name");
        $stmt->execute();
        echo "INSERT INTO `$name` (";
        for($i = 0; $col_name = $stmt->fetchColumn();)
        {
            if($i++ > 0)
                echo ", ";
            if(($backup_type == 'settings') && (($col_name == 'creator') || ($col_name == 'user_id')))
                $user_col = $i;
            if($col_name == 'id_hi')
                $is_tree = true;
            echo $col_name;
        }
        echo ") VALUES " . PHP_EOL;

        if($is_tree)
        {           
            $stmtS = $db->prepare("SELECT * FROM $name WHERE id_hi is null");
            $stmtS->execute();
            for($i=0; $value = $stmtS->fetch(); $i++)
            {
                if($i > 0)
                    echo "," . PHP_EOL;
                echo "(";
                $iCol = 0;
                foreach($value as $k => $val)
                {
                    if($iCol++ > 0)
                        echo ", ";
                    if($iCol == $user_col)
                    {
                        echo "1";
                        continue;
                    }
                    echo is_null($val) ? "null" : $db->quote($val);
                }
                echo ")";
                child_out($value['id'], $name);
            }
        }
        else for($i=0; $value = $stmtS->fetch(); $i++)
        {
            if($i > 0)
                echo "," . PHP_EOL;
            echo "(";
            $iCol = 0;
            foreach($value as $k => $val)
            {
                if($iCol++ > 0)
                    echo ", ";
                if(($name == DB_TABLE_PREFIX.'users') && ($iCol == 4)) //Не выгружать пароли пользователей
                {
                    echo "''";
                    continue;
                }
                if($iCol == $user_col)
                {
                    echo "1";
                    continue;
                }
                echo is_null($val) ? "null" : $db->quote($val);
            }
            echo ")";
        }
        echo ";" . PHP_EOL;
    }
?>
