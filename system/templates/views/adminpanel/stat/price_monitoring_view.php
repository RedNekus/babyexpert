<script type="text/javascript" src="/js/admin/stat/price_monitoring/table.js"></script>
<script type="text/javascript" src="/js/admin/stat/price_monitoring/tree.js"></script>

<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 28px;" class="ui-userdata ui-state-default"></div>
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

	</div>
	
</div>	