<?php

class Catalog_sklad_Controller {
	private $_table, $_content;

	public function __construct() {
		Load::model('Catalog');
		$this->_table = Config::getParam('modules->catalog->table');
		$this->_content['title'] = 'Склад товаров';
	}

	
	public function defaultAction() {
		$table_tree = get_table('catalog_tree');
		$where = 'pid = 1';
		$trees_limit = Database::getCount($table_tree,$where);
		$trees = Database::getRows($table_tree,'name','asc',$trees_limit,$where);
		
		$this->_content['content'] = Render::view('adminpanel/catalog_sklad/list',array('trees' => $trees));
		Render::layout('adminpanel/adminpanel', $this->_content);
		
	}
	
    public function loadAction() {
	
		// Начало формирование объекта
		$data = array();
		$params = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		
		

		$params['tip_catalog'] = @$_GET['id_tree'];		
		$params['id_maker'] = @$_GET['id_maker'];
		$params['id_tovar'] = @$_GET['id_tovar'];
		$params['sort'] = @$_GET['sidx'].'-'.@$_GET['sord'];
		
		$count = Catalog::getPodborAdmin($params, "", FALSE);
		
		$data = getPaginationAdmin($count,$limit,$page);

		$items = Catalog::getPodborAdmin($params, $data['limit'], TRUE);
		
		//$items_for_price = Catalog::getPodborAdmin($params, $count, TRUE);
		$total_price = 0;		
		//foreach($items_for_price as $ifp) {

		//	$id_item = $ifp['id'];

		//	$total_price += get_total_price($id_item);
					
		//}
		
		$i = 0;

		foreach($items as $item) {

			$id_item = $item['id'];

			$data['rows'][$i]['id'] = $id_item;		
			$data['rows'][$i]['cell'][] = Database::getField(get_table('prefix'),$item['id_prefix']);
			$data['rows'][$i]['cell'][] = Database::getField(get_table('maker'),$item['id_maker']);
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = $item['name_color'];
			$data['rows'][$i]['cell'][] = '<a href="/product/'.$item['path'].'" target="_ablank">просмотр</a>';
			$data['rows'][$i]['cell'][] = get_sklad_real_ostatok($id_item,1);
			$data['rows'][$i]['cell'][] = get_sklad_real_ostatok($id_item,2);
			$data['rows'][$i]['cell'][] = get_free_ostatok($id_item);
			$data['rows'][$i]['cell'][] = get_real_ostatok($id_item);
			$data['rows'][$i]['cell'][] = get_real_ostatok_sklad($id_item);
			$data['rows'][$i]['cell'][] = get_rezerv($id_item);
			$data['rows'][$i]['cell'][] = get_vozvrat_na_sklad($id_item);				
			$data['rows'][$i]['cell'][] = get_brak($id_item);				
			$data['rows'][$i]['cell'][] = get_dostavka_tovara($id_item);				
			$data['rows'][$i]['cell'][] = get_predzakaz($id_item);
			$data['rows'][$i]['cell'][] = get_ozhidaemiy_prihod($id_item);
			$i++;		
		}
		$data['userdata']['total_price'] = $total_price;	

		echo json_encode($data);
  
	}  
  	

	public function savepriceAction() {
		save_price_catalog_sklad();
	} 

	public function printostAction() {
		$data = array();
		$data['html'] = get_print_ost();
		echo json_encode($data);		
	}
  	
	public function refresh_ostatkiAction() {
		echo refresh_ostatki();	
	} 

	public function get_postavkiAction() {
		$data = array();
		if (isset($_POST['id'])) {
		$data['html'] = get_print_postavki($_POST['id']);
		}
		echo json_encode($data);		
	}
  	
	/**/
	
	
}