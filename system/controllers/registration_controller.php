<?php

class Registration_Controller {
	private $_table, $_content;

	public function __construct() {
		Load::model('Registration');
	
		$this->_table = Config::getParam('modules->registration->table');
		$this->_content['left'] = Render::view('catalog/razdel');
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view ('registration/list');
  
		Render::layout('page',  $this->_content);
	}

	public function addAction() {
  
		$data = array();

		if (isset($_POST['email']) && !($error = Registration::checkForm())) {
			
			$myrow = Registration::getLogin($_POST['login']);
			
			if (empty($myrow['id'])) {

				$children = '';
				$phone = '';
				
				foreach($_POST['children'] as $item) {
					$children .= $item['name'].'-'.$item['d'].':'.$item['m'].':'.$item['y'].',';	
				}
				
				foreach($_POST['phone'] as $item) {
					$phone .= $item['name'].',';
				}

				Registration::addUser(array(
					'manager' => 0,
					'id_adminuser' => 0,
					'login' => htmlspecialchars($_POST['login']),
					'pass' => md5($_POST['pass']),
					'name' => htmlspecialchars($_POST['name']),
					'fam' => htmlspecialchars($_POST['fam']),
					'email' => htmlspecialchars($_POST['email']),
					'phone' => $phone,
					'children' => $children,		 			
					'city' => htmlspecialchars($_POST['city']),
					'street' => htmlspecialchars($_POST['street']),
					'house' => htmlspecialchars($_POST['house']),
					'building' => htmlspecialchars($_POST['building']),
					'apartment' => htmlspecialchars($_POST['apartment']),
					'entrance' => htmlspecialchars($_POST['entrance']),
					'floor' => htmlspecialchars($_POST['floor']),
					'comment' => htmlspecialchars($_POST['comment']),
					'card_number' => htmlspecialchars($_POST['card_number']),
					'promo_code' => htmlspecialchars($_POST['promo_code']),
					'discount_price' => ((isset($_POST['discount_price'])) ? 1 : 0),
					'newsletter' => ((isset($_POST['newsletter'])) ? 1 : 0),
					'active' => 0	
				));

				$data['succes'] = TRUE;	
				$data['redirect'] = 1;
				$data['url'] = '/registration/sms/?login='.$_POST['login'];
				$phone = $_POST['phone'][0]['name'];
				
				if (isset($phone) and !empty($phone)) {
				
					$code  = mt_rand(10000,99999); 
					
					$text = 'Ваш код регистрации: '.$code;
					
					$result = send_sms($text,$phone);
					 
					if ($result->status) {
			
						$_SESSION['code'] = $code;
						$_SESSION['result'] = $result;
						
					}

				}				
	
			} else { 
				$data['succes'] = FALSE;
				$data['message'] = 'Такой логин уже существует';
				
			}
		}
		echo json_encode($data);
	}
	
	public function saveAction() {
  
		$data = array();

		if (isset($_POST['email']) && !($error = Registration::checkForm())) {
			
			$myrow = Registration::getLogin($_POST['login']);

				if ($_POST['pass']==$myrow['pass']) {
					$passw = $_POST['pass'];
				} else {
					$passw = md5($_POST['pass']);
				}
				$children = '';
				$phone = '';
				
				foreach($_POST['children'] as $item) {
					$children .= $item['name'].'-'.$item['d'].':'.$item['m'].':'.$item['y'].',';	
				}

				Registration::updateUser(array(
					'id' => $myrow['id'],
					'manager' => $myrow['manager'],
					'id_adminuser' => $myrow['id_adminuser'],
					'login' => htmlspecialchars($_POST['login']),
					'pass' => $passw,
					'name' => htmlspecialchars($_POST['name']),
					'fam' => htmlspecialchars($_POST['fam']),
					'email' => htmlspecialchars($_POST['email']),
					'phone' => $_POST['phone'],
					'children' => $children,		 			
					'city' => htmlspecialchars($_POST['city']),
					'street' => htmlspecialchars($_POST['street']),
					'house' => htmlspecialchars($_POST['house']),
					'building' => htmlspecialchars($_POST['building']),
					'apartment' => htmlspecialchars($_POST['apartment']),
					'entrance' => htmlspecialchars($_POST['entrance']),
					'floor' => htmlspecialchars($_POST['floor']),
					'comment' => $myrow['comment'],
					'card_number' => htmlspecialchars($_POST['card_number']),
					'promo_code' => htmlspecialchars($_POST['promo_code']),
					'discount_price' => ((isset($_POST['discount_price'])) ? 1 : 0),
					'newsletter' => ((isset($_POST['newsletter'])) ? 1 : 0),
					'active' => $myrow['active']	
				));

				$data['succes'] = TRUE;	
				$data['redirect'] = 1;
				$data['url'] = '/cabinet/';
		}
		echo json_encode($data);
	}
	
	public function loginAction() {

		$data = array();	
		$data['succes'] = FALSE;
		
		if (isset($_POST['login'])) $login = $_POST['login'];  
		if (isset($_POST['pass'])) $pass=$_POST['pass'];
   
		if (($login != '') or ($pass != ''))  {

		$login = stripslashes($login);
		$login = htmlspecialchars($login);
		$pass = stripslashes($pass);
		$pass = htmlspecialchars($pass);

		$login = trim($login);
		$pass = md5(trim($pass));

		$myrow = Registration::getLogin($login);
    
		if (empty($myrow['login'])) {
			$data['message'] = "Данный пользователь не зарегистрирован в системе.";
		} else {
		
			if ($myrow['pass']==$pass) {
			//если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
			$_SESSION['user']=$myrow;
			
			$name_expire = Config::getParam('modules->registration->cookie->expire');

			setcookie("login", $_POST['login'], time() + $name_expire, '/');
			setcookie("pass", $_POST['pass'], time() + $name_expire, '/');
			unset($_SESSION['collection']);
			$data['succes'] = TRUE;
			//$data['message'] = "Успешный вход";
			} else {			
			$data['message'] = "Извините, введённый вами пароль неверный.";
			}
			
		}
	} else {
		$data['message'] = "Вы ввели не всю информацию, заполните все поля!";
	}
	echo json_encode($data);
	}

	public function logoutAction() {
  
		unset($_SESSION['user']);
		unset($_SESSION['collection']);
		header('location: /');
  
	}
	
	public function smsAction() {
		
		$this->_content['content'] = Render::view ('registration/sms',
						array(
							'result' => @$_SESSION['result']							
						));
  
		Render::layout('page',  $this->_content);
		
	}	
	
	public function activeuserAction() {
		
		$data = array();
		
		if (isset($_POST['code'])) {
		
			if ($_POST['code']==$_SESSION['code']) {
			
				$item = Registration::getLogin($_POST['login']);
				
				Registration::activeUser(array(
						'id' => $item['id'],
						'active' => 1
						));
			
				$data['succes'] = TRUE;
				$data['message'] = 'Успешная активация пользователя. 
				Можете зайти в личный кабинет. 
				<a href="/" title="">На главную</a>';
				
			} else {
			
				$data['message'] = "Извините, введенный вами код не совпадает с указанным в смс.";
			
			}
		
		}

	echo json_encode($data);
		
	}
  
}