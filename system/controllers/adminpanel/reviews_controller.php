<?php

class Reviews_Controller {
  private $_config, $_content;

  public function __construct() {
    Load::model(array('Reviews','Tree','Catalog'));
    $this->_config = Config::getParam('modules->reviews');
    $this->_content['title'] = 'Отзывы';
  }

  public function defaultAction() {
    $this->listAction();
  }
  
	public function listAction() {
	
		$this->_content['content'] = Render::view('adminpanel/reviews/list');
   
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

		// Теперь эта переменная хранит кол-во записей в таблице
		$count = Reviews::getTotalReviews();  
		
		$data = getPaginationAdmin($count,$limit,$page);

		if (@$searchField) {
		$reviewss = Reviews::searchAdmin($searchField, $searchString);
		} else {
		$reviewss = Reviews::getReviews($sidx, $sord, $data['limit']);
		}

		$i = 0;
		foreach($reviewss as $item) {
			$rank = "";
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$nameTovar = Catalog::getCollectionByID($item['id_catalog']);
			$data['rows'][$i]['cell'][] = '<a href="/product/'.$nameTovar['path'].'" target="_ablank">'.$nameTovar['name'].'</a>';
			$data['rows'][$i]['cell'][] = $item['content'];
			$data['rows'][$i]['cell'][] = '<img src="/img/admin/rank_'.$item['rank'].'.png" width=75px alt="" />';
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = date('d.m.Y',$item['timestamp']);			
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
  }  
  
  public function editAction() {

	// РЕДАКТИРОВАТЬ элемент в таблице
	if (isset($_POST['action']) and $_POST['action']=="edit") {
		Reviews::updateReviews(array(
			'id' => $_POST['id'],  
			'id_catalog' => $_POST['id_catalog'],
			'name' => $_POST['name'],
			'email' => $_POST['email'],		  
			'telefon' => $_POST['telefon'],			  		  		  
			'promocod' => $_POST['promocod'],			  		  		  
			'active' => ((isset($_POST['active'])) ? 1 : 0),
			'rank' => $_POST['rank'],			
			'content' => $_POST['content']
			));	
	}

	// УДАЛИТЬ элемент из таблицы
	if (isset($_POST['del_id']))  {
	  Reviews::removeReview($_POST['del_id']);
	}	
  }
  
  //Получение данных по id в форму для добавления товара
  public function datahandlingAction() {
	$reviews = array();
	$reviews = Reviews::getReview($_POST['id']);
	$nameTovar = Catalog::getCollectionByID($reviews['id_catalog']);
	$reviews['nameTovar'] = $nameTovar['name'];
	echo json_encode($reviews);
  }

}