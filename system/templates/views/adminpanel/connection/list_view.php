<script type="text/javascript" src="/js/admin/connection/grid_connection.js"></script>
<script type="text/javascript" src="/js/admin/connection/grid_tree.js"></script>

<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 24px;" class="ui-userdata ui-state-default">
				
				<?php if(@$access['connection_add']==1): ?>
					<div id="addtree" class="button add-folder"></div>
				<?php else: ?>
					<div class="button add-folder noactive"></div>
				<?php endif; ?>
				
					<div id="AddTreeDialog">
						<form  method="post" name="connection_form_tree" id="connection_form_tree" action="">					
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
					
					<?php if(@$access['connection_edit']==1): ?>	
						<div id="edittree" class="button edit-folder"></div>
					<?php else: ?>
						<div class="button edit-folder noactive"></div>
					<?php endif; ?>
					<?php if(@$access['connection_del']==1): ?>
						<div id="deltree" class="button del-folder"></div>
					<?php else: ?>
						<div class="button del-folder noactive"></div>
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
        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить выбранное?</p>
		</div>
		
		<div id="dialog-edit">
			
			<div id="tabs-content">

				<form method="post" name="connection_form" id="connection_form" action="">			

					<input type="text" name="id" id="val_id" style="display: none;"/>
					<input type="text" name="action_group" id="action_pole" style="display: none;" />					
					<table class="table-tabs-content tip">
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>	
							<tr>
								<td> Портал: </td>
								<td>
									<select name="id_tree" id="val_id_tree">
										<option value="0">-- Выберите --</option>
										<?php foreach ($portals as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>								
							<tr>
								<td> Название портал: </td>				
								<td><input type="text" name="name_portal" id="val_name_portal" /></td>
							</tr>
							<tr>
								<td> Бренд: </td>
								<td>
									<select name="id_maker" id="val_id_maker">
										<option value="0">-- Выберите --</option>
										<?php foreach ($makers as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>							
							<tr>
								<td> Название сайт: </td>				
								<td><input type="text" name="name_site" id="val_name_site" /></td>
							</tr>								
						</tbody>
					</table>
				</form>			
			</div>	
		</div>

		<div id="catalogFormImport">
			<form method="post" name="import_form" id="import_form" action="/adminpanel/connection/edit" target="hiddenimportframe" enctype="multipart/form-data">
				<input type="hidden" name="import_action" id="import_action" value="add"/>
				<table class="table-tabs-content tip">
					<tbody>	
						<tr>
							<td class="td-name-width">&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td> Портал: </td>
							<td>
								<select name="id_tree" id="val_id_tree">
									<option value="0">-- Выберите --</option>
									<?php foreach ($portals as $item): ?>														
										<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>						
						<tr>
							<td> Обновить из фаила:</td>	
							<td><input type="file" name="secondXLS" size="100"/></td>
						</tr>						
						
						<tr>
							<td> &nbsp;</td>	
							<td> 
								<iframe id="hiddenimportframe" name="hiddenimportframe" style="width:0px; height:0px; border:0px"></iframe>	
							</td>
						</tr>						
					</tbody>
				</table>				
			</form>
		</div>
		
	</div>
	
</div>	