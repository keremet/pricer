<?session_start();
header( 'Content-Type: text/html; charset=utf-8' );

if($_SESSION['user']['id']==null)
	die('Требуется авторизация');
?>

<html>
<head>
  <meta charset="utf-8">
  <title>Загрузка JSON с чеками</title>
</head>
<body>
	<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
	<tr valign="TOP">
		<td align="left"><a href="exit.php">Выход</a>
		<td align="left"><a href="receipt_list.php">Список чеков</a>
	</table>	
	
	<h2><p><b> Форма для загрузки JSON</b></p></h2>
	<form action="receipt_upload.php" method="post" enctype="multipart/form-data">
	<input type="file" name="filename"><br> 
	<input type="submit" value="Загрузить"><br>
	</form>
</body>
</html>
