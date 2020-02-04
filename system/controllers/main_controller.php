<?php

class Main_Controller {
  public function __construct() {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      no_cache();
    }

    if (URL::getSegment(1) == 'adminpanel') {
		Load::controller('adminpanel/authenticate');

		$access = get_array_access();
		$route = URL::getSegment(2);

		if (isset($access[$route . '_review']) && $access[$route . '_review'] == 0) {
			unset($_SESSION['isLoggedIn']);
			header('location: /');
			exit();
		}		
    }

    $controller = URL::getController();
    $action = URL::getAction();

    Load::controller($controller, $action);

  }
}