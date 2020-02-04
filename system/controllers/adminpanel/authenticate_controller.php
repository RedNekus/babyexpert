<?php

class Authenticate_Controller {
	public function __construct() {
		
		if (isset($_GET['logout'])) {
			unset($_SESSION['isLoggedIn']);
			header('location: /');
			exit();
		}

		$error = Array();

		if (isset($_POST['login_form'])) {
		
			if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } 
			if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }

			if ($_POST['login'] == $login && $_POST['password'] == $password) {

				$login = trim(stripslashes(htmlspecialchars($login)));
				$password = trim(stripslashes(htmlspecialchars($password)));

				$password = md5($password);
				
				$user = Database::getRow(get_table('adminusers'),$login,'login');
					
				if (empty($user['password'])) {
					Log::write('Пользователь с введенным логином не существует', FALSE, TRUE);
					$error['no_valid'] = true;
				} else { 
					if ($user['password']==$password and $user['active']==1) { 
						$_SESSION['isLoggedIn'] = $user;
						header('location:' . $_SERVER['REQUEST_URI']);
						exit();
					} else {  //если пароли не сошлись
						Log::write('Не верный логин/пароль к CMS', FALSE, TRUE);
						$error['no_valid'] = true;
					}
				}			

			} 
		}

		if (!isset($_SESSION['isLoggedIn'])) {
			Render::layout('adminpanel/login', Array('error' => $error));
			exit();
		}
  
	}
	
}