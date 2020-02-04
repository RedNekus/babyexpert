<div class="h-mb">
	<form action="/registration/add/" method="post" id="form-registration" class="form registration">
		<p>Поля отмеченные <b>*</b> обязательны для заполнения</p>
		<fieldset>
			<div class="line">
				<div class="l-col"><label for="login">Логин:</label></div>
				<div class="w-input"><input id="login" type="text" name="login" value="*" maxlength="32" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="password">Пароль:</label></div>
				<div class="w-input"><input id="password" type="text" name="pass" value="*" maxlength="20" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="confirm">Подтвердите пароль:</label></div>
				<div class="w-input"><input id="confirm" type="text" name="confirm" value="*" maxlength="20" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Контактная информация</legend>
			<div class="line">
				<div class="l-col"><label for="name">Ваше имя:</label></div>
				<div class="w-input"><input id="name" type="text" name="name" value="" maxlength="60"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="fam">Ваша фамилия:</label></div>
				<div class="w-input"><input id="fam" type="text" name="fam" value="" maxlength="60"><i class="cl"></i><i class="cr"></i></div>
			</div>			
			<div class="line">
				<div class="l-col"><label for="email">E-mail:</label></div>
				<div class="w-input"><input id="email" type="text" name="email" value="*" maxlength="30" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div id="phone-info">
				<div class="phone-info">
					<div class="line">
						<p class="note" style="margin-bottom: 5px;">телефон в международном формате: (375297775533)</p>					
						<div class="l-col"><label for="phone">Ваш телефон:</label></div>
						<div class="w-input"><input id="phone" type="text" name="phone[0][name]" value="*" class="required asterisk onlydigit" maxlength="60"><i class="cl"></i><i class="cr"></i></div>
						<span class="rm-phone">Удалить</span>						
					</div>
				</div>
			</div>
			<span id="add-phone">Добавить телефон</span>
		</fieldset>
		<fieldset>
			<legend>Дети</legend>
			<div id="children-info">
				<div class="child-info">
					<div class="line">
						<div class="l-col"><label for="child_name">Имя ребенка:</label></div>
						<div class="w-input"><input id="child_name" type="text" name="children[0][name]"><i class="cl"></i><i class="cr"></i></div>
					</div>
					<div class="line">
						<div class="l-col"><label>Дата рождения:</label></div>
						<div class="w-select">
							<select name="children[0][d]" aria-disabled="false" style="display: none;">
								<option value="0">число</option>
								<?php for($i=1; $i<32; $i++) echo '<option value="'.$i.'">'.$i.'</option>'; ?>
							</select>
						</div>
						<div class="w-select">
							<select name="children[0][m]" aria-disabled="false" style="display: none;">
								<option value="0">месяц</option>
								<?php for($i=1; $i<13; $i++) echo '<option value="'.$i.'">'.$i.'</option>'; ?>
							</select>
						</div>
						<div class="w-select">
							<select name="children[0][y]" aria-disabled="false" style="display: none;">
								<option value="0">год</option>
								<?php for($i=2005; $i<2025; $i++) echo '<option value="'.$i.'">'.$i.'</option>'; ?>
							</select>
						</div>
						<span class="rm-child">Удалить</span>
					</div>
				</div>
			</div>
			<span id="add-child">Добавить ребенка</span>
		</fieldset>
		<fieldset>
			<legend>Информация для быстрого оформления заказов</legend>
			<div class="line">
				<div class="l-col"><label for="city">Город:</label></div>
				<div class="w-input"><input id="city" type="text" name="city" value="" maxlength="60"><i class="cl"></i><i class="cr"></i></div>
			</div>			
			<div class="line">
				<div class="l-col"><label for="street">Улица:</label></div>
				<div class="w-input"><input id="street" type="text" name="street" value="" maxlength="60"><i class="cl"></i><i class="cr"></i></div>
				<p class="note">если переулок, укажите это в графе</p>
			</div>
			<div class="line">
				<div class="l-col"><label for="house">Дом:</label></div>
				<div class="w-input"><input id="house" type="text" name="house" value="" maxlength="6"><i class="cl"></i><i class="cr"></i></div>
				<label for="building" class="building">Корпус:</label><div class="w-input"><input id="building" type="text" name="building" value="" maxlength="4"><i class="cl"></i><i class="cr"></i></div>
				<label for="apartment" class="apartment">Квартира:</label><div class="w-input"><input id="apartment" type="text" name="apartment" value="" maxlength="6"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="entrance">Подъезд:</label></div>
				<div class="w-input"><input id="entrance" type="text" name="entrance" value="" maxlength="3"><i class="cl"></i><i class="cr"></i></div>
				<label for="floor" class="floor">Этаж:</label><div class="w-input"><input id="floor" type="text" name="floor" value="" maxlength="2"><i class="cl"></i><i class="cr"></i></div>
			</div>
		</fieldset>
		<fieldset>
			<div class="line cmt">
				<div class="l-col"><label for="comment">Примечание:</label></div>
				<div class="w-textarea"><textarea name="comment" maxlength="255"></textarea><i class="ct"></i><i class="cb"></i><i class="cr"></i><i class="cl"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="card-number">Номер карты:</label></div>
				<div class="w-input"><input id="card-number" type="text" name="card_number" value="" maxlength="32"><i class="cl"></i><i class="cr"></i></div>
				<label for="promo-code" class="promo-code">Промо код:</label><div class="w-input"><input id="promo-code" type="text" title="Промокод дает право на получение скидок и участие в розыгрышах. Выдается при покупке товара на сумму превышающую 50 у.е." name="promo_code" value="" maxlength="32"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="ch-group">
				<div class="line">
					<p>Тип отображения цен на сайте:</p>
					<div class="r-group">
						<div class="w-radio"><input id="with-discount" type="radio" name="discount_price" value="1" checked="checked" style="opacity: 0;"></div>
						<label for="with-discount">Со скидкой</label>
						<div class="w-radio"><input id="without-discount" type="radio" name="discount_price" value="0" style="opacity: 0;"></div>
						<label for="without-discount">Без скидки</label>	
					</div>
				</div>
				<div class="line">
					<div class="w-checkbox"><input id="newsletter" type="checkbox" name="newsletter" value="1" checked="checked" style="opacity: 0;"></div>
					<label for="newsletter">Я хочу получать информацию о скидках и акциях</label>
				</div>
			</div>
			<button type="submit" class="btn-register"><span>Отправить</span></button>
		</fieldset>
	</form>
</div>								