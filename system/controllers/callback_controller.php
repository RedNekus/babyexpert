<?php

class Callback_Controller {
    private $_content, $_config;

	public function defaultAction() {
	  
		$this->_content['left'] = Render::view('catalog/razdel');
		
		$this->_content['content'] = Render::view('callback');
			
		Render::layout('page', $this->_content);
	
	}
 	
	public function sendAction() {
	
		$data = array();	
			
		Load::library('php_mailer/class.phpmailer.php');
			
		$mail = new PHPMailer();
		$mail->SetFrom($_SERVER['SERVER_ADMIN'], 'Сайт '.Config::getParam('modules->callback->zakaz->title'));
		$mail->AddAddress('zvonok@babyexpert.by');
		$mail->Subject = 'Заказать звонок!';
		$mail->IsHTML(true);		
		$mail->Body = Render::letters(Config::getParam('modules->callback->zakaz->letter'));
		$mail->CharSet = 'utf-8';
		
		if ($mail->Send()) {		
			$data['succes'] = TRUE;	
			$data['message'] = 'Ожидайте в скором времени Вам перезвонят';		
		} else {
			$data['succes'] = FALSE;
			$data['message'] = 'Ошибка при отправке!';
		}

		echo json_encode($data);
		
	}
	
}