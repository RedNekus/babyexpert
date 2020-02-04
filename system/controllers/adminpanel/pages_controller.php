<?php

class Pages_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Pages','Pages_language'));
		$this->_config = Config::getParam('modules->pages');
		$this->_content['title'] = 'Страницы сайта';
	}

	public function defaultAction() {
		$this->listAction();
	}
  
	public function listAction() {
	
		$this->_content['content'] = Render::view('adminpanel/pages/list');
	   
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

		$count = Pages::getTotalPages();  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) {
			$makers = Pages::searchAdmin($searchField, $searchString);
		} else {
			$makers = Pages::getPages($sidx, $sord, $data['limit']);
		}

		$i = 0;
		foreach($makers as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['prioritet'];			
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
  }  
  
	public function editAction() {

		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
			Pages::addPage(array(
				'title' => $_POST['title'],
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description'],		  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'short_description' => $_POST['short_description'],
				'path' => $_POST['path'],
				'prioritet' => $_POST['prioritet'],			
				'namefull' => $_POST['namefull']
			));	
				
			$id_pages_lng = Pages::getLastId();	
		
			Pages_language::addPage(array(
				'id_pages_lng' => $id_pages_lng,
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'],
				'namefull_lng' => $_POST['namefull_lng'],
				'short_description_lng' => $_POST['short_description_lng'],				
				'title_lng' => $_POST['title_lng'],
				'keywords_lng' => $_POST['keywords_lng'],
				'description_lng' => $_POST['description_lng']
			));	
			
			
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
			Pages::updatePage(array(
				'id' => $_POST['id'],  
				'title' => $_POST['title'],
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description'],		  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'short_description' => $_POST['short_description'],
				'path' => $_POST['path'],
				'prioritet' => $_POST['prioritet'],			
				'namefull' => $_POST['namefull']
				));	
		
			Pages_language::updatePage(array(
				'id_lng' => $_POST['id_lng'],
				'id_pages_lng' => $_POST['id'],
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'],
				'namefull_lng' => $_POST['namefull_lng'],
				'short_description_lng' => $_POST['short_description_lng'],				
				'title_lng' => $_POST['title_lng'],
				'keywords_lng' => $_POST['keywords_lng'],
				'description_lng' => $_POST['description_lng']
			));					
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Pages::removePage($_POST['del_id']);
			Pages_language::removePage($_POST['del_id']);
		}
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function datahandlingAction() {
		$pages = array();
		$pages = Pages::getPageByID($_POST['id']);
		$pages['language'] = Pages_language::getPageByIdLng($pages['id']);
		echo json_encode($pages);
	}

}