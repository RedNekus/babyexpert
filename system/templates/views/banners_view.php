<?php 
	$tree = Tree::getTreeByPath(URL::getSegment(3));
	if(isset($tree['id'])) 
	$banners = Banners::getBannersByIdCategories($tree['id'],"prioritet","DESC");
	else 
	$banners = Banners::getBannersActive("prioritet","DESC");


if (@$banners):  
$path = Config::getParam('modules->banners->image');
?>
<div id="promo-block" class="b-promo">
	<div class="b-banners-slider">
		<div id="slider" class="nivoSlider">
			<?php foreach($banners as $banner): ?>
				<?php if ($banner['path']): ?>
					<a href="<?php echo $banner['path']; ?>" title="<?php echo $banner['name']; ?>" target="_ablank">
						<img src="<?php echo $path['big']['path'].$banner['image']; ?>" alt="<?php echo $banner['name']; ?>" />
					</a>
				<?php else: ?>
					<img src="<?php echo $path['big']['path'].$banner['image']; ?>" alt="<?php echo $banner['name']; ?>" />
				<?php endif; ?>	
			<?php endforeach; ?>
		</div>
		<i class="sht"></i>
		<i class="shl"></i>
		<i class="shb"></i>
		<i class="shr"></i>
	</div>
	<div class="b-promo-links">
		<ul>
			<li><a href="/skidki/">Форум</a></li>
			<li><a href="/overview/">Обзоры</a></li>
			<li><a href="/article/">Статьи</a></li>
			<li><a href="/sale">Акции</a></li>
		</ul>
	</div>
</div>
<?php endif; ?>