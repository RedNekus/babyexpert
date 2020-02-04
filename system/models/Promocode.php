<?php

class Promocode {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->promocode');
		self::$_table = self::$_config['table'];
	}
  
	public static function getPageByID($id) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($promocode = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении промокодов из БД', E_USER_ERROR);
		}

		return $promocode;
		
	}  
	
	public static function getPageByName($name) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `name` = :name and `active` = 1';

		if (($promocode = DB::query($statement, array('name' => $name), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении промокодов из БД', E_USER_ERROR);
		}

		return $promocode;
		
	}
  
	public static function getPromocode($order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении промокодов из БД', E_USER_ERROR);
		}

		return $makers;
		
	}
 
	public static function getTotalPromocode() {
  
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества промокодов в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
  
	public static function getPromocodeRaffle() {

		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE active_raffle = 1 ORDER by id ASC';
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении промокодов из БД', E_USER_ERROR);
		}

		return $makers;
		
	}
	
	public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
		}

		return $makers;
		
	}

	public static function addPage($data) {
  
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`active`, `name`, `active_raffle`)
					  VALUES
						(:active, :name, :active_raffle)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении промокодов в БД!', E_USER_ERROR);
		}
		
	}
	
	public static function updatePage($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `active` = :active,
							`name` = :name,
							`active_raffle` = :active_raffle
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания промокодов в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function removePage($id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении промокодов из БД!', E_USER_ERROR);
		}

	}

  
}