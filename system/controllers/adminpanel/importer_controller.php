<?php

class Importer_Controller {
	private $_table, $_content;

	public function __construct() {
		$this->_table = get_table('importer');
		$this->_content['title'] = 'Импортеры';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/importer/list');
   
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
			$data['rows'][$i]['cell'][] = $item['adres'];
			$data['rows'][$i]['cell'][] = $item['contact'];			
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');			
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {
	
		$data = array();
		
		if (isset($_POST['action'])) {

			$data = array( 
				'name' => $_POST['name'],
				'adres' => $_POST['adres'],
				'contact' => $_POST['contact'],
				'serv_centr' => $_POST['serv_centr'],
				'active' => ((isset($_POST['active'])) ? 1 : 0)
			);
			
		}
		
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {

			Database::insert($this->_table,$data);
	
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
		  
			$error = Array();
			$id = $_POST['id'];
			
			$where = "`id` = $id";
			Database::update($this->_table,$data,$where);

		}
		
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			$id = $_POST['del_id'];
			Database::delete($this->_table,$id);
		}	
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($data);
	}

}