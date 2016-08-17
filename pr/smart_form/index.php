<?
include '../template/header.php';
headerOut('Ввод данных', array('prod','shop'));
include '../template/jstree/jstree.php';
?>
<script type="text/javascript" src="../template/input_calendar/tcal.js"></script>
<script>
	function get_price(shop, product){
		$('.result').html('Поиск цены');
		jQuery.ajax({
			url:     'get_price.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {shop: shop, product: product}, 
			success: function(result) {
				if(result){
					var vars = JSON.parse (result);
					if(vars.price){
						$('.result').text('Подставлена цена на ' + vars.date_buy);
						$('#offer_price').attr('value', vars.price).val(vars.price);
					}
				}else{
					$('.result').text('Цена не найдена');
					$('#offer_price').attr('value', '').val('');
				}
			}
		});
	}
	function shop_select(id){
		$('#selected_shop_id').attr('value', id);
		if($('#selected_product_id').attr('value')){
			get_price(id, $('#selected_product_id').attr('value'));
		}else{
			$('.result').html('ID магазина = ' + id);
		}
	}
	function product_select(id){
		$('#selected_product_id').attr('value', id);
		if($('#selected_shop_id').attr('value')){
			get_price($('#selected_shop_id').attr('value'), id);
		}else{
			$('.result').html('ID товара = ' + id);
		}

	}
	function SavePrice() {
		$('.result').html('');
		jQuery.ajax({
			url:     'save_price.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {
				shop_id: $('#selected_shop_id').attr('value'),
				product_id: $('#selected_product_id').attr('value'),
				price: document.getElementById('offer_price').value,
				date_buy: $('#date_buy').attr('value')
			},
			success: function(response) { //Если все нормально
				$('.result').html(response);
			},
			error: function(response) { //Если ошибка
				$('.result').html('Ошибка');
			}
		});
	}
</script>
<link rel="stylesheet" href="../template/input_calendar/tcal.css"/>
<?if($_SESSION['user']['id']){?>	
	<div style="padding: 10px;">
		<span>
			<h2 style="display: inline;">Дата покупки: </h2>
			<input type="text" readonly class="tcal" id="date_buy" name="date_buy" value="<?=date("d/m/Y")?>">
			<h2 style="display: inline;">Цена</h2>
			<input onkeypress="$('.result').text('');" type="text" id="offer_price" name="offer_price">
			<input type="submit" name="smart_form" value="Добавить" onclick="SavePrice(); return false;">
			<div class="result"></div>
		</span>
	</div>
	<input type="hidden" id="selected_product_id" name="product_id" value="">
	<input type="hidden" id="selected_shop_id" name="shop_id" value="">
	<table>
	<tr>
	<td valign="top">
		<?
			putTree('prod', '../products/');
		?>
	<td valign="top">
		<?
			putTree('shop', '../shops/');
		?>
	</table>
<?}else{ echo 'Доступ запрещён. Авторизуйтесь.';}
include('../template/footer.php');?>
