<script type="text/javascript" src="/js/admin/adminusers/grid_table.js"></script>
<div>

	<div>
	
		<div id="deldialog" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить пользователя?</p>
		</div>
	
		<div id="catalogForm">
			<form method="post" name="catalog_form" id="catalog_form" action="">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Общее</a></li>
						<li><a href="#tabs-content-2">Описание</a></li>
						<li><a href="#tabs-content-3">Meta</a></li>					
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
								<td> Права доступа:</td>	
								<td>
									<select name="id_access" id="val_id_access">
										<option value="0">-- Выберите --</option>
										<?php foreach ($adminacces as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td> Менеджер:</td>	
								<td>
									<select name="id_manager" id="val_id_manager">
										<option value="0">-- Выберите --</option>
										<?php foreach ($managers as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td> Курьер:</td>	
								<td>
									<select name="id_courier" id="val_id_courier">
										<option value="0">-- Выберите --</option>
										<?php foreach ($couriers_tree as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td> Касса поступления:</td>	
								<td>
									<select name="id_kassa_tree" id="val_id_kassa_tree">
										<option value="0">-- Выберите --</option>
										<?php foreach($kassa_tree as $item): ?>
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>			
								</td>
							</tr>								
							<tr>
								<td> Кассы:</td>	
								<td>
									<?php foreach($kassa_tree as $item): ?>
									<div class="checkbox-block">
									<input type="checkbox" name="id_kassa[]" value="<?php echo $item['id']; ?>" class="ch-clear" id="val_ch_<?php echo $item['id']; ?>"/><label class="norm-label" for="val_ch_<?php echo $item['id']; ?>"><?php echo $item['name']; ?></label> <br/>
									</div>
									<?php endforeach; ?>					
								</td>
							</tr>							
							<tr>
								<td> Фамилия Имя Отчество:</td>				
								<td><input type="text" name="fio" id="val_fio" /></td>
							</tr>
							<tr>
								<td> Логин:</td>				
								<td><input type="text" name="login" id="val_login" /></td>
							</tr>							
							<tr>
								<td> Пароль:</td>				
								<td><input type="PASSWORD" name="password" id="val_password" /></td>
							</tr>
							<tr>
								<td> Email: </td>				
								<td><input type="text" name="email" id="val_email" /></td>
							</tr>							
						</tbody>
					</table>
					</div>
	<!-- Вкладка описание !-->
					<div id="tabs-content-2">
						<table class="table-tabs-content">
							<tr>
								<td><label>Описание:</label></td>
							</tr>
							<tr>
								<td><textarea name="short_description" class="tinymce" cols="50" rows="8" id="val_short_description"></textarea></td>
							</tr>
						</table>
					</div>
	<!-- Вкладка МЕТА !-->						
					<div id="tabs-content-3">
						<table class="table-tabs-content">
							<tbody>					
								<tr>
									<td class="td-name-width"> Title: </td>				
									<td><input type="text" name="title" id="val_title" /></td>
								</tr>
								<tr>
									<td class="td-name-align"> Meta Keywords:</td>				
									<td><textarea name="keywords" id="val_keywords"></textarea></td>
								</tr>
								<tr>
									<td class="td-name-align"> Meta Description: </td>				
									<td><textarea name="description" id="val_description"></textarea></td>
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
