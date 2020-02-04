<script type="text/javascript" src="/js/admin/reference/competitors_table.js"></script>
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
					<table class="table-tabs-content tip">
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен:</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>							
							<tr>
								<td> Название:</td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>	
							<tr>
								<td> УНП:</td>				
								<td><input type="text" name="unp" id="val_unp" /></td>
							</tr>							
							<tr>
								<td> Телефон:</td>				
								<td><input type="text" name="phone" id="val_phone" /></td>
							</tr>							
							<tr>
								<td> Телефон:</td>				
								<td><input type="text" name="phone2" id="val_phone2" /></td>
							</tr>							
							<tr>
								<td> Телефон:</td>				
								<td><input type="text" name="phone3" id="val_phone3" /></td>
							</tr>							
							<tr>
								<td> Сайт:</td>				
								<td><input type="text" name="site" id="val_site" /></td>
							</tr>								
							<tr>
								<td> Сайт:</td>				
								<td><input type="text" name="site2" id="val_site2" /></td>
							</tr>								
							<tr>
								<td> Сайт:</td>				
								<td><input type="text" name="site3" id="val_site3" /></td>
							</tr>							
							<tr>
								<td> Описание:</td>				
								<td><textarea name="description" id="val_description"></textarea></td>
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
