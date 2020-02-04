<?php

function get_sklad_real_ostatok($id_item,$id_sklad) {
	$result = 0;
	$table = get_table('sklad_tovar');
	$where = "id_item = '$id_item' and status = 2 and id_sklad = $id_sklad";
	$items = Database::getRows($table,'id','asc',false,$where);
	foreach($items as $item) {
		$result += $item['kolvo_hold'];
	}
	return $result;

}

function get_free_ostatok($id_item) {
	$result = 0;
	if (get_real_ostatok($id_item)>0) {
		$kolvo = 0;
		$real_ostatok = get_real_ostatok($id_item);
		$rezerv = get_rezerv($id_item);
		$vns = get_vozvrat_na_sklad($id_item);
		$dt = get_dostavka_tovara($id_item);
		$result = $real_ostatok - $rezerv - $vns - $dt; 
		//if ($result < 0) $result = 0;
	}
	return $result;
}

function get_real_ostatok_sklad($id_item) {
	$result = 0;
	if (get_real_ostatok($id_item)>0) {
		$kolvo = 0;
		$real_ostatok = get_real_ostatok($id_item);
		$dt = get_dostavka_tovara($id_item);
		$result = $real_ostatok - $dt; 
		if ($result < 0) $result = 0;
	}
	return $result;

}

function get_real_ostatok($id_item) {
	return Database::getField(get_table('catalog'),$id_item,'id','kolvo');
}

function get_rezerv($id_item) {
	$result = 0;
	if (get_real_ostatok($id_item)>0) {
		$kolvo = 0;
		$table = get_table('zakaz');
		$where = "id_item = '$id_item' and rezerv = 1";
		$items = Database::getRows($table,'id','asc',false,$where);	
		foreach($items as $item) {
			$result += $item['kolvo'];
		}
	}	
	return $result;
}

function get_predzakaz($id_item) {
	$result = 0;

		$table = get_table('zakaz');
		$where = "id_item = '$id_item' and predzakaz = 1";
		$items = Database::getRows($table,'id','asc',false,$where);	
		foreach($items as $item) {
			$result += $item['kolvo'];
		}	

	return $result;
}

function get_ozhidaemiy_prihod($id_item) {
	$result = 0;
	if (get_real_ostatok($id_item)>0) {	
		$table = get_table('sklad_tovar');
		$where = "id_item = '$id_item' and status = 1";
		$items = Database::getRows($table,'id','asc',false,$where);
		foreach($items as $item) {
			$result += $item['kolvo_hold'];
		}	
	}
	return $result;
}

function get_vozvrat_na_sklad($id_item) {
	$result = 0;
	if (get_real_ostatok($id_item)>0) {
		$zakaz = get_table('zakaz');
		$zakaz_client = get_table('zakaz_client');
		$table = "$zakaz as t1 JOIN $zakaz_client as t2 ON t1.id_client = t2.id";	
		$where = "t1.id_item = '$id_item' 
			and t1.shipped = 0
			and t1.delivered = 0 
			and t1.rezerv = 0 
			and t1.predzakaz = 0 
			and t2.dostavka = 1 
			and t1.vozvrat = 0
			and t1.nosell = 0
			and t1.otkaz = 0";
		$items = Database::getRows($table,'t1.id','asc',false,$where);
		foreach($items as $item) {
			$result += $item['kolvo'];
		}
	}
	return $result;
}

function get_dostavka_tovara($id_item) {
	$result = 0;
	if (get_real_ostatok($id_item)>0) {
		$zakaz = get_table('zakaz');
		$zakaz_client = get_table('zakaz_client');
		$table = "$zakaz as t1 JOIN $zakaz_client as t2 ON t1.id_client = t2.id";	
		$where = "t1.id_item = '$id_item' 
			and t1.shipped = 1
			and t1.delivered = 0 
			and t1.rezerv = 0 
			and t1.predzakaz = 0 
			and t2.dostavka = 1 
			and t1.vozvrat = 0
			and t1.nosell = 0";
		$items = Database::getRows($table,'t1.id','asc',false,$where);
		foreach($items as $item) {
			$result += $item['kolvo'];
		}
	}
	return $result;
}

function get_brak($id_item) {
	$result = 0;
	//if (get_real_ostatok($id_item)>0) {
		$kolvo = 0;
		$table = get_table('zakaz');
		$where = "id_item = $id_item and vozvrat = 3";
		$items = Database::getRows($table,'id','asc',false,$where);	
		foreach($items as $item) {
			$result += $item['kolvo'];
		}
		$result += get_brak_tmc($id_item);
		
	//}	
	return $result;
}

function get_brak_tmc($id_item) {
	$result = 0;
	if (get_real_ostatok($id_item)>0) {	
	$items = Database::getRows(get_table('sklad_tovar'),'id','asc',false,"id_item=$id_item and id_sklad=7");
		foreach($items as $item) {
			$result += $item['kolvo_hold'];
		}
	}	
	return $result;		
}