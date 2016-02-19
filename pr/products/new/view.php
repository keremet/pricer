<!--p><a class="fancybox" href="#new_product_form">Добавить новый товар</a></p>
<div id="new_product_form" style="width:400px;display: none;"><!--style="display: none;"-->
	<form action="" method="post" enctype = 'multipart/form-data'>
		Название* :<br>
		<input required type="text" name="product_name" placeholder="Название товара" value="<?=$_REQUEST['product_name']?>"><br><br>
		Фото (изображение не больше 1 Мб):<br>
		<input type="file" name="image" /><br><br>
		<span><input type="radio" name="PROP_VALUE[0][]" checked="checked" onclick="$('#packing').css('display', '');" value="Упакованный">Упакованный</span>
		<span><input type="radio" name="PROP_VALUE[0][]" onclick="$('#packing').css('display', 'none');" value="Развесной">Развесной</span><br><br>
		<div id="packing">
			Вес упаковки:<br>
			<input type="text" name="PROP_VALUE[1][]" placeholder="Масса товара в упаковке (г.)" value="<?=$_REQUEST['']?>"><br><br>
			Количество штук в упаковке:<br>
			<input type="text" name="PROP_VALUE[2][]" placeholder="Количество штук в упаковке" value="<?=$_REQUEST['']?>"><br><br>
		</div>
		<div class="property" id="property_3">
			<strong>Тип товара</strong><br>
			<button id="button_3" onclick="new_value('3'); return false;">Добавить ещё одно значение</button>
			<input type="text" name="PROP_VALUE[3][]" placeholder="Значение свойства" value="">
			<input type="hidden" name="PROP_NAME[3]" value="Тип товара">
		</div>
		<button onclick="$(this).before('<div class=\'property\' id=\'property_'+i+'\'><input type=\'text\' name=\'PROP_NAME['+i+']\' placeholder=\'Название свойства\' value=\'\'><button id=\'button_'+i+'\' onclick=\'new_value(&quot;'+i+'&quot;); return false;\'>Добавить ещё одно значение</button> <input type=\'text\' name=\'PROP_VALUE['+i+'][]\' placeholder=\'Значение свойства\' value=\'\'></div>'); i ++; return false;">Добавить свойство</button><br><br>
		<input type="submit" name="new_product" value="Добавить товар"><br><br>
		<input type="hidden" name="PROP_NAME[0]" value="Упаковка">
		<input type="hidden" name="PROP_NAME[1]" value="Масса товара в упаковке (г.)">
		<input type="hidden" name="PROP_NAME[2]" value="Количество штук в упаковке">
	</form>
<!--/div-->
<script>
	i = 4;
	function new_value(id){
		//alert(id);
		//id = $(t).attr('id');
		$('div#property_'+id).append('<input type=\'text\' name=\'PROP_VALUE['+id+'][]\' placeholder=\'Значение свойства\' value=\'\'>');
		return false;
	}
</script>