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

    $index = 0;
    while($result = $stmt->fetchColumn())
    {
        if (preg_match("/^".DB_TABLE_PREFIX.".*/m", $result))
        {
            $table_name[$index] = $result;
            $index++;
        }
    }

    $query = array();
    $count_col = 0;
    foreach($table_name as $name)
    {
        $stmt = $db->prepare("DESCRIBE $name");
        $stmt->execute();
        $index_add_col = 0;
        $count_row = $stmt->rowCount();
        $query["$name"] .= "INSERT INTO `$name` (";
        while($result = $stmt->fetchColumn())
        {
            if($index_add_col != $count_row - 1)
            {
                $query["$name"] .= $result .= ", ";
            }
            else
            {
                $query["$name"] .= $result .= ") VALUES " . PHP_EOL;
            }
            $index_add_col++;
        }

        $stmt = $db->prepare("SELECT * FROM $name");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $all_col_count = $stmt->columnCount();
        $all_row_count = $stmt->rowCount();
        
        if($all_row_count == 0)
        {
            $query["$name"] = "";
        }

        foreach($result as $key =>  $value)
        {
            $query["$name"] .= "(";
            foreach($value as $k => $val)
            {
                $data_row = is_null($val) == true ? "null" : "'" . $val . "'";
                if($count_col == $all_col_count - 1)
                {
                    $query["$name"] .= $data_row;
                    $count_col = 0;
                }
                else
                {
                    $query["$name"] .= $data_row . ", " ;
                    $count_col++;
                }

            }
            if($key == count($result) - 1)
            {
                $query["$name"] .= ");" . PHP_EOL;
            }
            else
            {
                $query["$name"] .= ")," . PHP_EOL;
            }
        }
    }
    foreach($query as $val)
    {
        echo $val;
    }
?>
