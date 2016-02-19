<div id="reg_form" style="display: none;"><!--style="display: none;"-->
	<h2>Регистрация нового пользователя</h2>
	<form action="" method="post" enctype = 'multipart/form-data'>
		ФИО* :<br>
		<input required type="text" name="name" placeholder="Ваше полное имя" value="<?=$_REQUEST['name']?>"><br><br>
		Фото (изображение не больше 1 Мб):<br>
		<input type="file" name="image" /><br><br>
		Информация о себе:<br>
		<textarea name="text" placeholder="Несколько слов о вашей биографии, интересах и т.д."><?=$_REQUEST['text']?></textarea><br><br>
		e-mail* :<br>
		<input required type="email" name="email" placeholder="Ваша электронная почта" value="<?=$_REQUEST['email']?>"><br><br>
		Логин* :<br>
		<input required type="text" name="new_login" placeholder="Ваш псевдоним в системе" value="<?=$_REQUEST['new_login']?>"><br><br>
		Пароль (только латинские буквы, цифры и подчёркивание)* :<br>
		<input required type="password" name="password1" placeholder="Придумайте себе пароль" value="<?=$_REQUEST['password1']?>"><br><br>
		Повторите пароль* :<br>
		<input required type="password" name="password2" placeholder="Повторите пароль" value="<?=$_REQUEST['password2']?>"><br><br>
		<input type="submit" name="register" value="Зарегистрироваться"><br><br>
		Уже зарегистрированы в системе? Тогда <a class="fancybox" href="#auth_form">авторизуйтесь</a>.<br><br>
	</form>
</div>
