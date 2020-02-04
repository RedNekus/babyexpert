<?php

class Specpredlozheniya_Controller {
    private $_content, $_config;

	public function defaultAction() {
	  
		$this->_content['left'] = Render::view('catalog/razdel');

		Load::model(array("Catalog", "Images"));
		
		$this->_config = Config::getParam('modules->catalog');
		
		$this->_content['content'] = Render::view(
		
				'catalog/block_index',
				
				array(
	
					'items' => Catalog::getCatalogForSite("spec_pred=1",9),

					'makers' => Maker::getMakersForSite("active=1", "name", "ASC", Maker::getTotalMakers()),
					
					'imagepath' => $this->_config['image'],
						
					'i' => 1
					
				));
			
		Render::layout('page', $this->_content);
		
	}

}