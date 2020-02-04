<?php

class Aaa_Controller {  

	public function __construct() {
		$this->_content['title'] = 'Снос таблиц';
	}
	
	public function defaultAction() {    
  
	}

	public function dropAction() {
		$access = get_array_access();
		if ($access['id']==1) Database::dropTable(get_table('zakaz'));	
	}
	
	
}