<?php

class Brand {
	private static $_config, $_table;

	public static function init() {
		self::$_config = Config::getParam('modules->brand');
		self::$_table = self::$_config['table'];
	}
  
	public static function getCollectionByID($id) {
	
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении брендов из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
  
  
	public static function getCollections($valuerazdel,$order_name,$order,$limit) {
  
		$statement = 'SELECT * 
						FROM `' . self::$_table . '` 
						WHERE `id_catalog_tree` = '.$valuerazdel.' 
						ORDER BY `'.$order_name.'` '.$order.' 
						LIMIT ' . $limit;

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении брендов из БД', E_USER_ERROR);
		}

		return $collections;
	
	} 
  
	public static function getTotalCollections($valuerazdel) {
		
		$statement = 'SELECT count(*) as `count` FROM `'.self::$_table.'` WHERE `id_catalog_tree` = '.$valuerazdel;

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества брендов в БД!', E_USER_ERROR);
		}

		return $count;
		
	}  
	
	public static function getBrands($valuerazdel, $valuemaker) {
  
		$statement = 'SELECT * 
						FROM `' . self::$_table . '` 
						WHERE `id_catalog_tree` = '.$valuerazdel.' and `id_maker` = '.$valuemaker.'
						ORDER BY `id` DESC 
						LIMIT 1';

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении брендов из БД', E_USER_ERROR);
		}

		return $collections;
	
	}
  
	public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
		}

		return $collections;
		
	}
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последнего бренда из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
	
	public static function addbrand($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_catalog_tree`, `id_maker`, `name`, `title`, `keywords`, `description`, `short_description`)
					  VALUES
						(:id_catalog_tree, :id_maker, :name, :title, :keywords, :description, :short_description)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении брендов в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function updatebrand($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `id_catalog_tree` = :id_catalog_tree,
						  `id_maker` = :id_maker,
						  `name` = :name,
						  `title` = :title,
						  `keywords` = :keywords,
						  `description` = :description,
						  `short_description` = :short_description
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания брендов в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function removeCollection($id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении брендов из БД!', E_USER_ERROR);
		}

	}

}