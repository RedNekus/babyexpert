<?php

class Akcii_language {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->akcii');
		self::$_table = self::$_config['table_lng'];
	}
  
	public static function getAkciiByID($id_lng) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

		if (($akcii = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении акций из БД', E_USER_ERROR);
		}

		return $akcii;
  
	}
  
	public static function getAkciiByIdLng($id_akcii_lng) {
		
		$statement = 'SELECT * FROM '.self::$_table.' WHERE `id_akcii_lng` = :id_akcii_lng and `id_language` = 2';

		if (($collection = DB::query($statement, array('id_akcii_lng' => $id_akcii_lng), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 
	public static function addAkcii($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_akcii_lng`, `id_language`, `name_lng`, `namefull_lng`, `short_description_lng`, `title_lng`, `keywords_lng`, `description_lng`)
					  VALUES
						(:id_akcii_lng, :id_language, :name_lng, :namefull_lng, :short_description_lng, :title_lng, :keywords_lng, :description_lng)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении акций в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateAkcii($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_akcii_lng` = :id_akcii_lng,
							`id_language` = :id_language,
							`name_lng` = :name_lng,
							`namefull_lng` = :namefull_lng,							
							`short_description_lng` = :short_description_lng,
							`title_lng` = :title_lng,
							`keywords_lng` = :keywords_lng,
							`description_lng` = :description_lng
					  WHERE `id_lng` = :id_lng';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания акций в БД!', E_USER_ERROR);
		}
		
	}
   
	public static function removeAkcii($id_akcii_lng) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_akcii_lng` = :id_akcii_lng;';

		$params = array ('id_akcii_lng' => $id_akcii_lng);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении акций из БД!', E_USER_ERROR);
		}

	}
  
}