<?php

class Adminusers_stats {
  private static $_config, $_table, $_table_import;

  public static function init() {
    self::$_config = Config::getParam('modules->adminusers_stats');
    self::$_table = self::$_config['table'];
  }
  
  public static function getStatByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($adminusers = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении пользователей из БД', E_USER_ERROR);
    }

    return $adminusers;
  }
   
	public static function getStatsByIdCatalog($id_catalog) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_catalog` = '.$id_catalog.' ORDER by `created` DESC';

		if (($adminusers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении пользователей из БД', E_USER_ERROR);
		}

		return $adminusers;
		
	}  
	
	public static function getDateByIdCatalog($id_catalog, $action) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` 
							WHERE `id_catalog` = '.$id_catalog.' and `action` = '.$action.' 
							ORDER by `created` DESC LIMIT 1';

		if (($adminusers = DB::query($statement, null, TRUE)) === FALSE) {
			trigger_error('Ошибка при получении пользователей из БД', E_USER_ERROR);
		}

		return $adminusers;
		
	}   	
 
  public static function getUsers($order_name, $order, $limit) {

	$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
	
    if (($makers = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении пользователей из БД', E_USER_ERROR);
    }

    return $makers;
  } 
   
  public static function getEmail($login) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `login` = :login';

    if (($collection = DB::query($statement, Array('login' => $login), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении Email из БД!', E_USER_ERROR);
    }

    return $collection['email'];
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

	public static function addStats($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_catalog`, `id_adminusers`, `action`, `created`, `created_time`)
					  VALUES
						(:id_catalog, :id_adminusers, :action, :created, :created_time)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении пользователей в БД!', E_USER_ERROR);
		}
		
	}
  
  public static function updateUser($data) {
    $statement = 'UPDATE `'.self::$_table.'`
					SET `active` = :active,
						`fio` = :fio,
						`login` = :login,
						`email` = :email,
						`password` = :password,
						`id_access` = :id_access
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания пользователей в БД!', E_USER_ERROR);
    }
  }
  
  public static function removeUser($id) {
    $adminusers = self::getUserByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $adminusers['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении пользователей из БД!', E_USER_ERROR);
    }

  }

}