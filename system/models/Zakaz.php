<?php

class Zakaz {
	private static $_config, $_table, $_config_client, $_table_client; 

	public static function init() {
		self::$_config = Config::getParam('modules->zakaz');
		self::$_table = self::$_config['table'];
		
		self::$_config_client = Config::getParam('modules->zakaz_client');
		self::$_table_client = self::$_config_client['table'];		
	}
  
	public static function getZakazByID($id) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($catalog_zakaz = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);
		}

		return $catalog_zakaz;
		
	}
  
	public static function getZakaz($order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` 
						ORDER BY '.$order_name.' '.$order.' 
						LIMIT ' . $limit;

		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);
		}

		return $makers;
		
	}
	  	
	public static function getZakazByRaffleId($id_raffle) {

		$statement = 'SELECT * FROM `' . self::$_table . '` 
						WHERE raffle = '.$id_raffle;

		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);
		}

		return $makers;
		
	}	
	
	public static function getZakazsByDate($raffle_id,$date_ot,$date_do) {

		$statement = 'SELECT t1.*, t2.date_zakaz
					  FROM '.self::$_table.' as t1
					  JOIN '.self::$_table_client.' as t2 ON t1.id_client = t2.id
					  WHERE t1.raffle = '.$raffle_id.' and t2.date_zakaz > "'.$date_ot.'" and t2.date_zakaz <= "'.$date_do.'"';

		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);
		}

		return $makers;
		
	}	
	
	public static function getCountByDate($raffle_id,$date_ot,$date_do) {

		$statement = 'SELECT count(*) as `count` 
					  FROM '.self::$_table.' as t1
					  JOIN '.self::$_table_client.' as t2 ON t1.id_client = t2.id
					  WHERE t1.raffle = '.$raffle_id.' and t2.date_zakaz > "'.$date_ot.'" and t2.date_zakaz < "'.$date_do.'"';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества данных в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
	
	public static function getTotalZakaz() {
  
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества данных в БД!', E_USER_ERROR);
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

			trigger_error('Ошибка при получении информации о последней данныхе из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
	
	public static function addZakaz($data) {
  
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_client`, `id_catalog`, `id_item`, `name_tovar`, `nomer_zakaza`, `active`, `cena`, `cena_blr`, `kolvo`, `raffle`, `id_gift`, `promocode`)
					  VALUES
						(:id_client, :id_catalog, :id_item, :name_tovar, :nomer_zakaza, :active, :cena, :cena_blr, :kolvo, :raffle, :id_gift, :promocode)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении данных в БД!', E_USER_ERROR);
		}
		
	}
	
	public static function updateZakaz($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_client` = :id_client,
							`id_catalog` = :id_catalog,
							`id_item` = :id_item,
							`name_tovar` = :name_tovar,
							`nomer_zakaza` = :nomer_zakaza,
							`active` = :active,
							`cena` = :cena,
							`cena_blr` = :cena_blr,
							`kolvo` = :kolvo,
							`raffle` = :raffle,
							`id_gift` = :id_gift,
							`promocode` = :promocode
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания данных в БД!', E_USER_ERROR);
		}
		
	}	
	
	public static function updateCouriers($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `was` = :was,
							`delivered` = :delivered,
							`passed` = :passed
					  WHERE `id_client` = :id_client';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания данных в БД!', E_USER_ERROR);
		}
		
	}
	
	
	public static function updateWinner($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `winner` = :winner
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении победителя в БД!', E_USER_ERROR);
		}
		
	}	
	
	public static function updateFromCouriers($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `was` = :was,
						    `delivered` = :delivered,
						    `passed` = :passed
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении победителя в БД!', E_USER_ERROR);
		}
		
	}	
  
	public static function removeZakaz($id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении данных из БД!', E_USER_ERROR);
		}

	}

}