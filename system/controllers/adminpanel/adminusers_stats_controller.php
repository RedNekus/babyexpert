<?php

class Adminusers_stats_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Adminusers_stats', 'Catalog', 'Adminusers'));
		$this->_config = Config::getParam('modules->adminusers_stats');
		$this->_content['title'] = 'Статистика наполнения товаров';
	}

	
	public function defaultAction() {
  
		$adminusers  = Adminusers::getUsers('id','ASC',Adminusers::getTotalUsers());
	
		$this->_content['content'] = Render::view(
				'adminpanel/adminusers_stats/list', array (
				'adminusers' => get_tree_adminusers($adminusers),
				'access' => get_array_access()
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

		$table = get_table('catalog').' as t1 JOIN '.get_table('adminusers_stats').' as t2 ON t1.id = t2.id_catalog';
		$where = '1';
		if (isset($_GET['id'])) $where .= ' and id_adminusers = '.$_GET['id']; 
		if (isset($_GET['paid'])) $where .= ' and t1.paid = '.$_GET['paid']; 
		if (isset($_GET['action'])) {
			$where .= ' and action = '.$_GET['action'];
			$action = $_GET['action'];
		} else {
			$action = 'add';
		}
		if (isset($_GET['date_ot']) and !empty($_GET['date_ot'])) $where .= ' and "'.$_GET['date_ot'].'" <= created';
		if (isset($_GET['date_do']) and !empty($_GET['date_do'])) $where .= ' and "'.$_GET['date_do'].'" >= created';

		$count = Database::getCountSQL('SELECT count(distinct id) as `count` FROM '.$table.' WHERE '.$where);
			
		$data = getPaginationAdmin($count,$limit,$page);
			
			if (@$searchField) {
				$productions = Database::searchAdmin(get_table('catalog'),$searchField, $searchOper, $searchString);
			} else {
				$productions = Database::getRows($table, $sidx, $sord.', created_time '.$sord, $data['limit'], $where);
			}	

		
		if (isset($productions)) {
			$i = 0;
			foreach($productions as $item) {

				$data['rows'][$i]['id'] = $item['id'];
				$data['rows'][$i]['cell'][] = '<a href="/product/'.$item['path'].'" title="" target="_ablank">'.$item['name'].'</a>';
				switch ($item['action']) {
					case 'add': $action = "Товар был добавлен"; break;
					case 'edit': $action = "Товар был изменен"; break;
					case 'del': $action = "Товар был удален"; break;
					case 'view': $action = "Товар был просмотрен"; break;
				}				
				$data['rows'][$i]['cell'][] = $action;
				$data['rows'][$i]['cell'][] = transform_norm_date(@$item['created']).' '.@$item['created_time'];
				$data['rows'][$i]['cell'][] = '12 000';
				$data['rows'][$i]['cell'][] = '<b>'.(($item['paid']!=0) ? "Да" : "Нет").'</b>';		
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
		$productions = Adminusers_stats::getStatsByIdCatalog($id);

		$i = 0;
		foreach($productions as $item) {

			$adminusers = Adminusers::getUserByID($item['id_adminusers']);
			switch ($item['action']) {
				case 'add': $action = "Товар был добавлен"; break;
				case 'edit': $action = "Товар был изменен"; break;
				case 'del': $action = "Товар был удален"; break;
				case 'view': $action = "Товар был просмотрен"; break;
			}
			$data['rows'][$i]['id'] = $item['id_stats'];
			$data['rows'][$i]['cell'][] = ((isset($adminusers['login'])) ? $adminusers['login'] : '<span style="color:red">Пользователь с id:'.$item['id_adminusers'].' - был удален</span>');		
			$data['rows'][$i]['cell'][] = $action;
			$data['rows'][$i]['cell'][] = transform_norm_date($item['created']).' '.@$item['created_time'];
			$data['rows'][$i]['cell'][] = "";
			$data['rows'][$i]['cell'][] = "";			
			$i++;

		}

		echo json_encode($data);
	}  
 
	public function statsaddAction() {
	  
		if (!empty($_POST['array'])) {
			
			
			$items = explode(',',$_POST['array']);
			foreach($items as $id_tmp) {
				$id = str_replace("grid","",$id_tmp); 
				Catalog::updateCollectionPaid(array(
					'id' => $id,
					'paid' => 1
				));
			}
		
		}
	} 
	
	
	public function testAction() {
	
		$items = Catalog::getCollections2();
		foreach($items as $item) {
			//echo $item['id'].'<br/>';
			Adminusers_stats::addStats(array(
				'id_catalog' => $item['id'],
				'id_adminusers' => 2,
				'action' => 'add',
				'created' => time()
			));	
		}
	}	
}