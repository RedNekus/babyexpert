<?php

class Article_Controller {
	private $_config, $_content, $_table, $_table_lng;

	public function __construct() {
		$this->_config = Config::getParam('modules->article');
		$this->_table = get_table('article');
		$this->_table_lng = get_table_lng('article');
		$this->_content['title'] = 'Статьи сайта';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/article/list');
   
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
			
			$error = Array();
			
			if ($_FILES['image']['name']) {  
			
				$upload = Database::uploadImage($_FILES['image'], $this->_config['image']);

				if ($upload['error']) {$error['image'] = $upload['error'];}
			
			}
			
			if (!$error) {	
			
				$data = array(
					'title' => $_POST['title'],
					'keywords' => $_POST['keywords'],
					'description' => $_POST['description'],		  
					'name' => $_POST['name'],			  	
					'image' => $upload['name'],			
					'active' => ((isset($_POST['active'])) ? 1 : 0),
					'short_description' => $_POST['short_description'],
					'path' => $_POST['path'],
					'prioritet' => $_POST['prioritet'],			
					'namefull' => $_POST['namefull'],
					'timestamp' => $_POST['timestamp']
				);	
			
				Database::insert($this->_table,$data);
			
				$id_article_lng = Database::getLastId($this->_table);	
			
				$data_lng = array(
					'id_article_lng' => $id_article_lng,
					'id_language' => 2,
					'name_lng' => $_POST['name_lng'],
					'namefull_lng' => $_POST['namefull_lng'],
					'short_description_lng' => $_POST['short_description_lng'],				
					'title_lng' => $_POST['title_lng'],
					'keywords_lng' => $_POST['keywords_lng'],
					'description_lng' => $_POST['description_lng']
				);		
					
				Database::insert($this->_table_lng,$data_lng);
				
			}
	
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
		  
			$error = Array();
			$id = $_POST['id'];
			$item = Database::getRow($this->_table,$id);

			if ($_FILES['image']['name']) {  
			
				$upload = Database::uploadImage($_FILES['image'], $this->_config['image']);

				if ($upload['error']) {$error['image'] = $upload['error'];}
			
			}

			if (!$error) {
			
				$data = array( 
					'title' => $_POST['title'],
					'keywords' => $_POST['keywords'],
					'description' => $_POST['description'],		  
					'name' => $_POST['name'],	
					'image' => ((isset($upload)) ? $upload['name'] : $item['image']),			
					'active' => ((isset($_POST['active'])) ? 1 : 0),
					'short_description' => $_POST['short_description'],
					'path' => $_POST['path'],
					'prioritet' => $_POST['prioritet'],			
					'namefull' => $_POST['namefull'],
					'timestamp' => $_POST['timestamp']
				);	
				
				$where = "`id` = $id";
				Database::update($this->_table,$data,$where);
				
				$data_lng = array(
					'id_article_lng' => $id,
					'id_language' => 2,
					'name_lng' => $_POST['name_lng'],
					'namefull_lng' => $_POST['namefull_lng'],
					'short_description_lng' => $_POST['short_description_lng'],				
					'title_lng' => $_POST['title_lng'],
					'keywords_lng' => $_POST['keywords_lng'],
					'description_lng' => $_POST['description_lng']
				);	
				
				$id_lng = $_POST['id_lng'];
				$where_lng = "`id_lng` = $id_lng";
				Database::update($this->_table_lng,$data_lng,$where_lng);
				
				if (isset($upload)) {
					@unlink(ROOT.$this->_config['image']['small']['path'].$item['image']);
					@unlink(ROOT.$this->_config['image']['big']['path'].$item['image']);
				}				
			}

		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			$id = $_POST['del_id'];
			Database::delete($this->_table,$id);
			Database::delete($this->_table_lng,$id,'id_article_lng');
			
			$item = Database::getRow($this->_table,$id);
			@unlink(ROOT.$this->_config['image']['small']['path'].$item['image']);
			@unlink(ROOT.$this->_config['image']['big']['path'].$item['image']);
		}	
	
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$id_language = 2;
		$data = Database::getRow($this->_table,$_POST['id']);
		$data['url'] = $this->_config['image']['small']['path'].$data['image'];
		$data['language'] = Database::getRow($this->_table_lng, $_POST['id'], 'id_article_lng', $id_language);
		echo json_encode($data);
	}

}