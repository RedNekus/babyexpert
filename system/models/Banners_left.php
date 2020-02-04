<?php

class Banners_left {
  private static $_config, $_table, $_content, $_banner_name;

  public static function init() {
    self::$_config = Config::getParam('modules->banners_left');
    self::$_table = self::$_config['table'];
  }
  
  public static function getBannersByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($banners = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении баннеров из БД', E_USER_ERROR);
    }

    return $banners;
  }
  
	public static function getBanners($order_name, $order, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении баннеров из БД', E_USER_ERROR);
		}

		return $makers;
	
	} 
  
	public static function getBannersActive($order_name, $order) {

		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `active`=1 ORDER BY `'.$order_name.'` '.$order;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении баннеров из БД', E_USER_ERROR);
		}

		return $makers;
		
	}  
  
	public static function getTotalBanners() {
		
		$statement = 'SELECT count(*) as `count` FROM `'.self::$_table.'`';

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества баннеров в БД!', E_USER_ERROR);
		}

		return $count;
	
	}
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последнем баннере из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
	
	public static function addBanners($data) {
		
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`active`, `name`, `path`, `image`, `prioritet`)
					  VALUES
						(:active, :name, :path, :image, :prioritet)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении баннеров в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function updateBanners($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `active` = :active,
							`name` = :name,
							`path` = :path,
							`image` = :image,
							`prioritet` = :prioritet
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания баннеров в БД!', E_USER_ERROR);
		}
		
	}
 
	public static function uploadImage($image) {
		
		Load::library('class.upload/class.upload.php');

		$upload = new upload($image, 'ru_RU');

		if (!$upload->uploaded) {
			return array('error' => $upload->error);
		}

		$image_name = create_file_name($upload->file_src_name_body);
		
		$upload->file_new_name_body = $image_name;
		$upload->allowed = 'image/*';
		$upload->image_max_width = self::$_config['image']['width'];
		$upload->image_max_height = self::$_config['image']['height'];

		$upload->process(ROOT.self::$_config['image']['path']);

		if (!$upload->processed) {
			return array('error' => $upload->error);
		}

		$upload->clean();

		return array (
			'name' => $upload->file_dst_name,
			'error' => FALSE
		);
  
	}
  
  public static function removeBanners($id) {
    $banners = self::getBannersByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $banners['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении производителей из БД!', E_USER_ERROR);
    }
    @unlink(ROOT.self::$_config['image']['path'].$banners['image']);
  }
  
}