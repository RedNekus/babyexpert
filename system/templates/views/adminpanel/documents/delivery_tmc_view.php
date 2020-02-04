<script type="text/javascript" src="/js/admin/documents/delivery_tmc_table.js"></script>
<script type="text/javascript" src="/js/admin/documents/delivery_tmc_tovar_table.js"></script>
<div>

	<div id="dialog-del" class="del-form">
		<input type="hidden" id="del_id"/>
		<p>Вы действительно хотите удалить запись?</p>
	</div>

	<div id="dialog-edit">
		<div class="admin-tabs">
			<ul>
				<li><a href="#tabs-content-1">Общие</a></li>
				<li><a href="#tabs-content-2">Подбор</a></li>	
				<li><a href="#tabs-content-3">Предварительная накладная</a></li>				
				<li><a href="#tabs-content-4">Товар на складе</a></li>				
			</ul>			
			<div id="tabs-content-1">
				<form method="post" name="form" id="form" action="">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="action" id="action_pole" />					
					<input type="hidden" name="return_tmc" id="val_return_tmc" />					
					<table class="table-tabs-content">
						<tbody>												
							<tr>
								<td class="td-name-width"> Номер накладной:</td>				
								<td><input type="text" name="nomer_nakladnoy" id="val_nomer_nakladnoy" /></td>
							</tr>	
							<tr>
								<td> Дата:</td>				
								<td><input type="text" name="date_delivery" id="val_date_delivery" class="admin-datepicker-format" /></td>
							</tr>							
							<tr>
								<td> Кладовщик:</td>	
								<td>
									<select name="id_storekeepers" id="val_id_storekeepers">
										<option value="0">-- Выберите --</option>
										<?php foreach ($storekeepers as $item): ?>	
											<option value="<?php echo $item['id']; ?>" <?php if ($item['id']==19) echo 'selected'; ?>><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>							
							<tr>
								<td> Склад:</td>	
								<td>
									<select name="id_sklad" id="val_id_sklad">
										<?php foreach ($sklad as $item): ?>														
											<option value="<?php echo $item['id']; ?>" <?php if ($item['id']==1) echo 'selected'; ?>><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td> Поставщик:</td>	
								<td>
									<select name="id_suppliers" id="val_id_suppliers">
										<option value="0">-- Выберите --</option>
										<?php foreach ($suppliers as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>	
							<tr>
								<td> Безнал:</td>	
								<td>
									<input type="checkbox" name="beznal" id="val_beznal" />
								</td>
							</tr>		
							<tr <?php if($access['id']==1): ?>style="display:none;"<?php endif; ?>>
								<td> Перевозчик:</td>	
								<td>
									<select name="id_carriers" id="val_id_carriers">
										<option value="0">-- Выберите --</option>
										<?php foreach ($carriers as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr <?php if($access['id']==1): ?>style="display:none;"<?php endif; ?>>
								<td> Сумма перевозчику:</td>				
								<td><input type="text" name="sum_carriers" id="val_sum_carriers" /></td>
							</tr>	
							<tr>
								<td> Валюта:</td>	
								<td>
									<select name="id_valute" id="val_id_valute">
										<option value="0">-- Выберите --</option>
										<?php foreach ($valute as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td> Курс:</td>				
								<td>
									<input type="text" name="kurs" value="1" id="val_kurs" />
									<i style="font-size: 10px;">Внимание: дробную часть указывайте через точку. Например: 30.5</i>
								</td>
							</tr>							
						</tbody>
					</table>
				</form>
			</div>	
			<div id="tabs-content-2">	
				<form method="post" action="" id="form-sklad-tovar">
					<table class="table-tabs-content">
						<tr>
							<td class="td-name-width"> Раздел:</td>	
							<td>
								<select name="id_tree" class="podbor val_id_tree">
									<option value="0">-- Выберите --</option>
									<?php foreach ($trees as $item): ?>														
										<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td> Курс:</td>	
							<td>
								<input class="tmp_kurs" rel="0" type="text" />
							</td>							
						</tr>
						<tr>
							<td> Производитель:</td>	
							<td>
								<select name="id_maker" class="podbor val_id_maker">
									<option value="0">-- Выберите --</option>
									<?php foreach ($makers as $item): ?>														
										<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td colspan=2>&nbsp;</td>
						</tr>
						<tr>
							<td> Наименование:</td>	
							<td>
								<select name="id_tovar" class="podbor val_id_tovar">
									<option value="0">-- Выберите --</option>
								</select>
							</td>
							<td colspan=2>&nbsp;</td>							
						</tr>			
					</table>
				</form>
				<table id="le_table_tovar"></table>
				<div id="le_table_tovarPager"></div>			
				<br/>
				<table id="le_table_sklad_tovar"></table>
				<div id="le_table_sklad_tovarPager"></div>					
			</div>
			<div id="tabs-content-3">
				<form method="post" action="">
					<table class="table-tabs-content">
						<tr>	
							<td><div class="prefix-app select-div"></div><div class="maker-app select-div"></div></td>	
							<td class="ta-right"> Курс:</td>	
							<td><input class="tmp_kurs" rel="1" type="text" /></td>							
						</tr>			
					</table>
				</form>	
				<table id="le_table_sklad_tovar2"></table>
				<div id="le_table_sklad_tovar2Pager"></div>				
			</div>
			<div id="tabs-content-4">
				<form method="post" action="" id="lol">
					<table class="table-tabs-content">
						<tr>
							<td><div class="prefix-app select-div"></div><div class="maker-app select-div"></div></td>					
							<td class="ta-right"> Курс:</td>	
							<td><input class="tmp_kurs" rel="2" type="text" /></td>								
						</tr>		
					</table>
				</form>		
				<table id="le_table_sklad_tovar3"></table>
				<div id="le_table_sklad_tovar3Pager"></div>				
			</div>
		</div>
		
	</div>

	<table id="le_table"></table>
	<div id="le_tablePager"></div>
	
	<div id="dialog-sklad-add">
		<form action="" id="form-tovar-add">
			<input type="hidden" name="id_item" id="vals_id_item" />
			<input type="hidden" name="id_delivery_tmc" id="vals_id_delivery_tmc" />
			<input type="hidden" name="action" id="vals_action">
			<table class="table-tabs-content tip">
				<tr>
					<td> Количество:</td>	
					<td><input type="text" name="kolvo_hold" id="vals_kolvo_hold" /></td>
				</tr>				
				<tr>
					<td> Цена:</td>	
					<td><input type="text" name="cena" id="vals_cena" /></td>
				</tr>
				<tr>
					<td> Склад:</td>	
					<td>
						<select name="id_sklad" id="vals_id_sklad">
							<?php foreach ($sklad as $item): ?>														
								<option value="<?php echo $item['id']; ?>" <?php if ($item['id']==1) echo 'selected'; ?>><?php echo $item['name']; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>				
				<tr>
					<td> Примечание:</td>	
					<td><textarea name="note" id="vals_note"></textarea></td>
				</tr>			
			</table>
		</form>
	</div>
	
		
	<div id="dialog-oplata">
		<form action="" id="form-oplata">
			<input type="hidden" name="id" id="valo_id" />
			<input type="hidden" name="id_valute" id="valo_id_valute" />
			<input type="hidden" name="id_suppliers" id="valo_id_suppliers" />
			<table class="table-tabs-content tip">
				<tr>
					<td><input type="checkbox" name="with_kassa" id="valo_with_kassa" /></td>	
					<td colspan=3>Оплачено через кассу</td>
				</tr>				
				<tr>
					<td>Поставщик</td>	
					<td colspan=3><input type="text" readonly name="suppliers" id="valo_suppliers" /></td>
				</tr>			
				<tr>
					<td class="valo_valute"> </td>	
					<td><input type="text" readonly name="sum" id="valo_sum" /></td>
					<td class="ta-right"> Курс</td>	
					<td><input type="text" readonly name="kurs" id="valo_kurs" /></td>
				</tr>				
				<tr>
					<td> USD</td>	
					<td><input type="text" readonly name="sum_usd" id="valo_sum_usd" /></td>
				</tr>
				
			</table>
		</form>
	</div>
	
	

</div>	
