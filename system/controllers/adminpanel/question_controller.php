<?php

class Question_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Question','Tree','Catalog'));
		$this->_config = Config::getParam('modules->question');
		$this->_content['title'] = 'Вопрос-ответ';
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/questions/list');
   
		Render::layout('adminpanel/adminpanel', $this->_content);
   
	}

    public function loadAction() {
		// Начало формирование объекта
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		

		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		
		
		// Если не указано поле сортировки, то производить сортировку по первому полю
		if(!$sidx) $sidx =1;

		// Теперь эта переменная хранит кол-во записей в таблице
		$count = Question::getTotalQuestions();  
		
		// Рассчитаем сколько всего страниц займут данные в БД
		if( $count > 0 && $limit > 0) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		// Если по каким-то причинам клиент запросил
		if ($page > $total_pages) $page=$total_pages;

		// Рассчитываем стартовое значение для LIMIT запроса
		$start = $limit*$page - $limit;
		// Зашита от отрицательного значения
		if($start <0) $start = 0;

		if (@$searchField) {
		$questions = Question::searchAdmin($searchField, $searchString);
		} else {
		$questions = Question::getQuestions($sidx, $sord, $start.', '.$limit);
		}

		// Начало формирование массива
		// для последующего преобразоования
		// в JSON объект
		$data['page']       = $page;
		$data['total']      = $total_pages;
		$data['records']    = $count;
		
		$i = 0;
		foreach($questions as $item) {
			$rank = "";
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$nameTovar = Catalog::getCollectionByID($item['id_catalog']);
			$data['rows'][$i]['cell'][] = '<a href="/product/'.@$nameTovar['path'].'" target="_ablank">'.@$nameTovar['name'].'</a>';
			$data['rows'][$i]['cell'][] = $item['question'];
			$data['rows'][$i]['cell'][] = $item['answer'];
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = (($item['notice']!=0) ? 'Отправлено' : 'Не отправлено');
			$data['rows'][$i]['cell'][] = date('d.m.Y',$item['timestamp']);			
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
  }  
  
  public function editAction() {

	// РЕДАКТИРОВАТЬ элемент в таблице
	if (isset($_POST['action']) and $_POST['action']=="edit") {
	
	$notice = 0; 
	
	if (isset($_POST['notice_yes'])) {
		Load::library('php_mailer/class.phpmailer.php');	
		$mail = new PHPMailer();
		$mail->SetFrom($_SERVER['SERVER_ADMIN'], 'Сайт babyexpert');
		$mail->AddAddress($_POST['email']);
		$mail->Subject = 'Новый ответ на сайте!';
		$mail->IsHTML(true);
		$mail->Body = Render::letters(Config::getParam('modules->question->letter'));
		$mail->CharSet = 'utf-8';
		
		if (!empty($_POST['telefon'])) {
			
			$text = "У вас новый ответ на сайте http://babyexpert.by";
			
			$result = send_sms($text,$_POST['telefon']);
			
		}
		
		if ($mail->Send()) $notice = 1;
		
	}
	
		Question::updateQuestion(array(
			'id' => $_POST['id'],  
			'id_catalog' => $_POST['id_catalog'],
			'name' => $_POST['name'],
			'email' => $_POST['email'],		  
			'telefon' => $_POST['telefon'],			  		  		  
			'active' => ((isset($_POST['active'])) ? 1 : 0),
			'notice' => $notice,
			'question' => $_POST['question'],			
			'answer' => $_POST['answer']
			));	
	}

	// УДАЛИТЬ элемент из таблицы
	if (isset($_POST['del_id']))  {
	  Question::removeQuestion($_POST['del_id']);
	}	
  }
  
	//Получение данных по id в форму для добавления товара
	public function datahandlingAction() {
		$questions = array();
		$questions = Question::getQuestion($_POST['id']);
		$nameTovar = Catalog::getCollectionByID($questions['id_catalog']);
		$questions['nameTovar'] = $nameTovar['name'];
		echo json_encode($questions);
	}

}