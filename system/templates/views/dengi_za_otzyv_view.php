<?php 
	Pages::fetchContent(URL::getPath());
	UI::addCSS('/css/dengi_za_otzyv.css');
?>
<div class="b-akciya">
	<h2 id="dzv"><?php echo Pages::getNamefull(); ?></h2>
	<img src="/img/dengi_za_otzyv/bg.png" alt="">
	<div class="bi-podrobnee">Подробности</div>
	<div class="bi-content">
	<?php echo Pages::getContent(); ?>
	</div>
</div>