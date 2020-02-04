<?php

class Wantdiscount_Controller {
    private $_content, $_config;

	public function defaultAction() {
  
		$this->_content['left'] = Render::view('catalog/razdel');
	
		$this->_content['content'] = Render::view('wantdiscount');
		
		Render::layout('page', $this->_content);
	
	}
	
	public function sendAction() {
	
		$data = array();	
			
		Load::library('php_mailer/class.phpmailer.php');
			
		$mail = new PHPMailer();
		$mail->SetFrom($_SERVER['SERVER_ADMIN'], 'Сайт '.Config::getParam('modules->wantdiscount->zakaz->title'));
		$mail->AddAddress(Adminusers::getEmail("egorych"));
		$mail->Subject = 'Хочу скидку!';
		$mail->IsHTML(true);		
		$mail->Body = Render::letters(Config::getParam('modules->wantdiscount->zakaz->letter'));
		$mail->CharSet = 'utf-8';
		
		if ($mail->Send()) {		
			$data['succes'] = TRUE;	
			$data['message'] = 'Ваш заявка принята';		
		} else {
			$data['succes'] = FALSE;
			$data['message'] = 'Ошибка при отправке!';
		}

		echo json_encode($data);
		
	} 

	public function foundcheaperAction() {
	
		$this->_content['title'] = "Нашли дешевле";
			
		$this->_content['description'] = "Нашли дешевле";
			
		$this->_content['keywords'] = "Нашли дешевле";
		
		$this->_content['left'] = Render::view('catalog/razdel');
	
		$this->_content['content'] = Render::view('wantdiscount_found');
		
		Render::layout('page', $this->_content);
	
	}	
}