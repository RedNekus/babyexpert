<?php 

  UI::addCSS(array(
	
    '/css/catalog/list.css'
	
  ));

?>
<div class="h-mb">
	<h1 class="b-page-title"><span>Результат поиска</span></h1>	
	<div class="b-products">
		<ul class="grid">
			<?php foreach($collections as $item) : ?>
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
		</ul><!-- /.row -->
		<div class="toolbar bottom">
			<span class="pagination">
				<?php echo @$pagination; ?>
			</span>		
			<span class="display">
			<?php if (isset($_SESSION['rows_on_page'])) :?>
				Показывать по: 
				<span>
					<a href="?rows_on_page=9<?php echo $anchor;?>" <?php if(@$rows_on_page==9): ?>class="active"<?php endif; ?>>9</a>
					<a href="?rows_on_page=18<?php echo $anchor;?>" <?php if(@$rows_on_page==18): ?>class="active"<?php endif; ?>>18</a>
					<a href="?rows_on_page=27<?php echo $anchor;?>" <?php if(@$rows_on_page==27): ?>class="active"<?php endif; ?>>27</a>
				</span>
			<?php endif; ?>	
			</span>
			<span class="items-count">
				Показано <?php echo $getLimitas; ?> (всего <?php echo @$totals; ?> позиций)
			</span>
			<span class="b-back-top">
				<a id="back-top" href="#pinfo-top">Вверх</a>
			</span>				
		</div>		
	</div><!-- /.b-products -->
</div>