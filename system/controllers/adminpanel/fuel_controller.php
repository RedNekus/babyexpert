<?php

class Fuel_Controller {
	private $_config, $_content, $_table;

	public function __construct() {
		$this->_config = Config::getParam('modules->fuel');
		$this->_content['title'] = 'Расход топлива';
		$this->_table = get_table('fuel');
		$this->_table_tree = get_table('fuel_tree');	
		$this->_table_kon = get_table('kontragenty');		
	}

	public function defaultAction() {
		
		$couriers  = Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 5');
		$trees  = Database::getRows($this->_table_tree);

		$this->_content['content'] = Render::view(
			'adminpanel/fuel/list', array (
				'couriers' => $couriers,
				'trees' => get_tree_characteristics($trees),
				'access' => get_array_access()
				));
	   
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

		$where = "1";
		
		$access = get_array_access();
		if ($access['id']!=1) $id_courier = (@$_GET['id_courier']) ? $_GET['id_courier'] : $access['id_courier'];	
		else $id_courier = (@$_GET['id_courier']) ? $_GET['id_courier'] : '';
		$id_tree = (@$_GET['id_tree']) ? $_GET['id_tree'] : '';
		$date_ot = @$_GET['date_ot'];
		$date_do = @$_GET['date_do'];

		if (!empty($id_courier)) $where .= ' and id_courier = '.$id_courier;		
		if (!empty($id_tree)) $where .= ' and id_tree = '.$id_tree;		
		if (!empty($date_ot)) $where .= ' and date_create >= "'.$date_ot.'"';
		if (!empty($date_do)) $where .= ' and date_create <= "'.$date_do.'"';

		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'],$where);
		
		$total_km = 0;		
		$total_cena = 0;		
		$i = 0;
		foreach($items as $item) {
			
				$rashod = Database::getField($this->_table_tree,$item['id_tree'],'id','rashod');
				$total_km += $item['km'];
				$total_cena += ($rashod / 100) * $item['km'] * $item['cena'];
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_kon,$item['id_courier']);			
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_tree,$item['id_tree']);
			$data['rows'][$i]['cell'][] = $item['km'];			
			$data['rows'][$i]['cell'][] = $item['cena'];
			$data['rows'][$i]['cell'][] = $item['comment'];
			$data['rows'][$i]['cell'][] = norm_date($item['date_create']);
			$i++;

		}
		
		$data['userdata']['km'] = $total_km;
		$data['userdata']['total_cena'] = $total_cena;
		
		echo json_encode($data);
	
	}  
  
	public function editAction() {

		$data = array();
		
		if (isset($_POST['action'])) {
			$data = array(	  
				'km' => $_POST['km'],			  		  		  
				'id_tree' => $_POST['id_tree'],			  		  		  
				'id_courier' => $_POST['id_courier'],			  		  		  
				'cena' => $_POST['cena'],			  		  		  
				'comment' => $_POST['comment'],			  		  		  
				'date_create' => (!empty($_POST['date_create']) ? $_POST['date_create'] : date('Y-m-d'))
			);	
		
			// ДОБАВИТЬ элемент в таблицу
			if ($_POST['action']=="add") {
				Database::insert($this->_table,$data);					
			}

			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="edit") {
				$where = '`id` = '.$_POST['id']; 
				Database::update($this->_table,$data,$where);		
			}
			
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Database::delete($this->_table,$_POST['del_id']);
		}	

/********************************************************/
/*			Редатирование модуля дерево порталов		*/
/********************************************************/
		if (isset($_POST['action_tree'])) {
			$data_tree = array(
				'name' => $_POST['name'],							
				'rashod' => $_POST['rashod']				
			);			
		}
		
		// Добавить элемент в ДЕРЕВО
		if (isset($_POST['action_tree']) and $_POST['action_tree']=="add")  {
			Database::insert($this->_table_tree,$data_tree);
		}	

		// Редактировать элемент в ДЕРЕВЕ
		if (isset($_POST['action_tree']) and $_POST['action_tree']=="edit")  {
			$where = 'id = '.$_POST['id'];
			Database::update($this->_table_tree,$data_tree,$where);
		}

		// Удалить элемент в ДЕРЕВЕ
		if (isset($_POST['del_id_tree']))  {
			Database::delete($this->_table_tree,$_POST['del_id_tree']);
		}	
		
		$trees = array();
		$trees  = get_tree_characteristics(Database::getRows($this->_table_tree));
		echo json_encode($trees);
					
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();		
		$data = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($data);
	}
	
	public function open_lastAction() {
		$data = array();		
		if (Database::getCount($this->_table)>0) {
			$last_id = Database::getLastId($this->_table);
			$data = Database::getRow($this->_table,$last_id);
			echo json_encode(@$data['cena']);
		}
	}
	
	//Получение данных по id в форму для редактирования раздела
	public function open_treeAction() {
		$data = array();
		$data = Database::getRow($this->_table_tree,$_POST['id']);
		echo json_encode($data);
	}   
  
}