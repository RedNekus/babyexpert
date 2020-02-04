<?php

class Connection_tree {
	private static $_config, $_table;

	public static function init() {
		self::$_config = Config::getParam('modules->connection_tree');
		self::$_table = self::$_config['table'];
	}
  
	public static function getTreeByID($id) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
  
	public static function getTrees() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY id ASC';

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collections;
	
	} 

  public static function getTotalTrees() {
    $statement = 'SELECT count(*) as `count` FROM `'.self::$_table.'`';

    if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества дерева в БД!', E_USER_ERROR);
    }

    return $count;
  }


  public static function addTree($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`name`)
                  VALUES
                    (:name)';
    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении дерева в БД!', E_USER_ERROR);
    }
  }
  
  public static function updateTree($data) {
    $statement = 'UPDATE `'.self::$_table.'`
                  SET `name` = :name
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания дерева в БД!', E_USER_ERROR);
    }
  }

  
  public static function removeTree($id) {
    $collection = self::getTreeByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $collection['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении дерева из БД!', E_USER_ERROR);
    }

  }

}