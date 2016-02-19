<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Умная форма';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Умная форма';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/input_calendar/tcal.js"></script>
<script>
	$(document).ready(function(){
		$('.ac_input').keyup(function(){
			$('div.select_box#shop').removeClass("selected");
			$('div.select_box#shop').text('new');
			$('#selected_shop_id').attr('value', '');
			var queryId = $(this).attr('id');
			var queryString = $(this).val();
			$.post(
				'ajax/shop_autocomplete.php',
				{
					name: queryString,
					var: queryId,
				},
				function(data){
					$('#'+queryId+'_autocom').html(data);
				}
			);
		});
		$('.ac_pr_input').keyup(function(){
			$('div.select_box#product').removeClass("selected");
			$('div.select_box#product').text('new');
			$('#selected_product_id').attr('value', '');
			var queryId = $(this).attr('id');
			var queryString = $(this).val();
			$.post(
				'ajax/product_autocomplete.php',
				{
					name: queryString,
					var: queryId,
				},
				function(data){
					$('#'+queryId+'_autocom').html(data);
				}
			);
		});
	});
	function get_price(shop, product){
		jQuery.ajax({
			url:     'ajax/get_price.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {shop: shop, product: product}, 
			success: function(result) {
				if(result){
					var vars = JSON.parse (result);
					if(vars.price){
						//if(!$('#offer_price').val()){
							$('.price_message').text('Цена добавлена автоматически');
							$('#offer_price').attr('value', vars.price).val(vars.price);
						//}
					}
				}else{
					$('.price_message').text('');
					$('#offer_price').attr('value', '').val('');
				}
			}
		});
	}
	function shop_select(id, name, network, town, address){
		//alert(id);
		//alert(name);
		$('#shop_name').attr('value', name).val(name);
		$('#shop_network').attr('value', network).val(network);
		$('#shop_town').attr('value', town).val(town);
		$('#shop_address').attr('value', address).val(address);
		$('.autocom').html('');
		$('div.select_box#shop').text('id'+id);
		$('#selected_shop_id').attr('value', id);
		$('div.select_box#shop').addClass("selected");
		if($('#selected_product_id').attr('value')){
			get_price($('#selected_shop_id').attr('value'), $('#selected_product_id').attr('value'));
		}
	}
	function product_select(id, name){
		//alert(id);
		//alert(name);
		$('#product_name').attr('value', name).val(name);
		$('.autocom').html('');
		$('div.select_box#product').text('id'+id);
		$('#selected_product_id').attr('value', id);
		$('div.select_box#product').addClass("selected");
		if($('#selected_shop_id').attr('value')){
			get_price($('#selected_shop_id').attr('value'), $('#selected_product_id').attr('value'));
		}
	}
	$(document).click(function (event) {	//При клике вне поля и подсказок скрыть подсказки
        if (!$(event.target).hasClass('autocom')){
            $('.autocom').html('');
        }
    });
	function AjaxFormRequest(form_id,url) {
		jQuery.ajax({
			url:     url, //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: jQuery("#"+form_id).serialize(), 
			success: function(response) { //Если все нормально
				//document.getElementById(result_id).innerHTML = response;
				//$(".result").html(response);
				//alert(response);
				var vars = JSON.parse (response);
				//alert(vars.shop_town);
				if(vars.errors){
					alert(vars.errors);
				}else{
					if(vars.type == 'shop'){
						shop_select(vars.id, vars.shop_name, vars.shop_network, vars.shop_town, vars.shop_address); 
						$('.fancybox-close').click();
						$("#shop_select_table").append('<tr onclick="shop_select(\''+vars.id+'\', \''+vars.shop_name+'\', \''+vars.shop_network+'\', \''+vars.shop_town+'\', \''+vars.shop_address+'\'); $(\'.fancybox-close\').click();"><td>'+vars.shop_name+'</td><td>'+vars.shop_network+'</td><td>'+vars.shop_town+'</td><td>'+vars.shop_address+'</td></tr>');
					}else if(vars.type == 'product'){
						product_select(vars.id, vars.product_name); 
						$('.fancybox-close').click();
						$("#product_select_table").append('<tr onclick="product_select(\''+vars.id+'\', \''+vars.product_name+'\'); $(\'.fancybox-close\').click();"><td>'+vars.product_name+'</td></tr>');
					}
				}
				//shop_select(\''.$v['id'].'\', \''.$v['name'].'\', \''.$v['network'].'\', \''.$v['town'].'\', \''.$v['address'].'\'); $(\'.fancybox-close\').click();
			},
			error: function(response) { //Если ошибка
				//alert("Ошибка при отправке формы");
			}
		});
	}
	function MainFormRequest(form_id,url) {
		jQuery.ajax({
			url:     url, //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: jQuery("#"+form_id).serialize(), 
			success: function(response) { //Если все нормально
				$('.result').html(response);
				/*var vars = JSON.parse (response);
				//alert(vars.shop_town);
				if(vars.errors){
					alert(vars.errors);
				}else{
					if(vars.type == 'shop'){
						shop_select(vars.id, vars.shop_name, vars.shop_network, vars.shop_town, vars.shop_address); 
						$('.fancybox-close').click();
						$("#shop_select_table").append('<tr onclick="shop_select(\''+vars.id+'\', \''+vars.shop_name+'\', \''+vars.shop_network+'\', \''+vars.shop_town+'\', \''+vars.shop_address+'\'); $(\'.fancybox-close\').click();"><td>'+vars.shop_name+'</td><td>'+vars.shop_network+'</td><td>'+vars.shop_town+'</td><td>'+vars.shop_address+'</td></tr>');
					}else if(vars.type == 'product'){
						product_select(vars.id, vars.product_name); 
						$('.fancybox-close').click();
						$("#product_select_table").append('<tr onclick="product_select(\''+vars.id+'\', \''+vars.product_name+'\'); $(\'.fancybox-close\').click();"><td>'+vars.product_name+'</td></tr>');
					}
				}
				//shop_select(\''.$v['id'].'\', \''.$v['name'].'\', \''.$v['network'].'\', \''.$v['town'].'\', \''.$v['address'].'\'); $(\'.fancybox-close\').click();*/
			},
			error: function(response) { //Если ошибка
				//alert("Ошибка при отправке формы");
			}
		});
	}
</script>
<link rel="stylesheet" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/input_calendar/tcal.css"/>
<?if($_SESSION['user']['id']){?>
	<h1>Умная форма</h1>
	<!--img src="<?=$GLOBALS['site_settings']['site_folder']?>/images/doing.png" style="width: 100%;">
	<img src="<?=$GLOBALS['site_settings']['site_folder']?>/images/tost.png" style="width: 100%;"-->
	<div class="result"></div>
	<div id="select_shop" style="display: none;"><!--style="display: none;"-->
		<h2>Выберите магазин</h2>
		<?$query = "SELECT `id`, `name`, `creator`, `text`, `address`, `photo`, `network`, `town`, `date_change` FROM `".$GLOBALS['site_settings']['db']['tables']['shops']."`";
		$table = $db->select($query, array());
		?>
		<table class="main select" id="shop_select_table"><tr><th>Название</th><th>Сеть</th><th>Город</th><th>Адрес</th></tr>
		<?foreach($table as $k => $v){
			echo '<tr onclick="shop_select(\''.$v['id'].'\', \''.htmlspecialchars($v['name'], ENT_QUOTES).'\', \''.htmlspecialchars($v['network'], ENT_QUOTES).'\', \''.htmlspecialchars($v['town'], ENT_QUOTES).'\', \''.htmlspecialchars($v['address'], ENT_QUOTES).'\'); $(\'.fancybox-close\').click();"><td>'.$v['name'].'</td><td>'.$v['network'].'</td><td>'.$v['town'].'</td><td>'.$v['address'].'</td></tr>';
		}?>
		</table>
		<button onclick="$('#new_shop_popup').css('display', ''); return false;">Новый магазин</button><br>
		<form id="new_shop_popup" style="display: none;" action="" method="post">
			<h2>Добавление нового магазина</h2>
			Название :<br>
			<input type="text" name="shop_name" placeholder="Название магазина" value="<?=$_REQUEST['shop_name']?>"><br><br>
			Торговая сеть :<br>
			<input type="text" name="shop_network" placeholder="Торговая сеть" value="<?=$_REQUEST['shop_network']?>"><br><br>
			Город :<br>
			<input type="text" name="shop_town" placeholder="Город" value="<?=$_REQUEST['shop_town']?>"><br><br>
			Адрес:<br>
			<textarea name="shop_address" placeholder="Адрес магазина"><?=$_REQUEST['shop_address']?></textarea><br><br>
			Комментарий:<br>
			<textarea name="shop_text" placeholder="Комментарий"><?=$_REQUEST['shop_text']?></textarea><br><br>
			<input type="submit" name="new_shop" value="Добавить магазин" onclick="AjaxFormRequest('new_shop_popup', 'ajax/shop_new_ajax.php'); return false;"><br><br>
		</form>
		<!--a href="#new_shop" onclick="$('.fancybox-close').click();" >Добавить новый магазин</a-->
		<!--button onclick="$('#new_shop_link').click(); return false;">Добавить новый</button-->
	</div>
	
	<div id="new_shop" style="display: none;"><!--style="display: none;"-->
		<form id="new_shop_popup_2" action="" method="post">
			<h2>Добавление нового магазина</h2>
			Название :<br>
			<input type="text" name="shop_name" placeholder="Название магазина" value="<?=$_REQUEST['shop_name']?>"><br><br>
			Торговая сеть :<br>
			<input type="text" name="shop_network" placeholder="Торговая сеть" value="<?=$_REQUEST['shop_network']?>"><br><br>
			Город :<br>
			<input type="text" name="shop_town" placeholder="Город" value="<?=$_REQUEST['shop_town']?>"><br><br>
			Адрес:<br>
			<textarea name="shop_address" placeholder="Адрес магазина"><?=$_REQUEST['shop_address']?></textarea><br><br>
			Комментарий:<br>
			<textarea name="shop_text" placeholder="Комментарий"><?=$_REQUEST['shop_text']?></textarea><br><br>
			<input type="submit" name="new_shop" value="Добавить магазин" onclick="AjaxFormRequest('new_shop_popup_2', 'ajax/shop_new_ajax.php'); return false;"><br><br>
		</form>
	</div>
	<div id="select_product" style="display: none;"><!--style="display: none;"-->
		<h2>Выберите товар</h2>
		<?$query = "SELECT `id`, `name`, `creator`, `date_change` FROM `".$GLOBALS['site_settings']['db']['tables']['products']."` ORDER BY name ASC";
		$table = $db->select($query, array());
		?>
		<table class="main select" id="product_select_table"><tr><th>Название</th></tr>
		<?foreach($table as $k => $v){
			echo '<tr onclick="product_select(\''.$v['id'].'\', \''.htmlspecialchars($v['name'], ENT_QUOTES).'\'); $(\'.fancybox-close\').click();"><td>'.$v['name'].'</td></tr>';
		}?>
		</table>
		<button onclick="$('#new_product_popup').css('display', ''); return false;">Новый товар</button><br>
		<form id="new_product_popup" style="display: none;" action="" method="post">
			<h2>Добавление нового товара</h2>
			Название :<br>
			<input type="text" name="product_name" placeholder="Название товара" value="<?=$_REQUEST['product_name']?>"><br><br>
			<input type="submit" name="new_product" value="Добавить товар" onclick="AjaxFormRequest('new_product_popup', 'ajax/product_new_ajax.php'); return false;"><br><br>
		</form>
	</div>
	
	<div id="new_product" style="display: none;"><!--style="display: none;"-->
		<form id="new_product_popup_2" action="" method="post">
			<h2>Добавление нового товара</h2>
			Название :<br>
			<input type="text" name="product_name" placeholder="Название товара" value="<?=$_REQUEST['product_name']?>"><br><br>
			<input type="submit" name="new_product" value="Добавить товар" onclick="AjaxFormRequest('new_product_popup_2', 'ajax/product_new_ajax.php'); return false;"><br><br>
		</form>
	</div>
	<form id="form_main">
		<fieldset>
			<div style="padding: 10px;">
				<span>
					<h2 style="display: inline;">Дата покупки: </h2>
					<input type="text" readonly class="tcal" name="date_buy" value="<?=date("d/m/Y")?>">
				</span>
			</div>
		</fieldset>
		<fieldset style="position: relative;">
			<!--div class="overlay"></div-->
			<div style="padding: 10px;">
				<h2 style="display: inline;">Магазин</h2>
					<a id="select_shop_button" class="fancybox" style="display: none;" href="#select_shop">Выбрать магазин</a>
					<a id="new_shop_link" class="fancybox" style="display: none;" href="#new_shop">Добавить новый магазин</a>
					<button onclick="$('#select_shop_button').click(); return false;">Выбрать</button>
					<!--button onclick="$('#new_shop_link').click(); return false;">Добавить новый</button-->
			</div>
			<div style="padding: 10px; width:80%; display: inline-block;">
				<b>Название</b><br>
				<input style="width:100%;" class="ac_input" autocomplete="off" id="shop_name" name="shop_name" type="text" value="">
				<div class="autocom" id="shop_name_autocom"></div>
			</div>
			<div class="select_box" id="shop">new</div>
			<input type="hidden" id="selected_shop_id" name="shop_id" value="">
			<div style="display: inline-block; width: 30%; padding: 10px">
				<span class="network"><b>Сеть</b></span><br>
				<input autocomplete="off" class="ac_input" name="shop_network" id="shop_network" type="text" value="" style="width:100%">
				<div class="autocom" id="shop_network_autocom"></div>
			</div>
			<div style="display: inline-block; width: 30%; padding: 10px">
				<span class="town"><b>Город</b></span><br>
				<input autocomplete="off" class="ac_input" name="shop_town" id="shop_town" type="text" value="" style="width:100%">
				<div class="autocom" id="shop_town_autocom"></div>
			</div>
			<div style="display: inline-block; width: 30%; padding: 10px">
				<span class="address"><b>Адрес</b></span><br>
				<input autocomplete="off" class="ac_input" name="shop_address" id="shop_address" type="text" value="" style="width:100%">
				<div class="autocom" id="shop_address_autocom"></div>
			</div>
		</fieldset>
		<fieldset>
			<div style="padding: 10px;">
				<h2 style="display: inline;">Товар</h2>
				<a id="select_product_button" class="fancybox" style="display: none;" href="#select_product">Выбрать товар</a>
				<a id="new_product_link" class="fancybox" style="display: none;" href="#new_product">Добавить новый товар</a>
				<button onclick="$('#select_product_button').click(); return false;">Выбрать</button>
				<!--button onclick="$('#new_product_link').click(); return false;">Добавить новый</button-->
			</div>
			<div style="padding: 10px;">
				Название<br>
				<input autocomplete="off" class="ac_pr_input" name="product_name" id="product_name" type="text" value="" style="width:60%">
				<div class="select_box" id="product">new</div>
				<input type="hidden" id="selected_product_id" name="product_id" value="">
				<div class="autocom" id="product_name_autocom"></div>
				<h2>Цена</h2>
				<div class="price_message"></div>
				<input onchange="$('.price_message').text('');" type="text" id="offer_price" name="price" step="0.01" placeholder="Цена" value="<?=$_REQUEST['price']?>">
				<input type="submit" name="smart_form" value="Добавить" onclick="MainFormRequest('form_main', 'ajax/main_form.php'); return false;">
			</div>
		</fieldset>
	</form>
<?}else{ echo 'Доступ запрещён. Авторизуйтесь.';}?>
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>
