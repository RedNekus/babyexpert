<?php

function get_status($status_id, $format = false) {
	
	$result = "";

	switch ($status_id) {
		case 0: $result = "Не определено"; break;
		case 1: $result = "Наличие уточняйте"; break;
		case 2: $result = "Нет в наличии"; break;
		case 3: $result = "Под заказ"; break;
		case 4: $result = "Нет в наличии"; break;
		case 5: $result = "Скоро в продаже"; break;
		case 6: $result = "В наличии"; break;
		case 7: $result = "Наличие уточняйте"; break;
		case 8: $result = "Наличие уточняйте"; break;		
	}
	if((@$_SESSION['user']) && (@$_SESSION['user']['active']==1)) {
		if ($format) return $result;
		else echo $result;
	} else {
		return "";
	}
}

function get_status_to_pokupay($status_id) {
	
	$result = "";

	switch ($status_id) {
		case 0: $result = "Не определено"; break;
		case 1: $result = "Наличие уточняйте"; break;
		case 2: $result = "Нет в наличии"; break;
		case 3: $result = "Под заказ"; break;
		case 4: $result = "Нет в наличии"; break;
		case 5: $result = "Скоро в продаже"; break;
		case 6: $result = "В наличии"; break;
		case 7: $result = "Наличие уточняйте"; break;
		case 8: $result = "Наличие уточняйте"; break;	
	}
	
	return $result; 
}

function get_status_to_migom($status_id) {
	
	$result = "";

	switch ($status_id) {
		case 0: $result = "Не определено"; break;
		case 1: $result = "Наличие уточняйте"; break;
		case 2: $result = "Нет в наличии"; break;
		case 3: $result = "Под заказ"; break;
		case 4: $result = "Нет в наличии"; break;
		case 5: $result = "Наличие уточняйте"; break;
		case 6: $result = "В наличии"; break;
		case 7: $result = "Наличие уточняйте"; break;
		case 8: $result = "Наличие уточняйте"; break;
	}
	
	return $result; 
}

function get_status_to_onliner($status_id) {
	
	$result = "";

	switch ($status_id) {
		case 0: $result = "Не определено"; break;
		case 1: $result = "склад"; break;
		case 2: $result = "нет"; break;
		case 3: $result = "заказ"; break;
		case 4: $result = "нет"; break;
		case 5: $result = "склад"; break;
		case 6: $result = "склад"; break;
		case 7: $result = "заказ"; break;
		case 8: $result = "склад"; break;
	}
	
	return $result; 
}

function get_status_to_1k($status_id) {
	
	$result = "";

	switch ($status_id) {
		case 0: $result = "надо уточнять"; break;
		case 1: $result = "на складе"; break;
		case 2: $result = "нет в наличии"; break;
		case 3: $result = "под заказ"; break;
		case 4: $result = "нет в наличии"; break;
		case 5: $result = "ожидаем"; break;
		case 6: $result = "на складе"; break;
		case 7: $result = "надо уточнять"; break;
		case 8: $result = "надо уточнять"; break;		
	}
	
	return $result; 
}

function get_status_vozvrat($status) {
	
	$result = "";

	switch ($status) {
		case 0: $result = "нет"; break;
		case 1: $result = "склад"; break;
		case 2: $result = "офис"; break;
		case 3: $result = "брак"; break;
	}
	
	return $result; 
}

function razdel_convert($str) {

	$result = $str;

	switch ($str) {
		case "Велосипеды": $result = "Детские велосипеды"; break;
		case "Коляски": $result = "Детские коляски"; break;
		case "Кроватки": $result = "Детские кроватки"; break;
		case "Электромобили": $result = "Детские электромобили"; break;
		case "Стульчики": $result = "Стульчики для кормления"; break;
		case "Видеоняни": $result = "Радионяни и видеоняни"; break;
		case "Радионяни": $result = "Радионяни и видеоняни"; break;
		case "Манежи": $result = "Детские манежи"; break;
		case "Комоды": $result = "Детские комоды"; break;
		case "Детские парты": $result = "Детские столы и парты"; break;
	}
	
	
	return $result;

}

function maker_convert($str) {

	$result = $str;

	switch ($str) {
		case "FD Design (ABC Design)": $result = "ABC Design"; break;
		case "Bertoni (Lorelli)": $result = "Bertoni"; break;
		case "STM (Recaro)": $result = "STM"; break;
		case "Peg-Perego": $result = "Peg Perego"; break;
		case "Лель": $result = "ЛЕЛЬ"; break;	
		case "Philips Avent": $result = "Philips"; break;		
	}

	
	
	return $result;

}

function name_convert($str, $portals, $id_maker) {

	$result = $str;

	foreach($portals as $item) {

		if ($item['name_site']==$str) {
			if ($item['id_maker']!=0) {
				if ($item['id_maker'] == $id_maker) {
					$result = $item['name_portal'];
					break;
				} else {
					continue;
				}	
			} else {
				$result = $item['name_portal'];
				break;	
			}
			
		}
	} 			

	return $result;

}


function get_comment_by_razdel($r_name) {
	
	$result = "";

	switch ($r_name) {
		case "Детские велосипеды": $result = ""; break;
		case "Детские коляски": $result = "Гарантия 12 месяцев. В наличии и под заказ! Информацию по наличию уточняйте у наших менеджеров по контактным телефонам. Работаем без выходных. Всю подробную информацию о товарах Вы можете получить на сайте www.babyexpert.by. Skype: babyexpert.by"; break;
		case "Детские кроватки": $result = "Гарантия 12 месяцев. В наличии и под заказ! Информацию по наличию уточняйте у наших менеджеров по контактным телефонам. Работаем без выходных. Всю подробную информацию о товарах Вы можете получить на сайте www.babyexpert.by. Skype: babyexpert.by"; break;
		case "Детские электромобили": $result = ""; break;
		case "Автокресла": $result = "Гарантия 12 месяцев. В наличии и под заказ! Информацию по наличию уточняйте у наших менеджеров по контактным телефонам. Работаем без выходных. Всю подробную информацию о товарах Вы можете получить на сайте www.babyexpert.by. Skype: babyexpert.by"; break;
		case "Стульчики для кормления": $result = "Гарантия 6-12 месяцев. В наличии и под заказ! Информацию по наличию уточняйте у наших менеджеров по контактным телефонам. Работаем без выходных. Всю подробную информацию о товарах Вы можете получить на сайте www.babyexpert.by. Skype: babyexpert.by"; break;
		case "Системы наблюдения за ребенком": $result = "В наличии и под заказ! Информацию по наличию уточняйте у наших менеджеров по контактным телефонам. Постоянным клиентам скидки! Работаем без выходных. Всю подробную информацию о товарах Вы можете получить на сайте www.babyexpert.by. Skype: babyexpert.by"; break;
		case "Подогреватели и стерилизаторы": $result = "В наличии и под заказ! Информацию по наличию уточняйте у наших менеджеров по контактным телефонам. Постоянным клиентам скидки! Работаем без выходных. Всю подробную информацию о товарах Вы можете получить на сайте www.babyexpert.by. Skype: babyexpert.by"; break;
		case "Детские манежи": $result = "Гарантия 6-12 месяцев. В наличии и под заказ! Информацию по наличию уточняйте у наших менеджеров по контактным телефонам. Работаем без выходных. Всю подробную информацию о товарах Вы можете получить на сайте www.babyexpert.by. Skype: babyexpert.by"; break;
		case "Детские комоды": $result = "Гарантия 12 месяцев. В наличии и под заказ! Информацию по наличию уточняйте у наших менеджеров по контактным телефонам. Работаем без выходных. Всю подробную информацию о товарах Вы можете получить на сайте www.babyexpert.by. Skype: babyexpert.by"; break;
	
	}
	
	return $result; 
}

function get_status_spros($status) {
	
	$result = "";

	switch ($status) {
		case 1: $result = "Спрос"; break;
		case 2: $result = "Отказ"; break;
		case 3: $result = "Думают"; break;
		case 4: $result = "В работе"; break;
		case 5: $result = "Рекламация"; break;
	}
	
	return $result; 
}

function get_type_complect($status) {
	
	$result = "";

	switch ($status) {
		case 1: $result = "Каркас"; break;
		case 2: $result = "Удлинение"; break;
		case 3: $result = "Поликарбонат"; break;
		case 4: $result = "Мойка"; break;
		case 5: $result = "Рукомойник"; break;
		case 6: $result = "Тумба"; break;
		case 7: $result = "Бак"; break;
		case 8: $result = "Кабина"; break;
		case 9: $result = "Расцветка"; break;
		case 10: $result = "Сборка"; break;
	}
	
	return $result; 
}

function get_razmer_complect($status) {
	
	$result = "";

	switch ($status) {
		case 1: $result = "3 х 4 х 2,1"; break;
		case 2: $result = "3 х 6 х 2,1"; break;
		case 3: $result = "3 х 8 х 2,1"; break;
		case 4: $result = "3 х 10 х 2,1"; break;
	}
	
	return $result; 
}

function razdel_convert_unishop($str) {

	$result = $str;

	switch ($str) {
		case "ТЕПЛИЦЫ ИЗ ПОЛИКАРБОНАТА": $result = "Теплицы и парники"; break;
		case "НАСОСНОЕ ОБОРУДОВАНИЕ": $result = "Насосное оборудование"; break;
		case "ДУШИ ДЛЯ ДАЧИ": $result = "Баки и емкости"; break;
		case "КОМПЛЕКТЫ СКЛАДНОЙ МЕБЕЛИ": $result = "Наборы складной мебели"; break;
		case "АККУМУЛЯТОРЫ ХОЛОДА": $result = "Аккумуляторы холода"; break;
		case "ТЕРМОСУМКИ": $result = "Термосумки и термоконтейнеры"; break;
		case "КАРКАСНЫЕ БАССЕЙНЫ": $result = "Бассейны"; break;
		case "БАССЕЙН-ДЖАКУЗИ": $result = "Бассейны"; break;
		case "СЕМЕЙНЫЕ БАССЕЙНЫ": $result = "Бассейны"; break;
		case "БИОТУАЛЕТЫ": $result = "Биотуалеты"; break;
		case "СУМКИ-ТЕЛЕЖКИ": $result = "Дорожные сумки"; break;
		case "ДУШЕВЫЕ КАБИНЫ": $result = "Дачные души"; break;
		case "УМЫВАЛЬНИКИ ДЛЯ ДАЧИ": $result = "Дачные умывальники"; break;
		case "ШАТРЫ И ТЕНТЫ": $result = "Шатры и тенты"; break;
		case "САДОВЫЕ КАЧЕЛИ": $result = "Садовые качели"; break;
		case "ДВОЙНЫЕ КОМПЛЕКТЫ": $result = "Наборы садовой мебели"; break;
		case "ТРОЙНЫЕ КОМПЛЕКТЫ": $result = "Наборы садовой мебели"; break;
		case "САДОВОЕ ХРАНЕНИЕ": $result = "Хозяйственные постройки"; break;	
	}
	
	
	return $result;

}
