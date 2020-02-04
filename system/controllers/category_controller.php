<?php
class Category_Controller {
	private $_content, $_config;

	public function __construct() {
		
		$this->_config = Config::getParam('modules->catalog');

		Load::model(array('Catalog', 'Friend_send', 'Promocode', 'Maker', 'Brand', 'Reviews', 'Prefix', 'Question', 'Catalog_characteristics', 'Images', 'Characteristics', 'Characteristics_tree', 'Characteristics_group', 'Characteristics_group_tip'));
		 
		$this->_content['left'] = Render::view('catalog/razdel');
																
	}

	public function defaultAction() {
	
		$this->_content['title'] = "Каталог товаров";
			
		$this->_content['description'] = "Каталог товаров";
			
		$this->_content['keywords'] = "Каталог товаров";
		
		$razdel['id'] = 1;
		$razdel['name'] = "Каталог товаров";
	
		$_SESSION['row_sorted'] = $row_sorted = getValueBySelect(@$_GET['row_sorted'],@$_SESSION['row_sorted'],'prioritet-DESC');

		$_SESSION['vmode'] = getValueBySelect(@$_GET['vmode'],@$_SESSION['vmode'],'grid');
		
		$rows_on_page = $this->_config['pagination']['rows_on_page'];
			
		$params = array();

		if((isset($_GET['tip_catalog'])) and (!empty($_GET['tip_catalog']))) { $params['tip_catalog'] = $_GET['tip_catalog']; } else { $params['tip_catalog'] = $razdel['id']; }
		if(isset($_GET['maker'])) { $params['id_maker'] = $_GET['maker']; }
		if((isset($_GET['price_ot'])) and ($_GET['price_ot']!='от')) { $params['price_ot'] = $_GET['price_ot'];	}
		if((isset($_GET['price_do'])) and ($_GET['price_do']!='до')) { $params['price_do'] = $_GET['price_do']; }
			
		$anchor = "";
		foreach($_GET as $index => $val) {
			if (($index != "page") and ($index != "rows_on_page")) $anchor .= '&'.$index.'='.@$val;
		}
		
		$params['sort'] = $row_sorted;
			
		$totals = Catalog::getPodbor($params, "", FALSE);
		
		$pagination = new Pagination ($totals,$rows_on_page,$this->_config['pagination']['link_by_side'],$this->_config['pagination']['url_segment'],$this->_config['pagination']['base_url']);
		
		$collections = Catalog::getPodbor($params, $pagination->getLimit(), TRUE);
			
		$this->_content['content'] = Render::view(
			'catalog/list', Array (     
				'h1item' => "Каталог продукции",
				'collections' => $collections,		
				'imagepath' => $this->_config['image'],
				'tips_catalog' => Tree::getTreesForSite("pid=1"),
				'vmode' => @$_SESSION['vmode'],
				'totals' => $totals,
				'anchor' => @$anchor,
				'razdel' => $razdel,
				'rows_on_page' => $rows_on_page,
				'makers' => Maker::getMakersForSite("active=1","name","ASC",Maker::getTotalMakers()),
				'pagination' => $pagination->getPagination(),
				'getLimitas' => $pagination->getLimitas()
		  )
		);
		
		Render::layout('page', $this->_content);
	
	}
  
	public function detailedAction() {

		$razdel = Tree::getTreeByPath(URL::getSegment(3));
		
		$subrazdel = Tree::getTreeById($razdel['pid']);
			
		if ($subrazdel['pid'] != 0) $razdel0 = $subrazdel;
						
		$params = array();
			
		if (isset($razdel['id'])) {
		
			if (URL::getSegment(4)!='') {
			
				$m_url = Maker::getMakersByPath(URL::getSegment(4));
				
				$elems = Brand::getBrands($razdel['id'],$m_url['id']);
				
				if (isset($elems[0])) {
					
					$h1item = $elems[0]['name'];
					
					$this->_content['title'] = $elems[0]['title'];
						
					$this->_content['description'] = $elems[0]['description'];
						
					$this->_content['keywords'] = $elems[0]['keywords'];

					$useful_info = 	$elems[0]['short_description'];
					
				}
				
				$params['id_maker'] = $m_url['id'];
			
			} else {
			
				$h1item = $razdel['name'];
			
				$this->_content['title'] = $razdel['title'];
					
				$this->_content['description'] = $razdel['description'];
					
				$this->_content['keywords'] = $razdel['keywords'];	
				
				$useful_info = $razdel['short_description'];
				
			}

			$params['filtr'] = TRUE;

			$_SESSION['row_sorted'] = $row_sorted = getValueBySelect(@$_GET['row_sorted'],@$_SESSION['row_sorted'],'prioritet-DESC');

			$_SESSION['vmode'] = getValueBySelect(@$_GET['vmode'],@$_SESSION['vmode'],'grid');
			
			$rows_on_page = $this->_config['pagination']['rows_on_page'];

			if((isset($_GET['tip_catalog'])) and (!empty($_GET['tip_catalog']))) { $params['tip_catalog'] = $_GET['tip_catalog']; } else { $params['tip_catalog'] = $razdel['id']; }
			if(isset($_GET['maker'])) { $params['id_maker'] = $_GET['maker']; }
			if((isset($_GET['price_ot'])) and ($_GET['price_ot']!='от')) { $params['price_ot'] = $_GET['price_ot']; }
			if((isset($_GET['price_do'])) and ($_GET['price_do']!='до')) { $params['price_do'] = $_GET['price_do']; }
		
			$anchor = '';
			$anchor_maker = '';
			foreach($_GET as $index => $val) {
				if (($index != "page") and ($index != "rows_on_page")) {
					if ((!empty($val)) and (!is_array($val))) $anchor .= '&'.$index.'='.@$val;
					if (is_array($val)) {
						foreach($val as $elem) {
							if ($index == "maker") {
								$anchor_maker .= '&'.$index.'[]='.@$elem;
							} else {
								$anchor .= '&'.$index.'[]='.@$elem;	
							}
						}
					}
				
				}
			}

			if (isset($_GET['maker'])) {
				foreach($_GET['maker'] as $maker) {
					$url_brand = Maker::getMakerById($maker);
					break;
				}
				$ank = substr($anchor, 1);
				
				//if ((!empty($_GET['maker'])) and (count($_GET['maker'])==1)) 
				//header('Location: /category/'.$razdel['path'].'/'.$url_brand['path'].'/?'.$ank);
				//else 
				//header('Location: /category/'.$razdel['path'].'/?'.$ank.$anchor_maker);
				//echo "";				
			} 			
			
		
			$params['sort'] = $row_sorted;
			
			$makers = Catalog::getIdMakerByIdRazdel($razdel['id']);

			if (isset($_GET['filtr_submit'])) {	
				
				$zaprosSQL = get_sql_by_filtr();
				if (!empty($zaprosSQL)) {
				$list = Catalog_characteristics::getCollectionForSite($zaprosSQL);
				$str = '';
				foreach ($list as $item) {
					$str .= $item['id_catalog'].',';
				}
				
				if (empty($str)) $params['filtr'] = FALSE; else $params['list_id'] = substr($str, 0, strlen($str)-1);
				}
			}
				
			$totals = Catalog::getPodbor($params, "", FALSE);
		
			$pagination = new Pagination ($totals,$rows_on_page,$this->_config['pagination']['link_by_side'],$this->_config['pagination']['url_segment'],$this->_config['pagination']['base_url']);
						
			$collections = Catalog::getPodbor($params, $pagination->getLimit(), TRUE);
			$tips_catalog = Tree::getTreesForSite("pid=".$razdel['id']);
			if (empty($tips_catalog)) $tips_catalog = Tree::getTreesForSite("pid=".$razdel['pid']);
			
			$this->_content['left'] = Render::view('catalog/left_filtr');	

			$this->_content['content'] = Render::view(
					'catalog/list', Array (
						'collections' => $collections,
						'pagination' => $pagination->getPagination(),
						'getLimitas' => $pagination->getLimitas(),
						'h1item' => @$h1item,
						'razdel' => $razdel,
						'razdel0' => @$razdel0,
						'useful_info' => @$useful_info,
						'anchor' => @$anchor.@$anchor_maker,
						'rows_on_page' => $rows_on_page,
						'vmode' => @$_SESSION['vmode'],
						'tips_catalog' => $tips_catalog,
						'makers' => $makers,
						'totals' => $totals,
						'imagepath' => $this->_config['image']
					)
			);
		
		}

		Render::layout('page', $this->_content);
	
	}
	
	public function searchAction() {
    
	
		if ((isset($_GET['q'])) and (!empty($_GET['q'])) and ($_GET['q']!='Поиск...')) {
				
			$this->_content['title'] = 'Результаты поиска';
			
			$totals = Catalog::getTotalSearch($_GET['q']);
			
			$_SESSION['rows_on_page'] = $rows_on_page = getValueBySelect(@$_GET['rows_on_page'],@$_SESSION['rows_on_page'],$this->_config['pagination']['rows_on_page']);
				
			$anchor = '';
			foreach($_GET as $index => $val) {
				if (($index != "page") and ($index != "rows_on_page")) {
					if ((!empty($val)) and (!is_array($val))) $anchor .= '&'.$index.'='.@$val;
					if (is_array($val)) {
						foreach($val as $elem) {
							if ($index != "maker") {
								$anchor .= '&'.$index.'[]='.@$elem;	
							}
						}
					}
				
				}
			}	
				
			$pagination = new Pagination (
				$totals,
				$rows_on_page,
				$this->_config['pagination']['link_by_side'],
				$this->_config['pagination']['url_segment'],
				$this->_config['pagination']['base_url']
			);
									
			$collections = Catalog::search($_GET['q'],$pagination->getLimit());

			if ($collections) {
			   $this->_content['content'] = Render::view(
				'catalog/search_result',
				 Array(
				   'collections' => $collections,
				   'pagination' => $pagination->getPagination(),
				   'rows_on_page' => $rows_on_page,
				   'anchor' => $anchor,
				   'getLimitas' => $pagination->getLimitas(),
				   'imagepath' => $this->_config['image'],
				 )
			  );
			} else {
				$this->_content['content'] = '<h1 class="b-page-title"><span>Результаты поиска</span></h1><div class="editor">По запросу "'.$_GET['q'].'" ничего не найдено</div>';
			}
			
		}
		
		if ((isset($_GET['code'])) and (!empty($_GET['code'])) and ($_GET['code']!='Поиск по коду')) {
			
			$code = $_GET['code'];
			$items = Friend_send::getZakazs($code);
			unset($_SESSION['compare']);
			$_SESSION['code'] = $code;
			foreach($items as $item) {
			
				$id_catalog = $item['id_catalog'];			
			
				$id_zakaz = $id_catalog;

				$compare = Catalog::getCollectionByID($id_catalog);
				$id_char = $compare['id_char'];
				$tree = Tree::getTreeByID($id_char);
				
				$_SESSION['compare'][$id_char][$id_zakaz] = $compare;
	
				$_SESSION['compare'][$id_char][$id_zakaz]['image_path'] = insert_image($id_catalog);
		
				$_SESSION['compare'][$id_char][$id_zakaz]['kolvo'] = 1;
		
				$_SESSION['compare'][$id_char][$id_zakaz]['id_item'] = $id_zakaz;
				
				$_SESSION['compare'][$id_char][$id_zakaz]['id_image'] = $id_image;
				
			}			
			
			header("Location: /cart/wishlist/");
		
		}		
		
		Render::layout('page', $this->_content);
	}	
	
	public function pred_searchAction() {
	
		$data = array();    
		
		if ($_GET['q']!='') {
			
			$items = Catalog::search($_GET['q'],9);
			
			if (@$items) {
				$data['html'] = get_pred_search($items,$this->_config['image']);
				$data['succes'] = TRUE;
			} else {
				$data['succes'] = FALSE;
			}
			
		} else {
				
			$data['succes'] = FALSE;
			
		}
		echo json_encode($data);

	}	
	
	
	public function podborAction() {
    
		$data = array();

		$razdel = Tree::getTreeByPath($_GET['path']);
		
		$subrazdel = Tree::getTreeById($razdel['pid']);
			
		if ($subrazdel['pid'] != 0) $razdel0 = $subrazdel;
		else $razdel0 = 0;
									
		$params = array();
		
		$params['filtr'] = TRUE;
	
		if ($_GET['path2']!='') {
		
			$m_url = Maker::getMakersByPath($_GET['path2']);
			
			$elems = Brand::getBrands($razdel['id'],$m_url['id']);
			
			if (isset($elems[0])) {
				
				$h1item = $elems[0]['name'];
	
				$useful_info = 	$elems[0]['short_description'];
				
			}
			
			$params['id_maker'] = $m_url['id'];
		
		} else {
		
			$h1item = $razdel['name'];

			$useful_info = $razdel['short_description'];
			
		}

		$_SESSION['row_sorted'] = $row_sorted = getValueBySelect(@$_GET['row_sorted'],@$_SESSION['row_sorted'],'prioritet-DESC');

		$_SESSION['vmode'] = getValueBySelect(@$_GET['vmode'],@$_SESSION['vmode'],'grid');
		
		$_SESSION['rows_on_page'] = $rows_on_page = getValueBySelect(@$_GET['rows_on_page'],@$_SESSION['rows_on_page'],$this->_config['pagination']['rows_on_page']);

		if((isset($_GET['tip_catalog'])) and (!empty($_GET['tip_catalog']))) { $params['tip_catalog'] = $_GET['tip_catalog']; } else { $params['tip_catalog'] = $razdel['id']; }
		if(isset($_GET['maker'])) { $params['id_maker'] = $_GET['maker']; }
		if((isset($_GET['price_ot'])) and ($_GET['price_ot']!='от')) { $params['price_ot'] = $_GET['price_ot']; }
		if((isset($_GET['price_do'])) and ($_GET['price_do']!='до')) { $params['price_do'] = $_GET['price_do']; }
	
		$anchor = '';
		$anchor_maker = '';
		foreach($_GET as $index => $val) {
			if (($index != "page") and ($index != "rows_on_page")) {
				if ((!empty($val)) and (!is_array($val))) $anchor .= '&'.$index.'='.@$val;
				if (is_array($val)) {
					foreach($val as $elem) {
						if ($index == "maker") {
							$anchor_maker .= '&'.$index.'[]='.@$elem;
						} else {
							$anchor .= '&'.$index.'[]='.@$elem;	
						}
					}
				}
			
			}
		}

		if (isset($_GET['maker'])) {
			foreach($_GET['maker'] as $maker) {
				$url_brand = Maker::getMakerById($maker);
				break;
			}
			$ank = substr($anchor, 1);
			
			if ((!empty($_GET['maker'])) and (count($_GET['maker'])==1)) { 
				//$data['redirect'] = '/category/'.$razdel['path'].'/'.$url_brand['path'].'/?'.$ank;
			} else {
				//$data['redirect'] = '/category/'.$razdel['path'].'/?'.$ank.$anchor_maker;
			}			
		} 			
			
		
		$params['sort'] = $row_sorted;
			
		$makers = Catalog::getIdMakerByIdRazdel($razdel['id']);

		if (isset($_GET['filtr_submit'])) {	
			
			$zaprosSQL = get_sql_by_filtr();
			if (!empty($zaprosSQL)) {
			$list = Catalog_characteristics::getCollectionForSite($zaprosSQL);
			$str = '';
			foreach ($list as $item) {
				$str .= $item['id_catalog'].',';
			}
			
			if (empty($str)) $params['filtr'] = FALSE; else $params['list_id'] = substr($str, 0, strlen($str)-1);
			}
		}
				
		$totals = Catalog::getPodbor($params, "", FALSE);
		
		$pagination = new Pagination ($totals,$rows_on_page,$this->_config['pagination']['link_by_side'],$this->_config['pagination']['url_segment'],$this->_config['pagination']['base_url']);
						
		$collections = Catalog::getPodbor($params, $pagination->getLimit(), TRUE);

		$pagi = $pagination->getPagination();
		$getLimitas = $pagination->getLimitas();
		$anchor = @$anchor.@$anchor_maker;
		$vmode = @$_SESSION['vmode'];
		$imagepath = $this->_config['image'];		
		
		$data['html'] = list_product_html($collections,$imagepath,$getLimitas,$pagi,$rows_on_page,$anchor,$razdel0,$h1item,$totals);
		$data['succes'] = true;
		
		echo json_encode($data);
	}	
	

	public function addreviewAction() {
		
		$data = array();
		
		if((!empty($_POST['kapcha'])) and ($_POST['kapcha'] == $_SESSION['rand_code'])) {
		
			if (!empty($_POST['id_catalog'])) {
				
				if (!empty($_POST['promocod'])) {
					$promocode = Promocode::getPageByName($_POST['promocod']);
						if (@$promocode['id']) {
							if (mb_strlen($_POST['content'], "utf-8") < 200) {
								$data['succes'] = FALSE;
								$data['message'] = "Для платного отзыва количество символов не должно быть меньше 200.";							
							} else {
								Promocode::updatePage(array(
									'id' => $promocode['id'],
									'name' => $promocode['name'],
									'active' => 0,
									'active_raffle' => $promocode['active_raffle'],								
								));
								$text = "Спасибо за оставленный отзыв. Он будет опубликован после проверки модератором";
								send_sms($text,$_POST['telefon']);
								$data['succes'] = TRUE;
								$data['message'] = "Отзыв успешно добавлен. Он будет опубликован после проверки администратором сайта.";						
							}
						} else {
							$data['succes'] = FALSE;
							$data['message'] = "Внимательно введите код с флаера.";
						}
				} else {
					$data['succes'] = TRUE;
					$data['message'] = "Отзыв успешно добавлен. Он будет опубликован после проверки администратором сайта.";
				}			
			} else {
				$data['succes'] = FALSE;
				$data['message'] = "Произошла ошибка. Отзыв не добавлен.";		
			}
		} else {
			$data['succes'] = FALSE;
			$data['message'] = "Не верный код с картинки";				
		}
		
		if ($data['succes']==TRUE) {
			Reviews::addReviews(array(
				'id_catalog' => $_POST['id_catalog'],
				'name' => htmlspecialchars($_POST['name']),
				'telefon' => htmlspecialchars($_POST['telefon']),
				'email' => htmlspecialchars($_POST['email']),
				'rank' => htmlspecialchars($_POST['rating']),
				'content' => htmlspecialchars($_POST['content']),
				'promocod' => htmlspecialchars($_POST['promocod']),
				'active' => 0,
				'timestamp' => time()
			));			
		}
		echo json_encode($data);	
	//Load::library('php_mailer/class.phpmailer.php');	
	//   $mail = new PHPMailer();
	//   $mail->SetFrom($_SERVER['SERVER_ADMIN'], 'Сайт babyexpert');
	//   $mail->AddAddress(Config::getParam('modules->question->email'));
	//   $mail->Subject = 'Новый отзыв на сайте!';
	//   $mail->Body = Render::letters(Config::getParam('modules->question->letter_reviews'));
	//   $mail->CharSet = 'utf-8';

   //echo ($mail->Send()) ? 'success' : 'failed';	
	}  

	public function addquestionAction() {
		$data = array();
		
		if (!empty($_POST['id_catalog'])) {
			
			Question::addQuestion(array(
				'id_catalog' => $_POST['id_catalog'],
				'name' => htmlspecialchars($_POST['name']),
				'telefon' => htmlspecialchars($_POST['telefon']),
				'email' => htmlspecialchars($_POST['email']),
				'question' => htmlspecialchars($_POST['question']),
				'answer' => "",
				'active' => 0,
				'notice' => 0,
				'timestamp' => time()
			));	
			
			$data['succes'] = TRUE;
			$data['message'] = "Вопрос успешно отправлен.";		
		} else {
			$data['succes'] = FALSE;
			$data['message'] = "Произошла ошибка. Вопрос не добавлен.";		
		}
		
		echo json_encode($data);	
	//Load::library('php_mailer/class.phpmailer.php');	
	//   $mail = new PHPMailer();
	//   $mail->SetFrom($_SERVER['SERVER_ADMIN'], 'Сайт avto');
	//   $mail->AddAddress(Config::getParam('modules->question->email'));
	//   $mail->Subject = 'Новый отзыв на сайте!';
	//   $mail->Body = Render::letters(Config::getParam('modules->question->letter_reviews'));
	//   $mail->CharSet = 'utf-8';

   //echo ($mail->Send()) ? 'success' : 'failed';	
	}		
		
}