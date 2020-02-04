<?php

class Banners_language {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->banners');
		self::$_table = self::$_config['table_lng'];
	}
  
	public static function getBannersByID($id_lng) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

		if (($banners = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении акций из БД', E_USER_ERROR);
		}

		return $banners;
  
	}
  
	public static function getBannersByIdLng($id_banners_lng) {
		
		$statement = 'SELECT * FROM '.self::$_table.' WHERE `id_banners_lng` = :id_banners_lng and `id_language` = 2';

		if (($collection = DB::query($statement, array('id_banners_lng' => $id_banners_lng), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 
	public static function addBanners($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_banners_lng`, `id_language`, `name_lng`, `short_description_lng`)
					  VALUES
						(:id_banners_lng, :id_language, :name_lng, :short_description_lng)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении акций в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateBanners($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_banners_lng` = :id_banners_lng,
							`id_language` = :id_language,
							`name_lng` = :name_lng,						
							`short_description_lng` = :short_description_lng
					  WHERE `id_lng` = :id_lng';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания акций в БД!', E_USER_ERROR);
		}
		
	}
   
	public static function removeBanners($id_banners_lng) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_banners_lng` = :id_banners_lng;';

		$params = array ('id_banners_lng' => $id_banners_lng);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении акций из БД!', E_USER_ERROR);
		}

	}
  
}