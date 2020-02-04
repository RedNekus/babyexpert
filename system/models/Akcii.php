<?php

class Akcii {
  private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->akcii');
		self::$_table = self::$_config['table'];
	}
  
	public static function getAkciiByID($id) {
	
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($akcii = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении акций из БД', E_USER_ERROR);
		}

		return $akcii;
		
	}
  
	public static function getAkcii($order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении акций из БД', E_USER_ERROR);
		}

		return $makers;
	
	} 
 
	public static function getTotalAkcii() {
	
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества акций в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
   
	public static function getAkciiByPath($path) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `path` = :path';

		if (($collection = DB::query($statement, array('path' => $path), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении новости из БД', E_USER_ERROR);
		}

		return $collection;
		
	} 
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последней акции из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
	
	public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
		}

		return $makers;
	
	}

	public static function addAkcii($data) {
  
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`active`, `name`, `namefull`, `path`, `image`, `prioritet`, `short_description`, `title`, `keywords`, `description`, `timestamp`)
					  VALUES
						(:active, :name, :namefull, :path, :image, :prioritet, :short_description, :title, :keywords, :description, :timestamp)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении акций в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function updateAkcii($data) {
	
		$statement = 'UPDATE `'.self::$_table.'`
						SET `active` = :active,
							`name` = :name,
							`namefull` = :namefull,
							`path` = :path,
							`image` = :image,
							`prioritet` = :prioritet,
							`short_description` = :short_description,
							`title` = :title,
							`keywords` = :keywords,
							`description` = :description,
							`timestamp` = :timestamp
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания акций в БД!', E_USER_ERROR);
		}
		
	}
 
	public static function uploadImage($image, $config) {
		Load::library('class.upload/class.upload.php');
		$upload = new upload($image, 'ru_RU');

		if (!$upload->uploaded) {
			return array('error' => $upload->error);
		}

		$image_name = create_file_name($upload->file_src_name_body);

		$upload->file_new_name_body = $image_name;
		$upload->allowed = array('image/*');
		$upload->image_min_width = $config['small']['width'];
		$upload->image_min_height = $config['small']['height'];

		if ($upload->image_src_x > $config['big']['width']) {
			$upload->image_resize = true;
			$upload->image_ratio_y = true;
			$upload->image_x = $config['big']['width'];
		}

		$upload->process(ROOT.$config['big']['path']);

		if (!$upload->processed) {
			return array('error' => $upload->error);
		}

		$upload->file_new_name_body = $image_name;
		$upload->image_resize = true;
		$upload->image_ratio_crop = true;
		$upload->image_x = $config['small']['width'];
		$upload->image_y = $config['small']['height'];

		$upload->process(ROOT.$config['small']['path']);

		if (!$upload->processed) {
			return array('error' => $upload->error);
		}

		$upload->clean();

		return array (
		  'name' => $upload->file_dst_name,
		  'error' => FALSE
		);
		
	}
  
	public static function removeAkcii($id) {
	
		$akcii = self::getAkciiByID($id);

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $akcii['id']);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении акций из БД!', E_USER_ERROR);
		}
		@unlink(ROOT.self::$_config['image']['small']['path'].$akcii['image']);
		@unlink(ROOT.self::$_config['image']['big']['path'].$akcii['image']);
	
	}
  
}