<?php

class Adminusers_Controller {
	private $_table, $_content;

	public function __construct() {
		$this->_table = Config::getParam('modules->adminusers->table');
		$this->_content['title'] = 'Учетные данные';
	}

	public function defaultAction() {

		$adminacces = Database::getRows(get_table('adminaccess'));
		
		$couriers_tree = Database::getRows(get_table('kontragenty'),'id','asc',false,'id_tip = 5');
		$managers = Database::getRows(get_table('kontragenty'),'id','asc',false,'id_tip = 4');
		
		$kassa_tree = Database::getRows(get_table('kassa_tree'),'name','asc');
		
		$this->_content['content'] = Render::view(
					'adminpanel/adminusers/list',array(
						'adminacces' => $adminacces,	
						'couriers_tree' => $couriers_tree,	
						'managers' => $managers,	
						'kassa_tree' => $kassa_tree,	
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
			$data['rows'][$i]['cell'][] = $item['fio'];
			$data['rows'][$i]['cell'][] = $item['login'];
			$data['rows'][$i]['cell'][] = $item['email'];			
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');			
			$i++;

		}

		echo json_encode($data);
  
	}  
  
	public function editAction() {

		$data = array();
	  
		if (isset($_POST['action'])) {
			
			$id_kassa = implode(",", $_POST['id_kassa']);
			
			$data = array(
				'fio' => $_POST['fio'],	
				'id_access' => $_POST['id_access'],	
				'id_manager' => $_POST['id_manager'],	
				'id_courier' => $_POST['id_courier'],	
				'id_kassa' => $id_kassa,	
				'id_kassa_tree' => $_POST['id_kassa_tree'],	
				'login' => $_POST['login'],
				'email' => $_POST['email'],
				'active' => ((isset($_POST['active'])) ? 1 : 0)
			);	
			
			// ДОБАВИТЬ элемент в таблицу
			if ($_POST['action']=="add") {		
				$data['password'] = md5($_POST['password']);	
				Database::insert($this->_table,$data);
				
			}

			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="edit") {
				$adminusers = Database::getRow($this->_table,$_POST['id']);
				if ($_POST['password']==$adminusers['password']) {
					$passw = $_POST['password'];
				} else {
					$passw = md5($_POST['password']);
				}
				$data['password'] = $passw;
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