<?php

class Couriers_Controller {
	private $_config, $_content, $_table, $_table_zakaz;

	public function __construct() {
		Load::model(array('Catalog','Couriers','Zakaz_client','Zakaz','Currency_tree'));
		$this->_table = get_table('couriers');
		$this->_table_zakaz = get_table('zakaz');
		$this->_table_client = get_table('zakaz_client');
		$this->_table_catalog = get_table('catalog');
		$this->_table_kon = get_table('kontragenty');
		$this->_table_kon_tip = get_table('kontragenty_tip');
		$this->_content['title'] = 'Курьеры';
	}

	
	public function defaultAction() {
  
		$couriers = Database::getRows($this->_table_kon_tip,'name','asc',false,'couriers_show=1');
		$trees  = Database::getRows($this->_table_kon_tip);

		$this->_content['content'] = Render::view(
				'adminpanel/reference/couriers', array (
				'trees' => get_courier_tree_by_access($trees),
				'couriers' => $couriers,
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

		$access = get_array_access();

		$id_couriers  = (isset($_GET['id_tree']) and $_GET['id_tree']!='undefined') ? @$_GET['id_tree'] : $access['id_courier'];
		if ($id_couriers == 0 and $access['id'] != 1) $id_couriers = 999;
		
		$adopted = (@$_GET['adopted']) ? @$_GET['adopted'] : 0;

		$date_ot = @$_GET['date_ot'];
		$date_do = @$_GET['date_do'];
				
		if (@$searchField) {
			$count = Couriers::getTotalSearchAdmin($searchField, $searchString);  
		} else {
			$count = Couriers::getTotalCouriersByDate($id_couriers,$adopted,$date_ot,$date_do);
		}
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) {
			$couriers = Couriers::searchAdmin($searchField, $searchString,$data['limit']);
		} else {
			$couriers = Couriers::getCouriersByDate($id_couriers,$adopted,$date_ot,$date_do,$sidx,$sord,$data['limit']); 
		}

		$i = 0;

		$total_us = 0;
		$total_blr = 0;
		$total_zp = 0;
		$total_zp_blr = 0;
			
		foreach($couriers as $item) {

			$name_tovar = ''; 
			$b = ''; $bb = '';
			$summ_usd = 0;
			$summ_bur = 0;
			$total_usd = 0;
			$total_bur = 0;
			$passed = 0;
			
			$client = Database::getRow($this->_table_client,$item['id_client']);
			
			$adres = get_adres_client($client);
			
			$id_client = $client['id'];

			$where = "id_client = $id_client and shipped = 1 and vozvrat = 0";
			$zakazs = Database::getRows($this->_table_zakaz, 'id', 'asc', false, $where);
			
			if (empty($zakazs)) continue;
			foreach($zakazs as $zakaz) {
				$style='';
				if ($zakaz['delivered']==0) $style='style="color: red;"';
				if ($zakaz['delivered']==1) $style='style="color: black;"';
				if ($zakaz['passed']!=0) $passed = $zakaz['passed'];

				$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);
				$name = get_product_name($product,false,$zakaz['name_tovar']);
				$name_tovar .= "<div $style>$name</div>";	
				
				$summ_usd += with_skidka($zakaz);
				$summ_bur += with_skidka($zakaz,'bur');
				if (($zakaz['was']==1) and ($zakaz['delivered']==1)) {
					if ($zakaz['passed'] == 1) $total_usd += with_skidka($zakaz);
					if ($zakaz['passed'] == 2) $total_bur += with_skidka($zakaz,'bur');	
				}								
			}		

			$summ_usd += $client['doplata_usd'];
			$summ_bur += $client['doplata_blr'];
			
			if ($passed != 0) {
				if (($item['total'] != $summ_usd) or ($item['total_blr'] != $summ_bur)) { 
					$usd = $item['total'] - $client['doplata_usd'];
					$bur = $item['total_blr'] - $client['doplata_blr'];
					$kurs = $item['kurs'];		
					$usd_t = $total_usd;
					$bur_t = $total_bur;
					
					$res_usd = $usd_t - $usd;	
					$bur_t  = $res_usd * $kurs;
					if ($total_bur != 0) $bur_t = $total_bur + $bur_t;
					if ($bur_t != $bur) {
						$b = '<span style="color:#8c16fa;">'; 
						$bb = '</span>'; 
					}				

				}
			}
			
			if ($item['oplatil']==1 and $item['adopted']==1) {
				$d = '<span style="color:green;">';
				$dd = '</span>';
			} else {
				$d = '<span style="color:red;">';
				$dd = '</span>';				
			}
				
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = '';
			$data['rows'][$i]['cell'][] = $d.$client['nomer_zakaza'].$dd;
			$data['rows'][$i]['cell'][] = $b.$name_tovar.$bb;				
			$data['rows'][$i]['cell'][] = $b.$adres.$bb;
			$data['rows'][$i]['cell'][] = $b.get_courier_name(Database::getField($this->_table_kon,$item['id_couriers'])).$bb;
			$data['rows'][$i]['cell'][] = $summ_usd;				
			$data['rows'][$i]['cell'][] = $summ_bur;
			$data['rows'][$i]['cell'][] = get_cur_valute($passed);
			$data['rows'][$i]['cell'][] = (($item['adopted']==1) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['zp'];
			$data['rows'][$i]['cell'][] = $item['zp_blr'];
			$data['rows'][$i]['cell'][] = $item['total'];
			$data['rows'][$i]['cell'][] = $item['total_blr'];
			$data['rows'][$i]['cell'][] = $item['comment'];		
			$data['rows'][$i]['cell'][] = $item['date_dostavka'];		
			$i++;	
			
			$total_us += $item['total']; 
			$total_blr += $item['total_blr']; 
			$total_zp += $item['zp'];
			$total_zp_blr += $item['zp_blr'];
		
		}			
			
		$data['userdata']['total_usd'] = $total_us;
		$data['userdata']['total_blr'] = $total_blr;
		$data['userdata']['zp'] = $total_zp;
		$data['userdata']['zp_blr'] = $total_zp_blr;
		
		echo json_encode($data);
		
	}   
    
	public function editAction() {
 		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action_group']) and $_POST['action_group']=="edit") {
			$data = array();
			$data['succes'] = true;
			$total_usd = 0;
			$total_bur = 0;
			$id_client = $_POST['id_client'];
			
			$where = "id_client = $id_client and shipped = 1";
			$zakazs = Database::getRows($this->_table_zakaz, 'id', 'asc', false, $where);
		
			foreach($zakazs as $zakaz) {
				if (($zakaz['was']==1) and ($zakaz['delivered']==1)) {
					if ($zakaz['passed'] == 1) $total_usd += with_skidka($zakaz);
					if ($zakaz['passed'] == 2) $total_bur += with_skidka($zakaz,'bur');	
				}
				if ($zakaz['passed'] == 0) {
					$data['succes'] = false;
					$data['message'] = "Заполните примечание! Почему не выбрана валюта?";
				}
			}
			
			$client = Database::getRow($this->_table_client,$id_client);
			$total_usd += $client['doplata_usd'];
			$total_bur += $client['doplata_blr'];

			if (($total_usd != $_POST['total']) or ($total_bur != $_POST['total_blr'])) {
				$usd = $_POST['total'];
				$bur = $_POST['total_blr'];
				$kurs = $_POST['kurs'];		
				$usd_t = $total_usd;
				$bur_t = $total_bur;
				
				$res_usd = $usd_t - $usd;
				
				$bur_t  = $res_usd * $kurs;
		
				if ($total_bur != 0) $bur_t = $total_bur + $bur_t;
				
				if ($bur_t != $bur) {
					$data['succes'] = false;
					$data['message'] = "Заполните примечание! Не совпадают итоговые суммы!";
				}			
			}			
			if ($data['succes']==false) {
				if (!empty($_POST['comment'])) $data['succes'] = true;			
			}
			$post = array(
				'id_couriers' => $_POST['id_couriers'],
				'id' => $_POST['id'],
				'operation' => 1,
				'cena_usd' => $_POST['total'],
				'cena_blr' => $_POST['total_blr'],
			);
			if ($_POST['obrabotan']==1 and in_array($_POST['id_couriers'],array(5,56))) {
				$res['succes'] = false;
				$res['message'] = 'Данные уже внесены в кассу, менять сумму запрещено!';				
			} else {
				$res = insert_kassa_by_kontragent($post);
			}
			if ($res['succes']==true and $data['succes']==true) {
			
				$obrabotan = $_POST['obrabotan']; 
			
				if ($obrabotan==0) { 
					//$where = "id_client = $id_client and shipped = 1";
					//$zakazs = Database::getRows($this->_table_zakaz, 'id', 'asc', false, $where);				
					foreach($zakazs as $zakaz) { 
						update_kolvo_tovar($zakaz); 
						}
					$obrabotan = 1;	
				}
				$cour_data = array(			
					'id_couriers' => $_POST['id_couriers'],			  
					'id_client' => $id_client,
					'zp' => $_POST['zp'],
					'zp_blr' => $_POST['zp_blr'],
					'total' => $_POST['total'],
					'total_blr' => $_POST['total_blr'],
					'comment' => $_POST['comment'],
					'obrabotan' => $obrabotan
				);	
				
				$where = 'id = '.$_POST['id'];
				Database::update($this->_table,$cour_data,$where);

			} else {
				$data = $res;
			}
			
			echo json_encode($data);			
		}


/********************************************************/
/*			Редатирование модуля дерево порталов		*/
/********************************************************/
	
		if (isset($_POST['action_tree'])) {
			$data = array();
			
			$data = array(
				'id_tip' => $_POST['id_tip'],
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
			$trees  = get_courier_tree_by_access(Database::getRows($this->_table_kon_tip));
			echo json_encode($trees);			
			
		}	



		// Удалить элемент в ДЕРЕВЕ
		if (isset($_POST['del_id_couriers']))  {
			Database::remove($this->_table_kon,$_POST['del_id_couriers']);
			
			$trees = array();
			$trees  = get_tree_by_access(Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 5'), 'id_courier');
			echo json_encode($trees);			
		}	
			
	}
	
	public function saveAction() {
 		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action_group']) and $_POST['action_group']=="edit") {
			$data = array();
			$data['succes'] = true;
			$total_usd = 0;
			$total_bur = 0;
			$id_client = $_POST['id_client'];
			
			$where = "id_client = $id_client and shipped = 1";
			$zakazs = Database::getRows($this->_table_zakaz, 'id', 'asc', false, $where);
		
			foreach($zakazs as $zakaz) {
				if (($zakaz['was']==1) and ($zakaz['delivered']==1)) {
					if ($zakaz['passed'] == 1) $total_usd += with_skidka($zakaz);
					if ($zakaz['passed'] == 2) $total_bur += with_skidka($zakaz,'bur');	
				}
				if ($zakaz['passed'] == 0) {
					$data['succes'] = false;
					$data['message'] = "Заполните примечание! Почему не выбрана валюта?";
				}
			}
			
			$client = Database::getRow($this->_table_client,$id_client);
			$total_usd += $client['doplata_usd'];
			$total_bur += $client['doplata_blr'];

			if (($total_usd != $_POST['total']) or ($total_bur != $_POST['total_blr'])) {
				$usd = $_POST['total'];
				$bur = $_POST['total_blr'];
				$kurs = $_POST['kurs'];		
				$usd_t = $total_usd;
				$bur_t = $total_bur;
				
				$res_usd = $usd_t - $usd;
				
				$bur_t  = $res_usd * $kurs;
		
				if ($total_bur != 0) $bur_t = $total_bur + $bur_t;
				
				if ($bur_t != $bur) {
					$data['succes'] = false;
					$data['message'] = "Заполните примечание! Не совпадают итоговые суммы!";
				}			
			}			
			if ($data['succes']==false) {
				if (!empty($_POST['comment'])) $data['succes'] = true;			
			}
	
			if ($data['succes']==true) {
			
				$obrabotan = $_POST['obrabotan']; 
			
				if ($obrabotan==0) { 
					//$where = "id_client = $id_client and shipped = 1";
					//$zakazs = Database::getRows($this->_table_zakaz, 'id', 'asc', false, $where);				
					foreach($zakazs as $zakaz) { 
						update_kolvo_tovar($zakaz); 
						}
					$obrabotan = 1;	
				}
				$cour_data = array(			
					'id_couriers' => $_POST['id_couriers'],			  
					'id_client' => $id_client,
					'zp' => $_POST['zp'],
					'zp_blr' => $_POST['zp_blr'],
					'total' => $_POST['total'],
					'total_blr' => $_POST['total_blr'],
					'comment' => $_POST['comment'],
					'obrabotan' => $obrabotan
				);	
				
				$where = 'id = '.$_POST['id'];
				Database::update($this->_table,$cour_data,$where);

			} else {
				$data = $res;
			}
			
			echo json_encode($data);			
		}
		
	}
		
	//Получение данных по id в форму для редактирования раздела
	public function datatreehandlingAction() {
	  
		$trees = array();
			
		$trees = Database::getRow($this->_table_kon,$_POST['id']);
	
		echo json_encode($trees);
		
	}   
 
	//Получение данных по id в форму для редактирования товара
	public function datahandlingAction() {
  
		$data = array();
		
		$id = $_POST['id'];		
		
		$data = Database::getRow($this->_table,$id);

		$client = Database::getRow($this->_table_client,$data['id_client']);
		$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,"id_client = ".$data['id_client']);
		$total_usd = 0;
		$total_blr = 0;
		foreach($zakazs as $item) {
			if (($item['was']==1) and ($item['delivered']==1)) {
				if ($item['passed']==1) $total_usd += with_skidka($item);
				if ($item['passed']==2) $total_blr += with_skidka($item,'bur');
			} 
		}
		$data['t_total'] = $total_usd + $client['doplata_usd'];	
		$data['t_total_blr'] = $total_blr + $client['doplata_blr'];	
		$data['doplata_usd'] = $client['doplata_usd'];	
		$data['doplata_blr'] = $client['doplata_blr'];	
			
		echo json_encode($data);
	
	}
/*
	public function getselectAction() {
		$trees  = Database::getRows($this->_table_kon,'id','asc',false,'id_tip = 5');
		$html = '0:нет;';
		foreach($trees as $tree) {
			$html .= $tree['id'].":".get_courier_name(@$tree['name']).";";
		}
		echo mb_substr($html, 0, -1);
	} 
	
	public function getselecthtmlAction() {
		get_select_courier();
	} 
*/	
	public function adoptedupdateAction() {

		$data = array();
		
		if (!empty($_POST['array'])) {
				
			foreach(explode(',',$_POST['array']) as $id) {
				
				$courier = Database::getRow($this->_table,$id);
				
				if ($courier['adopted']==0) {
									
					$access = get_array_access();
						
					$client = Database::getRow($this->_table_client,$courier['id_client']);
									
					if (in_array($client['sposob_dostavki'],array('vozim','dodoma'))) {
						
						if (!in_array($courier['id_couriers'],array(5,56))) {
							if ($client['sposob_dostavki']=='vozim') $new_id = 5;
							if ($client['sposob_dostavki']=='dodoma') $new_id = 56;							
							// обычные курьеры
							$post = array(
								'id_couriers' => $new_id,
								'id' => $id,
								'operation' => 1,
								'cena_usd' => $courier['total'],
								'cena_blr' => $courier['total_blr'],
							);							
							$data = insert_kassa_by_kontragent($post);
							if ($data['succes']==true) {
								$cour_data = array( 			
									'id_couriers' => $new_id,			  
									'id_client' => $courier['id_client'],
									'zp' => 0,
									'total' => $courier['total'],
									'total_blr' => $courier['total_blr'],
									'comment' => $courier['comment'],
									'obrabotan' => $courier['obrabotan']
								);	
								Database::insert($this->_table,$cour_data);
								$total = 0;
								$total_blr = 0;
							}							
						} else {

							if (!empty($access['id_kassa_tree'])) {

								$total = $courier['total'];
								$total_blr = $courier['total_blr'];
								
								$post = array(
									'id_couriers' => $courier['id_couriers'],
									'id' => $id,
									'operation' => 3,
									'cena_usd' => $total,
									'cena_blr' => $total_blr,
									'adopted' => 1,
									'id_tree_end' => $access['id_kassa_tree'],
									
								);				
								$data = insert_kassa_by_kontragent($post);
								$post['id_kassa_tree'] = $access['id_kassa_tree'];
								$post['operation'] = 4;
								$data = insert_kassa_by_kontragent($post);
						
							} else {
								$data['succes'] = false;	
							}	
						}
						
					} else {

						if (!empty($access['id_kassa_tree'])) {

							$total = $courier['total'];
							$total_blr = $courier['total_blr'];
							
							$kontragent = Database::getRow(get_table('kontragenty'),$courier['id_couriers']);
							
							if ($kontragent['id_tip']==11) {
								
								$admin_kassa = Database::getField(get_table('adminusers'),$_SESSION['isLoggedIn']['id'],'id','id_kassa_tree');
												
								$post = array(
									'id_couriers' => $courier['id_couriers'],
									'operation' => 1,									
									'cena_usd' => $total,
									'cena_blr' => $total_blr,			
								);	
								$data = insert_kassa_by_kontragent($post);

								$admin_kontragent = Database::getRow(get_table('kassa_tree'),$admin_kassa);
								
								$post['id_couriers'] = $admin_kontragent['id_kontragenty'];
								
								$data = insert_kassa_by_kontragent($post);
							
								
							} else {
							
								$post = array(
									'id_couriers' => $courier['id_couriers'],
									'id' => $id,
									'operation' => 3,
									'cena_usd' => $total,
									'cena_blr' => $total_blr,
									'adopted' => 1,
									'id_tree_end' => $access['id_kassa_tree'],
									
								);				
								$data = insert_kassa_by_kontragent($post);
								$post['id_kassa_tree'] = $access['id_kassa_tree'];
								$post['operation'] = 4;
								$data = insert_kassa_by_kontragent($post);
														
								
							}

						} else {
							$data['succes'] = false;	
						}	
					}					

					if ($data['succes']==true) {
						$cour_data_t = array(
							'adopted' => 1,
							'total' => $total,
							'total_blr' => $total_blr
						);
						$where = "id = $id";
						Database::update($this->_table,$cour_data_t,$where);
						$data = insert_zp_by_manager($courier);
					}
					
				} else {
					$data['succes'] = false;
					$data['message'] = 'Заказ уже принят!';
				}
			}
		
		}
		
		echo json_encode($data);
		
	}	
	
	public function resetdataAction() {
		

		if (!empty($_POST['array'])) {
					
			$items = explode(',',$_POST['array']);
			foreach($items as $id) {
				$courier = Database::getRow($this->_table,$id);
				
				if (isset($courier['id_client']) and $courier['oplatil']==0) {
					
					if ($courier['adopted']==1) {
						$cour_data = array(
							'adopted' => 0
						);					
					} else {
						$cour_data = array(
							'zp' => 0,
							'total' => 0,
							'total_blr' => 0,
							'comment' => '',
							'adopted' => 0
						);	
						
						$data_zakaz = array(
							'was' => 0,
							'delivered' => 0,
							'passed' => 0
						);	
						$where_zakaz = 'id_client='.$courier['id_client'];
						Database::update($this->_table_zakaz,$data_zakaz,$where_zakaz);
					}
					$where = "id = $id";
					Database::update($this->_table,$cour_data,$where);
				}
			}
		
		}
		
	}	
	
	public function zpupdateAction() {
		

		if (!empty($_POST['array'])) {
					
			$items = explode(',',$_POST['array']);
			$zp = 0;
			foreach($items as $id) {
				$courier = Database::getRow($this->_table,$id);
				
				if (isset($courier['id_client']) and $courier['adopted']==1 and $courier['oplatil']==0) {	
					$zp += $courier['zp'];	
					$zp_blr += $courier['zp_blr'];	
					$arr['oplatil'] = 1;
					Database::update($this->_table,$arr,"id = $id");
				}
			}
			if ($zp > 0 or $zp_blr > 0) {
			$nomer = 0;
			$nomer = Database::getLastId(get_table('kassa'));
			$nomer++;		
			$access = get_array_access();
				if ($access['id_kassa_tree']!=0) {
					$data = array(
						'nomer' => $nomer,
						'active' => 1,
						'id_tree' => $access['id_kassa_tree'],			  
						'cena_usd' => $zp,			  		  			  
						'cena_blr' => $zp_blr,			  		  			  
						'operation' => 2,			  
						'id_tip_operation' => 18,			  
						'date_create' => date("Y-m-d"),
						'time_create' => date("G:i:s"),
						'id_adminusers' => $_SESSION['isLoggedIn']['id']				
					);
					Database::insert(get_table('kassa'),$data);		
				}
			} else {
				echo 'Кассы с таким контрагентом не существует!';
			}
		}
		
	}
	
	//Получение данных по id в форму для добавления товара
	public function dataprintAction() {
		$zakaz = array();
		$zakaz = get_otchet_by_date(@$_GET['id_couriers'],@$_GET['adopted'],@$_GET['date_zakaz_ot'],@$_GET['date_zakaz_do']);
		echo json_encode($zakaz);
	}

    public function loadcouriersAction() {
		// Начало формирование объекта
		$data = array();
	
		$page  	= @$_GET['page'];      // Номер запришиваемой страницы
		$limit 	= @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  	= @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  	= @$_GET['sord'];      // Направление сортировки
		$host 	= $_SERVER['HTTP_HOST'];
		
		$id_client = $_GET['id_client'];
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		

		$where = "id_client = $id_client and shipped = 1";
		$count = Database::getCount($this->_table_zakaz,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table_zakaz, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table_zakaz, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		foreach($items as $zakaz) {
			
			$product = Database::getRow($this->_table_catalog,$zakaz['id_catalog']);

			if (isset($product['id'])) {
	
				$name = get_product_name($product,false,$zakaz['name_tovar']);
						 
				$suma_usd = with_skidka($zakaz);
				$suma_bur = with_skidka($zakaz,'bur');	
				
				$status = get_status_zakaz($zakaz);
				
				$data['rows'][$i]['id'] = $zakaz['id'];		
				$data['rows'][$i]['cell'][] = '<a href="http://'.$host.'/product/'.$product['path'].'" target="_ablank">'.$name.'</a>';	
				$data['rows'][$i]['cell'][] = '';				
				$data['rows'][$i]['cell'][] = (($zakaz['was']==1) ? 'Да' : 'Нет');
				$data['rows'][$i]['cell'][] = (($zakaz['delivered']==1) ? 'Да' : 'Нет');
				$data['rows'][$i]['cell'][] = $zakaz['kolvo'];									
				$data['rows'][$i]['cell'][] = $suma_usd;			
				$data['rows'][$i]['cell'][] = $suma_bur;						
				$data['rows'][$i]['cell'][] = get_cur_valute($zakaz['passed']);	
				$data['rows'][$i]['cell'][] = $status;	
				$data['rows'][$i]['cell'][] = '';				
				$data['rows'][$i]['cell'][] = '';				
				$i++;
				
			}
		}

		echo json_encode($data);
	}	
	
	public function editcouriersAction() {
	
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['oper']) and $_POST['oper']=="edit") {
			$total = 0;
			$pas = 0;
			$data = array('succes' => true, 'zp' => 0, 'total' => 0, 'req_comment' => false, 'msg' => '');

			$checked = $_POST['checked'];
			
			$delivered = 0;
			$was = 0;			
			$passed = 0;
				
			if ($checked == "Да") {
				$was = 1;				
				$delivered = 1;			
				$passed = 2;
				$data['req_comment'] = true;	
			} 
			
			if ($data['succes']) {
				$data_zakaz = array(
					'id' => $_POST['id'],  		  
					'was' => $was,
					'delivered' => $delivered,
					'passed' => $passed
				);	
				$where = "id = ".$_POST['id'];
				Database::update($this->_table_zakaz,$data_zakaz,$where);	
				$zakaz = Database::getRow($this->_table_zakaz,$_POST['id']);	
				$client = Database::getRow($this->_table_client,$zakaz['id_client']);
				
			
				if (($was=='Да') or ($delivered=='Да')) {
					$data['zp'] = get_courier_zp($client);
				} 
				
				$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,"id_client = ".$zakaz['id_client']);
				$total_usd = 0;
				$total_bur = 0;
				foreach($zakazs as $item) {
					if (($item['was']==1) and ($item['delivered']==1)) {
						if ($item['passed']==1) $total_usd += with_skidka($item);
						if ($item['passed']==2) $total_bur += with_skidka($item,'bur');
						$pas = $item['passed'];
					} 
				}
				$total_usd += $client['doplata_usd'];
				$total_bur += $client['doplata_blr'];				
				$data['total'] = $total_usd;
				$data['total_blr'] = $total_bur;					
				
			}		
			echo json_encode($data);
		}	
	
	}	
}