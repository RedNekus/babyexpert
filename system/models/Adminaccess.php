<?php

class Adminaccess {
  private static $_config, $_table, $_content;

  public static function init() {
    self::$_config = Config::getParam('modules->adminaccess');
    self::$_table = self::$_config['table'];
  }
  
  public static function getAccessByID($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($adminaccess = DB::query($statement, array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении производителей из БД', E_USER_ERROR);
    }

    return $adminaccess;
  }
  
  public static function getAccess($order_name, $order, $limit) {

	$statement = 'SELECT * FROM `' . self::$_table . '` ORDER BY `'.$order_name.'` '.$order.' LIMIT ' . $limit;
	
    if (($makers = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении производителей из БД', E_USER_ERROR);
    }

    return $makers;
  } 
 
  public static function getTotalAccess() {
    $statement = 'SELECT count(*) as `count` FROM `' . self::$_table . '`';

    if (($count = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества производителей в БД!', E_USER_ERROR);
    }

    return $count;
  } 
  
  public static function searchAdmin($searchField, $searchString) {
  
		$statement = 'SELECT * FROM `'.self::$_table.'` WHERE `'.$searchField.'` LIKE "%'.$searchString.'%"';
		
		if (($makers = DB::query($statement)) === FALSE) {
		  trigger_error('Ошибка при выполнении поиска в БД', E_USER_ERROR);
    }

    return $makers;
  }

  public static function addAccess($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`name`, `active`, `prioritet`, `catalog_add`, `catalog_del`, `catalog_edit`, `catalog_review`, `characteristics_add`, `characteristics_del`, `characteristics_edit`, `characteristics_review`, `maker_add`, `maker_del`, `maker_edit`, `maker_review`, `question_add`, `question_del`, `question_edit`, `question_review`, `reviews_add`, `reviews_del`, `reviews_edit`, `reviews_review`, `news_add`, `news_del`, `news_edit`, `news_review`, `article_add`, `article_del`, `article_edit`, `article_review`, `akcii_add`, `akcii_del`, `akcii_edit`, `akcii_review`, `banners_add`, `banners_del`, `banners_edit`, `banners_review`, `pages_add`, `pages_del`, `pages_edit`, `pages_review`, `currency_add`, `currency_del`, `currency_edit`, `currency_review`, `adminusers_add`, `adminusers_del`, `adminusers_edit`, `adminusers_review`, `adminaccess_add`, `adminaccess_del`, `adminaccess_edit`, `adminaccess_review`, `registration_add`, `registration_del`, `registration_edit`, `registration_review`, `connection_add`, `connection_del`, `connection_edit`, `connection_review`, `stats_review`)
                  VALUES
                    (:name, :active, :prioritet, :catalog_add, :catalog_del, :catalog_edit, :catalog_review, :characteristics_add, :characteristics_del, :characteristics_edit, :characteristics_review, :maker_add, :maker_del, :maker_edit, :maker_review, :question_add, :question_del, :question_edit, :question_review, :reviews_add, :reviews_del, :reviews_edit, :reviews_review, :news_add, :news_del, :news_edit, :news_review, :article_add, :article_del, :article_edit, :article_review, :akcii_add, :akcii_del, :akcii_edit, :akcii_review, :banners_add, :banners_del, :banners_edit, :banners_review, :pages_add, :pages_del, :pages_edit, :pages_review, :currency_add, :currency_del, :currency_edit, :currency_review, :adminusers_add, :adminusers_del, :adminusers_edit, :adminusers_review, :adminaccess_add, :adminaccess_del, :adminaccess_edit, :adminaccess_review, :registration_add, :registration_del, :registration_edit, :registration_review,  :connection_add, :connection_del, :connection_edit, :connection_review, :stats_review)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении производителей в БД!', E_USER_ERROR);
    }
  }
  
  public static function updateAccess($data) {
    $statement = 'UPDATE `'.self::$_table.'`
					SET `active` = :active,
						`name` = :name,
						`prioritet` = :prioritet,
						`catalog_add` = :catalog_add,
						`catalog_del` = :catalog_del,
						`catalog_edit` = :catalog_edit,
						`catalog_review` = :catalog_review,
						`characteristics_add` = :characteristics_add,
						`characteristics_del` = :characteristics_del,
						`characteristics_edit` = :characteristics_edit,
						`characteristics_review` = :characteristics_review,	
						`maker_add` = :maker_add,
						`maker_del` = :maker_del,
						`maker_edit` = :maker_edit,
						`maker_review` = :maker_review,	
						`question_add` = :question_add,
						`question_del` = :question_del,
						`question_edit` = :question_edit,
						`question_review` = :question_review,
						`reviews_add` = :reviews_add,
						`reviews_del` = :reviews_del,
						`reviews_edit` = :reviews_edit,
						`reviews_review` = :reviews_review,
						`news_add` = :news_add,
						`news_del` = :news_del,
						`news_edit` = :news_edit,
						`news_review` = :news_review,
						`article_add` = :article_add,
						`article_del` = :article_del,
						`article_edit` = :article_edit,
						`article_review` = :article_review,
						`akcii_add` = :akcii_add,
						`akcii_del` = :akcii_del,
						`akcii_edit` = :akcii_edit,
						`akcii_review` = :akcii_review,	
						`banners_add` = :banners_add,
						`banners_del` = :banners_del,
						`banners_edit` = :banners_edit,
						`banners_review` = :banners_review,	
						`pages_add` = :pages_add,
						`pages_del` = :pages_del,
						`pages_edit` = :pages_edit,
						`pages_review` = :pages_review,
						`currency_add` = :currency_add,
						`currency_del` = :currency_del,
						`currency_edit` = :currency_edit,
						`currency_review` = :currency_review,				
						`adminusers_add` = :adminusers_add,
						`adminusers_del` = :adminusers_del,
						`adminusers_edit` = :adminusers_edit,
						`adminusers_review` = :adminusers_review,					
						`adminaccess_add` = :adminaccess_add,
						`adminaccess_del` = :adminaccess_del,
						`adminaccess_edit` = :adminaccess_edit,
						`adminaccess_review` = :adminaccess_review,		
						`registration_add` = :registration_add,
						`registration_del` = :registration_del,
						`registration_edit` = :registration_edit,
						`registration_review` = :registration_review,		
						`connection_add` = :connection_add,
						`connection_del` = :connection_del,
						`connection_edit` = :connection_edit,
						`connection_review` = :connection_review,						
						`stats_review` = :stats_review						
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания производителей в БД!', E_USER_ERROR);
    }
  }

  
	public static function removeAccess($id) {

		$statement = 'DELETE FROM `'.self::$_table.'`
					  WHERE `id` = :id;';

		$params = array ('id' => $id);

		if (DB::query($statement, $params) === FALSE) {
			trigger_error('Ошибка при удалении производителей из БД!', E_USER_ERROR);
		}
	
	}
  
}