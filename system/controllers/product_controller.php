<?php
class Product_Controller {
  private $_content, $_config;

  public function __construct() {
    $this->_config = Config::getParam('modules->catalog');

    Load::model(array('Catalog', 'Maker', 'Reviews', 'Prefix', 'Question', 'Catalog_characteristics', 'Images', 'Characteristics', 'Characteristics_tree', 'Characteristics_group', 'Characteristics_group_tip'));
	 
    $this->_content['left'] = Render::view('catalog/razdel');
															
  }

	public function defaultAction() {

		Header('Location: /category/');	
		
	}
  
	public function detailedAction() {
    
		$collection = Catalog::getCollectionByUrl(URL::getSegment(3));

		if ($collection) {

		$charactrform = formcreatetosite(@$collection['id'],@$collection['id_char']);
			
		$header = Tree::getTreeByID($collection['id_razdel1']);

		$subrazdel = Tree::getTreeByID($collection['id_razdel2']);	
		if (@$subrazdel['pid'] != 0) {
			$header0 = $subrazdel;
		}		
		
		$prefix = Prefix::getPrefixByID($collection['id_prefix']);
			
		$this->_content['title'] = $collection['title'];
			
		$this->_content['description'] = $collection['description'];
			
		$this->_content['keywords'] = $collection['keywords'];

		$gifts = array();
		
		foreach(explode(',podarokId',$collection['podarok']) as $elem) {

			if (!empty($elem)) $gifts[] = Catalog::getCollectionById($elem);
		
		}	
		
		$colors = getColorsByIdCatalog($collection['id']);
		
		if ((@$_SESSION['user']['id_adminuser']!=0) and (@$_SESSION['user']['manager']!=0)) $manager = true; else $manager = false;
				
		$this->_content['content'] = Render::view(
				'catalog/detailed', Array (
					'collection' => $collection,
					'header' => $header,
					'header0' => @$header0,
					'prefix' => @$prefix,
					'gifts' => $gifts,
					'manager' => @$manager,
					'maker' => Maker::getMakerByID($collection['id_maker']),
					'images' => Images::getImagesByIdCatalog($collection['id']),
					'colors' => $colors,
					'reviews' => Reviews::getReviewsForSite($collection['id']),
					'question' => Question::getQuestionForSite($collection['id']),						
					'imagepath' => $this->_config['image'],
					'charactrform' => $charactrform
				)
		);

		} else {

			$items = Catalog::getCollectionLikeUrl(str_replace("-","_",URL::getSegment(3)));
			if (@$items['path']) {
			header("HTTP/1.1 301 Moved Permanently");
			Header("Location: /product/".@$items['path']);
			} else {
			$content = '<h1 class="b-page-title"><span>Каталог</span></h1><div class="editor">Данной модели на сайте не обнаружено.<br/> Возможно, это произошло из-за переезда сайта на новый домен,<br/> попробуйте найти нужную вам модель через поиск сайта</div>';
			}
			
			$this->_content['content'] = $content;
		}
		
	
		Render::layout('page', $this->_content);
		
	}
	
}