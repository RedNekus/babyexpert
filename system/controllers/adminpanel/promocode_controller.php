<?php

class Promocode_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Promocode'));
		$this->_config = Config::getParam('modules->promocode');
		$this->_content['title'] = 'Промокоды';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/promocode/list');
	   
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

		$count = Promocode::getTotalPromocode();  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) {
			$makers = Promocode::searchAdmin($searchField, $searchString);
		} else {
			$makers = Promocode::getPromocode($sidx, $sord, $data['limit']);
		}

		$i = 0;
		
		foreach($makers as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');	
			$data['rows'][$i]['cell'][] = (($item['active_raffle']!=0) ? 'Да' : 'Нет');	
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
	
	}  
  
	public function editAction() {

		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
			Promocode::addPage(array(	  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'active_raffle' => ((isset($_POST['active_raffle'])) ? 1 : 0)
			));	
		
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
			Promocode::updatePage(array(
				'id' => $_POST['id'],  	  
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'active_raffle' => ((isset($_POST['active_raffle'])) ? 1 : 0)
				));	
						
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Promocode::removePage($_POST['del_id']);
		}
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function datahandlingAction() {
		$promocode = array();
		$promocode = Promocode::getPageByID($_POST['id']);
		echo json_encode($promocode);
	}  
	
	//Получение данных по id в форму для добавления товара
	public function newelementAction() {
		
		
		for($i=0; $i<1000; $i++) {
			Promocode::addPage(array(	  
				'name' => rand_str(),			  		  		  
				'active' => 1,
				'active_raffle' => 1
			));			
		}
	}
	
	
	public function savetoxlsAction() {
				
		$list = array();
		$params = array();

		array_push($list,array(
			iconv('UTF-8', 'Windows-1251', 'Название'),
			iconv('UTF-8', 'Windows-1251', 'Активен')
			));		
		$params['sort'] = 'id-asc';
		$count = Promocode::getTotalPromocode();
		$collections = Promocode::getPromocode('id','ASC', $count);
		
		foreach($collections as $item) {
			$active = (($item['active']!=0) ? 'Да' : 'Нет');
			$active = iconv('UTF-8', 'Windows-1251', $active);			
			$active_raffle = (($item['active_raffle']!=0) ? 'Да' : 'Нет');
			$active_raffle = iconv('UTF-8', 'Windows-1251', $active_raffle);
			array_push($list,array(
					@$item['name'],
					$active,
					$active_raffle
					));			
		}
		outputCSV('assets/files/promocode.csv',$list);
		echo json_encode("Промокоды обновлены! <br/><a href='http://babyexpert.by/assets/files/promocode.csv'>Ссылка</a> для скачивания. <br/>http://babyexpert.by/assets/files/promocode.csv");				
	} 	
	

	
	
}