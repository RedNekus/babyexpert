<?php

class Maker_language {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->maker');
		self::$_table = self::$_config['table_lng'];
	}
  
	public static function getMakerByID($id_lng) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

		if (($collection = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении производителей из БД', E_USER_ERROR);
		}

		return $collection;
  
	}
  
	public static function getMakerByIdLng($id_maker_lng) {
		
		$statement = 'SELECT * FROM '.self::$_table.' WHERE `id_maker_lng` = :id_maker_lng and `id_language` = 2';

		if (($collection = DB::query($statement, array('id_maker_lng' => $id_maker_lng), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 
	public static function addMaker($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_maker_lng`, `id_language`, `name_lng`, `namefull_lng`, `country_lng`, `short_description_lng`, `title_lng`, `keywords_lng`, `description_lng`)
					  VALUES
						(:id_maker_lng, :id_language, :name_lng, :namefull_lng, :country_lng, :short_description_lng, :title_lng, :keywords_lng, :description_lng)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении производителей в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateMaker($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_maker_lng` = :id_maker_lng,
							`id_language` = :id_language,
							`name_lng` = :name_lng,
							`namefull_lng` = :namefull_lng,							
							`country_lng` = :country_lng,							
							`short_description_lng` = :short_description_lng,
							`title_lng` = :title_lng,
							`keywords_lng` = :keywords_lng,
							`description_lng` = :description_lng
					  WHERE `id_lng` = :id_lng';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания производителей в БД!', E_USER_ERROR);
		}
		
	}
   
	public static function removeMaker($id_maker_lng) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_maker_lng` = :id_maker_lng;';

		$params = array ('id_maker_lng' => $id_maker_lng);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении производителей из БД!', E_USER_ERROR);
		}

	}
  
}