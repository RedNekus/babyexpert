<?php

class Catalog_complect_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model('Catalog');
		$this->_table = get_table('catalog_complect');
	}

	
	public function defaultAction() {
		
	}
	
    public function loadAction() {
		// Начало формирование объекта
		$data = array();
			
		$id = @$_GET['id_catalog'];      		//Значение раздела или подраздела
		
		if (isset($id)) {

			$items = Database::getRows($this->_table,'id_razmer','asc',false,"id_catalog = $id");
			
			$i = 0;
			
			foreach($items as $item) {
				$product = Database::getRow(get_table('catalog'),$item['id_product']);
				if (empty($product)) continue;
				$data['rows'][$i]['id'] = $item['id'];
				$data['rows'][$i]['cell'][] = get_razmer_complect($item['id_razmer']);
				$data['rows'][$i]['cell'][] = get_product_name($product,true);
				$data['rows'][$i]['cell'][] = $product['cena'];
				$data['rows'][$i]['cell'][] = get_type_complect($item['type_complect']);
				$data['rows'][$i]['cell'][] = $item['kolvo'];
				$data['rows'][$i]['cell'][] = $item['skidka_usd'];			
				$i++;
			}

		} 

		echo json_encode($data);
	}
	
	public function editAction() {
		

		if (isset($_POST['action_complect']))   {

			$data = array(
				'type_complect' => $_POST['type_complect'],
				'kolvo' => $_POST['kolvo'],
				'id_razmer' => isset($_POST['id_razmer']) ? $_POST['id_razmer'] : 0,
				'skidka_usd' => $_POST['skidka_usd'],
				'skidka_blr' => $_POST['skidka_blr'],
				'doplata_usd' => $_POST['doplata_usd'],
				'doplata_blr' => $_POST['doplata_blr'],
				'skidka_roznica' => $_POST['skidka_roznica'],
				'skidka_roznica1' => $_POST['skidka_roznica1'],
				'skidka_diler1' => $_POST['skidka_diler1'],
				'skidka_diler2' => $_POST['skidka_diler2'],
				'skidka_diler3' => $_POST['skidka_diler3'],
			);
		
			if ($_POST['action_complect']=="add") {
				
				$data['id_product'] = $_POST['id_product'];
				$data['id_catalog'] = $_POST['id_catalog'];
				
				Database::insert($this->_table,$data);
			}
			
			if ($_POST['action_complect']=="edit") {
				$where = "id = ".$_POST['id'];
				Database::update($this->_table,$data,$where);
			}
	
		}
	
		// УДАЛИТЬ изображение
		if (isset($_POST['del_id_complect']))  {
			Database::delete($this->_table,$_POST['del_id_complect']);
		}
	}
	
	//Получение данных по id в форму для редактирования изображения
	public function openAction() {
  
		$items = array();
		
		$items = Database::getRow($this->_table,$_POST['id']);

		echo json_encode($items);
	
	}
	
	public function load_select_makerAction() {
		$data = array();
		$data['makers'] = get_select_menu_by_id_tree($_POST['id_tree']);
		$data['tovars'] = get_select_menu_by_id_maker($_POST['id_tree'],0);
		echo json_encode($data);		
	}	
	
	public function load_select_tovarAction() {
		$data = array();
		$data['tovars'] = get_select_menu_by_id_maker($_POST['id_tree'],$_POST['id_maker']);
		echo json_encode($data);
	}
  	
}