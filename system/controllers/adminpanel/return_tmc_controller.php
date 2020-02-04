<?php

class Return_tmc_Controller {
	private $_content, $_table;

	public function __construct() {
		Load::model('Catalog');
		$this->_table = get_table('return_tmc');
		$this->_table_delivery = get_table('delivery_tmc');
		$this->_table_tc = get_table('sklad_tovar');
		$this->_table_kon = get_table('kontragenty');
		$this->_content['title'] = 'Документы возврат ТМЦ';
	}

	public function defaultAction() {
	
		$sklad = Database::getRows(get_table('sklad'));

		$storekeepers = Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 3');

		$carriers = Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 1');	

		$kontragenty = Database::getRows(get_table('kontragenty_tip'),'id','asc',false,'couriers_show = 1');

		$valute = Database::getRows(get_table('valute'));

		$trees = Database::getRows(get_table('catalog_tree'), 'name','asc', false,'pid=1');

		$makers = Database::getRows(get_table('maker'), 'name','asc');

		$this->_content['content'] = Render::view(
				'adminpanel/documents/return_tmc', array(
					'sklad' => $sklad,
					'storekeepers' => $storekeepers,
					'carriers' => $carriers,
					'kontragenty' => $kontragenty,
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
		
		if (isset($_GET['kontragent']) and !empty($_GET['kontragent'])) $where .= ' AND id_kontragent = '.$_GET['kontragent']; 			
		if (isset($_GET['date_ot']) and !empty($_GET['date_ot'])) $where .= ' and "'.$_GET['date_ot'].'" <= date_create';
		if (isset($_GET['date_do']) and !empty($_GET['date_do'])) $where .= ' and "'.$_GET['date_do'].'" >= date_create';
		
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;

		foreach($items as $item) {
		
			$delivery_tmc = Database::getRow($this->_table_delivery,$item['id_delivery_tmc']);
			$adminuser = Database::getRow(get_table('adminusers'),@$delivery_tmc['id_adminuser']);

			if (isset($delivery_tmc['id'])) {
					
				$where = 'id_delivery_tmc = '.$delivery_tmc['id'];
				$sklad_tovars = Database::getRows($this->_table_tc,'id','asc',Database::getCount($this->_table_tc,$where),$where);
				$sum = 0;
				$status = 0;
				foreach($sklad_tovars as $s_tovar) {
					$sum += $s_tovar['cena_sell'] * $s_tovar['kolvo_hold'];	
					$status = $s_tovar['status'];
				}			
				
				$sum_ye = transform_to_kurs($sum,$delivery_tmc['id_valute'],$delivery_tmc['kurs']);
					
				if ($delivery_tmc['block_edit']==0) { $b = '<span style="color:red;">'; $bb = '</span>'; }	
				elseif ($delivery_tmc['oplata_from_kassa']==0) { $b = '<strong>'; $bb = '</strong>'; }
				else { $b = ''; $bb = ''; }
				
			}
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = '';
			$data['rows'][$i]['cell'][] = (isset($delivery_tmc['nomer_nakladnoy']) ? $delivery_tmc['nomer_nakladnoy'] : "Удалите накладную");
			$data['rows'][$i]['cell'][] = $b.@$adminuser['fio'].$bb;
			$data['rows'][$i]['cell'][] = $sum;	
			$data['rows'][$i]['cell'][] = get_valute_tmc(@$delivery_tmc['id_valute']);			
			$data['rows'][$i]['cell'][] = $sum_ye;			
			$data['rows'][$i]['cell'][] = $b.Database::getField($this->_table_kon,$item['id_kontragent']).$bb;			
			$data['rows'][$i]['cell'][] = $b.Database::getField(get_table('sklad'),@$delivery_tmc['id_sklad']).$bb;			
			$data['rows'][$i]['cell'][] = $item['date_create'];		
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {

		$data = array();
			
		if (isset($_POST['action'])) {
		
			$date_delivery = (empty($_POST['date_delivery'])) ? date("Y-m-d") : $_POST['date_delivery'];
			
			$data_tmc = array( 	  
				'id_adminuser' => $_SESSION['isLoggedIn']['id'],
				'date_delivery' => $date_delivery,
				'nomer_nakladnoy' => $_POST['nomer_nakladnoy'],
				'id_sklad' => $_POST['id_sklad'],
				'id_valute' => $_POST['id_valute'],
				'kurs' => 1,
				'id_suppliers' => 101,				
				'return_tmc' => 1,				
				'id_storekeepers' => $_POST['id_storekeepers']
				);		
	
			
			// ДОБАВИТЬ элемент в таблицу
			if ($_POST['action']=="add") {
				Database::insert($this->_table_delivery,$data_tmc);	
				$id_delivery_tmc = Database::getLastId($this->_table_delivery);
				$data = array(
					'id_delivery_tmc' => $id_delivery_tmc,
					'id_kontragent' => $_POST['id_kontragent'],
					'date_create' => $date_delivery
				);
				Database::insert($this->_table,$data);
				$id_return_tmc = Database::getLastId($this->_table);			
			}

			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="edit") {
				//$where_tmc = "id=".$_POST['id_delivery_tmc'];
				//Database::update($this->_table_delivery,$data_tmc,$where_tmc);
				
				$id_delivery_tmc = $_POST['id_delivery_tmc'];
				$id_return_tmc = $_POST['id'];
				
				$data = array('id_kontragent' => $_POST['id_kontragent']);				
				$where = '`id` = '.$_POST['id']; 
				Database::update($this->_table,$data,$where);	
					
			}
			
			$arr = array();
			$arr['last_id_delivery'] = $id_delivery_tmc;
			$arr['last_id'] = $id_return_tmc;
			$arr['action'] = @$_POST['action'];
			echo json_encode($arr);		
			
		}
		
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Database::delete($this->_table,$_POST['del_id']);
		}	
		
	
	}

	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);
		$delivery_tmc = Database::getRow($this->_table_delivery,$data['id_delivery_tmc']);
		$data['id_delivery_tmc'] = $delivery_tmc['id'];
		$data['date_delivery'] = $delivery_tmc['date_delivery'];
		$data['id_sklad'] = $delivery_tmc['id_sklad'];
		$data['nomer_nakladnoy'] = $delivery_tmc['nomer_nakladnoy'];
		$data['id_valute'] = $delivery_tmc['id_valute'];
		$data['block_edit'] = $delivery_tmc['block_edit'];
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
				$table_where = get_table('couriers').' as t1 JOIN '.get_table('zakaz').' as t2 ON t1.id_client = t2.id_client';
				$where = 'id_couriers='.$_GET['id_kontragent'].' and t2.vozvrat = 0 and predzakaz = 0 and id_catalog='.$item['id']; 
				$elems = Database::getSQL('SELECT t1.id_client, t2.* FROM '.$table_where.' WHERE '.$where.' ORDER BY id desc');
				$prefix = Database::getRow($table_pfx,$item['id_prefix']);
				$maker = Database::getRow($table_maker,$item['id_maker']);
				foreach($elems as $elem) {
					$date_dostavka = Database::getField(get_table('zakaz_client'),$elem['id_client'],'id','date_dostavka');
					$data['rows'][$i]['id'] = $elem['id'];
					$data['rows'][$i]['cell'][] = ' ';			
					$data['rows'][$i]['cell'][] = @$prefix['name'];
					$data['rows'][$i]['cell'][] = @$maker['name'];
					$data['rows'][$i]['cell'][] = $item['name'];
					$data['rows'][$i]['cell'][] = $elem['cena'];
					$data['rows'][$i]['cell'][] = transform_norm_date($date_dostavka);
					$data['rows'][$i]['cell'][] = $elem['nomer_zakaza'];
					$i++;		
				}
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
		$where = "`id_delivery_tmc` = $id_delivery_tmc";
		
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

			$prefix = Database::getRow(get_table('prefix'),@$tovar['id_prefix']);
			$maker = Database::getRow(get_table('maker'),@$tovar['id_maker']);
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = @$prefix['name'];
			$data['rows'][$i]['cell'][] = @$maker['name'];
			$data['rows'][$i]['cell'][] = @$tovar['name'];
			$data['rows'][$i]['cell'][] = $kolvo;
			$data['rows'][$i]['cell'][] = $item['cena'];					
			$data['rows'][$i]['cell'][] = $item['cena_sell'];					
			$data['rows'][$i]['cell'][] = @$sum_opt;
			$data['rows'][$i]['cell'][] = @$cena_ye;				
			$data['rows'][$i]['cell'][] = @$sum_ye;				
			$data['rows'][$i]['cell'][] = $tovar['cena'];
			$data['rows'][$i]['cell'][] = Database::getField(get_table('zakaz'),$item['id_zakaz'],'id','nomer_zakaza');			
			$i++;

		}

		echo json_encode($data);
	
	}
		
	public function edit_tovarAction() {
	
		if (isset($_POST['action']) and $_POST['action']=="add")  {
		
			$return_tmc = Database::getRow($this->_table,$_POST['id_delivery_tmc']);

			$zakaz = Database::getRow(get_table('zakaz'),$_POST['id_zakaz']);
			
			if ($_POST['kolvo_hold'] <= $zakaz['kolvo']) {
				
				$data = array( 	 
					'cena' => get_summa_zakupka($zakaz),
					'cena_sell' => $_POST['cena'],				
					'id_delivery_tmc' => $_POST['id_delivery_tmc'],
					'id_sklad' => $_POST['id_sklad'],				
					'id_item' => $zakaz['id_item'],
					'kolvo_hold' => $_POST['kolvo_hold'],
					'status' => 0,
					'id_zakaz' => $_POST['id_zakaz']
					);	

				Database::insert($this->_table_tc,$data);
				
			}
		}	
		
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['oper']) and $_POST['oper']=="del")  {
			$id = $_POST['id'];
			$sklad_tovar = Database::getRow($this->_table_tc,$id);
			if ($sklad_tovar['status']!=2)	Database::delete($this->_table_tc,$id);
		}
		
	}

	public function block_editAction() {
		
		if (isset($_POST['block_edit'])) {
			
			$data = array();
			$id = $_POST['id_delivery_tmc'];
			$where = "id_delivery_tmc = $id";
			$sklad_tovars = Database::getRows($this->_table_tc,'id','asc',Database::getCount($this->_table_tc,$where),$where);
			$sum = 0;
			$fl = false;
			$data['succes'] = true;
			foreach($sklad_tovars as $s_tovar) {
				if ($s_tovar['status'] != 2) {
					$sum += $s_tovar['cena'] * $s_tovar['kolvo_hold'];	
					$fl = true;
				}
			}			
			if ($fl) {
				
				$kontragent = Database::getRow(get_table('kontragenty'),$_POST['id_kontragent']);
				
				if ($kontragent['id_tip']=='11') {
					/* Диллеры */
					$post = array(
						'id_couriers' => $_POST['id_kontragent'],
						'operation' => 1,
						'cena_usd' => $sum,
						'id_delivery_tmc' => $id
					);			
					$data = insert_kassa_by_kontragent($post);				
				} else {
					$post = array(
						'id_couriers' => $_POST['id_kontragent'],
						'operation' => 2,
						'cena_usd' => $sum,
						'id_delivery_tmc' => $id
					);			
					$data = insert_kassa_by_kontragent($post);					
				}
				

			}
			if ($data['succes']==true) {
				
				$where_tovar = "`id_delivery_tmc` = $id and `status` <> 2";
				$data_tovar = array('status' => 2);
				Database::update($this->_table_tc,$data_tovar,$where_tovar);

				foreach($sklad_tovars as $s_tovar) {
					$product = Database::getRow(get_table('catalog'),$s_tovar['id_item']);				
					$new_kolvo = $s_tovar['kolvo_hold'] + $product['kolvo'];
					Database::update(get_table('catalog'),array('kolvo'=>$new_kolvo),'id='.$product['id']);
					Database::update(get_table('zakaz'),array('vozvrat'=>$s_tovar['id_sklad']),'id='.$s_tovar['id_zakaz']);
				}				

				$data_tovar = array('block_edit' => $_POST['block_edit']);
				Database::update($this->_table_delivery,$data_tovar,"id = $id");
			}
			echo json_encode($data);
		}
		
	}
	
	public function get_cenaAction() {
		
		if (isset($_POST['id_zakaz'])) {
			$zakaz = Database::getRow(get_table('zakaz'),$_POST['id_zakaz']);
			echo json_encode($zakaz);
		}
		
	}
  
	
	public function get_html_oplataAction() {
		if (isset($_POST['id']) and !empty($_POST['id'])) {
			$data = array();
			$data = Database::getRow($this->_table_delivery,$_POST['id']);
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
			$data['kontragent'] = Database::getField($this->_table_kon,$_POST['id_kontragent']);
			echo json_encode($data);				
		}
	}	
	
	public function oplataAction() {
		
		$data = array();
		
		if (isset($_POST['with_kassa'])) {
			Database::update($this->_table_delivery,array('oplata_from_kassa' => 1),"id=".$_POST['id']);
			$data['succes'] = true;
		} else {
			$id = $_POST['id'];			
			$delivery_tmc = Database::getRow($this->_table_delivery,$id);
			if ($delivery_tmc['oplata_from_kassa'] == 0) {

				$where = "id_delivery_tmc = $id";
				$sklad_tovars = Database::getRows($this->_table_tc,'id','asc',Database::getCount($this->_table_tc,$where),$where);
				$sum = 0;
				foreach($sklad_tovars as $s_tovar) {
					$sum += $s_tovar['cena'] * $s_tovar['kolvo_hold'];	
				}			
				$kontragent_kassa = 0;
				$admin_kassa = 0;
				
				$kontragent_kassa = Database::getField(get_table('kassa_tree'),$_POST['id_kontragent'],'id_kontragenty','id');
				$admin_kassa = Database::getField(get_table('adminusers'),$_SESSION['isLoggedIn']['id'],'id','id_kassa_tree');
				
				$kontragent = Database::getRow(get_table('kontragenty'),$_POST['id_kontragent']);

				$post = array(
					'id_couriers' => $_POST['id_kontragent'],
					'operation' => 2			
				);		
				if ($_POST['id_valute']==1) $post['cena_rur'] = $sum; 
				if ($_POST['id_valute']==2) $post['cena_blr'] = $sum;
				if ($_POST['id_valute']==6) $post['cena_eur'] = $sum;
				if ($_POST['id_valute']==7) $post['cena_usd'] = $sum;
				
				$data = insert_kassa_by_kontragent($post);
								
				if ($kontragent['id_tip']==11) {
					/*  Диллеры  */

					$admin_kontragent = Database::getRow(get_table('kassa_tree'),$admin_kassa);
					
					$post['id_couriers'] = $admin_kontragent['id_kontragenty'];
					
					$data = insert_kassa_by_kontragent($post);
					
				} 	
			
				if ($data['succes']==true) {
					Database::update($this->_table_delivery,array('oplata_from_kassa' => 1),"id=$id");
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