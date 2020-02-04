<?php 
	$banners_left = Banners_left::getBannersActive('prioritet','DESC');
	if (isset($banners_left[0])) {
		$image_cfg_left = Config::getParam('modules->banners_left->image');
		$width = $image_cfg_left['width'];
		$height = $image_cfg_left['height'];		
		$banners_left_img_post = '<img src="'.$image_cfg_left['path'].$banners_left[0]['image'].'" width="'.$width.'" height="'.$height.'" title="'.$banners_left[0]['name'].'" alt="'.$banners_left[0]['name'].'">';
		if ($banners_left[0]['path']) $banners_left_img_post = '<a href="'.$banners_left[0]['path'].'" title="'.$banners_left[0]['name'].'">'.$banners_left_img_post.'</a>';
	}
	
	$banners_small = Banners_small::getBannersActive('prioritet','DESC');
		if (isset($banners_small[0])) {	
		$image_cfg_small = Config::getParam('modules->banners_small->image');
		$width = $image_cfg_small['width'];
		$height = $image_cfg_small['height'];
		
		$banners_small_img_post = '<img src="'.$image_cfg_small['path'].$banners_small[0]['image'].'" width="'.$width.'" height="'.$height.'" title="'.$banners_small[0]['name'].'" alt="'.$banners_small[0]['name'].'">';
		if ($banners_small[0]['path']) $banners_small_img_post = '<a href="'.$banners_small[0]['path'].'" title="'.$banners_small[0]['name'].'">'.$banners_small_img_post.'</a>';
	}
	
?>

<script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>

<ul class="b-banners-list">
	<?php if (URL::getSegment(1)!="product"): ?>
	<li><a href="/wantdiscount"><img src="/img/banner-1.jpg" width="226" height="51" alt="Хочу скидку"></a></li>
	<?php endif; ?>
	<?php if (isset($banners_small_img_post)) { ?>
	<li><?= $banners_small_img_post; ?></li>
	<?php } ?>
	<li><a href="/wantproduct"><img src="/img/banner-2.jpg" width="226" height="87" alt="Хочу товар которого нет на сайте!"></a></li>
	<li><a href="/callback"><img src="/img/banner-3.jpg" width="226" height="112" alt="Заказать звонок"></a></li>
	<?php if (isset($banners_left_img_post)) { ?>
	<li><?= $banners_left_img_post; ?></li>
	<?php } ?>
	<li><div id="vk_groups"></div></li>
	<li><img src="/img/card_ico.png" alt="" style="width: 226px;"></li>
</ul>

<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 0, width: "226", height: "400", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 56421194);
</script> 
		