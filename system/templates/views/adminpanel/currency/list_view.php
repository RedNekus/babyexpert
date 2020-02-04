<script type="text/javascript" src="/js/admin/currency/grid_currency.js"></script>
<script type="text/javascript" src="/js/admin/currency/grid_tree.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="code"]');
  })
</script>
<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 24px;" class="ui-userdata ui-state-default">
				
				<?php if(@$access['currency_add']==1): ?>
					<div id="addtree" class="button add-folder"></div>
				<?php else: ?>
					<div class="button add-folder noactive"></div>
				<?php endif; ?>
				
					<div id="AddTreeDialog">							
						<div class="admin-tabs">
							<ul>
								<li><a href="#tabs-tree-catalog-1">Общее</a></li>
							</ul>
							<div id="tabs-tree-catalog-1">
								<form  method="post" name="catalog_form_tree" id="catalog_form_tree" action="">							
									<input type="hidden" name="action_tree" id="action_tree"/>
									<input type="hidden" name="id" id="valueTree_id"/>
									<input type="hidden" name="pid" id="valueTree_pid"/>	
									<table class="table-tabs-content">								
										<tr>
											<td class="td-name-width"> Активен: </td>				
											<td><input type="checkbox" name="active"  id="valueTree_active" /></td>
										</tr>
										<tr>
											<td> Базовая валюта:</td>				
											<td><input type="checkbox" name="baza"  id="valueTree_baza"  /></td>
										</tr>										
										<tr>
											<td> Краткое название:</td>	
											<td><input type="text" name="name" id="valueTree_name"/></td>
										</tr>
										<tr>
											<td> Полное название:</td>	
											<td><input type="text" name="fullname" id="valueTree_fullname"/></td>
										</tr>
										<tr>
											<td> Код:</td>	
											<td><input type="text" name="code" id="valueTree_code"/></td>
										</tr>
										<tr>
											<td> Символ:</td>	
											<td><input type="text" name="symbol" id="valueTree_symbol"/></td>
										</tr>	
										<tr>
											<td> Курс:</td>				
											<td><input type="text" name="kurs" id="valueTree_kurs" /></td>
										</tr>										
										<tr>
											<td> Приоритет:</td>	
											<td><input type="text" name="prioritet" id="valueTree_prioritet" style="width: 50px;"/></td>
										</tr>										
									</table>
								</form>	
							</div>	
						</div>
													
					</div>
					
				<?php if(@$access['currency_edit']==1): ?>	
					<div id="edittree" class="button edit-folder"></div>
				<?php else: ?>
					<div class="button edit-folder noactive"></div>
				<?php endif; ?>
				<?php if(@$access['currency_del']==1): ?>
					<div id="deltree" class="button del-folder"></div>
				<?php else: ?>
					<div class="button del-folder noactive"></div>
				<?php endif; ?>
				
				<div id="delrazdel" class="del-form">
					<input type="hidden" id="del_id_tree"/>
						<p>Вы действительно хотите удалить курс?</p>
				</div>

			</div>
			
			<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"></div>
			
			<div class="ui-jqgrid-bdiv">
			
				<ul id="tree_catalog" class="filetree">
					<?php echo @$trees; ?>
				</ul>
				
			</div>
				
			<div class="ui-state-default ui-jqgrid-pager corner-bottom" style="width: 100%;" dir="ltr"></div>
		
		</div>
	
	</div>	
	
	<div id="right">
        
		<table id="le_table"></table>
        <div id="le_tablePager"></div>

	</div>

</div>	
