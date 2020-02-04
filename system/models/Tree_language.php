<?php

class Tree_language {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->catalog_tree');
		self::$_table = self::$_config['table_lng'];
	}
  
	public static function getTreeByID($id_lng) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id_lng` = :id_lng';

		if (($tree = DB::query($statement, array('id_lng' => $id_lng), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении акций из БД', E_USER_ERROR);
		}

		return $tree;
  
	}
  
	public static function getTreeByIdLng($id_tree_lng) {
		
		$statement = 'SELECT * FROM '.self::$_table.' WHERE `id_tree_lng` = :id_tree_lng and `id_language` = 2';

		if (($collection = DB::query($statement, array('id_tree_lng' => $id_tree_lng), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collection;
		
	}
 
	public static function addTree($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`id_tree_lng`, `id_language`, `name_lng`, `namefull_lng`, `short_description_lng`, `description_app_lng`, `title_lng`, `keywords_lng`, `description_lng`)
					  VALUES
						(:id_tree_lng, :id_language, :name_lng, :namefull_lng, :short_description_lng, :description_app_lng, :title_lng, :keywords_lng, :description_lng)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении дерева языка в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateTree($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `id_tree_lng` = :id_tree_lng,
							`id_language` = :id_language,
							`name_lng` = :name_lng,
							`namefull_lng` = :namefull_lng,							
							`short_description_lng` = :short_description_lng,
							`description_app_lng` = :description_app_lng,
							`title_lng` = :title_lng,
							`keywords_lng` = :keywords_lng,
							`description_lng` = :description_lng
					  WHERE `id_lng` = :id_lng';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания дерева языка в БД!', E_USER_ERROR);
		}
		
	}
   
	public static function removeTree($id_tree_lng) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id_tree_lng` = :id_tree_lng;';

		$params = array ('id_tree_lng' => $id_tree_lng);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении акций из БД!', E_USER_ERROR);
		}

	}
  
}