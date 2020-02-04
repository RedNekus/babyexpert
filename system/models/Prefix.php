<?php

class Prefix {
	private static $_config, $_table;

	public static function init() {
		self::$_config = Config::getParam('modules->prefix');
		self::$_table = self::$_config['table'];
	}
  
	public static function getPrefixByID($id) {
		
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

		if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
		  trigger_error('Ошибка при получении префикса из БД', E_USER_ERROR);
		}

		return $collection;
	}
   
	public static function getPrefixByIdCatalog($id_tree) {
  
		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE id_tree = '.$id_tree.' ORDER by `baza` DESC, `name` ASC';

		if (($collections = DB::query($statement)) === FALSE) {
		  trigger_error('Ошибка при получении изображения из БД', E_USER_ERROR);
		}

		return $collections;
	
	}  
  
	public static function getTotalPrefixByIdCatalog($id_tree) {
		
		$statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '` WHERE id_tree = '.$id_tree;

		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества изображениий в БД!', E_USER_ERROR);
		}

		return $count;
	
	}   
  
  public static function addPrefix($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`name`, `baza`, `id_tree`)
                  VALUES
                    (:name, :baza, :id_tree)';
    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении префикса в БД!', E_USER_ERROR);
    }
  }
  
  public static function updatePrefix($data) {
    $statement = 'UPDATE `'.self::$_table.'`
                  SET `name` = :name,
					  `id_tree` = :id_tree,
					  `baza` = :baza					  
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания префикса в БД!', E_USER_ERROR);
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
  
  
  public static function removePrefix($id) {
    $collection = self::getPrefixByID($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id;';

    $params = array ('id' => $collection['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении префикса из БД!', E_USER_ERROR);
    }
	
    @unlink(ROOT.self::$_config['image']['small']['path'].$collection['image']);
    @unlink(ROOT.self::$_config['image']['big']['path'].$collection['image']);
	
  }

}