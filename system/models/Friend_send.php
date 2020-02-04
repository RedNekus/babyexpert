<?php

class Friend_send {
	private static $_config, $_table, $_table_import;

	public static function init() {
		self::$_config = Config::getParam('modules->friend_send');
		self::$_table = self::$_config['table'];
	}
  
	public static function getZakazByID($id) {
    
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($registration = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении заказов из БД', E_USER_ERROR);
		}

    return $registration;
	
	}
   
	public static function getDatas($id_user) {

		$statement = 'SELECT `date_zakaz` FROM `' . self::$_table . '` WHERE `id_user` = '.$id_user.' GROUP by `date_zakaz`';

		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении заказов из БД', E_USER_ERROR);
		}

    return $makers;
	
	}
	
	public static function getZakazs($code) {

		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `code` = '.$code;
	
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении заказов из БД', E_USER_ERROR);
		}

    return $makers;
	
	} 

  
	public static function getTotalZakazs() {
		
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества заказов в БД!', E_USER_ERROR);
		}

    return $count;
	
	}

	public static function addZakaz($data) {
    
		$statement = 'INSERT INTO `'.self::$_table.'`
                    (`id_catalog`, `id_image`, `date_zakaz`, `code`)
                  VALUES
                    (:id_catalog, :id_image, :date_zakaz, :code)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении заказов в БД!', E_USER_ERROR);
		}
	}
  
	public static function updateZakaz($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_catalog` = :id_catalog,
							`id_image` = :id_image,
							`date_zakaz` = :date_zakaz,
							`code` = :code
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания заказов в БД!', E_USER_ERROR);
		}
	}

	public static function removeZakaz($id) {
		
		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении заказов из БД!', E_USER_ERROR);
		}

	}

}