<?php

class Advantages {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->advantages');
		self::$_table = self::$_config['table'];
	}
  
	public static function getAdvantages($order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении списка преимуществ из БД', E_USER_ERROR);
		}

		return $makers;
	
	} 
 
	public static function getTotalAdvantages() {
	  
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества преимуществ в БД!', E_USER_ERROR);
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
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последней акции из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
	
	public static function addAdvantages($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`active`, `name`, `namefull`, `path`, `prioritet`, `short_description`, `title`, `keywords`, `description`, `timestamp`)
					  VALUES
						(:active, :name, :namefull, :path, :prioritet, :short_description, :title, :keywords, :description, :timestamp)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении преимущества в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function updateAdvantages($data) {
	
		$statement = 'UPDATE `'.self::$_table.'`
						SET `active` = :active,
							`name` = :name,
							`namefull` = :namefull,
							`path` = :path,
							`prioritet` = :prioritet,
							`short_description` = :short_description,
							`title` = :title,
							`keywords` = :keywords,
							`description` = :description,
							`timestamp` = :timestamp
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания преимуществ в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function removeAdvantages($id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении преимуществ из БД!', E_USER_ERROR);
		}

	}
  
}