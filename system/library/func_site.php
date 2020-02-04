<?php


function items_to_select_html($items,$id_razmer) {
	$html = '<option value="0">-- Выберите --</option>';
	foreach ($items as $item) {	
		if ($id_razmer == $item['id']) $sel = 'selected'; else $sel = '';
		$html .= '<option value="'.$item['id'].'" '.$sel.'>'.$item['name'].'</option>';
	}
	return $html;
}

function table_razmer_html($collection) {
	$items = Database::getRows(get_table('catalog_razmer'),'cena','desc',false,"id_catalog = ".$collection['id']." and active = 1");
	$html = '';
	if (!empty($items)) {
		
		$html .= '<tr class="tr-head">
			<th class="td-size">Размер, <span>'.@$items[0]['sufix'].'</span></th>
			<th class="td-price">Наименование</th>
			<th class="td-options">Описание</th>
			<th class="td-total">Цена</th>
			<th class="td-buttons"></th>		
		</tr>';				
		foreach($items as $item) {
			if ($item['cena_blr'] <= 0) {
				$cena = transform_to_blr($item,false);
			} else {
				$cena = $item['cena_blr'];
			}
			$cur = @$_SESSION['currency'];
			$valute = ($cur == 'usd') ? 'ye' : 'br';
			$razmer = Database::getField(get_table('razmer'),$item['id_razmer'],'id','name');
			$description = Database::getField(get_table('description'),$item['id_description'],'id','name');

			$html .= '<tr>';
			$html .= '<td>'.$razmer.'</td>';
			$html .= '<td>'.$item['name'].'</td>';
			$html .= '<td>'.$description.'</td>';
			$html .= '<td>'.formatCena($cena).' '.$valute.'</td>';
			$html .= '<td class="td-buttons"><button type="submit" data-product-id="'.$collection['id'].'" data-id="'.$item['id'].'" data-cena="'.$cena.'" class="btn btn-blue razmer_add">Купить</button></td>';
			$html .= '</tr>';

					
		}
	}
	return $html;
}

function get_arr_cena_complect($id_catalog,$id_razmer) {
	$data = array();
	
	if ($id_razmer) {
		$where = "id_catalog = $id_catalog and id_razmer = $id_razmer";
	} else {
		$where = "id_catalog = $id_catalog";
	}
	$elems = Database::getRows(get_table('catalog_complect'),'id_razmer','asc',false,$where);
	$data['cena_karkas'] = 0;
	$data['cena_ydlinen'] = 0;
	$data['cena_poly'] = 0;
	$data['cena_karkas_usd'] = 0;
	$data['cena_ydlinen_usd'] = 0;
	$data['cena_poly_usd'] = 0;
	$data['karkas'] = array();
	$data['ydlinen'] = array();
	$data['poly'] = array();
	if (!empty($elems)) {
		foreach($elems as $elem) {
			if ($elem['type_complect']==1) $karkas = $elem;
			if ($elem['type_complect']==2) $ydlinen = $elem;
			if ($elem['type_complect']==3) $poly = $elem;
		}
		
		if (isset($karkas)) {
			$data['cena_karkas'] = get_cena_complect($karkas);
			$data['cena_karkas_usd'] = get_cena_complect_usd($karkas);
			$data['karkas'] = $karkas;
		}
		if (isset($ydlinen)) {
			$data['cena_ydlinen'] = get_cena_complect($ydlinen);
			$data['cena_ydlinen_usd'] = get_cena_complect_usd($ydlinen);
			$data['ydlinen'] = $ydlinen;
		}
		if (isset($poly)) {
			$data['cena_poly'] = get_cena_complect($poly);
			$data['cena_poly_usd'] = get_cena_complect_usd($poly);
			$data['poly'] = $poly;
		}

	} 
	return $data;		
}

function get_complect_table($product) {
	
	$id_catalog = $product['id'];
	$table_complect = get_table('catalog_complect');
	
	if (@$_SESSION['currency']=='byr') $symbol = '(бел.руб)';
	else $symbol = '$';
	
	$html = '';
	$html .= '<form id="poli_add" action="/cart/addtocart/" method="POST">';
	if ($product['vid_complect']==1) {
		
		$html .= '<input type="hidden" value="'.$product['id'].'" name="pid">';
		$html .= '<table style="display: block;"  class="b-product-options">
					<tr>
						<th class="td-size">РАЗМЕР, <span>м</span></th>
						<th class="td-price">СТОИМОСТЬ КАРКАСА (бел.руб)</th>
						<th class="td-options">ПОЛИКАРБОНАТ</th>
						<th class="td-total">ОБЩАЯ СУММА '.$symbol.'</th>
						<th class="td-buttons"></th>
					</tr>';
		for($i=1;$i<=4;$i++) {
		
		$arr = get_arr_cena_complect($id_catalog,$i);
		if (@$_SESSION['currency']=='byr') {
			$cena_karkas = $arr['cena_karkas'];
			$cena_ydlinen = $arr['cena_ydlinen'];
			$cena_poly = $arr['cena_poly'];
		} else {
			$cena_karkas = $arr['cena_karkas_usd'];
			$cena_ydlinen = $arr['cena_ydlinen_usd'];
			$cena_poly = $arr['cena_poly_usd'];			
		}
		$poly = $arr['poly'];
		$ydlinen = $arr['ydlinen'];
		if (!empty($poly)) {
			$total_cena = $cena_karkas+$cena_ydlinen;
			
			$product_poly = Database::getRow(get_table('catalog'),$poly['id_product']);
			$name = get_product_name($product_poly);
			$maker_name = Database::getField(get_table('maker'),$product_poly['id_maker'],'id','name');
			
			$html .= '	<tr>
							<td><div class="hover-block">'.get_razmer_complect($i).'</div><div class="table-catalog-sklad hideshow">'.get_table_sklad_tovar_site(@$ydlinen['id_product']).'</div></td>
							<td>'.formatCena($total_cena).'</td>
							<td>
								<fieldset class="hover-block">
									<input type="checkbox" class="choise_plita" name="poly" data-cena="'.($total_cena).'" value="'.@$cena_poly.'" id="ind'.$i.'"><label for="ind'.$i.'">'.@$maker_name.' ('.@$poly['kolvo'].' лст.)</label><br>
									<div class="table-catalog-sklad hideshow">'.get_table_sklad_tovar_site($poly['id_product']).'</div>
								</fieldset>
							</td>
							<td><span class="total-price">'.formatCena($total_cena).'</span></td>
							<td class="td-buttons">
								<button class="btn btn-blue btn-complect" data-razmer="'.$i.'">Купить</button>
							</td>
						</tr>';	
			}	
		}		
		$html .= '</table>';		
	}	
	if ($product['vid_complect']==2) {
		
		$total_cena = cena_vid_complect($id_catalog,4) + cena_vid_complect($id_catalog,5) + cena_vid_complect($id_catalog,6);
		
		$html .= '<input type="hidden" value="'.$product['id'].'" name="pid">';
		$html .= '<input type="hidden" class="cenaProduct" value="'.transform_to_currency($product,false).'" name="cena-product">';
		$html .= '<table style="display: block;"  class="b-product-options moika">
					<tr>
						<th class="td-size">МОЙКА</th>
						<th class="td-price-m">РУКОМОЙНИК</th>
						<th class="td-options">ТУМБА</th>
						<th class="td-total-m">ОБЩАЯ СУММА '.$symbol.'</th>
						<th class="td-buttons"></th>
					</tr>';
		
			$html .= '<tr>';
			$html .= '<td>'.radio_vid_complect($id_catalog,4).'</td>';	
			$html .= '<td>'.radio_vid_complect($id_catalog,5).'</td>';	
			$html .= '<td>'.radio_vid_complect($id_catalog,6).'</td>';
			$html .= '<td><span class="total-price">'.formatCena($total_cena).'</span></td>';	
			$html .= '<td class="td-buttons">
							<button class="btn btn-blue btn-complect" data-moika="1">Купить</button>
						</td>';	
			$html .= '</tr>';
				
		$html .= '</table>';		
	}	
	if ($product['vid_complect']==4) {
		
		$total_cena = cena_vid_complect($id_catalog,7) + cena_vid_complect($id_catalog,8);
		
		$html .= '<input type="hidden" value="'.$product['id'].'" name="pid">';
		$html .= '<input type="hidden" class="cenaProduct" value="'.transform_to_currency($product,false).'" name="cena-product">';
		$html .= '<table style="display: block;"  class="b-product-options moika">
					<tr>
						<th class="td-price-m">БАК</th>
						<th class="td-price-m">КАБИНА</th>
						<th class="td-total-m">ОБЩАЯ СУММА '.$symbol.'</th>
						<th class="td-buttons"></th>
					</tr>';
		
			$html .= '<tr>';
			$html .= '<td>'.radio_vid_complect($id_catalog,7).'</td>';	
			$html .= '<td>'.radio_vid_complect($id_catalog,8).'</td>';	
			$html .= '<td><span class="total-price">'.formatCena($total_cena).'</span></td>';	
			$html .= '<td class="td-buttons">
							<button class="btn btn-blue btn-complect" data-moika="1">Купить</button>
						</td>';	
			$html .= '</tr>';
				
		$html .= '</table>';		
	}
	if ($product['vid_complect']==6) {
		
		$total_cena = cena_vid_complect($id_catalog,7) + cena_vid_complect($id_catalog,8) + cena_vid_complect($id_catalog,3);
		
		$html .= '<input type="hidden" value="'.$product['id'].'" name="pid">';
		$html .= '<input type="hidden" class="cenaProduct" value="'.transform_to_currency($product,false).'" name="cena-product">';
		$html .= '<table style="display: block;"  class="b-product-options moika">
					<tr>
						<th class="td-options">БАК</th>
						<th class="td-options">КАБИНА</th>
						<th class="td-options">ПОЛИКАРБОНАТ</th>
						<th class="td-total-m">ОБЩАЯ СУММА '.$symbol.'</th>
						<th class="td-buttons"></th>
					</tr>';
		
			$html .= '<tr>';
			$html .= '<td>'.checkbox_vid_complect($id_catalog,7).'</td>';	
			$html .= '<td>'.checkbox_vid_complect($id_catalog,8).'</td>';	
			$html .= '<td>'.checkbox_vid_complect($id_catalog,3).'</td>';	
			$html .= '<td><span class="total-price">'.formatCena($total_cena).'</span></td>';	
			$html .= '<td class="td-buttons">
							<button class="btn btn-blue btn-complect" data-moika="1">Купить</button>
						</td>';	
			$html .= '</tr>';
				
		$html .= '</table>';		
	}	
	if ($product['vid_complect']==7) {
		
		$total_cena = cena_vid_complect($id_catalog,1) + cena_vid_complect($id_catalog,3);
		
		$html .= '<input type="hidden" value="'.$product['id'].'" name="pid">';
		$html .= '<input type="hidden" class="cenaProduct" value="'.transform_to_currency($product,false).'" name="cena-product">';
		$html .= '<table style="display: block;"  class="b-product-options moika">
					<tr>
						<th class="td-price-m">КАРКАС</th>
						<th class="td-price-m">ПОЛИКАРБОНАТ</th>
						<th class="td-total-m">ОБЩАЯ СУММА '.$symbol.'</th>
						<th class="td-buttons"></th>
					</tr>';
		
			$html .= '<tr>';
			$html .= '<td>'.radio_vid_complect($id_catalog,1).'</td>';	
			$html .= '<td>'.radio_vid_complect($id_catalog,3).'</td>';	
			$html .= '<td><span class="total-price">'.formatCena($total_cena).'</span></td>';	
			$html .= '<td class="td-buttons">
							<button class="btn btn-blue btn-complect" data-moika="1">Купить</button>
						</td>';	
			$html .= '</tr>';
				
		$html .= '</table>';		
	}	
	$html .= '</form>';
	return $html;
}

function cena_vid_complect($id_catalog,$type_complect) {
	$cena = 0;
	$where = "id_catalog = $id_catalog and type_complect = $type_complect";
	$items = Database::getRows(get_table('catalog_complect'),'type_complect','asc',false,$where);
	if (isset($items[0])) {
		$cena = transform_cena_complect($items[0]);
	}	

	return $cena;
}

function get_complect_button($product) {
	
	$id_catalog = $product['id'];
	$table_complect = get_table('catalog_complect');
	
	$html = '';
	$html .= '<form id="poli_add" action="/cart/addtocart/" method="POST">';

	if ($product['vid_complect']==3) {
		
		$html .= '<input type="hidden" value="'.$product['id'].'" name="pid">';
		$html .= '<div class="moika">';
		for($i=4;$i<7;$i++) {
			$arr = radio_vid_complect2($id_catalog,$i);
			$html .= $arr['html'];
		}
		$html .= '<button class="btn buy btn-complect"></button>';
		$html .= '</div>';

	}
	
	if ($product['vid_complect']==5) {
		
		$html .= '<input type="hidden" value="'.$product['id'].'" name="pid">';
		$html .= '<div class="moika">';
		$bak = radio_vid_complect2($id_catalog,7);
		$kabina = radio_vid_complect2($id_catalog,8);
		$polykarbonat = radio_vid_complect2($id_catalog,3);
		$html .= $bak['html'];
		$html .= $kabina['html'];
		$html .= $polykarbonat['html'];
		$html .= '<button class="btn buy btn-complect"></button>';
		$html .= '</div>';

	}
	$html .= '</form>';
	echo $html;
}
	
function transform_cena_complect($item) {

	$result = 0;
	
	if (@$_SESSION['currency']=='byr') $result = get_cena_complect($item);
	else $result = get_cena_complect_usd($item);
	
	return $result;
}

			
function radio_vid_complect($id_catalog,$type_complect) {
	$html = '';
	$where = "id_catalog = $id_catalog and type_complect = $type_complect";
	$items = Database::getRows(get_table('catalog_complect'),'type_complect','asc',false,$where);
	$i = 0;
	foreach($items as $item) {
		if (@$_SESSION['user']['diler']>0) {
			if (get_free_ostatok($item['id_product']) <= 0) $style = 'style="color:red"';
			else $style = 'style="color:green"';	
		} else {
			$style = '';
		}		
		$product = Database::getRow(get_table('catalog'),$item['id_product']);
		$cena = transform_cena_complect($item);
		if ($i++ == 0) $chk = 'checked'; else $chk = '';
		$html .= '<div class="block-m-complect hover-block"><div class="table-catalog-sklad hideshow">'.get_table_sklad_tovar_site($item['id_product']).'</div><input type="radio" '.$chk.' class="choise_moika" name="type_id_'.$type_complect.'" data-id="'.$item['id'].'" value="'.@$cena.'" id="g'.$item['id'].'" /><label for="g'.$item['id'].'" '.$style.'>'.get_product_name($product).'</label></div>';
	}
	return $html;
}

function checkbox_vid_complect($id_catalog,$type_complect) {
	$html = '';
	$where = "id_catalog = $id_catalog and type_complect = $type_complect";
	$items = Database::getRows(get_table('catalog_complect'),'type_complect','asc',false,$where);
	$i = 0;
	foreach($items as $item) {
		$product = Database::getRow(get_table('catalog'),$item['id_product']);
		$cena = transform_cena_complect($item);
		if ($i++ == 0) $chk = 'checked'; else $chk = '';
		$html .= '<div class="block-m-complect hover-block"><div class="table-catalog-sklad hideshow">'.get_table_sklad_tovar_site($item['id_product']).'</div><input type="checkbox" '.$chk.' class="choise_moika" name="type_id_'.$type_complect.'" data-id="'.$item['id'].'" value="'.@$cena.'" id="g'.$item['id'].'" /><label for="g'.$item['id'].'">'.get_product_name($product).'</label></div>';
	}
	return $html;
}

function radio_vid_complect2($id_catalog,$type_complect) {
	$html = '';
	$cena = 0;
	$where = "id_catalog = $id_catalog and type_complect = $type_complect";
	$items = Database::getRows(get_table('catalog_complect'),'type_complect','asc',false,$where);
	if (isset($items[0])) {
		$item = $items[0];
		$product = Database::getRow(get_table('catalog'),$item['id_product']);
		$cena = transform_cena_complect($item);
		$html .= '<div class="block-m-complect" style="display:none" ><input type="radio" checked class="choise_moika" name="type_id_'.$type_complect.'" data-id="'.$item['id'].'" value="'.@$cena.'" /></div>';
	}
	$data = array();
	$data['html'] = $html;
	$data['cena'] = $cena;
	
	return $data;
}

function get_cena_complect($elem,$not_kolvo = false,$sess = array()) {
	$product = Database::getRow(get_table('catalog'),$elem['id_product']);
	if (empty($elem['id_product'])) return;
	$cena = transform_to_blr($product,false,$sess);
	$result = 0;
	$kurs = get_kurs();
	
	if ($elem['skidka_blr']>0)	$skidka = $elem['skidka_blr'];
	else $skidka = $elem['skidka_usd'] * $kurs;
	
	if ($elem['doplata_blr']>0) $doplata = $elem['doplata_blr'];
	else $doplata = $elem['doplata_usd'] * $kurs;

	if (empty($sess)) $sess = @$_SESSION['user'];
	if (isset($sess['diler']) and !empty($sess['diler'])) {
		$doplata = 0;
		$skidka = 0;
		if ($sess['diler']==1) $skidka = $elem['skidka_roznica'] * $kurs;				
		if ($sess['diler']==2) $skidka = $elem['skidka_roznica1'] * $kurs;
		if ($sess['diler']==3) $skidka = $elem['skidka_diler1'] * $kurs;	
		if ($sess['diler']==4) $skidka = $elem['skidka_diler2'] * $kurs;	
		if ($sess['diler']==5) $skidka = $elem['skidka_diler3'] * $kurs; 
	}	
	
	if ($not_kolvo) $kolvo = 1;
	else $kolvo = $elem['kolvo'];
	
	$result = ($cena - $skidka + $doplata) * $kolvo;
	return round($result,-3);
}

function get_cena_complect_usd($elem,$not_kolvo = false,$sess = array()) {
	$product = Database::getRow(get_table('catalog'),$elem['id_product']);
	if (empty($elem['id_product'])) return;

	$result = 0;

	if ($not_kolvo) $kolvo = 1;
	else $kolvo = $elem['kolvo'];
	
	if (empty($sess)) $sess = @$_SESSION['user'];	
	
	$arr = transform_diler_cena($product,$sess);	
	$cena = $arr['cena'];
	
	$skidka = $elem['skidka_usd'];
	$doplata = $elem['doplata_usd'];

	if (isset($sess['diler']) and !empty($sess['diler'])) {
		$doplata = 0;
		$skidka = 0;
		if ($sess['diler']==1) $skidka = $elem['skidka_roznica'];				
		if ($sess['diler']==2) $skidka = $elem['skidka_roznica1'];
		if ($sess['diler']==3) $skidka = $elem['skidka_diler1'];	
		if ($sess['diler']==4) $skidka = $elem['skidka_diler2'];	
		if ($sess['diler']==5) $skidka = $elem['skidka_diler3']; 
	}	
	
	$result = ($cena - $skidka + $doplata) * $kolvo;
	return $result;
}

function complect_to_session() {
	if (!isset($_SESSION['collection'])) return;
	foreach($_SESSION['collection'] as $sess) {
		if (!isset($sess['complect_razmer']) and !isset($sess['complect_moika'])) continue;
		if (isset($sess['complect_moika'])) {
			//$_SESSION['collection'][$sess['id']]['cena_blr'] = transform_to_blr(Database::getRow(get_table('catalog'),$sess['id']),false);
			unset($_SESSION['collection'][$sess['id']]);
		}
		$id_catalog = $sess['id'];
		$id_razmer = @$sess['complect_razmer'];
		if (isset($id_razmer)) $where = "id_catalog = $id_catalog and id_razmer = $id_razmer";
		else $where = "id_catalog = $id_catalog and id IN (".$sess['complect_moika'].")";		
		$elems = Database::getRows(get_table('catalog_complect'),'id_razmer','asc',false,$where);
		foreach($elems as $elem) {
			$id_zakaz = $elem['id_product'];
			$product = Database::getRow(get_table('catalog'),$id_zakaz);
			
			$_SESSION['collection'][$id_zakaz] = $product;
			$_SESSION['collection'][$id_zakaz]['image_path'] = insert_image($id_zakaz);	
			$_SESSION['collection'][$id_zakaz]['id_gift'] = @$id_gift;
			$_SESSION['collection'][$id_zakaz]['cena_blr'] = get_cena_complect($elem,true);
			$_SESSION['collection'][$id_zakaz]['cena'] = get_cena_complect_usd($elem,true);
			$_SESSION['collection'][$id_zakaz]['rezerv'] = 0;
			$_SESSION['collection'][$id_zakaz]['date_rezerv'] = '';
			$_SESSION['collection'][$id_zakaz]['predzakaz'] = 0;
			$_SESSION['collection'][$id_zakaz]['id_postavshik'] = 0;
			$_SESSION['collection'][$id_zakaz]['date_predzakaz'] = '';	
			$_SESSION['collection'][$id_zakaz]['skidka'] = 0;
			$_SESSION['collection'][$id_zakaz]['skidka_procent'] = 0;
			$_SESSION['collection'][$id_zakaz]['doplata'] = 0;
			$_SESSION['collection'][$id_zakaz]['dostavka'] = 0;	
			$_SESSION['collection'][$id_zakaz]['kolvo'] = $sess['kolvo'] * $elem['kolvo'];
			$_SESSION['collection'][$id_zakaz]['id_item'] = $id_zakaz;				
			
		}
	}

}

function add_to_cart($id_zakaz,$id_gift=0) {

	$sess = @$_SESSION['collection'];
	
	if (isset($sess[$id_zakaz])) { 
		$sess[$id_zakaz]['kolvo']++;
	} else {		
		$product = Database::getRow(get_table('catalog'),$id_zakaz);				
		$sess[$id_zakaz] = $product;
		$sess[$id_zakaz]['image_path'] = insert_image($id_zakaz);	
		$sess[$id_zakaz]['id_gift'] = @$id_gift;
		$sess[$id_zakaz]['cena_blr'] = transform_to_blr($product,false);
		$sess[$id_zakaz]['rezerv'] = 0;
		$sess[$id_zakaz]['date_rezerv'] = '';
		$sess[$id_zakaz]['predzakaz'] = 0;
		$sess[$id_zakaz]['id_postavshik'] = 0;
		$sess[$id_zakaz]['date_predzakaz'] = '';	
		$sess[$id_zakaz]['skidka'] = 0;
		$sess[$id_zakaz]['skidka_procent'] = 0;
		$sess[$id_zakaz]['doplata'] = 0;
		$sess[$id_zakaz]['dostavka'] = 0;	
		$sess[$id_zakaz]['kolvo'] = 1;
		$sess[$id_zakaz]['id_item'] = $id_zakaz;
		$sess[$id_zakaz]['short_description'] = '';
		$sess[$id_zakaz]['full_description'] = '';
	}

	$_SESSION['collection'] = $sess;
	
}


function add_to_cart_with_post($post) {

	if (isset($post['complect']) and !empty($post['complect'])) {
		$arr = explode(',',$post['complect']);
		foreach($arr as $id) {
			if (!empty($id)) {
				$elem = Database::getRow(get_table('catalog_complect'),$id);
				$id_zakaz = $elem['id_product'];			
				add_to_cart($id_zakaz);
				$_SESSION['collection'][$id_zakaz]['cena'] = get_cena_complect_usd($elem,true);
				$_SESSION['collection'][$id_zakaz]['cena_blr'] = get_cena_complect($elem,true);
				$_SESSION['collection'][$id_zakaz]['id_complect'] = $elem['id'];
				if (isset($_SESSION['collection'][$id_zakaz]['kolvo'])) {
					$_SESSION['collection'][$id_zakaz]['kolvo']--;
					$_SESSION['collection'][$id_zakaz]['kolvo'] += $elem['kolvo'];
				} else {
					$_SESSION['collection'][$id_zakaz]['kolvo'] = $elem['kolvo'];
				}				
			}
		}
	}
	
	if (isset($post['id_razmer']) and !empty($post['id_razmer'])) {
		$id_zakaz = $post['pid'];		
		add_to_cart($id_zakaz);
		$id_razmer = $post['id_razmer'];
		$razmer = Database::getRow(get_table('catalog_razmer'),$id_razmer);
		$_SESSION['collection'][$id_zakaz]['articul'] = @$razmer['name'];
		$razmer_t = Database::getField(get_table('razmer'),$razmer['id_razmer'],'id','name');
		$_SESSION['collection'][$id_zakaz]['razmer'] = $razmer_t;
		$_SESSION['collection'][$id_zakaz]['name'] .= ' '.@$razmer_t;	
		$_SESSION['collection'][$id_zakaz]['razmer_description'] = Database::getField(get_table('description'),$razmer['id_description'],'id','name');
		if (isset($post['summa'])) $_SESSION['collection'][$id_zakaz]['cena_blr'] = $post['summa'];					
	}
	
	if (isset($post['complect_razmer']) and !empty($post['complect_razmer'])) {
		
		$id_zakaz = $post['pid'];
		$complect_razmer = $post['complect_razmer'];

		$elems = Database::getRows(get_table('catalog_complect'),'id_razmer','asc',false,"id_catalog = $id_zakaz and id_razmer = $complect_razmer");
		
		foreach($elems as $elem) {
			$id_product = $elem['id_product'];

			if (!isset($post['poly']) and $elem['type_complect']==3) continue;
			add_to_cart($id_product);
			$_SESSION['collection'][$id_product]['complect_razmer'] = $complect_razmer;
			$_SESSION['collection'][$id_product]['cena'] = get_cena_complect_usd($elem,true);
			$_SESSION['collection'][$id_product]['cena_blr'] = get_cena_complect($elem,true);
			$_SESSION['collection'][$id_product]['id_complect'] = $elem['id'];
			if (isset($_SESSION['collection'][$id_product]['kolvo'])) {
				$_SESSION['collection'][$id_product]['kolvo']--;
				$_SESSION['collection'][$id_product]['kolvo'] += $elem['kolvo'];
			} else {
				$_SESSION['collection'][$id_product]['kolvo'] = $elem['kolvo'];
			}

		}		

	}
		
	if (isset($post['id_item'])) unset($_SESSION['collection'][$post['id_item']]);
	
}

function add_to_cart_only($id_zakaz,$razmer) {

	$sess = @$_SESSION['collection'];
	$id_item = $id_zakaz.'-'.$razmer;
	if (@$_POST['poly'] > 0) $id_item .= '-1';
	
	if (isset($sess[$id_item])) { 
		$sess[$id_item]['kolvo']++;
	} else {		
		$product = Database::getRow(get_table('catalog'),$id_zakaz);
		$sess[$id_item] = $product;		
		$sess[$id_item]['image_path'] = insert_image($id_zakaz);	
		$sess[$id_item]['id_gift'] = @$id_gift;
		$sess[$id_item]['rezerv'] = 0;
		$sess[$id_item]['date_rezerv'] = '';
		$sess[$id_item]['predzakaz'] = 0;
		$sess[$id_item]['id_postavshik'] = 0;
		$sess[$id_item]['date_predzakaz'] = '';	
		$sess[$id_item]['skidka'] = 0;
		$sess[$id_item]['skidka_procent'] = 0;
		$sess[$id_item]['doplata'] = 0;
		$sess[$id_item]['dostavka'] = 0;	
		$sess[$id_item]['kolvo'] = 1;
		$sess[$id_item]['id_item'] = $id_item;
		$sess[$id_item]['short_description'] = '';
		$sess[$id_item]['full_description'] = '';
		$sess[$id_item]['name'] .= ' '.get_razmer_complect($razmer);

		$sess[$id_item]['complect'] = @$_POST['complect'];
		$sess[$id_item]['id_razmer'] = @$_POST['id_razmer'];
		$sess[$id_item]['pid'] = @$_POST['pid'];
		$sess[$id_item]['summa'] = @$_POST['summa'];
		$sess[$id_item]['complect_razmer'] = @$_POST['complect_razmer'];
		if (isset($_POST['poly']) and !empty($_POST['poly'])) $sess[$id_item]['poly'] = $_POST['poly'];
		
		if (isset($_POST['complect']) and !empty($_POST['complect'])) {
			
			$summa_blr = 0;		
			if (isset($_POST['type_id_1'])) $summa_blr += $_POST['type_id_1'];			
			if (isset($_POST['type_id_2'])) $summa_blr += $_POST['type_id_2'];			
			if (isset($_POST['type_id_3'])) $summa_blr += $_POST['type_id_3'];			
			if (isset($_POST['type_id_4'])) $summa_blr += $_POST['type_id_4'];			
			if (isset($_POST['type_id_5'])) $summa_blr += $_POST['type_id_5'];			
			if (isset($_POST['type_id_6'])) $summa_blr += $_POST['type_id_6'];			
			if (isset($_POST['type_id_7'])) $summa_blr += $_POST['type_id_7'];
			if (isset($_POST['type_id_8'])) $summa_blr += $_POST['type_id_8'];
		
		}
				
		if (isset($_POST['id_razmer']) and !empty($_POST['id_razmer'])) {
			
			$summa = $_POST['summa'];	
			$summa_blr = $_POST['summa'];	
				
		}
		
		if (isset($_POST['complect_razmer']) and !empty($_POST['complect_razmer'])) {
		
			$arr = get_arr_cena_complect($id_zakaz,$razmer);	
			$cena_karkas = $arr['cena_karkas_usd'];
			$cena_ydlinen = $arr['cena_ydlinen_usd'];		
			
			$cena_karkas_blr = $arr['cena_karkas'];
			$cena_ydlinen_blr = $arr['cena_ydlinen'];	
	
			if ($_POST['poly'] > 0) {
				$cena_poly = $arr['cena_poly_usd'];	
				$cena_poly_blr = $arr['cena_poly'];	
				$sess[$id_item]['name'] .= ' с поликарбонатом';				
			} else {
				$cena_poly = 0;		
			}
					
			$summa = @$cena_karkas + @$cena_ydlinen + @$cena_poly;
			$summa_blr = @$cena_karkas_blr + @$cena_ydlinen_blr + @$cena_poly_blr;				
		}

		$sess[$id_item]['cena'] = @$summa;
		$sess[$id_item]['cena_blr'] = @$summa_blr;
	
	}

	$_SESSION['collection'] = $sess;
	
}

function add_to_cart_only_post($post) {
		
	$id_zakaz = $post['pid'];
		
	if (isset($post['complect_razmer']) and $post['complect_razmer']!=0) $complect_razmer = $post['complect_razmer'];
	
	if (isset($post['id_razmer']) and !empty($post['id_razmer'])) $complect_razmer = $post['id_razmer'];

	if (isset($post['complect']) and !empty($post['complect'])) $complect_razmer = $post['complect'];
	
	add_to_cart_only($id_zakaz,$complect_razmer);		
}


?>