<?php

class Advantages_Controller {
	private $_config, $_content, $_table, $_table_lng;

	public function __construct() {
		$this->_config = Config::getParam('modules->advantages');
		$this->_table = get_table('advantages');
		$this->_table_lng = get_table_lng('advantages');		
		$this->_content['title'] = 'Наши преимущества';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/advantages/list');
		//$this->_content['content'] = "TEST";
	   
		Render::layout('adminpanel/adminpanel', $this->_content);
	   
	}
	private static function loadImage() {
		if(isset($_FILES['image_file'])) {
			$uploaddir = './assets/images/advantages';
			if(!is_dir($uploaddir))
				mkdir($uploaddir, 0777);
			$uploadfile = $uploaddir."/".basename($_FILES['image_file']['name']);
			copy($_FILES['image_file']['tmp_name'], $uploadfile);
		}
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
			$data['rows'][$i]['cell'][] = $item['title'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['prioritet'];
			$data['rows'][$i]['cell'][] = $item['timestamp'];
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {
	
		$data = array();
		$data_lng = array();
		
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
			$data = array(	  
				'title' => $_POST['title'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'description' => $_POST['description'],
				'image' => isset($_POST['image'])? $_POST['image'] : "/assets/images/advantages/".$_FILES['image_file']['name'],
				'prioritet' => $_POST['prioritet'],
				'timestamp' => $_POST['timestamp']
			);		
			
			Database::insert($this->_table,$data);
				
			$id_advantages_lng = Database::getLastId($this->_table);	
		
			$data_lng = array(
				'id_advantages_lng' => $id_advantages_lng,
				'id_language' => 2,
				'title_lng' => $_POST['title_lng'],
				'description_lng' => $_POST['description_lng'],				
				'title_lng' => $_POST['title_lng']
			);	
					
			Database::insert($this->_table_lng,$data_lng);
			self::loadImage();
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
		  
			$error = Array();
			$id = $_POST['id'];

			$data = array( 
				'title' => $_POST['title'],
				'description' => $_POST['description'],		  		  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'image' => isset($_POST['image'])? $_POST['image'] : "/assets/images/advantages/".$_FILES['image_file']['name'],
				'prioritet' => $_POST['prioritet'],
				'timestamp' => $_POST['timestamp']
			);	
				
			$where = "`id` = $id";
			Database::update($this->_table,$data,$where);
						
			$data_lng = array(
				'id_advantages_lng' => $id,
				'id_language' => 2,
				'title_lng' => $_POST['title_lng'],
				'description_lng' => $_POST['description_lng']
			);	
			
			$id_lng = $_POST['id_lng'];
			$where_lng = "`id_lng` = $id_lng";
			Database::update($this->_table_lng,$data_lng,$where_lng);
			self::loadImage();
		}
		
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			$id = $_POST['del_id'];
			Database::delete($this->_table,$id);
			Database::delete($this->_table_lng,$id,'id_advantages_lng');
		}	
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$id_language = 2;
		$data = Database::getRow($this->_table,$_POST['id']);
		$data['language'] = Database::getRow($this->_table_lng,$_POST['id'],'id_advantages_lng',$id_language);
		echo json_encode($data);
	}
}