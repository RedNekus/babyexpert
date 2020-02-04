<?php

class Maker_Controller {
	private $_table, $_content;

	public function __construct() {
		$this->_table = get_table('maker');
		$this->_table_lng = get_table_lng('maker');	
		$this->_content['title'] = 'Бренды';
	}

	public function defaultAction() {
		
		$manufacturer = Database::getRows(get_table('manufacturer'));

		$importer = Database::getRows(get_table('importer'));
	
		$this->_content['content'] = Render::view('adminpanel/maker/list', array(
					'importer' => $importer,
					'manufacturer' => $manufacturer
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
			$data['rows'][$i]['cell'][] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = $item['country'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['path'];			
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
	
	}  
  
	public function editAction() {
	
		$data = array();
		$data_lng = array();
		
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
			$data = array(
				'title' => $_POST['title'],
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description'],		  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'short_description' => $_POST['short_description'],
				'path' => $_POST['path'],
				'country' => $_POST['country'],			
				'namefull' => $_POST['namefull'],
				'id_manufacturer' => $_POST['id_manufacturer'],
				'id_importer' => $_POST['id_importer']
			);	
			
			Database::insert($this->_table,$data);
				
			$id_maker_lng = Database::getLastId($this->_table);	

			$data_lng = array(
				'id_maker_lng' => $id_maker_lng,
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'],
				'namefull_lng' => $_POST['namefull_lng'],
				'country_lng' => $_POST['country_lng'],
				'short_description_lng' => $_POST['short_description_lng'],				
				'title_lng' => $_POST['title_lng'],
				'keywords_lng' => $_POST['keywords_lng'],
				'description_lng' => $_POST['description_lng']
			);	
			
			Database::insert($this->_table_lng,$data_lng);			
			
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
		  
			$error = Array();
			$id = $_POST['id'];
			$item = Database::getRow($this->_table,$id);
				
			$data = array( 
				'title' => $_POST['title'],
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description'],		  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'short_description' => $_POST['short_description'],
				'path' => $_POST['path'],
				'country' => $_POST['country'],			
				'namefull' => $_POST['namefull'],
				'id_manufacturer' => $_POST['id_manufacturer'],
				'id_importer' => $_POST['id_importer']
			);	
				
			$where = "`id` = $id";
			Database::update($this->_table,$data,$where);
			
			$data_lng = array(
				'id_maker_lng' => $_POST['id'],
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'],
				'namefull_lng' => $_POST['namefull_lng'],
				'country_lng' => $_POST['country_lng'],
				'short_description_lng' => $_POST['short_description_lng'],				
				'title_lng' => $_POST['title_lng'],
				'keywords_lng' => $_POST['keywords_lng'],
				'description_lng' => $_POST['description_lng']
			);	
			
			$id_lng = $_POST['id_lng'];
			$where_lng = "`id_lng` = $id_lng";
			Database::update($this->_table_lng,$data_lng,$where_lng);			
			
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			$id = $_POST['del_id'];
			Database::delete($this->_table,$id);
			Database::delete($this->_table_lng,$id,'id_maker_lng');
		}	
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$id_language = 2;
		$data = Database::getRow($this->_table,$_POST['id']);
		$data['language'] = Database::getRow($this->_table_lng,$_POST['id'],'id_maker_lng',$id_language);
		echo json_encode($data);
	}

}