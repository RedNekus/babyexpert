<?php

class Banners {
  private static $_config, $_table, $_content;

  public static function init() {
    self::$_config = Config::getParam('modules->banners');
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

		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `active`=1 and `id_categories`=0 ORDER BY `'.$order_name.'` '.$order;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении баннеров из БД', E_USER_ERROR);
		}

		return $makers;
		
	}
	
	public static function getBannersByIdCategories($id_categories, $order_name, $order) 
	{
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `active` = 1 and `id_categories`='.$id_categories.' ORDER BY `'.$order_name.'` '.$order;
		
		if (($makers = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении баннеров из БД', E_USER_ERROR);
		}

		return $makers;
	}
    
  public static function getTotalBanners() {
    $statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

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
                    (`active`, `name`, `path`, `image`, `prioritet`, `short_description`)
                  VALUES
                    (:active, :name, :path, :image, :prioritet, :short_description)';

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
						`prioritet` = :prioritet,
						`short_description` = :short_description
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания баннеров в БД!', E_USER_ERROR);
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
  
  public static function removeBanners($id) {
    $banners = self::getBannersByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $banners['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении производителей из БД!', E_USER_ERROR);
    }
    @unlink(ROOT.self::$_config['image']['small']['path'].$banners['image']);
    @unlink(ROOT.self::$_config['image']['big']['path'].$banners['image']);
  }
  
}