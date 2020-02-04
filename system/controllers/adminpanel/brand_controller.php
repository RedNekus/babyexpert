<?php

class Brand_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(array('Brand', 'Brand_language', 'Maker', 'Tree', 'Catalog'));
		$this->_config = Config::getParam('modules->brand');
		$this->_content['title'] = 'Бренд разделов';
	}

	
	public function defaultAction() {
		$this->listAction();
	}
  
  
	public function listAction() {
  
		$trees  = Tree::getTrees();
	
		$this->_content['content'] = Render::view(
				'adminpanel/brand/list', array (
				'trees' => get_tree($trees, 0),
				'id_group' => Brand::getCollectionByID(1)
				)
			);
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
	
		$id = (@$_GET['id']) ? @$_GET['id'] : 1;
		
		$count = Brand::getTotalCollections($id);
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) {
			$productions = Brand::searchAdmin($searchField, $searchString);
		} else {
			$productions = Brand::getCollections($id,$sidx, $sord, $data['limit']);
		}
			$i = 0;
			foreach($productions as $item) {

				$data['rows'][$i]['id'] = $item['id'];
				$data['rows'][$i]['cell'][] = $item['id'];
				$data['rows'][$i]['cell'][] = $item['name'];
				$data['rows'][$i]['cell'][] = $item['title'];
				$data['rows'][$i]['cell'][] = $item['keywords'];					
				$data['rows'][$i]['cell'][] = $item['description'];					
				$i++;

				}			
	
		
		echo json_encode($data);
		
	}  

    
	public function editAction() {

	/********************************************************/
	/*			Редатирование модуля бренд  				*/
	/********************************************************/
		
		// Добавить элемент в таблицу
		if (isset($_POST['action_group']) and $_POST['action_group']=="add")  {

			Brand::addbrand(array(
				'id_catalog_tree' => $_POST['id_catalog_tree'],
				'id_maker' => $_POST['id_maker'], 
				'name' => $_POST['name'], 
				'title' => $_POST['title'],					
				'keywords' => $_POST['keywords'],					
				'description' => $_POST['description'], 
				'short_description' => $_POST['short_description']				
			));	
			
			$id_brand_lng = Brand::getLastId();	
		
			Brand_language::addBrand(array(
				'id_brand_lng' => $id_brand_lng,
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'],
				'short_description_lng' => $_POST['short_description_lng'],				
				'title_lng' => $_POST['title_lng'],
				'keywords_lng' => $_POST['keywords_lng'],
				'description_lng' => $_POST['description_lng']
			));	
		}	

		// Редактировать элемент в таблице
		if (isset($_POST['action_group']) and $_POST['action_group']=="edit")  {

			Brand::updatebrand(array(
				'id'  => $_POST['id'],
				'id_catalog_tree' => $_POST['id_catalog_tree'],
				'id_maker' => $_POST['id_maker'], 
				'name' => $_POST['name'], 
				'title' => $_POST['title'],					
				'keywords' => $_POST['keywords'],					
				'description' => $_POST['description'], 
				'short_description' => $_POST['short_description']					
			));
			
			Brand_language::updateBrand(array(
				'id_lng' => $_POST['id_lng'],
				'id_brand_lng' => $_POST['id'],
				'id_language' => 2,
				'name_lng' => $_POST['name_lng'],
				'short_description_lng' => $_POST['short_description_lng'],				
				'title_lng' => $_POST['title_lng'],
				'keywords_lng' => $_POST['keywords_lng'],
				'description_lng' => $_POST['description_lng']
			));
		}

		// Удалить элемент из таблицы
		if (isset($_POST['del_id_brand']))  {
			Brand::removeCollection($_POST['del_id_brand']);
			Brand_language::removeBrand($_POST['del_id_brand']);
		}
	
	}
  
 
	//Получение данных по id в форму для редактирования товара
	public function datahandlingAction() {
  
		$brand = array();
		
		$brand = Brand::getCollectionByID($_POST['id']);
		
		$brand['language'] = Brand_language::getBrandByIdLng($_POST['id']);
		
		$brand['maker'] = create_maker_form($_POST);
		
		echo json_encode($brand);
	
	}
 
	//Получение данных по id в форму для редактирования изображения
	public function nextitemcatalogAction() {
  
		$items = array();
	
		if (@$_POST['id_razdel']) $items['maker'] = create_maker_form($_POST);		
	
		echo json_encode($items);
	
	}	
 
	//Получение данных по id в форму для редактирования товара
	public function brandAction() {
  
		$trees  = Tree::getTrees();
		foreach($trees as $tree) {
			$makers = Catalog::getIdMakerByIdRazdel($tree['id']);
			foreach ($makers as $maker) {
				if (@$maker['id']) {
					$name = $tree['name'].' '.@$maker['name'];
					Brand::addbrand(array(
						'id_catalog_tree' => $tree['id'],
						'id_maker' => $maker['id'], 
						'name' => $name, 
						'title' => $name,					
						'keywords' => $name,					
						'description' => $name, 
						'short_description' => $name				
					));	
			
					$id_brand_lng = Brand::getLastId();	
				
					Brand_language::addBrand(array(
						'id_brand_lng' => $id_brand_lng,
						'id_language' => 2,
						'name_lng' => $name,
						'short_description_lng' => $name,				
						'title_lng' => $name,
						'keywords_lng' => $name,
						'description_lng' => $name
					));
					
				}
			}
		}
	
	} 
}