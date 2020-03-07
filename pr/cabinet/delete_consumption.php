<?
session_start();
header( 'Content-Type: text/html; charset=utf-8' );
include('../template/connect.php');

if($_SESSION['user_id']==null)
	die('Требуется авторизация');

if (isset($_REQUEST['id'])) {
    if ($_SESSION['user_del_anothers_consumptions'] != '1') {
        $stmtS = $db->prepare(
            "SELECT cc.creator, cc.id_hi
            FROM consumption c
              JOIN consumption_clsf cc ON cc.id = c.clsf_id
            WHERE c.id = ?"
            );
        if (!$stmtS->execute(array($_REQUEST['id']))) {
            echo 'Ошибка запроса поиска расхода'; print_r($stmtS->errorInfo());
            exit();
        }
        if ($row = $stmtS->fetch()) {
            while (is_null($row['creator'])) {
                $stmtS2 = $db->prepare(
                    "SELECT cc.creator, cc.id_hi
                    FROM consumption_clsf cc
                    WHERE cc.id = ?"
                    );
                if (!$stmtS2->execute(array($row['id_hi']))) {
                    echo 'Ошибка запроса поиска категории расхода'; print_r($stmtS2->errorInfo());
                    exit();
                }
                if (!($row = $stmtS2->fetch())) {
                    echo 'Ошибка поиска категории расхода'; print_r($stmtS2->errorInfo());
                    exit();
                }
            }
            
            if ($row['creator'] != $_SESSION['user_id'])
                die("Удаление чужих расходов запрещено для пользователя");
        } else {
            echo 'Ошибка поиска расхода'; print_r($stmt->errorInfo());
            exit();
        }
    }

	$stmt = $db->prepare("DELETE FROM consumption WHERE id = ?");
	if(!$stmt->execute(array($_REQUEST['id']))){
		echo 'Ошибка удаления расхода'; print_r($stmt->errorInfo());
		exit();
	}
	$count = $stmt->rowCount();
	if($count == 1){
		echo "Расход удален";
	}elseif($count < 1){
		echo "Расход не найден";
	}else{
		echo "Дублирование индекса. Удалено больше 1 строки.";
	}
}
?>
