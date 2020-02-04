<script type="text/javascript" src="/js/admin/catalog_sklad/grid_table.js"></script>

<div>
	<div id="left" style="height: 20%; width: 100%;">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	

			<div class="ui-jqgrid-bdiv">
				<form method="post" action="" id="form-sklad-tovar">
					<table class="table-tabs-content sklad">
						<tr>
							<td class="ta-right"> Раздел:</td>	
							<td>
								<select name="id_tree" class="podbor val_id_tree">
									<option value="0">-- Выберите --</option>
									<?php foreach ($trees as $item): ?>														
										<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>							
							<td class="ta-right"> Производитель:</td>	
							<td>
								<select name="id_maker" class="podbor val_id_maker">
									<option value="0">-- Выберите --</option>
								</select>
							</td>
							<td class="ta-right"> Наименование:</td>	
							<td>
								<select name="id_tovar" class="podbor val_id_tovar">
									<option value="0">-- Выберите --</option>
								</select>
							</td>							
						</tr>			
					</table>
				</form> 
				
			</div>

		</div>
	
	</div>
				
	<div id="right" style="height: 80%;width: 100%;">
        <table id="le_table"></table>
        <div id="le_tablePager"></div>
	</div>
	
	<div id="dialog-print" class="">
		<div class="admin-tabs print_table">
			<table id="table-print">
			</table>
		</div>
	</div>
			
	<div id="dialog-print-ost" class="">
		<div class="admin-tabs print_table">
			<table id="table-print-ost" class="table-ost">
			</table>
		</div>
	</div>
					
	<div id="dialog-get-postavki" class="">
		<div class="admin-tabs print_table">
			<table id="table-print-postavki" class="table-ost">
			</table>
		</div>
	</div>
		
</div>	