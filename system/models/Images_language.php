<?php

class Images_language {
  private static $_config, $_table;

  public static function init() {
    self::$_config = Config::getParam('modules->images_language');
    self::$_table = self::$_config['table'];
  }
  
  public static function getImagesByID($id_lng) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

    if (($collection = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении изображения из БД', E_USER_ERROR);
    }

    return $collection;
  }
  
  public static function getImagesItem($id_catalog_lng) {
    $statement = 'SELECT * FROM '.self::$_table.' WHERE `id_catalog_lng` = :id_catalog_lng and `id_language` = 2';

    if (($collection = DB::query($statement, array('id_catalog_lng' => $id_catalog_lng), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
    }

    return $collection;
  }  
  
  public static function addImages($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`id_catalog_lng`, `id_language`, `description_lng`)
                  VALUES
                    (:id_catalog_lng, :id_language, :description_lng)';
    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении изображения в БД! ', E_USER_ERROR);
    }
  }
  
  public static function updateImages($data) {
    $statement = 'UPDATE `'.self::$_table.'`
                  SET `id_catalog_lng` = :id_catalog_lng,					  
					  `id_language` = :id_language,
					  `description_lng` = :description_lng					  
                  WHERE `id_lng` = :id_lng';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания изображения в БД!', E_USER_ERROR);
    }
  }
 
  
  public static function removeImages($id_lng) {
    $collection = self::getImagesByID($id_lng);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id_lng` = :id_lng;';

    $params = array ('id_lng' => $collection['id_lng']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении изображения из БД!', E_USER_ERROR);
    }
	
  }

}