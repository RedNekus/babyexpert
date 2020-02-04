<?php

class Raffle_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Raffle','Zakaz','Zakaz_client','Catalog'));
		$this->_config = Config::getParam('modules->raffle');
		$this->_content['title'] = 'Розыгрыши сайта';
	}

	public function defaultAction() {
		$this->listAction();
	}
  
	public function listAction() {
	
		$trees  = Tree::getTrees();
	
		$this->_content['content'] = Render::view(
			'adminpanel/raffle/list', 
			array(
				'img_size' => $this->_config['image']['small'],
				'trees' => get_tree($trees, 0), 
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

		$count = Raffle::getTotalRaffle();  
	
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) {
			$items = Raffle::searchAdmin($searchField, $searchString);
		} else {
			$items = Raffle::getRaffle($sidx, $sord, $data['limit']);
		}
		
		$i = 0;
		foreach($items as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['timestamp'];
			$data['rows'][$i]['cell'][] = $item['timestampend'];
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
  }  
  
	public function editAction() {

		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
		
			$error = Array();
			
			if ($_FILES['image']['name']) {  
			
				$upload = Raffle::uploadImage ($_FILES['image'], $this->_config['image']);

				if ($upload['error']) {$error['image'] = $upload['error'];}
			
			}			
		
			if (!$error) {	
				Raffle::addRaffle(array(
					'ids_catalog' => '',				
					'title' => $_POST['title'],
					'keywords' => $_POST['keywords'],
					'description' => $_POST['description'],		  
					'name' => $_POST['name'],			  	
					'video_url' => $_POST['video_url'],			  	
					'image' => $upload['name'],			
					'active' => ((isset($_POST['active'])) ? 1 : 0),
					'short_description' => $_POST['short_description'],
					'path' => $_POST['path'],
					'prioritet' => $_POST['prioritet'],			
					'timestamp' => $_POST['timestamp'],
					'timestampend' => $_POST['timestampend'],
					'timestamp2' => $_POST['timestamp2'],
					'timestampend2' => $_POST['timestampend2'],
					'timestamp3' => $_POST['timestamp3'],
					'timestampend3' => $_POST['timestampend3'],
					'timestamp4' => $_POST['timestamp4'],
					'timestampend4' => $_POST['timestampend4']
				));	
			}
		
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
		  
			$error = Array();
			$raffle = Raffle::getRaffleByID($_POST['id']);

			if ($_FILES['image']['name']) {  
			
				$upload = Raffle::uploadImage($_FILES['image'], $this->_config['image']);

				if ($upload['error']) {$error['image'] = $upload['error'];}
			
			}

			if (!$error) {
			
				if (!empty($_POST['ids_catalog'])) {
					update_raffle_in_catalog($_POST['id'],$_POST['ids_catalog']);
				}			
				
				Raffle::updateRaffle(array(
					'id' => $_POST['id'],  	
					'ids_catalog' => $_POST['ids_catalog'],					
					'title' => $_POST['title'],
					'keywords' => $_POST['keywords'],
					'description' => $_POST['description'],		  
					'name' => $_POST['name'],	
					'video_url' => $_POST['video_url'],	
					'image' => ((isset($upload)) ? $upload['name'] : $raffle['image']),			
					'active' => ((isset($_POST['active'])) ? 1 : 0),
					'short_description' => $_POST['short_description'],
					'path' => $_POST['path'],
					'prioritet' => $_POST['prioritet'],			
					'timestamp' => $_POST['timestamp'],
					'timestampend' => $_POST['timestampend'],
					'timestamp2' => $_POST['timestamp2'],
					'timestampend2' => $_POST['timestampend2'],
					'timestamp3' => $_POST['timestamp3'],
					'timestampend3' => $_POST['timestampend3'],
					'timestamp4' => $_POST['timestamp4'],
					'timestampend4' => $_POST['timestampend4']					
				));	

				if (isset($upload)) {
					@unlink(ROOT.$this->_config['image']['small']['path'].$raffle['image']);
					@unlink(ROOT.$this->_config['image']['big']['path'].$raffle['image']);
				}
			}

		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			Raffle::removeRaffle($_POST['del_id']);
		}	
	
	}
  
	//Получение данных по id в форму для добавления товара
	public function datahandlingAction() {
		$raffle = array();
		$id_raffle = $_POST['id'];
		$raffle = Raffle::getRaffleByID($id_raffle);
		$raffle['url'] = $this->_config['image']['small']['path'].$raffle['image'];
		$raffle['html1'] = create_clients_win_table($raffle,1);
		$raffle['html2'] = create_clients_win_table($raffle,2);
		$raffle['html3'] = create_clients_win_table($raffle,3);
		$raffle['html4'] = create_clients_win_table($raffle,4);
		$raffle['html5'] = create_clients_win_table($raffle,5);
		$products = Catalog::getCatalogByRaffle($id_raffle);
		$str = '';
		foreach($products as $product) {
			$str .= ','.$product['id'];
		}
		$raffle['ids_catalog'] = $str;
		echo json_encode($raffle);
	}

 
    public function loadPSAction() {
	
		// Начало формирование объекта
		$data = array();
		$params = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
	
		$params['tip_catalog']  = (@$_GET['id']) ? @$_GET['id'] : 1;	
		$params['sort'] 		= $sidx.'-'.$sord;

		$count = Catalog::getPodbor($params,"",FALSE);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		$productions = Catalog::getPodbor($params, $data['limit'], TRUE);
	
		$i = 0;
		foreach($productions as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = get_product_name($item, true);			
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
	} 	
	
	public function sendsmsAction() {
		
		$data = array();
		
		$id = $_POST['id'];
		$phone = $_POST['phone'];
		$tur = $_POST['tur'];
		
		$text = "Поздравляем, Вы победили в РОЗЫГРЫШЕ!\nПодробности на babyexpert.by (ссылка) в разделе Розыгрыши.";
		
		$result = send_sms($text,$phone);
		
		if ($result->status) {
			Zakaz::updateWinner(array(
				'id' => $id,
				'winner' => $tur
				));
			$data['succes'] = TRUE;	
			$data['message'] = "Sms успешно отправлено участнику!";
		} else {
			$data['succes'] = FALSE;	
			$data['message'] = "Sms не отправлена участнику!";		
		}
		
		echo json_encode($data);
		
	}
	
}