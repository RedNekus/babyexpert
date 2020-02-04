<script type="text/javascript" src="/js/admin/price_monitoring/grid_table.js"></script>
<script type="text/javascript" src="/js/admin/price_monitoring/grid_tovar_table.js"></script>
<div>

	<div>
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить запись?</p>
		</div>
	
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Общие</a></li>
						<li><a href="#tabs-content-2">Подбор</a></li>			
					</ul>
					<div id="tabs-content-1">
						<table class="table-tabs-content char">
							<tbody>							
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
								</tr>
								<tr>
									<td> Наименование:</td>	
									<td>
										<select name="id_tovar" class="podbor val_id_tovar">
											<option value="0">-- Выберите --</option>
										</select>
									</td>						
								</tr>								
							</tbody>
						</table>						
					</div>
					<div id="tabs-content-2">		
						<input type="hidden" name="id" id="val_id"/>
						<input type="hidden" name="id_catalog" id="val_id_catalog"/>
						<input type="hidden" name="kurs" id="val_kurs"/>
						<input type="hidden" name="action" id="action_pole" />					
						<table class="table-tabs-content price-table">
							<tbody>	
								<tr>
									<td class="td-name-width"> Товар:</td>	
									<td>
										<input name="tovar_name" readonly id="val_tovar_name" class="ib-90"/>
									</td>
									<td><div id="link-product"></div></td>
								</tr>							
								<tr>
									<td> Цена $:</td>	
									<td>
										<input name="cena" id="val_cena" class="ib-90" />
									</td>	
									<td>&nbsp;</td>
								</tr>							
								<tr>
									<td> Цена Br:</td>	
									<td>
										<input name="cena_blr" id="val_cena_blr" style="width: 43.5%;"/>
										<input name="cena_usd_kurs" id="val_cena_usd_kurs" style="width: 44%;"/>
									</td>	
									<td>&nbsp;</td>
								</tr>						
								<tr>
									<td> Цена Br по курсу:</td>	
									<td>
										<input name="cena_blr_kurs" id="val_cena_blr_kurs" class="ib-90"/>
									</td>	
									<td><a href="#" id="logs-product">История</a></td>
								</tr>							
								<tr>
									<td> Цена закупочная:</td>	
									<td>
										<input name="cena_zakup" readonly id="val_cena_zakup" class="ib-90"/>
									</td>	
									<td>&nbsp;</td>
								</tr>							
								<tr>
									<td> Дельта:</td>	
									<td>
										<input name="delta" readonly id="val_delta" class="ib-90"/>
									</td>	
									<td>&nbsp;</td>
								</tr>														
							</tbody>
						</table>
					
						<table id="le_table_tovar"></table>
						<div id="le_table_tovarPager"></div>					
			
					</div>
				</div>
			</form>
		</div>
		
		<div id="dialog-tovar-add">
			<form method="post" name="form-tovar" id="form-tovar" action="">		
				<div id="tabs-content-1">
					<input type="hidden" name="id" id="value_id"/>
					<input type="hidden" name="id_tovar" id="value_id_tovar"/>
					<input type="hidden" name="action" id="value_action" />					
					<input type="hidden" name="id_price_monitoring" id="value_id_price_monitoring" />					
					<table class="table-tabs-content tip">
						<tbody>						
							<tr>
								<td class="td-name-width"> Конкурент:</td>	
								<td>
									<select name="id_competitors" id="value_id_competitors">
										<option value="0">-- Выберите --</option>
										<?php foreach ($competitors as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['site']; ?></option>
										<?php endforeach; ?>
									</select>
									<span id="link-concurent"></span>
								</td>						
							</tr>					
							<tr>
								<td> Ссылка:</td>	
								<td>
									<input type="text" name="link" id="value_link" />
								</td>						
							</tr>					
							<tr>
								<td> Цена $:</td>	
								<td>
									<input type="text" name="cena" id="value_cena" />
								</td>						
							</tr>				
							<tr>
								<td> Цена Br:</td>	
								<td>
									<input type="text" name="cena_blr" id="value_cena_blr" />
								</td>						
							</tr>			
						</tbody>
					</table>	
				</div>			
			</form>
		</div>
		
		<div id="dialog-logs">
			<div id="table-logs" class="admin-tabs"></div>
		</div>
		
        <table id="le_table"></table>
        <div id="le_tablePager"></div>
	
	</div>
	
</div>	
