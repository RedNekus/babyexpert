<script type="text/javascript" src="/js/admin/adminaccess/grid_table.js"></script>
<div>

	<div>
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить данные?</p>
		</div>
	
		<div id="dialog-edit">
			<form method="post" name="form" id="form">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-adminaccess-1">Общее</a></li>
						<li><a href="#tabs-adminaccess-2">Каталог</a></li>
						<li><a href="#tabs-adminaccess-3">Контент</a></li>					
						<li><a href="#tabs-adminaccess-4">Настройки</a></li>					
						<li><a href="#tabs-adminaccess-5">Справочники</a></li>					
						<li><a href="#tabs-adminaccess-6">Документы</a></li>					
						<li><a href="#tabs-adminaccess-7">Статистика</a></li>					
					</ul>
	<!-- Вкладка общие -->				
					<div id="tabs-adminaccess-1">
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
								<td> Приоритет:</td>				
								<td><input type="text" name="prioritet" id="val_prioritet" /></td>
							</tr>								
						</tbody>
					</table>
					</div>
	<!-- Вкладка  Доступ к модулям (каталог) -->
					<div id="tabs-adminaccess-2">
						<table>
							<tr>
								<td class="td-name-width"> Каталог товаров:</td>				
								<td class="td-access-width"><?php echo get_access_form("catalog"); ?></td>
							</tr>
							<tr>
								<td class="td-name-width"> Каталог комплекты:</td>				
								<td class="td-access-width"><?php echo get_access_form("catalog_complect"); ?></td>
							</tr>
							<tr>
								<td class="td-name-width"> Товары недели:</td>				
								<td class="td-access-width"><?php echo get_access_form("product_week"); ?></td>
							</tr>							
							<tr>
								<td> Отзывы:</td>				
								<td class="td-access-width"><?php echo get_access_form("reviews"); ?></td>
							</tr>							
							<tr>
								<td> Вопрос ответ:</td>				
								<td class="td-access-width"><?php echo get_access_form("question"); ?></td>
							</tr>			
							<tr>
								<td> Бренды:</td>				
								<td class="td-access-width"><?php echo get_access_form("maker"); ?></td>
							</tr>			
							<tr>
								<td> Импортеры:</td>				
								<td class="td-access-width"><?php echo get_access_form("importer"); ?></td>
							</tr>			
							<tr>
								<td> Изготовители:</td>				
								<td class="td-access-width"><?php echo get_access_form("manufacturer"); ?></td>
							</tr>			<tr>
								<td> Бренд + раздел:</td>				
								<td class="td-access-width"><?php echo get_access_form("brand"); ?></td>
							</tr>							
							<tr>
								<td> Характеристики:</td>				
								<td class="td-access-width"><?php echo get_access_form("characteristics"); ?></td>
							</tr>							
							<tr>
								<td> Розыгрыши:</td>				
								<td class="td-access-width"><?php echo get_access_form("raffle"); ?></td>
							</tr>							
							<tr>
								<td> Заказы клиенты:</td>				
								<td class="td-access-width"><?php echo get_access_form("zakaz"); ?></td>
							</tr>							
							<tr>
								<td> Заказы товар:</td>				
								<td class="td-access-width"><?php echo get_access_form("zakaz_tovar"); ?></td>
							</tr>							
							<tr>
								<td> Склад товаров:</td>				
								<td class="td-access-width"><?php echo get_access_form("catalog_sklad"); ?></td>
							</tr>							
							<tr>
								<td> Мониторинг цен:</td>				
								<td class="td-access-width"><?php echo get_access_form("price_monitoring"); ?></td>
							</tr>
						</table>
					</div>
	<!-- Вкладка  Доступ к модулям (контент) -->						
					<div id="tabs-adminaccess-3">
						<table>
							<tr>
								<td class="td-name-width"> Новости:</td>				
								<td class="td-access-width"><?php echo get_access_form("news"); ?></td>
							</tr>							
							<tr>
								<td> Статьи:</td>				
								<td class="td-access-width"><?php echo get_access_form("article"); ?></td>
							</tr>							
							<tr>
								<td> Акции:</td>				
								<td class="td-access-width"><?php echo get_access_form("akcii"); ?></td>
							</tr>							
							<tr>
								<td> Баннеры:</td>				
								<td class="td-access-width"><?php echo get_access_form("banners"); ?></td>
							</tr>							
							<tr>
								<td> Баннер большой:</td>				
								<td class="td-access-width"><?php echo get_access_form("banners_left"); ?></td>
							</tr>							
							<tr>
								<td> Баннер маленький:</td>				
								<td class="td-access-width"><?php echo get_access_form("banners_small"); ?></td>
							</tr>							
							<tr>
								<td> Страницы:</td>				
								<td class="td-access-width"><?php echo get_access_form("pages"); ?></td>
							</tr>
						</table>				
					</div>	
	<!-- Вкладка Доступ к модулям (настройки) -->
					<div id="tabs-adminaccess-4">
						<table>
							<tr>
								<td class="td-name-width"> Валюты и Курсы:</td>				
								<td class="td-access-width"><?php echo get_access_form("currency"); ?></td>
							</tr>							
							<tr>
								<td> Учетные данные:</td>				
								<td class="td-access-width"><?php echo get_access_form("adminusers"); ?></td>
							</tr>							
							<tr>
								<td> Доступ к админке:</td>				
								<td class="td-access-width"><?php echo get_access_form("adminaccess"); ?></td>
							</tr>							
							<tr>
								<td> Пользователи:</td>				
								<td class="td-access-width"><?php echo get_access_form("registration"); ?></td>
							</tr>							
							<tr>
								<td> Промокод:</td>				
								<td class="td-access-width"><?php echo get_access_form("promocode"); ?></td>
							</tr>							
							<tr>
								<td> Порталы:</td>				
								<td class="td-access-width"><?php echo get_access_form("connection"); ?></td>
							</tr>	
							<tr>
								<td> ЗП менеджер:</td>				
								<td class="td-access-width"><?php echo get_access_form("zpmanager"); ?></td>
							</tr>								
						</table>				
					</div>	
	<!-- Вкладка Доступ к модулям (словари) -->
					<div id="tabs-adminaccess-5">
						<table>
							<tr>
								<td class="td-name-width"> Курьеры:</td>				
								<td class="td-access-width"><?php echo get_access_form("couriers"); ?></td>
							</tr>	
							<tr>
								<td> Менеджеры:</td>				
								<td class="td-access-width"><?php echo get_access_form("managers"); ?></td>
							</tr>	
							<tr>
								<td> Контрагенты:</td>				
								<td class="td-access-width"><?php echo get_access_form("kontragenty"); ?></td>
							</tr>		
							<tr>
								<td> Тип контрагентов:</td>				
								<td class="td-access-width"><?php echo get_access_form("kontragenty_tip"); ?></td>
							</tr>		
							<tr>
								<td> Валюты:</td>				
								<td class="td-access-width"><?php echo get_access_form("valute"); ?></td>
							</tr>		
							<tr>
								<td> Склады:</td>				
								<td class="td-access-width"><?php echo get_access_form("sklad"); ?></td>
							</tr>		
							<tr>
								<td> Тип операции:</td>				
								<td class="td-access-width"><?php echo get_access_form("tip_operation"); ?></td>
							</tr>		
							<tr>
								<td> Конкуренты:</td>				
								<td class="td-access-width"><?php echo get_access_form("competitors"); ?></td>
							</tr>														
						</table>				
					</div>	
	<!-- Вкладка Доступ к модулям (документы) -->
					<div id="tabs-adminaccess-6">
						<table>
							<tr>
								<td class="td-name-width"> Поступление ТМЦ:</td>				
								<td class="td-access-width"><?php echo get_access_form("delivery_tmc"); ?></td>
							</tr>
							<tr>
								<td class="td-name-width"> Возврат ТМЦ:</td>				
								<td class="td-access-width"><?php echo get_access_form("return_tmc"); ?></td>
							</tr>
							<tr>
								<td class="td-name-width"> Заявка на склад:</td>				
								<td class="td-access-width"><?php echo get_access_form("application_for_warehouse"); ?></td>
							</tr>	
							<tr>
								<td class="td-name-width"> Кассы:</td>				
								<td class="td-access-width"><?php echo get_access_form("kassa"); ?></td>
							</tr>	
							<tr>
								<td class="td-name-width"> Кассы дерево:</td>				
								<td class="td-access-width"><?php echo get_access_form("kassa_tree"); ?></td>
							</tr>	
							<tr>
								<td class="td-name-width"> Топливо:</td>				
								<td class="td-access-width"><?php echo get_access_form("fuel"); ?></td>
							</tr>														
						</table>				
					</div>
	<!-- Статистика -->
					<div id="tabs-adminaccess-7">
						<table>							
							<tr>
								<td> Наполнители:</td>				
								<td class="td-access-width"><?php echo get_access_form("adminusers_stats"); ?></td>
							</tr>	
							<tr>
								<td class="td-name-width"> Спрос:</td>				
								<td class="td-access-width"><?php echo get_access_form("spros"); ?></td>
							</tr>							
						</table>				
					</div>					
				</div>	
			</form>
		</div>


        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	
