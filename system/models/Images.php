<?php

class Images {
	private static $_config, $_config_catalog, $_table;

	public static function init() {
		self::$_config = Config::getParam('modules->images');
		self::$_config_catalog = Config::getParam('modules->catalog');
		self::$_table = self::$_config['table'];
	}
  
	public static function getImagesByID($id) {
    
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении изображения из БД', E_USER_ERROR);
		}

		return $collection;
	
	}
   
	public static function getImagesByIdCatalog($id_catalog) {
  
		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE id_catalog = '.$id_catalog.' ORDER by `showfirst` DESC';

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении изображения из БД', E_USER_ERROR);
		}

		return $collections;
	
	}  
	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последнем проекте из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
  
	public static function addImages($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_catalog`, `image`, `description`, `showfirst`)
					  VALUES
						(:id_catalog, :image, :description, :showfirst)';
		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении изображения в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function updateImages($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `id_catalog` = :id_catalog,					  
						  `image` = :image,
						  `showfirst` = :showfirst,
						  `description` = :description					  
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания изображения в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function removeImages($id) {
    
		$collection = self::getImagesByID($id);

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $collection['id']);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении изображения из БД!', E_USER_ERROR);
		}

		//@unlink(ROOT.self::$_config_catalog['image']['small']['path'].$collection['image']);
		//@unlink(ROOT.self::$_config_catalog['image']['big']['path'].$collection['image']);
	
	}  
	
	public static function removeImagesByIdCatalog($id_catalog) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_catalog` = :id_catalog;';

		$params = array ('id_catalog' => $id_catalog);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении изображения из БД!', E_USER_ERROR);
		}
	
	}

}