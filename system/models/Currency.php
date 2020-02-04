<?php

class Currency {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->currency');
		self::$_table = self::$_config['table'];
	}
    
  
	public static function getCurrencyByID($id) {
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($currency = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении валют из БД', E_USER_ERROR);
		}

		return $currency;
	}  
  
	public static function getCurrency($id, $order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` 
								WHERE `id_currency_tree` = '.$id.'
								ORDER BY `'.$order_name.'` '.$order.' 
								LIMIT ' . $limit;
	
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении валют из БД', E_USER_ERROR);
		}

		return $makers;
	
	} 
 
	public static function getTotalCurrency($id) {
		
		$statement = 'SELECT count(*) as `count` 
							FROM `' . self::$_table . '`
							WHERE `id_currency_tree` = '.$id;

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества валют в БД!', E_USER_ERROR);
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

	public static function addPage($data) {
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`active`, `id_currency_tree`, `baza`, `name`, `short_name`, `prioritet`, `kurs`)
					  VALUES
						(:active, :id_currency_tree, :baza, :name, :short_name, :prioritet, :kurs)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении валют в БД!', E_USER_ERROR);
		}
	}
  
	public static function updatePage($data) {
		$statement = 'UPDATE `'.self::$_table.'`
						SET `active` = :active,
							`id_currency_tree` = :id_currency_tree,
							`baza` = :baza,
							`name` = :name,
							`short_name` = :short_name,
							`kurs` = :kurs,
							`prioritet` = :prioritet
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания валют в БД!', E_USER_ERROR);
		}
	}
  
	public static function removePage($id) {
		$currency = self::getCurrencyByID($id);

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $currency['id']);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении валют из БД!', E_USER_ERROR);
		}

	}
  
}