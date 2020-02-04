<?php

class Catalog_language {
  private static $_config, $_table;

  public static function init() {
    self::$_config = Config::getParam('modules->catalog');
    self::$_table = self::$_config['table_lng'];
	
  }
  
  public static function getCollectionByID($id_lng) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

    if (($collection = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
    }

    return $collection;
  }
  
  public static function getCollectionItem($id_catalog_lng) {
    $statement = 'SELECT * FROM '.self::$_table.' WHERE `id_catalog_lng` = :id_catalog_lng and `id_language` = 2';

    if (($collection = DB::query($statement, array('id_catalog_lng' => $id_catalog_lng), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
    }

    return $collection;
  }

  public static function addCollection($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`id_catalog_lng`, `id_language`, `name_lng`, `title_lng`, `keywords_lng`, `description_lng`, `short_description_lng`, `full_description_lng`, `instructions_lng`)
                  VALUES
                    (:id_catalog_lng, :id_language, :name_lng, :title_lng, :keywords_lng, :description_lng, :short_description_lng, :full_description_lng, :instructions_lng)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении продукции в БД!', E_USER_ERROR);
    }
  }
  
  public static function updateCollection($data) {
    $statement = 'UPDATE `'.self::$_table.'`
                  SET `id_catalog_lng` = :id_catalog_lng,
					  `title_lng` = :title_lng,
				      `keywords_lng` = :keywords_lng,
				      `description_lng` = :description_lng,
				      `id_language` = :id_language,				  					  
                      `name_lng` = :name_lng,
                      `short_description_lng` = :short_description_lng,
					  `full_description_lng` = :full_description_lng,
					  `instructions_lng` = :instructions_lng
                  WHERE `id_lng` = :id_lng';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания продукции в БД!', E_USER_ERROR);
    }
  }
  
  public static function removeCollection($id_lng) {
    $collection = self::getCollectionByID($id_lng);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id_lng` = :id_lng;';

    $params = array ('id_lng' => $collection['id_lng']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении продукции из БД!', E_USER_ERROR);
    }

  }

}