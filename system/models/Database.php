<?php

class Database {
	
    public static function prepareValue($value){

		$value = addslashes(trim($value));
		$value = '"'.$value.'"';

        return $value;

    }
	
	public static function insert($table,$data) {

        $fields = array();
		$values = array();

        if (is_array($data)){

			foreach ($data as $field => $value){

				$value = self::prepareValue($value);
				
                $fields[] = "`$field`";
                $values[] = $value;

			}

            $fields = implode(', ', $fields);
            $values = implode(', ', $values);

			$sql = "INSERT INTO `$table` ($fields) VALUES ($values)";

			if (DB::query($sql) === FALSE) {
				trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
			}

		}
		
	}

	public static function update($table,$data,$where,$fv=false) {
	
		$set = array();

		foreach ($data as $field=>$value) {
		
			if ($fv==false) $value = self::prepareValue($value);
			
			if ($fv) $set[] = "`$field` = `$value`";
			else $set[] = "`$field` = $value";
			
		}

        $set = implode(', ', $set);

		$sql = "UPDATE `$table` SET $set WHERE $where";

		if (DB::query($sql) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}
		
	}
  
	public static function delete($table,$val,$row = 'id') {

		$sql = "DELETE FROM `$table` WHERE `$row` = :val";

		if (DB::query($sql, array ('val' => $val)) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

	}
	  
	public static function searchAdmin($table, $searchField, $searchOper, $searchString, $t_where = '') {
		  
		$opts = array(
			'eq'=>'=', 			//равно
			'ne'=>'<>',			//не равно
			'lt'=>'<', 			//меньше
			'le'=>'<=',			//меньше или равно
			'gt'=>'>', 			//больше
			'ge'=>'>=',			//больше или равно
			'bw'=>'LIKE', 		//begins with
			'bn'=>'NOT LIKE', 	//doesn't begin with
			'in'=>'LIKE', 		//is in
			'ni'=>'NOT LIKE', 	//is not in
			'ew'=>'LIKE', 		//ends with
			'en'=>'NOT LIKE', 	//doesn't end with
			'cn'=>'LIKE', 		//contains
			'nc'=>'NOT LIKE'  	//doesn't contain
		);
  
		if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
		if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
		if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		if(!is_numeric($searchString)) $searchString = "'".$searchString."'";
		
		$where =  "WHERE $searchField ".$opts[$searchOper]." $searchString ";  
  
		$sql = "SELECT * FROM $table $where $t_where";

		if (($items = DB::query($sql)) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

		return $items;
		
	}	
 
	public static function getRow($table,$val,$row = 'id',$lng=0) {
	
		if ($lng != 0) $and = " and `id_language` = $lng";
		$sql = "SELECT * FROM `$table` WHERE `$row` = :val".@$and;

		if (($item = DB::query($sql, array('val' => $val), TRUE)) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

		return $item;
		
	}
	
	public static function getRows($table, $order_name='id', $order='asc', $limit=false, $where = '1', $group='', $fields='*') {//Добавлен параметр $fields 04.02.2020
        
		if (!empty($group)) { $group = " GROUP BY {$group}"; }	
	
		$sql = "SELECT $fields FROM $table WHERE $where $group ORDER BY $order_name $order";

        if ($limit) { $sql .= " LIMIT {$limit}"; }
		
		if (($items = DB::query($sql)) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

		return $items;
	
	} 
 	
	public static function getSQL($sql) {

		if (($items = DB::query($sql)) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

		return $items;
	
	} 
    	
	public static function getCountSQL($sql) {

		if (($count = DB::query($sql, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

		return $count;
	
	} 
 
	public static function getCount($table, $where = '1') {
	  
		$sql = "SELECT count(1) as `count` FROM $table WHERE $where";

		if (($count = DB::query($sql, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

		return $count;
	
	}	

//============================================================================//
//============================================================================//

	/**
	 * Возвращает одно поле из таблицы в базе
	 *
	 * @param string $table
	 * @param string $where
	 * @param string $field
	 * @return mixed
	 */
	public static function getField($table, $val, $row = 'id', $field = 'name'){
		$row = self::getRow($table, $val, $row);
		return @$row[$field];
	}

	public static function getFields($table, $where, $fields='*', $order=''){
		$row = self::getRow($table, $where, $fields, $order);
		return $row;
	}


    public static function dropTable($table){

        $sql = "DROP TABLE $table";

		if ((DB::query($sql)) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

    }
	
	public static function getLastId($table) {

		$sql = "SELECT * FROM `$table` ORDER BY `id` DESC LIMIT 1";

		if (($item = DB::query($sql, null, TRUE)) === FALSE) {
			trigger_error('Ошибка  !'.$sql, E_USER_ERROR);
		}

		return $item['id'];

	}	

	public static function uploadImage($image, $config, $filename = '') {
		
		Load::library('class.upload/class.upload.php');
		$upload = new upload($image, 'ru_RU');

		if (!$upload->uploaded) {
		   return array('error' => $upload->error);
		}
		
		if (empty($filename)) $filename = $upload->file_src_name_body;
		
		$image_name = create_file_name($filename);

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
		$upload->image_ratio_y = true;
		$upload->image_ratio_fill = 'R';
		$upload->image_x = $config['small']['width'];	
		$upload->image_background_color = '#FFFFFF';

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
  	
}