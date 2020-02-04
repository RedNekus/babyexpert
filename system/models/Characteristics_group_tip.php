<?php

class Characteristics_group_tip {
  private static $_config, $_table;

  public static function init() {
    self::$_config = Config::getParam('modules->characteristics_group_tip');
    self::$_table = self::$_config['table'];
  }
  
  public static function getTipByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении тип характеристики из БД', E_USER_ERROR);
    }

    return $collection;
  }
   
  public static function getTipsByIdCharacter($valuerazdel) {
  
	$statement = 'SELECT * FROM `' . self::$_table . '` WHERE `id_characteristics` = '.$valuerazdel;
	
    if (($collections = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении тип характеристик из БД', E_USER_ERROR);
    }

    return $collections;
  } 
  
  public static function getTips() {

    $statement = 'SELECT * FROM `' . self::$_table;

    if (($collections = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении тип характеристики из БД', E_USER_ERROR);
    }

    return $collections;
  } 

  public static function getTotalTips() {
    $statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

    if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества тип характеристики в БД!', E_USER_ERROR);
    }

    return $count;
  }


  public static function addTip($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`name`, `id_characteristics`)
                  VALUES
                    (:name, :id_characteristics)';
    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении тип характеристики в БД!', E_USER_ERROR);
    }
  }
  
  public static function updateTip($data) {
    $statement = 'UPDATE `'.self::$_table.'`
                  SET `name` = :name,
					  `id_characteristics` = :id_characteristics
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания тип характеристики в БД!', E_USER_ERROR);
    }
  }

  
  public static function removeTip($id) {
    $collection = self::getTipByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $collection['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении тип характеристики из БД!', E_USER_ERROR);
    }

  }

}