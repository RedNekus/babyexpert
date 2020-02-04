<?php

class Valute_Controller {
	private $_content, $_table;

	public function __construct() {
		$this->_table = get_table('valute');
		$this->_content['title'] = 'Справочник перевозчики';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/reference/valute');
	   
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
			$data['rows'][$i]['cell'][] = $item['date_create'];
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {

		$data = array();
		
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
			$data = array(	  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'date_create' => $_POST['date_create']
			);
		
			Database::insert($this->_table,$data);					
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
			$data = array( 	  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'date_create' => $_POST['date_create']
				);
				
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
		$valute = array();
		$valute = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($valute);
	}

}