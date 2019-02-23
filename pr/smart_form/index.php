<?
include '../template/header.php';
headerOut('Ввод данных', array('prod','shop'));
include '../template/jstree/jstree.php';
?>
<script type="text/javascript" src="../template/input_calendar/tcal.js"></script>
<script>
	(function() {
	  /**
	   * Корректировка округления десятичных дробей.
	   *
	   * @param {String}  type  Тип корректировки.
	   * @param {Number}  value Число.
	   * @param {Integer} exp   Показатель степени (десятичный логарифм основания корректировки).
	   * @returns {Number} Скорректированное значение.
	   */
	  function decimalAdjust(type, value, exp) {
		// Если степень не определена, либо равна нулю...
		if (typeof exp === 'undefined' || +exp === 0) {
		  return Math[type](value);
		}
		value = +value;
		exp = +exp;
		// Если значение не является числом, либо степень не является целым числом...
		if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
		  return NaN;
		}
		// Сдвиг разрядов
		value = value.toString().split('e');
		value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
		// Обратный сдвиг
		value = value.toString().split('e');
		return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
	  }

	  // Десятичное округление к ближайшему
	  if (!Math.round10) {
		Math.round10 = function(value, exp) {
		  return decimalAdjust('round', value, exp);
		};
	  }
	})();	
	
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
	function calc_cost(){
		priceStr = document.getElementById('offer_price').value.replace(',', '.');
		amountStr = document.getElementById('amount').value.replace(',', '.');
		
		$('.result').text('Стоимость='+Math.round10(Number(priceStr)*Number(amountStr), -2));
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
				amount: document.getElementById('amount').value,
				date_buy: document.getElementById('date_buy').value
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
<?if($_SESSION['user_id']){?>	
	<div style="padding: 10px;">
		<span>
			<h2 style="display: inline;">Дата покупки: </h2>
			<input size=10 type="text" readonly class="tcal" id="date_buy" value="<?=date("d/m/Y")?>">
			<h2 style="display: inline;">Цена</h2>
			<input size=8 onkeyup="calc_cost()" type="text" id="offer_price">
			<h2 style="display: inline;">Количество</h2>
			<input size=5 onkeyup="calc_cost()" type="text" id="amount">
			<input type="submit" value="Добавить" onclick="SavePrice(); return false;">
			<div class="result"></div>
		</span>
	</div>
	<input type="hidden" id="selected_product_id" value="">
	<input type="hidden" id="selected_shop_id" value="">
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
