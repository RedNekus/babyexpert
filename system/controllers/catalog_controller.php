<?php

class Catalog_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Catalog', 'Catalog_category', 'Currency_tree', 'Catalog_language', 'Adminusers_stats', 'Catalog_characteristics', 'Tree', 'Tree_language', 'Maker', 'Images', 'Prefix', 'Characteristics', 'Characteristics_tree', 'Characteristics_group', 'Characteristics_group_tip'));
		$this->_config = Config::getParam('modules->catalog');
		$this->_content['title'] = 'Каталог товаров';
	}

	
	public function defaultAction() {
		$this->listAction();
	}
  
  
	public function listAction() {
  
		$trees  = Tree::getTrees();
		$total_maker = Maker::getTotalMakers();
		$makers  = Maker::getMakers("name","asc",$total_maker);
		$currency  = Currency_tree::getTreesByPid(1);
		$characteristics = Characteristics_tree::getTrees();
	
		$this->_content['content'] = Render::view(
				'adminpanel/catalog/list', array (
				'trees' => get_tree($trees, 0),
				'makers' => $makers,
				'access' => get_array_access(),
				'currency' => $currency,
				'img_size' => $this->_config['image']['small'],
				'characteristics' => $characteristics
				)
			);
		Render::layout('adminpanel/adminpanel', $this->_content);
	}

	
    public function loadAction() {
	
		// Начало формирование объекта
		$data = array();
		$params = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		

		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		

		$valuerazdel  = (@$_GET['id']) ? @$_GET['id'] : 1;	

		$count = Catalog::getTotalCollections($valuerazdel);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) {
			$productions = Catalog::searchAdmin($searchField, $searchString);
		} else {
			$productions = Catalog::getCollections($valuerazdel,$sidx,$sord,$data['limit']);
		}

		$i = 0;
		foreach($productions as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];
			$data['rows'][$i]['cell'][] = '<a href="/product/'.$item['path'].'" target=_ablank>просмотр</a>';
			$data['rows'][$i]['cell'][] = $item['cena'];
			$data['rows'][$i]['cell'][] = (($item['new']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = (($item['hit']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = (($item['spec_pred']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['prioritet'];			
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
  }  
  
	public function editAction() {
  
/********************************************************/
/*				Редатирование модуля товаров			*/  
/********************************************************/  
	
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
		
		$path = "";
		if ((!empty($_POST['path'])) or ($_POST['path']!="")) {
			$path = $_POST['path'];
		} else {
			$path = translit_path($_POST['name']);
		}
			Catalog::addCollection(array(
				'title' => $_POST['title'],
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description'],		  
				'name' => $_POST['name'],			  
				'cena' => $_POST['cena'],		  
				'id_currency' => $_POST['id_currency'],		  
				'cena_blr' => $_POST['cena_blr'],
				'new' => ((isset($_POST['new'])) ? 1 : 0),
				'hit' => ((isset($_POST['hit'])) ? 1 : 0),
				'spec_pred' => ((isset($_POST['spec_pred'])) ? 1 : 0), 		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'tovar_nedeli' => ((isset($_POST['tovar_nedeli'])) ? 1 : 0),
				'expert' => ((isset($_POST['expert'])) ? 1 : 0),
				'prioritet' => $_POST['prioritet'],
				'short_description' => $_POST['short_description'],
				'full_description' => $_POST['full_description'],
				'path' => $path,
				'id_maker' => $_POST['id_maker'],			
				'instructions' => $_POST['instructions'],
				'pohozhie' => $_POST['pohozhie'],
				'soput' => $_POST['soput'],
				'podarok' => $_POST['podarok'],
				'id_prefix' => $_POST['id_prefix'],
				'id_char' => $_POST['id_char'],
				'status' => $_POST['status']
			));	
			
			$id_catalog = Catalog::getLastId();	
			
			Catalog_language::addCollection(array(
				'id_catalog_lng' => $id_catalog,
				'id_language' => 2,
				'title_lng' => $_POST['title_lng'],
				'keywords_lng' => $_POST['keywords_lng'],
				'description_lng' => $_POST['description_lng'],		  
				'name_lng' => $_POST['name_lng'],			  
				'short_description_lng' => $_POST['short_description_lng'],
				'full_description_lng' => $_POST['full_description_lng'],			
				'instructions_lng' => $_POST['instructions_lng']
			));		
			Adminusers_stats::addStats(array(
				'id_catalog' => $id_catalog,
				'id_adminusers' => $_SESSION['isLoggedIn']['id'],
				'action' => $_POST['action'],
				'created' => time()
			));

			if ((isset($_POST['json'])) and (!empty($_POST['json']))) {
				save_category($id_catalog, $_POST['json']);
			}	
			
			if ((isset($_POST['str_char'])) and (!empty($_POST['str_char']))) {
			
				if (isset($_POST['text'])) FormToSQLAdd($id_catalog, $_POST['text'], "text");

				if (isset($_POST['checkbox'])) FormToSQLAdd($id_catalog, $_POST['checkbox'], "checkbox");
			
				if (isset($_POST['radio'])) FormToSQLAdd($id_catalog, $_POST['radio'], "radio");

				if (isset($_POST['select'])) FormToSQLAdd($id_catalog, $_POST['select'], "select");

			}
			
		}

		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
		
			$path = "";
			if ((!empty($_POST['path'])) or ($_POST['path']!="")) {
				$path = $_POST['path'];
			} else {
				$path = translit_path($_POST['name']);
			}
			
			$id_catalog = $_POST['id'];
			
			Catalog::updateCollection(array(
				'id' => $id_catalog,  
				'title' => $_POST['title'],
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description'],						
				'name' => $_POST['name'],			  
				'cena' => $_POST['cena'],		  
				'id_currency' => $_POST['id_currency'],		  
				'cena_blr' => $_POST['cena_blr'],
				'new' => ((isset($_POST['new'])) ? 1 : 0),
				'hit' => ((isset($_POST['hit'])) ? 1 : 0),
				'spec_pred' => ((isset($_POST['spec_pred'])) ? 1 : 0), 		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'tovar_nedeli' => ((isset($_POST['tovar_nedeli'])) ? 1 : 0),
				'expert' => ((isset($_POST['expert'])) ? 1 : 0),				
				'prioritet' => $_POST['prioritet'],
				'short_description' => $_POST['short_description'],
				'full_description' => $_POST['full_description'],
				'path' => $path,
				'id_maker' => $_POST['id_maker'],			
				'instructions' => $_POST['instructions'],
				'pohozhie' => $_POST['pohozhie'],
				'soput' => $_POST['soput'],
				'podarok' => $_POST['podarok'],				
				'id_prefix' => $_POST['id_prefix'],
				'id_char' => $_POST['id_char'],
				'status' => $_POST['status']				
			));	
	
			if (empty($_POST['id_lng'])) {
				Catalog_language::addCollection(array(
					'id_catalog_lng' => $id_catalog,
					'id_language' => 2,
					'title_lng' => $_POST['title_lng'],
					'keywords_lng' => $_POST['keywords_lng'],
					'description_lng' => $_POST['description_lng'],		  
					'name_lng' => $_POST['name_lng'],			  
					'short_description_lng' => $_POST['short_description_lng'],
					'full_description_lng' => $_POST['full_description_lng'],			
					'instructions_lng' => $_POST['instructions_lng']
				));				
			} else {
				Catalog_language::updateCollection(array(
					'id_lng' => $_POST['id_lng'],
					'id_catalog_lng' => $_POST['id'],
					'id_language' => 2,
					'title_lng' => $_POST['title_lng'],
					'keywords_lng' => $_POST['keywords_lng'],
					'description_lng' => $_POST['description_lng'],		  
					'name_lng' => $_POST['name_lng'],			  
					'short_description_lng' => $_POST['short_description_lng'],
					'full_description_lng' => $_POST['full_description_lng'],			
					'instructions_lng' => $_POST['instructions_lng']
				));			
			}
			Adminusers_stats::addStats(array(
				'id_catalog' => $id_catalog,
				'id_adminusers' => $_SESSION['isLoggedIn']['id'],
				'action' => $_POST['action'],
				'created' => time()
			));			

			if ((isset($_POST['json'])) and (!empty($_POST['json']))) {
				save_category($id_catalog, $_POST['json']);
			}	

			
			if ((isset($_POST['str_char'])) and (!empty($_POST['str_char']))) {
			
				Catalog_characteristics::removeCollection($id_catalog);
			
				if (isset($_POST['text'])) FormToSQLAdd($id_catalog, $_POST['text'], "text");

				if (isset($_POST['checkbox'])) FormToSQLAdd($id_catalog, $_POST['checkbox'], "checkbox");
			
				if (isset($_POST['radio'])) FormToSQLAdd($id_catalog, $_POST['radio'], "radio");

				if (isset($_POST['select'])) FormToSQLAdd($id_catalog, $_POST['select'], "select");
				
			}			
		}
		
		if (isset($_POST['action']) and $_POST['action']=="view") {
			Adminusers_stats::addStats(array(
				'id_catalog' => $_POST['id'],
				'id_adminusers' => $_SESSION['isLoggedIn']['id'],
				'action' => $_POST['action'],
				'created' => time()
			));			
		}
		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['oper']) and $_POST['oper']=="edit") {
			Catalog::updateCollectionFromTable(array(
				'id' => $_POST['id'],  		  
				'cena' => $_POST['cena'],
				'new' => ($_POST['new']=='Да') ? 1 : 0,
				'hit' => ($_POST['hit']=='Да') ? 1 : 0,
				'spec_pred' => ($_POST['spec_pred']=='Да') ? 1 : 0,
				'active' => ($_POST['active']=='Да') ? 1 : 0
				));	
		}
	
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id_catalog']))  {
			Catalog::removeCollection($_POST['del_id_catalog']);
			Adminusers_stats::addStats(array(
				'id_catalog' => $_POST['del_id_catalog'],
				'id_adminusers' => $_SESSION['isLoggedIn']['id'],
				'action' => "del",
				'created' => time()
			));		  
		}	
		
/********************************************************/
/*			Редатирование модуля дерево разделов		*/
/********************************************************/
	
		// Добавить элемент в ДЕРЕВО
		if (isset($_POST['action_tree']) and $_POST['action_tree']=="add")  {

			Tree::addTree(array(
				'pid' => $_POST['pid'],
				'name' => $_POST['name'],
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'fullname' => $_POST['fullname'],
				'path' => $_POST['path'],	
				'prioritet' => $_POST['prioritet'],
				'characteristic' => $_POST['characteristic'],
				'short_description' => $_POST['short_description'],
				'title' => $_POST['title'],	
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description']
			));	

			$id_tree_lng = Tree::getLastId();	
		
			Tree_language::addTree(array(
				'id_tree_lng' => $id_tree_lng,
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'],
				'namefull_lng' => $_POST['namefull_lng'],
				'short_description_lng' => $_POST['short_description_lng'],				
				'title_lng' => $_POST['title_lng'],
				'keywords_lng' => $_POST['keywords_lng'],
				'description_lng' => $_POST['description_lng']
			));		
			
		}	

		// Редактировать элемент в ДЕРЕВЕ
		if (isset($_POST['action_tree']) and $_POST['action_tree']=="edit")  {

			Tree::updateTree(array(
				'id'  => $_POST['id'],
				'pid' => $_POST['pid'],
				'name' => $_POST['name'],
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'fullname' => $_POST['fullname'],
				'path' => $_POST['path'],	
				'prioritet' => $_POST['prioritet'],
				'characteristic' => $_POST['characteristic'],
				'short_description' => $_POST['short_description'],
				'title' => $_POST['title'],	
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description']				
				));		
	
			if (empty($_POST['id_lng'])) {
				Tree_language::addTree(array(
					'id_tree_lng' => $_POST['id'],
					'id_language' => 2,
					'name_lng' => $_POST['name_lng'],
					'namefull_lng' => $_POST['namefull_lng'],
					'short_description_lng' => $_POST['short_description_lng'],				
					'title_lng' => $_POST['title_lng'],
					'keywords_lng' => $_POST['keywords_lng'],
					'description_lng' => $_POST['description_lng']
				));				
			} else {
				Tree_language::updateTree(array(
					'id_lng' => $_POST['id_lng'],
					'id_tree_lng' => $_POST['id'],
					'id_language' => 2,
					'name_lng' => $_POST['name_lng'],
					'namefull_lng' => $_POST['namefull_lng'],
					'short_description_lng' => $_POST['short_description_lng'],				
					'title_lng' => $_POST['title_lng'],
					'keywords_lng' => $_POST['keywords_lng'],
					'description_lng' => $_POST['description_lng']
				));			
			}
		}

		// Удалить элемент в ДЕРЕВЕ
		if (isset($_POST['del_id_tree']))  {
			Tree::removeTree($_POST['del_id_tree']);
		}
		
		$trees = array();	
		$trees  = get_tree(Tree::getTrees(), 0);
		echo json_encode($trees);	
	}
	
	//Получение информации по id 
	public function datainitAction() {
		$trees = array();	
		$trees = get_parents(Tree::getTrees(), $_POST['id']);
		$tree = explode(", ", $trees);
		array_pop($tree);
		$tree = array_reverse($tree);
		$name = Tree::getTreeByID($_POST['id']);
		$tree['name'] = $name['name'];
		echo json_encode($tree);
	} 	
	
	public function dataaccessAction() {
		$access = array();	
		$access = get_array_access();
		echo json_encode($access);
	} 	
	
	public function savepriceAction() {
				
		$list = array();
		$params = array();

		array_push($list,array(
			iconv('UTF-8', 'Windows-1251', 'Название'),
			iconv('UTF-8', 'Windows-1251', 'Цена'),
			iconv('UTF-8', 'Windows-1251', 'Ссылка на страницу товара'),
			iconv('UTF-8', 'Windows-1251', 'Ссылка на картинку товара'),
			iconv('UTF-8', 'Windows-1251', 'Категория товара')
			));		
		$params['tip_catalog'] = 1;
		$params['sort'] = 'id-asc';
		$count = Catalog::getPodbor($params, "", FALSE);
		$collections = Catalog::getPodbor($params, $count, TRUE);
		foreach($collections as $item) {
			$prefix = Prefix::getPrefixByID($item['id_prefix']);
			$name = iconv('UTF-8', 'Windows-1251', @$prefix['name'].' '.$item['name']);	
			$razdel = Tree::getTreeByID($item['id_razdel1']);		
			$r_name = iconv('UTF-8', 'Windows-1251', @$razdel['name']);
			array_push($list,array(
					@$name,
					@$item['cena'],
					"http://babyexpert.by/product/".@$item['path'],
					"http://babyexpert.by".$this->_config['image']['big']['path'].insert_image($item['id']),
					@$r_name));			
		}
		outputCSV('assets/files/csv.csv',$list);
		echo json_encode("Прайс обновлен! <br/><a href='http://babyexpert.by/assets/files/csv.csv'>Ссылка</a> для скачивания. <br/>http://babyexpert.by/assets/files/csv.csv");				
	} 	

	public function savepricemigomAction() {
				
		$migom_list = array();
		$params = array();

		array_push($migom_list,array(
			iconv('UTF-8', 'Windows-1251', 'Название'),
			iconv('UTF-8', 'Windows-1251', 'Цена'),
			iconv('UTF-8', 'Windows-1251', 'Ссылка на страницу товара'),
			iconv('UTF-8', 'Windows-1251', 'Ссылка на картинку товара'),
			iconv('UTF-8', 'Windows-1251', 'Примечание'),
			iconv('UTF-8', 'Windows-1251', 'Категория товара'),
			iconv('UTF-8', 'Windows-1251', 'Наличие'),
			));
		$params['tip_catalog'] = 1;
		$params['sort'] = 'id-asc';
		$count = Catalog::getPodbor($params, "", FALSE);
		$collections = Catalog::getPodbor($params, $count, TRUE);
		foreach($collections as $item) {
			$prefix = Prefix::getPrefixByID($item['id_prefix']);
				
			$razdel = Tree::getTreeByID($item['id_razdel1']);		
			$r_name = iconv('UTF-8', 'Windows-1251', @$razdel['name']);	
			$nalichie = iconv('UTF-8', 'Windows-1251', get_status_to_migom(@$item['status']));	
			$colors = getColorsByIdCatalog($item['id']);
			if (isset($colors)) {
				foreach($colors as $color) {
					$name = iconv('UTF-8', 'Windows-1251', @$prefix['name'].' '.$item['name']);
					$color_name = iconv('UTF-8', 'Windows-1251', ' '.$color['name']);
					array_push($migom_list,array(
							$name.@$color_name,
							$item['cena'],
							"http://babyexpert.by/product/".$item['path'],
							"http://babyexpert.by".$this->_config['image']['big']['path'].@$color['image'],
							iconv('UTF-8', 'Windows-1251', $item['full_description']),
							$r_name,
							$nalichie));				
				
				}
			} else {
			$name = iconv('UTF-8', 'Windows-1251', @$prefix['name'].' '.$item['name']);
			array_push($migom_list,array(
					$name,
					$item['cena'],
					"http://babyexpert.by/product/".$item['path'],
					"http://babyexpert.by".$this->_config['image']['big']['path'].insert_image($item['id']),
					$item['full_description'],
					$r_name,
					$nalichie));
			}
		}

		outputCSV('assets/files/migom.csv',$migom_list);
		echo json_encode("Прайс Migom обновлен! <br/><a href='http://babyexpert.by/assets/files/migom.csv'>Ссылка</a> для скачивания. <br/>http://babyexpert.by/assets/files/migom.csv");		
	} 	

	public function savepricepokupayAction() {
				
		$pokupay_list = array();
		$params = array();

		array_push($pokupay_list,array(
			iconv('UTF-8', 'Windows-1251', 'Категория товара'),		
			iconv('UTF-8', 'Windows-1251', 'Название'),
			iconv('UTF-8', 'Windows-1251', 'Цена'),
			iconv('UTF-8', 'Windows-1251', 'Валюта'),		
			iconv('UTF-8', 'Windows-1251', 'Наличие'),			
			iconv('UTF-8', 'Windows-1251', 'Ссылка на страницу товара'),
			));

		$params['tip_catalog'] = 1;
		$params['sort'] = 'id-asc';
		$count = Catalog::getPodbor($params, "", FALSE);
		$collections = Catalog::getPodbor($params, $count, TRUE);
		foreach($collections as $item) {
			$prefix = Prefix::getPrefixByID($item['id_prefix']);
				
			$razdel = Tree::getTreeByID($item['id_razdel1']);		
			$r_name = iconv('UTF-8', 'Windows-1251', @$razdel['name']);	
			$nalichie = iconv('UTF-8', 'Windows-1251', get_status_to_pokupay(@$item['status']));	

			$name = iconv('UTF-8', 'Windows-1251', $item['name']);
			array_push($pokupay_list,array(
					$r_name,			
					$name,
					$item['cena'],
					'y.e.',
					$nalichie,
					"http://babyexpert.by/product/".$item['path']					
					));
	
		}

		outputCSV('assets/files/pokupay.csv',$pokupay_list);
		echo json_encode("Прайс Pokupay обновлен! <br/><a href='http://babyexpert.by/assets/files/pokupay.csv'>Ссылка</a> для скачивания. <br/>http://babyexpert.by/assets/files/pokupay.csv");
	} 	

	
	public function savesitemapAction() {
				
		$homepage = file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/sitemap_xml'); 
		file_put_contents('sitemap.xml',$homepage);
		echo json_encode("Фаил sitemap.xml успешно обновлен");
	}
	
	//Получение данных префикса по id в форму для редактирования раздела
	public function getPrefixIdAction() {
	  
		$prefix = array();
			
		$prefix = Prefix::getPrefixByIdCatalog($_POST['id']);
			
		echo json_encode($prefix);
		
	}   
  
	//Получение данных по id в форму для редактирования раздела
	public function datatreehandlingAction() {
	  
		$trees = array();
			
		$trees = Tree::getTreeByID($_POST['id']);
			
		$trees['language'] = Tree_language::getTreeByIdLng($trees['id']);
		
		echo json_encode($trees);
		
	}   
 
	//Получение данных по id в форму для редактирования ДЕРЕВА
	public function nextitemtreeAction() {
  
		$item = Tree::getNextId();
		
		$id_tree = $item['auto_increment'];					
			
		echo $id_tree;
	
	}
 
	//Получение данных по id в форму для редактирования товара
	public function datahandlingAction() {
  
		$catalog = array();
		
		$catalog = Catalog::getCollectionByID($_POST['id']);
			
		$catalog['language'] = Catalog_language::getCollectionItem($catalog['id']);
		
		$catalog['formcreate'] = formcreate($catalog['id_char'], $catalog['id']);;
			
		echo json_encode($catalog);
	
	}
	
	//Получение данных по id в форму для редактирования изображения
	public function nextitemcatalogAction() {
  
		$items = array();//Catalog::getNextId();
		
		if (@$_POST['id_characteristics']) $items['formcreate'] = formcreate($_POST['id_characteristics'], @$items['auto_increment']);		
			
		if (@$_POST['id_prefix']) $items['prefix'] = create_prefix_form($_POST['id_prefix'], "add", @$items['auto_increment']);;		
	
		echo json_encode($items);
	
	}	
	
	//Изменение характиристики
	public function changecharAction() {
  
		$items = array();
		
		if ((@$_POST['id_char']) and (@$_POST['id'])) $items['formcreate'] = formcreate($_POST['id_char'], $_POST['id']);		

		echo json_encode($items);
	
	}	
 
    public function loadPSAction() {
	
		// Начало формирование объекта
		$data = array();
		$params = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
	
		$params['tip_catalog']  = (@$_GET['id']) ? @$_GET['id'] : 1;	
		$params['sort'] 		= $sidx.'-'.$sord;

		$count = Catalog::getPodbor($params,"",FALSE);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		$productions = Catalog::getPodbor($params, $data['limit'], TRUE);
	
		$i = 0;
		foreach($productions as $item) {

			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['name'];			
			$i++;

		}

		//header("Content-type: text/script;charset=utf-8");

		echo json_encode($data);
	} 

    public function loadcategoryAction() {
	
		// Начало формирование объекта
		$data = array();
	
		$id_catalog = (@$_POST['id_catalog']) ? @$_POST['id_catalog'] : 1; 

		$catalog_categorys = Catalog_category::getCollections(@$id_catalog);
		$catalog = Catalog::getCollectionById(@$id_catalog);
		
		$i = 0;
		foreach($catalog_categorys as $item) {

			if (!empty($item['id_razdel5'])) $name = Tree::getTreeByID($item['id_razdel5']); 
				else if (!empty($item['id_razdel4'])) $name = Tree::getTreeByID($item['id_razdel4']);
					else if (!empty($item['id_razdel3'])) $name = Tree::getTreeByID($item['id_razdel3']);
						else if (!empty($item['id_razdel2'])) $name = Tree::getTreeByID($item['id_razdel2']);
							else if (!empty($item['id_razdel1'])) $name = Tree::getTreeByID($item['id_razdel1']);
							
			$data[$i]['no'] = $i;
			$data[$i]['id_razdel0'] = $item['id_razdel0'];	
			$data[$i]['id_razdel1'] = $item['id_razdel1'];
			$data[$i]['id_razdel2'] = $item['id_razdel2'];
			$data[$i]['id_razdel3'] = $item['id_razdel3'];
			$data[$i]['id_razdel4'] = $item['id_razdel4'];
			$data[$i]['id_razdel5'] = $item['id_razdel5'];
			$data[$i]['name'] = $name['name'];
			$data[$i]['formcreate'] = formcreate($catalog['id_char'], $id_catalog);
			$data[$i]['prefix'] = create_prefix_form($name['id'], "edit", $id_catalog);
			$i++;

		}
		

		echo json_encode($data);
	} 		

    public function idcharAction() {
	
		// Начало формирование объекта
		$data = array();
		$count = 1;

		for ($i=1;$i<3000;$i++) {
		
		$catalog_categorys = Catalog_category::getCollections($i);
		$k = 0;
			foreach($catalog_categorys as $item) {

				
			
				if (!empty($item['id_razdel5'])) $name = Tree::getTreeByID($item['id_razdel5']); 
					else if (!empty($item['id_razdel4'])) $name = Tree::getTreeByID($item['id_razdel4']);
						else if (!empty($item['id_razdel3'])) $name = Tree::getTreeByID($item['id_razdel3']);
							else if (!empty($item['id_razdel2'])) $name = Tree::getTreeByID($item['id_razdel2']);
								else if (!empty($item['id_razdel1'])) $name = Tree::getTreeByID($item['id_razdel1']);
					
				$data[$k]['id_char'] = @$name['characteristic'];
				$k++;

			}
			if (isset($data[0]['id_char'])) {
				Catalog::updateCollectionChar(array(
					'id' => $i,  		  
					'id_char' => $data[0]['id_char']				
				));
			}
		
		}

	
	} 	

}