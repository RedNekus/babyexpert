<?php

class Page_Controller{
  private $_content;

  public function defaultAction() {
	
	$this->_content['left'] = Render::view('catalog/razdel');
	
    Render::layout('page', $this->_content);
  }
}