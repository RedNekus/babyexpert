<?php

class Reviews {
  private static $_table;

  public static function init() {
    self::$_table = Config::getParam('modules->reviews->table');
  }

  public static function getReview($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($review = DB::query($statement, Array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении данных из БД!', E_USER_ERROR);
    }

    return $review;
  }

  public static function getReviewsForSite($id) {
    $statement = 'SELECT *
                  FROM `'.self::$_table.'` WHERE id_catalog='.$id.' AND active=1 ORDER BY `id` DESC';

    if (($reviews = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении отзывов из БД!', E_USER_ERROR);
    }

    return $reviews;
  }
  
  public static function getReviews($order_name, $order, $limit) {
    $statement = 'SELECT *
                  FROM `'.self::$_table.'` 
                  ORDER BY `'.$order_name.'` '.$order.' 
				  LIMIT ' . $limit;

    if (($reviews = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении отзывов из БД!', E_USER_ERROR);
    }

    return $reviews;
  }
  
  public static function getReviewsForMain($order_name, $order, $limit) {
    $statement = 'SELECT *
                  FROM `'.self::$_table.'`
				  WHERE active=1
                  ORDER BY `'.$order_name.'` '.$order.' 
				  LIMIT ' . $limit;

    if (($reviews = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении отзывов из БД!', E_USER_ERROR);
    }

    return $reviews;
  }

  public static function getTotalReviews() {
    $statement = 'SELECT count(*) as `count` FROM `'.self::$_table.'`';

    if (($total = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества отзывов из БД!', E_USER_ERROR);
    }

    return $total;
  }
  
  public static function addReviews($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`id_catalog`, `name`, `telefon`, `promocod`, `email`, `rank`, `content`, `active`, `timestamp`)
                  VALUES
                    (:id_catalog, :name, :telefon, :promocod, :email, :rank, :content, :active, :timestamp)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении отзыва в БД!', E_USER_ERROR);
    }
  }  
   
  public static function updateReviews($data) {
    $statement = 'UPDATE `'.self::$_table.'`
					SET `id_catalog` = :id_catalog,
						`name` = :name,
						`telefon` = :telefon,
						`promocod` = :promocod,
						`email` = :email,
						`rank` = :rank,
						`content` = :content,
						`active` = :active
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания производителей в БД!', E_USER_ERROR);
    }
  }  
  
  public static function removeReview($id) {
    $reviews = self::getReview($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id';

    $params = array ('id' => $reviews['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении отзыва из БД!', E_USER_ERROR);
    }
  }  
}