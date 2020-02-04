<?php

class Application_for_warehouse_Controller {
	private $_config, $_content, $_table, $_table_zakaz;

	public function __construct() {
		Load::model(array('Catalog','Couriers','Currency_tree','Zakaz_client'));
		$this->_table = get_table('zakaz_client');
		$this->_table_zakaz = get_table('zakaz');
		$this->_table_catalog = get_table('catalog');
		$this->_content['title'] = 'Заявка на склад';
	}

	
	public function defaultAction() {
  
		$trees  = Database::getRows(get_table('kontragenty'),'id','asc',false,'id_tip = 5');
	
		$this->_content['content'] = Render::view(
				'adminpanel/documents/application_for_warehouse', array (
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

		$date_ot = @$_GET['date_ot'];
		$date_do = @$_GET['date_do'];
			
		if (!empty($date_ot)) $where_ot = ' and date_dostavka >= "'.$date_ot.'"';
		if (!empty($date_do)) $where_do = ' and date_dostavka <= "'.$date_do.'"';
		
		$where = "dostavka = 1".@$where_ot.@$where_do;
		
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;

		foreach($items as $item) {

			$shipped = "Нет";
			
			$name_tovar = '';
			$kolvo_only = '';

			$adres = get_adres_client($item);
		
			$where = "id_client = ".$item['id']." and rezerv = 0 and otkaz = 0";
			$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,$where);
			$kolvo = 0;
			
			foreach($zakazs as $zakaz) {
				if ($zakaz['shipped']==0) $style='style="color: red;"';
				if ($zakaz['shipped']==1) $style='style="color: blue;"';
				if ($zakaz['delivered']==1) $style='style="color: black;"';
				if ($zakaz['vozvrat']!=0) $style='style="color: green;"';
				if ($zakaz['predzakaz']==1) $style='style="color: grey;"';
				
				$kolvo += $zakaz['kolvo'];
				
				$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);		
				$name = get_product_name($product,true,$zakaz['name_tovar']);
				$name_tovar .= "<div $style>$name</div>";	
				
				$kolvo_only .= "<div>".$zakaz['kolvo']."</div>";
				
				if ($zakaz['shipped']!=0) $shipped = "Да";
			}	
			
			$summ = get_summa_by_client($item);
			
			if (!empty($zakazs)) {
			
				$adminuser = Database::getRow(get_table('adminusers'),$item['id_adminuser']);
	
				$couriers = Database::getRow(get_table('couriers'),$item['id'],'id_client');
				$name_courier = get_courier_name(Database::getField(get_table('kontragenty'),@$couriers['id_couriers']));
			
				$access = get_array_access();
			
				$be = ''; $bs = '';
				if ($access['application_for_warehouse_edit']==1) {
					$be = '<div" rel="'.$item['id'].'" class="button edit editdata"></div>';
					$bs = '<div" rel="'.$item['id'].'" class="button save savedata"></div>';
				}
			
				$diler = Database::getRow(get_table('registration'),$item['id_diler']);
			
				$data['rows'][$i]['id'] = $item['id'];
				$data['rows'][$i]['cell'][] = $be;
				$data['rows'][$i]['cell'][] = $item['nomer_zakaza'];
				$data['rows'][$i]['cell'][] = $name_courier;
				$data['rows'][$i]['cell'][] = $bs;
				$data['rows'][$i]['cell'][] = @$diler['login'];				
				$data['rows'][$i]['cell'][] = @$adminuser['fio'];
				$data['rows'][$i]['cell'][] = $kolvo_only;
				$data['rows'][$i]['cell'][] = $name_tovar;				
				$data['rows'][$i]['cell'][] = $adres;
				$data['rows'][$i]['cell'][] = (($item['beznal']==1) ? 'Б' : '');					
				$data['rows'][$i]['cell'][] = $summ['usd'];				
				$data['rows'][$i]['cell'][] = $summ['bur'];
				$data['rows'][$i]['cell'][] = $kolvo;
				$data['rows'][$i]['cell'][] = $shipped;
				$data['rows'][$i]['cell'][] = $item['comment'];		
				$data['rows'][$i]['cell'][] = $item['comment_m'];		
				$data['rows'][$i]['cell'][] = $item['comment_w'];		
				$data['rows'][$i]['cell'][] = $item['date_dostavka'];
				$i++;							

			}			
		}
		echo json_encode($data);
		
	}   
    
	public function editAction() {
 		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
			$data = array();
			$data['succes'] = true;
			$shipped = 0;
			$id_client = $_POST['id'];	
				
			$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,"id_client = $id_client");	
			$client = Database::getRow($this->_table,$id_client);		
			foreach($zakazs as $zakaz) {
				if ($zakaz['shipped'] == 0) {
					$data['succes'] = false;
					$data['message'] = "Почему не выбрана отгрузка?";
				}
			
				if ($zakaz['vozvrat']==3) {
					$data['succes'] = false;
					$data['message'] = "Укажите примечание по браку";
				}	
				
				$shipped = $zakaz['shipped'];
			}

			if ($data['succes']==false) {
				if (!empty($_POST['comment_w'])) $data['succes'] = true;			
			}
			if ($data['succes']==true) {			
				$cour_data = array(			
					'id_couriers' => $_POST['id_couriers'],			  
					'comment_w' => $_POST['comment_w'],				
					'beznal' => ((isset($_POST['beznal'])) ? 1 : 0)
				);	
				
				$where = 'id = '.$_POST['id'];
				Database::update($this->_table,$cour_data,$where);
				
				if(empty($_POST['id_couriers'])) Database::update($this->_table,array('sposob_dostavki' => @$_POST['sposob_dostavki']),"id = $id_client");
			} 
			
			echo json_encode($data);			
		}
		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['oper']) and $_POST['oper']=="edit") {
			$data = array();
			$data['succes'] = true;
			$id_couriers = $_POST['id_couriers'];
			$id_client = $_POST['id']; 			
		
			$tmp_zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,"id_client = $id_client");
			foreach($tmp_zakazs as $tmp_zakaz) {
				if ($tmp_zakaz['delivered']==1) {
					$data['succes'] = false;
					$data['message'] = 'Товар доставлен, используйте возврат!';
				} elseif ($tmp_zakaz['shipped']==1) {
					$data['succes'] = false;
					$data['message'] = 'Снимите отгрузку!';					
				}
			}
					
			if ($data['succes']) {

				$table_couriers = get_table('couriers');
				Database::delete($table_couriers,$id_client,'id_client');	
				
				if ($id_couriers != 0) {
					$courier = Database::getRow(get_table('kontragenty'),$id_couriers);
					if ($courier['id_tip']==11) {

						$zakaz_data = array(
							'was' => 1,
							'delivered' => 1,
							'passed' => 1
						);
						Database::update($this->_table_zakaz,$zakaz_data,"id_client = $id_client and predzakaz = 0 and rezerv = 0");
						
						$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,"id_client = $id_client");
						$client = Database::getRow($this->_table,$id_client);
						$summ_with_skidka = get_summa_by_client($client);
						$total_usd = $summ_with_skidka['usd'];

						$cour_data = array(
							'id_couriers' => $id_couriers,			  
							'id_client' => $id_client,
							'zp' => 0,
							'total' => $total_usd,
							'comment' => '',
							'obrabotan' => 1,
							'kurs' => get_kurs()
						);
						
						Database::insert($table_couriers,$cour_data);
						$last_id = Database::getLastId($table_couriers);
						
						$post = array(
							'id_couriers' => $id_couriers,
							'id' => $last_id,
							'operation' => 2,
							'cena_usd' => $total_usd,
						);
						$data = insert_kassa_by_kontragent($post);	
						if ($data['succes']==true) {
							foreach($zakazs as $zakaz) {
								update_kolvo_tovar($zakaz);
							}
						}
					} else {
						$cour_data = array(
							'id_couriers' => $id_couriers,			  
							'id_client' => $id_client,
							'zp' => 0,
							'total' => 0,
							'comment' => '',
							'kurs' => get_kurs()
						);	
						Database::insert($table_couriers,$cour_data);
					}
					
					
				}
			
				$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,"id_client = $id_client");

				foreach($zakazs as $zakaz) {

					$data_arr = array( 		  
						'shipped' => 1
					);	
					$where = "id = ".$zakaz['id']." and rezerv=0 ";
					Database::update(get_table('zakaz'),$data_arr,$where);
				}	
				
			}		
			
			echo json_encode($data);
		}
		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (!empty($_POST['array'])) {
			$data = array();
			$data['succes'] = false;
			
			if ($_POST['id_couriers']!=0) {			
			$total = 0;
			$data['succes'] = true;
			$id_couriers = $_POST['id_couriers'];

			$items = explode(',',$_POST['array']);

				
			if ($data['succes']) {
			
				$msg = '';
			
				$table_couriers = get_table('couriers');
			
				foreach($items as $id_client) {

					if ($id_client > 0) {
					
						$courier = Database::getRow($table_couriers,$id_client,'id_client');	
						
						if (empty($courier)) {

							$courier = Database::getRow(get_table('kontragenty'),$id_couriers);
							if ($courier['id_tip']==11) {

								$zakaz_data = array(
									'was' => 1,
									'delivered' => 1,
									'passed' => 1
								);
								Database::update($this->_table_zakaz,$zakaz_data,"id_client = $id_client and predzakaz = 0 and rezerv = 0");
								
								$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,"id_client = $id_client");								
								$client = Database::getRow($this->_table,$id_client);
								$summ_with_skidka = get_summa_by_client($client);
								$total_usd = $summ_with_skidka['usd'];
								
								$cour_data = array(
									'id_couriers' => $id_couriers,			  
									'id_client' => $id_client,
									'zp' => 0,
									'total' => $total_usd,
									'comment' => '',
									'obrabotan' => 1,
									'kurs' => get_kurs()
								);
								
								Database::insert($table_couriers,$cour_data);
								$last_id = Database::getLastId($table_couriers);
								
								$post = array(
									'id_couriers' => $id_couriers,
									'id' => $last_id,
									'operation' => 2,
									'cena_usd' => $total_usd,
								);
								$data = insert_kassa_by_kontragent($post);	
								if ($data['succes']==true) {
									foreach($zakazs as $zakaz) {
										update_kolvo_tovar($zakaz);
									}
								}								
							} else {
								$cour_data = array(
									'id_couriers' => $id_couriers,			  
									'id_client' => $id_client,
									'zp' => 0,
									'total' => 0,
									'comment' => '',
									'kurs' => get_kurs()
								);	
								Database::insert($table_couriers,$cour_data);
							}
	
						} else {
							$client = Database::getRow(get_table('zakaz_client'),$id_client);	
							$msg .= $client['nomer_zakaza'].', ';
						}
				
						if (!empty($msg)) $data['message'] = "В заявках № ".$msg." групповая смена курьеров невозможна. Сменить курьера возможно в ручном режиме, нажав на поле с именем курьера, которое следует за номером заказа.";
					
						$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,"id_client = $id_client");

						foreach($zakazs as $zakaz) {

							$data_arr = array('shipped' => 1);	
							$where = "id = ".$zakaz['id']." and rezerv=0 ";
							Database::update(get_table('zakaz'),$data_arr,$where);
						}
						
					}
				}		
			}

			}
			
			echo json_encode($data);
						
		}
		
	}
  
 
	//Получение данных по id в форму для редактирования товара
	public function openAction() {
  
		$data = array();
		
		$id = $_POST['id'];		
		
		$data = Database::getRow($this->_table,$id);
		
		$courier = Database::getRow(get_table('couriers'),$id,'id_client');
		$data['comment_c'] = @$courier['comment'];
		$data['id_couriers'] = @$courier['id'];
		$sum = get_summa_by_client($data);
		$data['sum_usd'] = $sum['usd'];
		$data['sum_blr'] = $sum['bur'];		
		echo json_encode($data);
	
	}	

    public function loadwarehouseAction() {
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
		
		$where = "id_client = $id_client and rezerv = 0 and otkaz = 0";
		$count = Database::getCount($this->_table_zakaz,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table_zakaz, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table_zakaz, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		$kolvo = 0;
		$sum_total_cena = 0;
		$sum_total_cena_blr = 0;		
		foreach($items as $zakaz) {
	
			$product = Database::getRow($this->_table_catalog,$zakaz['id_catalog']);

			if (isset($product['id'])) {
				
				$couriers = Database::getRow(get_table('couriers'),$zakaz['id_client'],'id_client');
				$name_courier = get_courier_name(Database::getField(get_table('kontragenty'),@$couriers['id_couriers']));
				
				$name = get_product_name($product,true,$zakaz['name_tovar']);
						 
				$suma_usd = with_skidka($zakaz);
				$suma_bur = with_skidka($zakaz,'bur');	
		
		
				$kolvo += $zakaz['kolvo'];
				$sum_total_cena += $suma_usd;
				$sum_total_cena_blr += $suma_bur;
				
				$status = $status = get_status_zakaz($zakaz);
				
				$data['rows'][$i]['id'] = $zakaz['id'];	
				$data['rows'][$i]['cell'][] = '';					
				$data['rows'][$i]['cell'][] = '<a href="http://'.$host.'/product/'.$product['path'].'" target="_ablank">'.$name.'</a>';				
				$data['rows'][$i]['cell'][] = '';				
				$data['rows'][$i]['cell'][] = (($zakaz['shipped']==1) ? 'Да' : 'Нет');
				$data['rows'][$i]['cell'][] = $zakaz['kolvo'];									
				$data['rows'][$i]['cell'][] = $suma_usd;			
				$data['rows'][$i]['cell'][] = $suma_bur;	
				$data['rows'][$i]['cell'][] = get_status_vozvrat($zakaz['vozvrat']);				
				$data['rows'][$i]['cell'][] = $name_courier;	
				$data['rows'][$i]['cell'][] = $status;	
				$data['rows'][$i]['cell'][] = '';				
				$i++;
				
			}
		}
		
		$data['userdata']['kolvo'] = $kolvo;
		$data['userdata']['cena'] = $sum_total_cena;
		$data['userdata']['cena_blr'] = $sum_total_cena_blr;		

		echo json_encode($data);
	}

	public function editwarehouseAction() {
		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['oper']) and $_POST['oper']=="edit") {
			$total = 0;
			$pas = 0;
			$id = $_POST['id'];
			$data = array('succes' => true, 'total' => 0, 'req_comment' => false, 'msg' => '');

			$shipped = $_POST['shipped'];
			$vozvrat = $_POST['vozvrat'];
			$where = "id = $id";
			
			$zakaz = Database::getRow($this->_table_zakaz,$id);
			
			if ($zakaz['delivered']==1) {
				$data['succes'] = false;
				$data['msg'] = "Нельзя вернуть доставленный товар";
			}

			if ($data['succes']) {
			
				$data_arr = array( 		  
					'shipped' => (($shipped=='Да') ? 1 : 0),
					'vozvrat' => $vozvrat
				);	
					
				Database::update($this->_table_zakaz,$data_arr,$where);	
					

				$zakazs = Database::getRows($this->_table_zakaz,'id','asc',false,'id_client = '.$zakaz['id_client']);
				$delete = false;
				foreach($zakazs as $item) {
					if ($item['shipped']==0) $delete = true;
					else $delete = false;
				}
				if ($delete == true) {
					Database::delete(get_table('couriers'),$zakaz['id_client'],'id_client');
					$data['delete'] = true;
				}
			}

			echo json_encode($data);
		}	
	
	}

	//Получение данных по id в форму для добавления товара
	public function dataprintAction() {
		$zakaz = array();
		$zakaz['html'] = get_print_form_to_date($_GET['date_zakaz'],@$_GET['cur_id']);
		echo json_encode($zakaz);
	}

	public function getselectAction() {
		
		$trees  = Database::getRows(get_table('kontragenty_tip'),'id','asc',false,'couriers_show = 1');
		$html = '0:нет;';
		foreach ($trees as $tree) {
			$bykva = mb_substr(@$tree['name'],0,1,'UTF-8');
			$items = Database::getRows(get_table('kontragenty'),'id','asc',false,'id_tip = '.$tree['id']);
			foreach($items as $item) {
				$html .= $item['id'].":".@$bykva.' '.@$item['name'].";";
			}			
		}

		echo mb_substr($html, 0, -1);
	} 
		
	public function getselecthtmlAction() {
		get_select_courier();
	} 	
	
	public function getskladhtmlAction() {
		$data = array();
		$zakaz = Database::getRow(get_table('zakaz'),$_POST['id']);
		if (empty($zakaz)) return;
		$product = Database::getRow(get_table('catalog'),$zakaz['id_catalog']);
		$data['html'] = '<div id="table-skald-tovar">';
		$data['html'] .= '<div><h3>'.get_product_name($product,true).'</h3></div>';
		$data['html'] .= get_table_sklad_tovar($zakaz['id_catalog']);
		$data['html'] .= '</div>';
		echo json_encode($data);
	} 

	public function refresh_ostatkiAction() {
		echo refresh_ostatki();	
	} 
	
	
}