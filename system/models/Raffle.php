<?php

class Raffle {
	private static $_config, $_table, $_content;

	public static function init() {
		self::$_config = Config::getParam('modules->raffle');
		self::$_table = self::$_config['table'];
	}
  
	public static function getRaffleByID($id) {
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($raffle = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении розыгрышей из БД', E_USER_ERROR);
		}

		return $raffle;
	}
  
	public static function getRaffleForSite($WHERE, $limit) {
  
		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE '.$WHERE.' ORDER by `timestamp` DESC LIMIT ' . $limit;

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении розыгрышей из БД', E_USER_ERROR);
		}

		return $collections;
	
	}
  
	public static function getRaffle($order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении розыгрышей из БД', E_USER_ERROR);
		}

		return $makers;
		
	} 
  
	public static function getTotalRaffle() {
		
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества розыгрышей в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
  
	public static function getRaffleWithDate($date_now) {

		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `timestamp` <= "'.$date_now.'" and `timestampend` >= "'.$date_now.'"';
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении розыгрышей из БД', E_USER_ERROR);
		}

		return $makers;
		
	}
	
	public static function getRaffleByPath($path) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `path` = :path';

		if (($collection = DB::query($statement, array('path' => $path), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении розыгрыша из БД', E_USER_ERROR);
		}

		return $collection;
		
	} 

  public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($makers = DB::query($statement)) === FALSE) {
		  trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
    }

    return $makers;
  }

  public static function addRaffle($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`active`, `name`, `video_url`, `ids_catalog`, `path`, `image`, `prioritet`, `short_description`, `title`, `keywords`, `description`, `timestamp`, `timestampend`, `timestamp2`, `timestampend2`, `timestamp3`, `timestampend3`, `timestamp4`, `timestampend4`)
                  VALUES
                    (:active, :name, :video_url, :ids_catalog, :path, :image, :prioritet, :short_description, :title, :keywords, :description, :timestamp, :timestampend, :timestamp2, :timestampend2, :timestamp3, :timestampend3, :timestamp4, :timestampend4)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении розыгрышей в БД!', E_USER_ERROR);
    }
  }
  
  public static function updateRaffle($data) {
    $statement = 'UPDATE `'.self::$_table.'`
					SET `active` = :active,
						`name` = :name,
						`video_url` = :video_url,
						`ids_catalog` = :ids_catalog,
						`path` = :path,
						`image` = :image,
						`prioritet` = :prioritet,
						`short_description` = :short_description,
						`title` = :title,
						`keywords` = :keywords,
						`description` = :description,
						`timestamp` = :timestamp,
						`timestampend` = :timestampend,
						`timestamp2` = :timestamp2,
						`timestampend2` = :timestampend2,
						`timestamp3` = :timestamp3,
						`timestampend3` = :timestampend3,
						`timestamp4` = :timestamp4,
						`timestampend4` = :timestampend4
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания розыгрышей в БД!', E_USER_ERROR);
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
  
  public static function removeRaffle($id) {
    $raffle = self::getRaffleByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $raffle['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении розыгрышей из БД!', E_USER_ERROR);
    }
    @unlink(ROOT.self::$_config['image']['small']['path'].$raffle['image']);
    @unlink(ROOT.self::$_config['image']['big']['path'].$raffle['image']);
  }
  
}