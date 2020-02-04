<?php

class Maker {
  private static $_config, $_table, $_table_import;

  public static function init() {
    self::$_config = Config::getParam('modules->maker');
    self::$_table = self::$_config['table'];
  }
  
  public static function getMakerByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($maker = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении производителей из БД', E_USER_ERROR);
    }

    return $maker;
  }
  
  public static function getMakers($order_name, $order, $limit) {

	$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
	
    if (($makers = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении производителей из БД', E_USER_ERROR);
    }

    return $makers;
  } 
  
  public static function getMakersForSite($where,$order_name, $order, $limit) {

	$statement = 'SELECT * FROM `' . self::$_table . '` WHERE '.$where.' ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
	
    if (($makers = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении производителей из БД', E_USER_ERROR);
    }

    return $makers;
  } 
  
  public static function getMakersByPath($path) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `path` = :path';

    if (($collection = DB::query($statement, array('path' => $path), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении производителей из БД', E_USER_ERROR);
    }

    return $collection;
  }
  
  public static function getTotalMakers() {
    $statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

    if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества производителей в БД!', E_USER_ERROR);
    }

    return $count;
  }
  
  public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($makers = DB::query($statement)) === FALSE) {
		  trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
    }

    return $makers;
  }
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последнего производителя из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
	
  public static function addMaker($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`active`, `name`, `namefull`, `path`, `country`, `short_description`, `title`, `keywords`, `description`)
                  VALUES
                    (:active, :name, :namefull, :path, :country, :short_description, :title, :keywords, :description)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении производителей в БД!', E_USER_ERROR);
    }
  }
  
  public static function updateMaker($data) {
    $statement = 'UPDATE `'.self::$_table.'`
					SET `active` = :active,
						`name` = :name,
						`namefull` = :namefull,
						`path` = :path,
						`country` = :country,
						`short_description` = :short_description,
						`title` = :title,
						`keywords` = :keywords,
						`description` = :description
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания производителей в БД!', E_USER_ERROR);
    }
  }
  
  public static function removeMaker($id) {
    $maker = self::getMakerByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $maker['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении производителей из БД!', E_USER_ERROR);
    }

  }

}