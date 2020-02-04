<?php

class Characteristics_language {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->characteristics');
		self::$_table = self::$_config['table_lng'];
	}
  
	public static function getCharacteristicsByID($id_lng) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

		if (($char = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении акций из БД', E_USER_ERROR);
		}

		return $char;
  
	}
  
	public static function getCharacteristicsByIdLng($id_char_lng) {
		
		$statement = 'SELECT * FROM '.self::$_table.' WHERE `id_char_lng` = :id_char_lng and `id_language` = 2';

		if (($collection = DB::query($statement, array('id_char_lng' => $id_char_lng), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 
	public static function addCharacteristics($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_char_lng`, `id_language`, `name_lng`, `prefix_lng`, `sufix_lng`)
					  VALUES
						(:id_char_lng, :id_language, :name_lng, :prefix_lng, :sufix_lng)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении акций в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateCharacteristics($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_char_lng` = :id_char_lng,
							`id_language` = :id_language,
							`name_lng` = :name_lng,
							`prefix_lng` = :prefix_lng,							
							`sufix_lng` = :sufix_lng
					  WHERE `id_lng` = :id_lng';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания акций в БД!', E_USER_ERROR);
		}
		
	}
   
	public static function removeCharacteristics($id_char_lng) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_char_lng` = :id_char_lng;';

		$params = array ('id_char_lng' => $id_char_lng);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении акций из БД!', E_USER_ERROR);
		}

	}
  
}