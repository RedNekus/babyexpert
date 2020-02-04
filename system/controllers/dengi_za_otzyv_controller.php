<?php

class Dengi_za_otzyv_Controller {
    private $_content, $_config;

	public function defaultAction() {
  
		$this->_content['left'] = Render::view('catalog/razdel');
	
		$this->_content['content'] = Render::view('dengi_za_otzyv');
		
		Render::layout('page', $this->_content);
	
	}
 
}