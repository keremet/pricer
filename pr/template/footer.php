<?
if($_REQUEST['author'] == 'Авторизоваться'){
    $stmt = $db->prepare("SELECT u.id, g.del_anothers_receipts, g.del_anothers_consumptions, g.del_anothers_shop_links
                        , g.del_anothers_product_links, g.del_anothers_shops, g.del_anothers_products
                        , g.edt_anothers_shops, g.edt_anothers_products, g.upload_receipts_from_file, g.download_backup
                      FROM ".DB_TABLE_PREFIX."users u
                        JOIN ".DB_TABLE_PREFIX."user_group g ON g.id = u.group_id
                      WHERE u.login = ? AND u.password = ?");
    $login = trim(htmlspecialchars($_REQUEST['login']));
    $stmt->execute(array($login, trim($_REQUEST['password'])));
    $rowU = $stmt->fetch();
    if(!$rowU)
        echo "<script>alert('Ошибка: Неверный логин или пароль');</script>";
    else{
        echo "<script>document.location.href='".
            (($login == "keremet")?"../cabinet/index.php":"../smart_form/").
            "';</script>";
        $_SESSION['user_id'] = $rowU['id'];
        $_SESSION['user_del_anothers_receipts'] = $rowU['del_anothers_receipts'];
        $_SESSION['user_del_anothers_consumptions'] = $rowU['del_anothers_consumptions'];
        $_SESSION['user_del_anothers_shop_links'] = $rowU['del_anothers_shop_links'];
        $_SESSION['user_del_anothers_product_links'] = $rowU['del_anothers_product_links'];
        $_SESSION['user_del_anothers_shops'] = $rowU['del_anothers_shops'];
        $_SESSION['user_del_anothers_products'] = $rowU['del_anothers_products'];
        $_SESSION['user_edt_anothers_shops'] = $rowU['edt_anothers_shops'];
        $_SESSION['user_edt_anothers_products'] = $rowU['edt_anothers_products'];
        $_SESSION['user_upload_receipts_from_file'] = $rowU['upload_receipts_from_file'];
        $_SESSION['user_download_backup'] = $rowU['download_backup'];
        if($_REQUEST['remember'] == 'Y'){
            setcookie('user_login', trim(htmlspecialchars($_REQUEST['login'])), time() + 3600 * 24 * 30, '/');
            setcookie('user_password', trim($_REQUEST['password']), time() + 3600 * 24 * 30, '/');
        }else{
            setcookie('user_login', '', time() - 1, '/');
            setcookie('user_password', '', time() - 1, '/');
        }
    }
}
?>
<div id="auth_form" style="width:400px;display: none;">
	<h2>Авторизация пользователя</h2>
	<form action="" method="post">
		Логин* :<br>
		<input required type="text" name="login" placeholder="Ваш логин" value="<?=$_REQUEST['login']?>"><br><br>
		Пароль* :<br>
		<input required type="password" name="password" placeholder="Ваш пароль" value="<?=$_REQUEST['password"']?>"><br><br>
		<!--/Поле с автозаполнением -->
		<input type="checkbox" name="remember"<?if($_COOKIE['user_login'] && $_COOKIE['user_password']) echo ' checked="checked"';?> value="Y">Запомнить логин и пароль<br><br>
		Если у вас ещё нет аккаунта в системе, вы можете зарегистрироваться.<br><br>
		<input type="submit" name="author" value="Авторизоваться">
	</form>
</div>
			</div>
		</div>
	</body>
</html>
