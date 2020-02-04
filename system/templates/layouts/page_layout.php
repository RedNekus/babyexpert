<?php Pages::fetchContent(URL::getPath()); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
	<title><?php if (isset($title)) { echo @$title; } else { echo Pages::getTitle(); } ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="generator" content="Aver CMS" />
	<meta name="description" content="<?php  if (isset($description)) { echo @$description; } else { echo Pages::getDescription(); } ?>" />	
	<meta name="keywords" content="<?php if (isset($keywords)) { echo @$keywords; } else { echo Pages::getKeywords(); } ?>" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="/css/main.css" />
	<link rel="canonical" href="<?php echo get_url_canonical(); ?>">		
	<?php foreach(UI::getCSS() as $css): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>" />
	<?php endforeach; ?>	
	<script src="/js/jquery-3.4.1.min.js"></script>
	<script src="/js/popper.min.js" ></script>
	<script src="/js/bootstrap.min.js" ></script>
	<script src="/js/main.js" ></script>
	<meta name="yandex-verification" content="34515885633bedad" />	
	
</head>

	<body>
		<header id="header-collapse">
			<nav class="navbar navbar-light navbar-expand-md bg-light d-none d-md-flex">
				<div class="container-xl h-100">
					<?php Render::view('menu','', TRUE); ?>
				</div>	
			</nav>
			<div class="logo-container">
				<img src="/img/logo.png" />
				<form id="search-form" class="form-inline">
					<div class="input-group">
						<input class="form-control form-control-lg search-input" type="search" placeholder="Поиск" aria-label="Поиск">
						<div class="input-group-append">
							<button class="btn btn-search" type="submit">&nbsp;</button>
						</div>
					</div>
					<div class="search-results">
						<h2 class="search-header"><a href="#">Электроградусники (26 товаров)</a></h2>
						<h2 class="search-header"><a href="#">Градусники (56 товаров)</a></h2>
						<div class="goods-item">
							<div class="img-wrapper">
								<img src="img/goods-img-1.jpg" />
							</div>
							<div class="d-flex flex-column flex-fill">
								<h3>Электроградусник Maximus LLC-890 Small</h3>
								<p class="price-block flex-fill d-flex d-md-none align-items-end">Цена: <span class="price my-0 mx-2 text-info">790.90</span> руб</p>
								<div class="justify-content-between flex-fill d-none d-md-flex">
									<p class="text-success mb-0 mr-3">В наличии</p>
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
								</div>
								<p class="compare mb-0 d-none d-lg-block">
									<span>К сравнению</span>
									<span>Оставить отзыв</span>
									<span>Характеристики</span>
								</p>
							</div>
							<div class="d-none d-md-flex flex-column ml-3">
								<p class="price-block flex-fill">Цена: <span class="price">790.90</span> руб</p>
								<p class="mb-0"><a href="#" class="btn btn-info">Подробнее</a></p>
							</div>
						</div>
						<div class="goods-item">
							<div class="img-wrapper">
								<img src="img/goods-img-1.jpg" />
							</div>
							<div class="d-flex flex-column flex-fill">
								<h3>Электроградусник Maximus LLC-890 Small</h3>
								<p class="price-block flex-fill d-flex d-md-none align-items-end">Цена: <span class="price my-0 mx-2 text-info">790.90</span> руб</p>
								<div class="justify-content-between flex-fill d-none d-md-flex">
									<p class="text-success mb-0 mr-3">В наличии</p>
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
								</div>
								<p class="compare mb-0 d-none d-lg-block">
									<span>К сравнению</span>
									<span>Оставить отзыв</span>
									<span>Характеристики</span>
								</p>
							</div>
							<div class="d-none d-md-flex flex-column ml-3">
								<p class="price-block flex-fill">Цена: <span class="price">790.90</span> руб</p>
								<p class="mb-0"><a href="#" class="btn btn-info">Подробнее</a></p>
							</div>
						</div>
						<div class="goods-item">
							<div class="img-wrapper">
								<img src="img/goods-img-1.jpg" />
							</div>
							<div class="d-flex flex-column flex-fill">
								<h3>Электроградусник Maximus LLC-890 Small</h3>
								<p class="price-block flex-fill d-flex d-md-none align-items-end">Цена: <span class="price my-0 mx-2 text-info">790.90</span> руб</p>
								<div class="justify-content-between flex-fill d-none d-md-flex">
									<p class="text-success mb-0 mr-3">В наличии</p>
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
								</div>
								<p class="compare mb-0 d-none d-lg-block">
									<span>К сравнению</span>
									<span>Оставить отзыв</span>
									<span>Характеристики</span>
								</p>
							</div>
							<div class="d-none d-md-flex flex-column ml-3">
								<p class="price-block flex-fill">Цена: <span class="price">790.90</span> руб</p>
								<p class="mb-0"><a href="#" class="btn btn-info">Подробнее</a></p>
							</div>
						</div>
						<p class="text-center big-text"><a href="#">Показать все результаты поиска</a></p>
					</div>
				</form>
				<p class="basket d-none d-lg-flex"><a href="#">2 товара</a><a href="">Сравнение</a><i>3</i></p>
				<p class="basket d-lg-none"><a href="#">&nbsp;</a><a href="">2 товара</a></p>
				<div class="user-login  dropdown">
					<span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><u>Мое</u></span>
					<div class="dropdown-menu dropdown-menu-right">
						<p>
							<strong data-toggle="modal" class="entr-icon">Вход</strong>
							<span class="sep">|</span>
							<strong data-toggle="modal" class="reg-icon">Регистрация</strong>
						</p>
						<p class="border-y"><a href="#" class="basket-icon">Корзина</a></p>
						<p class="see-icon">Просмотренные товары</p>
						<p class="list-icon">Списки сравнения</p>
					</div>
				</div>
			</div>
			<nav class="navbar navbar-dark p-md-0 navbar-expand-md bg-primary d-none d-md-flex">
				<div class="container-xl">
					<?php Render::view('main_menu','', TRUE); ?>
				</div>
			</nav>
			<nav class="navbar navbar-dark bg-primary d-md-none dropdown">
				<div class="container-xl px-0">
					<div class="menu-toggler double-line" data-toggle="modal" data-target="#menu"><i class="fas fa-bars"></i></div>
					<div class="flex-fill px-3"><img src="img/logo-mob.png" /></div>
					<div class="px-3 double-line square">
						<i data-toggle="collapse" data-target="#phones" aria-expanded="false" aria-controls="phones" class="fas fa-phone-alt"></i>
					</div>
					<div class="px-3 square">
						<span data-toggle="dropdown" data-offset="0,0,10,20" aria-haspopup="true" aria-expanded="false" id="search-dropdown">
								<img src="img/search-white.png" />
						</span>
						<div class="dropdown-menu" aria-labelledby="search-dropdown" id="search">
							<form id="search-form-mobile" class="form-inline">
								<div class="input-group">
									<input class="form-control form-control-lg search-input" type="search" placeholder="Поиск" aria-label="Поиск">
									<div class="input-group-append">
										<button class="btn bg-info" type="submit"><img src="img/search-white.png" /></button>
									</div>
								</div>
								<div class="search-results">
									<h2 class="search-header"><a href="#">Электроградусники (26 товаров)</a></h2>
									<h2 class="search-header"><a href="#">Градусники (56 товаров)</a></h2>
									<div class="goods-item">
										<div class="img-wrapper">
											<img src="img/goods-img-1.jpg" />
										</div>
										<div class="d-flex flex-column flex-fill">
											<h3>Электроградусник Maximus LLC-890 Small</h3>
											<p class="price-block flex-fill d-flex d-sm-none align-items-end">Цена: <span class="price my-0 mx-2 text-info">790.90</span> руб</p>
											<div class="justify-content-between flex-fill d-none d-sm-flex">
												<p class="text-success mb-0 mr-3">В наличии</p>
												<div class="rating">
													<span>&#xe80c;</span>
													<span>&#xe80c;</span>
													<span>&#xe80c;</span>
													<span>&#xe80c;</span>
													<span>&#xe80c;</span>
												</div>
											</div>
											<p class="compare mb-0 d-none d-sm-block">
												<span>К сравнению</span>
												<span>Оставить отзыв</span>
												<span>Характеристики</span>
											</p>
										</div>
										<div class="d-none d-sm-flex flex-column ml-3">
											<p class="price-block flex-fill">Цена: <span class="price">790.90</span> руб</p>
											<p class="mb-0"><a href="#" class="btn btn-info">Подробнее</a></p>
										</div>
									</div>
									<div class="goods-item">
										<div class="img-wrapper">
											<img src="img/goods-img-1.jpg" />
										</div>
										<div class="d-flex flex-column flex-fill">
											<h3>Электроградусник Maximus LLC-890 Small</h3>
											<p class="price-block flex-fill d-flex d-sm-none align-items-end">Цена: <span class="price my-0 mx-2 text-info">790.90</span> руб</p>
											<div class="justify-content-between flex-fill d-none d-sm-flex">
												<p class="text-success mb-0 mr-3">В наличии</p>
												<div class="rating">
													<span>&#xe80c;</span>
													<span>&#xe80c;</span>
													<span>&#xe80c;</span>
													<span>&#xe80c;</span>
													<span>&#xe80c;</span>
												</div>
											</div>
											<p class="compare mb-0 d-none d-sm-block">
												<span>К сравнению</span>
												<span>Оставить отзыв</span>
												<span>Характеристики</span>
											</p>
										</div>
										<div class="d-none d-sm-flex flex-column ml-3">
											<p class="price-block flex-fill">Цена: <span class="price">790.90</span> руб</p>
											<p class="mb-0"><a href="#" class="btn btn-info">Подробнее</a></p>
										</div>
									</div>
									<p class="text-center big-text"><a href="#">Показать все результаты поиска</a></p>
								</div>
							</form>
						</div>
					</div>
					<div class="px-3 cart square"><a href="#"><img src="img/basket.png" /><i class="count">3</i></a></div>
				</div>
			</nav>
			<div class="collapse phone-collapse" data-parent="#header-collapse" id="phones">
				<p class="phone-dropdown"><small>+375 29</small> 670-00-77</p>
				<p class="phone-dropdown"><small>+375 29</small> 670-00-77</p>
				<p class="phone-dropdown"><small>+375 29</small> 670-00-77</p>
				<p class="text-center">Не смогли дозвониться?<br><a href="#" data-toggle="modal" data-target="#call"><u>Заказать звонок</u></a></p>
				<p class="line">Работаем ежедневно c 9 до 21</p>
				<p class="messenger viber"><a href="#">24med.by</a></p>
				<p class="messenger whatsapp"><a href="#">@e24medbot</a></p>
				<p class="messenger telegram"><a href="#">24@24med.by</a></p>
				<p class="text-center"><button class="btn btn-info btn-lg" data-toggle="modal" data-target="#call">Обратная связь</button></p>
				<p class="social-lg text-center">
					<a href="#" class="fb">&#xf30c;</a>
					<a href="#" class="ig">&#xf32d;</a>
					<a href="#" class="vk">&#xf189;</a>
				</p>
			</div>
		</header>
		<main>
			<div class="container-xl">
			<?php
				if (isset($content)) {			
					echo $content;	
				} elseif (Pages::getContent()){		  
					echo Pages::getContent();
				} else {	  
					echo '&nbsp;';		  
				}
			?>
			</div>
		</main>
		<footer>
			<div class="container-xl">
				<div class="row">
					<div class="col-12 col-lg-3 d-flex flex-column flex-md-row flex-lg-column align-items-md-center align-items-lg-stretch">
						<p class="phone"><span class="icon-1"><small>+375 29</small> 670-00-77</span><br>
						<span class="icon-2"><small>+375 29</small> 670-00-77</span></p>
						<p class="flex-fill mx-md-3 mx-lg-0"><button class="btn btn-lg btn-outline-info"  data-toggle="modal" data-target="#question">Отправить вопрос</button></p>
						<p class="social-md">
							<a href="#" class="fb">&#xf30c;</a>
							<a href="#" class="ig">&#xf32d;</a>
							<a href="#" class="vk">&#xf189;</a>
						</p>
					</div>
					<div class="d-none d-sm-block col-4 col-lg-3">
						<h3>Компания</h3>
						<ul>
							<li><a href="#">Новости</a></li>
							<li><a href="#">О нас</a></li>
							<li><a href="#">Миссия и ценности</a></li>
							<li><a href="#">Реквизиты</a></li>
							<li><a href="#">Отзывы</a></li>
							<li><a href="#">Вакансии</a></li>
							<li><a href="#">Поставщикам</a></li>
							<li><a href="#">Безналичные продажи</a></li>
							<li><a href="#">Оптовые продажи</a></li>
						</ul>
					</div>
					<div class="d-none d-sm-block col-4 col-lg-3">
						<h3>Специальные предложения</h3>
						<ul>
							<li><a href="#">Бонусная программа</a></li>
							<li><a href="#">Подарочный сертификат</a></li>
							<li><a href="#">Товары в рассрочку</a></li>
							<li><a href="#">Скидки, суперцены</a></li>
							<li><a href="#">Уцененные товары</a></li>
							<li><a href="#">Идеи новогодних подарков</a></li>
							<li><a href="#">Рекомендуем</a></li>
							<li><a href="#">Товары Премиум-класса</a></li>
							<li><a href="#">Шоу-рум Sundays.by</a></li>
						</ul>
					</div>
					<div class="d-none d-sm-block col-4 col-lg-3">
						<h3>Информация</h3>
						<ul>
							<li><a href="#">Договор публичной оферты</a></li>
							<li><a href="#">Как совершить покупку</a></li>
							<li><a href="#">Как использовать промокод</a></li>
							<li><a href="#">Замена и возврат товара</a></li>
							<li><a href="#">Сервисные центры</a></li>
							<li><a href="#">Производители</a></li>
							<li><a href="#">Помощь в выборе</a></li>
						</ul>
					</div>
					<div class="mt-md-5 col-12 col-md-4 col-lg-3 offset-lg-3">
						<p>ООО «Триовист», юр.адрес: 220020,<br> Минск, пр. Победителей, 100, оф. 203</p>
					</div>
					<div class="mt-md-5 col-12 col-md-6">
						<p>В торговом реестре с 23 июня 2010г. УНП 190806803.<br> Регистрация №190806803, 22.02.2007, Мингорисполком.</p>
					</div>
				</div>
			</div>
		</footer>
		<!-- Modal -->
		<div class="modal fade" id="call" tabindex="-1" role="dialog" aria-labelledby="callLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="callLabel">Заказать звонок</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&#xe807;</span>
						</button>
					</div>
					<div class="modal-body">
						<p class="text-muted">все поля обязательны для заполнениявсе поля обязательны для заполнения</p>
						<form method="POST" action="/">
							<div class="form-group">
								<label for="name">Ваше имя</label>
								<input type="text" class="form-control" id="name" />
							</div>
							<div class="form-group">
								<label for="phone">Ваш телефон</label>
								<input type="tel" class="form-control" id="phone"  placeholder="+375(__) __-__-__"  />
							</div>
							<button type="submit" class="btn btn-primary">Отправить</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="question" tabindex="-1" role="dialog" aria-labelledby="questionLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="questionLabel">Задать вопрос</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&#xe807;</span>
						</button>
					</div>
					<div class="modal-body">
						<p class="text-muted">все поля обязательны для заполнениявсе поля обязательны для заполнения</p>
						<form method="POST" action="/">
							<div class="form-group">
								<label for="name">Ваше имя</label>
								<input type="text" class="form-control" id="name" />
							</div>
							<div class="form-group">
								<label for="phone">Ваш телефон</label>
								<input type="tel" class="form-control" id="phone" placeholder="+375(__) __-__-__" />
							</div>
							<div class="form-group">
								<label for="question">Вопрос</label>
								<textarea class="form-control" id="question" rows="3"></textarea>
							</div>
							<button type="submit" class="btn btn-primary">Отправить</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="menu" tabindex="-1" role="dialog" aria-labelledby="menuLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<span data-toggle="modal">Вход</span><span data-toggle="modal" class="px-2">|</span><span data-toggle="modal">Регистрация</span>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&#xe807;</span>
						</button>
					</div>
					<div class="modal-body menu">	
						<h2 data-toggle="collapse" data-target="#cat" aria-expanded="false" aria-controls="collapseExample">Каталог</h2>
						<ul id="cat" class="collapse show">
							<li class="nav-item"><a class="nav-link" href="#">Для кухни</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Для дома</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Для ремонта</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Электроника</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Компьютеры</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Офис</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Дача, сад</a></li>
							<li class="nav-item">
							<a class="nav-link" data-toggle="collapse" href="#avto" aria-expanded="false" aria-controls="avto">Авто</a>
							<ul class="collapse" id="avto">
								<li class="nav-item"><a class="nav-link" href="#">Детские автокресла</a></li>
								<li class="nav-item"><a class="nav-link" href="#">Коляски Cybex</a></li>
								<li class="nav-item">
								<a class="nav-link" data-toggle="collapse" href="#bags" aria-expanded="false" aria-controls="bags">Сумки</a>
									<ul class="collapse" id="bags">
										<li class="nav-item"><a class="nav-link" href="#">Сумки-кенгуру</a>
										<li class="nav-item"><a class="nav-link" href="#">Навестные сумки</a>
									</ul>
								</li>
							</ul>
							</li>
							<li class="nav-item"><a class="nav-link" href="#">Спорт</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Красота</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Детям</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Шины</a></li>
						</ul>
						<h2 data-toggle="collapse" data-target="#site" aria-expanded="false" aria-controls="collapseExample">Меню сайта</h2>
						<ul id="site" class="collapse">
							<li class="nav-item"><a class="nav-link" href="#">О компании</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Доставка</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Оплата</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Товары в рассрочку</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Скидки</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Уцененные товары</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Гарантия</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Возврат</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>