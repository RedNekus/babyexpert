<?php 

	UI::addCSS(array(
		'/css/raffle/list.css'
	));
		
	UI::addJS(array(
		'/js/datetime.js'
	));
		
?>

<div class="h-mb">
	<div class="b-raffle">
		<ul class="list">
			<?php foreach($items as $item):  	
				
				$raffle_id = $item['id'];
				$count1 = Zakaz::getCountByDate($raffle_id, $item['timestamp'], $item['timestampend']); 
				$count2 = Zakaz::getCountByDate($raffle_id, $item['timestamp2'], $item['timestampend2']); 
				$count3 = Zakaz::getCountByDate($raffle_id, $item['timestamp3'], $item['timestampend3']); 
				$count4 = Zakaz::getCountByDate($raffle_id, $item['timestamp4'], $item['timestampend4']); 
				$count5 = $count1 + $count2 + $count3 + $count4;
			?>			
			<li>
				<div class="pict">
					<a href="/raffle/<?php echo $item['path']; ?>/5" class="name"><?php echo $item['name']; ?></a>
					<a href="/raffle/<?php echo $item['path']; ?>/5" class="image">
						<img src="<?php echo $imagepath.$item['image']; ?>" alt="<?php echo $item['name']; ?>">
					</a>
				</div>
				<div class="details">
				
					<div class="det-lines">
						<div class="det-block date">
							<?php echo transform_norm_date($item['timestamp']); ?> - <?php echo transform_norm_date($item['timestampend']); ?>
						</div>
						<div class="det-block time">
							<?php echo get_raffle_form_by_date($item['timestamp'],$item['timestampend'],1); ?>
						</div>
						<div class="det-block players">
							Учавствует: <b><?php echo $count1; ?></b>
						</div>
						<a href="/raffle/<?php echo $item['path']; ?>/1" class="det-block button">Подробнее</a>
					</div>	
					
					<div class="det-lines">
						<div class="det-block date">
							<?php echo transform_norm_date($item['timestamp2']); ?> - <?php echo transform_norm_date($item['timestampend2']); ?>
						</div>
						<div class="det-block time">
							<?php echo get_raffle_form_by_date($item['timestamp2'],$item['timestampend2'],2); ?>
						</div>
						<div class="det-block players">
							Учавствует: <b><?php echo $count2; ?></b>
						</div>
						<a href="/raffle/<?php echo $item['path']; ?>/2" class="det-block button">Подробнее</a>
					</div>
					
					<div class="det-lines">
						<div class="det-block date">
							<?php echo transform_norm_date($item['timestamp3']); ?> - <?php echo transform_norm_date($item['timestampend3']); ?>
						</div>
						<div class="det-block time">
							<?php echo get_raffle_form_by_date($item['timestamp3'],$item['timestampend3'],3); ?>
						</div>
						<div class="det-block players">
							Учавствует: <b><?php echo $count3; ?></b>
						</div>
						<a href="/raffle/<?php echo $item['path']; ?>/3" class="det-block button">Подробнее</a>
					</div>	
					
					<div class="det-lines">
						<div class="det-block date">
							<?php echo transform_norm_date($item['timestamp4']); ?> - <?php echo transform_norm_date($item['timestampend4']); ?>
						</div>
						<div class="det-block time">
							<?php echo get_raffle_form_by_date($item['timestamp4'],$item['timestampend4'],4); ?>
						</div>
						<div class="det-block players">
							Учавствует: <b><?php echo $count4; ?></b>
						</div>
						<a href="/raffle/<?php echo $item['path']; ?>/4" class="det-block button">Подробнее</a>
					</div>	
					
					<div class="det-lines last">
						<div class="det-block date">
							<div>Второй шанс</div>
							(<?php echo transform_norm_date($item['timestamp']); ?> - <?php echo transform_norm_date($item['timestampend4']); ?>)
						</div>
						<div class="det-block time">
							<?php echo get_raffle_form_by_date($item['timestamp'],$item['timestampend4'],5); ?>
						</div>
						<div class="det-block players">
							Учавствует: <b><?php echo $count5; ?></b>
						</div>
						<a href="/raffle/<?php echo $item['path']; ?>/5" class="det-block button">Подробнее</a>
					</div>
					
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>