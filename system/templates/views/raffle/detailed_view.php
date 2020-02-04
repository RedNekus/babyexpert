<?php
	UI::addCSS(array(
		'/css/raffle/detailed.css',
		'/js/fancybox2/jquery.fancybox.css',
		'/js/fancybox2/helpers/jquery.fancybox-buttons.css',
	));
		
	UI::addJS(array(
		'/js/datetime.js'
	));
	
	$i = 1;
?>
<div class="raffle-detailed-content">
	<div class="left-raffle">
		<div class="l-image-raffle">
			<a href="<?php echo $imagepath['big']['path'].$item['image']; ?>" class="image fancybox">
				<img src="<?php echo $imagepath['big']['path'].$item['image']; ?>" alt="<?php echo $item['name']; ?>">
			</a>
		</div>
		<div class="l-timer-raffle">
			<?php if (($date_ot < date('Y-m-d')) and ($date_do > date('Y-m-d'))): ?>
			<div class="l-head-raffle">часов/минут/секунд</div>
			<div class="l-body-raffle"><?php echo get_raffle_form_by_date($date_ot,$date_do,URL::getSegment(4)); ?></div>
			<?php else: ?>
			<div class="l-body-raffle text"><?php echo get_raffle_form_by_date($date_ot,$date_do,URL::getSegment(4)); ?></div>
			<?php if(!empty($item['video_url'])): ?>
			<div class="l-link-video"><a href="<?php echo $item['video_url']; ?>" target="_ablank" title="Смотреть видео розыгрыша">Смотреть видео розыгрыша</a></div>
			<?php endif; ?>
			<?php endif; ?>
		</div>
		<div class="l-descr-raffle">
			<h2>Условия розыгрыша</h2>
			<div><?php echo $item['short_description']; ?></div>
		</div>
	</div>
	
	<div class="right-raffle">
		<table class="table-raffle">
			<tr class="head-raffle">
				<td class="head-nomer">№</td>
				<td class="head-nome-zakaza">Номер заказа</td>
				<td class="head-name">Участник</td>
			</tr>
			<?php 
			foreach($zakazs as $zakaz) { 
				$client = Zakaz_client::getClientById($zakaz['id_client']);
				if (isset($client['id'])) {
				
					if ($zakaz['winner']!=0) {
						$class = 'class="winner"';
						$text = 'Победитель в '.$zakaz['winner'].' розыгрыше';
					} else {
						$class = '';
						$text = $client['firstname'];
					}
				
					echo '<tr '.$class.'>';
					echo '	<td>'.$i++.'</td>';
					echo '	<td>'.$client['nomer_zakaza'].'</td>';
					echo '	<td>'.$text.'</td>';
					echo '</tr>';			
				} 
			}
			?>
		</table>
	</div>
</div>



