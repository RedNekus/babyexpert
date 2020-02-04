<?php

class Cart {

	public static function transformToComplect() {
		
		$sess = $_SESSION;
		
		foreach($sess['collection'] as $item) {
			
			add_to_cart_with_post($item);
			
		}
		
		return $_SESSION;
		
	}
	
	public static function addToCart() {
			
		$data = array();

		$sess = self::transformToComplect();
		
		if (isset($sess['collection'])) {	

			$data['succes'] = true;
			if ((@$sess['user']['id_adminuser']!=0) and (@$sess['user']['manager']!=0)) {
				$tovars = "";
				foreach($sess['collection'] as $zakaz) {
					
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
				
				if ($no = Zakaz_client::getLastNomer()) ++$no;
				else $no = 1;

				$phone = '';
				$sposob_dostavki = '';
				foreach($_POST['phone'] as $item) {	$phone .= $item['name'].','; }


				if ((@$sess['user']['id_adminuser']!=0) and (@$sess['user']['manager']!=0)) {
					$id_adminuser = @$sess['user']['id_adminuser'];
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
					$doplata_blr = $_POST['doplata_blr'];	
					$doplata_usd = $_POST['doplata_usd'];	

					if ((isset($_POST['samovivoz'])) or (isset($_POST['samovivoz_ofice']))) {	
						send_kladovshik_sms($_POST,$date_dostavka,$sess['collection']);
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
					$doplata_blr = '';					
					$doplata_usd = '';					
				}	
				
				$data_client = array(
					'nomer_zakaza' => $no,
					'phone' => $phone,										
					'firstname' => $_POST['firstname'],
					'email' => $_POST['email'],
					'city' => $_POST['city'],
					'poselok' => $_POST['poselok'],
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
					'print_ready' => $print_ready,
					'sposob_dostavki' => $sposob_dostavki,
					'doplata_usd' => $doplata_usd,					
					'doplata_blr' => $doplata_blr,
					'id_diler' => @$_POST['id_diler'],					
					'dumayut' => ((isset($_POST['dumayut'])) ? 1 : 0),						
					'beznal' => ((isset($_POST['beznal'])) ? 1 : 0)						
				);	
				Database::insert(get_table('zakaz_client'),$data_client);
				$last_client = Database::getLastId(get_table('zakaz_client'));
				$raffle = false;
				foreach($sess['collection'] as $item) {
					if (@$item['raffle']!=0) {
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
						'id_item' => $item['id'],
						'name_tovar' => $item['name'],
						'cena' => $item['cena'],
						'cena_blr' => $item['cena_blr'],
						'id_complect' => @$item['id_complect'],
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
						'dostavka' => @$item['dostavka']
					);
					Database::insert(get_table('zakaz'),$data_zakaz);
	
				}
			
				if (isset($_POST['id_diler']) and $_POST['id_diler']>0) update_to_diler_cena(
					array(
						'id_diler' => $_POST['id_diler'],
						'id' => $last_client
					));
					
									
				$sum = get_summa_by_client(Database::getRow(get_table('zakaz_client'),$last_client));
				$cena_rozn_usd = $sum['bur'] / get_kurs();
				$data_client = array('cena_rozn_usd'=>$cena_rozn_usd);
				Database::update(get_table('zakaz_client'),$data_client,'id='.$last_client);
										
			
				if (@$sess['user']['manager']==1) $text_tmp = "";
				else $text_tmp = "\nС Вами свяжутся для его подтверждения. ";
				
				$r_text = '';
				if ($raffle) $r_text = "При покупке данного товара Вы принимаете участие в РОЗЫГРЫШЕ.\nКод для участия в нем выдается при передаче товара.\n";
				
				$thx = "Спасибо, что выбрали нас.\nhttp://agrosila.pl";
				
				$text = "Ваш заказ $no. $text_tmp\n$r_text.$thx";
				
				$result = send_sms($text,$_POST['phone'][0]['name']);
						
				$data['url'] = '/dengi_za_otzyv#dzv';	
				$data['message'] = 'Ваш заказ оформлен<br/>Заказ №'.$no.'<br/>С Вами свяжутся для его подтверждения<br/><a href="/" title="На главную">На главную</a>';	
				unset($_SESSION['collection']);
			}
		}

		return $data;
	
	}
	
	public static function addToCartDiler() {
			
		$data = array();

		$tovars = "";
		foreach($sess['collection'] as $zakaz) {
			
			if (get_free_ostatok($zakaz['id_item']) < $zakaz['kolvo']) {
				if ($zakaz['predzakaz']!=1) {
					$data['succes'] = false;
					
					$maker = Maker::getMakerByID($zakaz['id_maker']);
					if (isset($maker['name'])) $maker_name = $maker['name'].' '; else $maker_name = '';	
					
					$tovars .= '<div style="color:red;">необходимого количества ('.$zakaz['kolvo'].' шт.) '.$maker_name.$zakaz['name'].' нет на складе!</div>';
				}
			}		
		}
		if ($data['succes'] == false) $data['message'] = $tovars;

		if ($data['succes'] == true) {
	
			$sess = $sess['user'];
			$phone = '';
			if ($sess['phone']!=0) $phone .= $sess['phone'];
			if ($sess['phone2']!=0) $phone .= ','.$sess['phone2'];
			if ($sess['phone3']!=0) $phone .= ','.$sess['phone3'];
		
			$no = Zakaz_client::getLastNomer();
			++$no;
				
			$data_client = array(
				'nomer_zakaza' => $no,
				'phone' => $phone,										
				'firstname' => $sess['name'],
				'email' => $sess['email'],
				'active' => 1,
				'street' => $sess['street'],
				'house' => $sess['house'],
				'building' => $sess['building'],
				'apartment' => $sess['apartment'],
				'floor' => $sess['floor'],
				'entrance' => $sess['entrance'],
				'date_zakaz' => date("Y-m-d"),
				'time_zakaz' => date("G:i:s"),					
				'date_dostavka' => date("Y-m-d"),					
				'dostavka' => 1,					
				'id_diler' => $sess['id']				
			);	
			Database::insert(get_table('zakaz_client'),$data_client);
			
			$last_client = Database::getLastId(get_table('zakaz_client'));
			foreach($sess['collection'] as $item) {
				$data_zakaz = array(
					'id_client' => $last_client,
					'nomer_zakaza' => $no,
					'id_catalog' => $item['id'],
					'id_item' => $item['id'],
					'name_tovar' => $item['name'],
					'cena' => $item['cena'],
					'cena_blr' => $item['cena_blr'],
					'kolvo' => $item['kolvo'],							
					'predzakaz' => $item['predzakaz'],							
					'date_predzakaz' => $item['date_predzakaz']							
				);
				Database::insert(get_table('zakaz'),$data_zakaz);
			}

								
			$sum = get_summa_by_client(Database::getRow(get_table('zakaz_client'),$last_client));
			$cena_rozn_usd = $sum['bur'] / get_kurs();
			$data_client = array('cena_rozn_usd'=>$cena_rozn_usd);
			Database::update(get_table('zakaz_client'),$data_client,'id='.$last_client);
							
			$data['succes'] = true;
			$data['backurl'] = true;	
			$data['message'] = 'Ваш заказ оформлен<br/>Заказ №'.$no.'<br/>С Вами свяжутся для его подтверждения<br/><a href="/" title="На главную">На главную</a>';	
			unset($_SESSION['collection']);
		}
			
		return $data;	
	}
 
    
}