<?php

class Pages_language {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->pages');
		self::$_table = self::$_config['table_lng'];
	}
  
	public static function getPageByID($id_lng) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

		if (($collection = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении страниц из БД', E_USER_ERROR);
		}

		return $collection;
  
	}
  
	public static function getPageByIdLng($id_pages_lng) {
		
		$statement = 'SELECT * FROM '.self::$_table.' WHERE `id_pages_lng` = :id_pages_lng and `id_language` = 2';

		if (($collection = DB::query($statement, array('id_pages_lng' => $id_pages_lng), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении страниц из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 
	public static function addPage($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_pages_lng`, `id_language`, `name_lng`, `namefull_lng`, `short_description_lng`, `title_lng`, `keywords_lng`, `description_lng`)
					  VALUES
						(:id_pages_lng, :id_language, :name_lng, :namefull_lng, :short_description_lng, :title_lng, :keywords_lng, :description_lng)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении страниц в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updatePage($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_pages_lng` = :id_pages_lng,
							`id_language` = :id_language,
							`name_lng` = :name_lng,
							`namefull_lng` = :namefull_lng,							
							`short_description_lng` = :short_description_lng,
							`title_lng` = :title_lng,
							`keywords_lng` = :keywords_lng,
							`description_lng` = :description_lng
					  WHERE `id_lng` = :id_lng';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания страниц в БД!', E_USER_ERROR);
		}
		
	}
   
	public static function removePage($id_pages_lng) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_pages_lng` = :id_pages_lng;';

		$params = array ('id_pages_lng' => $id_pages_lng);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении страниц из БД!', E_USER_ERROR);
		}

	}
  
}