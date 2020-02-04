<script type="text/javascript" src="/js/admin/fuel/fuel_table.js"></script>
<script type="text/javascript" src="/js/admin/fuel/fuel_tree.js"></script>
<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 28px;" class="ui-userdata ui-state-default">
				
				<?php if(@$access['fuel_add']==1): ?>
					<div id="addtree" class="button add-folder"></div>
				<?php else: ?>
					<div class="button add-folder noactive"></div>
				<?php endif; ?>
				
					<div id="AddTreeDialog">
						<form  method="post" name="form_tree" id="form_tree" action="">					
							<table class="table-tabs-content tip" style="margin-top: 10px;">
								<tbody>												
									<input type="text" name="id"  id="valueTree_id" style="display: none;"/></td>
									<input type="text" name="action_tree" id="action_tree" style="display: none;"/>					
									<tr>
										<td class="td-name-width"> Название: </td>				
										<td><input type="text" name="name" id="valueTree_name" /></td>
									</tr>
									<tr>
										<td class="td-name-width"> Расход (л) на 100 км: </td>				
										<td><input type="text" name="rashod" id="valueTree_rashod" /></td>
									</tr>													
								</tbody>
							</table>
						</form>
					</div>
					
					<?php if(@$access['fuel_edit']==1): ?>	
						<div id="edittree" class="button edit-folder"></div>
					<?php else: ?>
						<div class="button edit-folder noactive"></div>
					<?php endif; ?>
					<?php if(@$access['fuel_del']==1): ?>
						<div id="deltree" class="button del-folder"></div>
					<?php else: ?>
						<div class="button del-folder noactive"></div>
					<?php endif; ?>
					
					<?php if ($access['id']==1): ?>
					<div class="toolbar_top">
						<div class="toolbar-block" >
							<select name="id_courier" class="my-select" id="valueTree_id_courier">
								<option value="0">Все</option>
								<?php foreach($couriers as $item): ?>
								<option value="<?php echo $item['id']; ?>" <?php if ($access['id_courier']==$item['id']) echo 'selected'; ?>><?php echo $item['name']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<?php endif; ?>
					
					<div id="delrazdel" class="del-form">
						<input type="hidden" id="del_id_tree"/>
							<p>Вы действительно хотите удалить портал?</p>
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
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить запись?</p>
		</div>
	
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="">		
				<div id="tabs-content-1">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="action" id="action_pole" />	
					<input type="hidden" name="id_tree" id="val_id_tree" />					
					<table class="table-tabs-content tip">
						<tbody>												
							<tr <?php if ($access['id']!=1) echo 'style="display:none;"'; ?>>
								<td> Курьер:</td>				
								<td>						
									<select name="id_courier" id="val_id_courier">
										<option value="0">Все</option>
										<?php foreach($couriers as $item): ?>
										<option value="<?php echo $item['id']; ?>" <?php if ($access['id_courier']==$item['id']) echo 'selected'; ?>><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>												
							<tr>
								<td> Цена за литр:</td>				
								<td><input type="text" name="cena" id="val_cena" /></td>
							</tr>						
							<tr>
								<td> Дата:</td>				
								<td><input type="text" name="date_create" id="val_date_create" class="admin-datepicker-format"  /></td>
							</tr>																		
							<tr>
								<td> Количество (км):</td>				
								<td><input type="text" name="km" id="val_km" /></td>
							</tr>																		
							<tr>
								<td> Примечание:</td>				
								<td><textarea name="comment" id="val_comment"></textarea></td>
							</tr>											
						</tbody>
					</table>
				</div>			
			</form>
		</div>

        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	
