<?php

class Catalog_3d_Controller {
	private $_config, $_content;

	public function __construct() {
		Load::model('Catalog_3d');
		$this->_config = Config::getParam('modules->catalog_3d');
	}

	
	public function defaultAction() {
		
	}
	
    public function loadAction() {
		// Начало формирование объекта
		$html = array();
			
		$id = @$_POST['3d_id'];      		//Значение раздела или подраздела
		
		if (isset($id)) {

			$data = Catalog_3d::get3dFileByIdCatalog($id);

		} 
	
		if (@$data) {
		$html['id'] = $data['id'];
		$pyt = $this->_config['swf']['path'].$data['swf'];
		$html['picture'] = '<object type="application/x-shockwave-flash" data="/'.$pyt.'" width="430" height="360"><param name="wmode" value="transparent" /><param name="movie" value="/'.$pyt.'" /></object>';
		}
		echo json_encode($html);
	}
	
	public function editAction() {
		
		// Закачать swf файл
		if (isset($_POST['edit3d']))  {
			$catalog_3d = Catalog_3d::get3dFileByID($_POST['id_3d']);
			if (isset($_FILES['swf']['tmp_name'])) {
				$copyswf = 0;	
				$uploaddir = $this->_config['swf']['path'];
				$filename = time().create_file_name($_FILES['swf']['name']);
				$uploadfile = $uploaddir.basename($filename);
				// Копируем файл из каталога для временного хранения файлов:
				if (!empty($_FILES['swf']['tmp_name'])) {
					$copyswf = copy($_FILES['swf']['tmp_name'], $uploadfile);
				}
				
				if ($copyswf) {
					if (@$_POST['id_3d']) {
						Catalog_3d::update3dFile(array(
							'id' => $_POST['id_3d'],
							'id_catalog' => $_POST['id_catalog'],
							'swf' => $filename	
						));	
					@unlink(ROOT."/".$uploaddir.$catalog_3d['swf']);						
					} else {
						Catalog_3d::add3dFile(array(
							'id_catalog' => $_POST['id_catalog'],
							'swf' => $filename	
						));						
					}
		
				}
			}
		echo '<object type="application/x-shockwave-flash" data="/'.$uploadfile.'" width="430" height="360"><param name="wmode" value="transparent" /><param name="movie" value="/'.$uploadfile.'" /></object>';

		}
	
		// УДАЛИТЬ изображение
		if (isset($_POST['del3d']))  {
			Catalog_3d::remove3dFile($_POST['id_3d']);
		}
	}

	
}