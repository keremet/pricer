<?
include '../template/header.php';
headerOut('Аналитика');
?>
<script type="text/javascript" src="../template/input_calendar/tcal.js"></script>
<link rel="stylesheet" href="../template/input_calendar/tcal.css"/>
<script>
	$('body').on('click','.sel_item', function(){
		if($(this).hasClass('product')){ //alert('продукт');
			var strid = $(this).attr('id');
			var numid = strid.substr(9);
			$('#tr_prod_'+numid).removeClass('selected'); 
			$('#tr_prod_'+numid+' input').removeAttr('checked');
		}else if($(this).hasClass('equ_product')){
			var strid = $(this).attr('id');
			var numid = strid.substr(13);
			$('#tr_equ_prod_'+numid).removeClass('selected'); 
			$('#tr_equ_prod_'+numid+' input').removeAttr('checked');
		}else if($(this).hasClass('shop')){
			var strid = $(this).attr('id');
			var numid = strid.substr(9);
			$('#tr_shop_'+numid).removeClass('selected'); 
			$('#tr_shop_'+numid+' input').removeAttr('checked');
		}else if($(this).hasClass('user')){
			var strid = $(this).attr('id');
			var numid = strid.substr(9);
			$('#tr_user_'+numid).removeClass('selected'); 
			$('#tr_user_'+numid+' input').removeAttr('checked');
		}
		$(this).remove();
	});
	function delete_price(id){
		if (!confirm('Удалить цену?'))
			return;
		jQuery.ajax({
			url:     'delete_price.php', //Адрес подгружаемой страницы
			type:     "POST", //Тип запроса
			dataType: "html", //Тип данных
			data: {id: id}, 
			success: function(result) {
				alert(result);
				//if(result=='Цена удалена')
					location.reload();
			}
		});
	}
</script>
<?
$isUserAutorized = ($_SESSION['user']['id'] != null);

if($isUserAutorized){
	$users_ = $db->query("SELECT id, login, name FROM ".DB_TABLE_PREFIX."users order by login")->fetchAll();
	$arUsers = array();
	if($users_){
		foreach($users_ as $k => $v){
			$arUsers[$v['id']] = $v['login'];
		}
	}
}

$arProds = array();
foreach($db->query("SELECT id, name FROM  ".DB_TABLE_PREFIX."products order by name") as $row)
	$arProds[$row['id']] = $row['name'];

$arEquProds = array();
foreach($db->query("SELECT id, name FROM  ".DB_TABLE_PREFIX."products_equ_clsf WHERE id_hi is not null order by name") as $row)
	$arEquProds[$row['id']] = $row['name'];

$shops_ = $db->query("SELECT id, name, address, network_id, town_id FROM ".DB_TABLE_PREFIX."shops order by name")->fetchAll();
$arShops = array();
if($shops_){
	foreach($shops_ as $k => $v){
		$arShops[$v['id']] = $v['name'];
	}
}
?>
<form method="get" style="padding-bottom: 15px">
	<b onclick="if($(this).next('fieldset').is(':hidden')){ $(this).next('fieldset').show(200); $(this).next('fieldset').find('input[type=checkbox]').attr('checked', 'checked');} else {$(this).next('fieldset').hide(200); $(this).next('fieldset').find('input[type=checkbox]').removeAttr('checked')}">
		<a id="select_product_button" class="fancybox" href="#select_product">Фильтровать по товару</a><br>
	</b>
	<div class="selected_items" id="selected_product">
		<?if(is_array($_GET['product'])){
			foreach($_GET['product'] as $k => $v){
				echo '<span class="sel_item product" id="sel_prod_'.$v.'">'.$arProds[$v].'</span>';
			}
		}?>
	</div>
	<div id="select_product" style="display: none;">
		<table class="main select" id="product_select_table"><tr><th></th><th>Название</th></tr>
		<?foreach($arProds as $k => $v){
			echo '<tr id="tr_prod_'.$k.'"';
			if(is_array($_GET['product']) && in_array($k, $_GET['product'])) echo ' class="selected"';
			echo '><td><input type="checkbox"';
			if(is_array($_GET['product']) && in_array($k, $_GET['product'])) echo ' checked="checked"';
			echo ' name="product[]" onchange="$(this).closest(\'tr\').toggleClass(\'selected\'); if($(this).closest(\'tr\').hasClass(\'selected\')){ $(\'div.selected_items#selected_product\').append(\'<span class=&#34sel_item product&#34; id=&#34;sel_prod_'.$k.'&#34;>'.htmlspecialchars($v, ENT_QUOTES).'</span>\');} else {$(\'#sel_prod_'.$k.'\').remove();}" value="'.$k.'" ></td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.htmlspecialchars($v, ENT_QUOTES).'<div class="select_box">'.$k.'</div></td></tr>';
		}?>
		</table>
	</div>
	
	<b><a id="select_equ_product_button" class="fancybox" href="#select_equ_product">Фильтровать по эквивалентным товарам</a></b><br>
	<div class="selected_items" id="selected_equ_product">
		<?if(is_array($_GET['equ_product'])){
			foreach($_GET['equ_product'] as $k => $v){
				echo '<span class="sel_item equ_product" id="sel_equ_prod_'.$v.'">'.$arEquProds[$v].'</span>';
			}
		}?>
	</div>
	<div id="select_equ_product" style="display: none;">
		<table class="main select" id="equ_product_select_table"><tr><th></th><th>Название</th></tr>
		<?foreach($arEquProds as $k => $v){
			echo '<tr id="tr_equ_prod_'.$k.'"';
			if(is_array($_GET['equ_product']) && in_array($k, $_GET['equ_product'])) echo ' class="selected"';
			echo '><td><input type="checkbox"';
			if(is_array($_GET['equ_product']) && in_array($k, $_GET['equ_product'])) echo ' checked="checked"';
			echo ' name="equ_product[]" onchange="$(this).closest(\'tr\').toggleClass(\'selected\'); if($(this).closest(\'tr\').hasClass(\'selected\')){ $(\'div.selected_items#selected_equ_product\').append(\'<span class=&#34sel_item equ_prod&#34; id=&#34;sel_equ_prod_'.$k.'&#34;>'.htmlspecialchars($v, ENT_QUOTES).'</span>\');} else {$(\'#sel_equ_prod_'.$k.'\').remove();}" value="'.$k.'" ></td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.$v.'<div class="select_box">'.$k.'</div></td></tr>';
		}?>
		</table>
	</div>
	
	<b><a id="select_shop_button" class="fancybox" href="#select_shop">Фильтровать по магазину</a></b><br>
	<div class="selected_items" id="selected_shop">
		<?if(is_array($_GET['shops'])){
			foreach($_GET['shops'] as $k => $v){
				echo '<span class="sel_item shop" id="sel_shop_'.$v.'">'.$arShops[$v].'</span>';
			}
		}?>
	</div>
	<div id="select_shop" style="display: none;">
		<table class="main select" id="shop_select_table"><tr><th></th><th>Название</th><th>Сеть</th><th>Город</th><th>Адрес</th></tr>
		<?foreach($shops_ as $k => $v){
			echo '<tr id="tr_shop_'.$v['id'].'"';
			if(is_array($_GET['shops']) && in_array($v['id'], $_GET['shops'])) echo ' class="selected"';
			echo '><td><input type="checkbox"';
			if(is_array($_GET['shops']) && in_array($v['id'], $_GET['shops'])) echo ' checked="checked"';
			echo ' name="shops[]" onchange="$(this).closest(\'tr\').toggleClass(\'selected\'); if($(this).closest(\'tr\').hasClass(\'selected\')){ $(\'div.selected_items#selected_shop\').append(\'<span class=&#34sel_item shop&#34; id=&#34;sel_shop_'.$v['id'].'&#34;>'.htmlspecialchars($v['name'], ENT_QUOTES).'</span>\');} else {$(\'#sel_shop_'.$v['id'].'\').remove();}" value="'.$v['id'].'" ></td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.$v['name'].'<div class="select_box">'.$v['id'].'</div></td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.$v['network'].'</td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.$v['town'].'</td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.$v['address'].'</td></tr>';
		}?>
		</table>
	</div>
	
	<?if($isUserAutorized) {?>
		<b><a id="select_user_button" class="fancybox" href="#select_user">Фильтровать по автору</a></b><br>
		<div class="selected_items" id="selected_user">
			<?if(is_array($_GET['users'])){
				foreach($_GET['users'] as $k => $v){
					echo '<span class="sel_item user" id="sel_user_'.$v.'">'.$arUsers[$v].'</span>';
				}
			}?>
		</div>
		<div id="select_user" style="display: none;">
			<table class="main select" id="user_select_table"><tr><th></th><th>Логин</th><th>Ф.И.О.</th></tr>
			<?foreach($users_ as $k => $v){
				echo '<tr id="tr_user_'.$v['id'].'"';
				if(is_array($_GET['users']) && in_array($v['id'], $_GET['users'])) echo ' class="selected"';
				echo '><td><input type="checkbox"';
				if(is_array($_GET['users']) && in_array($v['id'], $_GET['users'])) echo ' checked="checked"';
				echo ' name="users[]" onchange="$(this).closest(\'tr\').toggleClass(\'selected\'); if($(this).closest(\'tr\').hasClass(\'selected\')){ $(\'div.selected_items#selected_user\').append(\'<span class=&#34sel_item user&#34; id=&#34;sel_user_'.$v['id'].'&#34;>'.htmlspecialchars($v['login'], ENT_QUOTES).'</span>\');} else {$(\'#sel_user_'.$v['id'].'\').remove();}" value="'.$v['id'].'" ></td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.$v['login'].'<div class="select_box">'.$v['id'].'</div></td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.$v['name'].'</td></tr>';
			}?>
			</table>
		</div>
	<? } ?>
	<b onclick="if($(this).next('fieldset').is(':hidden')){ $(this).next('fieldset').show(200); $(this).next('fieldset').find('input[type=checkbox]').attr('checked', 'checked');} else {$(this).next('fieldset').hide(200); $(this).next('fieldset').find('input[type=checkbox]').removeAttr('checked')}; return false;"><a href="#">Фильтровать по цене</a></b>
	<fieldset<?if(!is_array($_GET['filter']) || !in_array('price', $_GET['filter'])) echo ' style="display:none;"'?>>
		<input type="checkbox" name="filter[]" value="price" style="display:none"<?if(is_array($_GET['filter']) && in_array('price', $_GET['filter'])) echo ' checked="checked"'?>>
		от&nbsp;<input type="text" name="price_from" value="<?=$_GET['price_from']?>">
		&nbsp;до&nbsp;<input type="text" name="price_to" value="<?=$_GET['price_to']?>">
	</fieldset><br>
	<b onclick="if($(this).next('fieldset').is(':hidden')){ $(this).next('fieldset').show(200); $(this).next('fieldset').find('input[type=checkbox]').attr('checked', 'checked');} else {$(this).next('fieldset').hide(200); $(this).next('fieldset').find('input[type=checkbox]').removeAttr('checked')}; return false;"><a href="#">Фильтровать по дате добавления</a></b>
	<fieldset<?if(!is_array($_GET['filter']) || !in_array('date', $_GET['filter'])) echo ' style="display:none;"'?>>
		<input type="checkbox" name="filter[]" value="date" style="display:none"<?if(is_array($_GET['filter']) && in_array('date', $_GET['filter'])) echo ' checked="checked"'?>>
		от&nbsp;<input type="text" readonly class="tcal" name="date_from" value="<?=$_GET['date_from']?>">
		&nbsp;до&nbsp;<input type="text" readonly class="tcal" name="date_to" value="<?=$_GET['date_to']?>">
	</fieldset><br>
	<input type="submit" name="send" value="Применить фильтр" >
</form>
<table id="myTable" class="main tablesorter">
	<thead>
		<tr>
			<th class="header">Дата покупки</th>
			<th class="header">Цена</th>
			<th class="header">В упаковке</th>
			<th class="header">Цена единицы</th>
			<th class="header">Товар</th>
			<th class="header">Магазин</th>
			<?if($isUserAutorized) {?>
				<th class="header">Кол-во</th>
				<th class="header">Покупатель</th>
				<th class="header" style="width: 90px;">Действия</th>
			<? } ?>
		</tr>
	</thead>
	<tbody>
	<?
	if (isset($_GET['send'])) {
		$query_array = array();
		$query = 
		"select f.id
		  , f.creator
		  , f.date_buy
		  , f.price
		  , f.product as id_Товара
		  , ".DB_TABLE_PREFIX."products.name as Товар
		  , ".DB_TABLE_PREFIX."products.in_box
		  , f.amount
		  , ".DB_TABLE_PREFIX."ed_izm.name as ЕдИзм
		  , f.shop as id_Магазина
		  , ".DB_TABLE_PREFIX."shops.name as Магазин"
		  .(($isUserAutorized)?", ".DB_TABLE_PREFIX."users.login":"").
		 " from ".DB_TABLE_PREFIX."fact f, ".DB_TABLE_PREFIX."shops".(($isUserAutorized)?", ".DB_TABLE_PREFIX."users":"").", ".DB_TABLE_PREFIX."products
		 left join ".DB_TABLE_PREFIX."ed_izm on ".DB_TABLE_PREFIX."ed_izm.id = ".DB_TABLE_PREFIX."products.ed_izm_id
		 where f.product = ".DB_TABLE_PREFIX."products.id
		   and f.shop = ".DB_TABLE_PREFIX."shops.id"
		   .(($isUserAutorized)?" and f.creator = ".DB_TABLE_PREFIX."users.id":"");

		if(is_array($_GET['filter']) && in_array('date', $_GET['filter'])){
			if($_GET['date_from']){
				$pieces = explode("/", $_GET['date_from']);
				$date_from = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
				$query .= " and f.date_buy >= ?";
				$query_array[] = $date_from;
				//echo $date_from;
			}
			if($_GET['date_to']){
				$pieces = explode("/", $_GET['date_to']);
				$date_to = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
				$query .= " and f.date_buy <= ?";
				$query_array[] = $date_to;
				//echo $date_to;
			}
		}

		if(is_array($_GET['filter']) && in_array('price', $_GET['filter'])){
			if($_GET['price_from']){
				$query .= " and f.price >= ?";
				$query_array[] = $_GET['price_from'];
			}
			if($_GET['price_to']){
				$query .= " and f.price <= ?";
				$query_array[] = $_GET['price_to'];
			}
		}

		if(is_array($_GET['product']) && is_array($_GET['equ_product'])){
			$query .= " and (f.product IN (";
			$i = 0;
			foreach($_GET['product'] as $k => $v){
				if($i != 0){
					$query .= ", ";
				}else $i = 1;
				$query .= "?";
				$query_array[] = $v;
			}
			$query .= ") or f.product IN (
						SELECT ep.product_id
						FROM ".DB_TABLE_PREFIX."equ_products ep
						WHERE ep.equ_clsf_id IN (";
			$i = 0;
			foreach($_GET['equ_product'] as $k => $v){
				if($i != 0){
					$query .= ", ";
				}else $i = 1;
				$query .= "?";
				$query_array[] = $v;
			}
			$query .= ")))";	
		} else if(is_array($_GET['product'])){
			$query .= " and f.product IN (";
			$i = 0;
			foreach($_GET['product'] as $k => $v){
				if($i != 0){
					$query .= ", ";
				}else $i = 1;
				$query .= "?";
				$query_array[] = $v;
			}
			$query .= ")";
		} else if(is_array($_GET['equ_product'])){
			$query .= " and f.product IN (
						SELECT ep.product_id
						FROM ".DB_TABLE_PREFIX."equ_products ep
						WHERE ep.equ_clsf_id IN (";
			$i = 0;
			foreach($_GET['equ_product'] as $k => $v){
				if($i != 0){
					$query .= ", ";
				}else $i = 1;
				$query .= "?";
				$query_array[] = $v;
			}
			$query .= "))";
		}

		if(is_array($_GET['shops'])){
			$query .= " and f.shop IN (";
			$i = 0;
			foreach($_GET['shops'] as $k => $v){
				if($i != 0){
					$query .= ", ";
				}else $i = 1;
				$query .= "?";
				$query_array[] = $v;
			}
			$query .= ")";
		}

		if(is_array($_GET['users'])){
			$query .= " and f.creator IN (";
			$i = 0;
			foreach($_GET['users'] as $k => $v){
				if($i != 0){
					$query .= ", ";
				}else $i = 1;
				$query .= "?";
				$query_array[] = $v;
			}
			$query .= ")";
		}
		
		$stmt = $db->prepare($query);
		$stmt->execute($query_array);
		
		foreach($stmt->fetchAll() as $k => $v){?>
			<tr>
				<td><?=$v['date_buy']?></td>
				<td><?=$v['price']?></td>
				<td><?=($v['in_box'])?($v['in_box'].' '.$v['ЕдИзм']):($v['ЕдИзм']?'Развесной':'')?></td>
				<td><?if($v['ЕдИзм']){
					echo ($v['in_box'])?(round($v['price'] / $v['in_box'], 2)):$v['price'];
					echo ' руб / '.$v['ЕдИзм'];
					}?></td>
				<td><a href="../products/?id=<?=$v['id_Товара']?>" target="_blank"><?=$v['Товар']?></a></td>
				<td><a href="../shops/?id=<?=$v['id_Магазина']?>" target="_blank"><?=$v['Магазин']?></a></td>
				<?if($isUserAutorized) {?>
					<td><?=$v['amount']?></td>
					<td><?=$v['login']?></td>
					<td style="text-align: center;">
						<?if(($v['creator'] == $_SESSION['user']['id']) && ($v['id'])) {?>
							<button onclick="delete_price(<?=$v['id']?>);">Удалить цену</button>
						<? } ?>
					</td>
				<? } ?>
			</tr>
		<?}
	}
	?>
	</tbody>
</table>
<?include('../template/footer.php');?>
