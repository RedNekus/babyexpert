<script type="text/javascript" src="/js/admin/documents/kassa/kassa_table.js"></script>
<script type="text/javascript" src="/js/admin/documents/kassa/kassa_tree.js"></script>

<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 28px;" class="ui-userdata ui-state-default">
				
				<?php if(@$access['kassa_tree_add']==1): ?>
					<div id="addtree" class="button add-folder"></div>
				<?php else: ?>
					<div class="button add-folder noactive"></div>
				<?php endif; ?>
				
					<div id="AddTreeDialog">
						<form  method="post" name="form_tree" id="form_tree" action="">					
							<table class="table-tabs-content tip" style="margin-top: 10px;">
								<tbody>												
									<input type="hidden" name="id"  id="valueTree_id"/></td>
									<input type="hidden" name="action_tree" id="action_tree"/>										
									<tr>
										<td class="td-name-width"> Название</td>				
										<td><input type="text" name="name" id="valueTree_name" /></td>
									</tr>
									<tr>
										<td> Тип контрагента</td>				
										<td>
											<select name="id_kontragenty_tip" id="valueTree_id_kontragenty_tip">
												<option value="0">--нет--</option>
												<?php foreach($kontragenty_tip as $item): ?>
												<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
									<tr>
										<td> Контрагент</td>				
										<td>
											<select name="id_kontragenty" id="valueTree_id_kontragenty">
												<option value="0">--нет--</option>
												<?php foreach($kontragenty as $item): ?>
												<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>	
									<tr>
										<td class="td-name-width"> Разрешить минус: </td>				
										<td><input type="checkbox" name="minus"  id="valueTree_minus" /></td>
									</tr>	
									<tr>
										<td class="td-name-width"> Касса: </td>				
										<td>
											<select name="pid" id="valueTree_pid">
												<option value="0">--нет--</option>
												<?php foreach($kassa as $item): ?>
												<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
												<?php endforeach; ?>
											</select>										
										</td>
									</tr>									
								</tbody>
							</table>
						</form>
					</div>
					
					<?php if(@$access['kassa_tree_edit']==1): ?>	
						<div id="edittree" class="button edit-folder"></div>
					<?php else: ?>
						<div class="button edit-folder noactive"></div>
					<?php endif; ?>
					<?php if(@$access['kassa_tree_del']==1): ?>
						<div id="deltree" class="button del-folder"></div>
					<?php else: ?>
						<div class="button del-folder noactive"></div>
					<?php endif; ?>
				
					<div id="delrazdel" class="del-form">
						<input type="hidden" id="del_id_tree"/>
							<p>Вы действительно хотите удалить выбранное?</p>
					</div>

			</div>
			<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"></div>
			<div class="ui-jqgrid-bdiv">
			
				<ul id="tree_connection" class="filetree">
						<?php echo $trees; ?>
				</ul>
				
			</div>
				
			<div class="ui-state-default ui-jqgrid-pager corner-bottom" style="width: 100%;" dir="ltr"></div>
		</div>
	
	</div>
	
	<div id="right">
        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить выбранное?</p>
		</div>
		
		<div id="dialog-edit">
			
			<div id="tabs-content">

				<form method="post" name="form" id="form" action="">			

					<input type="text" name="id" id="val_id" style="display: none;"/>
					<input type="text" name="id_tree" id="val_id_tree" style="display: none;"/>
					<input type="text" name="action_group" id="action_pole" style="display: none;" />					
					<table class="table-tabs-content tip">
						<tbody>		
							<tr>
								<td class="ta-right"> Сумма USD: </td>				
								<td><input type="text" name="summa_usd" readonly id="val_con_summa_usd" class="total_usd"/></td>
							</tr>						
							<tr>	
								<td class="ta-right"> Сумма BYR: </td>				
								<td><input type="text" name="summa_blr" readonly id="val_con_summa_blr" class="total_blr"/></td>	
							</tr>						
							<tr>	
								<td class="ta-right"> Сумма EUR: </td>				
								<td><input type="text" name="summa_eur" readonly id="val_con_summa_eur" class="total_eur"/></td>
							</tr>						
							<tr>	
								<td class="ta-right"> Сумма RUR: </td>				
								<td><input type="text" name="summa_rur" readonly id="val_con_summa_rur" class="total_rur"/></td>
							</tr>						
							<tr>
								<td class="td-name-width"> Подтверждаю: </td>				
								<td><input type="checkbox" name="active"  id="val_active" /></td>
							</tr>	
							<?php if(@$access['id']==1): ?>	
							<tr>
								<td class="td-name-width"> Дата: </td>				
								<td><input type="text" name="date_create" class="admin-datepicker-format" id="val_date_create" /></td>
							</tr>		
							<?php endif; ?>							
							<tr>
								<td> Операция: </td>				
								<td>
									<select name="operation" id="val_operation" class="disabled">
										<option value="1">Приход</option>
										<option value="2">Расход</option>
										<?php if(@$access['id']==1): ?>
										<option value="3">Перевод расход</option>
										<option value="4">Перевод приход</option>
										<option value="5">Конверсия расход</option>
										<option value="6">Конверсия приход</option>
										<?php endif; ?>	
									</select>
								</td>
							</tr>	
							<tr>
								<td> Дилер:</td>	
								<td>
									<select name="id_diler" id="val_id_diler">
										<option value="0">-- Выберите --</option>
										<?php foreach ($dilers as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>							
							<tr>
								<td> Тип операции: </td>				
								<td>
									<select name="id_tip_operation" id="val_id_tip_operation" class="tip_operation disabled">
									</select>
								</td>
							</tr>								
							<tr>
								<td> USD: </td>				
								<td><input type="text" name="cena_usd" id="val_cena_usd" class="readonly"/></td>
							</tr>						
							<tr>
								<td> BYR: </td>				
								<td><input type="text" name="cena_blr" id="val_cena_blr" class="readonly"/></td>
							</tr>						
							<tr>
								<td> EUR: </td>				
								<td><input type="text" name="cena_eur" id="val_cena_eur" class="readonly"/></td>
							</tr>						
							<tr>
								<td> RUR: </td>				
								<td><input type="text" name="cena_rur" id="val_cena_rur" class="readonly"/></td>
							</tr>						
							<tr>
								<td> Примечание: </td>				
								<td><textarea name="comment" id="val_comment"></textarea></td>
							</tr>								
						</tbody>
					</table>
				</form>			
			</div>	
		</div>		
		
		<div id="dialog-move">
			
			<div id="tabs-content">

				<form method="post" name="form-move" id="form-move" action="">			

					<input type="text" name="id" id="val_m_id" style="display: none;"/>
					<input type="text" name="action_move" id="action_move" style="display: none;" />					
					<table class="table-tabs-content tip">
						<tbody>	
							<tr>
								<td class="ta-right"> Сумма USD: </td>				
								<td><input type="text" name="summa_usd" readonly id="val_m_summa_usd" class="total_usd"/></td>
							</tr>						
							<tr>	
								<td class="ta-right"> Сумма BYR: </td>				
								<td><input type="text" name="summa_blr" readonly id="val_m_summa_blr" class="total_blr"/></td>	
							</tr>						
							<tr>	
								<td class="ta-right"> Сумма EUR: </td>				
								<td><input type="text" name="summa_eur" readonly id="val_m_summa_eur" class="total_eur"/></td>
							</tr>						
							<tr>	
								<td class="ta-right"> Сумма RUR: </td>				
								<td><input type="text" name="summa_rur" readonly id="val_m_summa_rur" class="total_rur"/></td>
							</tr>	
							
							<tr>
								<td> Тип операции: </td>				
								<td>
									<select name="id_tip_operation" id="val_m_id_tip_operation" class="tip_operation">
									</select>
								</td>
							</tr>							
							<tr class="hide">
								<td class="td-name-width"> Откуда: </td>				
								<td>
									<select name="id_tree" id="val_m_id_tree" >
										<?php foreach($kassa as $item): ?>
										<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td> Тип контрагента</td>				
								<td>
									<select name="id_kontragenty_tip" id="val_m_id_kontragenty_tip">
										<option value="0">--нет--</option>
										<?php foreach($kontragenty_tip as $item): ?>
										<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>							
							<tr>
								<td> Куда: </td>				
								<td>
									<select name="id_tree_tmp" id="val_m_id_tree_tmp">
										<?php foreach($kassa as $item): ?>
										<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>						
							<tr>
								<td> USD: </td>				
								<td><input type="text" name="cena_usd" id="val_m_cena_usd" /></td>
							</tr>						
							<tr>
								<td> BYR: </td>				
								<td><input type="text" name="cena_blr" id="val_m_cena_blr" /></td>
							</tr>						
							<tr>
								<td> EUR: </td>				
								<td><input type="text" name="cena_eur" id="val_m_cena_eur" /></td>
							</tr>						
							<tr>
								<td> RUR: </td>				
								<td><input type="text" name="cena_rur" id="val_m_cena_rur" /></td>
							</tr>						
							<tr>
								<td> Примечание: </td>				
								<td><textarea name="comment" id="val_m_comment"></textarea></td>
							</tr>								
						</tbody>
					</table>
				</form>			
			</div>	
		</div>		
		
		<div id="dialog-conversion">
			
			<div id="tabs-content">

				<form method="post" name="form-conversion" id="form-conversion" action="">			

					<input type="text" name="id" id="val_con_id" style="display: none;"/>
					<input type="text" name="id_tree" id="val_con_id_tree" style="display: none;"/>
					<input type="text" name="action_conversion" id="action_conversion" style="display: none;" />					
					<table class="table-tabs-content tip">
						<tbody>	
							<tr class="pb-10">
								<td class="ta-right" style="width:40px;"> &Sigma; USD: </td>				
								<td><input type="text" name="summa_usd" readonly id="val_con_summa_usd" class="total_usd ib-90"/></td>							
								<td class="ta-right" style="width:40px;"> &Sigma; BYR: </td>				
								<td><input type="text" name="summa_blr" readonly id="val_con_summa_blr" class="total_blr ib-90"/></td>							
								<td class="ta-right" style="width:40px;"> &Sigma; EUR: </td>				
								<td><input type="text" name="summa_eur" readonly id="val_con_summa_eur" class="total_eur ib-90"/></td>							
								<td class="ta-right" style="width:40px;"> &Sigma; RUR: </td>				
								<td><input type="text" name="summa_rur" readonly id="val_con_summa_rur" class="total_rur ib-90"/></td>
							</tr>						
							<tr class="pb-10">
								<td class="ta-right" colspan=2>
									<select name="valute1" id="val_con_valute1" style="width:84px">
										<option value="1">BYR</option>
										<option value="2">USD</option>
										<option value="3">EUR</option>
										<option value="4">RUR</option>
									</select>
								</td>
								<td style="text-align:center;">на</td>		
								<td>
									<select name="valute2" id="val_con_valute2" style="width:84px">
										<option value="2">USD</option>
										<option value="3">EUR</option>
										<option value="1">BYR</option>										
										<option value="4">RUR</option>										
									</select>
								</td>
								<td class="ta-right">курс</td>
								<td colspan=3><input type="text" name="kurs" class="changeVal" style="width:76px" id="val_con_kurs" /></td>
							</tr>	
							<tr>
								<td>&nbsp;</td>
								<td><input type="text" name="cena_old" class="changeVal ib-90" id="val_con_cena_old" /></td>
								<td style="text-align:center;">=</td>
								<td><input type="text" name="cena_new" class="ib-90" readonly id="val_con_cena_new" /></td>
								<td colspan=4>&nbsp;</td>
							</tr>
						</tbody>
					</table>
				</form>			
			</div>	
		</div>	
		
		<div id="dialog-filtr">
			
			<div id="tabs-content">

				<form method="post" name="form-filtr" id="form-filtr" action="">			
					<input type="hidden" name="id_tree" id="val_filtr_id_tree" />
					<table class="table-tabs-content char">
						<tbody>	
							<tr class="v-top">				
								<td>	
									<?php foreach($kontragenty_tip as $item): ?>	
									<fieldset>
										<div><b class="cur-pointer" rel="t<?php echo $item['id']; ?>"><?php echo $item['name']; ?>:</b></div>
										<?php foreach(Database::getRows(get_table('kontragenty'),'id','asc',false,'id_tip = '.$item['id']) as $item): ?>
										<input type="checkbox" name="tip_kassa[]" class="tmp-t<?php echo $item['id']; ?>" value="<?php echo $item['id']; ?>" /><label class="norm-label" for=""><?php echo $item['name']; ?></label> <br/>
										<?php endforeach; ?>
									</fieldset>	
									<?php endforeach; ?>
								</td>
								<td>
									<fieldset>
										<div><b>Дата:</b></div>
										<input type="text" class="admin-datepicker-format ib-90" name="date_ot"/>
										<input type="text" class="admin-datepicker-format ib-90" name="date_do"/>
									</fieldset>										
									<fieldset>
										<div><b>Тип операции:</b></div>
										<?php foreach($tip_operation as $item): ?>
										<input type="checkbox" name="id_tip_operation[]" value="<?php echo $item['id']; ?>" /><label class="norm-label" for=""><?php echo $item['name']; ?></label> <br/>
										<?php endforeach; ?>
									</fieldset>
									<fieldset>
										<div><b>Операция:</b></div>
										<input type="checkbox" name="operation[]" value="1" /><label class="norm-label" for="">Приход</label> <br/>
										<input type="checkbox" name="operation[]" value="2" /><label class="norm-label" for="">Расход</label> <br/>
										<input type="checkbox" name="operation[]" value="3" /><label class="norm-label" for="">Перевод из кассы</label> <br/>
										<input type="checkbox" name="operation[]" value="4" /><label class="norm-label" for="">Перевод в кассу</label> <br/>
										<input type="checkbox" name="operation[]" value="5" /><label class="norm-label" for="">Конверсия из кассы</label> <br/>
										<input type="checkbox" name="operation[]" value="6" /><label class="norm-label" for="">Конверсия в кассу</label> <br/>
									</fieldset>
									<fieldset>
										<div><b>Валюта:</b></div>
										<input type="checkbox" name="valute[]" value="1" /><label class="norm-label" for="">BYR</label> <br/>
										<input type="checkbox" name="valute[]" value="2" /><label class="norm-label" for="">USD</label> <br/>
										<input type="checkbox" name="valute[]" value="3" /><label class="norm-label" for="">EUR</label> <br/>									
										<input type="checkbox" name="valute[]" value="4" /><label class="norm-label" for="">RUR</label> <br/>									
									</fieldset>										
								</td>								
								<td>
									<fieldset>
										<div><b>Авторы:</b></div>
										<?php foreach($adminusers as $item): ?>
										<input type="checkbox" name="adminusers[]" value="<?php echo $item['id']; ?>" /><label class="norm-label" for=""><?php echo $item['login']; ?></label> <br/>
										<?php endforeach; ?>
									</fieldset>	
									<fieldset>
										<div><b>Безнал:</b></div>
										<input type="checkbox" name="beznal" /><label class="norm-label" for="">Оплата по безналу</label> <br/>
									</fieldset>								
								</td>								
							</tr>							
						</tbody>
					</table>
				</form>			
			</div>	
		</div>
		
	</div>
	
</div>	