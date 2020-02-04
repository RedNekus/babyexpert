<link type="text/css" rel="stylesheet" href="/css/admin/admin_menu.css" />
<ul id="admin_menu">
	<li><a href="#">Каталог</a>
		<ul>
		<?php 
		$admin = get_array_access();  
		if(@$admin['catalog_review'])
		echo '<li><a href="/adminpanel/catalog/">Каталог товаров</a></li>'; 

		if(@$admin['product_week_review'])
		echo '<li><a href="/adminpanel/product_week/">Товары недели</a></li>';
		
		if(@$admin['reviews_review'])
		echo '<li><a href="/adminpanel/reviews/">Отзывы</a></li>';

		if(@$admin['question_review'])
		echo '<li><a href="/adminpanel/question/">Вопрос-Ответ</a></li>';	
			
		if(@$admin['maker_review'])			
		echo '<li><a href="/adminpanel/maker/">Бренды</a></li>'; 
		
		if(@$admin['importer_review'])
		echo '<li><a href="/adminpanel/importer/">Импортеры</a></li>'; 
		
		if(@$admin['manufacturer_review'])
		echo '<li><a href="/adminpanel/manufacturer/">Изготовители</a></li>';
		
		if(@$admin['brand_review'])
		echo '<li><a href="/adminpanel/brand/">Бренд + раздел</a></li>'; 
			
		if(@$admin['characteristics_review'])
		echo '<li><a href="/adminpanel/characteristics/">Характеристики товаров</a></li>'; 

		if(@$admin['raffle_review'])
		echo '<li><a href="/adminpanel/raffle/">Розыгрыши</a></li>'; 	
			
		if(@$admin['zakaz_review'])
		echo '<li><a href="/adminpanel/zakaz/">Заказы</a></li> 	
			  <li><a href="/adminpanel/zakaz/predzakaz/">Заказы (предзаказ)</a></li> 	
			  <li><a href="/adminpanel/zakaz/rezerv/">Заказы (резерв)</a></li>	
			  <li><a href="/adminpanel/zakaz/status/">Заказы (статус)</a></li>	
			  <li><a href="/adminpanel/zakaz/stats/">Заказы (статистика)</a></li>	
			  <li><a href="/adminpanel/zakaz/vitek/">Заказы (витя)</a></li>';	
				
		if(@$admin['catalog_sklad_review'])
		echo '<li><a href="/adminpanel/catalog_sklad/">Остатки ТМЦ</a></li>'; 	
				
		if(@$admin['price_monitoring_review'])
		echo '<li><a href="/adminpanel/price_monitoring/">Мониторинг цен</a></li>'; 	
		?>		
		</ul> 
	</li> 
	<li> 
		<a href="#">Контент</a> 
		<ul>
		<?php
		if(@$admin['news_review'])
		echo '<li><a href="/adminpanel/news/">Новости</a></li>';	
			
		if(@$admin['article_review'])			
		echo '<li><a href="/adminpanel/article/">Статьи</a></li>';
			
		if(@$admin['akcii_review'])			
		echo '<li><a href="/adminpanel/akcii/">Акции</a></li>';
			
		if(@$admin['banners_review'])			
		echo '<li><a href="/adminpanel/banners/">Баннеры</a></li>';	

		if(@$admin['banners_left_review'])
		echo '<li><a href="/adminpanel/banners_left/">Баннер большой</a></li>';	

		if(@$admin['banners_small_review'])
		echo '<li><a href="/adminpanel/banners_small/">Баннер маленький</a></li>';			
			
		if(@$admin['pages_review']) 			
		echo '<li><a href="/adminpanel/pages/">Страницы</a></li>';
		?>
		</ul>		
	</li> 
	<li> 
		<a href="#">Настройки</a>
		<ul>
		<?php
		if(@$admin['currency_review'])			
		echo '<li><a href="/adminpanel/currency/">Валюты и Курсы</a></li>';
			
		if(@$admin['adminusers_review'])				
		echo '<li><a href="/adminpanel/adminusers/">Учетные данные</a></li>';
			
		if(@$admin['adminaccess_review'])				
		echo '<li><a href="/adminpanel/adminaccess/">Права доступа</a></li>';	
			
		if(@$admin['registration_review'])				
		echo '<li><a href="/adminpanel/registration/">Пользователи</a></li>';
		
		if(@$admin['promocode_review'])				
		echo '<li><a href="/adminpanel/promocode/">Промокоды</a></li>';
			
		if(@$admin['connection_review'])
		echo '<li><a href="/adminpanel/connection/">Порталы</a></li>';	
	
		if(@$admin['zpmanager_review'])		
		echo '<li><a href="/adminpanel/zpmanager/">ЗП менеджер</a></li>';	
		?>
		</ul>
	</li> 
	<li> 
		<a href="#">Справочники</a>
		<ul>	
		<?php
		if(@$admin['couriers_review'])		
		echo '<li><a href="/adminpanel/couriers/">Курьеры</a></li>';
	
		if(@$admin['managers_review'])		
		echo '<li><a href="/adminpanel/managers/">Менеджеры</a></li>';
	
		if(@$admin['kontragenty_tip_review'])		
		echo '<li><a href="/adminpanel/kontragenty_tip/">Тип контрагентов</a></li>';
	
		if(@$admin['kontragenty_review'])		
		echo '<li><a href="/adminpanel/kontragenty/">Контрагенты</a></li>';
		
		if(@$admin['valute_review'])		
		echo '<li><a href="/adminpanel/valute/">Валюты</a></li>';
	
		if(@$admin['sklad_review'])		
		echo '<li><a href="/adminpanel/sklad/">Склады</a></li>';
	
		if(@$admin['tip_operation_review'])		
		echo '<li><a href="/adminpanel/tip_operation/">Тип операции</a></li>';

		if(@$admin['competitors_review'])		
		echo '<li><a href="/adminpanel/competitors/">Конкуренты</a></li>';
		?>					
		</ul>
	</li>	
	<li> 
		<a href="#">Документы</a>
		<ul>
		<?php
		if(@$admin['delivery_tmc_review'])		
		echo '<li><a href="/adminpanel/delivery_tmc/">Поступление ТМЦ</a></li>';

		if(@$admin['return_tmc_review'])		
		echo '<li><a href="/adminpanel/return_tmc/">Возврат ТМЦ</a></li>';

		if(@$admin['application_for_warehouse_review'])		
		echo '<li><a href="/adminpanel/application_for_warehouse/">Заявка на склад</a></li>';
			
		if(@$admin['kassa_review'])		
		echo '<li><a href="/adminpanel/kassa/">Кассы</a></li>';
		?>					
		</ul>
	</li>
	<?php
	if (@$admin['fuel_review'])
	echo '<li><a href="/adminpanel/fuel">Топливо</a></li>'; 	
	?>
	<li> 
		<a href="#">Статистика</a>
		<ul>
		<?php
		if (@$admin['id']==1)
		echo '<li><a href="/adminpanel/stat_price_monitoring/">Мониторинг цен</a></li>';
	
		if(@$admin['adminusers_stats_review'])				
		echo '<li><a href="/adminpanel/adminusers_stats/">Наполнители</a></li>';	
	
		if(@$admin['spros_review'])				
		echo '<li><a href="/adminpanel/spros/">Спрос</a></li>';
		?>	
		</ul>		
	</li> 	
	<li style="float: right;"> 
		<a title="Выход" onclick="return confirm('Выйти?');" href="/adminpanel/?logout">ВЫХОД</a>
	</li> 
</ul> 
