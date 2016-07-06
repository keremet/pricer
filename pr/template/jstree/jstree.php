<?
	function putTree($suf, $path){
?>
		<div id="container<?=$suf?>" role="main">
			<div id="tree<?=$suf?>"></div>
			<div id="data<?=$suf?>">
				<div class="default" style="width: 200px;"></div>
			</div>
		</div>
		<!--script src="../template/jstree/jquery.min.js"></script-->
		<script src="../template/jstree/jstree.min.js"></script>
		
<script>
		$(function () {
			$(window).resize(function () {
				var h = Math.max($(window).height() - 0, 520);
				$('#container<?=$suf?>, #data<?=$suf?>, #tree<?=$suf?>').height(h).filter('.default').css('lineHeight', h + 'px');
			}).resize();

			$('#tree<?=$suf?>')
				.jstree({
					'core' : {
						'data' : {
							'url' : '<?=$path?>tree.php?operation=get_node',
							'data' : function (node) {
								return { 'id' : node.id };
							}/*,
							"error": function (jqXHR, textStatus, errorThrown) { $('#tree').html("<h3>There was an error while loading data for this tree</h3><p>" + jqXHR.responseText + "</p>"); }*/
						},
						'check_callback' : function(o, n, p, i, m) {
							if(m && m.dnd && m.pos !== 'i') { return false; }
							if(o === "move_node" || o === "copy_node") {
								if(this.get_node(n).parent === this.get_node(p).id) { return false; }
							}
							return true;
						},
						'force_text' : true,
						'themes' : {
							'responsive' : false,
							'variant' : 'small',
							'stripes' : true
						}
					},
					'contextmenu' : {
						'items' : function(node) {
							var tmp = $.jstree.defaults.contextmenu.items();

							tmp.remove.label = "Удалить";
							tmp.ccp.label = "Редактирование";
							tmp.ccp.submenu.copy.label = "Копировать";
							tmp.ccp.submenu.cut.label = "Вырезать";
							
							if(this.get_type(node) === "file") {
								delete tmp.create;
								delete tmp.rename;
								delete tmp.ccp.submenu.paste;
							}else{
								tmp.create.action = function (data) {
										var inst = $.jstree.reference(data.reference),
											obj = inst.get_node(data.reference);
										inst.create_node(obj, { type : "default" }, "last", function (new_node) {
											setTimeout(function () { inst.edit(new_node); },0);
										});
									}
								tmp.create.label = "Добавить каталог";
								tmp.rename.label = "Переименовать";
								tmp.ccp.submenu.paste.label = "Вставить";
							}
							return tmp;
						}
					},
					'types' : {
						'default' : { 'icon' : 'folder' },
						'file' : { 'valid_children' : [], 'icon' : 'file' }
					},
					'plugins' : ['dnd','types','contextmenu']
				})
				.on('delete_node.jstree', function (e, data) {
					$.get('<?=$path?>tree.php?operation=delete_node', { 'id' : data.node.id })
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('create_node.jstree', function (e, data) {
					$.get('<?=$path?>tree.php?operation=create_node', { 'type' : data.node.type, 'id' : data.node.parent, 'text' : data.node.text })
						.done(function (d) {
							data.instance.set_id(data.node, d.id);
						})
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('rename_node.jstree', function (e, data) {
					$.get('<?=$path?>tree.php?operation=rename_node', { 'id' : data.node.id, 'text' : data.text })
						.done(function (d) {
							data.instance.set_id(data.node, d.id);
						})
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('move_node.jstree', function (e, data) {
					$.get('<?=$path?>tree.php?operation=move_node', { 'id' : data.node.id, 'parent' : data.parent })
						.done(function (d) {
							//data.instance.load_node(data.parent);
							data.instance.refresh();
						})
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('copy_node.jstree', function (e, data) {
					$.get('<?=$path?>tree.php?operation=copy_node', { 'id' : data.original.id, 'parent' : data.parent })
						.done(function (d) {
							//data.instance.load_node(data.parent);
							data.instance.refresh();
						})
						.fail(function () {
							data.instance.refresh();
						});
				})
				.on('changed.jstree', function (e, data) {
					if(data && data.selected && data.selected.length) {
						if(window.last_tree_id !== undefined && last_tree_id == data.selected.join(':')){
							$('.fancybox-close').click();
						}else{
							window.last_tree_id = data.selected.join(':');
							$.get('<?=$path?>tree.php?<?=($suf=='')?'':'smart_form=1&'?>operation=get_content&id=' + data.selected.join(':'), function (d) {
									if(d){
										$('#data<?=$suf?> .default').html(d.content).show();
										<?if($suf=='prod'){?>
											product_select(d.product_id, d.product_name);
										<?}else if($suf=='shop'){?>
											shop_select(d.shop_id, d.shop_name, '', '', d.shop_address);
										<?}?>
									}
								}
							);
						}

					}
					else
						$('#data<?=$suf?> .default').html('').show();
				});
		});
		</script>
<?
	}
?>
