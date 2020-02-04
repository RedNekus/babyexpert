<?php
class Cabinet_Controller {
	private $_content, $_config, $_rows_on_page=20, $_link_by_side=3, $_base_url = "/cabinet/balancelist/";

	public function __construct() {

		if (@$_SESSION['user']) {
			
			Load::model(array('Catalog', 'Images'));
			
			$this->_content['left'] = Render::view('catalog/razdel');
		
			$this->_config = Config::getParam('modules->catalog');
		
		} else {
			
			header("Location: /");
			
		}
														
	}

	public function defaultAction() {

		$this->_content['title'] = "Личная информация";
		
		$error = array();
		
		$items = Registration::getLogin(@$_SESSION['user']['login']);
		$i=0;
		$children = explode(',',$items['children']);
		foreach($children as $child) {
			if (!empty($child)) {
			$array = explode('-',$child);
			$items['child'][$i]['name'] = $array[0];
			$date = $array[1];
			$child_array = explode(':',$date);
			$items['child'][$i]['d'] = $child_array[0];
			$items['child'][$i]['m'] = $child_array[1];
			$items['child'][$i]['y'] = $child_array[2];
			$i++;
			}
		}
		
		$this->_content['content'] = Render::view(
			'cabinet/list', Array (     
				'h1item' => "Личная информация",
				'items' => @$items,
				'error' => $error
			)
		);

		Render::layout('page', $this->_content);
	
	}
	
	public function myadresAction() {
    
		$this->_content['title'] = "Список адресов";

		$this->_content['content'] = Render::view(
			'cabinet/recharge', Array (     
				'h1item' => "Пополнение счета",
				'items' => @$_SESSION['user'],
				'error' => @$error
			)
		);
		Render::layout('page', $this->_content);
	}	
	
	public function myzakazAction() {
    
		$this->_content['title'] = "Мои заказы";

		$items = Registration_zakaz::getDatas($_SESSION['user']['id']);
		
		$this->_content['content'] = Render::view(
			'cabinet/myzakaz', Array (     
				'h1item' => "Мои заказы",
				'items' => @$items,
				'ses_id' => $_SESSION['user']['id'],
				'imagepath' => $this->_config['image'],
				'error' => @$error,
			)
		);

		Render::layout('page', $this->_content);
	}
	
}