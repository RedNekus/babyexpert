<?php 
  $active = "";
  //$url_path = URL::getSegment(1);
  Pages::fetchContent(URL::getPath());
?>
<div class="slider-container">
	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img src="img/slider.jpg" class="d-block w-100" alt="...">
			</div>
			<div class="carousel-item">
				<img src="img/slider.jpg" class="d-block w-100" alt="...">
			</div>
			<div class="carousel-item">
				<img src="img/slider.jpg" class="d-block w-100" alt="...">
			</div>
		</div>
		<ol class="carousel-indicators">
			<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
		</ol>
	</div>
	<div class="goods-block">
		<div class="col-6">
			<h3><a href="#">ИК градусники</a></h3>
			<img src="img/goods-img-1.jpg"/>
		</div>
		<div class="col-6">
			<h3><a href="#">ИК градусники</a></h3>
			<img src="img/goods-img-1.jpg"/>
		</div>
		<div class="col-6">
			<h3><a href="#">ИК градусники</a></h3>
			<img src="img/goods-img-1.jpg"/>
		</div>
		<div class="col-6">
			<h3><a href="#">ИК градусники</a></h3>
			<img src="img/goods-img-1.jpg"/>				
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12 col-lg-9 mb-3">
		<div class="headers">
			<button class="btn btn-lg btn-outline-info d-sm-none">Каталог</button>
			<button class="btn btn-lg btn-info d-sm-none">Скидки и суперцены</button>
			<div>
				<a href="#" class="h2 active">Популярные товары</a>
				<a href="#" class="h2">Акции</a>
				<a href="#" class="h2">Рекомендуем</a>
			</div>
		</div>
		<div id="carouselControls"  class="goods-slider carousel slide" data-ride="carousel">
			<a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
				<img src="img/arr-left.png" />
			</a>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<div class="d-flex flex-wrap">
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="carousel-item">
					<div class="d-flex flex-wrap">
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="img-wrapper">
								<div class="sale">%</div>
								<picture>
									<source srcset="img/googs-img-big.jpg" media="(max-width: 575px)">
									<source srcset="img/goods-img-2.jpg">
									<img srcset="img/goods-img-2.jpg" alt="Мое основное изображение">
								</picture>
								<h6 class="card-subtitle">Toshiba</h6>
							</div>
							<div class="d-flex flex-column">
								<h3><a href="#">Стиральная машина Miele WDB 020 W1 Classic</a></h3>
								<div class="card-rating">
									<div class="rating">
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
										<span>&#xe80c;</span>
									</div>
									<p class="text-success font-weight-bold">В наличии</p>
								</div>
								<div class="price-block">
									<div>
										<p class="old-price">1 439,00 руб</p>
										<p class="price">1890,00 руб</p>
									</div>
									<div>
										<p>экономия</p>
										<p class="highlight">450,00</p>
									</div>
								</div>
								<div class="d-none d-sm-block">
									<div class="price">1890 <span>руб</span></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
				<img src="img/arr-right.png" />
			</a>
		</div>	
	</div>
	<div class="col-3 d-none d-lg-block">
	<?php if (@$news): ?>
		<h2>Новости</h2>
		<?php foreach($news as $item): ?>
		<div>
			<p class="date"><?=strftime('%d/%m/%G', $item['timestamp'])?></p>
			<h3><a href="#"><?=$item['name']?></a></h3>
			<p><?=cut_str(strip_tags($item['short_description']), 100)?></p>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>
	</div>
</div>
<div class="banner-wrapper">
	<a href="#"><img src="img/banner.jpg" /></a>
</div>
<div class="d-none d-sm-block mb-3">
	<h2>Новинки в каталоге</h2>
	<div class="new-items">
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
		<span><a href="#">Багеты</a></span>
		<span><a href="#">Расчески</a></span>
	</div>
</div>
<div class="row">
	<div class="col-12 col-lg-9">
		<?php if (@$reviews): ?>
		<div class="headers">
			<h2>Отзывы покупателей</h2>
			<a href="#">Смотреть все отзывы</a>
		</div>
		<div id="carouselReviews" class="review-slider carousel slide" data-ride="carousel">
			<a class="review-control review-prew d-sm-none" href="#carouselReviews" role="button" data-slide="prev">
				<img src="img/arr-left.png" />
			</a>
			<div class="carousel-inner">
				<?php foreach($reviews as $item): ?>
					<div class="carousel-item active col-sm-4">
						<p class="h3"><?=$item['name']?></p>
						<p><?php echo $item['content']; ?></p>
						<div class="d-flex">
							<div class="rating">
								<?php for($i=0; $i < 5; $i++): ?>
								<?php if ($item['rank'] > $i): ?>
								<span>&#xe80c;</span>
								<?php else: ?>
								<span>&#xe80d;</span>
								<?php endif; ?>
								<?php endfor; ?>
							</div>
							<p class="date ml-3"><?=strftime('%e %B %G', $item['timestamp'])?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<a class="control review-next d-sm-none" href="#carouselReviews" role="button" data-slide="next">
				<img src="img/arr-right.png" />
			</a>
		</div>				
		<?php endif; ?>
		<h2 class="d-none d-md-flex">Наши преимущества</h2>
		<div class="row d-none d-md-flex">
			<div class="adv-item">
				<div><img src="img/adv-1.png" /></div>
				<div>
					<h3>Гарантия качества</h3>
					<p>Мы продаем только сертифицированный товар с гарантией сервисных центров, чек прилагается.</p>
				</div>
			</div>
			<div class="adv-item">
				<div><img src="img/adv-3.png" /></div>
				<div>
					<h3>Честные цены</h3>
					<p>Все цены, указанные на сайте, действительны, актуальны и не зависят от выбранной вами формы оплаты.</p>
				</div>
			</div>
			<div class="adv-item">
				<div><img src="img/adv-5.png" /></div>
				<div>
					<h3>Доставка по всей Беларуси</h3>
					<p>Мы доставляем заказы в Минск, Брест, Витебск, Гомель, Гродно, Могилев и в любую другую точку Беларуси!</p>
				</div>
			</div>
			<div class="adv-item">
				<div><img src="img/adv-2.png" /></div>
				<div>
					<h3>Мгновенные кредиты</h3>
					<p>Вы можете выбрать кредит среди предложений ведущих банков, рассчитать и оформить его, не выходя из дома.</p>
				</div>
			</div>
			<div class="adv-item">
				<div><img src="img/adv-4.png" /></div>
				<div>
					<h3>Надежный сервис</h3>
					<p>Мы предлагаем услуги по сборке, установке, настройке, гарантийному и постгарантийному обслуживанию товаров.</p>
				</div>
			</div>
			<div class="adv-item">
				<div><img src="img/adv-6.png" /></div>
				<div>
					<h3>Выгодные покупки</h3>
					<p>Наш онлайн-гипермаркет предлагает вам выгодные акции, скидки и собственную бонусную программу.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-3 d-flex flex-column">
		<h2 class="d-none d-md-flex">Присоединяйтесь</h2> 
		<script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>
		<div id="vk_groups" class="vk-mod">
		</div>
	</div>
</div>

<div class="text-container">
	<h2><?php echo Pages::getName(); ?></h2>
	<?php echo Pages::getContent(); ?>
</div>
<script type="text/javascript">
	VK.Widgets.Group("vk_groups", {mode: 0, width: "267", height: "460", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 56421194);
</script>