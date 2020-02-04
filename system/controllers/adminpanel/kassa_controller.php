<?php

class Kassa_Controller {
	private $_content;

	public function __construct() {
		$this->_content['title'] = 'Кассы';
		$this->_table = get_table('kassa');
		$this->_table_tree = get_table('kassa_tree');
	}
	
	public function defaultAction() {

		$trees  = Database::getRows($this->_table_tree,'name','asc');

		$dilers = Database::getRows($this->_table_tree,'name','asc',false,'pid IN (40,56)');
		
		$kontragenty = Database::getRows(get_table('kontragenty'));
		$kontragenty_tip = Database::getRows(get_table('kontragenty_tip'));	
		
		$tip_operation = Database::getRows(get_table('tip_operation'));
		$adminusers = Database::getRows(get_table('adminusers'));

		$this->_content['content'] = Render::view(
				'adminpanel/documents/kassa', array (
				'trees' => get_kassa_tree_by_access($trees),
				'kassa' => $trees,
				'access' => get_array_access(),
				'kontragenty' => $kontragenty,
				'kontragenty_tip' => $kontragenty_tip,
				'tip_operation' => $tip_operation,
				'dilers' => $dilers,
				'adminusers' => $adminusers
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

		$where = get_where_by_params($_GET);
		
		$count = Database::getCount($this->_table, $where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'], $where);
		
		$items_for_sum = Database::getRows($this->_table, $sidx, $sord, false, $where);
		
		$i = 0;
		$sum_cena_usd = 0;
		$sum_cena_blr = 0;
		$sum_cena_eur = 0;		
		$sum_cena_rur = 0;	
		
		foreach($items_for_sum as $item) {
			if (in_array($item['operation'],array(1,4,6))) $op = 1;
			if (in_array($item['operation'],array(2,3,5))) $op = -1;
						
			$sum_cena_usd += $item['cena_usd'] * $op;
			$sum_cena_blr += $item['cena_blr'] * $op;
			$sum_cena_eur += $item['cena_eur'] * $op;
			$sum_cena_rur += $item['cena_rur'] * $op;
		}	
		
		foreach($items as $item) {
			$img = '';
			if ($item['operation'] == 1) $img = '<img src="/img/admin/icons/plus.png" />';
			if ($item['operation'] == 2) $img = '<img src="/img/admin/icons/minus.png" />';
			if ($item['operation'] == 3) $img = '<img src="/img/admin/icons/m-minus.png" />';
			if ($item['operation'] == 4) $img = '<img src="/img/admin/icons/m-plus.png" />';
			if ($item['operation'] == 5) $img = '<img src="/img/admin/icons/conversion.png" />';
			if ($item['operation'] == 6) $img = '<img src="/img/admin/icons/conversion.png" />';

			if (in_array($item['operation'],array(1,4,6))) $op = 1;
			if (in_array($item['operation'],array(2,3,5))) $op = -1;
			
			$courier = Database::getRow(get_table('couriers'),$item['id_couriers']);
			$client = Database::getRow(get_table('zakaz_client'),@$courier['id_client']);
			
			if ($item['active']==0) { $bb = '<b style="color:red">'; $b = '</b>'; }
			else { $bb = ''; $b = '';}
		
			$date_create = norm_date($item['date_create']);
		
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = @$bb.$item['nomer'].@$b;
			$data['rows'][$i]['cell'][] = $img;
			$data['rows'][$i]['cell'][] = Database::getField(get_table('adminusers'),$item['id_adminusers'],'id','login');
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_tree,$item['id_tree']);
			$data['rows'][$i]['cell'][] = Database::getField($this->_table_tree,$item['id_tree_end']);
			$data['rows'][$i]['cell'][] = @$bb.$date_create.@$b;	
			$data['rows'][$i]['cell'][] = @$bb.$item['time_create'].@$b;			
			$data['rows'][$i]['cell'][] = formatCena($item['cena_usd'] * $op);	
			$data['rows'][$i]['cell'][] = formatCena($item['cena_blr'] * $op);	
			$data['rows'][$i]['cell'][] = formatCena($item['cena_eur'] * $op);	
			$data['rows'][$i]['cell'][] = formatCena($item['cena_rur'] * $op);	
			$data['rows'][$i]['cell'][] = Database::getField(get_table('tip_operation'),$item['id_tip_operation'],'id','name');	
			$data['rows'][$i]['cell'][] = $item['comment'];		
			$data['rows'][$i]['cell'][] = @$client['nomer_zakaza'];		
			$data['rows'][$i]['cell'][] = $item['kurs'];		
			$i++;

		}	
		$minus = 0;
		if (isset($_GET['id_tree']) and !empty($_GET['id_tree']) and !is_array(@$_GET['id_tree'])) {
			$k_html = '<option value="0">--выбор--</option>';
			$tree = Database::getRow($this->_table_tree,$_GET['id_tree']);	
			$kontragenty_tip = Database::getRow(get_table('kontragenty_tip'),$tree['id_kontragenty_tip']);
			if (!empty($kontragenty_tip['id_tip_operation'])) {
				$tip_operation = Database::getRows(get_table('tip_operation'),'name','asc',false,'id IN ('.$kontragenty_tip['id_tip_operation'].')');
				foreach($tip_operation as $tip) {
					$k_html .= '<option value="'.$tip['id'].'">'.$tip['name'].'</option>';
				}
			}
			if ($tree['minus']==1) $minus = 1;
		}
		$data['userdata']['minus'] = $minus;
		$data['userdata']['k_html'] = @$k_html;
		$data['userdata']['cena_usd'] = $sum_cena_usd;
		$data['userdata']['cena_blr'] = $sum_cena_blr;
		$data['userdata']['cena_eur'] = $sum_cena_eur;
		$data['userdata']['cena_rur'] = $sum_cena_rur;
		
		echo json_encode($data);
		
	}   
    
	public function editAction() {
  
/********************************************************/
/*		Редатирование модуля связей с порталами 		*/  
/********************************************************/  
		
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action_group']) and $_POST['action_group']=="add") {
			$nomer = 0;
			$nomer = Database::getLastId($this->_table);
			$nomer++;
			
			if ($_POST['id_diler']>0) {
				
				$data = array(
					'active' => 0,
					'nomer' => $nomer,
					'id_tree' => @$_POST['id_diler'],
					'id_tree_end' => @$_POST['id_tree'],
					'cena_usd' => $_POST['cena_usd'],			  
					'cena_blr' => $_POST['cena_blr'],			  
					'cena_eur' => $_POST['cena_eur'],			  			  
					'cena_rur' => $_POST['cena_rur'],			  			  
					'operation' => 3,			
					'id_tip_operation' => $_POST['id_tip_operation'],				
					'date_create' => date("Y-m-d"),
					'time_create' => date("G:i:s"),
					'comment' => $_POST['comment'],
					'id_adminusers' => $_SESSION['isLoggedIn']['id']
				);	
				
				Database::insert($this->_table,$data);	
				
				$data_tmp = array(
					'active' => 0,
					'nomer' => $nomer,
					'id_tree' => @$_POST['id_tree'],
					'id_tree_end' => @$_POST['id_diler'],				
					'cena_usd' => $_POST['cena_usd'],			  
					'cena_blr' => $_POST['cena_blr'],			  
					'cena_eur' => $_POST['cena_eur'],			  			  
					'cena_rur' => $_POST['cena_rur'],			  			  
					'operation' => 4,
					'id_tip_operation' => $_POST['id_tip_operation'],				
					'date_create' => date("Y-m-d"),
					'time_create' => date("G:i:s"),
					'comment' => $_POST['comment'],
					'id_adminusers' => $_SESSION['isLoggedIn']['id']
				);	
				
				Database::insert($this->_table,$data_tmp);
							
			} else {
					
				$data = array(
					'nomer' => $nomer,
					'active' => ((isset($_POST['active'])) ? 1 : 0),
					'id_tree' => @$_POST['id_tree'],			  
					'cena_usd' => $_POST['cena_usd'],			  
					'cena_blr' => $_POST['cena_blr'],			  
					'cena_eur' => $_POST['cena_eur'],			  
					'cena_rur' => $_POST['cena_rur'],			  
					'comment' => $_POST['comment'],			  
					'operation' => $_POST['operation'],			  
					'id_tip_operation' => $_POST['id_tip_operation'],			  
					'date_create' => (!empty($_POST['date_create']) ? $_POST['date_create'] : date("Y-m-d")),
					'time_create' => date("G:i:s"),
					'id_adminusers' => $_SESSION['isLoggedIn']['id']
				);	
				
				Database::insert($this->_table,$data);
				
			}
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action_group']) and $_POST['action_group']=="edit") {
			$active = ((isset($_POST['active'])) ? 1 : 0);
			$data = array(  		
				'comment' => $_POST['comment'],
				'date_create' => (!empty($_POST['date_create']) ? $_POST['date_create'] : date("Y-m-d")),				
			);	
			$access = get_array_access();
			if ($access['id']==1) {
				$data['id_tip_operation'] = $_POST['id_tip_operation'];
			}
			$where = 'id = '.$_POST['id'];
			Database::update($this->_table,$data,$where);
			
			if ($active == 1) {
				$data = array(
					'active' => 1
				);
				$item = Database::getRow($this->_table,$_POST['id']);
				if ($item['operation']==4) {
					$where = 'nomer = '.$item['nomer'];
					Database::update($this->_table,$data,$where);		
				}				
			}
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			$item = Database::getRow($this->_table,$_POST['del_id']);
			Database::delete($this->_table,$item['nomer'],'nomer');
		}	
		
		// ПЕРЕМЕСТИТЬ
		if (isset($_POST['action_move']) and $_POST['action_move']=="add") {
			$nomer = 0;
			$nomer = Database::getLastId($this->_table);
			$nomer++;			
			$data = array(
				'active' => 0,
				'nomer' => $nomer,
				'id_tree' => @$_POST['id_tree'],
				'id_tree_end' => @$_POST['id_tree_tmp'],
				'cena_usd' => $_POST['cena_usd'],			  
				'cena_blr' => $_POST['cena_blr'],			  
				'cena_eur' => $_POST['cena_eur'],			  			  
				'cena_rur' => $_POST['cena_rur'],			  			  
				'operation' => 3,			
				'id_tip_operation' => $_POST['id_tip_operation'],				
				'date_create' => date("Y-m-d"),
				'time_create' => date("G:i:s"),
				'comment' => $_POST['comment'],
				'id_adminusers' => $_SESSION['isLoggedIn']['id']
			);	
			
			Database::insert($this->_table,$data);	
			
			$data_tmp = array(
				'active' => 0,
				'nomer' => $nomer,
				'id_tree' => @$_POST['id_tree_tmp'],
				'id_tree_end' => @$_POST['id_tree'],				
				'cena_usd' => $_POST['cena_usd'],			  
				'cena_blr' => $_POST['cena_blr'],			  
				'cena_eur' => $_POST['cena_eur'],			  			  
				'cena_rur' => $_POST['cena_rur'],			  			  
				'operation' => 4,
				'id_tip_operation' => $_POST['id_tip_operation'],				
				'date_create' => date("Y-m-d"),
				'time_create' => date("G:i:s"),
				'comment' => $_POST['comment'],
				'id_adminusers' => $_SESSION['isLoggedIn']['id']
			);	
			
			Database::insert($this->_table,$data_tmp);
			
		}		
		
		// КОНВЕРСИЯ
		if (isset($_POST['action_conversion']) and $_POST['action_conversion']=="add") {
			
			$valute1 = $_POST['valute1'];
			$valute2 = $_POST['valute2'];
			
			//BYR to USD
			if ($valute1 == 1 and $valute2 == 2) {
				$cena_blr = $_POST['cena_old'];
				$cena_usd_new = $_POST['cena_new'];	
			}					
			//BYR to EUR
			if ($valute1 == 1 and $valute2 == 3) {
				$cena_blr = $_POST['cena_old'];
				$cena_eur_new = $_POST['cena_new'];	
			}					
			//BYR to RUR
			if ($valute1 == 1 and $valute2 == 4) {
				$cena_blr = $_POST['cena_old'];
				$cena_rur_new = $_POST['cena_new'];	
			}	
			
			//USD to BYR
			if ($valute1 == 2 and $valute2 == 1) {
				$cena_usd = $_POST['cena_old'];
				$cena_blr_new = $_POST['cena_new'];	
			}			
			//USD to EUR
			if ($valute1 == 2 and $valute2 == 3) {
				$cena_usd = $_POST['cena_old'];
				$cena_eur_new = $_POST['cena_new'];	
			}
			//USD to RUR
			if ($valute1 == 2 and $valute2 == 4) {
				$cena_usd = $_POST['cena_old'];
				$cena_rur_new = $_POST['cena_new'];	
			}	
			
			//EUR to BYR
			if ($valute1 == 3 and $valute2 == 1) {
				$cena_eur = $_POST['cena_old'];
				$cena_blr_new = $_POST['cena_new'];	
			}			
			//EUR to USD
			if ($valute1 == 3 and $valute2 == 2) {
				$cena_eur = $_POST['cena_old'];
				$cena_usd_new = $_POST['cena_new'];	
			}			
			//EUR to RUR
			if ($valute1 == 3 and $valute2 == 4) {
				$cena_eur = $_POST['cena_old'];
				$cena_rur_new = $_POST['cena_new'];	
			}		
			
			//RUR to BYR
			if ($valute1 == 4 and $valute2 == 1) {
				$cena_rur = $_POST['cena_old'];
				$cena_blr_new = $_POST['cena_new'];	
			}			
			//RUR to USD
			if ($valute1 == 4 and $valute2 == 2) {
				$cena_rur = $_POST['cena_old'];
				$cena_usd_new = $_POST['cena_new'];	
			}			
			//RUR to EUR
			if ($valute1 == 4 and $valute2 == 3) {
				$cena_rur = $_POST['cena_old'];
				$cena_eur_new = $_POST['cena_new'];	
			}
			
			$nomer = 0;
			$nomer = Database::getLastId($this->_table);
			$nomer++;
			
			$data = array(
				'active' => 1,
				'nomer' => $nomer,
				'id_tree' => @$_POST['id_tree'],			  		  
				'cena_usd' => @$cena_usd,			  
				'cena_blr' => @$cena_blr,			  
				'cena_eur' => @$cena_eur,			  			  
				'cena_rur' => @$cena_rur,			  			  
				'operation' => 5,			  
				'date_create' => date("Y-m-d"),
				'time_create' => date("G:i:s"),
				'id_adminusers' => $_SESSION['isLoggedIn']['id']
			);	
			
			Database::insert($this->_table,$data);	
			
			$data_tmp = array(
				'active' => 1,
				'nomer' => $nomer,
				'id_tree' => @$_POST['id_tree'],
				'kurs' => @$_POST['kurs'],					
				'cena_usd' => @$cena_usd_new,			  
				'cena_blr' => @$cena_blr_new,			  
				'cena_eur' => @$cena_eur_new,			  			  
				'cena_rur' => @$cena_rur_new,			  			  
				'operation' => 6,			  
				'date_create' => date("Y-m-d"),
				'time_create' => date("G:i:s"),
				'id_adminusers' => $_SESSION['isLoggedIn']['id']
			);	
			
			Database::insert($this->_table,$data_tmp);
			
		}

/********************************************************/
/*			Редатирование модуля дерево порталов		*/
/********************************************************/
		if (isset($_POST['action_tree'])) {
			$data_tree = array(
				'name' => $_POST['name'],							
				'id_kontragenty_tip' => $_POST['id_kontragenty_tip'],				
				'id_kontragenty' => $_POST['id_kontragenty'],	
				'pid' => $_POST['pid'],	
				'minus' => ((isset($_POST['minus'])) ? 1 : 0)
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
		$trees  = get_kassa_tree_by_access(Database::getRows($this->_table_tree,'name','asc'));
		echo json_encode($trees);
			
	}
	
	//Получение данных по id в форму для редактирования раздела
	public function open_treeAction() {
		$data = array();
		$data = Database::getRow($this->_table_tree,$_POST['id']);
		echo json_encode($data);
	}   
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($data);
	}
 	
	public function getselectAction() {
		$id = $_POST['id'];
		if (isset($_POST['kassi']))	$items = Database::getRows($this->_table_tree,'id','asc',false,"id_kontragenty_tip = $id");
		else $items = Database::getRows(get_table('kontragenty'),'id','asc',false,"id_tip = $id");
	
		$html = '<option value="0">--нет--</option>';
		foreach($items as $item) {
			$html .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';									
		}
		echo json_encode($html);
	}
	
}