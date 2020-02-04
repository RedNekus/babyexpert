<script type="text/javascript" src="/js/admin/reference/managers/managers_table.js"></script>
<script type="text/javascript" src="/js/admin/reference/managers/managers_tree.js"></script>
<script type="text/javascript" src="/js/admin/reference/managers/managers_zakaz_tovar.js"></script>

<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			
			<div style="height: 28px;" class="ui-userdata ui-state-default">
				
				<?php if(@$access['managers_add']==1): ?>
					<div id="addtree" class="button add-folder"></div>
				<?php else: ?>
					<div class="button add-folder noactive"></div>
				<?php endif; ?>
				
					<div id="AddTreeDialog">
						<form  method="post" name="managers_form_tree" id="managers_form_tree" action="">					
							<table class="table-tabs-content tip" style="margin-top: 10px;">
								<tbody>												
									<input type="text" name="id"  id="valueTree_id" style="display: none;"/></td>
									<input type="text" name="action_tree" id="action_tree" style="display: none;"/>	
									<tr>
										<td class="td-name-width"> Активен:</td>				
										<td><input type="checkbox" name="active"  id="valueTree_active"  /></td>
									</tr>									
									<tr>
										<td class="td-name-width"> Название</td>				
										<td><input type="text" name="name" id="valueTree_name" /></td>
									</tr>										
								</tbody>
							</table>
						</form>
					</div>
					
					<?php if(@$access['managers_edit']==1): ?>	
						<div id="edittree" class="button edit-folder"></div>
					<?php else: ?>
						<div class="button edit-folder noactive"></div>
					<?php endif; ?>
					<?php if(@$access['managers_del']==1): ?>
						<div id="deltree" class="button del-folder"></div>
					<?php else: ?>
						<div class="button del-folder noactive"></div>
					<?php endif; ?>
				
					<div id="delrazdel" class="del-form">
						<input type="hidden" id="del_id_managers"/>
							<p>Вы действительно хотите удалить выбранный элемент?</p>
					</div>

			</div>
			<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"></div>
			<div class="ui-jqgrid-bdiv">
			
				<ul id="tree_managers" class="filetree">
						<?php echo $trees; ?>
				</ul>
				
			</div>
				
			<div class="ui-state-default ui-jqgrid-pager corner-bottom" style="width: 100%;" dir="ltr"></div>
		</div>
	
	</div>
	
	<div id="right" >
        <table id="TableCatalog"></table>
        <div id="TableCatalogPager"></div>
		
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Заказ</a></li>				
					</ul>
	<!-- Вкладка общие !-->				
					<div id="tabs-content-1">
							<table class="table-tabs-content zakaz">
								<tr>
									<td class="td-name-width"> 
										<input type="text" readonly name="nomer_zakaza" style="display: inline-block;background: none; border:none; color: green; font-size: 16px;" class="ib-90" id="val_nomer_zakaza" />
									</td>
									<td>&nbsp;</td>
								</tr>							
							</table>
						<table id="TableTovar"></table>
						<div id="TableTovarPager"></div>
						<div style="padding-top: 20px;">						
							<table class="table-tabs-content zakaz">
								<tr>
									<td>
										<label class="opisanie-label">Примечание клиента:</label>
										<textarea name="comment" class="he-60" readonly cols="25" rows="8" id="val_comment"></textarea>
									</td>
									<td>
										<label class="opisanie-label">Примечание менеджера:</label>
										<textarea name="comment_m" cols="25" readonly class="he-60" rows="8" id="val_comment_m"></textarea>
									</td>
								</tr>
								<tr>
									<td>
										<label class="opisanie-label">Примечание курьера:</label>
										<textarea name="comment_c" class="he-60" readonly cols="25" rows="8" id="val_comment_c"></textarea>
									</td>
									<td>
										<label class="opisanie-label">Примечание кладовщика:</label>
										<textarea name="comment_w" cols="25" readonly class="he-60" rows="8" id="val_comment_w"></textarea>
									</td>
								</tr>
							</table>
						</div>
						<input type="hidden" name="id" id="val_id"/>																					
						<input type="hidden" name="id_manager" id="val_id_manager"/>																					
						<table class="table-tabs-content zakaz">
							<tbody>																													
								<tr>
									<td class="ta-right"> Имя:</td>				
									<td><input type="text" readonly name="firstname" id="val_firstname" /></td>
									<td class="ta-right"> Телефоны:</td>				
									<td><input type="text" readonly name="phone" id="val_phone" /></td>
									<td class="ta-right"> Емайл:</td>				
									<td><input type="text" readonly name="email" id="val_email" /></td>
								</tr>															
								<tr>
									<td class="ta-right"> Город:</td>				
									<td><input type="text" name="city" readonly id="val_city" /></td>
									<td class="ta-right"> Поселок:</td>				
									<td><input type="text" name="poselok" readonly id="val_poselok" /></td>
									<td class="ta-right"> Подъезд:</td>				
									<td><input type="text" name="entrance" readonly id="val_entrance" /></td>
								</tr>															
								<tr>	
									<td class="ta-right"> Улица:</td>				
									<td><input type="text" name="street" readonly id="val_street" /></td>
									<td class="ta-right"> Дом:</td>				
									<td><input type="text" name="house" readonly id="val_house" /></td>
									<td class="ta-right"> Корпус:</td>				
									<td><input type="text" name="building" readonly id="val_building" /></td>
								</tr>															
								<tr>	
									<td class="ta-right"> Квартира:</td>				
									<td><input type="text" name="apartment" readonly id="val_apartment" /></td>
									<td class="ta-right"> Этаж:</td>				
									<td><input type="text" name="floor" readonly id="val_floor" /></td>
									<td class="ta-right"> Дата доставки:</td>				
									<td><input type="text" name="date_dostavka" id="val_date_dostavka" class="admin-datepicker-format"/></td>											
								</tr>									
							</tbody>
						</table>						
					</div>		
				</div>	
			</form>
		</div>

	</div>
	
</div>	