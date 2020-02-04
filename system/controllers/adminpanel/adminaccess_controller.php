<?php

class Adminaccess_Controller {
	private $_content, $_table;

	public function __construct() {
		$this->_table = Config::getParam('modules->adminaccess->table');
		$this->_content['title'] = 'Права доступа';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/adminaccess/list');
   
		Render::layout('adminpanel/adminpanel', $this->_content);
   
	}

    public function loadAction() {
	
		// Начало формирование объекта
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		

		$count = Database::getCount($this->_table);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit']);
		
		$i = 0;
		foreach($items as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['prioritet'];
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {
		
		$data = array();
		
		if (isset($_POST['action'])) {
			
			$data = array(
				'name' => $_POST['name'],			  			
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'prioritet' => $_POST['prioritet'],
				'catalog_add' => ((isset($_POST['catalog_add'])) ? 1 : 0),
				'catalog_del' => ((isset($_POST['catalog_del'])) ? 1 : 0),
				'catalog_edit' => ((isset($_POST['catalog_edit'])) ? 1 : 0),
				'catalog_review' => ((isset($_POST['catalog_review'])) ? 1 : 0),
				'characteristics_add' => ((isset($_POST['characteristics_add'])) ? 1 : 0),
				'characteristics_del' => ((isset($_POST['characteristics_del'])) ? 1 : 0),
				'characteristics_edit' => ((isset($_POST['characteristics_edit'])) ? 1 : 0),
				'characteristics_review' => ((isset($_POST['characteristics_review'])) ? 1 : 0),	
				'maker_add' => ((isset($_POST['maker_add'])) ? 1 : 0),
				'maker_del' => ((isset($_POST['maker_del'])) ? 1 : 0),
				'maker_edit' => ((isset($_POST['maker_edit'])) ? 1 : 0),
				'maker_review' => ((isset($_POST['maker_review'])) ? 1 : 0),	
				'question_add' => ((isset($_POST['question_add'])) ? 1 : 0),
				'question_del' => ((isset($_POST['question_del'])) ? 1 : 0),
				'question_edit' => ((isset($_POST['question_edit'])) ? 1 : 0),
				'question_review' => ((isset($_POST['question_review'])) ? 1 : 0),
				'reviews_add' => ((isset($_POST['reviews_add'])) ? 1 : 0),
				'reviews_del' => ((isset($_POST['reviews_del'])) ? 1 : 0),
				'reviews_edit' => ((isset($_POST['reviews_edit'])) ? 1 : 0),
				'reviews_review' => ((isset($_POST['reviews_review'])) ? 1 : 0),
				'news_add' => ((isset($_POST['news_add'])) ? 1 : 0),
				'news_del' => ((isset($_POST['news_del'])) ? 1 : 0),
				'news_edit' => ((isset($_POST['news_edit'])) ? 1 : 0),
				'news_review' => ((isset($_POST['news_review'])) ? 1 : 0),		
				'article_add' => ((isset($_POST['article_add'])) ? 1 : 0),
				'article_del' => ((isset($_POST['article_del'])) ? 1 : 0),
				'article_edit' => ((isset($_POST['article_edit'])) ? 1 : 0),
				'article_review' => ((isset($_POST['article_review'])) ? 1 : 0),		
				'akcii_add' => ((isset($_POST['akcii_add'])) ? 1 : 0),
				'akcii_del' => ((isset($_POST['akcii_del'])) ? 1 : 0),
				'akcii_edit' => ((isset($_POST['akcii_edit'])) ? 1 : 0),
				'akcii_review' => ((isset($_POST['akcii_review'])) ? 1 : 0),		
				'banners_add' => ((isset($_POST['banners_add'])) ? 1 : 0),
				'banners_del' => ((isset($_POST['banners_del'])) ? 1 : 0),
				'banners_edit' => ((isset($_POST['banners_edit'])) ? 1 : 0),
				'banners_review' => ((isset($_POST['banners_review'])) ? 1 : 0),		
				'pages_add' => ((isset($_POST['pages_add'])) ? 1 : 0),
				'pages_del' => ((isset($_POST['pages_del'])) ? 1 : 0),
				'pages_edit' => ((isset($_POST['pages_edit'])) ? 1 : 0),
				'pages_review' => ((isset($_POST['pages_review'])) ? 1 : 0),
				'currency_add' => ((isset($_POST['currency_add'])) ? 1 : 0),
				'currency_del' => ((isset($_POST['currency_del'])) ? 1 : 0),
				'currency_edit' => ((isset($_POST['currency_edit'])) ? 1 : 0),
				'currency_review' => ((isset($_POST['currency_review'])) ? 1 : 0),			
				'adminusers_add' => ((isset($_POST['adminusers_add'])) ? 1 : 0),
				'adminusers_del' => ((isset($_POST['adminusers_del'])) ? 1 : 0),
				'adminusers_edit' => ((isset($_POST['adminusers_edit'])) ? 1 : 0),
				'adminusers_review' => ((isset($_POST['adminusers_review'])) ? 1 : 0),			
				'adminaccess_add' => ((isset($_POST['adminaccess_add'])) ? 1 : 0),
				'adminaccess_del' => ((isset($_POST['adminaccess_del'])) ? 1 : 0),
				'adminaccess_edit' => ((isset($_POST['adminaccess_edit'])) ? 1 : 0),
				'adminaccess_review' => ((isset($_POST['adminaccess_review'])) ? 1 : 0),			
				'registration_add' => ((isset($_POST['registration_add'])) ? 1 : 0),
				'registration_del' => ((isset($_POST['registration_del'])) ? 1 : 0),
				'registration_edit' => ((isset($_POST['registration_edit'])) ? 1 : 0),
				'registration_review' => ((isset($_POST['registration_review'])) ? 1 : 0),				
				'promocode_add' => ((isset($_POST['promocode_add'])) ? 1 : 0),
				'promocode_del' => ((isset($_POST['promocode_del'])) ? 1 : 0),
				'promocode_edit' => ((isset($_POST['promocode_edit'])) ? 1 : 0),
				'promocode_review' => ((isset($_POST['promocode_review'])) ? 1 : 0),			
				'raffle_add' => ((isset($_POST['raffle_add'])) ? 1 : 0),
				'raffle_del' => ((isset($_POST['raffle_del'])) ? 1 : 0),
				'raffle_edit' => ((isset($_POST['raffle_edit'])) ? 1 : 0),
				'raffle_review' => ((isset($_POST['raffle_review'])) ? 1 : 0),				
				'zakaz_add' => ((isset($_POST['zakaz_add'])) ? 1 : 0),
				'zakaz_del' => ((isset($_POST['zakaz_del'])) ? 1 : 0),
				'zakaz_edit' => ((isset($_POST['zakaz_edit'])) ? 1 : 0),
				'zakaz_review' => ((isset($_POST['zakaz_review'])) ? 1 : 0),				
				'zakaz_tovar_add' => ((isset($_POST['zakaz_tovar_add'])) ? 1 : 0),
				'zakaz_tovar_del' => ((isset($_POST['zakaz_tovar_del'])) ? 1 : 0),
				'zakaz_tovar_edit' => ((isset($_POST['zakaz_tovar_edit'])) ? 1 : 0),
				'zakaz_tovar_review' => ((isset($_POST['zakaz_tovar_review'])) ? 1 : 0),			
				'adminusers_stats_review' => ((isset($_POST['adminusers_stats_review'])) ? 1 : 0),	
				'connection_add' => ((isset($_POST['connection_add'])) ? 1 : 0),
				'connection_del' => ((isset($_POST['connection_del'])) ? 1 : 0),
				'connection_edit' => ((isset($_POST['connection_edit'])) ? 1 : 0),
				'connection_review' => ((isset($_POST['connection_review'])) ? 1 : 0),	
				'couriers_add' => ((isset($_POST['couriers_add'])) ? 1 : 0),
				'couriers_del' => ((isset($_POST['couriers_del'])) ? 1 : 0),
				'couriers_edit' => ((isset($_POST['couriers_edit'])) ? 1 : 0),
				'couriers_review' => ((isset($_POST['couriers_review'])) ? 1 : 0),	
				'kontragenty_add' => ((isset($_POST['kontragenty_add'])) ? 1 : 0),
				'kontragenty_del' => ((isset($_POST['kontragenty_del'])) ? 1 : 0),
				'kontragenty_edit' => ((isset($_POST['kontragenty_edit'])) ? 1 : 0),
				'kontragenty_review' => ((isset($_POST['kontragenty_review'])) ? 1 : 0),	
				'kontragenty_tip_add' => ((isset($_POST['kontragenty_tip_add'])) ? 1 : 0),
				'kontragenty_tip_del' => ((isset($_POST['kontragenty_tip_del'])) ? 1 : 0),
				'kontragenty_tip_edit' => ((isset($_POST['kontragenty_tip_edit'])) ? 1 : 0),
				'kontragenty_tip_review' => ((isset($_POST['kontragenty_tip_review'])) ? 1 : 0),		
				'valute_add' => ((isset($_POST['valute_add'])) ? 1 : 0),
				'valute_del' => ((isset($_POST['valute_del'])) ? 1 : 0),
				'valute_edit' => ((isset($_POST['valute_edit'])) ? 1 : 0),
				'valute_review' => ((isset($_POST['valute_review'])) ? 1 : 0),	
				'delivery_tmc_add' => ((isset($_POST['delivery_tmc_add'])) ? 1 : 0),
				'delivery_tmc_del' => ((isset($_POST['delivery_tmc_del'])) ? 1 : 0),
				'delivery_tmc_edit' => ((isset($_POST['delivery_tmc_edit'])) ? 1 : 0),
				'delivery_tmc_review' => ((isset($_POST['delivery_tmc_review'])) ? 1 : 0),	
				'sklad_add' => ((isset($_POST['sklad_add'])) ? 1 : 0),
				'sklad_del' => ((isset($_POST['sklad_del'])) ? 1 : 0),
				'sklad_edit' => ((isset($_POST['sklad_edit'])) ? 1 : 0),
				'sklad_review' => ((isset($_POST['sklad_review'])) ? 1 : 0),	
				'application_for_warehouse_add' => ((isset($_POST['application_for_warehouse_add'])) ? 1 : 0),
				'application_for_warehouse_del' => ((isset($_POST['application_for_warehouse_del'])) ? 1 : 0),
				'application_for_warehouse_edit' => ((isset($_POST['application_for_warehouse_edit'])) ? 1 : 0),
				'application_for_warehouse_review' => ((isset($_POST['application_for_warehouse_review'])) ? 1 : 0),	
				'catalog_sklad_add' => ((isset($_POST['catalog_sklad_add'])) ? 1 : 0),
				'catalog_sklad_del' => ((isset($_POST['catalog_sklad_del'])) ? 1 : 0),
				'catalog_sklad_edit' => ((isset($_POST['catalog_sklad_edit'])) ? 1 : 0),
				'catalog_sklad_review' => ((isset($_POST['catalog_sklad_review'])) ? 1 : 0),	
				'kassa_add' => ((isset($_POST['kassa_add'])) ? 1 : 0),
				'kassa_del' => ((isset($_POST['kassa_del'])) ? 1 : 0),
				'kassa_edit' => ((isset($_POST['kassa_edit'])) ? 1 : 0),
				'kassa_review' => ((isset($_POST['kassa_review'])) ? 1 : 0),	
				'kassa_tree_add' => ((isset($_POST['kassa_tree_add'])) ? 1 : 0),
				'kassa_tree_del' => ((isset($_POST['kassa_tree_del'])) ? 1 : 0),
				'kassa_tree_edit' => ((isset($_POST['kassa_tree_edit'])) ? 1 : 0),	
				'tip_operation_add' => ((isset($_POST['tip_operation_add'])) ? 1 : 0),
				'tip_operation_del' => ((isset($_POST['tip_operation_del'])) ? 1 : 0),
				'tip_operation_edit' => ((isset($_POST['tip_operation_edit'])) ? 1 : 0),
				'tip_operation_review' => ((isset($_POST['tip_operation_review'])) ? 1 : 0),	
				'competitors_add' => ((isset($_POST['competitors_add'])) ? 1 : 0),
				'competitors_del' => ((isset($_POST['competitors_del'])) ? 1 : 0),
				'competitors_edit' => ((isset($_POST['competitors_edit'])) ? 1 : 0),
				'competitors_review' => ((isset($_POST['competitors_review'])) ? 1 : 0),	
				'price_monitoring_add' => ((isset($_POST['price_monitoring_add'])) ? 1 : 0),
				'price_monitoring_del' => ((isset($_POST['price_monitoring_del'])) ? 1 : 0),
				'price_monitoring_edit' => ((isset($_POST['price_monitoring_edit'])) ? 1 : 0),
				'price_monitoring_review' => ((isset($_POST['price_monitoring_review'])) ? 1 : 0),	
				'fuel_add' => ((isset($_POST['fuel_add'])) ? 1 : 0),
				'fuel_del' => ((isset($_POST['fuel_del'])) ? 1 : 0),
				'fuel_edit' => ((isset($_POST['fuel_edit'])) ? 1 : 0),
				'fuel_review' => ((isset($_POST['fuel_review'])) ? 1 : 0),	
				'product_week_add' => ((isset($_POST['product_week_add'])) ? 1 : 0),
				'product_week_del' => ((isset($_POST['product_week_del'])) ? 1 : 0),
				'product_week_edit' => ((isset($_POST['product_week_edit'])) ? 1 : 0),
				'product_week_review' => ((isset($_POST['product_week_review'])) ? 1 : 0),	
				'importer_add' => ((isset($_POST['importer_add'])) ? 1 : 0),
				'importer_del' => ((isset($_POST['importer_del'])) ? 1 : 0),
				'importer_edit' => ((isset($_POST['importer_edit'])) ? 1 : 0),
				'importer_review' => ((isset($_POST['importer_review'])) ? 1 : 0),
				'manufacturer_add' => ((isset($_POST['manufacturer_add'])) ? 1 : 0),
				'manufacturer_del' => ((isset($_POST['manufacturer_del'])) ? 1 : 0),
				'manufacturer_edit' => ((isset($_POST['manufacturer_edit'])) ? 1 : 0),
				'manufacturer_review' => ((isset($_POST['manufacturer_review'])) ? 1 : 0),
				'brand_add' => ((isset($_POST['brand_add'])) ? 1 : 0),
				'brand_del' => ((isset($_POST['brand_del'])) ? 1 : 0),
				'brand_edit' => ((isset($_POST['brand_edit'])) ? 1 : 0),
				'brand_review' => ((isset($_POST['brand_review'])) ? 1 : 0),
				'banners_left_add' => ((isset($_POST['banners_left_add'])) ? 1 : 0),
				'banners_left_del' => ((isset($_POST['banners_left_del'])) ? 1 : 0),
				'banners_left_edit' => ((isset($_POST['banners_left_edit'])) ? 1 : 0),
				'banners_left_review' => ((isset($_POST['banners_left_review'])) ? 1 : 0),
				'banners_small_add' => ((isset($_POST['banners_small_add'])) ? 1 : 0),
				'banners_small_del' => ((isset($_POST['banners_small_del'])) ? 1 : 0),
				'banners_small_edit' => ((isset($_POST['banners_small_edit'])) ? 1 : 0),
				'banners_small_review' => ((isset($_POST['banners_small_review'])) ? 1 : 0),
				'spros_add' => ((isset($_POST['spros_add'])) ? 1 : 0),
				'spros_del' => ((isset($_POST['spros_del'])) ? 1 : 0),
				'spros_edit' => ((isset($_POST['spros_edit'])) ? 1 : 0),
				'spros_review' => ((isset($_POST['spros_review'])) ? 1 : 0),
				'zpmanager_add' => ((isset($_POST['zpmanager_add'])) ? 1 : 0),
				'zpmanager_del' => ((isset($_POST['zpmanager_del'])) ? 1 : 0),
				'zpmanager_edit' => ((isset($_POST['zpmanager_edit'])) ? 1 : 0),
				'zpmanager_review' => ((isset($_POST['zpmanager_review'])) ? 1 : 0),
				'managers_add' => ((isset($_POST['managers_add'])) ? 1 : 0),
				'managers_del' => ((isset($_POST['managers_del'])) ? 1 : 0),
				'managers_edit' => ((isset($_POST['managers_edit'])) ? 1 : 0),
				'managers_review' => ((isset($_POST['managers_review'])) ? 1 : 0),
				'catalog_complect_add' => ((isset($_POST['catalog_complect_add'])) ? 1 : 0),
				'catalog_complect_del' => ((isset($_POST['catalog_complect_del'])) ? 1 : 0),
				'catalog_complect_edit' => ((isset($_POST['catalog_complect_edit'])) ? 1 : 0),
				'catalog_complect_review' => ((isset($_POST['catalog_complect_review'])) ? 1 : 0),
				'return_tmc_add' => ((isset($_POST['return_tmc_add'])) ? 1 : 0),
				'return_tmc_del' => ((isset($_POST['return_tmc_del'])) ? 1 : 0),
				'return_tmc_edit' => ((isset($_POST['return_tmc_edit'])) ? 1 : 0),
				'return_tmc_review' => ((isset($_POST['return_tmc_review'])) ? 1 : 0)	
			);	
		
		}
		
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {

			Database::insert($this->_table,$data);	
		
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {

			$where = '`id` = '.$_POST['id'];
			Database::update($this->_table,$data,$where);	

		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Database::delete($this->_table,$_POST['del_id']);
		}	
	
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$adminaccess = array();
		$adminaccess = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($adminaccess);
	}
	
	public function accessAction() {
		$data = array();	
		$data = get_array_access();
		echo json_encode($data);
	} 	
		
}