<?php

class Catalog_category {
  private static $_config, $_table;

  public static function init() {
    self::$_config = Config::getParam('modules->catalog_categories');
    self::$_table = self::$_config['table'];
	
  }
  
  public static function getCollectionByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении категории из БД', E_USER_ERROR);
    }

    return $collection;
  }
  
  public static function getCollectionByUrl($path) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `path` = :path';

    if (($collection = DB::query($statement, array('path' => $path), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении категории из БД', E_USER_ERROR);
    }

    return $collection;
  }
  
  public static function getNextId() {
    $statement = 'SELECT "constant", auto_increment FROM information_schema.tables where table_name = "'.self::$_table.'"';
  
    if (($collection = DB::query($statement, array(), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении категории из БД', E_USER_ERROR);
    }

    return $collection;
  }
  
  public static function getCollections($valuerazdel) {

		$statement = 'SELECT * 
						FROM '.self::$_table.'
						WHERE id_catalog = '.$valuerazdel.'
						ORDER BY id_categories ASC';
	
    if (($collections = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении категории из БД', E_USER_ERROR);
    }

    return $collections;
  } 
  
  public static function getTotalCollections($razdel) {
	if (@$razdel) {
    $statement = 'SELECT count(*) as `count` 
						FROM '.self::$_table.' as t1
						JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog						
						WHERE `id_razdel0` = '.$razdel.' or
							  `id_razdel1` = '.$razdel.' or
							  `id_razdel2` = '.$razdel.' or
							  `id_razdel3` = '.$razdel.' or
							  `id_razdel4` = '.$razdel.' or
							  `id_razdel5` = '.$razdel;
	} else {
	$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';
	}

    if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества категории в БД!', E_USER_ERROR);
    }

    return $count;
  }
  
	public static function getCatalogForSite($WHERE, $limit) {
  
		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE '.$WHERE.' LIMIT ' . $limit;

		if (($collections = DB::query($statement)) === FALSE) {
		  trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collections;
	}

	public static function addCollection($data) {
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_catalog`, `id_razdel0`, `id_razdel1`, `id_razdel2`, `id_razdel3`, `id_razdel4`, `id_razdel5`)
					  VALUES
						(:id_catalog, :id_razdel0, :id_razdel1, :id_razdel2, :id_razdel3, :id_razdel4, :id_razdel5)';

		if (DB::query($statement, $data) === FALSE) {
		  trigger_error('Ошибка при добавлении категории в БД!', E_USER_ERROR);
		}
	}

	public static function clearCategoriesById($id_catalog) {

		$statement = 'DELETE FROM `'.self::$_table.'` WHERE id_catalog = '.$id_catalog;
    
		$params = array ('id_catalog' => $id_catalog);

		if (DB::query($statement, $params) === FALSE) {
		  trigger_error('Ошибка при удалении категории из БД!', E_USER_ERROR);
		}	  
	}

}