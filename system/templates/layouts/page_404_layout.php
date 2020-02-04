<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Страница не найдена!</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="/css/site404.css?1" />
  </head>
  <body>
    <div id="wrapper_404">
		<div class="header"></div>
		<div class="content">
			<div class="left">
				<div class="b-categories">
					<ul>
						<?php foreach($razdels as $razdel): ?>
							<li>
								<a href="/category/<?php echo $razdel['path']; ?>/"><?php echo $razdel['name']; ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>	
			</div>
			<div class="right">
				<div class="b-links">
					<ul>
						<li><a href="/">О МАГАЗИНЕ</a></li>
						<li><a href="/shipping">ДОСТАВКА</a></li>
						<li><a href="/">ГЛАВНАЯ</a></li>
						<li><a href="/warranty">ГАРАНТИЯ</a></li>
						<li><a href="/news">НОВОСТИ</a></li>
						<li><a href="/contacts">КОНТАКТЫ</a></li>
					</ul>
				</div>	
			</div>			
		</div>	
		<div class="bottom">
			<div class="b-home-tabs">
				<ul class="tabs-panel">
					<li class="new"><a href="/novinki" >Новинки</a></li>
					<li class="best"><a href="/hity_prodazh" >Хиты продаж</a></li>
					<li class="spec"><a href="/specpredlozheniya" >Спецпредложения</a></li>
					<li class="tovarnedeli"><a href="/tovary_nedeli" >Товары недели</a></li>
					<li class="expert"><a href="/vybor_eksperta" >Выбор эксперта</a></li>
				</ul>
				<ul class="tabs-panel-r">
					<li class="sale"><a href="/sale" >Акции и скидки</a></li>			
				</ul>
			</div>
		</div>
	</div>
  </body>
</html>
