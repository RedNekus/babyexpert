<?php

class Catalog_characteristics {
	private static $_config, $_table, $_config_categories, $_table_categories, $_config_catalog, $_table_catalog;

	public static function init() {
		self::$_config = Config::getParam('modules->catalog_characteristics');
		self::$_table = self::$_config['table'];

		self::$_config_categories = Config::getParam('modules->catalog_categories');
		self::$_table_categories = self::$_config_categories['table'];
		
		self::$_config_catalog = Config::getParam('modules->catalog');
		self::$_table_catalog = self::$_config_catalog['table'];		
	}
  
	public static function getCollectionCNI($id_catalog,$name,$id_input) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_catalog` = :id_catalog and `name` = :name and `id_input` = :id_input';

		if (($collection = DB::query($statement, array('id_catalog' => $id_catalog,'name' => $name,'id_input' => $id_input), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении характеристик из БД', E_USER_ERROR);
		}

		return $collection;
	
	}  
  
  
	public static function getCollectionsByIdCatalog($id_catalog) {

		$statement = 'SELECT * FROM '.self::$_table.' WHERE id_catalog = '.$id_catalog;
	
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении характеристик из БД', E_USER_ERROR);
		}

		return $collections;
	
	} 
	
	public static function getCollectionForSite($zaprosSQL) {

		$statement = $zaprosSQL; 
	
		if (($collections = DB::query($statement)) === FALSE) {

			trigger_error('Ошибка при получении данных из БД', E_USER_ERROR);

		}

		return $collections;

	}	 
  
	public static function addCharacteristics($data) {
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_catalog`, `name`, `value`, `id_input`)
					  VALUES
						(:id_catalog, :name, :value, :id_input)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении характеристик в БД!', E_USER_ERROR);
		}
	}
  
	public static function removeCollection($id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_catalog` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении характеристик из БД!', E_USER_ERROR);
		}

	}

}