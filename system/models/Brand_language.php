<?php

class Brand_language {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->brand');
		self::$_table = self::$_config['table_lng'];
	}
  
	public static function getBrandByID($id_lng) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

		if (($collection = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении новостей из БД', E_USER_ERROR);
		}

		return $collection;
  
	}
  
	public static function getBrandByIdLng($id_brand_lng) {
		
		$statement = 'SELECT * FROM '.self::$_table.' WHERE `id_brand_lng` = :id_brand_lng and `id_language` = 2';

		if (($collection = DB::query($statement, array('id_brand_lng' => $id_brand_lng), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 
	public static function addBrand($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_brand_lng`, `id_language`, `name_lng`, `short_description_lng`, `title_lng`, `keywords_lng`, `description_lng`)
					  VALUES
						(:id_brand_lng, :id_language, :name_lng, :short_description_lng, :title_lng, :keywords_lng, :description_lng)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении новостей в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateBrand($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_brand_lng` = :id_brand_lng,
							`id_language` = :id_language,
							`name_lng` = :name_lng,						
							`short_description_lng` = :short_description_lng,
							`title_lng` = :title_lng,
							`keywords_lng` = :keywords_lng,
							`description_lng` = :description_lng
					  WHERE `id_lng` = :id_lng';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания новостей в БД!', E_USER_ERROR);
		}
		
	}
   
	public static function removeBrand($id_brand_lng) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_brand_lng` = :id_brand_lng;';

		$params = array ('id_brand_lng' => $id_brand_lng);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении новостей из БД!', E_USER_ERROR);
		}

	}
  
}