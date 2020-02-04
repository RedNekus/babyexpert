<?php

class Connection {
	private static $_config, $_table;

	public static function init() {
		self::$_config = Config::getParam('modules->connection');
		self::$_table = self::$_config['table'];
	}
  
	public static function getConnectionByID($id) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении связей из БД', E_USER_ERROR);
		}

		return $collection;
	
	}  
  
	public static function getConnections($valuerazdel, $order_name, $order, $limit) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` 
						WHERE `id_tree` = '.$valuerazdel.' 						
						ORDER BY `'.$order_name.'` '.$order.' 
						LIMIT ' . $limit;
	
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении связей из БД', E_USER_ERROR);
		}

		return $collections;
		
	} 

	public static function getTotalConnections($valuerazdel) {
		
		$statement = 'SELECT count(*) as `count` FROM `'.self::$_table.'` WHERE `id_tree` = '.$valuerazdel;

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества связей в БД!', E_USER_ERROR);
		}

		return $count;
	
	}
  
	public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
		}

		return $collections;
		
	}
	
	public static function getTotalSearchAdmin($searchField, $searchString) {

		$statement = 'SELECT count(DISTINCT `id`) as `count` 
						FROM `'.self::$_table.'`
						WHERE '.$searchField.' LIKE "%'.$searchString.'%"';		
		
		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества продукции в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
	
	public static function addConnection($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_tree`, `name_portal`, `name_site`, `active`)
					  VALUES
						(:id_tree, :name_portal, :name_site, :active)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении связей в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function updateConnection($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `id_tree` = :id_tree,
						  `name_portal` = :name_portal,
						  `name_site` = :name_site,
						  `active` = :active
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания связей в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function removeConnection($id) {
	  
		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении связей из БД!', E_USER_ERROR);
		}

	}

}