<?php

class Registration {
  private static $_config, $_table, $_table_import;

  public static function init() {
    self::$_config = Config::getParam('modules->registration');
    self::$_table = self::$_config['table'];
  }
  
  public static function getUserByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($registration = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении пользователей из БД', E_USER_ERROR);
    }

    return $registration;
  }
   
  public static function getLogin($login) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `login` = :login';

    if (($registration = DB::query($statement, Array('login' => $login), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении данных из БД!', E_USER_ERROR);
    }

    return $registration;
  }
  
  public static function getUsers($order_name, $order, $limit) {

	$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
	
    if (($makers = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении пользователей из БД', E_USER_ERROR);
    }

    return $makers;
  } 
  
  public static function getTotalUsers() {
    $statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

    if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества пользователей в БД!', E_USER_ERROR);
    }

    return $count;
  }
  
  public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($makers = DB::query($statement)) === FALSE) {
		  trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
    }

    return $makers;
  }

  public static function addUser($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`manager`, `id_adminuser`, `login`, `pass`, `name`, `fam`, `city`, `email`, `phone`, `children`, `street`, `house`, `building`, `apartment`, `entrance`, `floor`, `comment`, `card_number`, `promo_code`, `discount_price`, `newsletter`, `active`)
                  VALUES
                    (:manager, :id_adminuser, :login, :pass, :name, :fam, :city, :email, :phone, :children, :street, :house, :building, :apartment, :entrance, :floor, :comment, :card_number, :promo_code, :discount_price, :newsletter, :active)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении пользователей в БД!', E_USER_ERROR);
    }
  }
  
	public static function updateUser($data) {
      
	  $statement = 'UPDATE `'.self::$_table.'`
					SET `manager` = :manager,
						`id_adminuser` = :id_adminuser,
						`login` = :login,
						`pass` = :pass,
						`name` = :name,
						`fam` = :fam,
						`city` = :city,
						`email` = :email,
						`phone` = :phone,
						`children` = :children,
						`street` = :street,
						`house` = :house,
						`building` = :building,
						`apartment` = :apartment,
						`entrance` = :entrance,
						`floor` = :floor,
						`comment` = :comment,
						`card_number` = :card_number,
						`promo_code` = :promo_code,
						`discount_price` = :discount_price,
						`newsletter` = :newsletter,
						`active` = :active
					WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания пользователей в БД!', E_USER_ERROR);
		}
	}  
	
	public static function activeUser($data) {
      
	  $statement = 'UPDATE `'.self::$_table.'`
					SET `active` = :active
					WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания пользователей в БД!', E_USER_ERROR);
		}
	}
  
  public static function checkForm() {
    $error = Array();
	
    if ((isset($_POST['password'])) and (isset($_POST['password_reply']))) {
		if ($_POST['password'] === $_POST['password_reply']) {
			$error = FALSE;
		} else {
			$error = TRUE;
		}
    }

    return $error;
  }

  public static function removeUser($id) {
    $registration = self::getUserByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $registration['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении пользователей из БД!', E_USER_ERROR);
    }

  }

}