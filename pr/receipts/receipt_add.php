<head>
	<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
	<title>Чек</title>
</head>
<script src="dates.js"></script>
<script>
function checkDelReceipt(){
	if(confirm('Действительно удалить чек?')){
		document.getElementById('oper_type').value='delete';
		return true;
	}
	return false;
}
function saveReceipt(){
	var arr_err = [];
	
	document.getElementById("date_cor").value = 
		form_and_check_dat(document.getElementById("date").value, arr_err);
    if(arr_err.length>0){
		alert("Ошибка в дате: "+arr_err[0]);
		document.getElementById("date").focus();
		document.getElementById("date").select();
		return false;
    }
    return true;
}
</script>	
<table style="page-break-before: always;" width="600" border="0" cellpadding="1" cellspacing="1">
<tr valign="TOP">
	<td align="left"><a href="exit.php">Выход</a>
	<td align="left"><a href="receipt_list.php">Список чеков</a>
</table>
<br/>
<?php
/*	include "../template/oft_table.php";
	include "../template/connect.php";
	include "money_out.php";*/
	

?> 
<form id="main_form" action="receipt_save.php" method="post">
<table border="0" cellpadding="0" cellspacing="2">
            
<tr><td>Дата<td><input id="date"  name="date" size="8" type="text" 
value="<?=(($id!=null)?$ent['date']:'')?>" maxlength="8" 
onkeyup="return proverka_dat(this);" onchange="return proverka_dat(this);">
        
<tr><td>Время(HHMMSS)<td><input id="time"  name="time" size="30" type="text" 
value="<?=(($id!=null)?$ent['time']:'')?>">
      
<tr><td>Сумма в копейках<td><input id="summa"  name="summa" size="30" type="text" 
value="<?=(($id!=null)?$ent['summa']:'')?>">

<tr><td>ФН(fiscalDriveNumber)<td><input id="fdn"  name="fdn" size="30" type="text" 
value="<?=(($id!=null)?$ent['fdn']:'')?>">

<tr><td>ФП(fiscalSign)<td><input id="fs"  name="fs" size="30" type="text" 
value="<?=(($id!=null)?$ent['fs']:'')?>">

<tr><td>ФД(fiscalDocumentNumber)<td><input id="fdoc"  name="fdoc" size="30" type="text" 
value="<?=(($id!=null)?$ent['fdoc']:'')?>">
      
      	           
</table>
<br><input value="Сохранить" type="submit"  onclick="return saveReceipt();">
<?php if ($id!=null) { ?>
<input value="Удалить" type="submit" onclick="return checkDelReceipt();">
<?php }?>
<input type="hidden" id="oper_type" name="oper_type" value="update">
<input type="hidden" id="id" name="id" value="<?=$id?>">
<input type="hidden" id="date_cor" name="date_cor">
</form>
