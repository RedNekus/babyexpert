<p class="show-fixed flex-fill up"><a href="javascript:void(0);">Наверх</a></p>
<p class="d-none d-lg-flex show-fixed mx-3"><a href="#">Просмотренные товары</a><i>5</i></p>
<p class="d-none d-lg-flex show-fixed mx-3"><a href="#">Сравнить товары</a><i>3</i></p>
<p class="basket show-fixed"><a href="#"><span class="d-none d-lg-inline">Корзина</span></a><a href="#">2 товара</a></p>
<ul class="navbar-nav h-100" id="MainMenu">	
	<?php foreach (Pages::getPagesList('5') as $page) : ?>
	<li class="nav-item<?php if (URL::getPath() == $page['path']): ?> active<?php endif; ?>">
		<a  class="nav-link" href="/<?php echo $page['path']; ?>/"><?php echo $page['name']; ?></a>
	</li>
	<?php endforeach; ?>
</ul>
<div class="time px-3 d-none d-xl-flex">Работаем с 9-00 до 21-00</div>
<div class="phone ml-4 dropdown">
	<span class="velcome dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">670-00-77</span>
	<div class="dropdown-menu dropdown-menu-right">
		<p class="phone-dropdown"><small>+375 29</small> 670-00-77</p>
		<p class="phone-dropdown"><small>+375 29</small> 670-00-77</p>
		<p class="phone-dropdown"><small>+375 29</small> 670-00-77</p>
		<p class="text-center">Не смогли дозвониться?<br><a href="#" data-toggle="modal" data-target="#call"><u>Заказать звонок</u></a></p>
		<p class="line">Работаем ежедневно c 9 до 21</p>
		<p class="messenger viber"><a href="#">24med.by</a></p>
		<p class="messenger whatsapp"><a href="#">@e24medbot</a></p>
		<p class="messenger telegram"><a href="#">24@24med.by</a></p>
		<p class="text-center"><button class="btn btn-info" data-toggle="modal" data-target="#call">Обратная связь</button></p>
		<p class="social-sm text-center">
			<a href="#" class="fb">&#xf30c;</a>
			<a href="#" class="ig">&#xf32d;</a>
			<a href="#" class="vk">&#xf189;</a>
		</p>
	</div>
</div>