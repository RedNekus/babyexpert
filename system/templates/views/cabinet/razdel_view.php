<?php 
	UI::addCSS(array(
		'/css/cabinet/razdel.css'
	));
	 	
?>
	
<ul class="b-sidebar-razdel">
	<li>
		<div class="header">
		Ваша скидка: <b>10%</b>
		</div>
	</li>
	<li>
		<div class="b-menu-razdel">	
			<a href="/cabinet/myzakaz/" title="Мои заказы" <?php if (URL::getSegment(2)=="myzakaz") echo 'class="active"'; ?>>Мои заказы</a>
			<a href="/cabinet/myadres/" title="Адресная книга" <?php if (URL::getSegment(2)=="myadres") echo 'class="active"'; ?>>Адресная книга</a>
			<a href="/cabinet/" title="Личная информация"><span class="pen"></span>Личная информация </a>
		</div>
	</li>	
	<li>
		<div class="total_summa">
			У Вас: <span><b>1400</b> баллов</span>
		</div>
	</li>	
</ul><!-- /.b-sidebar-links -->	