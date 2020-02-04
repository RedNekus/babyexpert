<?php

class Catalog_razmer_Controller {
	private $_config, $_content, $_table;

	public function __construct() {
		$this->_config = Config::getParam('modules->catalog');
		$this->_table = get_table('catalog_razmer');
		$this->_table_razmer = get_table('razmer');
		$this->_table_description = get_table('description');
	}

	
	public function defaultAction() {
		
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

		$id = $_GET['id_catalog'];
		$where = "id_catalog = $id";
		
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		foreach($items as $item) {
	
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_razmer,$item['id_razmer'],'id','name');
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_description,$item['id_description'],'id','name');
			$data['rows'][$i]['cell'][] = $item['cena'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');			
			$i++;
		}
 
		echo json_encode($data);
	}
	
	public function editAction() {
	
		if ((!empty($_POST['tmp_length'])) or (!empty($_POST['tmp_width'])) or (!empty($_POST['tmp_height']))) {	
			$table = get_table('razmer');
			if (!empty($_POST['tmp_length'])) $length = $_POST['tmp_length'];
			if (!empty($_POST['tmp_width'])) $width = 'x'.$_POST['tmp_width'];
			if (!empty($_POST['tmp_height'])) $height = 'x'.$_POST['tmp_height'];
			$name = @$length.@$width.@$height;
			Database::insert($table,array('name' => $name));
			$id_razmer = Database::getLastId($table);
		} else {
			$id_razmer = $_POST['id_razmer'];
		}
		
		if (!empty($_POST['tmp_description'])) {
			$table = get_table('description');
			$name = $_POST['tmp_description'];
			Database::insert($table,array('name' => $name));
			$id_description = Database::getLastId($table);			
		} else {
			$id_description = $_POST['id_description'];
		}
	
		$data = array(
			'id_catalog' => $_POST['id_catalog'],
			'name' => $_POST['name'],
			'sufix' => $_POST['sufix'],
			'id_razmer' => $id_razmer,
			'id_description' => $id_description,
			'active' => ((isset($_POST['active'])) ? 1 : 0),
			'cena' => $_POST['cena'],		
			'cena_blr' => $_POST['cena_blr']		
		);
			
		// Добавить изображение
		if (isset($_POST['action']) and $_POST['action']=="add")  {
	
			Database::insert($this->_table,$data);
			
		}
	
		// Редактировать изображение
		if (isset($_POST['action']) and $_POST['action']=="edit")  {
	
			$id = $_POST['id'];		
			$where = "`id` = $id";
			Database::update($this->_table,$data,$where);			

		}
	
		// УДАЛИТЬ изображение
		if (isset($_POST['del_id_razmer']))  {
			$id = $_POST['del_id_razmer'];
			Database::delete($this->_table,$id);
		}
	}
	
	//Получение данных по id в форму для редактирования изображения
	public function openAction() {
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);

		$razmers = Database::getRows($this->_table_razmer,'name','asc',false);		
		$data['razmer_html'] = items_to_select_html($razmers,$data['id_razmer']);

		$descriptions = Database::getRows($this->_table_description,'name','asc',false);		
		$data['description_html'] = items_to_select_html($descriptions,$data['id_description']);
		echo json_encode($data);
	
	}	
	
	//Получение данных по id в форму для редактирования изображения
	public function newopenAction() {
		$data = array();
		$razmers = Database::getRows($this->_table_razmer,'name','asc',false);		
		$data['razmer_html'] = items_to_select_html($razmers,0);
		$descriptions = Database::getRows($this->_table_description,'name','asc',false);
		$data['description_html'] = items_to_select_html($descriptions,0);
		echo json_encode($data);	
	}
	
}