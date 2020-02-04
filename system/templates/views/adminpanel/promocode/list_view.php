<script type="text/javascript" src="/js/admin/promocode/grid_promocode.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="path"]');
  })
</script>
<div>

	<div>
	
		<div id="deldialog" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить элемент?</p>
		</div>
	
		<div id="catalogForm">
			<form method="post" name="catalog_form" id="catalog_form" action="">
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
								<td class="td-name-width"> Активен (отзывы):</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>												
							<tr>
								<td class="td-name-width"> Активен (розыгрыщ):</td>				
								<td><input type="checkbox" name="active_raffle"  id="val_active_raffle"  /></td>
							</tr>					
							<tr>
								<td> Промокод:</td>				
								<td><input type="text" readonly name="name" id="val_name" /></td>
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
