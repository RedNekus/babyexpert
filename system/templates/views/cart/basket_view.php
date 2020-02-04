<ul id="slide-panel" class="b-slide-panel">
	<li>
		<div id="cart-box" class="b-cart-mini fbo">
			<div id="cm-content" class="inner">
			<?php echo getBasket(@$_SESSION['collection'],Config::getParam('modules->catalog->image')); ?>
			</div>
			<a href="/cart/" class="opener"></a>
		</div>
	</li>
	<li>
		<div id="wishlist-box" class="b-wishlist-mini fbo">
			<div id="wm-content" class="inner">
				<div class="wrap" id="wm-wrap">
					<?php echo getWishlist(@$_SESSION['compare'],Config::getParam('modules->catalog->image')); ?>	
				</div>	
				<div class="input-text">
					<form action="/cart/codetocompare" id="codetocompare">
						<input type="text" name="code" placeholder="Введите код" value="<?php echo @$_SESSION['code']; ?>" class="code-input" />
						<input type="submit" class="code-submit" />
						<div class="code-vopros">
							<span>
								Вы имеете возможность передать список понравившихся Вам товаров Вашему знакомому,  другу,  родственику,  для оказания помощи в выборе:
								<br/>1. Отложите интересующий Вас товар в "Отложенные товары" с помощью кнопок "Отложить" либо "К сравнению".
								<br/>2. Клацните ссылку "Посоветоваться с другом" (данная ссылка доступна только при наличии отложенных товаров).
								<br/>3. Получите 5-тизначный код.
								<br/>4. Передайте полученный код другому человеку доступным Вам способом.
								<br/>Находясь на сайте нашего магазина Ваш друг с помощью полученного кода может ознакомиться с товарами которые Вы отложили:
								<br/>1. Откройте закладку "Отложенные товары" в правом верхнем углу страницы.
								<br/>2. Введите полученный код в специальное поле обозначенное словами "введите код".
								<br/>3. Клацните кнопку справа от указанного Вами кода либо нажмите Ввод (Enter).
								<br/>4. Получите список товаров отложенных Вашим другом.
								<br/>5. Для просмотра товаров клацните кнопку "Просмотреть",  для сравнения, — "Сравнить".
								<br/>Код действителен 90 суток.
							</span>
						</div>
					</form>
				</div>			
			</div>
			<a href="/cart/wishlist/" class="opener"></a>
		</div>
	</li>
</ul>