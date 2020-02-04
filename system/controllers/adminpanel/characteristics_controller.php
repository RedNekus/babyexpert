<?php

class Characteristics_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Characteristics', 'Characteristics_language', 'Characteristics_tree', 'Characteristics_group', 'Characteristics_group_language', 'Characteristics_group_tip'));
		$this->_config = Config::getParam('modules->characteristics');
		$this->_content['title'] = 'Характеристики товаров';
	}

	
	public function defaultAction() {
		$this->listAction();
	}
  
  
	public function listAction() {
  
		$trees  = Characteristics_tree::getTrees();
	
		$this->_content['content'] = Render::view(
				'adminpanel/characteristics/list', array (
				'trees' => get_tree_characteristics($trees),
				'access' => get_array_access(),
				'id_group' => Characteristics::getCollectionByID(1)
				)
			);
		Render::layout('adminpanel/adminpanel', $this->_content);
	}

	
    public function loadAction() {
	
		// Начало формирование объекта
		$data = array();

		$data['page']       = "";
		$data['total']      = "";
		$data['records']    = "";
		
		$id = @$_GET['id'];
		if (@$id) {
		
			$productions = Characteristics_group::getCollections($id);

			$i = 0;
			foreach($productions as $item) {

				$data['rows'][$i]['id'] = $item['id'];
				$data['rows'][$i]['cell'][] = "<b>".$item['name']."</b>";
				$data['rows'][$i]['cell'][] = "";
				$data['rows'][$i]['cell'][] = "";
				$data['rows'][$i]['cell'][] = "";
				$data['rows'][$i]['cell'][] = "";
				$data['rows'][$i]['cell'][] = "";
				$data['rows'][$i]['cell'][] = "<b>".(($item['active']!=0) ? 'Да' : 'Нет')."</b>";
				$data['rows'][$i]['cell'][] = "<b>".$item['prioritet']."</b>";		
				$i++;

				}			
		}
		
		echo json_encode($data);
		
	}  
  
 
	public function subloadAction() {
	
		// Начало формирование объекта
		$data = array();
		$data['page']       = "";
		$data['total']      = "";
		$data['records']    = "";
		
		$id = str_replace("grid","",$_POST['id']);
		$productions = Characteristics::getCollections($id);

		$i = 0;
		foreach($productions as $item) {

			if ($item['tip']==1) $tip = "Текст";
			if ($item['tip']==2) $tip = "Переключатель (есть/нет)";
			if ($item['tip']==3) $tip = "Выбор одного значения";
			if ($item['tip']==4) $tip = "Выбор нескольких значений";		
		
			if ($item['filtr']==0) $filtr = "Нет";
			if ($item['filtr']==1) $filtr = "Левая колонка";
			if ($item['filtr']==2) $filtr = "Правая колонка";
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];		
			$data['rows'][$i]['cell'][] = $tip;
			$data['rows'][$i]['cell'][] = $filtr;
			$data['rows'][$i]['cell'][] = (($item['filtr_toolbar']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['prioritet_filtra'];
			$data['rows'][$i]['cell'][] = (($item['show_catalog']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['prioritet'];			
			$i++;

		}

		echo json_encode($data);
	}  
    
	public function editAction() {
  
/********************************************************/
/*		Редатирование модуля группы характеристик		*/  
/********************************************************/  
	
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action_group']) and $_POST['action_group']=="add") {
			Characteristics_group::addCollection(array(
				'id_tree' => $_POST['id_tree'],			  
				'name' => $_POST['name'],			  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'name_vision' => ((isset($_POST['name_vision'])) ? 1 : 0),
				'prioritet' => $_POST['prioritet']
			));	

			$id_char_group_lng = Characteristics_group::getLastId();

			Characteristics_group_language::addCharGroup(array(
				'id_char_group_lng' => $id_char_group_lng,
				'id_language' => 2,				
				'name_lng' => $_POST['name_lng'],	
			));
			
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action_group']) and $_POST['action_group']=="edit") {
			Characteristics_group::updateCollection(array(
				'id' => $_POST['id'],  			
				'id_tree' => $_POST['id_tree'],				
				'name' => $_POST['name'],			  		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'name_vision' => ((isset($_POST['name_vision'])) ? 1 : 0),
				'prioritet' => $_POST['prioritet']
			));	

			if (empty($_POST['id_lng'])) {
				Characteristics_group_language::addCharGroup(array(
					'id_char_group_lng' => $_POST['id'],
					'id_language' => 2,	  
					'name_lng' => $_POST['name_lng']
				));				
			} else {
				Characteristics_group_language::updateCharGroup(array(
					'id_lng' => $_POST['id_lng'],
					'id_char_group_lng' => $_POST['id'],
					'id_language' => 2,		  
					'name_lng' => $_POST['name_lng']
				));			
			}
			
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id_characteristics']))  {
			$id = str_replace("grid","",$_POST['del_id_characteristics']);
			Characteristics_group::removeCollection($id);
		}	
		
/********************************************************/
/*			Редатирование модуля характеристики			*/
/********************************************************/
	
		// Добавить элемент в таблицу
		if (isset($_POST['action_сharacteristics']) and $_POST['action_сharacteristics']=="add")  {

			Characteristics::addCharacteristics(array(
				'name' => $_POST['name'], 	
				'prefix' => $_POST['prefix'], 
				'prioritet' => $_POST['prioritet'],	
				'sufix' => $_POST['sufix'], 	
				'prioritet_filtra' => $_POST['prioritet_filtra'],
				'id_groupe' => $_POST['id_groupe'],	
				'show_catalog' => ((isset($_POST['show_catalog'])) ? 1 : 0), 
				'active' => ((isset($_POST['active'])) ? 1 : 0),	
				'tip' => $_POST['tip'],
				'filtr' => $_POST['filtr'],
				'tip_search' => $_POST['tip_search'],
				'filtr_toolbar' => ((isset($_POST['filtr_toolbar'])) ? 1 : 0),				
				'valcharacter' => ((isset($_POST['valcharacter'])) ? 1 : 0)				
			));	

			$id_char_lng = Characteristics::getLastId();
			
			Characteristics_language::addCharacteristics(array(
				'id_char_lng' => $id_char_lng,
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'], 	
				'prefix_lng' => $_POST['prefix_lng'],	
				'sufix_lng' => $_POST['sufix_lng']
			));
		}	

		// Редактировать элемент в таблице
		if (isset($_POST['action_сharacteristics']) and $_POST['action_сharacteristics']=="edit")  {

			Characteristics::updateCharacteristics(array(
				'id'  => $_POST['id'],
				'name' => $_POST['name'], 	
				'prefix' => $_POST['prefix'],
				'prioritet' => $_POST['prioritet'],	
				'sufix' => $_POST['sufix'], 	
				'prioritet_filtra' => $_POST['prioritet_filtra'],
				'id_groupe' => $_POST['id_groupe'],	
				'show_catalog' => ((isset($_POST['show_catalog'])) ? 1 : 0), 
				'active' => ((isset($_POST['active'])) ? 1 : 0),	
				'tip' => $_POST['tip'],
				'filtr' => $_POST['filtr'],
				'tip_search' => $_POST['tip_search'],
				'filtr_toolbar' => ((isset($_POST['filtr_toolbar'])) ? 1 : 0),				
				'valcharacter' => ((isset($_POST['valcharacter'])) ? 1 : 0)				
				));	

			if (empty($_POST['id_lng'])) {
				Characteristics_language::addCharacteristics(array(
					'id_char_lng' => $_POST['id'],
					'id_language' => 2,	  
					'name_lng' => $_POST['name_lng'],			  
					'prefix_lng' => $_POST['prefix_lng'],	
					'sufix_lng' => $_POST['sufix_lng']
				));				
			} else {
				Characteristics_language::updateCharacteristics(array(
					'id_lng' => $_POST['id_lng'],
					'id_char_lng' => $_POST['id'],
					'id_language' => 2,		  
					'name_lng' => $_POST['name_lng'],			  
					'prefix_lng' => $_POST['prefix_lng'],	
					'sufix_lng' => $_POST['sufix_lng']
				));			
			}
			
		}

		// Удалить элемент из таблицы
		if (isset($_POST['del_id_char']))  {
			Characteristics::removeCharacteristics($_POST['del_id_char']);
			Characteristics_language::removeCharacteristics($_POST['del_id_char']);
		}
		
/********************************************************/
/*			Редатирование модуля дерево разделов		*/
/********************************************************/
	
		// Добавить элемент в ДЕРЕВО
		if (isset($_POST['action_tree']) and $_POST['action_tree']=="add")  {

			Characteristics_tree::addTree(array(
				'name' => $_POST['name']
			));	
			
			$trees = array();
			$trees  = get_tree_characteristics(Characteristics_tree::getTrees());
			echo json_encode($trees);
		}	

		// Редактировать элемент в ДЕРЕВЕ
		if (isset($_POST['action_tree']) and $_POST['action_tree']=="edit")  {

			Characteristics_tree::updateTree(array(
				'id'  => $_POST['id'],
				'name' => $_POST['name']				
				));		
			
			$trees = array();
			$trees  = get_tree_characteristics(Characteristics_tree::getTrees());
			echo json_encode($trees);
		}

		// Удалить элемент в ДЕРЕВЕ
		if (isset($_POST['del_id_tree']))  {
			Characteristics_tree::removeTree($_POST['del_id_tree']);
			
			$trees = array();
			$trees  = get_tree_characteristics(Characteristics_tree::getTrees());
			echo json_encode($trees);
		}		
	
	}
	
 	//Получение данных по id в форму для редактирования раздела
	public function addselectitemsAction() {
	  
		$data = array();

		$i = 0;
		foreach(Characteristics_group::getCollections($_POST['id']) as $item) {

			$data[$i]['id'] = $item['id'];
			$data[$i]['name'] = $item['name'];			
			$i++;

		}			
					
		echo json_encode($data);
		
	}
	
	//Получение данных по id в форму для редактирования раздела
	public function datatreehandlingAction() {
	  
		$trees = array();
			
		$trees = Characteristics_tree::getTreeByID($_POST['id']);
	
		echo json_encode($trees);
		
	}   
 
	//Получение данных по id в форму для редактирования товара
	public function datahandlingAction() {
  
		$characteristics_group = array();
		
		$id = str_replace("grid","",$_POST['id']);		
		
		$characteristics_group = Characteristics_group::getCollectionByID($id);
		
		$characteristics_group['language'] = Characteristics_group_language::getCharGroupByIdLng($id);
			
		echo json_encode($characteristics_group);
	
	}

 
	//Получение данных по id в форму для редактирования товара
	public function datahandlingcharAction() {
  
		$characteristics = array();
		
		$characteristics = Characteristics::getCollectionByID($_POST['id']);
		
		$characteristics['language'] = Characteristics_language::getCharacteristicsByIdLng($_POST['id']);
				
		echo json_encode($characteristics);
	
	}
	
	//Получение данных по id в форму для редактирования изображения
	public function nextitemcharacteristicsAction() {
  
		$item = Characteristics::getNextId();
		
		$id_catalog = $item['auto_increment'];					
			
		echo $id_catalog;
	
	}	

 
}