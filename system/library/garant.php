<?php

function get_garant_talon($id) {
	
	$item = Database::getRow(get_table('zakaz'),$id);
				
	$product = Database::getRow(get_table('catalog'),$item['id_catalog']);
	$name = get_product_name($product,true,$item['name_tovar']);
	$cena_bur = with_skidka($item,'bur');
	$promocods = Database::getRows(get_table('promocode'),'id','asc',false,'active=1');
	$promocod = $promocods[rand(0,count($promocods))]['name'];

	$html = '<table class="print">
	<tr><td colspan=2 class="header text-center"><b>Гарантийный талон<b/></td></tr>
	
	<tr><td class="shapka">Наименование: </td><td class="shapka"><b>www.babyexpert.by<b/></td></tr>
	
	<tr><td class="shapka w70"><b>'.$name.'</b></td><td class="shapka">8 (033) 66-101-66 - МТС</td></tr>

	<tr><td class="shapka w70">Дата продажи: «___» ____________ '.date('Y').' г.</td><td class="shapka">8 (029) 66-101-66 - VELCOM</td></tr>	
																				
	<tr><td class="shapka w70">Стоимость:     '.formatCena($cena_bur).' рублей</td><td class="shapka">8 (025) 66-101-66 - Life :)</td></tr>							

	<tr><td class="shapka">Подпись продавца: _______________</td><td class="shapka">заказ № '.$item['nomer_zakaza'].'</td></tr>					

	<tr><td colspan=2 class="text-center"></br></br><b>Уважаемый покупатель!</b></td></tr>
	<tr><td colspan=2 class="text-center">Благодарим Вас за Ваш выбор  и покупку данного изделия.</td></tr>

	<tr><td colspan=2><span class="otstup-0">&nbsp;</span> <span class="inline-block"><b>Условия гарантийного обслуживания:</b></span></td></tr>
	<tr><td colspan=2 class="text-justify">
	<div><span class="otstup-0">1.</span>	<span class="inline-block w0">При покупке изделия требуйте его проверки в Вашем присутствии, в случае отказа звоните по вышеуказанным номерам.</span></div>
	<div><span class="otstup-0">2.</span>	<span class="inline-block w0">Срок бесплатного гарантийного обслуживания изделия составляет ___ месяцев со дня продажи через розничную сеть, при соблюдении покупателем условий эксплуатации (следуя инструкции), хранения и транспортировки.</span></div>
	<div><span class="otstup-0">3.</span>	<span class="inline-block w0">Покупатель должен внимательно ознакомиться с инструкцией по эксплуатации покупаемого изделия.</span></div>
	<div><span class="otstup-0">4.</span>	<span class="inline-block w0">Изделие принимается по гарантии только в полном комплекте в чистом сухом виде и с правильно заполненным гарантийным талоном (датой продажи, подписью продавца и покупателя). Если не соблюдены эти условия, претензии не принимаются).</span></div>
	<div><span class="otstup-0">5.</span>	<span class="inline-block w0">Неисправное изделие доставляется в сервисный центр продавца покупателем самостоятельно.</span></div>
	<div><span class="otstup-0">6.</span>	<span class="inline-block w0">Рекламация на неисправное изделие исчисляется с момента поступления изделия в сервисный центр продавца.</span></div>
	<div><span class="otstup-0">7.</span>	<span class="inline-block w0">Рассмотрение рекламации производится в течение 14 рабочих дней. В случае если потребуется дополнительная проверка причины поломки, рассмотрение рекламации может продлиться до 30 календарных дней. После истечения указанного срока, продавец обязуется предоставить покупателю отремонтированное или, на свое усмотрение, аналогичное новое изделие. </span></div>
	<div><span class="otstup-0">8.</span>	<span class="inline-block w0">Владелец изделия теряет право на бесплатный ремонт или замену изделия в период гарантийного срока в следующих случаях:</span></div>
	
	<div><span class="otstup-1">8.1.</span>	<span class="inline-block w1">При отсутствии гарантийного талона.</span></div>
	<div><span class="otstup-1">8.2.</span>	<span class="inline-block w1">При неправильной эксплуатации изделия с нарушением приложенной к товару инструкции по эксплуатации, при небрежном отношении, нарушении правил безопасности.</span></div>
	<div><span class="otstup-1">8.3.</span>	<span class="inline-block w1">Ремонта или модификации изделия, выполненной кем-либо, кроме работников сервисного цента.</span></div>
	<div><span class="otstup-1">8.4.</span>	<span class="inline-block w1">После воздействия на изделие:</span></div>
	
	<div><span class="otstup-2">•</span> <span class="inline-block">химически активной среды;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">инородных красителей;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">колюще-режущих средств, вызывающих разрывы и прочие повреждения тканей, образующих  царапины на металлических, пластмассовых, резиновых и прочих частях изделия;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">различных загрязнителей (песка, глины, грязи, мелкого щебня и т.д.), вызывающего заклинивание механизмов изделия;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">удара, неадекватного силового нажима на рычаги, кнопки и задвижки механизмов изделия, неумеренного силового воздействия (например, при выкручивании (выжимании) тканевых частей изделия, вызывающем расхождение тканевых швов и т.д.), давления других предметов с острыми гранями;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">насекомых и грызунов;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">избыточного давления;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">непреодолимой силы (пожар, наводнение, наезды транспорта и т.д.).</span></div>
	
	<div><span class="otstup-1">8.5.</span>	<span class="inline-block w1">Сервисное обслуживание в течение гарантийного срока производилось неуполномоченным лицом.</span></div>
	<div><span class="otstup-1">8.6.</span>	<span class="inline-block w1">Повреждения вызваны воздействием стихийный бедствий, природных факторов.</span></div>
	
	<div><span class="otstup-0">9.</span> <span class="inline-block w0">Не принимаются претензии, связанные с естественным износом изделия (потертости на тканях, износ шин, цветовое выгорание ткани или прочих деталей и механизмов изделия под воздействием открытого солнечного света, естественное увеличение люфта в парах трения-качения/скольжения).</span></div>
	<div><span class="otstup-0">10.</span> <span class="inline-block w0">Гарантия не распространяется на следующие элементы изделия:</span></div> 
	
	<div><span class="otstup-2">•</span> <span class="inline-block">все элементы, выполненные из ткани, резины и клеенки;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">люльки-корзины;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">шины и покрышки;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">молнии и заклепки;</span></div>
	<div><span class="otstup-2">•</span> <span class="inline-block">корзины для покупок, дождевики, москитные сетки, сумки и крепления к ним;</span></div></td></tr>

	<tr><td colspan=2 class="border-bot">С условиями гарантийного обслуживания и инструкцией по применению ознакомлен, изделие передано в полном комплекте без механических и прочих повреждений, 
	претензий к внешнему виду и комплектации изделия не имею </br></br>_______________________________________________________ </br>                  
	<span class="otstup-25">(покупатель: подпись, расшифровка)</span>
	</td></tr>
	<tr><td colspan=2 class="bottom-text">
		</br>
		<div>Внимание, акция. При оставлении отзыва на www.babyexpert.by о прибретенном товаре</div>
		<div>Ваш промо код для для получения денег за отзыв <b>'.$promocod.'</b></div>
		<div>Подробности на www.babyexpert.by в карточке приобретенного Вами товара.</div>	
	</td></tr>
	<tr><td colspan=2 class="header text-center pad-t"><b>ПРИ ПРЕДЪЯВЛЕНИИ ГАРАНТИЙНОГО ТАЛОНА СКИДКА 3%</b></td></tr> 
</table>';

	return $html;
}
