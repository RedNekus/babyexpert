<?php

class Home_Controller {
    private $_content, $_config;

	public function defaultAction() {
	  
		$this->_content['left'] = Render::view('catalog/razdel');

		Load::model(array("Catalog", "Images"));
		
		$this->_config = Config::getParam('modules->catalog');
		
		$this->_content['content'] = Render::view(
		
				'catalog/block_index',
				
				array(
	
					'items' => Catalog::getCollectionsActive(255,'prioritet','DESC',9),
					
					'makers' => Maker::getMakersForSite("active=1", "name", "ASC", Maker::getTotalMakers()),
					
					'imagepath' => $this->_config['image'],
						
					'i' => 1
					
				));
			
		Render::layout('page', $this->_content);
		
	}
	
	public function setcurrencyAction() {
	
		if (isset($_GET['currency'])) $_SESSION['currency']=$_GET['currency'];
		header('Location: '.$_SERVER['HTTP_REFERER']);
	
	}	
	
	public function refreshcurrencyAction() {
	
		Load::model("Catalog");
	
		$data = array();
		if (isset($_GET['currency'])) $_SESSION['currency']=$_GET['currency'];
		if (isset($_GET['id_catalog'])) {
			$collection = Catalog::getCollectionById($_GET['id_catalog']);
			if (!empty($collection)) {
				$data['succes'] = TRUE;
				$data['html'] = transform_to_currency($collection); 			
			} else {
				$data['succes'] = FALSE;
			}
		} else {
			$data['succes'] = FALSE;
		}

		echo json_encode($data);
		
	}	


	//public function clearcurAction() {unset($_SESSION['currency']);} 
}