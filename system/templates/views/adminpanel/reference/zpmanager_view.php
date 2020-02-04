<script type="text/javascript" src="/js/admin/reference/zpmanager_table.js"></script>
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
								<td class="td-name-width"> Цена от:</td>				
								<td><input type="text" name="cena_ot"  id="val_cena_ot"  /></td>
							</tr>							
							<tr>
								<td> Цена до:</td>				
								<td><input type="text" name="cena_do" id="val_cena_do" /></td>
							</tr>							
							<tr>
								<td> ЗП:</td>				
								<td><input type="text" name="zp" id="val_zp" /></td>
							</tr>							
							<tr>
								<td> Дельта:</td>				
								<td><input type="text" name="r_delta" id="val_r_delta" /></td>
							</tr>							
							<tr>
								<td> ЗП %:</td>				
								<td><input type="text" name="zp_procent" id="val_zp_procent" /></td>
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
