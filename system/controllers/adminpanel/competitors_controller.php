<?php

class Competitors_Controller {
	private $_content, $_table;

	public function __construct() {
		$this->_table = Config::getParam('modules->competitors->table');
		$this->_content['title'] = 'Справочник конкуренты';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/reference/competitors');
	   
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
			$data['rows'][$i]['cell'][] = '<div>'.$item['phone'].'</div><div>'.$item['phone2'].'</div><div>'.$item['phone3'].'</div>';
			$data['rows'][$i]['cell'][] = '<div><a href="'.$item['site'].'" target="_ablank">'.$item['site'].'</a></div><div><a href="'.$item['site2'].'" target="_ablank">'.$item['site2'].'</a></div><div><a href="'.$item['site3'].'" target="_ablank">'.$item['site3'].'</a></div>';
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
				'phone' => $_POST['phone'],			  		  		  
				'phone2' => $_POST['phone2'],			  		  		  
				'phone3' => $_POST['phone3'],			  		  		  
				'site' => $_POST['site'],			  		  		  
				'site2' => $_POST['site2'],			  		  		  
				'site3' => $_POST['site3'],			  		  		  
				'unp' => $_POST['unp'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'description' => $_POST['description']
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