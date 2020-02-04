<?php
	UI::addCSS(array(
		'/css/cart/zakaz_manager.css',
		'/js/jqueryui/css/cupertino/jquery-ui-1.10.0.custom.css',
		'/js/jqueryui/css/cupertino/jquery-ui-1.10.0.custom.css',
		'/js/jqueryui/css/ui.jqgrid.css',
		'/css/admin/admin.css',
		'/css/admin/content.css',
		)); 	
	UI::addJS(array(
		'/js/admin/functions.js',
		'/js/admin/admin.js',
		'/js/jqueryui/js/jquery-ui-1.10.0.custom.js',
		'/js/jqueryui/js/i18n/grid.locale-ru.js',
		'/js/jqueryui/js/jquery.jqgrid.min.js',
		'/js/jqueryui/src/grid.base.js',
		'/js/jqueryui/src/grid.common.js', 
		'/js/jqueryui/src/grid.formedit.js',
		'/js/jqueryui/src/grid.inlinedit.js',
		'/js/jqueryui/src/grid.celledit.js',
		'/js/jqueryui/src/grid.subgrid.js',
		'/js/jqueryui/src/grid.treegrid.js',
		'/js/jqueryui/src/grid.grouping.js',
		'/js/jqueryui/src/grid.custom.js',
		'/js/jqueryui/src/grid.postext.js',
		'/js/jqueryui/src/grid.tbltogrid.js', 
		'/js/jqueryui/src/grid.setcolumns.js',
		'/js/jqueryui/src/grid.import.js',
		'/js/jqueryui/src/jquery.fmatter.js',
		'/js/jqueryui/src/JsonXml.js',
		'/js/jqueryui/src/grid.jqueryui.js',
		'/js/jqueryui/src/jquery.searchFilter.js',
		'/js/zakaz/grid_table.js',		
		'/js/jqueryui/js/jquery.cookie.js',		
		)); 
?>

<div class="h-mb">
	<h1 class="b-page-title"><span>Оформление заказа менеджеры</span></h1>
	<div id="zakaz_product_table">
		<table id="TableTovar"></table>
		<div id="TableTovarPager"></div>
		
		<div id="zakaz_sum_skidka"></div>
		<div id="tovarForm">
			<form action="" id="form_tovar">
				<input type="hidden" name="id" id="value_id"/>
				<input type="hidden" name="action" id="action_tovar" />	
				<table class="table-tabs-content tip">	
					<tr>
						<td class="td-name-width">Код</td>
						<td><input type="text" name="id_item" class="ib-90" id="value_id_item" /></td>
						<td class="ta-right">Количество</td>
						<td><input type="text" name="kolvo" id="value_kolvo" /></td>						
					</tr>					
					<tr class="hide-tr">
						<td>Наименование</td>
						<td colspan=3><input type="text" name="name" id="value_name" /></td>
					</tr>								
					<tr class="hide-tr">
						<td>Цена</td>
						<td colspan=3><input type="text" name="cena" id="value_cena" /></td>
					</tr>								
					<tr>
						<td>Скидка $</td>
						<td><input type="text" name="skidka" class="ib-90" id="value_skidka" /></td>
						<td class="ta-right">Скидка %</td>
						<td><input type="text" name="skidka_procent" id="value_skidka_procent" /></td>
					</tr>								
					<tr>
						<td>Доплата $</td>
						<td><input type="text" name="doplata" class="ib-90" id="value_doplata" /></td>
						<td class="ta-right">Доставка $</td>
						<td><input type="text" name="dostavka" id="value_dostavka" /></td>
					</tr>	
					<tr>
						<td>Предзаказ:</td>				
						<td><input type="checkbox" name="predzakaz"  id="value_predzakaz"  /></td>							
						<td class="ta-right">Резерв:</td>				
						<td><input type="checkbox" name="rezerv" class="ib-90" id="value_rezerv"  /></td>					
					</tr>	
					<tr>
						<td> Поставщик:</td>				
						<td>
							<select name="id_postavshik" id="value_id_postavshik">
								<option value="0">нет</option>
								<?php 
								foreach($postavshik as $item) {
									echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
								}
								?>
							</select>						
						</td>
						<td class="ta-right"> Дата резерва:</td>				
						<td><input type="text" readonly name="date_rezerv" id="value_date_rezerv" class="admin-datepicker-format ib-90"/></td>														
					</tr>										
					<tr>
						<td> Дата предзаказа:</td>				
						<td><input type="text" readonly name="date_predzakaz" id="value_date_predzakaz" class="admin-datepicker-format ib-90"/></td>
						<td colspan=2>&nbsp;</td>
					</tr>	
					<tr>
						<td> &nbsp;</td>
						<td colspan=3><div id="table-skald-tovar"></div></td>
					</tr>
				</table>
			</form>
		</div>		
		
		<div id="delTovarForm" class="del-form">
			<input type="hidden" id="del_id_tovar"/>
			<p>Вы действительно хотите удалить товар?</p>
		</div>			
	</div>	
		<form id="form-checkout" action="/cart/send/" class="form registration" method="post">
		<?php 
		
			$last_id = Zakaz_client::getLastNomer(); 
			$date_now = date('Y-m-d');
			
		?>
		<input type="hidden" name="nomer" value="<?php echo ++$last_id; ?>">
		<p>Поля отмеченные <b>*</b> обязательны для заполнения</p>		
		<div class="position-left">
			<fieldset>
				<div class="line">
					<div class="l-col"><label for="firstname">Имя:</label></div>
					<div class="w-input"><input id="firstname" type="text" name="firstname" value="*" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
				</div>
				<div class="line">
					<div class="l-col"><label for="phone">Телефоны:</label></div>
					<div class="w-input"><input id="phone" type="text" value="*" name="phone[0][name]" class="required asterisk onlydigit width140"><i class="cl"></i><i class="cr"></i></div>
					<label for="phone1" class="phone1">&nbsp;</label>
					<div class="w-input"><input id="phone1" type="text" name="phone[1][name]" class="onlydigit width140"><i class="cl"></i><i class="cr"></i></div>
					<label for="phone2" class="phone2">&nbsp;</label>
					<div class="w-input"><input id="phone2" type="text" name="phone[2][name]" class="onlydigit width141"><i class="cl"></i><i class="cr"></i></div>			
			</div>					
				<div class="line">
					<div class="l-col"><label for="email">E-mail:</label></div>
					<div class="w-input"><input id="email" type="text" name="email"><i class="cl"></i><i class="cr"></i></div>
				</div>				
				<div class="line">
					<div class="l-col"><label for="code_zayavka">Код заявки:</label></div>
					<div class="w-input"><input id="code_zayavka" type="text" name="code_zayavka" value="" class="asterisk"><i class="cl"></i><i class="cr"></i></div>
				</div>
			</fieldset>
			<fieldset>
				<div class="line">
					<div class="l-col"><label for="city">Город:</label></div>
					<div class="w-input"><input id="city" class="width157" type="text" name="city"><i class="cl"></i><i class="cr"></i></div>
					<label for="poselok" class="poselok">Поселок:</label>
					<div class="w-input"><input id="poselok" class="width157" type="text" name="poselok"><i class="cl"></i><i class="cr"></i></div>
				</div>					
				<div class="line">
					<div class="l-col"><label for="street">Улица:</label></div>
					<div class="w-input"><input id="street" type="text" name="street" value="*" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
					<p class="note">если переулок, укажите это в графе</p>
				</div>
				<div class="line">
					<div class="l-col"><label for="house">Дом:</label></div>
					<div class="w-input"><input id="house" type="text" name="house" value="*" class="required asterisk onlydigit"><i class="cl"></i><i class="cr"></i></div>
					<label for="building" class="building">Корпус:</label>
					<div class="w-input"><input id="building" type="text" name="building"><i class="cl"></i><i class="cr"></i></div>
					<label for="apartment" class="apartment">Квартира:</label>
					<div class="w-input"><input id="apartment" type="text" name="apartment" value="" class="onlydigit"><i class="cl"></i><i class="cr"></i></div>
				</div>
				<div class="line">
					<div class="l-col"><label for="entrance">Подъезд:</label></div>
					<div class="w-input"><input id="entrance" type="text" name="entrance"><i class="cl"></i><i class="cr"></i></div>
					<label for="floor" class="floor">Этаж:</label><div class="w-input"><input id="floor" type="text" name="floor"><i class="cl"></i><i class="cr"></i></div>
				</div>
			</fieldset>
			<fieldset>
				<div class="line cmt">
					<div class="l-col"><label for="comment">Примечание клиента:</label></div>
					<div class="w-textarea"><textarea name="comment"></textarea><i class="ct"></i><i class="cb"></i><i class="cr"></i><i class="cl"></i></div>
				</div>			
				<div class="line cmt">
					<div class="l-col"><label for="comment_m">Примечание менеджера:</label></div>
					<div class="w-textarea"><textarea name="comment_m"></textarea><i class="ct"></i><i class="cb"></i><i class="cr"></i><i class="cl"></i></div>
				</div>
			</fieldset>
			<fieldset>
				<div class="line">
					<div class="w-checkbox"><input id="newsletter" type="checkbox" name="newsletter" style="opacity: 0;"></div>
					<label for="newsletter">Об условиях доставки крупногабаритного товара предупрежден</label>
				</div>			
				<button type="submit" class="btn-frmcheck"><span>Оформить</span></button>	
				<div class="line ib">
					<div class="w-checkbox"><input id="print_ready" type="checkbox" name="print_ready" style="opacity: 0;"></div>
					<label for="print_ready">Печать</label>
				</div>				
			</fieldset>
		</div>
		<div class="position-right">
			<fieldset> 
				<div class="line">
					<div class="w-checkbox"><input id="dostavka" type="checkbox" name="dostavka" style="opacity: 0;"></div>
					<label for="dostavka">Доставка</label>
				</div>	
				<div class="line">
					<div class="w-input"><input id="date_dostavka" type="text" name="date_dostavka" value="<?php echo $date_now; ?>" class="width157 admin-datepicker-format" placeholder="дата"><i class="cl"></i><i class="cr"></i></div>
				</div>			
			</fieldset>
			<fieldset> 
				<div class="line">
					<div class="w-checkbox"><input id="samovivoz_ofice" type="checkbox" name="samovivoz_ofice" style="opacity: 0;"></div>
					<label for="samovivoz_ofice">Самовывоз офис</label>
				</div>	
				<div class="line">
					<div class="w-checkbox"><input id="samovivoz" type="checkbox" name="samovivoz" style="opacity: 0;"></div>
					<label for="samovivoz">Самовывоз склад</label>
				</div>	
				<div class="line">
					<div class="w-radio"><input id="net" type="radio" checked name="sposob_dostavki" value="" style="opacity: 0;"></div>
					<label for="net">нет</label>
				</div>					
				<div class="line">
					<div class="w-radio"><input id="vozim" type="radio" name="sposob_dostavki" value="vozim" style="opacity: 0;"></div>
					<label for="vozim">Vozim.by</label>
				</div>	
				<div class="line">
					<div class="w-radio"><input id="rostik" type="radio" name="sposob_dostavki" value="rostik" style="opacity: 0;"></div>
					<label for="rostik">Ростик</label>
				</div>	
				<div class="line">
					<div class="w-radio"><input id="other" type="radio" name="sposob_dostavki" value="other" style="opacity: 0;"></div>
					<label for="other">Другой перевозчик</label>
				</div>				
			</fieldset>	
			<fieldset> 
				<div class="line">
					<div class="w-checkbox"><input id="dumayut" type="checkbox" name="dumayut" style="opacity: 0;"></div>
					<label for="dumayut">Думают</label>
				</div>					
			</fieldset>	
			<fieldset style="display:none;"> 		
				<div class="line">
					<div class="w-checkbox"><input id="otkaz" type="checkbox" name="otkaz" value="1" style="opacity: 0;"></div>
					<label for="otkaz">Отказ</label>
				</div>	
				<div class="line">
					<div class="w-checkbox"><input id="spros" type="checkbox" name="spros" value="1" style="opacity: 0;"></div>
					<label for="spros">Спрос</label>
				</div>				
			</fieldset>
		</div>		
	</form>
</div>				