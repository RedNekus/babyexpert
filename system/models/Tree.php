<?php

class Tree {
	private static $_config, $_table;

	public static function init() {
		self::$_config = Config::getParam('modules->catalog_tree');
		self::$_table = self::$_config['table'];
	}
  
	public static function getTreeByID($id) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 	
	public static function getTreeByChar($characteristic) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `characteristic` = :characteristic';

		if (($collection = DB::query($statement, array('characteristic' => $characteristic), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
   
	public static function getTreeNameByCharacteristics($characteristic) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `characteristic` = :characteristic';

		if (($collection = DB::query($statement, array('characteristic' => $characteristic), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collection['name'];
		
	}
  
	public static function getTreeByPath($path) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `path` = :path';

		if (($collection = DB::query($statement, array('path' => $path), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
  
	public static function getNextId() {
		
		$statement = 'SELECT "constant", auto_increment FROM information_schema.tables where table_name = "'.self::$_table.'"';
	  
		if (($collection = DB::query($statement, array(), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collection;
  
	}
  
	public static function getTrees() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER by prioritet DESC, name ASC';

		if (($collections = DB::query($statement)) === FALSE) {
		  trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collections;
	
	} 
  
	public static function getTotalTrees() {
		
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества дерева в БД!', E_USER_ERROR);
		}

		return $count;
  
	}
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последнем товаре из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
    
	public static function getTreesPath($child, $level) {

		$firstId = 1;
		if ($level == 1) {
			$statement = 1;
		}	
		if ($level == 2) {
			$statement = 'SELECT t1.id AS lvl1, t2.id as lvl2
							FROM `' . self::$_table . '` AS t1
								LEFT JOIN `' . self::$_table . '` AS t2 ON t2.pid = t1.id
							WHERE t1.id = '.$firstId.' AND t2.id = '.$child;
		}
		if ($level == 3) {
			$statement = 'SELECT t1.id AS lvl1, t2.id as lvl2, t3.id as lvl3
							FROM `' . self::$_table . '` AS t1
								LEFT JOIN `' . self::$_table . '` AS t2 ON t2.pid = t1.id
								LEFT JOIN `' . self::$_table . '` AS t3 ON t3.pid = t2.id
							WHERE t1.id = '.$firstId.' AND t3.id = '.$child;
		}
		if ($level == 4) {
			$statement = 'SELECT t1.id AS lvl1, t2.id as lvl2, t3.id as lvl3, t4.id as lvl4
							FROM `' . self::$_table . '` AS t1
								LEFT JOIN `' . self::$_table . '` AS t2 ON t2.pid = t1.id
								LEFT JOIN `' . self::$_table . '` AS t3 ON t3.pid = t2.id
								LEFT JOIN `' . self::$_table . '` AS t4 ON t4.pid = t3.id
							WHERE t1.id = '.$firstId.' AND t4.id = '.$child;
		}	
		if ($level == 5) {
			$statement = 'SELECT t1.id AS lvl1, t2.id as lvl2, t3.id as lvl3, t4.id as lvl4, t5.id as lvl5
							FROM `' . self::$_table . '` AS t1
								LEFT JOIN `' . self::$_table . '` AS t2 ON t2.pid = t1.id
								LEFT JOIN `' . self::$_table . '` AS t3 ON t3.pid = t2.id
								LEFT JOIN `' . self::$_table . '` AS t4 ON t4.pid = t3.id
								LEFT JOIN `' . self::$_table . '` AS t5 ON t5.pid = t4.id
							WHERE t1.id = '.$firstId.' AND t5.id = '.$child;
		}
		if ($level == 6) {
			$statement = 'SELECT t1.id AS lvl1, t2.id as lvl2, t3.id as lvl3, t4.id as lvl4, t5.id as lvl5, t6.id as lvl6
							FROM `' . self::$_table . '` AS t1
								LEFT JOIN `' . self::$_table . '` AS t2 ON t2.pid = t1.id
								LEFT JOIN `' . self::$_table . '` AS t3 ON t3.pid = t2.id
								LEFT JOIN `' . self::$_table . '` AS t4 ON t4.pid = t3.id
								LEFT JOIN `' . self::$_table . '` AS t5 ON t5.pid = t4.id
								LEFT JOIN `' . self::$_table . '` AS t6 ON t6.pid = t5.id
							WHERE t1.id = '.$firstId.' AND t6.id = '.$child;
		}
		
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collections;
  
	}  
 
	public static function getTreesForSite($WHERE) {
  
		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE '.$WHERE.' ORDER BY prioritet DESC, name ASC';

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collections;
  
	}  
  
	public static function getLastTree() {

		$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY id DESC LIMIT 1';

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);
		}

		return $collections;
	
	} 

	public static function addTree($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`name`, `pid`, `active`, `fullname`, `path`, `prioritet`, `cost_dostavka`, `characteristic`, `short_description`, `description_app`, `title`, `keywords`, `description`, `show_opt`, `show_banner`)
					  VALUES
						(:name, :pid, :active, :fullname, :path, :prioritet, :cost_dostavka, :characteristic, :short_description, :description_app, :title, :keywords, :description, :show_opt, :show_banner)';
		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении дерева в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function updateTree($data) {
  
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `name` = :name,
						  `pid` = :pid,					  
						  `active` = :active,
						  `fullname` = :fullname,					  
						  `path` = :path,
						  `prioritet` = :prioritet,
						  `cost_dostavka` = :cost_dostavka,
						  `characteristic` = :characteristic,
						  `short_description` = :short_description,					  
						  `description_app` = :description_app,					  
						  `title` = :title,
						  `keywords` = :keywords,
						  `description` = :description,
						  `show_opt` = :show_opt,
						  `show_banner` = :show_banner
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания дерева в БД!', E_USER_ERROR);
		}
		
	}

  
	public static function removeTree($id) {
		
		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении дерева из БД!', E_USER_ERROR);
		}

	}

}