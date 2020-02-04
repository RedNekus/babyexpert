<?php

class Catalog_3d {
  private static $_config, $_table;

  public static function init() {
    self::$_config = Config::getParam('modules->catalog_3d');
    self::$_table = self::$_config['table'];
  }
  
  public static function get3dFileByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении 3d файла из БД', E_USER_ERROR);
    }

    return $collection;
  }
   
  public static function get3dFileByIdCatalog($id_catalog) {
  
	$statement = 'SELECT * FROM `' . self::$_table . '` WHERE id_catalog = '.$id_catalog;

    if (($collections = DB::query($statement, array('id_catalog' => $id_catalog), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении 3d файла из БД', E_USER_ERROR);
    }

    return $collections;
  }  
  
  public static function add3dFile($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`id_catalog`, `swf`)
                  VALUES
                    (:id_catalog, :swf)';
    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении 3d файла в БД!', E_USER_ERROR);
    }
  }
  
  public static function update3dFile($data) {
    $statement = 'UPDATE `'.self::$_table.'`
                  SET `id_catalog` = :id_catalog,					  
					  `swf` = :swf				  
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания 3d файла в БД!', E_USER_ERROR);
    }
  }
  
  public static function remove3dFile($id) {
    $collection = self::get3dFileByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $collection['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении 3d файла из БД!', E_USER_ERROR);
    }
	
    @unlink(ROOT.self::$_config['image']['small']['path'].$collection['image']);
    @unlink(ROOT.self::$_config['image']['big']['path'].$collection['image']);
	
  }

}