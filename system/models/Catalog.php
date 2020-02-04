<?php

class Catalog {

	private static $_config, $_table, $_config_cat, $_table_cat, $_config_adm, $_table_adm, $_config_maker, $_table_maker, $_bd;

	public static function init() {
		self::$_config = Config::getParam('modules->catalog');
		self::$_table = self::$_config['table'];
		
		self::$_config_cat = Config::getParam('modules->catalog_categories');
		self::$_table_cat = self::$_config_cat['table'];
		
		self::$_config_adm = Config::getParam('modules->adminusers_stats');
		self::$_table_adm = self::$_config_adm['table'];	
		
		self::$_config_maker = Config::getParam('modules->maker');
		self::$_table_maker = self::$_config_maker['table'];

		self::$_bd = Config::getParam('components->db->db_name');	

	}
  
	public static function getCollectionByID($id) {
		
		$statement = 'SELECT * 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog 
							WHERE `id` = :id';

		if (($collection = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

    return $collection;
  
	}
  
	public static function getCollections($valuerazdel, $order_name, $order, $limit) 
	{
		$statement = 'SELECT * 
						FROM '.self::$_table.' as t1
						JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog		
						WHERE `id_razdel0` = '.$valuerazdel.' or
							  `id_razdel1` = '.$valuerazdel.' or
							  `id_razdel2` = '.$valuerazdel.' or
							  `id_razdel3` = '.$valuerazdel.' or
							  `id_razdel4` = '.$valuerazdel.' or
							  `id_razdel5` = '.$valuerazdel.' 
						GROUP BY t1.id							  
						ORDER BY `'.$order_name.'` '.$order.' 
						LIMIT ' . $limit;
			
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collections;
	}  
	  
	public static function getCollectionsActive($valuerazdel, $order_name, $order, $limit) 
	{
		$statement = 'SELECT * 
						FROM '.self::$_table.' as t1
						JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog		
						WHERE (`id_razdel0` = '.$valuerazdel.' or
							  `id_razdel1` = '.$valuerazdel.' or
							  `id_razdel2` = '.$valuerazdel.' or
							  `id_razdel3` = '.$valuerazdel.' or
							  `id_razdel4` = '.$valuerazdel.' or
							  `id_razdel5` = '.$valuerazdel.') and
							  `active` = 1
						GROUP BY t1.id							  
						ORDER BY `'.$order_name.'` '.$order.' 
						LIMIT ' . $limit;
			
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collections;
	}  
	
	public static function getCollectionsTest() {
  
		$statement = 'SELECT t1.*, t2.name as name_maker, t3.name_lng  
						FROM '.self::$_table.' as t1
						JOIN np_maker as t2 ON t1.id_maker = t2.id
						JOIN np_catalog_language as t3 ON t1.id = t3.id_catalog_lng
						WHERE t3.id_language = 2
						ORDER by t2.name ASC';
	
		
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collections;
	
	}	
	
	public static function getCatalogTmpName() {
  
		$statement = 'SELECT * FROM `np_catalog_tmp` ORDER BY `np_catalog_tmp`.`name_baby` DESC ';
	
		
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collections;
	
	}	
	
	public static function getCatalogByRaffle($raffle) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `raffle` = '.$raffle.' ORDER BY `id` DESC ';
	
		
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

		return $collections;
	
	}	
  
	public static function getTotalCollections($valuerazdel) {

		$statement = 'SELECT count(DISTINCT t1.`id`) as `count` 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog						
							WHERE t2.`id_razdel0` = '.$valuerazdel.' or
								  t2.`id_razdel1` = '.$valuerazdel.' or
								  t2.`id_razdel2` = '.$valuerazdel.' or
								  t2.`id_razdel3` = '.$valuerazdel.' or
								  t2.`id_razdel4` = '.$valuerazdel.' or
								  t2.`id_razdel5` = '.$valuerazdel;


		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества продукции в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
  
	public static function getCollectionByUrl($path) {
    
		$statement = 'SELECT * 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog	
							WHERE `path` = :path
							ORDER BY t2.id_categories ASC';

		if (($collection = DB::query($statement, array('path' => $path), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

    return $collection;
	
	}  

	public static function getCollectionLikeUrl($path) {
    
		$statement = 'SELECT * 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog	
							WHERE `path` LIKE "%'.$path.'%"';

		if (($collection = DB::query($statement, array('path' => $path), TRUE)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

    return $collection;
	
	}	

	public static function getCollectionsStats($id,$paid,$action, $order_name, $order, $limit) {
    
		if ($order_name == "created") $order_name = "t2.".$order_name;
	
		$statement = 'SELECT DISTINCT t1.id, t1.name, t1.active, t1.paid, t1.path 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_adm.' as t2 ON t1.id = t2.id_catalog	
							WHERE t2.`id_adminusers` = '.$id.' and t1.`paid` = '.$paid.' and t2.`action` = '.$action.'
							ORDER BY '.$order_name.' '.$order.' 
							LIMIT '.$limit;

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

    return $collections;
	
	}
	
	public static function getTotalCollectionsStats($id,$paid,$action) {
	
		$statement = 'SELECT count(distinct t1.id) as `count` 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_adm.' as t2 ON t1.id = t2.id_catalog	
							WHERE t2.`id_adminusers` = '.$id.' and t1.`paid` = '.$paid.' and t2.`action` = '.$action;


		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества продукции в БД!', E_USER_ERROR);
		}

    return $count;
		
	}	

	public static function getCollectionsStatsByDate($id,$paid,$action,$date_ot,$date_do, $order_name, $order, $limit) {
		
		if ($order_name == "created") $order_name = "t2.".$order_name;
	    
		$statement = 'SELECT DISTINCT t1.id, t1.name, t1.active, t1.paid, t1.path 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_adm.' as t2 ON t1.id = t2.id_catalog	
							WHERE t2.`id_adminusers` = '.$id.' 
							  and t1.`paid` = '.$paid.' 
							  and t2.`action` = '.$action.' 
							  and '.$date_ot.' <= t2.`created` and t2.`created` <= '.$date_do.'
							ORDER BY '.$order_name.' '.$order.' 
							LIMIT '.$limit;

		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

    return $collections;
	
	}
	
	public static function getPodborAdmin($params, $limit, $variant) {
				
        $sql = array();
		
        if(isset( $params['tip_catalog']) && !empty($params['tip_catalog']) ) {
			$razdel = $params['tip_catalog'];
            $sql[] = ' (t2.`id_razdel0` = '.$razdel.' or
						t2.`id_razdel1` = '.$razdel.' or
						t2.`id_razdel2` = '.$razdel.' or
						t2.`id_razdel3` = '.$razdel.' or
						t2.`id_razdel4` = '.$razdel.' or
						t2.`id_razdel5` = '.$razdel.')';
						
        }        
		
		if(isset( $params['id_maker']) && !empty($params['id_maker']) ) {
			if (is_array($params['id_maker'])) {
				$impl = implode(",",$params['id_maker']);
				$sql[] = ' `id_maker` IN ('.$impl.')';	
				$params['id_maker'] = $impl;
			} else {
				$sql[] = ' t1.`id_maker` = '.$params['id_maker'];
			}
        }		
		
		if(isset( $params['id_tovar']) && !empty($params['id_tovar']) ) {
			$sql[] = ' t1.`id` = '.$params['id_tovar'];
        }	

        $sql[] ='t1.`active` <> 2';
        
        $st_sql = implode(' AND ', $sql);
						
		$sortON = explode('-',$params['sort']);	

		if (isset($st_sql)&& !empty($st_sql)) {
			if ($variant) {

			  $statement = 'SELECT t1.*, t2.*, t3.id as id_color, t3.name as name_color 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog
							LEFT JOIN np_catalog_colors as t3 ON t1.id = t3.id_catalog							
							WHERE '.$st_sql.' 
							GROUP BY t1.id	
							ORDER BY `'.$sortON[0].'` '.$sortON[1].'
							LIMIT '.$limit;		

				if (($data = DB::query($statement, $params, FALSE)) === FALSE) {
					trigger_error('Ошибка при получении данных из БД!', E_USER_ERROR);
				}
				
			} else {
			
			  $statement = 'SELECT count(t1.id) as `count` 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog
							LEFT JOIN np_catalog_colors as t3 ON t1.id = t3.id_catalog							
							WHERE '.$st_sql;

				if (($data = DB::query($statement, '', TRUE, 'count')) === FALSE) {
					trigger_error('Ошибка при получении количества продукции в БД!', E_USER_ERROR);
				}
		
			}
		}


        return $data;
	
	}	
	
	public static function getIdMakerByIdRazdel($id_razdel1) {
			
		$statement = 'SELECT DISTINCT t1.`id_maker`, t3.`name`, t3.`id`, t3.`path` 
						FROM `'.self::$_table.'` as t1 
						JOIN `'.self::$_table_cat.'` as t2 ON t2.id_catalog = t1.id
						JOIN `np_maker` as t3 ON t3.id = t1.`id_maker`
						WHERE (t2.`id_razdel1` = '.$id_razdel1.' or
							  t2.`id_razdel2` = '.$id_razdel1.') and
							  t3.`active` = 1
						ORDER by t3.`name`';

		if (($collection = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении разделов из БД', E_USER_ERROR);
		}

    return $collection;
	
	}
  	
	public static function getLastId() {

		$statement = 'SELECT * FROM `'.self::$_table.'` ORDER BY `id` DESC LIMIT 1';

		if (($collection = DB::query($statement, null, TRUE)) === FALSE) {

			trigger_error('Ошибка при получении информации о последнем товаре из БД!', E_USER_ERROR);

		}

		return $collection['id'];

	}
    
	public static function getCollectionsCurrency($where, $order_name, $order, $limit) {

		$statement = 'SELECT * 
						FROM '.self::$_table.' as t1
						JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog
						WHERE '.$where.'
						GROUP BY t1.id							  
						ORDER BY '.$order_name.' '.$order.' 
						LIMIT '.$limit;
	
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при получении продукции из БД', E_USER_ERROR);
		}

    return $collections;
	
	} 
  
	public static function getTotalCollectionsCurrency($where) {

		$statement = 'SELECT count(distinct id) as `count` 
						FROM '.self::$_table.' as t1
						JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog					
						WHERE '.$where;


		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества продукции в БД!', E_USER_ERROR);
		}

    return $count;
	
	}
		
	public static function getCatalogForSite($WHERE, $limit) {

		$statement = 'SELECT * FROM `' . self::$_table . '` WHERE '.$WHERE.' and active=1 ORDER by `prioritet` DESC LIMIT ' . $limit;

		if (($collections = DB::query($statement)) === FALSE) {

			trigger_error('Ошибка при получении дерева из БД', E_USER_ERROR);

		}

		return $collections;

	}	
	
	public static function getPodbor($params, $limit, $variant) {
				
        $sql = array();

		
        if(isset( $params['tip_catalog']) && !empty($params['tip_catalog']) ) {
			$razdel = $params['tip_catalog'];
            $sql[] = ' (t2.`id_razdel0` = '.$razdel.' or
						t2.`id_razdel1` = '.$razdel.' or
						t2.`id_razdel2` = '.$razdel.' or
						t2.`id_razdel3` = '.$razdel.' or
						t2.`id_razdel4` = '.$razdel.' or
						t2.`id_razdel5` = '.$razdel.')';
						
        }        
		
		if(isset( $params['id_maker']) && !empty($params['id_maker']) ) {
			if (is_array($params['id_maker'])) {
				$impl = implode(",",$params['id_maker']);
				$sql[] = ' t1.`id_maker` IN ('.$impl.')';	
				$params['id_maker'] = $impl;
			} else {
				$sql[] = ' t1.`id_maker` = '.$params['id_maker'];
			}
        }	
		
		if(isset( $params['price_ot']) && !empty($params['price_ot']) ) {

            $sql[] = ' t1.`cena_blr_site` > '.$params['price_ot'];
        }	
		
		if(isset( $params['price_do']) && !empty($params['price_do']) ) {

            $sql[] = ' t1.`cena_blr_site` < '.$params['price_do'];			
        }
				
		if(isset( $params['new']) && !empty($params['new']) ) {
            $sql[] = ' t1.`new` = 1';			
        }
			
		if(isset( $params['list_id']) && !empty($params['list_id']) ) {
            $sql[] = ' t1.`id` IN ('.$params['list_id'].')';			
        }
		
		if(isset( $params['filtr']) && $params['filtr']==false ) {
			return;			
        }		
		
        $sql[] ='t1.`active` = 1';
        
        $st_sql = implode(' AND ', $sql);
						
		$sortON = explode('-',$params['sort']);	
		$sort_field = 't1.`'.$sortON[0].'`';
		
		if ($params['sort']=='myprice-ASC' or $params['sort']=='myprice-DESC') {
			$where_sort = ' AND (t1.cena_blr > 0 or t1.cena > 0)';
			$sort_field = 'IF ( t1.cena_blr > 0, t1.cena_blr, t1.cena * t3.kurs)';
		}
		
		if (isset($st_sql)&& !empty($st_sql)) {
			if ($variant) {

			  $statement = 'SELECT t1.*, t2.*, t3.id as id_kurs, t3.kurs
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog 
							JOIN np_currency_tree as t3 ON t1.id_currency = t3.id 
							WHERE '.$st_sql.@$where_sort.' 
							GROUP BY t1.id 
							ORDER BY '.$sort_field.' '.$sortON[1].', status ASC
							LIMIT '.$limit;		
							
				if (($data = DB::query($statement, $params, FALSE)) === FALSE) {
					trigger_error('Ошибка при получении данных из БД!'.$statement, E_USER_ERROR);
				}

			} else {
			
			  $statement = 'SELECT count(distinct t1.id) as `count` 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_cat.' as t2 ON t1.id = t2.id_catalog 
							WHERE '.$st_sql.@$where_sort;

				if (($data = DB::query($statement, '', TRUE, 'count')) === FALSE) {
					trigger_error('Ошибка при получении количества продукции в БД!', E_USER_ERROR);
				}
		
			}
		}


        return $data;
    } 
	
	public static function search($searchString, $limit = false) {
		
		$statement = 'SELECT t1.*, concat(t2.name, " ", t1.name) as `name_maker` 
						FROM `'.self::$_table.'`  as t1 
						JOIN `'.self::$_table_maker.'` as t2 ON t2.id = t1.id_maker		
						WHERE concat(t2.name, " ", t1.name) LIKE "%'.$searchString.'%" and t1.`active` = 1
						GROUP BY t1.id';
		if ($limit) $statement .= ' LIMIT '.$limit;

		if (($collections = DB::query($statement, array('searchString' => $searchString))) === FALSE) {
		trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
		}

		return $collections;
		
	}
	
	public static function getTotalSearch($searchString) {

		$statement = 'SELECT count(DISTINCT t1.`id`) as `count` 
						FROM `'.self::$_table.'`  as t1 
						JOIN `'.self::$_table_maker.'` as t2 ON t2.id = t1.id_maker		
						WHERE concat(t2.name, " ", t1.name) LIKE "%'.$searchString.'%" and t1.`active` = 1';		
		
		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества продукции в БД!', E_USER_ERROR);
		}

		return $count;
		
	}
	
	public static function searchAdmin($searchField, $searchString, $limit) {
  
		if ($searchField == 'maker') {
			$statement = 'SELECT t1.*, t2.name as name_maker 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_maker.' as t2 ON t1.id_maker = t2.id 						
							WHERE t2.`name` LIKE "%'.$searchString.'%"
							LIMIT '.$limit;		
		} elseif ($searchField == 'id') {
			$statement = 'SELECT t1.*, t2.name as name_maker  
							FROM `'.self::$_table.'`  as t1 	
							JOIN '.self::$_table_maker.' as t2 ON t1.id_maker = t2.id 
							WHERE t1.`id` = '.$searchString.' 
							LIMIT '.$limit;			
		} else {
			$statement = 'SELECT t1.*, concat(t2.name, " ", t1.name) as `name_maker` 
							FROM `'.self::$_table.'`  as t1 
							JOIN `'.self::$_table_maker.'` as t2 ON t2.id = t1.id_maker		
							WHERE concat(t2.name, " ", t1.name) LIKE "%'.$searchString.'%" 
							GROUP BY t1.id
							LIMIT '.$limit;			
		}

		
		if (($collections = DB::query($statement)) === FALSE) {
			trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
		}

    return $collections;
	
	}

	public static function getTotalSearchAdmin($searchField, $searchString) {

		if ($searchField == 'maker') {
			$statement = 'SELECT count(DISTINCT t1.`id`) as `count`, t2.`name` as name_maker 
							FROM '.self::$_table.' as t1
							JOIN '.self::$_table_maker.' as t2 ON t1.id_maker = t2.id 						
							WHERE t2.`name` LIKE "%'.$searchString.'%"';		
		} else {
			$statement = 'SELECT count(DISTINCT `id`) as `count` FROM '.self::$_table.' WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';		
		}
		
		if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
			trigger_error('Ошибка при получении количества продукции в БД!', E_USER_ERROR);
		}

		return $count;
		
	}

	
	public static function addCollection($data) {
		$statement = 'INSERT INTO `'.self::$_table.'`
						(`prioritet`, `title`, `keywords`, `description`, `path`, `id_maker`, `name`, `cena`, `id_currency`, `cena_blr`, `new`, `hit`, `spec_pred`, `tovar_nedeli`, `expert`, `raffle`, `active`, `short_description`, `full_description`, `instructions`, `pohozhie`, `soput`, `podarok`, `id_prefix`, `id_char`, `status`, `video_url`)
					  VALUES
						(:prioritet, :title, :keywords, :description, :path, :id_maker, :name, :cena, :id_currency, :cena_blr, :new, :hit, :spec_pred, :tovar_nedeli, :expert, :raffle, :active, :short_description, :full_description, :instructions, :pohozhie, :soput, :podarok, :id_prefix, :id_char, :status, :video_url)';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при добавлении продукции в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateCollection($data) {
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `prioritet` = :prioritet,
						  `title` = :title,
						  `keywords` = :keywords,
						  `description` = :description,
						  `path` = :path,
						  `id_maker` = :id_maker,					  					  
						  `name` = :name,
						  `cena` = :cena,
						  `id_currency` = :id_currency,						  
						  `cena_blr` = :cena_blr,
						  `new` = :new,					  
						  `hit` = :hit,
						  `spec_pred` = :spec_pred,
						  `active` = :active,
						  `tovar_nedeli` = :tovar_nedeli,
						  `expert` = :expert,
						  `raffle` = :raffle,
						  `short_description` = :short_description,
						  `full_description` = :full_description,
						  `instructions` = :instructions,
						  `pohozhie` = :pohozhie,
						  `soput` = :soput,
						  `podarok` = :podarok,
						  `id_prefix` = :id_prefix,
						  `id_char` = :id_char,
						  `status` = :status,
						  `video_url` = :video_url
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания продукции в БД!', E_USER_ERROR);
		}
	
	}
  
	public static function updateCollectionFromTable($data) {
		
		$statement = 'UPDATE `'.self::$_table.'`
						SET `cena` = :cena,
							`cena_blr` = :cena_blr,
							`new` = :new,
							`hit` = :hit,
							`spec_pred` = :spec_pred,
							`active` = :active
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении описания производителей в БД!', E_USER_ERROR);
		}
		
	}
  
	public static function updateCollectionChar($data) {
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `id_char` = :id_char
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении в БД!', E_USER_ERROR);
		}
	
	}  
	
	public static function updateCollectionPaid($data) {
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `paid` = :paid
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении оплаты в БД!', E_USER_ERROR);
		}
	
	}	
	
	public static function updateCollectionRaffle($data) {
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `raffle` = :raffle
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении розыгрышей в БД!', E_USER_ERROR);
		}
	
	}	
	
	public static function clearCollectionRaffle($id) {
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `raffle` = 0
					  WHERE `raffle` = :id';

		if (DB::query($statement, array('id' => $id)) === FALSE) {
			trigger_error('Ошибка при очистке розыгрышей в БД!', E_USER_ERROR);
		}
	
	}	
	
	public static function updateCollectionNames($data) {
		$statement = 'UPDATE `np_catalog_language`
					  SET `name` = :name
					  WHERE `id_catalog_lng` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении оплаты в БД!', E_USER_ERROR);
		}
	
	}
	
	public static function updateCollectionCurrency($data) {
		$statement = 'UPDATE `'.self::$_table.'`
					  SET `id_currency` = :id_currency
					  WHERE `id` = :id';

		if (DB::query($statement, $data) === FALSE) {
			trigger_error('Ошибка при обновлении оплаты в БД!', E_USER_ERROR);
		}
	
	}
	
	public static function removeCollection($id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении продукции из БД!', E_USER_ERROR);
		}

	}	


}