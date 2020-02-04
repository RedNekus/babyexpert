<?php

class Catalog_images_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model(Array('Images','Images_language','Prefix'));
		$this->_config = Config::getParam('modules->catalog');
	}

	
	public function defaultAction() {
		
	}
	
    public function loadAction() {
		// Начало формирование объекта
		$data = array();
			
		$id = @$_GET['id_catalog'];      		//Значение раздела или подраздела
		
		if (isset($id)) {

			$images = Images::getImagesByIdCatalog($id);
			$colors = getColorsByIdCatalog($id);
			if (!empty($colors)) {
				foreach($colors as $color) {
					$more_images = Images::getImagesByIdCatalog($color['id']);
					$images = array_merge($images,$more_images);
				}
			}
			
			$i = 0;
			
			foreach($images as $item) {
				$data['rows'][$i]['id'] = $item['id'];
				$data['rows'][$i]['cell'][] = '<img src="'.$this->_config['image']['small']['path'].$item['image'].'" style="width: 30px; height: 30px;"/>';
				$data['rows'][$i]['cell'][] = $item['image'];
				$data['rows'][$i]['cell'][] = $item['description'];
				$data['rows'][$i]['cell'][] = (($item['showfirst']!=0) ? 'Да' : 'Нет');			
				$i++;
			}

		} 

		echo json_encode($data);
	}
	
	public function editAction() {
		
		// Добавить изображение
		if (isset($_POST['action_image']) and $_POST['action_image']=="add")  {
			
			if ($_FILES['image']['name']) {

				$prefix_name = '';
				$maker_name = '';
				if (isset($_POST['prefix_id_tovar']) && !empty($_POST['prefix_id_tovar'])) $prefix_name = Database::getField(get_table('prefix'), $_POST['prefix_id_tovar']).'_';
				if (isset($_POST['maker_id_tovar']) && !empty($_POST['maker_id_tovar'])) $maker_name = Database::getField(get_table('maker'), $_POST['maker_id_tovar']) . '_';

				$filename = $prefix_name . $maker_name . @$_POST['name_tovar'] . '_' . @$_POST['description'];
			
				$upload = Database::uploadImage ($_FILES['image'], $this->_config['image'], $filename);
	
			}
	
			if (!$upload['error']) { 

				Images::addImages(array(
					'id_catalog' => $_POST['id_catalog'],
					'image' => $upload['name'],
					'description' => $_POST['description'],
					'showfirst' => ((isset($_POST['showfirst'])) ? 1 : 0)	
				));
				
				$last_id = Images::getLastId();
				
				Images_language::addImages(array(
					'id_catalog_lng' => $last_id,
					'id_language' => 2,
					'description_lng' => $_POST['description_lng']	
				));		
				
			}
			
		}
	
		// Редактировать изображение
		if (isset($_POST['action_image']) and $_POST['action_image']=="edit")  {
			$images = Images::getImagesByID($_POST['id_image']);

			if ($_FILES['image']['name']) {  

				$prefix_name = '';
				$maker_name = '';
				if (isset($_POST['prefix_id_tovar']) && !empty($_POST['prefix_id_tovar'])) $prefix_name = Database::getField(get_table('prefix'), $_POST['prefix_id_tovar']).'_';
				if (isset($_POST['maker_id_tovar']) && !empty($_POST['maker_id_tovar'])) $maker_name = Database::getField(get_table('maker'), $_POST['maker_id_tovar']) . '_';

				$filename = $prefix_name . $maker_name . @$_POST['name_tovar'] . '_' . @$_POST['description'];
			
				$upload = Database::uploadImage ($_FILES['image'], $this->_config['image'],$filename);

			}

			if (!$upload['error']) {
			
				Images::updateImages(array(
					'id' => $_POST['id_image'],
					'id_catalog' => $_POST['id_catalog'],
					'image' => ((isset($upload)) ? $upload['name'] : $images['image']),
					'description' => $_POST['description'],
					'showfirst' => ((isset($_POST['showfirst'])) ? 1 : 0)	
				));
				
				Images_language::updateImages(array(
					'id_lng' => $_POST['id_lng'],
					'id_catalog_lng' => $_POST['id_image'],
					'id_language' => 2,
					'description_lng' => $_POST['description_lng']	
				));				

				if (isset($upload)) {
					//@unlink(ROOT.$this->_config['image']['small']['path'].$images['image']);
					//@unlink(ROOT.$this->_config['image']['big']['path'].$images['image']);
				}
				
			}

		}
	
		// УДАЛИТЬ изображение
		if (isset($_POST['del_id_image']))  {
			Images::removeImages($_POST['del_id_image']);
		}
	}
	
	//Получение данных по id в форму для редактирования изображения
	public function datahandlingimgAction() {
  
		$images = array();
		
		$images = Images::getImagesByID($_POST['id']);
		
		$images['url'] = $this->_config['image']['small']['path'].$images['image'];
		
		$images['language'] = Images_language::getImagesItem($_POST['id']);
			
		echo json_encode($images);
	
	}
	
}