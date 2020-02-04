<?php 

  UI::addCSS(array(
	
    '/css/catalog/list.css'
	
  ));

?>
<div class="h-mb">
	<div class="tm-filtr-top">
		<div class="b-catalog-sm-filter">
			<form method="GET" action="">
				<label>Цена:</label>
				<div class="w-input small">
					<input name="price_ot" type="text" value="<?php echo (@$_GET['price_ot'])? $_GET['price_ot']:'от'; ?>" class="f-pricefrom"><i class="cl"></i><i class="cr"></i>
				</div>
				<div class="w-input small">
					<input name="price_do" type="text" value="<?php echo (@$_GET['price_do'])? $_GET['price_do']:'до'; ?>" class="f-priceto"><i class="cl"></i><i class="cr"></i>
				</div>
				<div class="w-select type">
					<select name="tip_catalog">
						<option value="">Выберите тип...</option>
						<?php 
						foreach($tips_catalog as $item) {
							$sel = (($item['id']==@$_GET['tip_catalog']) or ($item['id']==@$_GET['tip_catalog_url']))? 'selected' :	$sel = '';
							echo '<option value="'.$item['id'].'" '.$sel.'>'.$item['name'].'</option>';
						} 
						?>
					</select>
				</div>
				<div class="w-select mfa">
					<select name="maker">
						<option value="">Производитель...</option>
						<?php 
						foreach($makers as $maker) {
							$sel = ($maker['id']==@$_GET['maker'])? 'selected' :	$sel = '';
							echo '<option value="'.$maker['id'].'" '.$sel.'>'.$maker['name'].'</option>';
						}
						?>
					</select>
				</div>
				<input type="submit" value="Подобрать">
			</form>
		</div>
		<div class="b-catalog-sort">
			<form method="post">
				<ul class="b-view-switcher">
					<?php if (@$vmode=="list") :?>
						<li><a href="?vmode=grid">&nbsp;</a></li>
						<li class="active"><span>&nbsp;</span></li>
					<?php else: ?>
						<li class="active"><span>&nbsp;</span></li>
						<li><a href="?vmode=list">&nbsp;</a></li>				
					<?php endif; ?>
				</ul>
				<div class="w-select sort">
					<select id="sort-select" name="row_sorted" onChange="if (this.selectedIndex+1) this.form.submit()">
						<option value="id-asc" <?php if(@$_SESSION['row_sorted']=="id-asc"): ?>selected<?php endif; ?>>Сортировать по...</option>
						<option value="name-ASC" <?php if(@$_SESSION['row_sorted']=="name-ASC"): ?>selected<?php endif; ?>>названию А-Я</option>
						<option value="name-DESC" <?php if(@$_SESSION['row_sorted']=="name-DESC"): ?>selected<?php endif; ?>>названию Я-А</option>
						<option value="cena-ASC" <?php if(@$_SESSION['row_sorted']=="cena-ASC"): ?>selected<?php endif; ?>>цене начать с дешевых</option>
						<option value="cena-DESC" <?php if(@$_SESSION['row_sorted']=="cena-DESC"): ?>selected<?php endif; ?>>цене начать с дорогих</option>					
					</select>
				</div>	
			</form>
		</div>	
	</div>
	<div class="b-products">
		<div class="toolbar top">
			<h1><?php echo $h1item; ?></h1>
			<span class="pagination">
				<?php echo @$pagination; ?>
			</span>
		</div>
		<?php if (@$vmode=="list"): ?>
		<ul class="list">
		<?php foreach($collections as $items) : ?>
			<?php foreach($items as $item) : ?>
			<li>
				<div class="pict">
					<a href="/product/<?php echo $item['path']; ?>/" class="name"><?php echo get_product_name($item); ?></a>
					<a href="/product/<?php echo $item['path']; ?>/" class="image">
						<img src="<?php echo $imagepath['small']['path'].insert_image($item['id']); ?>" alt="<?php echo $item['name']; ?>">
					</a>
					<div class="buttons">
						<a href="/product/<?php echo $item['path']; ?>/#pit-tab-3" class="kol-colors"><?php get_count_colors($item['id']); ?></a>
						<a href="#" class="status r<?php echo $item['status']; ?>"><?php get_status($item['status']); ?></a>
						<a href="/product/<?php echo $item['path']; ?>/" class="btn-details">Подробнее</a>
						<?php echo get_buttons_catalog($item['id']); ?>
						<?php if ($item['status'] != 4): ?>
						<span class="price">Цена: <?php echo transform_to_currency($item); ?></span>
						<?php else: ?>
						<a href="#" class="status nvn">Снят с производства</a>
						<?php endif; ?>	
					</div>					
					<?php if ($item['new']==1): ?><i class="icon new"></i>
					<?php elseif ($item['hit']==1): ?><i class="icon best"></i><?php endif; ?>
				</div>
				<div class="details">
					<div class="desc editor"><?php echo $item['short_description']; ?></div>
					<ul class="buttons">
					<?php $colors = Colors::getColorsByIdCatalog($item['id']); ?>
						<li>
							<form id="form-product-buy" action="/cart/addtocart/" method="post">
								<input type="hidden" name="mid" value="<?php echo (isset($colors[0]['id']))?$colors[0]['id']:0; ?>">
								<input type="hidden" name="pid" value="<?php echo $item['id']; ?>">
								<input type="submit" value="" class="btn buy">
							</form>								
						</li>
					</ul>
				</div>					
			</li>
			<?php endforeach; ?>
		<?php endforeach; ?>
		</ul>			
		<?php else: ?>
		<ul class="grid">
		<?php foreach($collections as $items) : ?>
			<?php foreach($items as $item) : ?>
			<li>
				<div class="pict">
					<a href="/product/<?php echo $item['path']; ?>/" class="name"><?php echo get_product_name($item); ?></a>
					<a href="/product/<?php echo $item['path']; ?>/" class="image">
						<img src="<?php echo $imagepath['small']['path'].insert_image($item['id']); ?>" alt="<?php echo $item['name']; ?>">
					</a>
					<div class="buttons">
						<a href="/product/<?php echo $item['path']; ?>/#pit-tab-3" class="kol-colors"><?php get_count_colors($item['id']); ?></a>
						<a href="#" class="status r<?php echo $item['status']; ?>"><?php get_status($item['status']); ?></a>
						<a href="/product/<?php echo $item['path']; ?>/" class="btn-details">Подробнее</a>
						<?php echo get_buttons_catalog($item['id']); ?>
						<?php if ($item['status'] != 4): ?>
						<span class="price">Цена: <?php echo transform_to_currency($item); ?></span>
						<?php else: ?>
						<a href="#" class="status nvn">Снят с производства</a>
						<?php endif; ?>	
					</div>
					<?php if ($item['new']==1): ?><i class="icon new"></i>
					<?php elseif ($item['hit']==1): ?><i class="icon best"></i><?php endif; ?>
				</div>
			</li>
			<?php endforeach; ?>
		<?php endforeach; ?>
		</ul>						
		<?php endif; ?>
		<div class="toolbar bottom">
			<span class="pagination">
				<?php echo @$pagination; ?>
			</span>
			<?php if (isset($_SESSION['rows_on_page'])) :?>
			<span class="display">
				Показывать по: 
				<span>
				<?php $rows_on_page = $_SESSION['rows_on_page']; ?>
					<a href="?rows_on_page=9" <?php if($rows_on_page==9): ?>class="active"<?php endif; ?>>9</a>
					<a href="?rows_on_page=18" <?php if($rows_on_page==18): ?>class="active"<?php endif; ?>>18</a>
					<a href="?rows_on_page=27" <?php if($rows_on_page==27): ?>class="active"<?php endif; ?>>27</a>
				</span>
			</span>
			<?php endif; ?>
			<span class="items-count">
				Показано <?php echo $getLimitas; ?> (всего <?php echo @$totals; ?> позиций)
			</span>
		</div>
	</div>
</div>		