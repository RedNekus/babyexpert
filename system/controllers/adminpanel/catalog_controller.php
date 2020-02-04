<?php

class Catalog_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array(
				'Catalog', 
				'Catalog_category',
				'Catalog_language',				
				'Catalog_characteristics',
				'Raffle',
				'Currency_tree', 				 
				'Adminusers_stats', 
				'Tree', 
				'Tree_language', 
				'Maker', 
				'Images', 
				'Images_language', 
				'Prefix', 
				'Characteristics', 
				'Characteristics_tree', 
				'Characteristics_group', 
				'Characteristics_group_tip'
			)
		);
		$this->_config = Config::getParam('modules->catalog');
		$this->_table = get_table('catalog');
		$this->_content['title'] = 'Каталог товаров';
	}

	
	public function defaultAction() {
		$trees  = Tree::getTrees();
		$total_maker = Maker::getTotalMakers();
		$makers  = Maker::getMakers("name","asc",$total_maker);
		$currency  = Currency_tree::getTreesByPid(1);
		$characteristics = Characteristics_tree::getTrees();
		$product_week = Database::getRows(get_table('product_week'),'id','asc',false,'active = 1');

		$this->_content['content'] = Render::view(
				'adminpanel/catalog/list', array (
				'trees' => get_tree($trees, 0),
				'razdels' => Database::getRows(get_table('catalog_tree'), 'name','asc', false,'pid=1'),
				'makers' => $makers,
				'access' => get_array_access(),
				'currency' => $currency,
				'img_size' => $this->_config['image']['small'],
				'characteristics' => $characteristics,
				'product_week' => $product_week
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

		$where = '(`id_razdel0` = '.$valuerazdel.' or
		  `id_razdel1` = '.$valuerazdel.' or
		  `id_razdel2` = '.$valuerazdel.' or
		  `id_razdel3` = '.$valuerazdel.' or
		  `id_razdel4` = '.$valuerazdel.' or
		  `id_razdel5` = '.$valuerazdel.')';
		
		$table = get_table('catalog').' as t1 JOIN '.get_table('catalog_categories').' as t2 ON t1.id = t2.id_catalog';
		//Правка для сортировки
		$table .= ' JOIN '.get_table('maker').' as t3 ON t1.id_maker = t3.id';
		$table .= ' JOIN '.get_table('prefix').' as t4 ON t1.id_prefix = t4.id';
		if($sidx == 'id_maker') {
			$sidx = 'maker_name';
		} else if($sidx == 'name') {
			$sidx = 'product_name';
		} else {
			$sidx = 't1.'.$sidx;
		}
		//конец правки
		
		if (isset($_GET['tovar_nedeli'])) { $where .= ' and tovar_nedeli > 0'; }
		
		if (@$searchField) {
			$count = Catalog::getTotalSearchAdmin($searchField, $searchString);  
		} else {
			$count = Catalog::getTotalCollections($valuerazdel);
		}
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Catalog::searchAdmin($searchField, $searchString,$data['limit']);
		else $items = Database::getRows($table,'t1.active desc, '.$sidx,$sord,$data['limit'],$where,'t1.id', 't1.*, t3.name as maker_name, CONCAT(t4.name, \' \', t3.name, \' \', t1.name) as product_name');		
		$i = 0;
		foreach($items as $item) {
			$cena = $item['cena'];
			$cena_blr = transform_to_blr($item,false);
			if ($cena > 0) $kurs = $cena_blr / $cena;
			//var_dump($item);
			$data['rows'][$i]['id'] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['id'];
			$data['rows'][$i]['cell'][] = $item['maker_name'];//Database::getField(get_table('maker'),$item['id_maker']);
			$data['rows'][$i]['cell'][] = $item['product_name'];//get_product_name($item,true);
			$data['rows'][$i]['cell'][] = '<a href="/product/'.$item['path'].'" target=_ablank>просмотр</a>';
			$data['rows'][$i]['cell'][] = @$cena;
			$data['rows'][$i]['cell'][] = @$cena_blr;
			$data['rows'][$i]['cell'][] = $item['cena_blr'];
			$data['rows'][$i]['cell'][] = @$kurs;
			$data['rows'][$i]['cell'][] = (($item['new']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = (($item['hit']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = (($item['spec_pred']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = (($item['active']!=0) ? 'Да' : 'Нет');
			$data['rows'][$i]['cell'][] = $item['prioritet'];			
			$i++;

		}

		echo json_encode($data);
	}  
  
	public function editAction() {
  
/********************************************************/
/*				Редатирование модуля товаров			*/  
/********************************************************/  
	
		if (isset($_POST['action']) and in_array($_POST['action'],array('add','edit'))) {
			
			$prefix_name = '';
			$maker_name = '';
			if (!empty($_POST['id_prefix'])) {
				$prefix_name = Database::getField(get_table('prefix'), $_POST['id_prefix']) . ' ';
			}
			if (!empty($_POST['id_maker'])) {
				$maker_name = Database::getField(get_table('maker'), $_POST['id_maker']) . ' ';
			}
			
			if (!empty($_POST['path'])) { 
				$path = $_POST['path'];
			}else {
				$path = translit_path($prefix_name . $maker_name . $_POST['name']);	
			}	

			if (!empty($_POST['title'])) $title = $_POST['title'];	
			else $title = $prefix_name . $maker_name . $_POST['name'].' купить в Минске по низким ценам с фото';
					
			if (!empty($_POST['description'])) $description = $_POST['description'];	
			else $description = 'Приобрести ' . $prefix_name . $maker_name . $_POST['name'].' модель по очень выгодным ценам с бесплатной доставкой по Минску Вы сможете у нас в магазине. Профессиональные консультации и реальное наличие только у нас!';

			if ($_POST['cena_blr'] != 0) {
				$cena_blr_site = $_POST['cena_blr'];
			} else {
				$kurs = Database::getField(get_table('currency_tree'),$_POST['id_currency'],'id','kurs');
				$cena_blr_site = $_POST['cena'] * $kurs;
			}			
				
			$data = array(  
				'title' => $title,
				'keywords' => $_POST['keywords'],					
				'description' => $description,					
				'name' => $_POST['name'],			  
				'cena' => $_POST['cena'],		  
				'cena_old' => $_POST['cena_old'],		  		  
				'id_currency' => $_POST['id_currency'],		  
				'cena_blr' => $_POST['cena_blr'],
				'cena_blr_old' => $_POST['cena_blr_old'],
				'cena_blr_site'=> $cena_blr_site,				
				'pw_cena' => $_POST['pw_cena'],
				'pw_cena_blr' => $_POST['pw_cena_blr'],
				'new' => ((isset($_POST['new'])) ? 1 : 0),
				'hit' => ((isset($_POST['hit'])) ? 1 : 0),
				'spec_pred' => ((isset($_POST['spec_pred'])) ? 1 : 0), 		  		  
				'active' => ((isset($_POST['active'])) ? 1 : 0),
				'tovar_nedeli' => $_POST['tovar_nedeli'],
				'expert' => ((isset($_POST['expert'])) ? 1 : 0),				
				'show_opt' => ((isset($_POST['show_opt'])) ? 1 : 0),				
				'no_active_color' => ((isset($_POST['no_active_color'])) ? 1 : 0),				
				'raffle' => $_POST['raffle'],				
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
				'vid_complect' => $_POST['vid_complect'],
				'video_url' => $_POST['video_url'],
				'status' => $_POST['status'],
				'importer' => $_POST['importer'],
				'serv_centr' => $_POST['serv_centr'],
				'cost_dostavka' => $_POST['cost_dostavka'],
				'srok_city' => $_POST['srok_city'],
				'srok_country' => $_POST['srok_country'],
				'cena_rozn_usd' => $_POST['cena'],
				'cena_rozn_blr' => (!empty($_POST['cena_blr']) ? $_POST['cena_blr'] : $_POST['cena'] * get_kurs()),
				'cena_rozn1_usd' => $_POST['cena_rozn1_usd'],
				'cena_rozn1_blr' => $_POST['cena_rozn1_blr'],
				'cena_diler1_usd' => $_POST['cena_diler1_usd'],
				'cena_diler1_blr' => $_POST['cena_diler1_blr'],
				'cena_diler2_usd' => $_POST['cena_diler2_usd'],
				'cena_diler2_blr' => $_POST['cena_diler2_blr'],	
				'cena_diler3_usd' => $_POST['cena_diler3_usd'],
				'cena_diler3_blr' => $_POST['cena_diler3_blr'],								
			);	
			
		}
	
		// ДОБАВИТЬ элемент в таблицу
		if (isset($_POST['action']) and $_POST['action']=="add") {
		
			Database::insert($this->_table,$data);
			
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
				'created' => date('Y-m-d'),
				'created_time' => date('H:i:s')
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
					
			$id_catalog = $_POST['id'];			
			$where = "id = $id_catalog";
				
			if (!empty($_POST['description'])) $data['description'] = $_POST['description'];	
			else $data['description'] = $_POST['name'].' - '.get_char_description($id_catalog);

			Database::update($this->_table,$data,$where);
	
			if ($data['vid_complect'] == 8) {
				$colors = getColorsByIdCatalog($id_catalog);
				if (!empty($colors)) {
					$data_complect = array(
						'cena' => $data['cena'],		  
						'cena_old' => $data['cena_old'],		  		  
						'id_currency' => $data['id_currency'],		  
						'cena_blr' => $data['cena_blr'],
						'cena_blr_old' => $data['cena_blr_old'],
						'cena_blr_site'=> $data['cena_blr_site'],							
					);
					Database::update($this->_table,$data_complect,$where);
				}				
			}
	
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
				'created' => date('Y-m-d'),
				'created_time' => date('H:i:s')
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
				'created' => date('Y-m-d'),
				'created_time' => date('H:i:s')
			));			
		}
		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['oper']) and $_POST['oper']=="edit") {

			/*if ($_POST['cena_blr'] != 0) {
				$cena_blr_site = $_POST['cena_blr'];
			} else {
				$product = Database::getRow(get_table('catalog'),$_POST['id']);
				$kurs = Database::getField(get_table('currency_tree'),$product['id_currency'],'id','kurs');
				$cena_blr_site = $_POST['cena'] * $kurs;
			}	*/		
							
			Catalog::updateCollectionFromTable(array(
				'id' => $_POST['id'],  		  
				'cena' => $_POST['cena'],
				'cena_blr' => $_POST['cena_blr'],
				'new' => ($_POST['new']=='Да') ? 1 : 0,
				'hit' => ($_POST['hit']=='Да') ? 1 : 0,
				'spec_pred' => ($_POST['spec_pred']=='Да') ? 1 : 0,
				'active' => ($_POST['active']=='Да') ? 1 : 0
				));	
			
			Adminusers_stats::addStats(array(
				'id_catalog' => $_POST['id'],
				'id_adminusers' => $_SESSION['isLoggedIn']['id'],
				'action' => "edit",
				'created' => date('Y-m-d'),
				'created_time' => date('H:i:s')
			));
							
		}
	
		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id_catalog']))  {
			$del_id_catalog = $_POST['del_id_catalog'];
			
			Catalog::removeCollection($del_id_catalog);
			Catalog_category::clearCategoriesById($del_id_catalog);
			Images::removeImagesByIdCatalog($del_id_catalog);
			
			Adminusers_stats::addStats(array(
				'id_catalog' => $del_id_catalog,
				'id_adminusers' => $_SESSION['isLoggedIn']['id'],
				'action' => "del",
				'created' => date('Y-m-d'),
				'created_time' => date('H:i:s')
			));		  
		}	
		
		// КОПИРОВАТЬ элемент в таблицы
		if (isset($_POST['copy_id_catalog']))  {
			$id_catalog = $_POST['copy_id_catalog'];
			copy_table_to_catalog($id_catalog);	  
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
				'cost_dostavka' => $_POST['cost_dostavka'],
				'characteristic' => $_POST['characteristic'],
				'short_description' => $_POST['short_description'],
				'description_app' => $_POST['description_app'],
				'title' => $_POST['title'],	
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description'],
				'show_banner' => ((isset($_POST['show_banner'])) ? 1 : 0),
				'show_opt' => ((isset($_POST['show_opt'])) ? 1 : 0),
			));	

			$id_tree_lng = Tree::getLastId();	
		
			Tree_language::addTree(array(
				'id_tree_lng' => $id_tree_lng,
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'],
				'namefull_lng' => $_POST['namefull_lng'],
				'short_description_lng' => $_POST['short_description_lng'],				
				'description_app_lng' => $_POST['description_app_lng'],				
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
				'cost_dostavka' => $_POST['cost_dostavka'],
				'characteristic' => $_POST['characteristic'],
				'short_description' => $_POST['short_description'],
				'description_app' => $_POST['description_app'],
				'title' => $_POST['title'],	
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description'],
				'show_banner' => ((isset($_POST['show_banner'])) ? 1 : 0),
				'show_opt' => ((isset($_POST['show_opt'])) ? 1 : 0),				
			));		

			if (empty($_POST['id_lng'])) {
				Tree_language::addTree(array(
					'id_tree_lng' => $_POST['id'],
					'id_language' => 2,
					'name_lng' => $_POST['name_lng'],
					'namefull_lng' => $_POST['namefull_lng'],
					'short_description_lng' => $_POST['short_description_lng'],				
					'description_app_lng' => $_POST['description_app_lng'],				
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
					'description_app_lng' => $_POST['description_app_lng'],				
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

	public function savepriceAction() {

		save_price($_POST['action'],$this->_config['image']['big']['path']);
	
	} 	
	
	public function savesitemapAction() {
				
		$homepage = file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/sitemap_xml'); 
		file_put_contents('sitemap.xml',$homepage);
		echo json_encode("Фаил sitemap.xml успешно обновлен");
	}
		
	public function saveyandexmapAction() {
				
		$homepage = file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/yml_xml'); 
		file_put_contents('yml.xml',$homepage);
		echo json_encode("Фаил yml.xml успешно обновлен");
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
		
		$catalog['formcreate'] = formcreate($catalog['id_char'], $catalog['id']);
		
		$catalog['rafflecreate'] = raffle_create($catalog['id']);
			
		echo json_encode($catalog);
	
	}
	
	//Получение данных по id в форму для редактирования изображения
	public function nextitemcatalogAction() {
  
		$items = array();//Catalog::getNextId();
		
		if (@$_POST['id_characteristics']) $items['formcreate'] = formcreate($_POST['id_characteristics'], @$items['auto_increment']);		
			
		if (@$_POST['id_prefix']) $items['prefix'] = create_prefix_form($_POST['id_prefix'], "add", @$items['auto_increment']);

		$items['rafflecreate'] = raffle_create(@$items['auto_increment']);		
	
		echo json_encode($items);
	
	}	
	
	//Изменение характиристики
	public function changecharAction() {
  
		$items = array();
		
		if ((@$_POST['id_char']) and (@$_POST['id'])) $items['formcreate'] = formcreate($_POST['id_char'], $_POST['id']);		

		echo json_encode($items);
	
	}		
	
	public function reload_pathAction() {
		
		$data_res = array();
		
		if ($_SESSION['isLoggedIn']['id_access']==1) {
			
			$items = Database::getRows($this->_table);
			
			foreach($items as $item) {
				$data = array();
				$id_catalog = $item['id'];
				$where = "id = $id_catalog";

				if (!empty($item['id_prefix'])) $prefix_name = Database::getField(get_table('prefix'),$item['id_prefix']).'-';
				else $prefix_name = '';
					
				$data['path'] = translit_path($prefix_name.$item['name']);	

				Database::update($this->_table,$data,$where);	
				
			}
			
			$data_res['succes'] = true;
			$data_res['message'] = 'URL успешно обновлены!';
			
		} else {
			
			$data_res['succes'] = false;
			$data_res['message'] = 'Отказано в доступе!';			
			
		}
		
		echo json_encode($data_res);

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
			$data['rows'][$i]['cell'][] = get_product_name($item, true);			
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
				elseif (!empty($item['id_razdel4'])) $name = Tree::getTreeByID($item['id_razdel4']);
					elseif (!empty($item['id_razdel3'])) $name = Tree::getTreeByID($item['id_razdel3']);
						elseif (!empty($item['id_razdel2'])) $name = Tree::getTreeByID($item['id_razdel2']);
							elseif (!empty($item['id_razdel1'])) $name = Tree::getTreeByID($item['id_razdel1']);
							
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
	
    /*public function add_complect_colorAction() {
		
		$items = Database::getSQL('SELECT * FROM `np_catalog_colors` WHERE 1 GROUP BY `id_catalog`');
		$ids = '0';
		foreach($items as $item) {
			$ids .= ','.$item['id_catalog'];
		}
		$products = Database::getRows(get_table('catalog'),'id','asc',false,'id IN ('.$ids.') and vid_complect <> 8');
		foreach($products as $product) {

			$colors = Database::getRows(get_table('colors'),'id','asc',false,'id_catalog='.$product['id']);
			//if (empty($colors)) continue;
			
			Database::update(get_table('catalog'),['vid_complect'=>8],'id='.$product['id']);
			
			foreach($colors as $color) {
				if (empty($color['name'])) continue;
				$last_id = copy_table_to_catalog($product['id'],$color['name']);
					
				Images::addImages(array(
					'id_catalog' => $last_id,
					'image' => $color['image'],
					'description' => $color['name'],
					'showfirst' => $color['baza']
				));
				$last_id_lng = Images::getLastId();
				Images_language::addImages(array(
					'id_catalog_lng' => $last_id_lng,
					'id_language' => 2,
					'description_lng' => $color['name']	
				));					
				
				$data = array(
					'type_complect' => 9,
					'kolvo' => 1,
					'id_product' => $last_id,
					'id_catalog' => $product['id']
				);
				
				Database::insert(get_table('catalog_complect'),$data);
				
			}
		}

	}*/
	
	public function check_complectAction() {
		
		$items = Database::getSQL('SELECT * FROM `np_catalog_colors` WHERE 1 GROUP BY `id_catalog`');
		$ids = '0';
		$i = 1;
		foreach($items as $item) {
			$ids .= ','.$item['id_catalog'];
		}
		$products = Database::getRows(get_table('catalog'),'id','asc',false,'id IN ('.$ids.') and no_active_color = 0');
		foreach($products as $product) {

			$colors = Database::getRows('np_catalog_colors','id','asc',false,'active = 0 and id_catalog='.$product['id']);

			foreach($colors as $color) {
				if (empty($color['name'])) continue;
				$name = $product['name'].' '.$color['name'];
				$id = Database::getField(get_table('catalog'),$name,'name','id');
				echo $id.',';
				
				//$data = ['no_active_color' => 1];
				//Database::update(get_table('catalog'),$data,'name = "'.htmlspecialchars($name).'"');
			}
		}
		
	}
	
	/*public function update_descriptionAction() {
		
		$products = Database::getSQL('SELECT * FROM `np_catalog` WHERE 1');
		
		foreach($products as $product) {

			$h1 = get_product_name($product,true);

			$title = $h1 . ' купить в Минске по низким ценам с фото';
			$description = 'Приобрести ' . $h1 . ' модель по очень выгодным ценам с бесплатной доставкой по Минску Вы сможете у нас в магазине. Профессиональные консультации и реальное наличие только у нас!';
			
			$data = array(  
				'title' => $title,					
				'description' => $description
			);
				
			Database::update(get_table('catalog'), $data, 'id = "' . $product['id'] . '"');
			
		}
		
	}*/

}