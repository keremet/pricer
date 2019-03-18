<?php
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
        $index_add_col = 0;
        $count_row = $stmt->rowCount();
        echo "INSERT INTO `$name` (";
        while($result = $stmt->fetchColumn())
        {
            if($index_add_col != $count_row - 1)
            {
                echo $result . ", ";
            }
            else
            {
                echo $result . ") VALUES " . PHP_EOL;
            }
            $index_add_col++;
        }

        $all_col_count = $stmtS->columnCount();
        $result = $stmtS->fetchAll();
        foreach($result as $key =>  $value)
        {
            echo "(";
            foreach($value as $k => $val)
            {
                $data_row = is_null($val) == true ? "null" : "'" . $val . "'";
                if($count_col == $all_col_count - 1)
                {
                    echo $data_row;
                    $count_col = 0;
                }
                else
                {
                    echo $data_row . ", " ;
                    $count_col++;
                }

            }
            if($key == count($result) - 1)
            {
                echo ");" . PHP_EOL;
            }
            else
            {
                echo ")," . PHP_EOL;
            }
        }
    }
?>
