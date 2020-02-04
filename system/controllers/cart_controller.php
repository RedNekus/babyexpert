<?php
class Cart_Controller {
  private $_content, $_config;

	public function __construct() {
	
		$this->_config = Config::getParam('modules->catalog');

		Load::model(array(
				'Catalog', 
				'Zakaz', 
				'Zakaz_client', 
				'Friend_send', 
				'Maker', 
				'Reviews',
				'Images', 
				'Promocode',
				'Characteristics_group', 
				'Characteristics_tree', 
				'Characteristics', 
				'Catalog_characteristics', 
				'Characteristics_group_tip'
			)
		);
		
		$this->_content['left'] = Render::view('catalog/razdel');
															
	}

	public function defaultAction() {
		
		if (!isset($_SESSION['collection'])) header('location: /category/');
	
		$this->_content['content'] = Render::view(
			'cart/list', Array(
				'items' => $_SESSION['collection'],
				'imagepath' => $this->_config['image']
			 )
		  );
		
		Render::layout('page', $this->_content);

	}
	
	public function addtocartAction() 
	{	
		if (!empty($_POST['pid'])) {	
			$id_zakaz = $_POST['pid'];			
			
			if (isset($_POST['gid'])) {
				$id_gift = $_POST['gid'];
			}

			if (isset($_SESSION['collection'][$id_zakaz])) {

				$_SESSION['collection'][$id_zakaz]['kolvo']++;
				
			} else {			
			
				$product = Database::getRow(get_table('catalog'),$id_zakaz);
				
				if ($product['vid_complect'] == 8) {
					
					$colors = getColorsByIdCatalog($id_zakaz);
					if (!empty($colors[0]['id'])) add_to_cart($colors[0]['id']);
				
				} elseif ($product['vid_complect'] == 9) {
					
					unset($_SESSION['collection'][$id_zakaz]);
					$where = "id_catalog = ".$product['id']." and type_complect = 10";
					$complects = Database::getRows(get_table('catalog_complect'),'type_complect','asc',false,$where);
					foreach($complects as $complect) {
						add_to_cart($complect['id_product']);
					}
					
				} else {
					
					add_to_cart($product['id']);
					
				}				

				
			}

			$data = array();
			if (!isset($_SESSION['collection']) or empty($_SESSION['collection'])) {
				$data['succes'] = false;
				$data['message'] = "Произошла ошибка. Товар не добавлен в корзину.";				
			} else {
				$data['html'] = getBasket($_SESSION['collection'],$this->_config['image']);				
				$data['succes'] = true;	
				$data['message'] = "Товар добавлен к заказу";	
			}	
		} else {
			$data = array();
			$data['succes'] = false;
			$data['message'] = "Произошла ошибка. Вы не выбрали товар.";
		}
		echo json_encode($data);		
	}
	
	public function wishlistAction() {
		
		if (!isset($_SESSION['compare'])) header('location: /category/');
	
		$this->_content['title'] = "Отложенные товары";
				
		$this->_content['description'] = "Отложенные товары";
			
		$this->_content['keywords'] = "Отложенные товары";		
		
		$razdel['id'] = 1;
		
		$_SESSION['row_sorted'] = $row_sorted = getValueBySelect(@$_POST['row_sorted'],@$_SESSION['row_sorted'],'id-ASC');

		$_SESSION['vmode'] = $vmode = getValueBySelect(@$_GET['vmode'],@$_SESSION['vmode'],'grid');
		
		$_SESSION['rows_on_page'] = $rows_on_page = getValueBySelect(@$_GET['rows_on_page'],@$_SESSION['rows_on_page'],$this->_config['pagination']['rows_on_page']);
			
		$params = array();

		if((isset($_GET['tip_catalog'])) and (!empty($_GET['tip_catalog']))) { $params['tip_catalog'] = $_GET['tip_catalog']; } else { $params['tip_catalog'] = $razdel['id']; }
		if(isset($_GET['maker'])) { $params['id_maker'] = $_GET['maker']; }
		if((isset($_GET['price_ot'])) and ($_GET['price_ot']!='от')) { $params['price_ot'] = $_GET['price_ot']; }
		if((isset($_GET['price_do'])) and ($_GET['price_do']!='до')) { $params['price_do'] = $_GET['price_do']; }
	
		$params['sort'] = $row_sorted;
	
		$totals = count($_SESSION['compare']);
		
		$pagination = new Pagination ($totals,$rows_on_page,$this->_config['pagination']['link_by_side'],$this->_config['pagination']['url_segment'],$this->_config['pagination']['base_url']);
			
		$collections = @$_SESSION['compare'];
						
		$this->_content['content'] = Render::view(
			'cart/wishlist', Array(
				'h1item' => "Отложенные товары",
				'collections' => $collections,		
				'imagepath' => $this->_config['image'],
				'tips_catalog' => Tree::getTreesForSite("pid=1"),
				'vmode' => $vmode,
				'totals' => $totals,
				'makers' => Maker::getMakersForSite("active=1","name","ASC",Maker::getTotalMakers()),
				'pagination' => $pagination->getPagination(),
				'getLimitas' => $pagination->getLimitas()
			 )
		  );
		
		Render::layout('page', $this->_content);
		
	}

	public function updatecartAction() {
	
		if (isset($_POST['id'])) {
		
			$item = $_SESSION['collection'][$_POST['id']];
			$item['kolvo'] = @$_POST['qty'];
			$summa = 0;
			foreach ($_SESSION['collection'] as $elem) {
				$cena = transform_to_currency($elem,FALSE);
				if ($elem['id']==$item['id']) {
					$total_cena = $item['kolvo'] * $cena;
				} else {
					$total_cena = $elem['kolvo'] * $cena;
				}
				
				$summa += $total_cena;			
			}
			$data = array();
			$data['total'] = format_total_currency($cena * $item['kolvo']);
			$data['price'] = format_total_currency($cena);
			$data['totalAmount'] = format_total_currency($summa);
			$_SESSION['collection'][$_POST['id']] = $item;
			echo json_encode($data);		
		}
	}
	
	public function updatetableAction() {
		$data = array();
		
		if (isset($_GET['key'])) {

			$array[$_GET['key']] = $_GET['val'];
			
			//$_SESSION['skidka'] = $array;
			
			$data['succes'] = TRUE;
			$data['html'] = get_zakaz_product_table($_SESSION['collection'],$array);
		
		}
		
		echo json_encode($data);
	}
	
	public function clearcartAction() {

		unset($_SESSION['collection']);
		
		header("Location: ".$_SERVER['HTTP_REFERER']);
	
	}
	
	public function compareAction() {
	
		$this->_content['title'] = "Сравнение";
			
		$this->_content['description'] = "Сравнение";
			
		$this->_content['keywords'] = "Сравнение";
		
		if (empty($_SESSION['compare'])) header('location: /category/');
	
		if (@$array_key = array_keys($_SESSION['compare'])) {

			if (@URL::getSegment(3)) { $id_char = URL::getSegment(3); } else { $id_char = $array_key[0]; }
		
			if (empty($_SESSION['compare'][$id_char])) header('location: /cart/compare/');
		
			$this->_content['left'] = Render::view(
				'cart/razdel', Array(
					'trees' => $_SESSION['compare'],
					'array_key' => $array_key
				));

			$this->_content['content'] = Render::view(
				'cart/compare', Array(
					'session_compare' => @$_SESSION['compare'][$id_char],
					'char_groupe' => Characteristics_group::getCollections($id_char),
					'id_char' => $id_char,
					'imagepath' => $this->_config['image']
				));
			
			$this->_content['compare_table'] = Render::view(
				'cart/compare_table', Array(
					'session_compare' => @$_SESSION['compare'][$id_char],
					'char_groupe' => Characteristics_group::getCollections($id_char)
				));
				
			Render::layout('page', $this->_content);	
			
		} 
		
	}    

	public function addtocompareAction() {
		
		if (!empty($_POST['pid'])) {	
				
			$id_catalog = $_POST['pid'];			
			
			$id_zakaz = $id_catalog;
			
			$compare = Catalog::getCollectionByID($id_catalog);
			$id_char = $compare['id_char'];
			$tree = Tree::getTreeByID($id_char);			
			$_SESSION['compare'][$id_char][$id_zakaz] = $compare;
			//$_SESSION['compare'][$id_char][$id_zakaz]['name_razdel'] = @$tree['name'];			

			$_SESSION['compare'][$id_char][$id_zakaz]['image_path'] = insert_image($id_catalog);
	
			$_SESSION['compare'][$id_char][$id_zakaz]['kolvo'] = 1;
	
			$_SESSION['compare'][$id_char][$id_zakaz]['id_item'] = $id_zakaz;

			$data = array();
			$data['html'] = getWishlist($_SESSION['compare'],$this->_config['image']);				
			$data['succes'] = TRUE;	
			//$data['message'] = "Товар добавлен к заказу";	
			echo json_encode($data);
		
		} else {
		
			$data = array();
			$data['succes'] = FALSE;
			$data['message'] = "Произошла ошибка. Товар не добавлен в корзину.";
			echo json_encode($data);
			
		}
	
	}

	public function deltocompareAction() {
		
		if (!empty($_POST['id_del'])) { 
			$id_char = $_POST['id_char'];
			$id_del = $_POST['id_del'];
			unset($_SESSION['compare'][$id_char][$id_del]);
			if (empty($_SESSION['compare'][$id_char])) unset($_SESSION['compare'][$id_char]);
		}
	
	}
	
 	public function clearcompareAction() {

		unset($_SESSION['compare']);
		
		header("Location: ".$_SERVER['HTTP_REFERER']);
	
	} 
	
	public function zakazAction() {
		
		if (!isset($_SESSION['collection'])) header('location: /category/');
		if (@$_SESSION['user']['manager']==1) header('location: /cart/zakaz_manager/');

		$items = @$_SESSION['user'];
	
		$this->_content['content'] = Render::view('cart/zakaz',
			array(
				'items' => $items
			));
	
		Render::layout('page', $this->_content);
	
	}	

	public function deltocartAction() {

		if (!empty($_POST['id_del'])) { 
			unset($_SESSION['collection'][$_POST['id_del']]); 
		}
		
	}
	
	public function zakaz_managerAction() {
		
		if (!isset($_SESSION['collection'])) header('location: /category/');
	
		if (@$_SESSION['user']['manager']==0) {
			header('location: /category/');
		} 
		
		$items = array();
		
		$postavshik = Database::getRows(get_table('kontragenty'),'name','asc',false,'id_tip = 2');
		
		$this->_content['content'] = Render::view('cart/zakaz_manager',
			array(
				'items' => $items,
				'postavshik' => $postavshik
			));
	
		Render::layout('page', $this->_content);
	
	}

	public function loadAction() {
		// Начало формирование объекта
		$data = array();
		$host 	= $_SERVER['HTTP_HOST'];			
		$i = 0;
		$page  = @$_GET['page'];      // Номер запришиваемой страницы
		$limit = @$_GET['rows'];      // Количество запрашиваемых записей
	
		$data['page']       = $page;
		$data['total']      = count($_SESSION['collection']);
		$data['records']    = count($_SESSION['collection']);
		$data['limit']    	= $limit;	
		$suma_usd = 0;
		$suma_bur = 0;
		$kolvo = 0;
		$sum_total_cena = 0;		
		$sum_total_cena_blr = 0;		
		foreach($_SESSION['collection'] as $zakaz) {
			
			$product = Database::getRow(get_table('catalog'),$zakaz['id']);

			if (isset($product['name'])) {
						 
				$name = get_product_name($product,false,$zakaz['name']);							 
					
				$kolvo += $zakaz['kolvo'];
				$suma_usd += with_skidka($zakaz);
				$suma_bur += with_skidka($zakaz,'bur');				
				$total_cena_usd = $zakaz['kolvo'] * with_skidka($zakaz);
				$total_cena_bur = $zakaz['kolvo'] * with_skidka($zakaz,'bur');
				$sum_total_cena += $total_cena_usd;
				$sum_total_cena_blr += $total_cena_bur;				
				
				if ($zakaz['id_gift']!=0) {
					$gift = Database::getRow(get_table('catalog'),$zakaz['id_gift']);
					$html_g = '<a href="http://'.$host.'/product/'.$gift['path'].'" target="_ablank">'.$gift['name'].'</a>';
				} else {
					$html_g = '';
				}
				
				$html_r =  ($product['raffle']!=0) ? $zakaz['promocode'] : 'Нет';
				
				$status = '';

				if (@$zakaz['predzakaz']==1) $status = 'Предзаказ';
				if (@$zakaz['rezerv']==1) $status = 'Резерв';								
				
				$be = '<div" rel="'.$zakaz['id_item'].'" class="button edit editdata"></div>';
				
				$data['rows'][$i]['id'] = $zakaz['id_item'];
				$data['rows'][$i]['cell'][] = $be;
				$data['rows'][$i]['cell'][] = '<a href="http://'.$host.'/product/'.$product['path'].'" target="_ablank">'.$name.'</a>';		
				$data['rows'][$i]['cell'][] = $zakaz['kolvo'];		
				$data['rows'][$i]['cell'][] = with_skidka($zakaz);			
				$data['rows'][$i]['cell'][] = with_skidka($zakaz,'bur');			
				$data['rows'][$i]['cell'][] = $total_cena_usd;			
				$data['rows'][$i]['cell'][] = $total_cena_bur;			
				$data['rows'][$i]['cell'][] = $html_g;			
				$data['rows'][$i]['cell'][] = $html_r;			
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
	
	public function openAction() {
		$data = array();
		$data = $_SESSION['collection'][$_POST['id']];
		$data['html'] = get_table_sklad_tovar($data['id_item']);
		echo json_encode($data);
	}
  
	public function editAction() {

		$data = array();
	
		// ДОБАВИТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="add") {
			
			if (!empty($_POST['id_item'])) {
			
				$id_item = $_POST['id_item'];
				$id_items = explode('-',$id_item);
				
				$id_catalog = (isset($id_items[0])) ? $id_items[0] : $id_item;
				$id_color = (isset($id_items[1])) ? $id_items[1] : 0;
				
				$product = Database::getRow(get_table('catalog'),$id_catalog);
				$colors = Colors::getColorsByID($id_color);
				$name_tovar = $product['name'].' '.@$colors['name'];
			
			} elseif (!empty($_POST['name'])) {
				$id_catalog = add_new_product($_POST['name'],$_POST['cena']);
				$id_item = $id_catalog.'-0';
				$product = Database::getRow(get_table('catalog'),$id_catalog);		
				$name_tovar = $product['name'];
			}

			if (isset($_POST['predzakaz'])) {
				$predzakaz = 1;
			} else {
				if ((get_free_ostatok($id_item) >= $_POST['kolvo']) and (get_free_ostatok($id_item)>0)) {
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

				if (!empty($elem)) $gifts[] = Database::getRow(get_table('catalog'),$elem);
			
			}
		
			$data_zakaz = array(  
				'id_gift' => @$gifts[0]['id'],
				'id_catalog' => $id_catalog,
				'id_item' => $id_item,
				'name_tovar' => $name_tovar,
				'active' => 1,
				'cena_blr' => transform_to_blr($product,false),
				'kolvo' => $_POST['kolvo'],	
				'skidka' => $_POST['skidka'],
				'skidka_procent' => $_POST['skidka_procent'],
				'doplata' => $_POST['doplata'],
				'dostavka' => $_POST['dostavka'],				
				'rezerv' => $rezerv,
				'date_rezerv' => $date_rezerv,
				'predzakaz' => $predzakaz,
				'id_postavshik' => $_POST['id_postavshik'],
				'date_predzakaz' => $_POST['date_predzakaz']
			);	

			$_SESSION['collection'][$id_item] = array_merge($product,$data_zakaz);
					
		}
		
		// РЕДАКТИРОВАТЬ элемент в таблице
		if (isset($_POST['action']) and $_POST['action']=="edit") {
			$id_item = $_POST['id_item'];

			if (isset($_POST['predzakaz'])) {
				$predzakaz = 1;
			} else {
				if ((get_free_ostatok($id_item) >= $_POST['kolvo']) and (get_free_ostatok($id_item)>0)) {
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
			
			$_SESSION['collection'][$id_item]['kolvo'] = $_POST['kolvo'];
			$_SESSION['collection'][$id_item]['rezerv'] = $rezerv;
			$_SESSION['collection'][$id_item]['date_rezerv'] = $date_rezerv;
			$_SESSION['collection'][$id_item]['predzakaz'] = $predzakaz;
			$_SESSION['collection'][$id_item]['id_postavshik'] = @$_POST['id_postavshik'];
			$_SESSION['collection'][$id_item]['date_predzakaz'] = @$_POST['date_predzakaz'];
			$_SESSION['collection'][$id_item]['skidka'] = $_POST['skidka'];
			$_SESSION['collection'][$id_item]['skidka_procent'] = $_POST['skidka_procent'];
			$_SESSION['collection'][$id_item]['doplata'] = $_POST['doplata'];
			$_SESSION['collection'][$id_item]['dostavka'] = $_POST['dostavka'];
		}

		// УДАЛИТЬ элемент из таблицы
		if (isset($_POST['del_id']))  {
			unset($_SESSION['collection'][$_POST['del_id']]);
		}
		
		echo json_encode($data);		
		
	}
	 	
	public function sendAction() {
	
		$data = array();	
		$raffle = FALSE;
		
		if(isset($_SESSION['collection'])) {	

			$data['succes'] = true;
			if ((@$_SESSION['user']['id_adminuser']!=0) and (@$_SESSION['user']['manager']!=0)) {
				$tovars = "";
				foreach($_SESSION['collection'] as $zakaz) {
					
					if (get_free_ostatok($zakaz['id_item']) < $zakaz['kolvo']) {
						if ($zakaz['predzakaz']!=1) {
							$data['succes'] = false;
							
							$maker = Maker::getMakerByID($zakaz['id_maker']);
							if (isset($maker['name'])) $maker_name = $maker['name'].' '; else $maker_name = '';	
							
							$tovars .= '<div style="color:red;">необходимого количества ('.$zakaz['kolvo'].' шт.) '.$maker_name.$zakaz['name'].' нет на складе, СО = '.get_free_ostatok($zakaz['id_item']).'</div>';
						}
					}		
				}
				if ($data['succes'] == false) $data['message'] = $tovars;
			}
			if ($data['succes']) {
				
				$no = Zakaz_client::getLastNomer();
				++$no;
			
				$phone = '';
				$sposob_dostavki = '';
				foreach($_POST['phone'] as $item) {	$phone .= $item['name'].','; }
				if ((@$_SESSION['user']['id_adminuser']!=0) and (@$_SESSION['user']['manager']!=0)) {
					$id_adminuser = @$_SESSION['user']['id_adminuser'];
					$active = 1;
					$date_active = date("Y-m-d");
					$time_active = date("G:i:s");
					$date_dostavka = $_POST['date_dostavka'];				
					$comment_m = $_POST['comment_m'];
					$samovivoz = ((isset($_POST['samovivoz'])) ? 1 : 0);
					$samovivoz_ofice = ((isset($_POST['samovivoz_ofice'])) ? 1 : 0);
					$dostavka = ((isset($_POST['dostavka'])) ? 1 : 0);	
					$code_zayavka = $_POST['code_zayavka'];
					$print_ready = ((isset($_POST['print_ready'])) ? 1 : 0);
					$sposob_dostavki = ((isset($_POST['sposob_dostavki'])) ? $_POST['sposob_dostavki'] : '');	

					if ((isset($_POST['samovivoz'])) or (isset($_POST['samovivoz_ofice']))) {	
						send_kladovshik_sms($_POST,$date_dostavka,$_SESSION['collection']);
					}		
				
				} else {
					$id_adminuser = 0;
					$active = 0;
					$date_active = '';
					$time_active = '';
					$date_dostavka = date("Y-m-d");				
					$comment_m = '';
					$samovivoz = 0;
					$samovivoz_ofice = 0;
					$dostavka = 0;		
					$code_zayavka = '';
					$print_ready = 0;
					$sposob_dostavki = '';					
				}	
				
				$data_client = array(
					'nomer_zakaza' => $no,
					'phone' => $phone,					
					'firstname' => $_POST['firstname'],
					'email' => $_POST['email'],
					'city' => $_POST['city'],
					'street' => $_POST['street'],
					'house' => $_POST['house'],
					'building' => $_POST['building'],
					'apartment' => $_POST['apartment'],
					'floor' => $_POST['floor'],
					'entrance' => $_POST['entrance'],
					'date_zakaz' => date("Y-m-d"),
					'time_zakaz' => date("G:i:s"),					
					'date_active' => $date_active,
					'time_active' => $time_active,
					'date_dostavka' => $date_dostavka,
					'active' => $active,
					'comment' => $_POST['comment'],
					'comment_m' => $comment_m,
					'samovivoz' => $samovivoz,
					'samovivoz_ofice' => $samovivoz_ofice,
					'dostavka' => $dostavka,
					'id_adminuser' => $id_adminuser,
					'code_zayavka' => $code_zayavka,
					'poselok' => $_POST['poselok'],
					'print_ready' => $print_ready,
					'sposob_dostavki' => $sposob_dostavki,
					'dumayut' => ((isset($_POST['dumayut'])) ? 1 : 0)						
				);	
				Database::insert(get_table('zakaz_client'),$data_client);
				$last_client = Database::getLastId(get_table('zakaz_client'));
				foreach($_SESSION['collection'] as $item) {
					if ($item['raffle']!=0) {
						$raffle = TRUE;
						$promocode = Promocode::getPromocodeRaffle();
						if(@$promocode) {
							Promocode::updatePage(array(
								'id' => $promocode[0]['id'],
								'name' => $promocode[0]['name'],
								'active' => $promocode[0]['active'],
								'active_raffle' => 0,								
							));	
						}
					}
					$data_zakaz = array(
						'id_client' => $last_client,
						'nomer_zakaza' => $no,
						'id_catalog' => $item['id'],
						'id_item' => $item['id_item'],
						'name_tovar' => $item['name'],
						'cena' => $item['cena'],
						'cena_blr' => $item['cena_blr'],
						'kolvo' => $item['kolvo'],
						'raffle' => $item['raffle'],
						'id_gift' => (!empty($item['id_gift']) ? $item['id_gift'] : 0),
						'promocode' => (isset($promocode[0]['name']) ? $promocode[0]['name'] : ""),
						'rezerv' => @$item['rezerv'],
						'date_rezerv' => @$item['date_rezerv'],
						'predzakaz' => @$item['predzakaz'],
						'id_postavshik' => @$item['id_postavshik'],
						'date_predzakaz' => @$item['date_predzakaz'],
						'skidka' => @$item['skidka'],
						'skidka_procent' => @$item['skidka_procent'],
						'doplata' => @$item['doplata'],
						'dostavka' => @$item['dostavka'],					
					);
					Database::insert(get_table('zakaz'),$data_zakaz);
				}
			
				if (@$_SESSION['user']['manager']==1) $text_tmp = "";
				else $text_tmp = "\nС Вами свяжутся для его подтверждения. ";
				
				if ($raffle) $r_text = "При покупке данного товара Вы принимаете участие в РОЗЫГРЫШЕ.\nКод для участия в нем выдается при передаче товара.\n";
				
				$thx = "Спасибо, что выбрали нас.\nhttp://babyexpert.by";
				
				$text = "Ваш заказ ".$no.". ".$text_tmp."\n".@$r_text.$thx;
				
				$result = send_sms($text,$_POST['phone'][0]['name']);
						
				$data['url'] = '/dengi_za_otzyv#dzv';	
				$data['message'] = 'Ваш заказ оформлен<br/>Заказ №'.$no.'<br/>С Вами свяжутся для его подтверждения<br/><a href="/dengi_za_otzyv" title="Хочу бонус" style="font-size:20px;">Хочу бонус</a><br/><a href="/" title="На главную">На главную</a>';	
				unset($_SESSION['collection']);
			}
		} else {
		
			$data['succes'] = FALSE;
			$data['url'] = '/category';
			$data['message'] = 'Время заказа истекло, закажите товар повторно!';	
			
		}
		
		echo json_encode($data);
			
	}
  	
	/*public function testttAction() {
				
		if (@$_SESSION['user']['manager']==1) $text_tmp = " \n";
		else $text_tmp = "\nС Вами свяжутся для его подтверждения. \n";
	
		$text = "Ваш заказ 22. ".$text_tmp."Спасибо, что выбрали нас.\nhttp://babyexpert.by";	
		$result = send_sms($text,'30969754578');
	}*/
	
	public function linkstofriendAction() {
	
		$data = array();	

		$session = $_SESSION['compare'];
		
		if ((isset($session)) and (!empty($session))) {
			
			$code = mt_rand(10000,99999);
			
			foreach($session as $items) {
				foreach($items as $item) {
					Friend_send::addZakaz(array(
						'id_catalog' => $item['id'],
						'id_image' => insert_image($item['id']),
						'code' => $code,
						'date_zakaz' => date("d.m.Y")
					));	
				}
			}
			$_SESSION['code'] = $code;		
			$data['succes'] = TRUE;	
			$data['message'] = 'Ваш код '.$code.'. <br/>
								Передайте его Вашему другу. После ввода данного кода эту строку 
								<img src="/img/primer.png" alt="" />
								Ваш друг увидит товары которые Вы отложили и возможно окажет Вам помощь в выборе.
								';			
		
		} else {
		
			$data['succes'] = FALSE;
			$data['message'] = 'Добавьте товар для сравнения!';
			
		}

		echo json_encode($data);
	
	} 
	
  	
	public function codetocompareAction() {
	
		$data = array();	

		$code = $_POST['code'];
		
		if ((isset($code)) and (!empty($code))) {
			
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
				//$_SESSION['compare'][$id_char][$id_zakaz]['name_razdel'] = @$tree['name'];			

				$_SESSION['compare'][$id_char][$id_zakaz]['image_path'] = insert_image($id_catalog);
		
				$_SESSION['compare'][$id_char][$id_zakaz]['kolvo'] = 1;
		
				$_SESSION['compare'][$id_char][$id_zakaz]['id_item'] = $id_zakaz;

			}			
			
			$data['html'] = getWishlist($_SESSION['compare'],$this->_config['image']);		
			$data['succes'] = TRUE;	
			//$data['message'] = 'Ваш запрос принят!';	
		
		} else {
		
			$data['succes'] = FALSE;
			$data['message'] = 'Вы не ввели код!';
			
		}

		echo json_encode($data);
	
	}	
}