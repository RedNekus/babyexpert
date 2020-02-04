<?php

class Wantproduct_Controller {
    private $_content, $_config;

	public function defaultAction() {
  
		$this->_content['left'] = Render::view('catalog/razdel');
	
		$this->_content['content'] = Render::view('wantproduct');
		
		Render::layout('page', $this->_content);
	
	}
	
	public function sendAction() {
	
		$data = array();	
			
		Load::library('php_mailer/class.phpmailer.php');
			
		$mail = new PHPMailer();
		$mail->SetFrom($_SERVER['SERVER_ADMIN'], 'Сайт '.Config::getParam('modules->wantproduct->zakaz->title'));
		$mail->AddAddress(Adminusers::getEmail("egorych"));
		$mail->Subject = 'Хочу товара которого нет на сайте!';
		$mail->IsHTML(true);		
		$mail->Body = Render::letters(Config::getParam('modules->wantproduct->zakaz->letter'));
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
}