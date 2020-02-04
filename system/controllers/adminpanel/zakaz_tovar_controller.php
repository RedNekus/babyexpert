<?php

class Zakaz_tovar_Controller {
	private $_config, $_content;

	public function __construct() {
		$this->_table = get_table('zakaz');
		$this->_table_client = get_table('zakaz_client');
		$this->_content['title'] = 'Заказы сайта';
		$this->_table_catalog = get_table('catalog');
	}

	public function defaultAction() {
	
		$this->_content['content'] = Render::view('adminpanel/zakaz/list');

		Render::layout('adminpanel/adminpanel', $this->_content);
	   
	}

    public function loadAction() {
		// Начало формирование объекта
		$data = array();
	
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
		$sidx  = @$_GET['sidx'];      // Проще говоря поле, по которому следует производить сортировку									
		$sord  = @$_GET['sord'];      // Направление сортировки
		$host 	= $_SERVER['HTTP_HOST'];		
		$id_client = $_GET['id_client'];
		
		$searchField   = @$_GET['searchField'];	    //имя столбца
		$searchOper    = @$_GET['searchOper'];	    //содержит
		$searchString  = @$_GET['searchString'];	//искомое слово		
		
		if (isset($_GET['predzakaz'])) $predzakaz_sql = " and predzakaz = ".$_GET['predzakaz']; else $predzakaz_sql = "";
		if (isset($_GET['rezerv'])) $rezerv_sql = " and rezerv = ".$_GET['rezerv']; else $rezerv_sql = "";
		$where = "id_client = $id_client $predzakaz_sql $rezerv_sql";
		$count = Database::getCount($this->_table,$where);  
		
		$data = getPaginationAdmin($count,$limit,$page);
		
		if (@$searchField) $items = Database::searchAdmin($this->_table, $searchField, $searchOper, $searchString);
		else $items = Database::getRows($this->_table, $sidx, $sord, $data['limit'],$where);
		
		$i = 0;
		$suma_usd = 0;
		$suma_bur = 0;
		$kolvo = 0;
		$sum_total_cena = 0;
		$sum_total_cena_blr = 0;
		foreach($items as $zakaz) {
			
			$product = Database::getRow($this->_table_catalog,$zakaz['id_item']);

			if (isset($product['name'])) {
			
				$name = get_product_name($product,true,$zakaz['name_tovar']);
					
				if (!empty($zakaz['skidka']) or !empty($zakaz['skidka_procent']) or !empty($zakaz['doplata']) or !empty($zakaz['dostavka'])) {
					$b = '<b>';
					$bb = '</b>';
				} else {
					$b = '';
					$bb = '';					
				}

					
				$suma_usd += with_skidka($zakaz);
				$suma_bur += with_skidka($zakaz,'bur');	
				
				$kolvo += $zakaz['kolvo'];
				
				$total_cena_usd = with_skidka($zakaz);
				$total_cena_bur = with_skidka($zakaz,'bur');
				
				$sum_total_cena += $total_cena_usd;
				$sum_total_cena_blr += $total_cena_bur;

				$status = get_status_zakaz($zakaz);
				
				$be = '<div" rel="'.$zakaz['id'].'" class="button edit editdata"></div>';

				$data['rows'][$i]['id'] = $zakaz['id'];
				$data['rows'][$i]['cell'][] = $be;				
				$data['rows'][$i]['cell'][] = $b.'<a href="http://'.$host.'/product/'.$product['path'].'" target="_ablank">'.$name.'</a>'.$bb;		
				$data['rows'][$i]['cell'][] = $zakaz['kolvo'];		
				$data['rows'][$i]['cell'][] = $zakaz['cena'];			
				$data['rows'][$i]['cell'][] = $zakaz['cena_blr'];			
				$data['rows'][$i]['cell'][] = $total_cena_usd;			
				$data['rows'][$i]['cell'][] = $total_cena_bur;					
				$data['rows'][$i]['cell'][] = $status;			
				$i++;
				
			}
		}
		
		$data['userdata']['kolvo'] = $kolvo;
		$data['userdata']['cena'] = $suma_usd;
		$data['userdata']['cena_blr'] = $suma_bur;
		$data['userdata']['summa'] = $sum_total_cena;
		$data['userdata']['summa_blr'] = $sum_total_cena_blr;
		
		echo json_encode($data);
	}  
  
	public function editAction() {
		
		$data = array();
		
		// ДОБАВИТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="add") {
			
			if (!empty($_POST['id_item'])) {
				$id_item = $_POST['id_item'];
			} elseif (!empty($_POST['name'])) {
				$id_item = add_new_product($_POST['name'],$_POST['cena']);
			}
			
			$product = Database::getRow($this->_table_catalog,$id_item);		
			if (empty($product)) {
				$data['msg'] = 'Данные не сохранены!';
				echo json_encode($data);				
				return;
			}
			$name_tovar = $product['name'];
				
			if (isset($_POST['predzakaz'])) {
				$predzakaz = 1;
			} else {
				if ((get_free_ostatok($id_item) >= $_POST['kolvo']) and (get_free_ostatok($id_item) > 0)) {
					$predzakaz = 0;
				} else {
					$predzakaz = 1;
					$data['msg'] = 'Товара нет на складе, он автоматически перемещается в предзаказ!';
				}
			}
			
			if ((isset($_POST['rezerv'])) and (get_free_ostatok($id_item)>0)) {
				$rezerv = 1;
				$date_rezerv = $_POST['date_rezerv'];
			} else {
				if (isset($_POST['rezerv'])) $data['msg'] = 'Нельзя зарезервировать товар, которого нет на складе!';
				$rezerv = 0;
				$date_rezerv = '';
			}
			
			$gifts = array();
			foreach(explode(',podarokId',$product['podarok']) as $elem) {
				if (!empty($elem)) $gifts[] = Database::getRow($this->_table_catalog,$elem);
			}
			
			$data_zakaz = array(  
				'id_client' => $_POST['id_client'],
				'id_catalog' => $id_item,
				'id_item' => $id_item,
				'name_tovar' => $name_tovar,
				'nomer_zakaza' => $_POST['nomer_zakaza'],
				'active' => 1,
				'cena' => $product['cena'],
				'cena_blr' => transform_to_blr($product,false),
				'kolvo' => $_POST['kolvo'],
				'skidka' => $_POST['skidka'],
				'skidka_procent' => $_POST['skidka_procent'],
				'doplata' => $_POST['doplata'],
				'dostavka' => $_POST['dostavka'],				
				'raffle' => $product['raffle'],
				'id_gift' => @$gifts[0]['id'],
				'rezerv' => $rezerv,
				'date_rezerv' => $date_rezerv,
				'predzakaz' => $predzakaz,
				'id_postavshik' => $_POST['id_postavshik'],
				'date_predzakaz' => $_POST['date_predzakaz'],
			);	
			
			if (isset($_POST['active_predzakaz'])) $data_zakaz['active_predzakaz'] = 1;
			
			Database::insert($this->_table,$data_zakaz);	
					
		}
		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
			$id_item = $_POST['id_item'];
			
			$zakaz = Database::getRow($this->_table,$_POST['id']);
			$kolvo = $_POST['kolvo'];
			$z_kolvo = $zakaz['kolvo'];
			$so = get_free_ostatok($id_item);

			if (isset($_POST['predzakaz'])) {
				$predzakaz = 1;
			} else {
				if (($so >= $kolvo) and ($so>0)) $predzakaz = 0; else $predzakaz = 1;										
				
				$client = Database::getRow($this->_table_client,$zakaz['id_client']);
				if ($client['dostavka']==1) {
					if ($z_kolvo < $kolvo) {
						if ($zakaz['predzakaz']!=1) {
							if ($so >= $kolvo - $z_kolvo) $predzakaz = 0; else $predzakaz = 1;									
						}
					} elseif ($z_kolvo == $kolvo) {
						if ($zakaz['predzakaz']!=1) $predzakaz = 0;
					} else {
						if ($zakaz['predzakaz']!=1) {
							if ($z_kolvo + $so >= $kolvo) $predzakaz = 0; else $predzakaz = 1;							
						}
							
					}
				}

				if ($predzakaz == 1) $data['msg'] = 'Товара нет на складе, он автоматически перемещается в предзаказ!';

			}
			
			if ((isset($_POST['rezerv'])) and (get_free_ostatok($id_item)>0)) {
				$rezerv = 1;
				$date_rezerv = $_POST['date_rezerv'];
			} else {
				if (isset($_POST['rezerv'])) $data['msg'] = 'Нельзя зарезервировать товар, которого нет на складе!';
				$rezerv = 0;
				$date_rezerv = '';
			}
		
			$data_zakaz = array(  
				'id_catalog' => $id_item,
				'id_item' => $id_item,
				'kolvo' => $kolvo,	
				'skidka' => $_POST['skidka'],
				'skidka_procent' => $_POST['skidka_procent'],
				'doplata' => $_POST['doplata'],
				'dostavka' => $_POST['dostavka'],				
				'rezerv' => $rezerv,
				'date_rezerv' => $date_rezerv,
				'predzakaz' => $predzakaz,
				'id_postavshik' => $_POST['id_postavshik'],
				'date_predzakaz' => $_POST['date_predzakaz'],
				'nosell' => (isset($_POST['nosell']) ? 1 : 0)
			);	

			if ($id_item != $zakaz['id_item']) {
						
				$product = Database::getRow($this->_table_catalog,$id_item);		
				if (empty($product)) {
					$data['msg'] = 'Данные не сохранены!';
					echo json_encode($data);				
					return;
				}
				$name_tovar = $product['name'];			
				$data_zakaz['name_tovar'] = $name_tovar;

			}
			
			if (isset($_POST['active_predzakaz'])) $data_zakaz['active_predzakaz'] = 1;

			if ($zakaz['delivered']==1 or $zakaz['shipped']==1 ) {
				$data['msg'] = 'Товар доставлен или отгружен, данные не сохранены!';
			} else {
				$where = "id = ".$_POST['id'];
				Database::update($this->_table,$data_zakaz,$where);
			}
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			$zakaz = Database::getRow($this->_table,$_POST['del_id']);
			$zakaz_client = Database::getRow($this->_table_client,$zakaz['id_client']);
			if ($zakaz_client['dostavka'] == 0)	Database::delete($this->_table,$_POST['del_id']);
			else {
				$data['msg'] = 'Данные не сохранены, заказ стоит на доставку!';
			}
		}
		echo json_encode($data);
	}
  
	//Получение данных по id в форму для добавления товара
	public function openAction() {
		$data = array();
		$data = Database::getRow($this->_table,$_POST['id']);
		$data['html'] = get_table_sklad_tovar($data['id_item']);
		echo json_encode($data);
	}  
	
}