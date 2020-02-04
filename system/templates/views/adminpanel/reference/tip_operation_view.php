<script type="text/javascript" src="/js/admin/reference/tip_operation_table.js"></script>
<div>

	<div>
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить запись?</p>
		</div>
	
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="">		
				<div id="tabs-content-1">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="action" id="action_pole" />	
					<input type="hidden" name="date_create" id="val_date_create" value="<?php echo date('Y-m-d'); ?>"/>					
					<table class="table-tabs-content tip">
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен:</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>							
							<tr>
								<td> Краткое название:</td>				
								<td><input type="text" name="name" id="val_name" /></td>
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
