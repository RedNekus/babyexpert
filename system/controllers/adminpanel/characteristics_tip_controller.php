<?php

class Characteristics_tip_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model('Characteristics_group_tip');
	}

	
	public function defaultAction() {
		
	}
	
    public function loadAction() {
	// Начало формирование объекта
		$data = array();
			
		$id = @$_GET['id'];      		//Значение раздела или подраздела

		if (isset($id)) {
			
			$tip = Characteristics_group_tip::getTipsByIdCharacter($id);
			
			$i = 0;
				
				foreach($tip as $item) {
					$data['rows'][$i]['id'] = $item['id'];
					$data['rows'][$i]['cell'][] = $item['name'];			
					$i++;
				}
		} 

		echo json_encode($data);
	}
	
	public function editAction() {
		
		// Добавить префикс
		if (isset($_POST['action_tip']) and $_POST['action_tip']=="add")  {

			Characteristics_group_tip::addTip(array(
				'name' => $_POST['name'],
				'id_characteristics' => $_POST['id_characteristics']	
			));

		}
	
		// Редактировать префикс
		if (isset($_POST['action_tip']) and $_POST['action_tip']=="edit")  {

			Characteristics_group_tip::updateTip(array(
				'id' => $_POST['id'],
				'name' => $_POST['name'],
				'id_characteristics' => $_POST['id_characteristics']
			));

		}
	
		// УДАЛИТЬ префикс
		if (isset($_POST['del_id_tip']))  {
			Characteristics_group_tip::removeTip($_POST['del_id_tip']);
		}
	}
	

	//Получение данных по id в форму для редактирования префикса
	public function datahandlingAction() {
  
		$tip = array();
		
		$tip = Characteristics_group_tip::getTipByID($_POST['id']);
			
		echo json_encode($tip);
	
	} 
	
}