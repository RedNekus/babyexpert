<?php

class Kontragenty_tip_Controller {
	private $_content, $_table;

	public function __construct() {
		$this->_table = get_table('kontragenty_tip');
		$this->_content['title'] = 'Тип контрагентов';
	}

	public function defaultAction() {
		
		$tip_operations = Database::getRows(get_table('tip_operation'));
		
		$this->_content['content'] = Render::view(
			'adminpanel/reference/kontragenty_tip',array(
				'tip_operations' => $tip_operations
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
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['date_create'];
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {

		$data = array();
		
		if (isset($_POST['action'])) {
						
			$id_tip_operation = implode(",", $_POST['id_tip_operation']);
							
			$data = array(	  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'couriers_show' => ((isset($_POST['couriers_show'])) ? 1 : 0),
				'date_create' => $_POST['date_create'],
				'id_tip_operation' => $id_tip_operation
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
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($data);
	}

}