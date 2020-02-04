<?php

class Managers_Controller {
	private $_config, $_content, $_table, $_table_zakaz;

	public function __construct() {
		$this->_table = get_table('managers');
		$this->_table_zakaz = get_table('zakaz');
		$this->_table_client = get_table('zakaz_client');
		$this->_table_catalog = get_table('catalog');
		$this->_table_kon = get_table('kontragenty');
		$this->_content['title'] = 'Менеджеры';
	}

	
	public function defaultAction() {
  
		$trees  = Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 4');
	
		$this->_content['content'] = Render::view(
				'adminpanel/reference/managers', array (
				'trees' => get_tree_characteristics($trees),
				'couriers' => $trees,
				'access' => get_array_access(),
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

		if (!empty($_GET['adopted'])) $adopted = $_GET['adopted'];
		if (!empty($_GET['id_tree'])) $id_tree = $_GET['id_tree'];
		if (!empty($_GET['date_ot'])) $date_ot = $_GET['date_ot'];
		if (!empty($_GET['date_do'])) $date_do = $_GET['date_do'];
		
		$where = "1";
		if (isset($adopted)) $where .= " and adopted = $adopted";
		else $where .= " and adopted = 0";
		if (isset($id_tree)) $where .= " and id_manager = $id_tree";
		if (isset($date_ot)) $where .= " and date_sell >= '$date_ot'";
		if (isset($date_do)) $where .= " and date_sell <= '$date_do'";
		
		
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		$total_zp = 0;
		
		foreach($items as $item) {

			$client = Database::getRow($this->_table_client,$item['id_client']);
			$where = "id_client = ".$item['id_client'];
			$zakazs = Database::getRows($this->_table_zakaz, 'id', 'asc', false, $where);
			$name_tovar = '';
			
			foreach($zakazs as $zakaz) {
				$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);
				$name = get_product_name($product,false,$zakaz['name_tovar']);
				$name_tovar .= "<div>$name</div>";									
			}			
		
			if (@$client['call']==0) { $b = '<span style="color: red">'; $bb = '</span>'; }
			else { $b = ''; $bb = ''; }
		
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = '';				
			$data['rows'][$i]['cell'][] = $b.@$client['nomer_zakaza'].$bb;				
			$data['rows'][$i]['cell'][] = $b.@$name_tovar.$bb;
			$data['rows'][$i]['cell'][] = $b.Database::getField($this->_table_kon,$item['id_manager']).$bb;
			$data['rows'][$i]['cell'][] = $b.(($item['adopted']==1) ? 'Да' : 'Нет').$bb;
			$data['rows'][$i]['cell'][] = $item['zp'];
			$data['rows'][$i]['cell'][] = $item['time_call'];		
			$data['rows'][$i]['cell'][] = $item['date_call'];		
			$data['rows'][$i]['cell'][] = $item['date_sell'];		
			$i++;	
			$total_zp += $item['zp'];
		}		

		$data['userdata']['total_zp'] = $total_zp;

		echo json_encode($data);
		
	}   
	
	//Получение данных по id в форму для редактирования товара
	public function openAction() {

		$manager = Database::getRow($this->_table,$_POST['id']);
		
		$id = $manager['id_client'];
		$zakaz_client = Database::getRow($this->_table_client,$id);		
		$courier = Database::getRow(get_table('couriers'),$id,'id_client');		

		$zakaz_client['comment_c'] = @$courier['comment'];		
		$zakaz_client['id_manager'] = $manager['id'];
		echo json_encode($zakaz_client);
	
	}

    public function loadzakazAction() {
		// Начало формирование объекта
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		$host 	= $_SERVER['HTTP_HOST'];		
		$id_client = $_GET['id_client'];
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		
		
		if (isset($_GET['predzakaz'])) $predzakaz_sql = " and predzakaz = ".$_GET['predzakaz']; else $predzakaz_sql = "";
		if (isset($_GET['rezerv'])) $rezerv_sql = " and rezerv = ".$_GET['rezerv']; else $rezerv_sql = "";
		$where = "id_client = $id_client $predzakaz_sql $rezerv_sql";
		$count = Database::getCount($this->_table_zakaz,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table_zakaz, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table_zakaz, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		$suma_usd = 0;
		$suma_bur = 0;
		$kolvo = 0;
		$sum_total_cena = 0;
		$sum_total_cena_blr = 0;
		foreach($items as $zakaz) {
			
			$product = Database::getRow($this->_table_catalog,$zakaz['id_catalog']);

			if (isset($product['name'])) {
			
				$name = get_product_name($product,false,$zakaz['name_tovar']);
					
				if (!empty($zakaz['skidka']) or !empty($zakaz['skidka_procent']) or !empty($zakaz['doplata']) or !empty($zakaz['dostavka'])) {
					$b = '<b>'; $bb = '</b>';
				} else {
					$b = ''; $bb = '';					
				}

					
				$suma_usd += with_skidka($zakaz);
				$suma_bur += with_skidka($zakaz,'bur');	
				
				$kolvo += $zakaz['kolvo'];
				
				$total_cena_usd = with_skidka($zakaz);
				$total_cena_bur = with_skidka($zakaz,'bur');
				
				$sum_total_cena += $total_cena_usd;
				$sum_total_cena_blr += $total_cena_bur;
				
				if ($zakaz['id_gift']!=0) {
					$gift = Database::getRow(get_table('catalog'),$zakaz['id_gift']);
					$html_g = '<a href="http://'.$host.'/product/'.$gift['path'].'" target="_ablank">'.$gift['name'].'</a>';
				} else {
					$html_g = '';
				}
				
				$html_r =  ($product['raffle']!=0) ? $zakaz['promocode'] : 'Нет';
				
				$status = get_status_zakaz($zakaz);

				$data['rows'][$i]['id'] = $zakaz['id'];		
				$data['rows'][$i]['cell'][] = $b.'<a href="http://'.$host.'/product/'.$product['path'].'" target="_ablank">'.$name.'</a>'.$bb;		
				$data['rows'][$i]['cell'][] = $zakaz['kolvo'];		
				$data['rows'][$i]['cell'][] = with_skidka($zakaz);			
				$data['rows'][$i]['cell'][] = with_skidka($zakaz,'bur');			
				$data['rows'][$i]['cell'][] = $total_cena_usd;			
				$data['rows'][$i]['cell'][] = $total_cena_bur;			
				$data['rows'][$i]['cell'][] = $html_g;			
				$data['rows'][$i]['cell'][] = $html_r;			
				$data['rows'][$i]['cell'][] = $status;			
				$i++;
				
			}
		}
		
		$data['userdata']['kolvo'] = $kolvo;
		$data['userdata']['cena'] = $suma_usd;
		$data['userdata']['cena_blr'] = $suma_bur;
		$data['userdata']['summa'] = $sum_total_cena;
		$data['userdata']['summa_blr'] = $sum_total_cena_blr;
		
		echo json_encode($data);
	}
    
	public function edittreeAction() {

/********************************************************/
/*			Редатирование модуля дерево порталов		*/
/********************************************************/
	
		if (isset($_POST['action_tree'])) {
			$data = array();
			
			$data = array(
				'id_tip' => 4,
				'name' => $_POST['name'],
				'active' => ((isset($_POST['active'])) ? 1 : 0)
			);	
			
			// Добавить элемент в ДЕРЕВО
			if ($_POST['action_tree']=="add")  {
				Database::insert($this->_table_kon,$data);
			}
			
			// Редактировать элемент в ДЕРЕВЕ
			if ($_POST['action_tree']=="edit")  {
				$where = 'id = '.$_POST['id'];
				Database::update($this->_table_kon,$data,$where);
			}
			
			$trees = array();
			$trees  = get_tree_couriers(Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 4'));
			echo json_encode($trees);			
			
		}	



		// Удалить элемент в ДЕРЕВЕ
		if (isset($_POST['del_id_couriers']))  {
			Database::remove($this->_table_kon,$_POST['del_id_couriers']);
			
			$trees = array();
			$trees  = get_tree_couriers(Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 4'));
			echo json_encode($trees);			
		}	
			
	}
	
	//Получение данных по id в форму для редактирования раздела
	public function opentreeAction() {
	  
		$trees = array();
			
		$trees = Database::getRow($this->_table_kon,$_POST['id']);
	
		echo json_encode($trees);
		
	}  

	public function adoptedupdateAction() {
		
		if (!empty($_POST['array'])) {
			$data = array();	
			$items = explode(',',$_POST['array']);
			$zp = 0;
			foreach($items as $id) {
					
				Database::update($this->_table,array('adopted' => 1),"id = $id");
				
				$table_kassa = get_table('kassa');
				$manager = Database::getRow($this->_table,$id);
				$zp += $manager['zp'];				
				$nomer = 0;
				$nomer = Database::getLastId($table_kassa);
				$nomer++;
				$data = array(
					'nomer' => $nomer,
					'active' => 1,
					'id_tree' => 16,			  
					'cena_usd' => $zp * (-1),			  			  		  
					'operation' => 2,			  
					'id_tip_operation' => 18,			  
					'date_create' => date("Y-m-d"),
					'time_create' => date("G:i:s"),
					'id_adminusers' => $_SESSION['isLoggedIn']['id']
				);	
				
				Database::insert($table_kassa,$data);				
			}
		
		}
		
		echo json_encode($data);
		
	}
	
	public function callAction() {
		
		$data = array();	
		$data['succes'] = false;
		$data['message'] = "Ошибка!";
		
		if (isset($_POST['call']) and $_POST['call']==1) {
			
			$id_client = $_POST['id'];
			$id_manager = $_POST['id_manager'];
			$manager = Database::getRow($this->_table,$id_manager);
			
			$zp = $manager['zp']++;
			
			$arr = array(
				'zp' => $zp,
				'time_call' => date("G:i:s"),
				'date_call' => date("Y-m-d"),
			);
			
			Database::update($this->_table,$arr,"id = $id_manager");
			
			$arr_client = array(
				'call' => $_POST['call']
			);
			
			Database::update($this->_table_client,$arr_client,"id = $id_client");
		
			$data['succes'] = true;
		}
		
		echo json_encode($data);
		
	}	

}