<?php

class Zpmanager_Controller {
	private $_content, $_table;

	public function __construct() {
		$this->_table = get_table('zpmanager');
		$this->_content['title'] = 'Справочник ЗП менеджерам';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/reference/zpmanager');
	   
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
			$data['rows'][$i]['cell'][] = $item['cena_ot'].' - '.$item['cena_do'];
			$data['rows'][$i]['cell'][] = $item['zp'];
			$data['rows'][$i]['cell'][] = $item['r_delta'];
			$data['rows'][$i]['cell'][] = $item['zp_procent'];
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {

		$data = array();
		
		
		if (isset($_POST['action'])) {
			$data = array(	  
				'cena_ot' => $_POST['cena_ot'],			  		  		  
				'cena_do' => $_POST['cena_do'],		  		  		  
				'zp' => $_POST['zp'],		  		  		  
				'r_delta' => $_POST['r_delta'],		  		  		  
				'zp_procent' => $_POST['zp_procent']		  		  		  
			);
			
			// ДОБАВИТЬ элемент в таблицу
			if ($_POST['action']=="add") {
				Database::insert($this->_table,$data);					
			}
			
			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="edit") {
				$where = '`id` = '.$_POST['id']; 
				Database::update($this->_table,$data,$where);	
			}			
		}		

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Database::delete($this->_table,$_POST['del_id']);
		}	
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$date = array();
		$date = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($date);
	}

}