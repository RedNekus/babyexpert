<?php

class Stat_price_monitoring_Controller {
	private $_content;

	public function __construct() {
		$this->_content['title'] = 'Статистика мониторинга цен';
		$this->_table = get_table('price_monitoring');
		$this->_table_tree = get_table('adminusers');
	}
	
	public function defaultAction() {
  
		$trees  = Database::getRows($this->_table_tree);

		$this->_content['content'] = Render::view(
				'adminpanel/stat/price_monitoring', array (
				'trees' => get_tree_adminusers($trees)
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

		$id_tree = @$_GET['id_tree'];
		$date_ot = @$_GET['date_ot'];
		$date_do = @$_GET['date_do'];
	
		$where = "1";
	
		if (!empty($id_tree)) $where .= ' and id_adminuser = '.$id_tree;		
		if (!empty($date_ot)) $where .= ' and date_create >= "'.$date_ot.'"';
		if (!empty($date_do)) $where .= ' and date_create <= "'.$date_do.'"';
		
		$count = Database::getCount($this->_table, $where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'], $where);
		
		$i = 0;
		$sum_count_con = 0;
		$sum_kolvo = 0;
		$sum_count_cena = 0;
		
		foreach($items as $item) {
		
			$product = Database::getRow(get_table('catalog'),$item['id_catalog']);
			$price_competitors = Database::getRows(get_table('price_competitors'),'id','asc',false,"id_price_monitoring = ".$item['id']);
			$kolvo = 0;
			$count_cena = 0;
			foreach($price_competitors as $pc) {
				$kolvo += $pc['kolvo'];	
				$logs = Database::getRows(get_table('logs'),'id','asc',false,'table_name = "price_competitors" and id_table = '.$pc['id']);
				$count_cena += count($logs);
			}
			$count_con = count($price_competitors);
			
			$sum_count_con += $count_con;
			$sum_kolvo += $kolvo;
			$sum_count_cena += $count_cena;
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = Database::getField(get_table('adminusers'),$item['id_adminuser'],'id','login');
			$data['rows'][$i]['cell'][] = get_product_name($product,true);
			$data['rows'][$i]['cell'][] = $count_con;
			$data['rows'][$i]['cell'][] = $kolvo;
			$data['rows'][$i]['cell'][] = $count_cena;
			$data['rows'][$i]['cell'][] = $item['date_create'];			
			$i++;

		}	

		$data['userdata']['count_con'] = $sum_count_con;
		$data['userdata']['kolvo'] = $sum_kolvo;
		$data['userdata']['count_cena'] = $sum_count_cena;

		echo json_encode($data);
		
	}   
	
}