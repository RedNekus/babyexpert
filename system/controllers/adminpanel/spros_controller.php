<?php

class Spros_Controller {
	private $_table, $_content;

	public function __construct() {
		$this->_table = get_table('spros');
		$this->_content['title'] = 'Спрос';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/spros/list');
   
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
		
		if (isset($_GET['manager']) and !empty($_GET['manager'])) $where .= ' AND id_manager = '.$_GET['manager'];		
		if (isset($_GET['active'])) $where .= ' and active = '.$_GET['active'];
		if (isset($_GET['status']) and !empty($_GET['status'])) $where .= ' and status = '.$_GET['status'];
		if (isset($_GET['date_ot']) and !empty($_GET['date_ot'])) $where .= ' and "'.$_GET['date_ot'].'" <= date_spros';
		if (isset($_GET['date_do']) and !empty($_GET['date_do'])) $where .= ' and "'.$_GET['date_do'].'" >= date_spros';
		
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);

		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, 'active asc, '.$sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		foreach($items as $item) {

			$id_item = $item['id_item'];
			$id_items = explode('-',$id_item);
			
			$id_catalog = (isset($id_items[0])) ? $id_items[0] : $id_item;
			$id_color = (isset($id_items[1])) ? $id_items[1] : 0;
			
			$product = Database::getRow(get_table('catalog'),$id_catalog);
			$colors = Database::getRow(get_table('colors'),$id_color);
			$name_tovar = get_product_name($product,true).' '.@$colors['name'];		
			
			if($item['status']==4) {
				$input = $item['time_spros']; 
				$kolvo_min = $item['chas'] * 60 + $item['min'];
				$result = "Напомнить в ".date('H:i',strtotime($input.' + '.$kolvo_min.' min')); 
			} else {
				$result = "";
			}
			$chas = (($item['chas']>0) ? '<b>'.$item['chas'].'</b> ч.' : '');
			$min = (($item['min']>0) ? '<b>'.$item['min'].'</b> мин.' : '');
		
			$res_now = (date('G') * 60) + date("i");
			$time_arr = explode(':',$item['time_update']);
			$res_spros = (($time_arr[0] + $item['chas']) * 60) + ($time_arr[1] + $item['min']);		
		
			$phone = '';
			if (!empty($item['phone1'])) $phone = $item['phone1'];
			if (!empty($item['phone2'])) $phone .= ','.$item['phone2'];
		
			$client = Database::getRows(get_table('zakaz_client'),'id','asc',false,'phone IN ('.$phone.')');
		
			if ($item['active']==1) { $b = '<span style="color:green">'; $bb = '</span>'; }
			elseif (!empty($client))  { $b = '<span style="color:indigo">'; $bb = '</span>'; }			
			elseif ($item['date_spros']!=date('Y-m-d')) { $b = '<span style="color:red">'; $bb = '</span>'; }
			elseif ($item['status']==1 and $res_now > $res_spros + 60) { $b = '<span style="color:orange">'; $bb = '</span>'; }			
			elseif ($res_now > $res_spros) { $b = '<span style="color:red">'; $bb = '</span>'; }
			else { $b = ''; $bb = '';}

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $b.$item['id_item'].$bb;
			$data['rows'][$i]['cell'][] = $b.Database::getField(get_table('registration'),$item['id_manager'],'id','name').$bb;
			$data['rows'][$i]['cell'][] = $b.$name_tovar.$bb;
			$data['rows'][$i]['cell'][] = $b.get_status_spros($item['status']).$bb;
			$data['rows'][$i]['cell'][] = $b.'<div>'.$item['phone1'].'</div><div>'.$item['phone2'].'</div>'.$bb;
			$data['rows'][$i]['cell'][] = $b.transform_norm_date($item['date_spros']).$bb;
			$data['rows'][$i]['cell'][] = $b.$item['time_spros'].$bb;
			$data['rows'][$i]['cell'][] = $b.$result.$bb;
			$data['rows'][$i]['cell'][] = $b.$item['comment'].$bb;			
			$data['rows'][$i]['cell'][] = $b.(($item['active']!=0) ? 'Да' : 'Нет').$bb;			
			$i++;

		}

		echo json_encode($data);
	
	}  
  
	public function editAction() {
	
		$data = array();
		
		if (isset($_POST['action'])) {

			$data = array( 
				'phone1' => $_POST['phone1'],
				'phone2' => $_POST['phone2'],
				'comment' => $_POST['comment'],
				'chas' => $_POST['chas'],
				'min' => $_POST['min'],
				'active' => ((isset($_POST['active'])) ? 1 : 0)				
			);
			
		}
		
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {

			Database::insert($this->_table,$data);
	
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
		  
			$error = Array();
			$id = $_POST['id'];
			
			$where = "`id` = $id";
			Database::update($this->_table,$data,$where);

		}
		
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			$id = $_POST['del_id'];
			Database::delete($this->_table,$id);
		}	
		
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);
		echo json_encode($data);
	}
		
	public function getselecthtmlAction() {
		get_select_html($_POST['method']);
	} 	
	
}