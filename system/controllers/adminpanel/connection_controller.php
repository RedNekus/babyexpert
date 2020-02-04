<?php

class Connection_Controller {
	private $_config, $_content;

	public function __construct() {
		$this->_content['title'] = 'Связь';
		$this->_table = get_table('connection');
		$this->_table_tree = get_table('connection_tree');
	}
	
	public function defaultAction() {
  
		$trees  = Database::getRows($this->_table_tree);
		$makers = Database::getRows(get_table('maker'),'name','asc');
	
		$this->_content['content'] = Render::view(
				'adminpanel/connection/list', array (
				'trees' => get_tree_characteristics($trees),
				'portals' => $trees,
				'makers' => $makers,
				'access' => get_array_access(),
				)
			);
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

		$id_tree  = (@$_GET['id']) ? @$_GET['id'] : 0;
		
		if (isset($id_tree)) $where = "id_tree = $id_tree";
		else $where = "1";

		$count = Database::getCount($this->_table, $where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'], $where);
		
		$i = 0;
		foreach($items as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name_portal'];	
			$data['rows'][$i]['cell'][] = $item['name_site'];	
			$data['rows'][$i]['cell'][] = (($item['active']==1) ? 'Да' : 'Нет');	
			$i++;

		}			
		
		echo json_encode($data);
		
	}   
    
	public function editAction() {
  
/********************************************************/
/*		Редатирование модуля связей с порталами 		*/  
/********************************************************/  

		if (isset($_POST['import_action']) and $_POST['import_action']=="add") {
			if (isset($_FILES['secondXLS']['tmp_name'])) {	
				$uploaddir = 'assets/files/';			
				$uploadfileupdate = $uploaddir.basename('xls_portal.xls');					
				if (!empty($_FILES['secondXLS']['tmp_name'])) {$copyxls = copy($_FILES['secondXLS']['tmp_name'], $uploadfileupdate);}
					if ($copyxls) {
						$xlsData= getXLS($uploadfileupdate);
						foreach($xlsData as $xlsItem){
			
							$name_portal = (isset($xlsItem[0])) ? $xlsItem[0] : "";
							$name_site = (isset($xlsItem[1])) ? $xlsItem[1] : "";
							if (($name_portal != "") and ($name_site != "")) {
								$data = array(
									'id_tree' => $_POST['id_tree'],			  
									'name_portal' => $name_portal,			  
									'name_site' => $name_site,			  
									'active' => 1
								);	
								Database::insert($this->_table,$data);
							}
						}
					}
				}	

		} 
		
		
		if (isset($_POST['action_group'])) {
			
			$data = array(
				'id_tree' => $_POST['id_tree'],			  
				'id_maker' => $_POST['id_maker'],			  
				'name_portal' => $_POST['name_portal'],			  
				'name_site' => $_POST['name_site'],			  
				'active' => ((isset($_POST['active'])) ? 1 : 0)
			);
			
			// ДОБАВИТЬ элемент в таблицу
			if ($_POST['action_group']=="add") {
				Database::insert($this->_table,$data);
			}
			
			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action_group']=="edit") {
				$where = "id = ".$_POST['id'];
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
			
			$data = array(
				'name' => $_POST['name']
			);
				
			// Добавить элемент в ДЕРЕВО
			if ($_POST['action_tree']=="add")  {
				Database::insert($this->_table_tree,$data);				
			}
			
			// Редактировать элемент в ДЕРЕВЕ
			if ($_POST['action_tree']=="edit")  {
				$where = "id = ".$_POST['id'];
				Database::update($this->_table_tree,$data,$where);						
			}
		}	


		// Удалить элемент в ДЕРЕВЕ
		if (isset($_POST['del_id_tree']))  {
			Database::delete($this->_table_tree,$_POST['del_id_tree']);
		}	
		
		$items = array();
		$items  = get_tree_characteristics(Database::getRows($this->_table_tree));
		echo json_encode($items);
			
	}
	
	//Получение данных по id в форму для редактирования раздела
	public function opentreeAction() {
	  
		$data = array();
			
		$data = Database::getRow($this->_table_tree,$_POST['id']);
	
		echo json_encode($data);
		
	}   
 
	//Получение данных по id в форму для редактирования товара
	public function openAction() {
		
		$data = array();
			
		$data = Database::getRow($this->_table,$_POST['id']);
	
		echo json_encode($data);
	
	}
 
}