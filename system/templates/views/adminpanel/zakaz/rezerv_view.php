<script type="text/javascript" src="/js/admin/zakaz/grid_rezerv.js"></script>
<script type="text/javascript" src="/js/admin/zakaz/grid_zakaz_tovar.js"></script>
<div>

	<div>
		
		<div id="deldialog" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить заказ?</p>
		</div>
	
		<div id="catalogForm">
			<form method="post" name="catalog_form" id="catalog_form" action="">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Заказ</a></li>				
					</ul>
	<!-- Вкладка общие !-->				
					<div id="tabs-content-1">
						<table id="TableTovar"></table>
						<div id="TableTovarPager"></div>
						<div style="padding-top: 20px;">
							<table class="table-tabs-content zakaz">
								<tr>
									<td>
										<label class="opisanie-label">Примечание клиента:</label>
										<textarea name="comment" class="he-60" cols="25" rows="8" id="val_comment"></textarea>
									</td>
									<td>
										<label class="opisanie-label">Примечание менеджера:</label>
										<textarea name="comment_m" cols="25" class="he-60" rows="8" id="val_comment_m"></textarea>
									</td>
								</tr>
							</table>
						</div>
						<input type="hidden" name="id" id="val_id"/>
						<input type="hidden" name="nomer_zakaza" id="val_nomer_zakaza"/>
						<input type="hidden" name="action" id="action_pole" />	
						<input type="hidden" name="active"  id="val_active"  />											
						<input type="hidden" name="id_adminuser"  id="val_id_adminuser"  />						
						<input type="hidden" name="id_couriers"  id="val_id_couriers"  />						
						<table class="table-tabs-content zakaz">
							<tbody>	
								<tr>
									<td class="ta-right"> Время заказа:</td>				
									<td><input type="text" readonly name="time_zakaz" id="val_time_zakaz" /></td>									
									<td class="ta-right"> Время обработки:</td>				
									<td><input type="text" readonly name="time_active" id="val_time_active" /></td>									
									<td class="ta-right">Доставка:</td>				
									<td><input type="checkbox" name="dostavka"  id="val_dostavka"  /></td>
								</tr>								
								<tr class="pb-10">
									<td class="ta-right"> Дата заказа:</td>				
									<td><input type="text" readonly name="date_zakaz" id="val_date_zakaz" /></td>									
									<td class="ta-right"> Дата обработки:</td>				
									<td><input type="text" readonly name="date_active" id="val_date_active" /></td>		
									<td class="ta-right"> Дата доставки:</td>				
									<td colspan=3><input type="text" name="date_dostavka" id="val_date_dostavka" class="admin-datepicker-format"/></td>									
								</tr>																													
								<tr>
									<td class="ta-right"> Имя:</td>				
									<td colspan=5><input type="text" name="firstname" id="val_firstname" /></td>
								</tr>	
								<tr>
									<td class="ta-right"> Телефоны:</td>				
									<td colspan=5><input type="text" name="phone" id="val_phone" /></td>
								</tr>
								<tr>
									<td class="ta-right"> Емайл:</td>				
									<td colspan=5><input type="text" name="email" id="val_email" /></td>
								</tr>															
								<tr>
									<td class="ta-right"> Город:</td>				
									<td><input type="text" name="city" id="val_city" /></td>
									<td class="ta-right"> Поселок:</td>				
									<td><input type="text" name="poselok" id="val_poselok" /></td>
									<td>&nbsp;</td>				
									<td><input type="hidden" /></td>
								</tr>															
								<tr>	
									<td class="ta-right"> Улица:</td>				
									<td><input type="text" name="street" id="val_street" /></td>
									<td class="ta-right"> Дом:</td>				
									<td><input type="text" name="house" id="val_house" /></td>
									<td class="ta-right"> Корпус:</td>				
									<td><input type="text" name="building" id="val_building" /></td>
								</tr>															
								<tr>	
									<td class="ta-right"> Квартира:</td>				
									<td><input type="text" name="apartment" id="val_apartment" /></td>
									<td class="ta-right"> Этаж:</td>				
									<td><input type="text" name="floor" id="val_floor" /></td>
									<td class="ta-right"> Подъезд:</td>				
									<td><input type="text" name="entrance" id="val_entrance" /></td>
								</tr>	
								<tr>
									<td class="ta-right"> Способ доставки:</td>				
									<td>
										<select name="sposob_dostavki" id="val_sposob_dostavki">
											<option value="">нет</option>
											<option value="vozim">Возим бай</option>
											<option value="dodoma">Додома</option>
											<option value="other">Другой</option>
										</select>									
									</td>	
									<td>&nbsp;</td>				
									<td><input type="hidden" /></td>									
									<td class="td-name-width">Самовывоз (склад):</td>				
									<td><input type="checkbox" name="samovivoz"  id="val_samovivoz"  /></td>
								<tr>						
								<tr>
									<td class="ta-right"> Код заявки:</td>				
									<td><input type="text" name="code_zayavka" id="val_code_zayavka" /></td>								
									<td>&nbsp;</td>				
									<td><input type="hidden" /></td>									
									<td class="td-name-width">Самовывоз (офис):</td>				
									<td><input type="checkbox" name="samovivoz_ofice"  id="val_samovivoz_ofice"  /></td>
								</tr>							
								<tr>	
									<td class="ta-right">Думают:</td>				
									<td><input type="checkbox" name="dumayut"  id="val_dumayut"  /></td>								
									<td colspan=2>&nbsp;</td>
									<td>В печать:</td>				
									<td><input type="checkbox" name="print_ready"  id="val_print_ready"  /></td>		
								</tr>								
							</tbody>
						</table>						
					</div>		
				</div>	
			</form>
		</div>


        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
		<!-- Форма товара -->
		<div id="tovarForm">
			<form action="" id="form_tovar">
				<input type="hidden" name="id" id="value_id"/>
				<input type="hidden" name="id_client" id="value_id_client"/>
				<input type="hidden" name="nomer_zakaza" id="value_nomer_zakaza"/>
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
	
</div>	
