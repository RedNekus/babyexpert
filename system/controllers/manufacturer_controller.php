<?php
class Manufacturer_Controller {
	private $_content, $_config, $_config_cat;

	public function __construct() {
		
		$this->_config = Config::getParam('modules->maker');
		
		$this->_config_cat = Config::getParam('modules->catalog');

		Load::model(array('Catalog', 'Maker', 'Catalog_characteristics', 'Images', 'Characteristics', 'Characteristics_tree', 'Characteristics_group', 'Characteristics_group_tip'));
	 
		$this->_content['left'] = Render::view('catalog/razdel');
															
	}

	public function defaultAction() {

		header('location: /category/');
	
	}
  
	public function detailedAction() {

		$razdel = Maker::getMakersByPath(URL::getSegment(3));

		if (isset($razdel['id'])) {
		
			$id_razdel = 1;
		
			$this->_content['title'] = $razdel['title'];
				
			$this->_content['description'] = $razdel['description'];
				
			$this->_content['keywords'] = $razdel['keywords'];

			$_SESSION['row_sorted'] = $row_sorted = getValueBySelect(@$_POST['row_sorted'],@$_SESSION['row_sorted'],'id-ASC');

			$_SESSION['vmode'] = getValueBySelect(@$_GET['vmode'],@$_SESSION['vmode'],'grid');
			
			$_SESSION['rows_on_page'] = $rows_on_page = getValueBySelect(@$_GET['rows_on_page'],@$_SESSION['rows_on_page'],$this->_config_cat['pagination']['rows_on_page']);
				
			$params = array();

			if((isset($_GET['tip_catalog'])) and (!empty($_GET['tip_catalog']))) { $params['tip_catalog'] = $_GET['tip_catalog']; } else { $params['tip_catalog'] = $id_razdel; }
			if(isset($_GET['maker'])) { $params['id_maker'] = $_GET['maker']; } else {$params['id_maker'] = $razdel['id'];}
			if((isset($_GET['price_ot'])) and ($_GET['price_ot']!='от')) { $params['price_ot'] = $_GET['price_ot']; }
			if((isset($_GET['price_do'])) and ($_GET['price_do']!='до')) { $params['price_do'] = $_GET['price_do']; }
		
			$anchor = "";
			foreach($_GET as $index => $val) {
				if (($index != "page") and ($index != "rows_on_page")) $anchor .= '&'.$index.'='.@$val;
			}
		
			$params['sort'] = $row_sorted;
			
			$makers = Maker::getMakersForSite("active=1","name","ASC",Maker::getTotalMakers());
			
			$totals = Catalog::getPodbor($params, "", FALSE);
			
			$pagination = new Pagination ($totals,$rows_on_page,$this->_config_cat['pagination']['link_by_side'],$this->_config_cat['pagination']['url_segment'],$this->_config_cat['pagination']['base_url']);
			
			$collections = Catalog::getPodbor($params, $pagination->getLimit(), TRUE);
			
			$tips_catalog = Tree::getTreesForSite("pid=1");
			
			$this->_content['content'] = Render::view(
					'catalog/list', Array (
						'collections' => $collections,
						'pagination' => $pagination->getPagination(),
						'getLimitas' => $pagination->getLimitas(),
						'h1item' => $razdel['name'],
						'razdel' => $razdel,
						'useful_info' => $razdel['short_description'],
						'razdel0' => @$razdel0,
						'anchor' => @$anchor,
						'rows_on_page' => $rows_on_page,
						'vmode' => @$_SESSION['vmode'],
						'tips_catalog' => $tips_catalog,
						'makers' => $makers,
						'totals' => $totals,
						'imagepath' => $this->_config_cat['image']
					)
			);
		
		}

		Render::layout('page', $this->_content);
	
	}

}