<?php

class Log_sell_Controller {
	private $_table, $_content;

	public function __construct() {
		$this->_table = get_table('log_sell');
		$this->_content['title'] = 'Лог продаж';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/log_sell/list');
   
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
			$data['rows'][$i]['cell'][] = $item['id_client'];
			$data['rows'][$i]['cell'][] = $item['id_item'];
			$data['rows'][$i]['cell'][] = $item['kolvo'];		
			$data['rows'][$i]['cell'][] = date('d.m.Y H:i:s',$item['date_create']);			
			$data['rows'][$i]['cell'][] = Database::getField(get_table('adminusers'),$item['id_adminuser'],'id','fio');					
			$i++;

		}

		echo json_encode($data);
	
	} 

		/*
		Поиск дубликатов
		
		SELECT * 
			FROM `np_log_sell` 
			WHERE CONCAT( `id_item`,`id_client` ) 
			IN (

			SELECT CONCAT( `id_item`, `id_client` ) AS x
			FROM `np_log_sell`
			GROUP BY x
			HAVING COUNT( x ) >1
			)

		*/
  
}