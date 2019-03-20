<?php
    session_start();

    if($_SESSION['user_download_backup'] != "1"){
        header( 'Content-Type: text/html; charset=utf-8' );
        die('Требуется авторизация или нет прав');
    }
    
    // database dump PHP © $continue$ - 2019 year
    $now = new DateTime();
    $filename = "backup_pricer_" . $now->format('Y_m_d_H_i_s') . ".sql";
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    include('../template/connect.php');
    
    $table_name = array();

    $stmt = $db->prepare("SHOW TABLES");
    $stmt->execute();

    while($result = $stmt->fetchColumn())
    {
        if (preg_match("/^".DB_TABLE_PREFIX.".*/m", $result))
        {
            $table_name[] = $result;
        }
    }

    $count_col = 0;
    foreach($table_name as $name)
    {
        $stmtS = $db->prepare("SELECT * FROM $name");
        $stmtS->execute();
        if($stmtS->rowCount() == 0)
            continue;
        
        $stmt = $db->prepare("DESCRIBE $name");
        $stmt->execute();
        echo "INSERT INTO `$name` (";
        for($i = 0; $result = $stmt->fetchColumn(); $i++)
        {
            if($i > 0)
                echo ", ";
            echo $result;
        }
        echo ") VALUES " . PHP_EOL;
        
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
                if(($name == DB_TABLE_PREFIX."users") && ($iCol == 4)) //Не выгружать пароли пользователей
                {
                    echo "''";
                    continue;
                }
                echo is_null($val) ? "null" : "'" . $val . "'";
            }
            echo ")";
        }
        echo ";" . PHP_EOL;
    }
?>
