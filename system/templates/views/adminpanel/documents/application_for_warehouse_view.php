<script type="text/javascript" src="/js/admin/documents/application_for_warehouse/application_for_warehouse_table.js"></script>
<script type="text/javascript" src="/js/admin/documents/application_for_warehouse/application_for_warehouse_zakaz_tovar.js"></script>

<div>
	<div id="print_dialog" class="">
		<div class="admin-tabs print_table">
			<label for="">Дата доставки:</label> <input type="text" readonly id="date_zakaz" value="<?php echo date('Y-m-d'); ?>"/>
			<label for="">Курьер:</label> 
			<?php get_select_courier(); ?>
			
			<div id="print_table">
				<table class="table-tabs-content t-print" id="print_table_elem" border=1 cellspacing="0">
				</table>
			</div>
		</div>
	</div>
		
	<div>
        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="">			
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Заказ</a></li>				
					</ul>
					<div id="tabs-content-1">
						<table class="table-tabs-content zakaz">
							<tr>
								<td class="td-name-width"> 
									<input type="text" readonly name="nomer_zakaza" style="display: inline-block;background: none; border:none; color: green; font-size: 16px;" class="ib-90" id="val_nomer_zakaza" />
								</td>
								<td>&nbsp;</td>
							</tr>							
						</table>					
						<table id="le_tableTovar"></table>
						<div id="le_tableTovarPager"></div>
						<input type="hidden" name="id" id="val_id" />
						<input type="hidden" name="action" id="action" />																	
						<input type="hidden" name="id_couriers" id="val_id_couriers" />
						<div style="padding-top: 20px;"></div>
						<table class="table-tabs-content zakaz">
							<tr class="pb-10">	
								<td class="ta-right"> &sum; $:</td>				
								<td><input type="text" name="sum_usd" readonly class="ib-90" id="val_sum_usd" /></td>									
								<td class="ta-right"> &sum; б.р:</td>				
								<td><input type="text" name="sum_blr" readonly id="val_sum_blr" /></td>
							</tr>				
							<tr class="pb-10">	
								<td class="ta-right"> Доплата $:</td>				
								<td><input type="text" name="doplata_usd" class="ib-90" id="val_doplata_usd" /></td>									
								<td class="ta-right"> Доплата Br:</td>				
								<td><input type="text" name="doplata_blr" id="val_doplata_blr" /></td>
							</tr>
							<tr>
								<td class="ta-right">Безнал:</td>				
								<td><input type="checkbox" name="beznal"  id="val_beznal"  /></td>	
								<td colspan=2></td>
							</tr>
						</table>						
						<table class="table-tabs-content zakaz">
							<tbody>	
								<tr>
									<td>
										<label class="opisanie-label">Примечание клиента:</label>
										<textarea name="comment" class="he-60" readonly cols="25" rows="8" id="val_comment"></textarea>
									</td>
									<td>
										<label class="opisanie-label">Примечание менеджера:</label>
										<textarea name="comment_m" cols="25" readonly class="he-60" rows="8" id="val_comment_m"></textarea>
									</td>
								</tr>							
								<tr>
									<td>
										<label class="opisanie-label">Примечание курьера:</label>
										<textarea name="comment_c" class="he-60" readonly cols="25" rows="8" id="val_comment_c"></textarea>
									</td>
									<td>
										<label class="opisanie-label">Примечание кладовщика:</label>
										<textarea name="comment_w" cols="25"  class="he-60" rows="8" id="val_comment_w"></textarea>
									</td>
								</tr>
							</tbody>
						</table>
						<table>
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
								<td colspan=3>&nbsp;</td>			
							<tr>						
							<tr>
								<td class="ta-right"> Город:</td>				
								<td><input type="text" name="city" id="val_city" /></td>
								<td class="ta-right"> Поселок:</td>				
								<td><input type="text" name="poselok" id="val_poselok" /></td>
								<td class="ta-right">Телефоны:</td>				
								<td><input type="text" name="phone" id="val_phone" /></td>
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
						</table>
					</div>
				</div>
			</form>	
		</div>	
		
	</div>
	
</div>	