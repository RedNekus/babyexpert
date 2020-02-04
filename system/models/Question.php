<?php

class Question {
  private static $_table;

  public static function init() {
    self::$_table = Config::getParam('modules->question->table');
  }

  public static function getQuestion($id) {
    $statement = 'SELECT * FROM `'.self::$_table.'` WHERE `id` = :id';

    if (($review = DB::query($statement, Array('id' => $id), TRUE)) === FALSE) {
      trigger_error('Ошибка при получении данных из БД!', E_USER_ERROR);
    }

    return $review;
  }

  public static function getQuestionForSite($id) {
    $statement = 'SELECT *
                  FROM `'.self::$_table.'` WHERE id_catalog='.$id.' and active=1 ORDER BY `id` DESC';

    if (($question = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении вопросов из БД!', E_USER_ERROR);
    }

    return $question;
  }  
  
  public static function getQuestions($order_name, $order, $limit) {
    $statement = 'SELECT *
                  FROM `'.self::$_table.'` 
                  ORDER BY `'.$order_name.'` '.$order.' 
				  LIMIT ' . $limit;

    if (($question = DB::query($statement)) === FALSE) {
      trigger_error('Ошибка при получении вопросов из БД!', E_USER_ERROR);
    }

    return $question;
  }

  public static function getTotalQuestions() {
    $statement = 'SELECT count(*) as `count` FROM `'.self::$_table.'`';

    if (($total = DB::query($statement, '', TRUE, 'count')) === FALSE) {
      trigger_error('Ошибка при получении количества вопросов из БД!', E_USER_ERROR);
    }

    return $total;
  }
  
  public static function addQuestion($data) {
    $statement = 'INSERT INTO `'.self::$_table.'`
                    (`id_catalog`, `name`, `telefon`, `email`, `question`, `answer`, `active`, `notice`, `timestamp`)
                  VALUES
                    (:id_catalog, :name, :telefon, :email, :question, :answer, :active, :notice, :timestamp)';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при добавлении вопросов в БД!', E_USER_ERROR);
    }
  }  
   
  public static function updateQuestion($data) {
    $statement = 'UPDATE `'.self::$_table.'`
					SET `id_catalog` = :id_catalog,
						`name` = :name,
						`telefon` = :telefon,
						`email` = :email,
						`question` = :question,
						`answer` = :answer,
						`active` = :active,
						`notice` = :notice
                  WHERE `id` = :id';

    if (DB::query($statement, $data) === FALSE) {
      trigger_error('Ошибка при обновлении описания вопросов в БД!', E_USER_ERROR);
    }
  }  
  
  public static function removeQuestion($id) {
    $question = self::getQuestion($id);

    $statement = 'DELETE FROM `'.self::$_table.'`
                  WHERE `id` = :id';

    $params = array ('id' => $question['id']);

    if (DB::query($statement, $params) === FALSE) {
      trigger_error('Ошибка при удалении вопросов из БД!', E_USER_ERROR);
    }
  }  
}