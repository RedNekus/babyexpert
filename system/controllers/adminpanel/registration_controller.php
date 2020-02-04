<?php

class Registration_Controller {
  private $_table, $_content;

	public function __construct() {
		$this->_table = get_table('registration');
		$this->_content['title'] = 'Пользователи';
	}

	public function defaultAction() {

		$table_adminusers = get_table('adminusers');
		$adminusers = Database::getRows($table_adminusers,'login','asc',Database::getCount($table_adminusers));
	  
		$kassa_tree = Database::getRows(get_table('kassa_tree'),'name','asc');
		
		$this->_content['content'] = Render::view(
			'adminpanel/registration/list',array(
				'adminusers' => $adminusers,
				'kassa_tree' => $kassa_tree
			)
		);
		
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
			$data['rows'][$i]['cell'][] = $item['login'];
			$data['rows'][$i]['cell'][] = $item['phone'];
			$data['rows'][$i]['cell'][] = $item['email'];			
			$data['rows'][$i]['cell'][] = Database::getField(get_table('kassa_tree'),$item['id_kassa_tree']);			
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');			
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {

		$data = array();
			
		if (isset($_POST['action'])) {
			
			$data = array(		  
				'manager' => $_POST['manager'],
				'diler' => $_POST['diler'],
				'id_adminuser' => $_POST['id_adminuser'],
				'pass' => md5($_POST['pass']),
				'login' => $_POST['login'],	
				'name' => $_POST['name'],	
				'email' => $_POST['email'],
				'phone' => $_POST['phone'],				
				'street' => $_POST['street'],
				'house' => $_POST['house'],
				'building' => $_POST['building'],
				'apartment' => $_POST['apartment'],
				'entrance' => $_POST['entrance'],
				'floor' => $_POST['floor'],	
				'id_kassa_tree' => $_POST['id_kassa_tree'],	
				'active' => ((isset($_POST['active'])) ? 1 : 0)
			);
			
			// ДОБАВИТЬ элемент в таблицу			
			if ($_POST['action']=="add") {
				Database::insert($this->_table,$data);	
			}			
			
			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="edit") {
				$registration = Database::getRow($this->_table,$_POST['id']);
				if ($_POST['pass']==$registration['pass']) $data['pass'] = $_POST['pass']; 
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