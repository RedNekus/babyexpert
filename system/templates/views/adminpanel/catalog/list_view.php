<link rel="stylesheet" type="text/css" media="screen" href="/css/admin/catalog.css" />
<script type="text/javascript" src="/js/admin/catalog/grid_catalog.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_images.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_complect.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_prefix.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_tree.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_pohozhie.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_soput.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_podarok.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_razdel.js"></script>
<script type="text/javascript" src="/js/admin/catalog/grid_razmer.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="path"]');
  })
</script>
<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 24px;" class="ui-userdata ui-state-default">
				
				<?php if(@$access['catalog_add']==1): ?>
					<div id="addtree" class="button add-folder"></div>
				<?php else: ?>
					<div class="button add-folder noactive"></div>
				<?php endif; ?>
					<div id="AddTreeDialog">							
							<div class="admin-tabs">
								<ul>
									<li><a href="#tabs-tree-catalog-1">Общее</a></li>
									<li><a href="#tabs-tree-catalog-2">Описание</a></li>
									<li><a href="#tabs-tree-catalog-3">Meta</a></li>
									<li id="tab_prefix"><a href="#tabs-tree-catalog-4">Префиксы</a></li>
								</ul>
							<form  method="post" name="catalog_form_tree" id="catalog_form_tree" action="">
								<div id="tabs-tree-catalog-1">
									<input type="hidden" name="action_tree" id="action_tree"/>
									<input type="hidden" name="id" id="valueTree_id"/>
									<input type="hidden" name="id_lng" id="valueTree_id_lng"/>
									<input type="hidden" name="pid" id="valueTree_pid"/>
									<table class="table-tabs-content tree">								
										<tr>
											<td class="td-name-width"> Активен: </td>				
											<td><input type="checkbox" name="active"  id="valueTree_active" /></td>
										</tr>	
										<tr>
											<td class="td-name-width"> Показывать оптовикам</td>				
											<td><input type="checkbox" name="show_opt" id="valueTree_show_opt"  /></td>
										</tr>	
										<tr>
											<td class="td-name-width"> Показывать банер</td>				
											<td><input type="checkbox" name="show_banner" id="valueTree_show_banner"  /></td>
										</tr>											
										<tr>
											<td> Краткое название:</td>	
											<td><input type="text" name="name" id="valueTree_name"/></td>
										</tr>										
										<tr>
											<td> Краткое название: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>	
											<td><input type="text" name="name_lng" id="valueTree_name_lng"/></td>
										</tr>
										<tr>
											<td> Полное название:</td>	
											<td><input type="text" name="fullname" id="valueTree_fullname"/></td>
										</tr>										
										<tr>
											<td> Полное название: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>	
											<td><input type="text" name="namefull_lng" id="valueTree_namefull_lng"/></td>
										</tr>
										<tr>
											<td> Путь:</td>	
											<td><input type="text" name="path" id="valueTree_path"/></td>
										</tr>	
										<tr>
											<td> Приоритет:</td>	
											<td><input type="text" name="prioritet" id="valueTree_prioritet" style="width: 50px;"/></td>
										</tr>	
										<tr>
											<td> Стоймость доставки:</td>	
											<td><input type="text" name="cost_dostavka" id="valueTree_cost_dostavka"/></td>
										</tr>
										<tr>
											<td> Характеристики:</td>	
											<td>
												<select name="characteristic" id="valueTree_characteristic">
													<option value="0">-- Выберите --</option>
													<?php foreach ($characteristics as $item): ?>														
														<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</td>
										</tr>										
									</table>
								</div>
								
								<div id="tabs-tree-catalog-2" class="without-indent">
									<div class="admin-tabs">
										<ul>
											<li><a href="#tabs-content-2-lng1">Русский</a></li>
											<li><a href="#tabs-content-2-lng2">Белорусский</a></li>					
										</ul>
										<div id="tabs-content-2-lng1">
											<table class="table-tabs-content tree">
												<tr>
													<td><label class="opisanie-label">Описание:</label></td>
												</tr>
												<tr>
													<td><textarea name="short_description" class="tinymce" cols="25" rows="8" id="valueTree_short_description"></textarea></td>
												</tr>
												<tr>
													<td><label class="opisanie-label">Описание предложения:</label></td>
												</tr>
												<tr>
													<td><textarea name="description_app" cols="25" rows="8" id="valueTree_description_app"></textarea></td>
												</tr>
											</table>
										</div>							
										<div id="tabs-content-2-lng2">
											<table class="table-tabs-content tree">
												<tr>
													<td><label class="opisanie-label">Описание:</label></td>
												</tr>
												<tr>
													<td><textarea name="short_description_lng" class="tinymce" cols="25" rows="8" id="valueTree_short_description_lng"></textarea></td>
												</tr>
						
												<tr>
													<td><label class="opisanie-label">Описание предложения:</label></td>
												</tr>
												<tr>
													<td><textarea name="description_app_lng" cols="25" rows="8" id="valueTree_description_app_lng"></textarea></td>
												</tr>						
											</table>
										</div>
									</div>
								</div>
								<div id="tabs-tree-catalog-3" class="without-indent">
									<div class="admin-tabs">
										<ul>
											<li><a href="#tabs-content-3-lng1">Русский</a></li>
											<li><a href="#tabs-content-3-lng2">Белорусский</a></li>					
										</ul>
										<div id="tabs-content-3-lng1">
											<table class="table-tabs-content tree">
												<tbody>					
													<tr>
														<td class="td-name-width"> Title: </td>				
														<td><input type="text" name="title" id="valueTree_title" /></td>
													</tr>
													<tr>
														<td class="td-name-align"> Meta Keywords:</td>				
														<td><textarea name="keywords" id="valueTree_keywords"></textarea></td>
													</tr>
													<tr>
														<td class="td-name-align"> Meta Description: </td>				
														<td><textarea name="description" id="valueTree_description"></textarea></td>
													</tr>						
												</tbody>
											</table>
										</div>							
										<div id="tabs-content-3-lng2">
											<table class="table-tabs-content tree">
												<tbody>					
													<tr>
														<td class="td-name-width"> Title: </td>				
														<td><input type="text" name="title_lng" id="valueTree_title_lng" /></td>
													</tr>
													<tr>
														<td class="td-name-align"> Meta Keywords:</td>				
														<td><textarea name="keywords_lng" id="valueTree_keywords_lng"></textarea></td>
													</tr>
													<tr>
														<td class="td-name-align"> Meta Description: </td>				
														<td><textarea name="description_lng" id="valueTree_description_lng"></textarea></td>
													</tr>						
												</tbody>
											</table>
										</div>
									</div>																
								</div>
							</form>	
								<div id="tabs-tree-catalog-4">
									<table id="TablePrefix"></table>
									
										<div id="catalogFormPrefix">
											<form method="post" name="catalog_frm_prefix" id="catalog_frm_prefix" action="">
												<input type="hidden" name="id_prefix" id="valuePrefix_id"/>
												<input type="hidden" name="action_prefix" id="action_prefix"/>
												<input type="hidden" name="id_tree" id="valuePrefix_id_tree"/>
												<table class="table-tabs-catalog ImageTable" id="PrefixTable">								
													<tr>
														<td class="td-name-width"> <label>Базовая:</label> </td>				
														<td><input type="checkbox" name="baza"  id="valuePrefix_baza" /></td>
													</tr>										
													<tr>
														<td> <label>Название:</label></td>	
														<td><input type="text" name="name" id="valuePrefix_name" /></td>
													</tr>		
												</table>
											</form>
										</div>
			
										<div id="DelPrefixForm" class="del-form">
											<input type="hidden" id="del_id_prefix"/>
												<p>Вы действительно хотите удалить префикс?</p>
										</div>	
									
								</div>
								
							</div>
													
					</div>
				<?php if(@$access['catalog_edit']==1): ?>	
					<div id="edittree" class="button edit-folder"></div>
				<?php else: ?>
					<div class="button edit-folder noactive"></div>
				<?php endif; ?>
				<?php if(@$access['catalog_del']==1): ?>
					<div id="deltree" class="button del-folder"></div>
				<?php else: ?>
					<div class="button del-folder noactive"></div>
				<?php endif; ?>
					<div id="delrazdel" class="del-form">
						<input type="hidden" id="del_id_tree"/>
							<p>Вы действительно хотите удалить раздел?</p>
					</div>

			</div>
			<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"></div>
			<div class="ui-jqgrid-bdiv">
			
				<ul id="tree_catalog" class="filetree">
					<?php echo $trees; ?>
				</ul>
				
			</div>
				
			<div class="ui-state-default ui-jqgrid-pager corner-bottom" style="width: 100%;" dir="ltr"></div>
		</div>
	
	</div>
	
	<div id="right">
        <table id="TableCatalog"></table>
        <div id="TableCatalogPager"></div>
		
		<div id="catalogFormDel" class="del-form">
			<input type="hidden" id="del_id_catalog"/>
			<p>Вы действительно хотите удалить товар?</p>
		</div>	
		
		<div id="catalogFormCopy" class="del-form">
			<input type="hidden" id="copy_id_catalog"/>
			<p>Вы действительно хотите скопировать товар?</p>
		</div>
	
		<div id="catalogForm">
			
				<div id="tabs-catalog">
					<ul>
						<li><a href="#tabs-catalog-1">Общие</a></li>
						<li><a href="#tabs-catalog-2">Описание</a></li>
						<li><a href="#tabs-catalog-3">Инструкции</a></li>
						<li><a href="#tabs-catalog-4">Meta</a></li>
						<li><a href="#tabs-catalog-5">Картинки</a></li>
						<li><a href="#tabs-catalog-6">Комплект</a></li>
						<li><a href="#tabs-catalog-7">3D фото</a></li>
						<li><a href="#tabs-catalog-8">Разделы</a></li>
						<li><a href="#tabs-catalog-9">Характеристики</a></li>	
						<li><a href="#tabs-catalog-10">Похожие</a></li>
						<li><a href="#tabs-catalog-11">Сопутствующие</a></li>					
						<li><a href="#tabs-catalog-12">Подарок</a></li>					
						<li><a href="#tabs-catalog-13">Розыгрыши</a></li>					
						<li><a href="#tabs-catalog-14">Минторг</a></li>						
						<li><a href="#tabs-catalog-15">Размеры</a></li>						
						<li><a href="#tabs-catalog-16">Цена</a></li>						
					</ul>
				<form method="post" name="catalog_form" id="catalog_form" action="">	
	<!-- Вкладка общие !-->				
					<div id="tabs-catalog-1">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="id_lng" id="val_id_lng"/>
					<input type="hidden" name="action" id="action_pole" />				
					<table class="table-tabs-catalog">
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен</td>				
								<td><input type="checkbox" name="active" id="val_active"  /></td>
							</tr>								
							<tr>
								<td class="td-name-width"> Не показывать расцветку: </td>				
								<td><input type="checkbox" name="no_active_color"  id="val_no_active_color" /></td>
							</tr>											
							<tr>
								<td> Префикс</td>	
								<td id="val_id_prefix"></td>
							</tr>
							<tr>
								<td> Характеристики</td>
								<td>
									<select name="id_char" id="val_id_char">
										<option value="0">-- Выберите --</option>
										<?php foreach ($characteristics as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td> Вид комплекта</td>
								<td>
									<select name="vid_complect" id="val_vid_complect">
										<option value="0">-- Выберите --</option>			
										<option value="1">Теплицы</option>
										<option value="2">Умывальники 1</option>
										<option value="3">Умывальники 2</option>
										<option value="4">Душ 1</option>
										<option value="5">Душ 2</option>
										<option value="6">Душ 3</option>
										<option value="7">Беседка</option>
										<option value="8">Расцветка</option>
										<option value="9">Сборка</option>
									</select>
								</td>
							</tr>							
							<tr>
								<td> Статус</td>	
								<td>
									<select name="status" id="val_status">
										<option value="0">-- Выберите --</option>			
										<option value="1">Наличие уточняйте</option>
										<option value="2">Нет в наличии</option>
										<option value="3">Под заказ</option>
										<option value="4">Снят с производства</option>
										<option value="5">Скоро в продаже</option>										
										<option value="6">В наличии</option>										
										<option value="7">Наличие уточняйте (onliner-под заказ)</option>										
										<option value="8">Наличие уточняйте (onliner-в наличии)</option>										
									</select>
								</td>
							</tr>							
							<tr>
								<td> Название</td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>	
							<tr>
								<td> Название <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>				
								<td><input type="text" name="name_lng" id="val_name_lng" /></td>
							</tr>	
							<tr>
								<td> Дополнительный текст</td>				
								<td><input type="text" name="dop_text" id="val_dop_text" /></td>
							</tr>							
							<tr>
								<td> Путь</td>				
								<td><input type="text" name="path" id="val_path" /></td>
							</tr>
							<tr>
								<td> Производитель</td>	
								<td>
									<select name="id_maker" id="val_id_maker">
										<option value="0">-- Выберите --</option>
										<?php foreach ($makers as $item): ?>							
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>							
							<tr>
								<td> Старая цена $</td>				
								<td><input type="text" name="cena_old" id="val_cena_old" /></td>
							</tr>							
							<tr>
								<td> Цена $</td>				
								<td><input type="text" name="cena" id="val_cena" /></td>
							</tr>
							<tr>
								<td> Курс валют</td>	
								<td>
									<select name="id_currency" id="val_id_currency">
										<?php foreach ($currency as $item): ?>							
											<option value="<?php echo $item['id']; ?>" <?php if ($item['baza']==1) echo "selected";?>><?php echo $item['name'].' {'.$item['kurs'].'}'; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>							
							<tr>
								<td> Цена br</td>				
								<td><input type="text" name="cena_blr"  id="val_cena_blr" /></td>
							</tr>							
							<tr>
								<td> Старая цена br</td>				
								<td><input type="text" name="cena_blr_old"  id="val_cena_blr_old" /></td>
							</tr>							
							<tr>
								<td> Приоритет</td>				
								<td><input type="text" name="prioritet"  id="val_prioritet" style="width: 50px;" /></td>
							</tr>	
							<tr>
								<td> Ссылка на видео</td>				
								<td><input type="text" name="video_url"  id="val_video_url" /></td>
							</tr>								
							<tr>
								<td> Новинка</td>				
								<td><input type="checkbox" name="new"  id="val_new" /></td>
							</tr>
							<tr>
								<td> Хит продаж</td>				
								<td><input type="checkbox" name="hit"  id="val_hit" /></td>
							</tr>
							<tr>
								<td> Спец. предложение</td>				
								<td><input type="checkbox" name="spec_pred"  id="val_spec_pred" /></td>
							</tr>							
							<tr>
								<td> Выбор эксперта</td>				
								<td><input type="checkbox" name="expert"  id="val_expert" /></td>
							</tr>	
							<tr>
								<td> Товар недели</td>	
								<td>
									<select name="tovar_nedeli" id="val_tovar_nedeli">
										<option value="0">-- Выберите --</option>
										<?php foreach ($product_week as $item): ?>							
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td> Цена недели $</td>				
								<td><input type="text" name="pw_cena" id="val_pw_cena" /></td>
							</tr>							
							<tr>
								<td> Цена недели br</td>				
								<td><input type="text" name="pw_cena_blr"  id="val_pw_cena_blr" /></td>
							</tr>							
						</tbody>
					</table>
					</div>
	<!-- Вкладка описание !-->
					<div id="tabs-catalog-2" class="without-indent">
						<div id="tabs-catalog-2-lng">
							<ul>
								<li><a href="#tabs-catalog-2-lng1">Русский</a></li>
								<li><a href="#tabs-catalog-2-lng2">Белорусский</a></li>					
							</ul>
							<div id="tabs-catalog-2-lng1">
								<table>
									<tr>
										<td><label class="opisanie-label">Краткое описание:</label></td>
									</tr>
									<tr>
										<td><textarea name="short_description" class="tinymce" cols="10" rows="8" id="val_short_description"></textarea></td>
									</tr>
									<tr>
										<td><label class="opisanie-label">Полное описание:</label></td>
									</tr>
									<tr>
										<td><textarea name="full_description" class="tinymce" cols="10" rows="8" id="val_full_description"></textarea></td>		
									</tr>
								</table>
							</div>
							<div id="tabs-catalog-2-lng2">
								<table>
									<tr>
										<td><label class="opisanie-label">Краткое описание:</label></td>
									</tr>
									<tr>
										<td><textarea name="short_description_lng" class="tinymce" cols="10" rows="8" id="val_short_description_lng"></textarea></td>
									</tr>
									<tr>
										<td><label class="opisanie-label">Полное описание:</label></td>
									</tr>
									<tr>
										<td><textarea name="full_description_lng" class="tinymce" cols="10" rows="8" id="val_full_description_lng"></textarea></td>		
									</tr>
								</table>
							</div>							
						</div>
					</div>					
	<!-- Вкладка инструкции !-->				
					<div id="tabs-catalog-3" class="without-indent">
						<div id="tabs-catalog-3-lng">
							<ul>
								<li><a href="#tabs-catalog-3-lng1">Русский</a></li>
								<li><a href="#tabs-catalog-3-lng2">Белорусский</a></li>					
							</ul>
							<div id="tabs-catalog-3-lng1">
								<table>
									<tr>
										<td><label class="opisanie-label">Дополнительное описание::</label></td>
									</tr>
									<tr>
										<td><textarea name="instructions" class="tinymce" cols="20" rows="28" id="val_instructions"></textarea></textarea></td>
									</tr>
								</table>	
							</div>	
							<div id="tabs-catalog-3-lng2">
								<table>
									<tr>
										<td><label class="opisanie-label">Дополнительное описание::</label></td>
									</tr>
									<tr>
										<td><textarea name="instructions_lng" class="tinymce" cols="20" rows="28" id="val_instructions_lng"></textarea></textarea></td>
									</tr>
								</table>	
							</div>								
						</div>
					</div>	
	<!-- Вкладка meta tags !-->					
					<div id="tabs-catalog-4" class="without-indent">
						<div id="tabs-catalog-4-lng">
							<ul>
								<li><a href="#tabs-catalog-4-lng1">Русский</a></li>
								<li><a href="#tabs-catalog-4-lng2">Белорусский</a></li>					
							</ul>
							<div id="tabs-catalog-4-lng1">						
								<table class="table-tabs-catalog">
									<tbody>					
										<tr>
											<td class="td-name-width"> <label>Title:</label> </td>				
											<td><input type="text" name="title" id="val_title" /></td>
										</tr>
										<tr>
											<td class="td-name-align"> <label>Meta Keywords:</label></td>				
											<td><textarea name="keywords" id="val_keywords"></textarea></td>
										</tr>
										<tr>
											<td class="td-name-align"> <label>Meta Description:</label> </td>				
											<td><textarea name="description" id="val_description"></textarea></td>
										</tr>						
									</tbody>
								</table>
							</div>
							<div id="tabs-catalog-4-lng2">						
								<table class="table-tabs-catalog">
									<tbody>					
										<tr>
											<td class="td-name-width"> <label>Title:</label> </td>				
											<td><input type="text" name="title_lng" id="val_title_lng" /></td>
										</tr>
										<tr>
											<td class="td-name-align"> <label>Meta Keywords:</label></td>				
											<td><textarea name="keywords_lng" id="val_keywords_lng"></textarea></td>
										</tr>
										<tr>
											<td class="td-name-align"> <label>Meta Description:</label> </td>				
											<td><textarea name="description_lng" id="val_description_lng"></textarea></td>
										</tr>						
									</tbody>
								</table>
							</div>							
						</div>
					</div>
	<!-- Вкладка разделы !-->
					<div id="tabs-catalog-8">				
						<div class="left-tc10" >						
							<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
								<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Переместить товар в другой раздел</span></div>
								<div class="ui-jqgrid-bdiv" style="height: 409px;">
								
									<ul id="tree_catalog_razdel" class="filetree">
										<?php echo $trees; ?>
									</ul>
									
								</div>

							</div>
						</div>	
						<div class="right-tc10" >
						<img id="loading_razdel" src="/img/admin/loading.gif"/>							
							<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Товары</span></div>
							<table id="TableRazdel"></table>
							<div id="pTableRazdel"></div>
						</div>						
					</div>					
	<!-- Вкладка похожие !-->						
					<div id="tabs-catalog-10">
					
						<div class="left-tc10">	
							<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
								<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Разделы</span></div>
								<div class="ui-jqgrid-bdiv" style="height: 408px;">
								
									<ul id="tree_catalog_pohozhie" class="filetree">
										<?php echo $trees; ?>
									</ul>
									
								</div>

							</div>
						</div>
						
						<div class="right-tc10">
							<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Товары</span></div>
							<table id="TablePohozhie"></table>
							<input type="text" name="pohozhie" id="val_pohozhie" value="" style="display: none;"/>
						</div>

					</div>
	<!-- Вкладка сопутствующие !-->						
					<div id="tabs-catalog-11">
					
						<div class="left-tc10" >	
							<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
								<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Разделы</span></div>
								<div class="ui-jqgrid-bdiv" style="height: 408px;">
								
									<ul id="tree_catalog_soput" class="filetree">
										<?php echo $trees; ?>
									</ul>
									
								</div>

							</div>
						</div>
						<div class="right-tc10" >
							<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Товары</span></div>
							<table id="TableSoput"></table>
							<input type="text" name="soput" id="val_soput" value="" style="display: none;"/>
						</div>
					
					</div>		
	<!-- Вкладка подарок !-->						
					<div id="tabs-catalog-12">
					
						<div class="left-tc10" >	
							<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
								<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Разделы</span></div>
								<div class="ui-jqgrid-bdiv" style="height: 408px;">
								
									<ul id="tree_catalog_podarok" class="filetree">
										<?php echo $trees; ?>
									</ul>
									
								</div>

							</div>
						</div>
						<div class="right-tc10" >
							<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Товары</span></div>
							<table id="TablePodarok"></table>
							<input type="text" name="podarok" id="val_podarok" value="" style="display: none;"/>
						</div>
					
					</div>		
	<!-- Вкладка розыгрыши !-->						
					<div id="tabs-catalog-13">
					
						<div id="raffle_create"></div>
					
					</div>
	<!-- Минторг !-->					
					<div id="tabs-catalog-14">			
					<table class="table-tabs-catalog">
						<tbody>																			
							<tr>
								<td class="td-name-width"> Импортер</td>				
								<td><input type="text" name="importer" id="val_importer" /></td>
							</tr>	
							<tr>
								<td> Сервисный центр</td>				
								<td><input type="text" name="serv_centr" id="val_serv_centr" /></td>
							</tr>							
							<tr>
								<td> Стоймость доставки</td>				
								<td><input type="text" name="cost_dostavka" id="val_cost_dostavka" /></td>
							</tr>							
							<tr>
								<td> Срок доставки по городу (дней)</td>				
								<td><input type="text" name="srok_city" value="3" id="val_srok_city" /></td>
							</tr>						
							<tr>
								<td> Срок доставки по Беларуси (дней)</td>				
								<td><input type="text" name="srok_country" value="15" id="val_srok_country" /></td>
							</tr>										
						</tbody>
					</table>
					</div>	
					<div id="tabs-catalog-16">
						<table class="table-tabs-content">
							<tbody>		
								<tr>
									<td class="td-name-width"> Показывать оптовикам</td>				
									<td colspan=3><input type="checkbox" name="show_opt" id="val_show_opt"  /></td>
								</tr>							
								<tr>
									<td class="td-name-width"> Цена розница USD</td>				
									<td><input type="text" name="cena_rozn_usd" id="val_cena_rozn_usd" /></td>									
									<td class="ta-right"> Цена розница BYR</td>												
									<td><input type="text" name="cena_rozn_blr" id="val_cena_rozn_blr" /></td>
								</tr>	
								<tr>
									<td> Розница 1 USD</td>				
									<td><input type="text" name="cena_rozn1_usd" id="val_cena_rozn1_usd" /></td>
									<td class="ta-right"> Розница 1 BYR</td>				
									<td><input type="text" name="cena_rozn1_blr" id="val_cena_rozn1_blr" /></td>
								</tr>							
								<tr>
									<td> Дилерская 1 USD</td>				
									<td><input type="text" name="cena_diler1_usd" id="val_cena_diler1_usd" /></td>
									<td class="ta-right"> Дилерская 1 BYR</td>				
									<td><input type="text" name="cena_diler1_blr" id="val_cena_diler1_blr" /></td>
								</tr>							
								<tr>
									<td> Дилерская 2 USD</td>				
									<td><input type="text" name="cena_diler2_usd" id="val_cena_diler2_usd" /></td>
									<td class="ta-right"> Дилерская 2 BYR</td>				
									<td><input type="text" name="cena_diler2_blr" id="val_cena_diler2_blr" /></td>
								</tr>						
								<tr>
									<td> Дилерская 3 USD</td>				
									<td><input type="text" name="cena_diler3_usd" id="val_cena_diler3_usd" /></td>
									<td class="ta-right"> Дилерская 3 BYR</td>				
									<td><input type="text" name="cena_diler3_blr" id="val_cena_diler3_blr" /></td>
								</tr>										
							</tbody>
						</table>						
					</div>					
				</form>	
	<!-- Вкладка картинки !-->				
					<div id="tabs-catalog-5">
						<table id="TableImages"></table>
							
							<div id="catalogFormImage">
								<form method="post" name="catalog_frm_image" id="catalog_frm_image" action="/adminpanel/catalog_images/edit" target="hiddenimageframe" enctype="multipart/form-data">
									<input type="hidden" name="id_image" id="valueImage_id"/>
									<input type="hidden" name="id_lng" id="valueImage_id_lng"/>
									<input type="hidden" name="action_image" id="action_image"/>
									<input type="hidden" name="id_catalog" id="valueImage_id_catalog"/>
									<input type="hidden" name="name_tovar"  id="valueImage_name_tovar" />
									<input type="hidden" name="prefix_id_tovar"  id="valueImage_prefix_id_tovar" />	
									<input type="hidden" name="maker_id_tovar"  id="valueImage_maker_id_tovar" />	
									<table class="table-tabs-catalog ImageTable" id="ImageTable">								
										<tr>
											<td class="td-name-width"> <label>Базовая:</label> </td>				
											<td><input type="checkbox" name="showfirst"  id="valueImage_showfirst" /></td>
										</tr>										
										<tr>
											<td> <label>Картинка:</label></td>	
											<td><input type="file" size="53" name="image" id="valueImage_file"/>
											<i style="font-size: 10px;"><?php echo "min размер: ".$img_size['width']."x".$img_size['height']; ?></i>
											</td>
										</tr>
										<tr>
											<td> <label>Описание:</label></td>	
											<td><input type="text" name="description" id="valueImage_description"/>								
											</td>
										</tr>
										<tr>
											<td> <label>Описание blr:</label></td>	
											<td><input type="text" name="description_lng" id="valueImage_description_lng"/></td>
										</tr>										
										<tr>
											<td> &nbsp;</td>	
											<td> 
												<img id="loading" src="/img/admin/loading.gif"/>
												<div id="image_insert" class="left"></div>
												<iframe id="hiddenimageframe" name="hiddenimageframe" style="width:0px; height:0px; border:0px"></iframe>	
												<div class="right"><i style="font-size: 10px;">ВНИМАНИЕ. Поле "Описание" не должно содержать Префикс либо Название товара,  исключительно краткое описание картинки. Примеры: вид сбоку,  в сложенном виде,  прогулочный блок и т.д.</i></div>
											</td>
										</tr>		
									</table>
								</form>
							</div>						
						
							<div id="DelImageForm" class="del-form">
								<input type="hidden" id="del_id_image"/>
									<p>Вы действительно хотите удалить изображение?</p>
							</div>
						
					</div>
	<!-- Вкладка комплекты !-->					
					<div id="tabs-catalog-6">
						<table id="TableComplect"></table>
						<div id="pager-complect"></div>
							
							<div id="catalogFormCompt">
								<form method="post" name="catalog_frm_complect" id="catalog_frm_complect">
									<input type="hidden" name="id" id="valueCompt_id"/>
									<input type="hidden" name="action_complect" id="action_complect"/>
									<input type="hidden" name="id_catalog" id="valueCompt_id_catalog"/>							
									<table class="table-tabs-content ImageTable">
										<tr>
											<td class="td-name-width"> <label>Тип:</label> </td>				
											<td colspan=3>
												<select name="type_complect" id="valueCompt_type_complect">
													<option value="0">Нет</option>
													<!--<option value="1">Каркас</option>
													<option value="2">Удлинение</option>
													<option value="3">Поликарбонат</option>
													<option value="4">Мойка</option>
													<option value="5">Рукомойник</option>
													<option value="6">Тумба</option>
													<option value="7">Бак</option>
													<option value="8">Кабина</option>-->
													<option value="9">Расцветка</option>
													<option value="10">Сборка</option>
												</select>
											</td>
										</tr>									
										<tr class="hide-block">
											<td class="td-name-width"> <label>Раздел:</label> </td>	
											<td colspan=3>
												<select class="podbor val_id_tree">
													<option value="0">-- Выберите --</option>
													<?php foreach ($razdels as $item): ?>														
														<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</td>							
										</tr>
										<tr class="hide-block">
											<td> <label>Производитель:</label> </td>	
											<td colspan=3>
												<select class="podbor val_id_maker">
													<option value="0">-- Выберите --</option>
													<?php foreach ($makers as $item): ?>														
														<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</td>
										</tr>
										<tr class="hide-block">
											<td> <label>Товар:</label> </td>	
											<td colspan=3>
												<select name="id_product" class="podbor val_id_tovar">
													<option value="0">-- Выберите --</option>
												</select>
											</td>
										</tr>										
										<tr style="display:none;">
											<td class="td-name-width"> <label>Длина теплицы:</label> </td>				
											<td colspan=3>
												<select name="id_razmer" id="valueCompt_id_razmer">
													<option value="0">нет</option>
													<option value="1">3 х 4 х 2,1</option>
													<option value="2">3 х 6 х 2,1</option>
													<option value="3">3 х 8 х 2,1</option>
													<option value="4">3 х 10 х 2,1</option>
												</select>
											</td>
										</tr>										
										<tr>
											<td> <label>Кол-во:</label> </td>				
											<td colspan=3><input type="text" name="kolvo"  id="valueCompt_kolvo" /></td>
										</tr>
										<tr>
											<td> <label>Скидка $:</label> </td>				
											<td><input type="text" name="skidka_usd"  id="valueCompt_skidka_usd" /></td>
											<td class="ta-right"> <label>Скидка Br:</label> </td>				
											<td><input type="text" name="skidka_blr"  id="valueCompt_skidka_blr" /></td>											
										</tr>
										<tr>
											<td> <label>Доплата $:</label> </td>				
											<td><input type="text" name="doplata_usd"  id="valueCompt_doplata_usd" /></td>
											<td class="ta-right"> <label>Доплата Br:</label> </td>				
											<td><input type="text" name="doplata_blr"  id="valueCompt_doplata_blr" /></td>											
										</tr>
										<tr>
											<td> <label>Скидка розница:</label> </td>				
											<td colspan=3><input type="text" name="skidka_roznica"  id="valueCompt_skidka_roznica" /></td>
										</tr>
										<tr>
											<td> <label>Скидка розница 1:</label> </td>				
											<td colspan=3><input type="text" name="skidka_roznica1"  id="valueCompt_skidka_roznica1" /></td>
										</tr>
										<tr>
											<td> <label>Скидка дилер 1:</label> </td>				
											<td colspan=3><input type="text" name="skidka_diler1"  id="valueCompt_skidka_diler1" /></td>
										</tr>
										<tr>
											<td> <label>Скидка дилер 2:</label> </td>				
											<td colspan=3><input type="text" name="skidka_diler2"  id="valueCompt_skidka_diler2" /></td>
										</tr>
										<tr>
											<td> <label>Скидка дилер 3:</label> </td>				
											<td colspan=3><input type="text" name="skidka_diler3"  id="valueCompt_skidka_diler3" /></td>
										</tr>											
									</table>
								</form>
							</div>						
						
							<div id="DelComptForm" class="del-form">
								<input type="hidden" id="del_id_complect"/>
									<p>Вы действительно хотите удалить выбранный элемент?</p>
							</div>
											
					</div>
	<!-- Вкладка 3D фото !-->					
					<div id="tabs-catalog-7">
						<form method="post" name="catalog_frm_3d" id="catalog_frm_3d" action="/adminpanel/catalog_3d/edit" target="hidden3dframe" enctype="multipart/form-data">
							<div>
							<input type="hidden" name="id_3d" id="value3d_id"/>
							<input type="hidden" name="id_catalog" id="value3d_id_catalog"/>
								<table class="table-tabs-catalog ImageTable">										
									<tr>
										<td class="td-name-width"> <label>3D фото:</label></td>	
										<td>
											<input type="file" name="swf" size="35" id="value3d_file"/>
										</td>
									</tr>	
								</table>
							</div>
							<img id="loading" src="/img/admin/loading.gif"/>
							<iframe id="hidden3dframe" name="hidden3dframe"></iframe>	
							<br/>
							<input type="submit" name="edit3d" value="Загрузить" />
							<input type="submit" name="del3d" value="Удалить" />
						</form>	
					</div>
	<!-- Вкладка характеристики !-->					
					<div id="tabs-catalog-9">
						<form method="post" name="catalog_form_character" id="catalog_form_character" action="">
							<div>
								<table class="table-tabs-catalog CharacteristicsTable">								
									<tr><td id="tabs-formcreate-9"></td></tr>						
								</table>										
							</div>
						</form>
					</div>	
	<!-- Вкладка размеры !-->					
					<div id="tabs-catalog-15">
						<table id="TableRazmer"></table>
							
							<div id="catalogFormRazmer">
								<form method="post" name="catalog_frm_razmer" id="catalog_frm_razmer" action="">
									<input type="hidden" name="id" id="valueRazmer_id"/>
									<input type="hidden" name="action" id="action_razmer"/>
									<input type="hidden" name="id_catalog" id="valueRazmer_id_catalog"/>									
									<table class="table-tabs-catalog ImageTable">								
										<tr>
											<td class="td-name-width"> <label>Активен:</label> </td>				
											<td><input type="checkbox" name="active" id="valueRazmer_active" /></td>
										</tr>										
										<tr>
											<td class="td-name-width"> <label>Название:</label> </td>				
											<td><input type="text" name="name"  id="valueRazmer_name" /></td>
										</tr>										
										<tr>
											<td class="td-name-width"> <label>Суфикс:</label> </td>				
											<td><input type="text" name="sufix"  id="valueRazmer_sufix" /></td>
										</tr>
										<tr>
											<td class="td-name-width"> <label> Размер:</label></td>	
											<td>
												<select name="id_razmer" id="valueRazmer_id_razmer">
													
												</select>
											</td>
										</tr>	
										<tr>
											<td class="td-name-width"> <label>Длина:</label> </td>				
											<td><input type="text" name="tmp_length"  id="valueRazmer_tmp_length" /></td>
										</tr>	
										<tr>
											<td class="td-name-width"> <label>Ширина:</label> </td>				
											<td><input type="text" name="tmp_width"  id="valueRazmer_tmp_width" /></td>
										</tr>	
										<tr>
											<td class="td-name-width"> <label>Высота:</label> </td>				
											<td><input type="text" name="tmp_height"  id="valueRazmer_tmp_height" /></td>
										</tr>										
										<tr>
											<td class="td-name-width"> <label>Цена $:</label> </td>				
											<td><input type="text" name="cena"  id="valueRazmer_cena" /></td>
										</tr>	

										<tr>
											<td class="td-name-width"> <label> Краткое описание:</label></td>	
											<td>
												<select name="id_description" id="valueRazmer_id_description">
													
												</select>
											</td>
										</tr>										
										<tr>
											<td class="td-name-width"> <label>Краткое описание:</label> </td>				
											<td><input type="text" name="tmp_description"  id="valueRazmer_tmp_description" /></td>
										</tr>										
										<tr>
											<td class="td-name-width"> <label>Цена br:</label> </td>				
											<td><input type="text" name="cena_blr"  id="valueRazmer_cena_blr" /></td>
										</tr>											
									</table>
								</form>
							</div>						
						
							<div id="DelRazmerForm" class="del-form">
								<input type="hidden" id="del_id_razmer"/>
									<p>Вы действительно хотите удалить выбранный элемент?</p>
							</div>
											
					</div>					
				</div>	
		</div>		
	</div>
	
</div>	