<?
include($_SERVER['DOCUMENT_ROOT'].'/beacon.php');
$GLOBALS['site_settings']['TAB_TITLE'] = 'Кировцены - Аналитика';
$GLOBALS['site_settings']['META']['TITLE'] = 'Кировцены - Аналитика';
$GLOBALS['site_settings']['META']['DESCRIPTION'] = 'Кировцены - аналитика цен на продукты в Кирове';
$GLOBALS['site_settings']['META']['KEYWORDS'] = 'Киров, цены, продукты, новости';
include($GLOBALS['site_settings']['root_path'].'/template/header/index.php');?>
<script type="text/javascript" src="<?=$GLOBALS['site_settings']['site_folder'];?>/template/input_calendar/tcal.js"></script>
<link rel="stylesheet" href="<?=$GLOBALS['site_settings']['site_folder'];?>/template/input_calendar/tcal.css"/>
<script>
	$('body').on('click','.sel_item', function(){
		if($(this).hasClass('product')){ //alert('продукт');
			var strid = $(this).attr('id');
			var numid = strid.substr(9);
			$('#tr_prod_'+numid).removeClass('selected'); 
			$('#tr_prod_'+numid+' input').removeAttr('checked');
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
</script>
<?$query = "SELECT id, login, name FROM `".$GLOBALS['site_settings']['db']['tables']['users']."` order by login";
$users_ = $db->select($query, array());
$arUsers = array();
if(is_array($users_)){
	foreach($users_ as $k => $v){
		$arUsers[$v['id']] = $v['login'];
	}
}
//rp($arUsers);
//rp($users_);

$query = "SELECT id, name FROM `".$GLOBALS['site_settings']['db']['tables']['products']."` order by name";
$prods_ = $db->select($query, array());
$arProds = array();
if(is_array($prods_)){
	foreach($prods_ as $k => $v){
		$arProds[$v['id']] = $v['name'];
	}
}

$query = "SELECT `id`, `name`, `address`, `network`, `town` FROM `".$GLOBALS['site_settings']['db']['tables']['shops']."` order by name";
$shops_ = $db->select($query, array());
$arShops = array();
if(is_array($shops_)){
	foreach($shops_ as $k => $v){
		$arShops[$v['id']] = $v['name'];
	}
}

//$query = "SELECT * FROM `".$GLOBALS['site_settings']['db']['tables']['product_offers']."`";
$query_array = array();
$query = "select ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".date_buy as `дата покупки`,
 ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".price as цена,
 ".$GLOBALS['site_settings']['db']['tables']['users'].".login as покупатель,
 ".$GLOBALS['site_settings']['db']['tables']['products'].".name as товар,
 ".$GLOBALS['site_settings']['db']['tables']['shops'].".name as магазин
 from ".$GLOBALS['site_settings']['db']['tables']['product_offers'].", ".$GLOBALS['site_settings']['db']['tables']['products'].", ".$GLOBALS['site_settings']['db']['tables']['shops'].", ".$GLOBALS['site_settings']['db']['tables']['users']."
 where ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".product = ".$GLOBALS['site_settings']['db']['tables']['products'].".id
 and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".shop = ".$GLOBALS['site_settings']['db']['tables']['shops'].".id
 and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".creator = ".$GLOBALS['site_settings']['db']['tables']['users'].".id";

if(is_array($_GET['filter']) && in_array('date', $_GET['filter'])){
	if($_GET['date_from']){
		$pieces = explode("/", $_GET['date_from']);
		$date_from = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
		$query .= " and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".date_buy >= {?}";
		$query_array[] = $date_from;
		//echo $date_from;
	}
	if($_GET['date_to']){
		$pieces = explode("/", $_GET['date_to']);
		$date_to = $pieces[2].'-'.$pieces[1].'-'.$pieces[0];
		$query .= " and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".date_buy <= {?}";
		$query_array[] = $date_to;
		//echo $date_to;
	}
}

if(is_array($_GET['filter']) && in_array('price', $_GET['filter'])){
	if($_GET['price_from']){
		$query .= " and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".price >= {?}";
		$query_array[] = $_GET['price_from'];
	}
	if($_GET['price_to']){
		$query .= " and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".price <= {?}";
		$query_array[] = $_GET['price_to'];
	}
}

if(is_array($_GET['product'])){
	$query .= " and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".product IN (";
	$i = 0;
	foreach($_GET['product'] as $k => $v){
		if($i != 0){
			$query .= ", ";
		}else $i = 1;
		$query .= "{?}";
		$query_array[] = $v;
	}
	$query .= ")";
}

if(is_array($_GET['shops'])){
	$query .= " and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".shop IN (";
	$i = 0;
	foreach($_GET['shops'] as $k => $v){
		if($i != 0){
			$query .= ", ";
		}else $i = 1;
		$query .= "{?}";
		$query_array[] = $v;
	}
	$query .= ")";
}

if(is_array($_GET['users'])){
	$query .= " and ".$GLOBALS['site_settings']['db']['tables']['product_offers'].".creator IN (";
	$i = 0;
	foreach($_GET['users'] as $k => $v){
		if($i != 0){
			$query .= ", ";
		}else $i = 1;
		$query .= "{?}";
		$query_array[] = $v;
	}
	$query .= ")";
}
//echo $query;
 $table_offers = $db->select($query, $query_array);

//rp($table_offers);
//$final_table = array();
$ti = 0;
/*foreach($table_offers as $k => $v){
	//rp ($v);
	foreach($v as $k2 => $v2){
		if($k2 == 'product'){ 
			$final_table[$ti][$k2] = $prods[$v2];
		}elseif($k2 == 'shop'){ 
			$final_table[$ti][$k2] = $shops[$v2];
		}elseif($k2 == 'creator'){ 
			$final_table[$ti][$k2] = $users[$v2];
		}else{ 
			$final_table[$ti][$k2] = $v2;
		//}
	}
	$ti ++;
}*/
//rp($_GET);?>
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
		<?foreach($prods_ as $k => $v){
			echo '<tr id="tr_prod_'.$v['id'].'"';
			if(is_array($_GET['product']) && in_array($v['id'], $_GET['product'])) echo ' class="selected"';
			echo '><td><input type="checkbox"';
			if(is_array($_GET['product']) && in_array($v['id'], $_GET['product'])) echo ' checked="checked"';
			echo ' name="product[]" onchange="$(this).closest(\'tr\').toggleClass(\'selected\'); if($(this).closest(\'tr\').hasClass(\'selected\')){ $(\'div.selected_items#selected_product\').append(\'<span class=&#34sel_item product&#34; id=&#34;sel_prod_'.$v['id'].'&#34;>'.htmlspecialchars($v['name'], ENT_QUOTES).'</span>\');} else {$(\'#sel_prod_'.$v['id'].'\').remove();}" value="'.$v['id'].'" ></td><td onclick="$(this).parent(\'tr\').find(\'input[type=checkbox]\').click();">'.htmlspecialchars($v['name'], ENT_QUOTES).'<div class="select_box">'.$v['id'].'</div></td></tr>';
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
<?if(is_array($table_offers)){?>
<table id="myTable" class="main tablesorter">
	<thead>
		<tr>
		<?if(is_array($table_offers[0])){
			foreach($table_offers[0] as $k => $v){?>
				<th class="header"><?=$k?></th>
			<?}
		}?>
		</tr>
	</thead>
	<tbody>
	<?foreach($table_offers as $k => $v){?>
		<tr>
		<?foreach($v as $k2 => $v2){?>
			<td><?=$v2?></td>
		<?}?>
		</tr>
	<?}
	//echo rp($final_table);?>
	</tbody>
</table>
<?}?>
<?include($GLOBALS['site_settings']['root_path'].'/template/footer/index.php');?>