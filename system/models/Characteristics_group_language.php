<?php

class Characteristics_group_language {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->characteristics_group');
		self::$_table = self::$_config['table_lng'];
	}
  
	public static function getCharGroupByID($id_lng) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

		if (($char = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении группы из БД', E_USER_ERROR);
		}

		return $char;
  
	}
  
	public static function getCharGroupByIdLng($id_char_group_lng) {
		
		$statement = 'SELECT * FROM '.self::$_table.' WHERE `id_char_group_lng` = :id_char_group_lng and `id_language` = 2';

		if (($collection = DB::query($statement, array('id_char_group_lng' => $id_char_group_lng), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении группы из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 
	public static function addCharGroup($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_char_group_lng`, `id_language`, `name_lng`)
					  VALUES
						(:id_char_group_lng, :id_language, :name_lng)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении группы в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateCharGroup($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_char_group_lng` = :id_char_group_lng,
							`id_language` = :id_language,
							`name_lng` = :name_lng
					  WHERE `id_lng` = :id_lng';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания группы в БД!', E_USER_ERROR);
		}
		
	}
   
	public static function removeCharGroup($id_char_group_lng) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_char_group_lng` = :id_char_group_lng;';

		$params = array ('id_char_group_lng' => $id_char_group_lng);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении группы из БД!', E_USER_ERROR);
		}

	}
  
}