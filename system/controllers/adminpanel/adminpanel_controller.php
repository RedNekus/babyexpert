<?php

class Adminpanel_Controller {  

	public function defaultAction() {    

		$data = Array (      

			'title' => 'Главная',      
			
			'content' => Render::view('adminpanel/menu') 
			
		);    

		Render::layout('adminpanel/adminpanel', $data);

	}
	
}