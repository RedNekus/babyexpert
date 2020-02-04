<?php

class Price_monitoring_Controller {
	private $_content, $_table;

	public function __construct() {
		Load::model('Catalog');		
		$this->_table = get_table('price_monitoring');
		$this->_table_c = get_table('price_competitors');
		$this->_table_log = get_table('logs');
		$this->_content['title'] = 'Мониторинг цен';
	}

	public function defaultAction() {
		
		$trees = Database::getRows(get_table('catalog_tree'), 'name','asc', false,'pid=1');
		$makers = Database::getRows(get_table('maker'),'name','asc');
		$competitors = Database::getRows(get_table('competitors'),'site','asc');
		
		$this->_content['content'] = Render::view(
			'adminpanel/price_monitoring/list', array(
					'trees' => $trees,
					'makers' => $makers,
					'competitors' => $competitors
			));
	   
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
			$product = Database::getRow(get_table('catalog'),$item['id_catalog']);
			$data['rows'][$i]['cell'][] = ' ';
			$data['rows'][$i]['cell'][] = get_product_name($product,true);
			$data['rows'][$i]['cell'][] = $product['cena'];
			$data['rows'][$i]['cell'][] = transform_to_blr($product,false);
			$data['rows'][$i]['cell'][] = '<a href="/product/'.$product['path'].'" target="_ablank">Просмотр</a>';			
			$data['rows'][$i]['cell'][] = Database::getField(get_table('adminusers'),$item['id_adminuser'],'id','login');
			$data['rows'][$i]['cell'][] = $item['date_create'];
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {

		$data = array();
		$arr = array();
		
		if (isset($_POST['action'])) {
			$data = array(	  			  		  		  		  		  		  
				'id_adminuser' => $_SESSION['isLoggedIn']['id'],
				'date_create' => date('Y-m-d'),
			);
			
			$data_cena = array(
				'cena' => $_POST['cena'],
				'cena_blr' => $_POST['cena_blr'],
			);
			
			// ДОБАВИТЬ элемент в таблицу
			if ($_POST['action']=="add") {
				$arr = array();
				$data['id_catalog'] = $_POST['id_tovar'];
				if ($data['id_catalog'] != 0) {
					Database::insert($this->_table,$data);
					Database::update(get_table('catalog'),$data_cena,"id = ".$data['id_catalog']);
					$arr['success'] = TRUE;
					$arr['last_id'] = Database::getLastId($this->_table);	
				} else {
					$arr['success'] = FALSE;
					$arr['msg'] = 'Необходимо выбрать товар!';
				}				
			}
			
			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="edit") {
				$data['id_catalog'] = $_POST['id_catalog'];
				if ($data['id_catalog'] != 0) {
					$where = '`id` = '.$_POST['id']; 
					Database::update($this->_table,$data,$where);
					Database::update(get_table('catalog'),$data_cena,"id = ".$data['id_catalog']);		
		
					if (!empty($_POST['id'])) { 			
						$logs = Database::getRows($this->_table_log,'id','desc',false,'table_name = "price_monitoring" and id_table = '.$_POST['id']);				
						if (@$logs[0]['cena'] != $_POST['cena'] or @$logs[0]['cena_blr'] != $_POST['cena_blr']) {	
							$data_log = array(	
								'table_name' => 'price_monitoring',
								'id_table' => $_POST['id'],
								'cena' => $_POST['cena'],
								'cena_blr' => $_POST['cena_blr'],
								'cena_zakup' => $_POST['cena_zakup'],
								'delta' => $_POST['delta'],
								'date_create' => date('Y-m-d'),
								'time_create' => date('G:i')
							);			
							Database::insert($this->_table_log,$data_log);
						}
					}
			
					$arr['success'] = TRUE;	
				} else {
					$arr['success'] = FALSE;
					$arr['msg'] = 'Необходимо выбрать товар!';
				}				
			}

		} 

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Database::delete($this->_table,$_POST['del_id']);
		}	
		$arr['action'] = @$_POST['action'];		
		echo json_encode($arr);	
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$id = $_POST['id'];
		$data = Database::getRow($this->_table,$id);
		$product = Database::getRow(get_table('catalog'),$data['id_catalog']);
		$data['cena'] = $product['cena'];
		$data['cena_blr'] = $product['cena_blr'];
		$data['cena_blr_kurs'] = transform_to_blr($product,false);
		$data['tovar_name'] = get_product_name($product,true);
		
		$table_st = get_table('sklad_tovar');
		$sklad_tovar = Database::getRows($table_st,'id','desc',false,'id_item LIKE "'.$data['id_catalog'].'-%"');		
		if (isset($sklad_tovar[0])) {
			$st = $sklad_tovar[0];
			$id = $st['id_delivery_tmc'];
	
			$item = Database::getRow(get_table('delivery_tmc'),$id);
			$cena_zakup = transform_to_kurs($st['cena'],$item['id_valute'],$item['kurs']);
						
		}
		$data['cena_zakup'] = @$cena_zakup;
		$data['kurs'] = Database::getField(get_table('currency_tree'),$product['id_currency'],'id','kurs');
		$data['delta'] = $product['cena'] -  @$cena_zakup;
		$data['link'] = '<a href="/product/'.$product['path'].'" target="_ablank">Просмотр</a>';
		echo json_encode($data);
	}

	public function load_select_makerAction() {
		$data = array();
		$data['makers'] = get_select_menu_by_id_tree($_POST['id_tree']);
		$data['tovars'] = get_select_menu_by_id_maker($_POST['id_tree'],0);
		echo json_encode($data);		
	}	
	
	public function load_select_tovarAction() {
		$data = array();
		$data['tovars'] = get_select_menu_by_id_maker($_POST['id_tree'],$_POST['id_maker']);
		echo json_encode($data);
	}
   	

	public function load_tovarAction() {
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		

		$id_price_monitoring = (@$_GET['id_price_monitoring']) ? @$_GET['id_price_monitoring'] : 0;
		$where = "`id_price_monitoring` = $id_price_monitoring";
		
		$count = Database::getCount($this->_table_c,$where);  
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table_c, $searchField, $searchOper, $searchString,$where);
		else $items = Database::getRows($this->_table_c, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		foreach($items as $item) {
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = '';
			$data['rows'][$i]['cell'][] = Database::getField(get_table('competitors'),$item['id_competitors'],'id','site');
			$data['rows'][$i]['cell'][] = '<a href="'.$item['link'].'" class="kolvo-click" rel="'.$item['id'].'" target="_ablank">Просмотр</a>';
			$data['rows'][$i]['cell'][] = '<a href="#" id="show-log" rel="'.$item['id'].'">История</a>';
			$data['rows'][$i]['cell'][] = $item['cena'];
			$data['rows'][$i]['cell'][] = $item['cena_blr'];
			$data['rows'][$i]['cell'][] = $item['date_create'];
			$data['rows'][$i]['cell'][] = $item['kolvo'];
			$data['rows'][$i]['cell'][] = '';			
			$i++;
		}

		echo json_encode($data);
	
	}	
	
	public function edit_tovarAction() {

		if (isset($_POST['action']))  {
		
			$data = array( 	 
				'id_price_monitoring' => $_POST['id_price_monitoring'],
				'id_competitors' => $_POST['id_competitors'],
				'link' => $_POST['link'],
				'cena' => $_POST['cena'],
				'cena_blr' => $_POST['cena_blr'],
				'date_create' => date('Y-m-d')
				);	


			if ($_POST['action']=='add') {	
				Database::insert($this->_table_c,$data);	
			}
			
			if ($_POST['action']=='edit') {	
				$where = 'id = '.$_POST['id'];
				Database::update($this->_table_c,$data,$where);	
			}
			
		}
		
		if (isset($_POST['id'])) {
			
			$logs = Database::getRows($this->_table_log,'id','desc',false,'table_name = "price_competitors" and id_table = '.$_POST['id']);				
			if (@$logs[0]['cena'] != $_POST['cena'] or @$logs[0]['cena_blr'] != $_POST['cena_blr']) {
			
				$data_log = array(	
					'table_name' => 'price_competitors',
					'id_table' => $_POST['id'],
					'cena' => $_POST['cena'],
					'cena_blr' => $_POST['cena_blr'],
					'date_create' => date('Y-m-d'),
					'time_create' => date('G:i')
				);			
				Database::insert($this->_table_log,$data_log);
			
			}
			
		}
		
		if (isset($_POST['oper']) and $_POST['oper']=="edit") {
		
			$data = array( 	 
				'cena' => $_POST['cena'],
				'cena_blr' => $_POST['cena_blr']
				);			
		
			$where = "id = ".$_POST['id'];
			Database::update($this->_table_c,$data,$where);	
						
		}		
		
		if (isset($_POST['oper']) and $_POST['oper']=="del") {
			Database::delete($this->_table_c,$_POST['id']);	
		}		
		
	}
	
	public function open_tovarAction() {
		$data = array();
		$id = $_POST['id'];
		$data = Database::getRow($this->_table_c,$id);
		echo json_encode($data);			
	}
		
	public function logs_tovarAction() {
		$data = array();
		$data = get_logs_by_table($_POST);
		echo json_encode($data);			
	}
			
	public function save_logsAction() {
		save_logs($_POST);	
	}
			
	public function kolvo_tovarAction() {
		$data = array();
		$id = $_POST['id'];
		$data = Database::getRow($this->_table_c,$id);
		$data['kolvo']++;
		Database::update($this->_table_c,array('kolvo' => $data['kolvo']),"id = $id");
		echo json_encode($data);			
	}
	
	
	
}