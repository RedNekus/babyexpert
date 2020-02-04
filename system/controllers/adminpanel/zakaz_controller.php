<?php

class Zakaz_Controller {
	private $_table_zakaz, $_table, $_content;

	public function __construct() {
		Load::model(array('Zakaz_client', 'Catalog', 'Prefix', 'Couriers'));
		$this->_table_zakaz = get_table('zakaz');
		$this->_table = get_table('zakaz_client');
		$this->_content['title'] = 'Заказы сайта';
	}

	public function defaultAction() {

		$postavshik = Database::getRows(get_table('kontragenty'),'name','asc',false,'id_tip = 2');
		$dilers = Database::getRows(get_table('registration'),'name','asc',false,"diler > 0");
	
		$this->_content['content'] = Render::view(
					'adminpanel/zakaz/list',array(
						'postavshik' => $postavshik,
						'dilers' => $dilers
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

		$count = Zakaz_client::getTotalClient();  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) {
			$zakaz_list = Zakaz_client::searchAdmin($searchField, $searchString);
		} else {
			$zakaz_list = Zakaz_client::getClient($sidx, $sord, $data['limit']);
		}

		$i = 0;
		foreach($zakaz_list as $item) {
			if ($item['active']==0) { $b = '<b>'; $bb = '</b>'; }
			elseif ($item['dostavka']==1) { $b = ''; $bb = ''; }
			elseif ($item['dumayut']==1) { $b = '<span style="color:blue">'; $bb = '</span>'; }
			else { $b = '<span style="color:red">'; $bb = '</span>'; }

			$adminuser = Database::getRow(get_table('adminusers'),$item['id_adminuser']);
		
			$adres = get_adres_client($item);
			
			$phones = get_phone($item['phone']);
			
			$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,'id_client = '.$item['id']);
						
			$summ = 0;
			$kolvo = 0;
			$name_tovar = '';
			$kolvo_only = '';
			foreach($zakazs as $zakaz) {
				$summ += $zakaz['cena'] * $zakaz['kolvo'];
				$kolvo += $zakaz['kolvo'];

				if (in_array($zakaz['vozvrat'],array(1,2,3))) $style = 'style="color:green"';				
				elseif ($zakaz['predzakaz'] == 1) $style = 'style="color:grey"';
				elseif ($zakaz['delivered'] == 1) $style = 'style="color: blue;"';				
				elseif ($zakaz['rezerv'] == 1) $style = 'style="color:#A600BC"';
				elseif ($zakaz['nosell'] == 1) $style = 'style="color:orange;"';
				elseif (($zakaz['was'] == 1) and ($zakaz['delivered'] == 0)) $style = 'style="color:#CE0018;font-weight:bold;"';
				elseif (($zakaz['shipped'] == 0) and (!empty($item['comment_w']))) $style = 'style="color:orange;font-weight:bold"';
				else $style = '';
				
				$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);				
				$name = get_product_name($product,true,$zakaz['name_tovar']);					
				$name_tovar .= "<div $style>$name</div>";

				$kolvo_only .= "<div>".$zakaz['kolvo']."</div>";				
			}		

			$comment = '';
			$comment .= '<div>'.$item['comment'].'</div>';
			$comment .= '<b>'.$item['comment_m'].'</b>';

			$couriers = Database::getRow(get_table('couriers'),$item['id'],'id_client');
			$name_courier = get_courier_name(Database::getField(get_table('kontragenty'),@$couriers['id_couriers']));			
			
			$access = get_array_access();
			
			$diler = Database::getRow(get_table('registration'),$item['id_diler']);

			if ($access['zakaz_edit']==1) $be = '<div" rel="'.$item['id'].'" class="button edit editdata"></div>';
			else $be = '';

			$summ_with_skidka = get_summa_by_client($item);
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $be;
			$data['rows'][$i]['cell'][] = $b.$item['nomer_zakaza'].$bb;
			$data['rows'][$i]['cell'][] = $b.$name_courier.$bb;
			$data['rows'][$i]['cell'][] = $b.@$diler['login'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$adminuser['fio'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$kolvo_only.$bb;
			$data['rows'][$i]['cell'][] = $b.@$name_tovar.$bb;
			$data['rows'][$i]['cell'][] = $b.@$phones.$bb;
			$data['rows'][$i]['cell'][] = $b.@$adres.$bb;			
			$data['rows'][$i]['cell'][] = (($item['beznal']==1) ? 'Б' : '');			
			$data['rows'][$i]['cell'][] = $summ;
			$data['rows'][$i]['cell'][] = $summ_with_skidka['usd'];
			$data['rows'][$i]['cell'][] = @$kolvo;				
			$data['rows'][$i]['cell'][] = $item['time_zakaz'].'<div>'.$item['date_zakaz'].'</div>';		
			$data['rows'][$i]['cell'][] = $item['time_active'].'<div>'.$item['date_active'].'</div>';	
			$data['rows'][$i]['cell'][] = $item['date_dostavka'];	
			$data['rows'][$i]['cell'][] = $comment;		
			$data['rows'][$i]['cell'][] = (($item['active']==1) ? 'Да' : 'Нет');		
			$i++;

		}

		echo json_encode($data);
  }  
  
	public function editAction() {
	
		
		if (isset($_POST['action'])) {

			$active = $_POST['active'];
			$login  = $_POST['id_adminuser'];			

			$data_client = array(
				'phone' => $_POST['phone'],					
				'firstname' => $_POST['firstname'],
				'email' => $_POST['email'],
				'city' => $_POST['city'],
				'street' => $_POST['street'],
				'house' => $_POST['house'],
				'building' => $_POST['building'],
				'apartment' => $_POST['apartment'],
				'floor' => $_POST['floor'],
				'entrance' => $_POST['entrance'],				
				'active' => $active,
				'comment' => $_POST['comment'],
				'comment_m' => $_POST['comment_m'],			
				'samovivoz' => ((isset($_POST['samovivoz'])) ? 1 : 0),				
				'samovivoz_ofice' => ((isset($_POST['samovivoz_ofice'])) ? 1 : 0),				
				'dostavka' => ((isset($_POST['dostavka'])) ? 1 : 0),				
				'id_adminuser' => $login,
				'code_zayavka' => $_POST['code_zayavka'],	
				'poselok' => $_POST['poselok'],	
				'print_ready' => ((isset($_POST['print_ready'])) ? 1 : 0),					
				'sposob_dostavki' => $_POST['sposob_dostavki'],	
				'doplata_usd' => $_POST['doplata_usd'],					
				'doplata_blr' => $_POST['doplata_blr'],					
				'dumayut' => ((isset($_POST['dumayut'])) ? 1 : 0),					
				'beznal' => ((isset($_POST['beznal'])) ? 1 : 0),					
				'id_diler' => $_POST['id_diler']				
			);	
			
			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="add") {

				$no = Zakaz_client::getLastNomer();
				$no++;

				$data_client['nomer_zakaza'] = $no;
				$data_client['date_active'] = ((!empty($_POST['date_active'])) ? $_POST['date_active'] : date('Y-m-d'));
				$data_client['time_active'] = ((!empty($_POST['time_active'])) ? $_POST['time_active'] : date('Y-m-d'));
				$data_client['date_dostavka'] = ((!empty($_POST['date_dostavka'])) ? $_POST['date_dostavka'] : date('Y-m-d'));
				Database::insert($this->_table,$data_client);
					
			}
			
			// РЕДАКТИРОВАТЬ элемент в таблице
			if ($_POST['action']=="edit") {
		
				$date_dostavka = $_POST['date_dostavka'];				

				$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,'id_client = '.$_POST['id']);
				if ((isset($_POST['samovivoz'])) or (isset($_POST['samovivoz_ofice']))) {
					send_kladovshik_sms($_POST,$date_dostavka,$zakazs);	
				}
				$data = array();
				$data['success'] = true;
				$tovars = '';
				foreach($zakazs as $zakaz) {

					$id_item = $zakaz['id_item'];
					$so = get_free_ostatok($id_item);
					$r1 = get_vozvrat_na_sklad($id_item);
					if ($so < $zakaz['kolvo']) {
						if ($zakaz['predzakaz']!=1 and isset($_POST['dostavka'])) {	
							if ($r1 >= $zakaz['kolvo']) continue;
							$data['success'] = false;	
							
							$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);
							$name = get_product_name($product,false,$zakaz['name_tovar']);
					
							$tovars .= "<div style=\"color:red;\">$name</div>";
						}
					}		
				}
				
				if ($data['success'] == false) $data['msg'] = "Товара $tovars нет на складе!";
				
				if ($data['success']) {
	
					if ($_POST['id_diler']>0) update_to_diler_cena($_POST);
										
					$data_client['nomer_zakaza'] = $_POST['nomer_zakaza'];	
					$data_client['date_dostavka'] = $date_dostavka;	
					$where = 'id = '.$_POST['id'];
					Database::update($this->_table,$data_client,$where);
						
					$sum = get_summa_by_client(Database::getRow($this->_table,$_POST['id']));
					
					if ($_POST['id_diler']>0) {
						$cena_rozn_usd = $sum['usd'];
					} else {
						$cena_rozn_usd = $sum['bur'] / get_kurs();
					}
					
					$data_client = array('cena_rozn_usd'=>$cena_rozn_usd);
					Database::update($this->_table,$data_client,$where);
					
				}
	
				echo json_encode($data);
			}

		}		
		
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			$client = Database::getRow($this->_table,$_POST['del_id']);
			$delivered = false;
			foreach(Database::getRows($this->_table_zakaz,'id','asc',false,'id_client = '.$client['id']) as $zakaz) {
				if ($zakaz['delivered']==1) $delivered = true;
			}
			if ($delivered == false) Database::delete($this->_table,$_POST['del_id']);
		}
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$id = $_POST['id'];
		$zakaz_client = Database::getRow($this->_table,$id);		
		
		if ($zakaz_client['active']==0) {
			$data_client = array(
				'active' => 1,
				'id_adminuser' => $_SESSION['isLoggedIn']['id'],
				'date_active' => date('Y-m-d'),				
				'time_active' => date('G:i:s'),				
				'date_dostavka' => date('Y-m-d')
				);
			Database::update($this->_table,$data_client,'id = '.$id);	
		}	

		$courier = Database::getRow(get_table('couriers'),$id,'id_client');		
		$zakaz_client = Database::getRow($this->_table,$id);
		$zakaz_client['id_couriers'] = @$courier['id'];
		$zakaz_client['stop_save'] = ((isset($courier['id'])) and ($courier['id'] != 0)) ? true : false;
		$zakaz_client['comment_c'] = @$courier['comment'];		
		$sum = get_summa_by_client($zakaz_client);
		$zakaz_client['sum_usd'] = $sum['usd'];
		$zakaz_client['sum_blr'] = $sum['bur'];
		echo json_encode($zakaz_client);
	}  

	//Получение данных по id в форму для добавления товара
	public function dataprintAction() {
		$zakaz = array();
		$zakaz['html'] = get_print_form_to_date($_GET['date_zakaz'],@$_GET['cur_id']);
		echo json_encode($zakaz);
	}
	
	//Получение данных по id в форму для добавления товара
	public function datagarantAction() {
		$html = get_garant_talon($_GET['id']);
		echo json_encode($html);
	}

	public function predzakazAction() {
		$postavshik = Database::getRows(get_table('kontragenty'),'name','asc',false,'id_tip = 2');
	
		$this->_content['title'] = 'Предзаказ';	
		$this->_content['content'] = Render::view(
					'adminpanel/zakaz/predzakaz',array(
						'postavshik' => $postavshik
					));

		Render::layout('adminpanel/adminpanel', $this->_content);
	} 	

    public function load_predzakazAction() {
		// Начало формирование объекта
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово	

		$table_zakaz = $this->_table_zakaz;
		$table_client = $this->_table;
		$tbl = "$table_zakaz as t1 LEFT JOIN $table_client as t2 on t1.id_client = t2.id";
		$where = "t1.predzakaz = 1";
		
		if ($searchField == "t1.id_postavshik") {
			$postavshik = Database::getRow(get_table('kontragenty'),$searchString,'name');
			$searchString = $postavshik['id'];
		}	
		
		$count = Database::getCount($tbl,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($tbl, $searchField, $searchOper, $searchString," and ".$where);
		else $items = Database::getRows($tbl, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		foreach($items as $item) {
			if ($item['active']==0) { $b = '<b>'; $bb = '</b>'; }
			elseif ($item['dostavka']==1) { $b = ''; $bb = ''; }
			elseif ($item['dumayut']==1) { $b = '<span style="color:blue">'; $bb = '</span>'; }
			else { $b = '<span style="color:red">'; $bb = '</span>'; }

			$adminuser = Database::getRow(get_table('adminusers'),$item['id_adminuser']);
		
			$adres = get_adres_client($item);

			$phones = get_phone($item['phone']);

			$summ = $item['cena'] * $item['kolvo'];
			$kolvo = $item['kolvo'];
				
			$product = Database::getRow(get_table('catalog'),$item['id_catalog']);			
			$name = get_product_name($product,false,$item['name_tovar']);
			
			$style = ($item['active_predzakaz']==0) ? 'style="color:green"' : 'style="color:black"'; 
			$name_tovar = "<div $style>$name</div>";		

			$postavshik = Database::getField(get_table('kontragenty'),$item['id_postavshik']);
			
			$access = get_array_access();			
			if ($access['zakaz_edit']==1) $be = '<div" rel="'.$item['id'].'" class="button edit editdata"></div>';
			else $be = '';
				
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $be;
			$data['rows'][$i]['cell'][] = $b.$item['nomer_zakaza'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$adminuser['fio'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$postavshik.$bb;
			$data['rows'][$i]['cell'][] = $b.@$name_tovar.$bb;
			$data['rows'][$i]['cell'][] = $b.@$phones.$bb;	
			$data['rows'][$i]['cell'][] = $item['kolvo'];			
			$data['rows'][$i]['cell'][] = $item['time_zakaz'].'<div>'.$item['date_zakaz'].'</div>';			
			$data['rows'][$i]['cell'][] = $item['date_predzakaz'];	
			$data['rows'][$i]['cell'][] = $item['comment_m'];			
			$i++;

		}

		echo json_encode($data);
  
	} 
	
	public function rezervAction() {

		$postavshik = Database::getRows(get_table('kontragenty'),'name','asc',false,'id_tip = 2');
		
		$this->_content['title'] = 'Резерв';
		$this->_content['content'] = Render::view(
					'adminpanel/zakaz/rezerv',array(
						'postavshik' => $postavshik
					));

		Render::layout('adminpanel/adminpanel', $this->_content);
	} 	

    public function load_rezervAction() {
		// Начало формирование объекта
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		

		$table_zakaz = $this->_table_zakaz;
		$table_client = $this->_table;
		$tbl = "$table_zakaz as t1 LEFT JOIN $table_client as t2 on t1.id_client = t2.id";
		$where = "t1.rezerv = 1";

		$count = Database::getCount($tbl,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($tbl, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($tbl, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		foreach($items as $item) {
			if ($item['active']==0) { $b = '<b>'; $bb = '</b>'; }
			elseif ($item['dostavka']==1) { $b = ''; $bb = ''; }
			elseif ($item['dumayut']==1) { $b = '<span style="color:blue">'; $bb = '</span>'; }
			else { $b = '<span style="color:red">'; $bb = '</span>'; }

			$adminuser = Database::getRow(get_table('adminusers'),$item['id_adminuser']);
		
			$adres = get_adres_client($item);

			$phones = get_phone($item['phone']);
			
			$summ = $item['cena'] * $item['kolvo'];
			$kolvo = $item['kolvo'];
				
			$product = Database::getRow(get_table('catalog'),$item['id_catalog']);			
			$name = get_product_name($product,false,$item['name_tovar']);
			
			$name_tovar = "<div>$name</div>";			
			
			$access = get_array_access();
			if ($access['zakaz_edit']==1) $be = '<div" rel="'.$item['id'].'" class="button edit editdata"></div>';
			else $be = '';
				
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $be;
			$data['rows'][$i]['cell'][] = $b.$item['nomer_zakaza'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$adminuser['fio'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$name_tovar.$bb;
			$data['rows'][$i]['cell'][] = $b.@$phones.$bb;	
			$data['rows'][$i]['cell'][] = $item['kolvo'];			
			$data['rows'][$i]['cell'][] = $item['time_zakaz'].'<div>'.$item['date_zakaz'].'</div>';			
			$data['rows'][$i]['cell'][] = $item['date_rezerv'];	
			$data['rows'][$i]['cell'][] = $item['comment_m'];		
			$i++;

		}

		echo json_encode($data);
  
	} 	
	
	public function vitekAction() {

		$postavshik = Database::getRows(get_table('kontragenty'),'name','asc',false,'id_tip = 2');
		$dilers = Database::getRows(get_table('registration'),'name','asc',false,"diler > 0");

		$this->_content['title'] = 'Витя';
		$this->_content['content'] = Render::view(
					'adminpanel/zakaz/vitek',array(
						'postavshik' => $postavshik,
						'dilers' => $dilers
					));

		Render::layout('adminpanel/adminpanel', $this->_content);
	} 	

    public function load_vitekAction() {
		// Начало формирование объекта
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово	
		
		$where = 'id_diler = 772';
		
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);

		
		if (@$searchField) {
			$zakaz_list = Zakaz_client::searchAdmin($searchField, $searchString);
		} else {
			$zakaz_list = Database::getRows($this->_table, $sidx, $sord, $data['limit'], $where);
		}

		$i = 0;
		$sum_raznica = 0;		
		foreach($zakaz_list as $item) {
			if ($item['active']==0) { $b = '<b>'; $bb = '</b>'; }
			elseif ($item['dostavka']==1) { $b = ''; $bb = ''; }
			elseif ($item['dumayut']==1) { $b = '<span style="color:blue">'; $bb = '</span>'; }
			else { $b = '<span style="color:red">'; $bb = '</span>'; }

			$adminuser = Database::getRow(get_table('adminusers'),$item['id_adminuser']);
		
			$adres = get_adres_client($item);
			
			$phones = get_phone($item['phone']);
			
			$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,'id_client = '.$item['id']);
						
			$summ = 0;
			$kolvo = 0;
			$sum_product_cena = 0;
			$sum_cena_vozvrat = 0;
			$name_tovar = '';
			$kolvo_only = '';
			foreach($zakazs as $zakaz) {
	
				if (in_array($zakaz['vozvrat'],array(1,2,3))) {
					$sum_cena_vozvrat += with_skidka($zakaz);	
					$style = 'style="color: green;"';
				} elseif ($zakaz['delivered'] == 1) $style = 'style="color: blue;"';
				elseif ($zakaz['predzakaz'] == 1) $style = 'style="color:grey"';
				elseif ($zakaz['rezerv'] == 1) $style = 'style="color:#A600BC"';
				elseif ($zakaz['nosell'] == 1) $style = 'style="color:orange;"';
				elseif (($zakaz['was'] == 1) and ($zakaz['delivered'] == 0)) $style = 'style="color:#CE0018;font-weight:bold;"';
				elseif (($zakaz['shipped'] == 0) and (!empty($item['comment_w']))) $style = 'style="color:orange;font-weight:bold"';
				else $style = '';
				
				$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);				
				$name = get_product_name($product,true,$zakaz['name_tovar']);
				//$sum_product_cena += $zakaz['cena_rozn_usd'] * $zakaz['kolvo'];
				$name_tovar .= "<div $style>$name</div>";

				$kolvo_only .= "<div>".$zakaz['kolvo']."</div>";				
			}		

			if ($sum_cena_vozvrat > 0) {
				$doplata_vozvrat = round($item['doplata_blr'] / get_kurs());
				$sum_product_cena = $item['cena_rozn_usd'] - $sum_cena_vozvrat - $doplata_vozvrat;
			} else {
				$sum_product_cena = $item['cena_rozn_usd'];
			}
			

			$comment = '';
			$comment .= '<div>'.$item['comment'].'</div>';
			$comment .= '<b>'.$item['comment_m'].'</b>';

			$couriers = Database::getRow(get_table('couriers'),$item['id'],'id_client');
			$name_courier = get_courier_name(Database::getField(get_table('kontragenty'),@$couriers['id_couriers']));			
			
			$diler = Database::getRow(get_table('registration'),$item['id_diler']);
			
			$access = get_array_access();

			if ($access['zakaz_edit']==1) $be = '<div" rel="'.$item['id'].'" class="button edit editdata"></div>';
			else $be = '';

			$summ_with_skidka = get_summa_by_client($item);
			$cena_dostavka = cena_dostavka($item);
			$cena_dostavka_sluzhba = cena_dostavka_sluzhba($item);
			
			$raznica = $sum_product_cena - $summ_with_skidka['usd'] - $cena_dostavka - $cena_dostavka_sluzhba;
			$sum_raznica += $raznica;
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $be;
			$data['rows'][$i]['cell'][] = $b.$item['nomer_zakaza'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$adminuser['fio'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$name_tovar.$bb;
			$data['rows'][$i]['cell'][] = $b.@$adres.$bb;
			$data['rows'][$i]['cell'][] = $b.@$kolvo_only.$bb;
			$data['rows'][$i]['cell'][] = $sum_product_cena;
			$data['rows'][$i]['cell'][] = $summ_with_skidka['usd'];
			$data['rows'][$i]['cell'][] = $cena_dostavka;				
			$data['rows'][$i]['cell'][] = $cena_dostavka_sluzhba;
			$data['rows'][$i]['cell'][] = $raznica;	
			$data['rows'][$i]['cell'][] = $item['date_zakaz'];	
			$data['rows'][$i]['cell'][] = $comment;		
			$data['rows'][$i]['cell'][] = (($item['active']==1) ? 'Да' : 'Нет');		
			$i++;

		}
		
		$data['userdata']['sum_raznica'] = $sum_raznica;
		
		echo json_encode($data);
  
	} 

	
	public function statusAction() {

		$postavshik = Database::getRows(get_table('kontragenty'),'name','asc',false,'id_tip = 2');
		
		$this->_content['title'] = 'Статус';
		$this->_content['content'] = Render::view(
					'adminpanel/zakaz/status',array(
						'postavshik' => $postavshik
					));

		Render::layout('adminpanel/adminpanel', $this->_content);
	} 	

    public function load_statusAction() {
		// Начало формирование объекта
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		

		$table_zakaz = $this->_table_zakaz;
		$table_client = $this->_table;
		$tbl = "$table_zakaz as t1 LEFT JOIN $table_client as t2 on t1.id_client = t2.id";
		$where = "1";
		
		if (isset($_GET['manager']) and !empty($_GET['manager'])) {
			$row = Database::getRow(get_table('adminusers'),$_GET['manager'],'id_manager');
			if (!empty($row['id_manager'])) $where .= ' AND id_adminuser = '.$row['id'];	
		}
		if (isset($_GET['status']) and !empty($_GET['status'])) {
			$status = $_GET['status'];
			if ($status == 1) $where .= ' AND otkaz = 1';
			if ($status == 2) $where .= ' AND nosell = 1';
			if ($status == 3) $where .= ' AND shipped = 1';
			if ($status == 4) $where .= ' AND predzakaz = 1';
			if ($status == 5) $where .= ' AND rezerv = 1';
			if ($status == 6) $where .= ' AND delivered = 1';	
			if ($status == 7) $where .= ' AND was = 1 AND delivered = 1';
			if ($status == 8) $where .= ' AND was = 1 AND delivered = 1 AND vozvrat = 1';
			if ($status == 9) $where .= ' AND vozvrat IN(1,2,3)';		
		}
		if (isset($_GET['diler']) and !empty($_GET['diler'])) $where .= ' AND id_diler = '.$_GET['diler']; 		
		if (isset($_GET['date_ot']) and !empty($_GET['date_ot'])) $where .= ' and "'.$_GET['date_ot'].'" <= date_zakaz';
		if (isset($_GET['date_do']) and !empty($_GET['date_do'])) $where .= ' and "'.$_GET['date_do'].'" >= date_zakaz';

		
		$count = Database::getCount($tbl,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($tbl, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($tbl, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		foreach($items as $item) {
			if ($item['active']==0) { $b = '<b>'; $bb = '</b>'; }
			elseif ($item['dostavka']==1) { $b = ''; $bb = ''; }
			elseif ($item['dumayut']==1) { $b = '<span style="color:blue">'; $bb = '</span>'; }
			else { $b = '<span style="color:red">'; $bb = '</span>'; }

			$adminuser = Database::getRow(get_table('adminusers'),$item['id_adminuser']);		
			$diler = Database::getRow(get_table('registration'),$item['id_diler']);	
					
			$adres = get_adres_client($item);

			$phones = get_phone($item['phone']);
			
			$summ = $item['cena'] * $item['kolvo'];
			$kolvo = $item['kolvo'];
				
			$product = Database::getRow(get_table('catalog'),$item['id_catalog']);			
			$name = get_product_name($product,false,$item['name_tovar']);
			
			$name_tovar = "<div>$name</div>";			
			
			$access = get_array_access();
			if ($access['zakaz_edit']==1) $be = '<div" rel="'.$item['id'].'" class="button edit editdata"></div>';
			else $be = '';
		
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $be;
			$data['rows'][$i]['cell'][] = $b.$item['nomer_zakaza'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$adminuser['fio'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$diler['name'].$bb;
			$data['rows'][$i]['cell'][] = $b.@$name_tovar.$bb;
			$data['rows'][$i]['cell'][] = $b.@$phones.$bb;	
			$data['rows'][$i]['cell'][] = $item['kolvo'];			
			$data['rows'][$i]['cell'][] = transform_norm_date($item['date_zakaz']);			
			$data['rows'][$i]['cell'][] = $item['time_zakaz'];			
			$data['rows'][$i]['cell'][] = get_status_zakaz($item);	
			$data['rows'][$i]['cell'][] = $item['comment_m'];		
			$i++;

		}

		echo json_encode($data);
  
	} 	
		
	public function getselecthtmlAction() {
		get_select_html($_POST['method']);
	} 	
	
	public function statsAction() {

		$postavshik = Database::getRows(get_table('kontragenty'),'name','asc',false,'id_tip = 2');
		$dilers = Database::getRows(get_table('registration'),'name','asc',false,"diler > 0");

		$this->_content['title'] = 'Статистика продаж';
		$this->_content['content'] = Render::view(
					'adminpanel/zakaz/stats',array(
						'postavshik' => $postavshik,
						'dilers' => $dilers
					));

		Render::layout('adminpanel/adminpanel', $this->_content);
	} 	

    public function load_statsAction() {
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
		
		if (isset($_GET['manager']) and !empty($_GET['manager'])) {
			$where_tmp = 'id_manager  IN ('.implode(",", $_GET['manager']).')';
			$rows = Database::getRows(get_table('adminusers'),'id','asc',false,$where_tmp);
			$adminuser_ids = '';
			foreach($rows as $rs) $adminuser_ids .= ','.$rs['id'];			
			$where .= ' and id_adminuser IN ('.substr($adminuser_ids, 1).')';	
		}				
		if (isset($_GET['date_ot']) and !empty($_GET['date_ot'])) $where .= ' and "'.$_GET['date_ot'].'" <= date_zakaz';
		if (isset($_GET['date_do']) and !empty($_GET['date_do'])) $where .= ' and "'.$_GET['date_do'].'" >= date_zakaz';

		$where_dil = '';
		
		if (isset($_GET['our_sell'])) $where_dil .= ' or id_diler = 0';	
		if (isset($_GET['diler_urozhay'])) $where_dil .= ' or id_diler IN ('.get_tree_by_diler(56).')';
		if (isset($_GET['diler_teplich'])) $where_dil .= ' or id_diler IN ('.get_tree_by_diler(40).')';
		if (isset($_GET['diler']) and !empty($_GET['diler'])) $where_dil .= ' or id_diler IN ('.implode(",", $_GET['diler']).')';			

		if (!empty($where_dil)) $where .= ' and ('.substr($where_dil,4).')';
		
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);

		$sum_raznica = 0;		
		$sum_zakup_total = 0;		
		$sum_dostavka_total = 0;		
		$sum_product_cena_total = 0;		
		
		foreach(Database::getRows($this->_table, $sidx, $sord, false, $where) as $item) {
			$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,'id_client = '.$item['id'].' and otkaz = 0');		
			$sum_zakup = 0;
			foreach($zakazs as $zakaz) {
				$sum_zakup += get_summa_zakupka($zakaz);
			}		

			$sum_dostavka = cena_dostavka($item) + cena_dostavka_sluzhba($item);
			$sum_skidka = get_summa_by_client($item);
			$sum_product_cena = (($item['cena_rozn_usd']>0) ? $item['cena_rozn_usd'] : $sum_skidka['usd']);

			$raznica = $sum_product_cena - $sum_zakup;
			
			$sum_raznica += $raznica;
			$sum_zakup_total += $sum_zakup;
			$sum_dostavka_total += $sum_dostavka;
			$sum_product_cena_total += $sum_product_cena;
			
		}

		if (@$searchField) {
			$zakaz_list = Zakaz_client::searchAdmin($searchField, $searchString);
		} else {
			$zakaz_list = Database::getRows($this->_table, $sidx, $sord, $data['limit'], $where);
		}

		$i = 0;
		
		foreach($zakaz_list as $item) {
		
			$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,'id_client = '.$item['id'].' and otkaz = 0');
			$sum_zakup = 0;
			$name_tovar = '';
			//$cena_tovar = '';

			foreach($zakazs as $zakaz) {
				$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);				
				$name = get_product_name($product,true,$zakaz['name_tovar']);
				$name_tovar .= "<div>$name</div>";
				$sum_zakup += get_summa_zakupka($zakaz);
				
				//$cena_tovar .= "<div>".get_summa_zakupka($zakaz)."</div>";
			}		

			$sum_dostavka = cena_dostavka($item) + cena_dostavka_sluzhba($item);
			$sum_skidka = get_summa_by_client($item);
			$sum_product_cena = (($item['cena_rozn_usd']>0) ? $item['cena_rozn_usd'] : $sum_skidka['usd']);
			$raznica = $sum_product_cena - $sum_zakup - $sum_dostavka;
	
			$adminuser = Database::getRow(get_table('adminusers'),$item['id_adminuser']);		
			$diler = Database::getRow(get_table('registration'),$item['id_diler']);	
						
			$access = get_array_access();

			if ($access['zakaz_edit']==1) $be = '<div" rel="'.$item['id'].'" class="button edit editdata"></div>';
			else $be = '';
			
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $be;
			$data['rows'][$i]['cell'][] = $item['nomer_zakaza'];
			$data['rows'][$i]['cell'][] = @$adminuser['fio'];
			$data['rows'][$i]['cell'][] = @$diler['name'];			
			$data['rows'][$i]['cell'][] = @$name_tovar;
			$data['rows'][$i]['cell'][] = $sum_product_cena;
			$data['rows'][$i]['cell'][] = $sum_zakup;
			$data['rows'][$i]['cell'][] = $sum_dostavka;
			$data['rows'][$i]['cell'][] = $raznica;	
			$data['rows'][$i]['cell'][] = $item['date_zakaz'];				
			$i++;

		}
		
		$data['userdata']['sum_product_cena_total'] = $sum_product_cena_total;
		$data['userdata']['sum_zakup_total'] = $sum_zakup_total;
		$data['userdata']['sum_dostavka_total'] = $sum_dostavka_total;
		$data['userdata']['sum_raznica'] = $sum_raznica;

		echo json_encode($data);
  
	} 
					
}