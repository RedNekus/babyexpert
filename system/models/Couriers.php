<?php

class Couriers {
	private static $_table, $_table_zakaz;

	public static function init() {
		self::$_table = get_table('couriers');		
		self::$_table_zakaz = get_table('zakaz_client');		
	}
  
	public static function getCouriersByID($id) {
		
		$statement = 'SELECT t1.*, t2.skidka_procent, t2.skidka_cena, t2.doplata, t2.cena_dostavka, t2.sposob_dostavki 
				FROM `'.self::$_table.'` as t1
				JOIN `'.self::$_table_zakaz.'` as t2
				WHERE t1.`id` = :id and t1.`id_client` = t2.`id`';

		if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении курьеров из БД', E_USER_ERROR);
		}

		return $collection;
	
	}  

	public static function getCourierss($valuerazdel, $adopted, $order_name, $order, $limit) {
  
		$statement = 'SELECT t1.*, t2.date_dostavka, t2.id as id_cl 
						FROM `'.self::$_table.'` as t1
						JOIN `'.self::$_table_zakaz.'` as t2 ON t1.`id_client` = t2.`id`
						WHERE t1.`id_couriers` = '.$valuerazdel.' and t1.`adopted` = '.$adopted.'						
						ORDER BY '.$order_name.' '.$order.' 
						LIMIT ' . $limit;

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении курьеров из БД', E_USER_ERROR);
		}

		return $collections;
		
	}  

	public static function getTotalCourierss($valuerazdel, $adopted) {
		
		$statement = 'SELECT count(*) as `count` FROM `'.self::$_table.'` WHERE `id_couriers` = '.$valuerazdel.' and `adopted` = '.$adopted;

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества курьеров в БД!', E_USER_ERROR);
		}

		return $count;
	
	}
	
	public static function getCouriersByDate($id_couriers, $adopted, $date_ot, $date_do, $order_name, $order, $limit) {
  
		if (!empty($date_ot)) $sql_ot = ' and t2.`date_dostavka` >= "'.$date_ot.'"';
		if (!empty($date_do)) $sql_do = ' and t2.`date_dostavka` <= "'.$date_do.'"';
  
		if ($id_couriers != 0) $where_couriers = 't1.`id_couriers` = '.$id_couriers.' and';
  
		$sql = 'SELECT t1.*, t2.date_dostavka, t2.id as id_cl 
						FROM `'.self::$_table.'` as t1
						JOIN `'.self::$_table_zakaz.'` as t2
						WHERE '.@$where_couriers.' t1.`id_client` = t2.`id`'.@$sql_ot.@$sql_do.' and t1.`adopted` = '.$adopted.'						
						ORDER BY '.$order_name.' '.$order.' 
						LIMIT ' . $limit;
				
		if (($collections = DB::query($sql)) === FALSE) {
			trigger_error('Ошибка при получении курьеров из БД', E_USER_ERROR);
		}

		return $collections;
		
	} 

	public static function getTotalCouriersByDate($id_couriers, $adopted, $date_ot, $date_do) {
  
		if (isset($date_ot)) $sql_ot = ' and t2.`date_dostavka` >= "'.$date_ot.'"';
		if (isset($date_do)) $sql_do = ' and t2.`date_dostavka` <= "'.$date_do.'"';
   
		if ($id_couriers != 0) $where_couriers = 't1.`id_couriers` = '.$id_couriers.' and';
 		
		$sql = 'SELECT count(*) as `count` 
						FROM `'.self::$_table.'` as t1
						JOIN `'.self::$_table_zakaz.'` as t2
						WHERE '.@$where_couriers.' t1.`id_client` = t2.`id`'.@$sql_ot.@$sql_do.' and t1.`adopted` = '.$adopted;

		if (($count = DB::query($sql, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества курьеров в БД!', E_USER_ERROR);
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
	
}