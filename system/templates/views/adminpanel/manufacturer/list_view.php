<script type="text/javascript" src="/js/admin/manufacturer/grid_table.js"></script>
<div>

	<div>
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить производителя?</p>
		</div>
	
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Общее</a></li>				
					</ul>
	<!-- Вкладка общие !-->				
					<div id="tabs-content-1">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="action" id="action_pole" />					
					<table class="table-tabs-content">
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
								<td> Адрес:</td>				
								<td><input type="text" name="adres" id="val_adres" /></td>
							</tr>				
							<tr>
								<td> Контакты:</td>				
								<td><input type="text" name="contact" id="val_contact" /></td>
							</tr>														
						</tbody>
					</table>
					</div>	
				</div>	
			</form>
		</div>


        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	
