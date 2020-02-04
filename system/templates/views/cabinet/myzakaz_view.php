<?php
  UI::addCSS('/css/cabinet/myzakaz.css'); 
?>
<script type="text/javascript"> 
	$(document).ready(function(){
		
	//Set default open/close settings
	$('.admin_content').hide(); //Hide/close all containers
	$('.admin_menu:first').addClass('active').next().show(); //Add "active" class to first trigger, then show/open the immediate next container
	 
	//On Click
	$('.admin_menu').click(function(){
		if( $(this).next().is(':hidden') ) { //If immediate next container is closed...
			$('.admin_menu').removeClass('active').next().slideUp(); //Remove all .admin_menu classes and slide up the immediate next container
			$(this).toggleClass('active').next().slideDown(); //Add .admin_menu class to clicked trigger and slide down the immediate next container
		}
		return false; //Prevent the browser jump to the link anchor
	});
	 
	});
</script>
<h1><?php echo $h1item; ?></h1>
<div id="myzakaz">
	<div class="first-block">
	<?php foreach($items as $elem): ?>
		<div class="admin_menu">
			<span class="openlist"></span>
			<div class="date"><?php echo $elem['date_zakaz']; ?></div>
			<div class="summa">Сумма: <b>156 000</b></div>
		</div>
		<div class="admin_content"> 
		<?php 
		$zakazs = Registration_zakaz::getZakazs($ses_id,$elem['date_zakaz']);

		foreach($zakazs as $item_s):  
			
			$item = Catalog::getCollectionByID($item_s['id_catalog']);		 
			$total_cena = $item_s['kolvo'] * intval($item['cena_blr']);
			$summa = $total_cena + @$summa;
			?>
				<div class="line-block">
					<div class="th-image">
						<?php if ($images = Images::getImagesByIdCatalog($item['id'])): ?>
							<img src="<?php echo $imagepath['small']['path'].$images[0]['image']; ?>" width="82" height="82" alt="">
						<?php else: ?>
							<img src="/img/bg.png" width="82" height="82" alt="">
						<?php endif; ?>
					</div>
					<div class="th-name">
						<div class="name"><?php echo $item['name']; ?></div>
					</div>		
					<div class="th-qty">
						<div class="qty">
							<?php echo $item_s['kolvo']; ?>
						</div>
					</div>	
					<div class="th-total-cena">
						<div class="cena"><strong><?php echo number_format(@$total_cena, 0 , '', ' '); ?></strong> бел.руб.</div>
					</div>				
				</div>								
		<?php endforeach; ?>
		<a href="" class="btn_eshe"></a>
		<div class="summa">Сумма: <b><?php echo formatCena($summa); ?></b></div>
		</div>		
	<?php endforeach; ?>
	</div>	
</div>