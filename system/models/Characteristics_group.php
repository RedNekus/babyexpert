<?php

class Characteristics_group {
  private static $_config, $_table;

  public static function init() {
    self::$_config = Config::getParam('modules->characteristics_group');
    self::$_table = self::$_config['table'];
  }
  
  public static function getCollectionByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении характеристик из БД', E_USER_ERROR);
    }

    return $collection;
  }

  public static function getNextId() {
    $statement = 'SELECT "constant", auto_increment FROM information_schema.tables where table_name = "'.self::$_table.'"';
  
    if (($collection = DB::query($statement, array(), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении характеристик из БД', E_USER_ERROR);
    }

    return $collection;
  }
  
  public static function getCollections($valuerazdel) {
  
	$statement = 'SELECT * FROM `' . self::$_table . '` WHERE `id_tree` = '.$valuerazdel.' ORDER by prioritet DESC';
	
    if (($collections = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении характеристик из БД', E_USER_ERROR);
    }

    return $collections;
  } 
 
  public static function getTotalCollections() {
    $statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

    if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества характеристик в БД!', E_USER_ERROR);
    }

    return $count;
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

			trigger_error('Ошибка при получении информации о последней акции из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}

  public static function addCollection($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`prioritet`, `id_tree`, `name`, `name_vision`, `active`)
                  VALUES
                    (:prioritet, :id_tree, :name, :name_vision, :active)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении характеристик в БД!', E_USER_ERROR);
    }
  }
  
  public static function updateCollection($data) {
    $statement = 'UPDATE `'.self::$_table.'`
                  SET `prioritet` = :prioritet,					  
					  `id_tree` = :id_tree,					  
                      `name` = :name,
                      `name_vision` = :name_vision,
					  `active` = :active
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания характеристик в БД!', E_USER_ERROR);
    }
  }
  
  public static function removeCollection($id) {
    $collection = self::getCollectionByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $collection['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении характеристик из БД!', E_USER_ERROR);
    }

        @unlink(ROOT.$this->_config['image']['small']['path'].$collection['image']);
        @unlink(ROOT.$this->_config['image']['big']['path'].$collection['image']);
  }

}