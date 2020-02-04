<?php

class Catalog_prefix_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model('Prefix');
	}

	
	public function defaultAction() {
		
	}
	
    public function loadAction() {
	// Начало формирование объекта
		$data = array();
			
		$id = @$_GET['prefix_id'];      		//Значение раздела или подраздела

		if (isset($id)) {
			
			$prefix = Prefix::getPrefixByIdCatalog($id);
			
			$i = 0;
				
				foreach($prefix as $item) {
					$data['rows'][$i]['id'] = $item['id'];
					$data['rows'][$i]['cell'][] = $item['name'];
					$data['rows'][$i]['cell'][] = (($item['baza']!=0) ? 'Да' : 'Нет');			
					$i++;
				}
		} 

		echo json_encode($data);
	}
	
	public function editAction() {
		
		// Добавить префикс
		if (isset($_POST['action_prefix']) and $_POST['action_prefix']=="add")  {

			Prefix::addPrefix(array(
				'name' => $_POST['name'],
				'id_tree' => $_POST['id_tree'],
				'baza' => ((isset($_POST['baza'])) ? 1 : 0)	
			));

		}
	
		// Редактировать префикс
		if (isset($_POST['action_prefix']) and $_POST['action_prefix']=="edit")  {

			Prefix::updatePrefix(array(
				'id' => $_POST['id_prefix'],
				'name' => $_POST['name'],
				'id_tree' => $_POST['id_tree'],				
				'baza' => ((isset($_POST['baza'])) ? 1 : 0)	
			));

		}
	
		// УДАЛИТЬ префикс
		if (isset($_POST['del_id_prefix']))  {
			Prefix::removePrefix($_POST['del_id_prefix']);
		}
	}
	

	//Получение данных по id в форму для редактирования префикса
	public function datahandlingAction() {
  
		$prefix = array();
		
		$prefix = Prefix::getPrefixByID($_POST['id']);
			
		echo json_encode($prefix);
	
	} 
	
}