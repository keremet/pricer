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
        'ed_izm',
        'products_main_clsf',
        'products',

        'products_equ_clsf',
        'equ_products',

        'town',
        'network',
        'shops_main_clsf',
        'shops'
    );
    switch($backup_type)
    {
    case 'settings':
        break;
    case 'all':
        array_unshift($table_name , 'users');
        array_unshift($table_name , 'user_group');

        $table_name[] = 'consumption_clsf';
        $table_name[] = 'consumption';

        $table_name[] = 'product_offers';

        $table_name[] = 'receipt';
        $table_name[] = 'receipt_item';
        $table_name[] = 'receipt_modifier';
        $table_name[] = 'receipt_user';
        break;
    default:
        die('Unknown backup type');
    };

    function row_out($name, $user_col, $row, $insert_hdr, &$i) {
        global $db;
        
        if(0 == $i)
            echo $insert_hdr;
        else if($i >= 50)
        {
            echo ";" . PHP_EOL . $insert_hdr;
            $i = 0;
        }
        else
            echo "," . PHP_EOL;
        $i++;
        echo  "(";

        $iCol = 0;
        foreach($row as $k => $val)
        {
            if($iCol++ > 0)
                echo ", ";
            if(($name == 'users') && ($iCol == 4)) //Не выгружать пароли пользователей
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

    function child_out($id_hi, $name, $user_col, $insert_hdr, &$i) {
        global $db;

        $stmtSC = $db->prepare("SELECT * FROM $name WHERE id_hi = ?");
        $stmtSC->execute(array($id_hi));
        while($row = $stmtSC->fetch())
        {
            row_out($name, $user_col, $row, $insert_hdr, $i);
            child_out($row['id'], $name, $user_col, $insert_hdr, $i);
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
        $insert_hdr = "INSERT INTO `$name` (";
        for($i = 0; $col_name = $stmt->fetchColumn();)
        {
            if($i++ > 0)
                $insert_hdr .= ", ";
            if(($backup_type == 'settings') && (($col_name == 'creator') || ($col_name == 'user_id')))
                $user_col = $i;
            if($col_name == 'id_hi')
                $is_tree = true;
            $insert_hdr .= $col_name;
        }
        $insert_hdr .= ") VALUES " . PHP_EOL;

        if($is_tree)
        {           
            $stmtS = $db->prepare("SELECT * FROM $name WHERE id_hi is null");
            $stmtS->execute();
            for($i=0; $row = $stmtS->fetch(); )
            {
                row_out($name, $user_col, $row, $insert_hdr, $i);
                child_out($row['id'], $name, $user_col, $insert_hdr, $i);
            }
        }
        else for($i=0; $row = $stmtS->fetch(); )
            row_out($name, $user_col, $row, $insert_hdr, $i);

        echo ";" . PHP_EOL . PHP_EOL;
    }
?>
