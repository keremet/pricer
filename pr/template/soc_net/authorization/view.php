<div id="auth_form" style="width:400px;display: none;"><!--style="display: none;"-->
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
