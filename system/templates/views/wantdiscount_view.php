<div class="block h-mb">
	<h1 class="b-page-title"><span>Хочу скидку</span></h1>
	<form action="/wantdiscount/send/" method="post" id="form-discount" class="form discount">
		<h2>Выберите вариант предоставления скидки</h2>
		<!--<fieldset>
			<div class="line">
				<div class="l-col">
					<span class="stiker">Скидка: <b>2</b>$</span>
					<div class="w-radio">
						<div class="radio">
							<span><input id="found-cheaper" type="radio" name="discount_choise" value="1" /></span>
						</div>
					</div>
				</div>
				<label for="found-cheaper"><a href="/wantdiscount/foundcheaper" class="found-cheaper">Мы нашли дешевле</a></label>
				<span class="post-stiker">Если вы нашли товар который стоит дешевле, чем товар на нашем сайте, вы получаете скидку</span>					
			</div>
			<div class="line">
				<div class="l-col"><label for="site-url">Ссылка на сайт:</label></div>
				<div class="w-input"><input id="site-url" type="text" name="site_url" maxlength="100" ><i class="cl"></i><i class="cr"></i></div>
			</div>
		</fieldset>
		<fieldset>
			<div class="line">
				<div class="l-col">
					<span class="stiker">Скидка: <b>1</b>$</span>
					<div class="w-radio">
						<div class="radio" >
							<span class="checked">
								<input id="come-registered" type="radio" name="discount_choise" value="9" />
							</span>
						</div>
					</div>
				</div>
				<label for="come-registered" class="show-discount">Вступил в группу Вконтакте</label>
				<span class="post-stiker">Если вы вступили в нашу группу Вконтакте, вы получаете скидку</span>					
			</div>
			<div class="line">
				<div class="l-col">
					<span class="stiker">Скидка: <b>2</b>$</span>
					<div class="w-radio">
						<div class="radio" >
							<span class="checked">
								<input id="come-themselves" type="radio" name="discount_choise" value="8" />
							</span>
						</div>
					</div>
				</div>
				<label for="come-themselves" class="show-discount">Мы оставим отзыв о приобретенном здесь товаре</label>
				<span class="post-stiker">Если вы оставили отзыв о приобретенном здесь товаре, вы получаете скидку</span>					
			</div>-->
		<fieldset>	
			<div class="line">
				<div class="l-col">
					<span class="stiker">Скидка: <b>4</b>$</span>
					<div class="w-radio">
						<div class="radio" >
							<span class="checked">
								<input id="come-some" type="radio" name="discount_choise" value="4" />
							</span>
						</div>
					</div>
				</div>
				<label for="come-some" class="show-discount">Приедем сами</label>
				<span class="post-stiker">Если вы приедите за товаром сами, вы получаете скидку</span>				
			</div>
			<div class="line">
				<div class="l-col">
					<span class="stiker">Скидка: <b>3</b>%</span>
					<div class="w-radio">
						<div class="radio">
							<span>
								<input id="reg-customer" type="radio" name="discount_choise" value="5" />
							</span>
						</div>
					</div>
				</div>
				<label for="reg-customer" class="show-discount">Я постоянный клиент</label>
				<span class="post-stiker">Если вы постоянный клиент, вы получаете скидку</span>				
			</div>
			<div class="line">
				<div class="l-col">
					<span class="stiker">Скидка: <b>1</b>$</span>
					<div class="w-radio">
						<div class="radio">
							<span>
								<input id="wrote-review" type="radio" name="discount_choise" value="6" />
							</span>
						</div>
					</div>
				</div>
				<label for="wrote-review" class="show-discount">Мы написали отзыв о вашем магазине со ссылкой на сайт</label>
				<span class="post-stiker">Если вы написали отзыв о нашем магазине со ссылкой на сайт, вы получаете скидку</span>						
			</div>
			<div class="line">
				<div class="l-col"><label for="review-url">Ссылка на отзыв:</label></div>
				<div class="w-input"><input id="review-url" type="text" name="review_url" maxlength="100"><i class="cl"></i><i class="cr"></i></div>
			</div>
		</fieldset>
		<!--<fieldset>
			<div class="line">
				<div class="l-col">
					<span class="stiker">Скидка: <b>4</b>$</span>
					<div class="w-radio">
						<div class="radio" id="uniform-created-topic">
							<span>
								<input id="created-topic" type="radio" name="discount_choise" value="7" >
							</span>
						</div>
					</div>
				</div>
				<label for="created-topic" class="show-discount">Мы завели тему на вашем форуме</label>
			</div>
			<div class="line">
				<div class="l-col"><label for="forum-url">Ссылка на тему:</label></div>
				<div class="w-input"><input id="forum-url" type="text" name="forum_url" value="" maxlength="100"><i class="cl"></i><i class="cr"></i></div>
			</div>
		</fieldset>-->
		<fieldset>
			<div class="line">
				<div class="l-col"><label for="phone-email">Ваш телефон / E-mail:</label></div>
				<div class="w-input"><input id="phone-email" type="text" name="phone_email" value="*" maxlength="60" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="comment">Примечание:</label></div>
				<div class="w-textarea"><textarea name="comment" maxlength="255"></textarea><i class="ct"></i><i class="cb"></i><i class="cr"></i><i class="cl"></i></div>
			</div>
			<button type="submit" class="btn-sendreq"><span>Отправить запрос</span></button>
		</fieldset>
	</form>
</div>