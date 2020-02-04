<?php

class Banners_small_Controller {
	private $_config, $_content, $_table, $_table_lng;

	public function __construct() {
		$this->_config = Config::getParam('modules->banners_small');
		$this->_table  = get_table('banners_small');			
		$this->_content['title'] = 'Баннеры левые';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view(
				'adminpanel/banners/list_small',array(
						'img_size' => $this->_config['image']['small']
				)
			);
   
		Render::layout('adminpanel/adminpanel', $this->_content);
   
	}
 
    public function loadAction() {
	
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
			$data['rows'][$i]['cell'][] = $item['path'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['prioritet'];
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
				
				$upload = Database::uploadImage ($_FILES['image'], $this->_config['image']);
				
				if ($upload['error']) {$error['image'] = $upload['error'];}
				
			}
			
			if (!$error) {	
				$data = array(	  
					'name' => $_POST['name'],			  			  	
					'image' => $upload['name'],			
					'active' => ((isset($_POST['active'])) ? 1 : 0),
					'path' => $_POST['path'],
					'prioritet' => $_POST['prioritet']
				);	
					
				Database::insert($this->_table,$data);
				
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
					'id' => $_POST['id'],  	  
					'name' => $_POST['name'],	
					'image' => ((isset($upload)) ? $upload['name'] : $item['image']),			
					'active' => ((isset($_POST['active'])) ? 1 : 0),
					'path' => $_POST['path'],
					'prioritet' => $_POST['prioritet']
				);	

				$where = "`id` = $id";
				Database::update($this->_table,$data,$where);				
		
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

			$item = Database::getRow($this->_table,$id);
			@unlink(ROOT.$this->_config['image']['small']['path'].$item['image']);
			@unlink(ROOT.$this->_config['image']['big']['path'].$item['image']);
		}		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);
		$data['url'] = $this->_config['image']['small']['path'].$data['image'];
		echo json_encode($data);
	}

}