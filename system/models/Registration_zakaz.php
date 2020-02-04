<?php

class Registration_zakaz {
	private static $_config, $_table, $_table_import;

	public static function init() {
		self::$_config = Config::getParam('modules->registration_zakaz');
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
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последнем товаре из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
	
	public static function getZakazs($id_user,$date_zakaz) {

		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_user` = '.$id_user.' AND `date_zakaz` = "'.$date_zakaz.'"';
	
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
                    (`id_user`, `id_catalog`, `date_zakaz`, `kolvo`)
                  VALUES
                    (:id_user, :id_catalog, :date_zakaz, :kolvo)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении заказов в БД!', E_USER_ERROR);
		}
	}
  
	public static function updateZakaz($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_user` = :id_user,
							`id_catalog` = :id_catalog,
							`date_zakaz` = :date_zakaz,
							`kolvo` = :kolvo
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания заказов в БД!', E_USER_ERROR);
		}
	}

	public static function removeZakaz($id) {
		
		$registration = self::getZakazByID($id);

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $registration['id']);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении заказов из БД!', E_USER_ERROR);
		}

	}

}