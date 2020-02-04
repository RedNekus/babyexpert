<link rel="stylesheet" type="text/css" media="screen" href="/css/admin/characteristics.css" />
<script type="text/javascript" src="/js/admin/characteristics/grid_characteristics.js"></script>
<script type="text/javascript" src="/js/admin/characteristics/grid_tree.js"></script>
<script type="text/javascript" src="/js/admin/characteristics/grid_tip.js"></script>

<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 24px;" class="ui-userdata ui-state-default">
				
				<?php if(@$access['characteristics_add']==1): ?>
					<div id="addtree" class="button add-folder"></div>
				<?php else: ?>
					<div class="button add-folder noactive"></div>
				<?php endif; ?>
				
					<div id="AddTreeDialog">
						<form  method="post" name="characteristics_form_tree" id="characteristics_form_tree" action="">					
							<table class="table-tabs-content tip" style="margin-top: 10px;">
								<tbody>												
									<input type="text" name="id"  id="valueTree_id" style="display: none;"/></td>
									<input type="text" name="action_tree" id="action_tree" style="display: none;"/>					
									<tr>
										<td class="td-name-width"> Название</td>				
										<td><input type="text" name="name" id="valueTree_name" /></td>
									</tr>													
								</tbody>
							</table>
						</form>
					</div>
					
				<?php if(@$access['characteristics_edit']==1): ?>	
					<div id="edittree" class="button edit-folder"></div>
				<?php else: ?>
					<div class="button edit-folder noactive"></div>
				<?php endif; ?>
				<?php if(@$access['characteristics_del']==1): ?>
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
			
				<ul id="tree_characteristics" class="filetree">
						<?php echo $trees; ?>
				</ul>
				
			</div>
				
			<div class="ui-state-default ui-jqgrid-pager corner-bottom" style="width: 100%;" dir="ltr"></div>
		</div>
	
	</div>
	
	<div id="right">
        <table id="TableCatalog"></table>
        <div id="TableCatalogPager"></div>
		
		<div id="characteristicsFormDel" class="del-form">
			<input type="hidden" id="del_id_characteristics"/>
			<p>Вы действительно хотите удалить группу характеристик?</p>
		</div>
		
		<div id="charFormDel" class="del-form">
			<input type="hidden" id="del_id_char"/>
			<p>Вы действительно хотите удалить характеристику?</p>
		</div>		
	
		<div id="characteristicsForm">
			
			<div id="tabs-content">

				<form method="post" name="characteristics_form" id="characteristics_form" action="">			

					<input type="text" name="id" id="val_id" style="display: none;"/>
					<input type="text" name="action_group" id="action_pole" style="display: none;" />
					<input type="text" name="id_tree" id="val_id_tree" style="display: none;"/>					
					<table class="table-tabs-content tip">
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>					
							<tr>
								<td> Название: </td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>					
							<tr>
								<td> Название: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>				
								<td><input type="text" name="name_lng" id="val_name_lng" /></td>
							</tr>	
							<tr>
								<td> Приоритет: </td>				
								<td><input type="text" name="prioritet"  id="val_prioritet" style="width: 50px;" /></td>
							</tr>
							<tr>
								<td> Отображать название в форме поиска: </td>				
								<td><input type="checkbox" name="name_vision"  id="val_name_vision" /></td>
							</tr>							
						</tbody>
					</table>
				</form>			
			</div>	
		</div>

		<div id="AddCharacteristicsDialog">													
			<div class="admin-tabs">			
				<ul>
					<li><a href="#tabs-content-1">Общее</a></li>
					<li><a href="#tabs-content-2">Данные</a></li>
				</ul>
					<form  method="post" name="catalog_form_сharacteristics" id="catalog_form_сharacteristics" action="">
						<div id="tabs-content-1">
							<input type="hidden" name="action_сharacteristics" id="action_сharacteristics"/>
							<input type="hidden" name="id" id="valueChar_id"/>
							<input type="hidden" name="id_lng" id="valueChar_id_lng"/>
								<table class="table-tabs-content char">	
									<tr>
										<td class="td-name-width"> Активен: </td>				
										<td><input type="checkbox" name="active"  id="valueChar_active" /></td>
									</tr>								
									<tr>
										<td> Группа:</td>	
										<td>
											<select name="id_groupe" id="valueChar_id_groupe">								
											</select>
										</td>
									</tr>								
									<tr>
										<td class="td-name-width"> Название: </td>	
										<td><input type="text" name="name" id="valueChar_name"/></td>
									</tr>									
									<tr>
										<td> Название: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>	
										<td><input type="text" name="name_lng" id="valueChar_name_lng"/></td>
									</tr>									
									<tr>
										<td> Префикс: </td>	
										<td><input type="text" name="prefix" id="valueChar_prefix"/></td>
									</tr>									
									<tr>
										<td> Префикс: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>	
										<td><input type="text" name="prefix_lng" id="valueChar_prefix_lng"/></td>
									</tr>
									<tr>
										<td> Суффикс: </td>	
										<td><input type="text" name="sufix" id="valueChar_sufix"/></td>
									</tr>									
									<tr>
										<td> Суффикс: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>	
										<td><input type="text" name="sufix_lng" id="valueChar_sufix_lng"/></td>
									</tr>	
									<tr>
										<td style="vertical-align: top;"> Описание:</td>	
										<td><textarea name="description" cols="20" rows="8" id="valueChar_description"></textarea></td>
									</tr>		
								</table>
						</div>
						<div id="tabs-content-2">
							<table class="table-tabs-content char">
								<tr>
									<td> Тип поиска:</td>	
									<td>
										<select name="tip_search" id="valueChar_tip_search">							
											<option value="1">Основные</option>
											<option value="2">Дополнительные</option>										
										</select>
									</td>
								</tr>							
								<tr>
									<td class="td-name-width"> Показывать в каталоге: </td>				
									<td><input type="checkbox" name="show_catalog"  id="valueChar_show_catalog" /></td>
								</tr>
								<tr>
									<td class="td-name-width"> Показывать одно поле: </td>				
									<td><input type="checkbox" name="valcharacter"  id="valueChar_valcharacter" /></td>
								</tr>
								<tr>
									<td> Фильтр:</td>	
									<td>
										<select name="filtr" id="valueChar_filtr">
											<option value="0">Нет</option>
											<option value="1">Левая колонка</option>
											<option value="2">Правая колонка</option>										
										</select>
									</td>
								</tr>	
								<tr>
									<td> Приоритет фильтра: </td>	
									<td><input type="text" name="prioritet_filtra" id="valueChar_prioritet_filtra" style="width: 100px;"/></td>
								</tr>								
								<tr>
									<td> Тип:</td>	
									<td>
										<select name="tip" id="valueChar_tip">							
											<option value="1">Текст</option>
											<option value="2">Переключатель (есть/нет)</option>
											<option value="3">Выбор одного значения</option>
											<option value="4">Выбор нескольких значений</option>										
										</select>
									</td>
								</tr>
								<tr>
									<td class="td-name-width"> Фильтр (toolbar): </td>				
									<td><input type="checkbox" name="filtr_toolbar"  id="valueChar_filtr_toolbar" /></td>
								</tr>
								<tr>
									<td> Приоритет: </td>	
									<td><input type="text" name="prioritet" id="valueChar_prioritet" style="width: 100px;"/></td>
								</tr>
								<tr>	
									<td colspan=2>
										Значения характеристики:
										<table id="TableCharacter"></table>				
										<div id="DelTipForm" class="del-form">
											<input type="hidden" id="del_id_tip"/>
												<p>Вы действительно хотите удалить поле?</p>
										</div>										
									</td>
								</tr>								
							</table>								
						</div>					
					</form>							
			</div>										
		</div>

		<div id="catalogFormTip">
			<form method="post" name="catalog_frm_tip" id="catalog_frm_tip" action="">
				<input type="hidden" name="id" id="valueTip_id"/>
				<input type="hidden" name="id_characteristics" id="valueTip_id_characteristics"/>
				<input type="hidden" name="action_tip" id="action_tip"/>
				<table class="table-tabs-content tip" id="TipTable">																	
					<tr>
						<td class="td-name-width"> <label>Название:</label></td>	
						<td><input type="text" name="name" id="valueTip_name" /></td>
					</tr>		
				</table>
			</form>
		</div>		
		
	</div>
	
</div>	