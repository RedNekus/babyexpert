<script type="text/javascript" src="/js/admin/reference/couriers/couriers_table.js"></script>
<script type="text/javascript" src="/js/admin/reference/couriers/couriers_tree.js"></script>
<script type="text/javascript" src="/js/admin/reference/couriers/couriers_zakaz_tovar.js"></script>

<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 28px;" class="ui-userdata ui-state-default">
				
				<?php if(@$access['couriers_add']==1): ?>
					<div id="addtree" class="button add-folder"></div>
				<?php else: ?>
					<div class="button add-folder noactive"></div>
				<?php endif; ?>
				
					<div id="AddTreeDialog">
						<form  method="post" name="couriers_form_tree" id="couriers_form_tree" action="">					
							<table class="table-tabs-content tip" style="margin-top: 10px;">
								<tbody>												
									<input type="text" name="id"  id="valueTree_id" style="display: none;"/></td>
									<input type="text" name="action_tree" id="action_tree" style="display: none;"/>	
									<tr>
										<td class="td-name-width"> Тип контрагента:</td>				
										<td><select name="id_tip" id="valueTree_id_tip">
												<?php foreach($couriers as $item): ?>
												<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="td-name-width"> Активен:</td>				
										<td><input type="checkbox" name="active"  id="valueTree_active"  /></td>
									</tr>									
									<tr>
										<td class="td-name-width"> Название</td>				
										<td><input type="text" name="name" id="valueTree_name" /></td>
									</tr>										
								</tbody>
							</table>
						</form>
					</div>
					
					<?php if(@$access['couriers_edit']==1): ?>	
						<div id="edittree" class="button edit-folder"></div>
					<?php else: ?>
						<div class="button edit-folder noactive"></div>
					<?php endif; ?>
					<?php if(@$access['couriers_del']==1): ?>
						<div id="deltree" class="button del-folder"></div>
					<?php else: ?>
						<div class="button del-folder noactive"></div>
					<?php endif; ?>
				
					<div id="delrazdel" class="del-form">
						<input type="hidden" id="del_id_couriers"/>
							<p>Вы действительно хотите удалить курьера?</p>
					</div>

			</div>
			<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"></div>
			<div class="ui-jqgrid-bdiv">
			
				<ul id="tree_couriers" class="filetree">
						<?php echo $trees; ?>
				</ul>
				
			</div>
				
			<div class="ui-state-default ui-jqgrid-pager corner-bottom" style="width: 100%;" dir="ltr"></div>
		</div>
	
	</div>
	
	<div id="right" >
        <table id="TableCatalog"></table>
        <div id="TableCatalogPager"></div>
		
		<div id="couriersForm">
			<form method="post" name="couriers_form" id="couriers_form" action="">			
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Заказ</a></li>				
					</ul>
					<div id="tabs-content-1">
						<table id="TableTovar"></table>
						<div id="TableTovarPager"></div>
						<input type="text" name="id" id="val_id" style="display: none;"/>
						<input type="text" name="action_group" id="action_pole" style="display: none;" />									
						<input type="text" name="id_client" id="val_id_client" style="display: none;" />									
						<input type="text" name="id_couriers" id="val_id_couriers" style="display: none;" />
						<input type="text" name="obrabotan" id="val_obrabotan"style="display: none;" />
						<input type="text" name="t_total" id="val_t_total" style="display: none;"/>
						<input type="text" name="t_total_blr" id="val_t_total_blr" style="display: none;"/>
						<div style="padding-top: 20px;"></div>						
						<table class="table-tabs-content zakaz">
							<tbody>					
								<tr>
									<td> Курс: </td>				
									<td colspan=3><input type="text" readonly name="kurs" id="val_kurs" /></td>								
								</tr>		
								<tr>
									<td> Итого USD: </td>				
									<td colspan=3><input type="text" name="total" id="val_total" /></td>								
								</tr>	
								<tr>
									<td> Итого BYR: </td>				
									<td colspan=3><input type="text" name="total_blr" id="val_total_blr" /></td>								
								</tr>							
								<tr>
									<td> ЗП $: </td>				
									<td><input type="text" name="zp" id="val_zp" /></td>		
									<td class="ta-right"> ЗП br: </td>				
									<td><input type="text" name="zp_blr" id="val_zp_blr" /></td>								
								</tr>																																																																																									
								<tr>
									<td rowspan=2> Примечание: </td>				
									<td rowspan=2><textarea type="text" name="comment" id="val_comment"></textarea></td>
									<td class="ta-right"> Доплата $: </td>				
									<td><input type="text" readonly name="doplata_usd" id="val_doplata_usd" /></td>																		
								</tr>	
								<tr>
									<td class="ta-right"> Доплата Br: </td>				
									<td><input type="text" readonly name="doplata_blr" id="val_doplata_blr" /></td>	
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</form>	
		</div>	
		
		<div id="print_dialog" class="">
			<div class="admin-tabs print_table">
				<label for="" class="ml_20">Дата от:</label> <input type="text" readonly id="print_date_ot" value=""/>
				<label for="" class="ml_20">Дата до:</label> <input type="text" readonly id="print_date_do" value=""/>
				<a href="" id="click-print" class="btn ml_20">Просмотр</a>
				<div id="print_table">
					<table class="table-tabs-content t-print" id="print_table_elem" border=1 cellspacing="0">
					</table>
				</div>
			</div>
		</div>		
		
	</div>
	
</div>	