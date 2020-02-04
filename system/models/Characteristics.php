<?php

class Characteristics {
  private static $_config, $_table;

  public static function init() {
    self::$_config = Config::getParam('modules->characteristics');
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
  
	if (@$valuerazdel) {
		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE `id_groupe` = '.$valuerazdel.' ORDER by prioritet_filtra DESC';
	} 
	
    if (($collections = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении характеристик из БД', E_USER_ERROR);
    }

    return $collections;
  } 

  public static function getCollectionsSite($valuerazdel) {
  
	if (@$valuerazdel) {
		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE `id_groupe` = '.$valuerazdel.' and `filtr` = 1 ORDER by prioritet_filtra DESC';
	} 
	
    if (($collections = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении характеристик из БД', E_USER_ERROR);
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
	
  public static function getCollectionsByShowCatalog($valuerazdel) {
  
	if (@$valuerazdel) {
		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE `id_groupe` = '.$valuerazdel.' and `show_catalog` = 1 ORDER by prioritet_filtra DESC';
	} 
	
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

  public static function addCharacteristics($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`name`, `prefix`, `prioritet`, `sufix`, `prioritet_filtra`, `id_groupe`, `show_catalog`, `active`, `tip`, `filtr`, `tip_search`, `filtr_toolbar`, `valcharacter`)
                  VALUES
                    (:name, :prefix, :prioritet, :sufix, :prioritet_filtra, :id_groupe, :show_catalog, :active, :tip, :filtr, :tip_search, :filtr_toolbar, :valcharacter)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении характеристик в БД!', E_USER_ERROR);
    }
  }
  
  public static function updateCharacteristics($data) {
    $statement = 'UPDATE `'.self::$_table.'`
                  SET `name` = :name,
					  `prefix` = :prefix,
					  `prioritet` = :prioritet,	  
				      `sufix` = :sufix,			      
				      `prioritet_filtra` = :prioritet_filtra,
					  `id_groupe` = :id_groupe,					  					                       
                      `show_catalog` = :show_catalog,					  
					  `active` = :active,
                      `tip` = :tip,
					  `filtr` = :filtr,
					  `tip_search` = :tip_search,
					  `filtr_toolbar` = :filtr_toolbar,
					  `valcharacter` = :valcharacter
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания характеристик в БД!', E_USER_ERROR);
    }
  }
  
  public static function removeCharacteristics($id) {
    $collection = self::getCollectionByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $collection['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении характеристик из БД!', E_USER_ERROR);
    }

  }

}