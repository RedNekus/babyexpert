<?php

class Currency_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Currency_tree','Catalog','Maker','Tree'));
		$this->_config = Config::getParam('modules->currency');
		$this->_content['title'] = 'Валюты сайта';
	}

	public function defaultAction() {
		$this->listAction();
	}
  
	public function listAction() {
	
		$trees  = Currency_tree::getTrees();
	
		$this->_content['content'] = Render::view(
			'adminpanel/currency/list',array(
				'trees' => get_tree($trees, 0),
				'currency_tree' => $trees,
				'access' => get_array_access()
			));
   
		Render::layout('adminpanel/adminpanel', $this->_content);
   
	}

    public function loadAction() {
		// Начало формирование объекта
		$data = array();
		$items = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   		= @$_GET['searchField'];		//имя столбца
		$searchOper    		= @$_GET['searchOper'];	    	//содержит
		$searchString  		= @$_GET['searchString'];		//искомое слово	
		
		$id  = (@$_GET['id_currency_tree']) ? @$_GET['id_currency_tree'] : 1;
		$items = json_decode(@$_GET['filters'], true);
		if(!empty($items['rules'])) {
			
			$where = "";
			foreach($items['rules'] as $elem) {
				if ($elem['field']=="id_razdel1") { 
					if ($elem['data']!=0) {
						$data_t = '`id_razdel1` = "'.$elem['data'].'" ';
					} else {
						$data_t = '`id_razdel0` = 1 ';
					}
				}
				if ($elem['field']=="id_maker") { 
					$maker = Maker::getMakersForSite('`name` LIKE "'.$elem['data'].'%"','id','ASC',1); 
					$data_t = '`id_maker` = "'.$maker[0]['id'].'" ';
				}
				if ($elem['field']=="name") { 
					$data_t = '`name` LIKE "'.$elem['data'].'%" ';						
				}
				$where .= $data_t.$items['groupOp'];
			}
			if ($id==1) $where .= ' `id_razdel0` = 1';
			else $where .= ' `id_currency` = '.$id;
		} else {
			if ($id==1) $where = ' `id_razdel0` = 1';
			else $where = ' `id_currency` = '.$id.' and `id_razdel0` = 1';
		}
		// Теперь эта переменная хранит кол-во записей в таблице
		$count = Catalog::getTotalCollectionsCurrency($where);  
		
		$data = getPaginationAdmin($count,$limit,$page);

		if (@$searchField) {
		$makers = Catalog::searchAdmin($searchField, $searchString);
		} else {
		$makers = Catalog::getCollectionsCurrency($where, $sidx, $sord, $data['limit']);
		}

		$i = 0;
		foreach($makers as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];
			$maker = Maker::getMakerByID($item['id_maker']);
			$data['rows'][$i]['cell'][] = @$maker['name'];
			$razdel = Tree::getTreeByID($item['id_razdel1']);
			$data['rows'][$i]['cell'][] = @$razdel['name'];		
			$currency = Currency_tree::getTreeByID($item['id_currency']);
			$data['rows'][$i]['cell'][] = @$currency['kurs'];			
			$data['rows'][$i]['cell'][] = @$currency['name'];			
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
  }  
  
	public function editAction() {
  
	/********************************************************/
	/*				Редатирование модуля товаров			*/  
	/********************************************************/  
	
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
			Currency::addPage(array(
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'baza' => ((isset($_POST['baza'])) ? 1 : 0),
				'name' => $_POST['name'],
				'id_currency_tree' => $_POST['id_currency_tree'],
				'short_name' => $_POST['short_name'],
				'kurs' => $_POST['kurs'],
				'prioritet' => $_POST['prioritet']
			));	
			reload_kurs();	
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
			Currency::updatePage(array(
				'id' => $_POST['id'],  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'baza' => ((isset($_POST['baza'])) ? 1 : 0),
				'name' => $_POST['name'],
				'id_currency_tree' => $_POST['id_currency_tree'],				
				'short_name' => $_POST['short_name'],
				'kurs' => $_POST['kurs'],
				'prioritet' => $_POST['prioritet']
				));	
			reload_kurs();	
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Currency::removePage($_POST['del_id']);
		}	
	
			
	/********************************************************/
	/*			Редатирование модуля дерево разделов		*/
	/********************************************************/
	
		if (isset($_POST['action_tree'])) {
			$products = Database::getRows(get_table('catalog'));
			foreach($products as $product) {
				if ($product['cena_blr'] != 0) {
					$cena_blr_site = $product['cena_blr'];
				} else {
					$kurs = Database::getField(get_table('currency_tree'),$product['id_currency'],'id','kurs');
					$cena_blr_site = $product['cena'] * $kurs;
				}
				Database::update(get_table('catalog'),array('cena_blr_site'=>$cena_blr_site),'id='.$product['id']);
			}	
		}
		
		// Добавить элемент в ДЕРЕВО
		if (isset($_POST['action_tree']) and $_POST['action_tree']=="add")  {

			Currency_tree::addTree(array(
				'name' => $_POST['name'],
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'fullname' => $_POST['fullname'],	
				'prioritet' => $_POST['prioritet'],	
				'pid' => $_POST['pid'],				
				'code' => $_POST['code'],
				'baza' => ((isset($_POST['baza'])) ? 1 : 0),
				'kurs' => $_POST['kurs'],
				'symbol' => $_POST['symbol']
			));	
			
			$trees = array();	
			$trees  = get_tree(Currency_tree::getTrees(), 0);
			echo json_encode($trees);
		
		}	

		// Редактировать элемент в ДЕРЕВЕ
		if (isset($_POST['action_tree']) and $_POST['action_tree']=="edit")  {

			Currency_tree::updateTree(array(
				'id'  => $_POST['id'],
				'name' => $_POST['name'],
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'fullname' => $_POST['fullname'],	
				'prioritet' => $_POST['prioritet'],	
				'pid' => $_POST['pid'],				
				'code' => $_POST['code'],
				'baza' => ((isset($_POST['baza'])) ? 1 : 0),
				'kurs' => $_POST['kurs'],				
				'symbol' => $_POST['symbol']				
				));		
			
			$trees = array();	
			$trees  = get_tree(Currency_tree::getTrees(), 0);
			echo json_encode($trees);
		}

		// Удалить элемент в ДЕРЕВЕ
		if (isset($_POST['del_id_tree']))  {
			Currency_tree::removeTree($_POST['del_id_tree']);
			
			$trees = array();	
			$trees  = get_tree(Currency_tree::getTrees(), 0);
			echo json_encode($trees);
		}	
	}
	
  //Получение данных по id в форму для добавления товара
	public function datahandlingAction() {
		$currency = array();
		$currency = Currency::getCurrencyByID($_POST['id']);
		echo json_encode($currency);
	}
  
	//Получение данных по id в форму для редактирования раздела
	public function datatreehandlingAction() {
	  
		$trees = array();
			
		$trees = Currency_tree::getTreeByID($_POST['id']);
			
		echo json_encode($trees);
		
	}  
	
	//Получение данных по id в форму для редактирования раздела
	public function datatreeinitAction() {
	  
		$trees = array();

		$cur = Currency_tree::getTreeByID($_POST['id']);
		if ($cur['pid']!=0) {
		$items = Currency_tree::getTreesByPid($cur['pid']);
		} else {
		$items = Currency_tree::getTreesByPid($cur['id']);
		}
		$trees['select_form'] = '<label for="">Переместить в</label>';
		$trees['select_form'] .= '<select name="id_currency" id="select-currency">';
		foreach($items as $item) {
		$trees['select_form'] .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		$trees['select_form'] .= '</select>';
		$trees['select_form'] .= '<a href="" class="btn-cur" id="btn-currency">Переместить</a>';
		
		echo json_encode($trees);
		
	}	
	
	//Получение данных по id в форму для редактирования раздела
	public function selectAction() {

		$items = Tree::getTreesForSite('pid = 1');

		$select = '<select>';
		$select .= '<option value="0">---</option>';
		foreach($items as $item) {
		$select .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
		$select .= '</select>';
			
		echo $select;
		
	}
	
	public function curgroupaddAction() {
	  
		if ((!empty($_POST['array'])) and (!empty($_POST['id']))) {
		
			$items = explode(',',$_POST['array']);
			foreach($items as $id) {
				Catalog::updateCollectionCurrency(array(
					'id' => $id,
					'id_currency' => $_POST['id']
				));
			}
		
		}
	}	
}