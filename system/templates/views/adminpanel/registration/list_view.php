<link rel="stylesheet" type="text/css" media="screen" href="/css/admin/content.css" />
<script type="text/javascript" src="/js/admin/registration/grid_registration.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="path"]');
  })
</script>
<div>

	<div>
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить пользователя?</p>
		</div>
	
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Общее</a></li>
						<li><a href="#tabs-content-2">Адрес</a></li>				
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
								<td> Менеджер:</td>				
								<td>
									<select name="manager" id="val_manager">
										<option value="0">Нет</option>
										<option value="1">Да</option>
									</select>
									<div><i>Если в поле менеджер стоит "Да", необходима привязка к этому менеджеру его учетных данных!</i></div>
								</td>
							</tr>							
							<tr>
								<td> Учетные данные:</td>	
								<td>
									<select name="id_adminuser" id="val_id_adminuser">
										<option value="0">-- Выберите --</option>
										<?php foreach ($adminusers as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['login']; ?></option>
										<?php endforeach; ?>
									</select>
									<div><i>Если в поле менеджер стоит "Да", необходима привязка к этому менеджеру его учетных данных!</i></div>
								</td>
							</tr>						
							<tr>
								<td> Дилер:</td>				
								<td>
									<select name="diler" id="val_diler">
										<option value="0">Нет</option>
										<option value="1">Розничная цена</option>
										<option value="2">Розничная 1</option>
										<option value="3">Диллерская 1</option>
										<option value="4">Диллерская 2</option>
										<option value="5">Диллерская 3</option>
									</select>
								</td>
							</tr>	
							<tr>
								<td> Касса:</td>	
								<td>
									<select name="id_kassa_tree" id="val_id_kassa_tree">
										<option value="0">-- Выберите --</option>
										<?php foreach ($kassa_tree as $item): ?>														
											<option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>						
							<tr>
								<td> Наименование:</td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>						
							<tr>
								<td> Логин:</td>				
								<td><input type="text" name="login" id="val_login" /></td>
							</tr>														
							<tr>
								<td> Пароль:</td>				
								<td><input type="password" name="pass" id="val_pass" /></td>
							</tr>
							<tr>
								<td> E-mail:</td>				
								<td><input type="text" name="email" id="val_email" /></td>
							</tr>
							<tr>
								<td> Контактный телефон:</td>				
								<td><input type="text" name="phone" id="val_phone" /></td>
							</tr>
							<tr>
								<td> Контактный телефон:</td>				
								<td><input type="text" name="phone2" id="val_phone2" /></td>
							</tr>
							<tr>
								<td> Контактный телефон:</td>				
								<td><input type="text" name="phone3" id="val_phone3" /></td>
							</tr>							
						</tbody>
					</table>
					</div>
	<!-- Вкладка описание !-->
					<div id="tabs-content-2">
					<table class="table-tabs-content">
						<tbody>						
							<tr>
								<td> Улица:</td>				
								<td><input type="text" name="street" id="val_street" /></td>
							</tr>
							<tr>
								<td> Дом:</td>				
								<td><input type="text" name="house" id="val_house" /></td>
							</tr>
							<tr>
								<td> Корпус:</td>				
								<td><input type="text" name="building" id="val_building" /></td>
							</tr>															
							<tr>
								<td> Квартира:</td>				
								<td><input type="text" name="apartment" id="val_apartment" /></td>
							</tr>
							<tr>
								<td> Подъезд:</td>				
								<td><input type="text" name="entrance" id="val_entrance" /></td>
							</tr>
							<tr>
								<td> Этаж:</td>				
								<td><input type="text" name="floor" id="val_floor" /></td>
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
