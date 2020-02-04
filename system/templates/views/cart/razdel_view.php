<ul class="b-banners-list">
	<li><a href="/wantdiscount"><img src="/img/banner-1.jpg" width="226" height="51" alt="Хочу скидку"></a></li>
	<li><a href="/wantproduct"><img src="/img/banner-2.jpg" width="226" height="87" alt="Хочу товар которого нет на сайте!"></a></li>
	<li><a href="/callback"><img src="/img/banner-3.jpg" width="226" height="112" alt="Заказать звонок"></a></li>
</ul>
<a class="b-cmp-back-catalog" href="/category/">Вернуться в каталог</a>
<div class="b-categories">
	<ul>
	<?php foreach($trees as $index => $value): ?>
		<li><a href="/cart/compare/<?php echo $index; ?>/"><?php echo Tree::getTreeNameByCharacteristics($index); ?> (<?php echo count($trees[$index]); ?>)</a></li>
	<?php endforeach; ?>	
	</ul>
</div>