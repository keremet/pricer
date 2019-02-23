<?
include '../template/header.php';
headerOut('Ввод данных', array('prod'));
include '../template/jstree/jstree.php';
?>
<script type="text/javascript" src="../template/input_calendar/tcal.js"></script>
<script>	
	function product_select(id){
		$('#selected_product_id').attr('value', id);
		$('.result').html('ID расхода = ' + id);
	}
	function refreshСonsumptions(){
		$.get('../prof_els/tree.php?operation=get_content&id=' + $('#selected_product_id').attr('value'), function (d) {
					if(d){
						$('#dataprod .default').html(d.content).show();
					}
				}
			);
	}
	function SaveСonsumption() {
		$('.result').html('');
		jQuery.ajax({
			url:     'save_consumption.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {
				product_id: $('#selected_product_id').attr('value'),
				price: document.getElementById('price').value,
				date_buy: document.getElementById('date_buy').value
			},
			success: function(response) { //Если все нормально
				$('.result').html(response);
				refreshСonsumptions();
			},
			error: function(response) { //Если ошибка
				$('.result').html('Ошибка');
			}
		});
	}
	function DeleteСonsumption(id){
		if (!confirm('Удалить расход?'))
			return;
		jQuery.ajax({
			url:     'delete_consumption.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {id: id}, 
			success: function(response) {
				$('.result').html(response);
				refreshСonsumptions();
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
			<h2 style="display: inline;">Стоимость</h2>
			<input size=8  type="text" id="price">			
			<input type="submit" value="Добавить" onclick="SaveСonsumption(); return false;">
			<div class="result"></div>
		</span>
	</div>
	<input type="hidden" id="selected_product_id" value="">
		<?
			putTree('prod', '../prof_els/');
		?>
<?}else{ echo 'Доступ запрещён. Авторизуйтесь.';}
include('../template/footer.php');?>
