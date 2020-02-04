<script type="text/javascript" src="/js/admin/spros/grid_table.js"></script>
<div>

	<div>
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить элемент?</p>
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
								<td class="td-name-width"> Завершен:</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>					
							<tr>
								<td> Телефон:</td>				
								<td><input type="text" name="phone1" id="val_phone1" /></td>
							</tr>				
							<tr>
								<td> Телефон:</td>				
								<td><input type="text" name="phone2" id="val_phone2" /></td>
							</tr>				
							<tr>
								<td> Имя:</td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>				
							<tr>
								<td> Часов:</td>				
								<td><input type="text" name="chas" id="val_chas" /></td>
							</tr>				
							<tr>
								<td> Минут:</td>				
								<td><input type="text" name="min" id="val_min" /></td>
							</tr>				
							<tr>
								<td> Примечание:</td>				
								<td><textarea name="comment" id="val_comment"></textarea></td>
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
