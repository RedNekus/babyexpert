<?php

class Zakaz_client {
	private static $_table, $_table_cur, $_table_zakaz, $_content;

	public static function init() {
		self::$_table = get_table('zakaz_client');
		self::$_table_zakaz = get_table('zakaz');
		self::$_table_cur = get_table('couriers');
	}

	public static function getClientFromCouriers($id_zakaz) {

		$statement = 'SELECT * 
				FROM `'.self::$_table.'` as t1
				JOIN `'.self::$_table_zakaz.'` as t2
				WHERE t1.`id` = t2.`id_client` and t2.`id` = '.$id_zakaz;
		
		if (($catalog_zakaz = DB::query($statement, array('id' => $id_zakaz), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);
		}

		return $catalog_zakaz;
		
	}  
  
	public static function getClient($order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` 
						ORDER BY '.$order_name.' '.$order.', active asc, samovivoz asc, samovivoz_ofice asc, sposob_dostavki asc, dostavka asc 
						LIMIT ' . $limit;

		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);
		}

		return $makers;
		
	}
 
	public static function getTotalClient() {
  
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества данных в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
  
	public static function getClientsByDate($date) {

		$statement = 'SELECT * FROM `' . self::$_table . '`
						WHERE `date_dostavka` = "'.$date.'" and `active` = 1 and `dostavka` = 1
						ORDER BY active asc, samovivoz asc, samovivoz_ofice asc, code_zayavka asc, dostavka asc';

		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);
		}

		return $makers;
		
	}  
	
	public static function getClientsByDateCur($date,$cur_id) {

		$statement = 'SELECT t1.*, t2.id_couriers 
						FROM '.self::$_table.' as t1
						JOIN '.self::$_table_cur.' as t2 ON t1.id = t2.id_client
						WHERE t1.date_dostavka="'.$date.'" and t1.active=1 and t1.dostavka=1 and t2.id_couriers='.$cur_id.'
						ORDER BY active asc, samovivoz asc, samovivoz_ofice asc, code_zayavka asc, dostavka asc';

		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);
		}

		return $makers;
		
	}
	
	public static function searchAdmin($searchField, $searchString) {
  
		if ($searchField=='id_item') $searchField = 't2.'.$searchField;
		if ($searchField=='nomer_zakaza') $searchField = 't1.'.$searchField;
		if ($searchField=='phone') $searchField = 't1.'.$searchField;
  
		$statement = 'SELECT t1.*, t2.id_item 
						FROM `'.self::$_table.'` as t1
						JOIN `'.get_table('zakaz').'` as t2 ON t1.id = t2.id_client
						WHERE '.$searchField.' LIKE "%'.$searchString.'%"';
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
		}

		return $makers;
		
	}
  	
	public static function getLastNomer() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последней данныхе из БД!', E_USER_ERROR);

		}

		return $collection['nomer_zakaza'];

	}  	

	public static function updateClientFromTable($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_couriers` = :id_couriers
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания данных в БД!', E_USER_ERROR);
		}
		
	}

}