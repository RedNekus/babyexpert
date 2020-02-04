<?php

class Delivery_tmc_Controller {
	private $_content, $_table;

	public function __construct() {
		Load::model('Catalog');
		$this->_table = get_table('delivery_tmc');
		$this->_table_tc = get_table('sklad_tovar');
		$this->_table_kon = get_table('kontragenty');
		$this->_content['title'] = 'Документы поступление ТМЦ';
	}

	public function defaultAction() {
	
		$sklad = Database::getRows(get_table('sklad'));

		$storekeepers = Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 3');

		$carriers = Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 1');	

		$suppliers = Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 2');

		$valute = Database::getRows(get_table('valute'));

		$trees = Database::getRows(get_table('catalog_tree'), 'name','asc', false,'pid=1');

		$makers = Database::getRows(get_table('maker'), 'name','asc');

		$this->_content['content'] = Render::view(
				'adminpanel/documents/delivery_tmc', array(
					'sklad' => $sklad,
					'storekeepers' => $storekeepers,
					'carriers' => $carriers,
					'suppliers' => $suppliers,
					'valute' => $valute,
					'trees' => $trees,
					'makers' => $makers,
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

		$where = '1';
		
		if (isset($_GET['author']) and !empty($_GET['author'])) $where .= ' AND id_adminuser = '.$_GET['author']; 			
		if (isset($_GET['sklad']) and !empty($_GET['sklad'])) $where .= ' AND id_sklad = '.$_GET['sklad']; 			
		if (isset($_GET['valute']) and !empty($_GET['valute'])) $where .= ' AND id_valute = '.$_GET['valute']; 			
		if (isset($_GET['carriers']) and !empty($_GET['carriers'])) $where .= ' AND id_carriers = '.$_GET['carriers']; 			
		if (isset($_GET['storekeepers']) and !empty($_GET['storekeepers'])) $where .= ' AND id_storekeepers = '.$_GET['storekeepers']; 			
		if (isset($_GET['suppliers']) and !empty($_GET['suppliers'])) $where .= ' AND id_suppliers = '.$_GET['suppliers']; 			
		if (isset($_GET['date_ot']) and !empty($_GET['date_ot'])) $where .= ' and "'.$_GET['date_ot'].'" <= date_delivery';
		if (isset($_GET['date_do']) and !empty($_GET['date_do'])) $where .= ' and "'.$_GET['date_do'].'" >= date_delivery';
		
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;

		foreach($items as $item) {
		
			$adminuser = Database::getRow(get_table('adminusers'),$item['id_adminuser']);
			$where = 'id_delivery_tmc = '.$item['id'];
			$sklad_tovars = Database::getRows($this->_table_tc,'id','asc',Database::getCount($this->_table_tc,$where),$where);
			$sum = 0;
			$status = 0;
			foreach($sklad_tovars as $s_tovar) {
				$sum += $s_tovar['cena'] * $s_tovar['kolvo_hold'];	
				$status = $s_tovar['status'];
			}			
			
			$sum_ye = transform_to_kurs($sum,$item['id_valute'],$item['kurs']);
						
			if ($item['block_edit']==0) { $b = '<span style="color:red;">'; $bb = '</span>'; }
			elseif ($item['oplata_from_kassa']==0) { $b = '<b>'; $bb = '</b>';}
			else { $b = ''; $bb = '';}

			$vid_doc = ($item['return_tmc'] == 1) ? 'Возврат ТМЦ' : get_status_tmc($status);
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = '';
			$data['rows'][$i]['cell'][] = $b.$item['nomer_nakladnoy'].$bb;
			$data['rows'][$i]['cell'][] = $b.$vid_doc.$bb;
			$data['rows'][$i]['cell'][] = $b.@$adminuser['fio'].$bb;			
			$data['rows'][$i]['cell'][] = $sum;	
			$data['rows'][$i]['cell'][] = get_valute_tmc($item['id_valute']);			
			$data['rows'][$i]['cell'][] = $sum_ye;						
			$data['rows'][$i]['cell'][] = Database::getField(get_table('sklad'),$item['id_sklad']);
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_kon,$item['id_storekeepers']);
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_kon,$item['id_carriers']);
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_kon,$item['id_suppliers']);
			$data['rows'][$i]['cell'][] = $item['date_delivery'];		
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {

		$data = array();
			
		if (isset($_POST['action'])) {
		
			$date_delivery = (empty($_POST['date_delivery'])) ? date("Y-m-d") : $_POST['date_delivery'];
			
			$data = array( 	  
				'date_delivery' => $date_delivery,
				'nomer_nakladnoy' => $_POST['nomer_nakladnoy'],
				'id_sklad' => $_POST['id_sklad'],
				'id_valute' => $_POST['id_valute'],
				'kurs' => $_POST['kurs'],
				'id_storekeepers' => $_POST['id_storekeepers'],
				'id_carriers' => $_POST['id_carriers'],	
				'id_suppliers' => $_POST['id_suppliers'],
				'sum_carriers' => $_POST['sum_carriers'],
				'beznal' => ((isset($_POST['beznal'])) ? 1 : 0),
				);		
		}
		
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
			$data['id_adminuser'] = $_SESSION['isLoggedIn']['id'];
			Database::insert($this->_table,$data);		
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
				
			$where = '`id` = '.$_POST['id']; 
			Database::update($this->_table,$data,$where);	
		
			if (isset($_POST['status'])) {				
				$where_tovar = '`id_delivery_tmc` = '.$_POST['id'].' and `status` <> 2';
				$data_tovar = array('status' => $_POST['status']);
				Database::update($this->_table_tc,$data_tovar,$where_tovar);
			}	
							
		}
		
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Database::delete($this->_table,$_POST['del_id']);
			Database::delete($this->_table_tc,$_POST['del_id'],'id_delivery_tmc');
		}	
		
		$arr = array();
		$arr['last_id'] = Database::getLastId($this->_table);
		$arr['action'] = @$_POST['action'];
		echo json_encode($arr);		
	}
  
	public function block_editAction() {
		
		if (isset($_POST['block_edit'])) {
			
			$data = array();
			$id = $_POST['id'];
			$where = "id_delivery_tmc = $id";
			$sklad_tovars = Database::getRows($this->_table_tc,'id','asc',Database::getCount($this->_table_tc,$where),$where);
			$sum = 0;
			foreach($sklad_tovars as $s_tovar) {
				$sum += $s_tovar['cena'] * $s_tovar['kolvo_hold'];	
			}
			Database::update($this->_table_tc,array('status'=>2),$where);
			$post_suppliers = array(
				'id_couriers' => $_POST['id_suppliers'],
				'operation' => 2,
				'beznal' => ((isset($_POST['beznal'])) ? 1 : 0),
			);
			$post_carriers = array(
				'id_couriers' => $_POST['id_carriers'],
				'operation' => 1,
			);			
			if ($_POST['id_valute']==1) {
				$post_suppliers['cena_rur'] = $sum; 
				$post_carriers['cena_rur'] = $_POST['sum_carriers'];
			}
			if ($_POST['id_valute']==2) {
				$post_suppliers['cena_blr'] = $sum;
				$post_carriers['cena_blr'] = $_POST['sum_carriers'];
			}
			if ($_POST['id_valute']==6) {
				$post_suppliers['cena_eur'] = $sum;
				$post_carriers['cena_eur'] = $_POST['sum_carriers'];
			}
			if ($_POST['id_valute']==7) {
				$post_suppliers['cena_usd'] = $sum;
				$post_carriers['cena_usd'] = $_POST['sum_carriers'];
			}
			
			$data = insert_kassa_by_kontragent($post_suppliers);
			$data = insert_kassa_by_kontragent($post_carriers);
			if ($data['succes']==true) {
				foreach($sklad_tovars as $s_tovar) {
					$product = Database::getRow(get_table('catalog'),$s_tovar['id_item']);				
					$new_kolvo = $s_tovar['kolvo_hold'] + $product['kolvo'];
					Database::update(get_table('catalog'),array('kolvo'=>$new_kolvo),'id='.$product['id']);	
					if ($s_tovar['id_zakaz']>0) {
					Database::update(get_table('zakaz'),array('vozvrat'=>1),'id='.$s_tovar['id_zakaz']);
					}
					Database::update($this->_table_tc,array('status'=>2),'id='.$s_tovar['id']);
				}					

				$data_tovar = array('block_edit' => $_POST['block_edit']);
				Database::update($this->_table,$data_tovar,"id = $id");
			}
			echo json_encode($data);
		}
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($data);
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
  
	public function load_tovarAction() {
		$data = array();
		$params = array();
		
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		
		
		if (@$_GET['id_tree']) {
			$params['tip_catalog'] = $_GET['id_tree'];
			$params['id_maker'] = $_GET['id_maker'];
			$params['id_tovar'] = @$_GET['id_tovar'];
			$params['sort'] = @$_GET['sidx'].'-'.@$_GET['sord'];
			
			$count = Catalog::getPodborAdmin($params, "", FALSE);
			
			$data = getPaginationAdmin($count,$limit,$page);

			$items = Catalog::getPodborAdmin($params, $data['limit'], TRUE);
			
			$i = 0;
			$table_pfx = get_table('prefix');
			$table_maker = get_table('maker');
			foreach($items as $item) {
				$id_item = $item['id'];
				$prefix = Database::getRow($table_pfx,$item['id_prefix']);
				$maker = Database::getRow($table_maker,$item['id_maker']);
				$data['rows'][$i]['id'] = $id_item;
				$data['rows'][$i]['cell'][] = ' ';			
				$data['rows'][$i]['cell'][] = @$prefix['name'];
				$data['rows'][$i]['cell'][] = @$maker['name'];
				$data['rows'][$i]['cell'][] = $item['name'];
				$data['rows'][$i]['cell'][] = $item['name_color'];
				$i++;		
			}
		}
		echo json_encode($data);
	
	}

	public function load_sklad_tovarAction() {
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		

		$id_delivery_tmc = (@$_GET['id_delivery_tmc']) ? @$_GET['id_delivery_tmc'] : 0;
		$status = (@$_GET['status']) ? @$_GET['status'] : 0;
		$valute = @$_GET['valute'];
		$kurs = @$_GET['kurs'];

		$where_tmp = " and `status` = $status";
		$where = "`id_delivery_tmc` = $id_delivery_tmc".@$where_tmp;
		
		$count = Database::getCount($this->_table_tc,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table_tc, $searchField, $searchOper, $searchString,$where);
		else $items = Database::getRows($this->_table_tc, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		foreach($items as $item) {
			$sum_opt = 0;

			$tovar = Database::getRow(get_table('catalog'),$item['id_item']);
			if (empty($tovar)) continue;
			$kolvo = $item['kolvo_hold'];
			$sum_opt = $item['cena'] * $kolvo;
		
			$cena_ye = transform_to_kurs($item['cena'],$valute,$kurs);
			$sum_ye = $cena_ye * $kolvo;
			
			$kurs_roznica = Database::getField(get_table('currency_tree'),$tovar['id_currency'],'id','kurs');
			$cena_roznica = ($tovar['cena'] == 0) ? transform_to_kurs($tovar['cena_blr_site'],'usd',$kurs_roznica) : $tovar['cena'];
			
			$access = get_array_access();
			if ($access['id']==7) {
				$delta = 0; 
				$sum_delta = 0;				
			} else {
				$delta = $cena_roznica - $cena_ye; 
				$sum_delta = $cena_roznica * $kolvo - $sum_ye;
			}
			$prefix = Database::getRow(get_table('prefix'),@$tovar['id_prefix']);
			$maker = Database::getRow(get_table('maker'),@$tovar['id_maker']);
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = '';
			$data['rows'][$i]['cell'][] = @$prefix['name'];
			$data['rows'][$i]['cell'][] = @$maker['name'];
			$data['rows'][$i]['cell'][] = @$tovar['name'];
			$data['rows'][$i]['cell'][] = $kolvo;
			$data['rows'][$i]['cell'][] = $item['cena'];					
			$data['rows'][$i]['cell'][] = @$sum_opt;
			$data['rows'][$i]['cell'][] = @$cena_ye;				
			$data['rows'][$i]['cell'][] = @$sum_ye;				
			$data['rows'][$i]['cell'][] = $cena_roznica;
			$data['rows'][$i]['cell'][] = @$delta;
			$data['rows'][$i]['cell'][] = @$sum_delta;
			$data['rows'][$i]['cell'][] = '';			
			$i++;

		}

		echo json_encode($data);
	
	}
		
	public function edit_tovarAction() {
	
		if (isset($_POST['action']) and $_POST['action']=="add")  {
		
			$delivery_tmc = Database::getRow($this->_table,$_POST['id_delivery_tmc']);
			if ($delivery_tmc['block_edit']==0) {		
			$data = array( 	 
				'cena' => $_POST['cena'],
				'id_delivery_tmc' => $_POST['id_delivery_tmc'],
				'id_sklad' => $_POST['id_sklad'],				
				'id_item' => $_POST['id_item'],
				'kolvo_hold' => $_POST['kolvo_hold'],
				'note' => $_POST['note'],
				'status' => 0
				);	

			Database::insert($this->_table_tc,$data);	
			} else {
				$data_er['msg'] = 'Запрещено менять данные!';
			}
			echo json_encode($data_er);
		}
		
		if (isset($_POST['oper']) and $_POST['oper']=="edit") {
		
			$sklad_tovar = Database::getRow($this->_table_tc,$_POST['id']);
			$delivery_tmc = Database::getRow($this->_table,$sklad_tovar['id_delivery_tmc']);
			
			if ($delivery_tmc['block_edit']==0) {
				$data = array( 	 
					'cena' => $_POST['cena_opt'],
					'kolvo_hold' => $_POST['kolvo_hold'],
					);			
			
				$where = "id = ".$_POST['id'];
				Database::update($this->_table_tc,$data,$where);	
			} else {
				$data_er['msg'] = 'Запрещено менять данные!';
			}
			echo json_encode($data_er);
		}		
		
		if (isset($_POST['oper']) and $_POST['oper']=="del") {
		
			$sklad_tovar = Database::getRow($this->_table_tc,$_POST['id']);
			$delivery_tmc = Database::getRow($this->_table,$sklad_tovar['id_delivery_tmc']);
			
			if ($delivery_tmc['block_edit']==0) {			
				Database::delete($this->_table_tc,$_POST['id']);
			} else {
				$data_er['msg'] = 'Запрещено менять данные!';
			}
			echo json_encode($data_er);			
		}
	}
	
	public function get_html_oplataAction() {
		if (isset($_POST['id']) and !empty($_POST['id'])) {
			$data = array();
			$data = Database::getRow($this->_table,$_POST['id']);
			$data['valute'] = get_valute_tmc($data['id_valute']);
			
			$where = 'id_delivery_tmc = '.$data['id'];
			$sklad_tovars = Database::getRows($this->_table_tc,'id','asc',Database::getCount($this->_table_tc,$where),$where);
			$sum = 0;
			foreach($sklad_tovars as $s_tovar) {
				$sum += $s_tovar['cena'] * $s_tovar['kolvo_hold'];	
			}			
			
			$sum_ye = transform_to_kurs($sum,$data['id_valute'],$data['kurs']);
						
			$data['sum'] = $sum;
			$data['sum_usd'] = $sum_ye;
			$data['suppliers'] = Database::getField($this->_table_kon,$data['id_suppliers']);
			echo json_encode($data);				
		}
	}	
	
	public function oplataAction() {
		
		$data = array();
		
		if (isset($_POST['with_kassa'])) {
			Database::update($this->_table,array('oplata_from_kassa' => 1),"id=".$_POST['id']);
			$data['succes'] = true;
		} else {
			$id = $_POST['id'];			
			$delivery_tmc = Database::getRow($this->_table,$id);
			if ($delivery_tmc['oplata_from_kassa'] == 0) {


				$where = "id_delivery_tmc = $id";
				$sklad_tovars = Database::getRows($this->_table_tc,'id','asc',Database::getCount($this->_table_tc,$where),$where);
				$sum = 0;
				foreach($sklad_tovars as $s_tovar) {
					$sum += $s_tovar['cena'] * $s_tovar['kolvo_hold'];	
				}			
				$suppliers_kassa = 0;
				$admin_kassa = 0;
				
				$suppliers_kassa = Database::getField(get_table('kassa_tree'),$_POST['id_suppliers'],'id_kontragenty','id');
				$admin_kassa = Database::getField(get_table('adminusers'),$_SESSION['isLoggedIn']['id'],'id','id_kassa_tree');
				
				$post = array(
					'id_kassa_tree' => $admin_kassa,
					'id_tree_end' => $suppliers_kassa,
					'id_couriers' => $_POST['id_suppliers'],
					'operation' => 3			
				);		
				if ($_POST['id_valute']==1) $post['cena_rur'] = $sum; 
				if ($_POST['id_valute']==2) $post['cena_blr'] = $sum;
				if ($_POST['id_valute']==6) $post['cena_eur'] = $sum;
				if ($_POST['id_valute']==7) $post['cena_usd'] = $sum;
				
				$data = insert_kassa_by_kontragent($post);
				
				$post['id_kassa_tree'] = $suppliers_kassa;
				$post['id_tree_end'] = $admin_kassa;
				$post['operation'] = 4;
				
				$data = insert_kassa_by_kontragent($post);
				if ($data['succes']==true) {
					Database::update($this->_table,array('oplata_from_kassa' => 1),"id=$id");
				}
			} else {
				$data['succes'] = false;
				$data['message'] = 'Данные уже внесены в кассу!';
			}

		}
		
		echo json_encode($data);		
		
	}
		
	public function getselecthtmlAction() {
		get_select_html($_POST['method']);
	} 	
	
}