<div class="h-mb">
	<h1 class="b-page-title"><span>Оформление заказа</span></h1>
		<form id="form-checkout" action="/cart/send/" class="form checkout" method="post">
		<?php $last_id = Zakaz_client::getLastNomer(); ?>
		<input type="hidden" name="nomer" value="<?php echo ++$last_id; ?>">
		<p>Поля отмеченные <b>*</b> обязательны для заполнения</p>
		<fieldset>
			<div class="line">
				<div class="l-col"><label for="firstname">Ваше имя:</label></div>
				<div class="w-input"><input id="firstname" type="text" name="firstname" value="<?php echo (@$items['firstname']) ? $items['firstname'] : '*'; ?>" maxlength="60" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
				<p class="note" style="color: green;">если не г.Минск — ФИО указывайте полностью</p>
			</div>
			<div id="phone-info">
				<div class="phone-info">
					<div class="line">
						<p class="note" style="margin-bottom: 5px;">телефон в международном формате: (375297775533)</p>					
						<div class="l-col"><label for="phone">Ваш телефон:</label></div>
						<div class="w-input"><input id="phone" type="text" name="phone[0][name]" value="<?php echo (@$items['phone']) ? $items['phone'] : '*'; ?>" class="required asterisk onlydigit" maxlength="60"><i class="cl"></i><i class="cr"></i></div>
						<span class="rm-phone">Удалить</span>						
					</div>
				</div>
			</div>		
			<span id="add-phone">Добавить телефон</span>			
			<div class="line">
				<div class="l-col"><label for="email">E-mail:</label></div>
				<div class="w-input"><input id="email" type="text" name="email" value="<?php echo (@$items['email']) ? $items['email'] : ''; ?>" maxlength="30"><i class="cl"></i><i class="cr"></i></div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Адрес доставки</legend>
			<div class="line">
				<div class="l-col"><label for="city">Город:</label></div>
				<div class="w-input"><input id="city" type="text" name="city" value="<?php echo (@$items['city']) ? $items['city'] : '*'; ?>" maxlength="60" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
				<p class="note" style="color: green;">если не г.Минск – укажите Ваш город</p>
			</div>				
			<div class="line">
				<div class="l-col"><label for="poselok">Поселок:</label></div>
				<div class="w-input"><input id="poselok" type="text" name="poselok" value="<?php echo (@$items['poselok']) ? $items['poselok'] : ''; ?>" maxlength="60" class="asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>			
			<div class="line">
				<div class="l-col"><label for="street">Улица:</label></div>
				<div class="w-input"><input id="street" type="text" name="street" value="<?php echo (@$items['street']) ? $items['street'] : '*'; ?>" maxlength="60" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
				<p class="note">если переулок, укажите это в графе</p>
			</div>
			<div class="line">
				<div class="l-col"><label for="house">Дом:</label></div>
				<div class="w-input"><input id="house" type="text" name="house" value="<?php echo (@$items['house']) ? $items['house'] : '*'; ?>" maxlength="6" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="building" class="building">Корпус:</label></div>
				<div class="w-input"><input id="building" type="text" name="building" value="<?php echo (@$items['building']) ? $items['building'] : ''; ?>" maxlength="4"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="apartment" class="apartment">Квартира:</label></div>
				<div class="w-input"><input id="apartment" type="text" name="apartment" value="<?php echo (@$items['apartment']) ? $items['apartment'] : ''; ?>" maxlength="6" ><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="entrance">Подъезд:</label></div>
				<div class="w-input"><input id="entrance" type="text" name="entrance" value="<?php echo (@$items['entrance']) ? $items['entrance'] : ''; ?>" maxlength="3"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="floor" class="floor">Этаж:</label></div>
				<div class="w-input"><input id="floor" type="text" name="floor" value="<?php echo (@$items['floor']) ? $items['floor'] : ''; ?>" maxlength="2"><i class="cl"></i><i class="cr"></i></div>
			</div>
		</fieldset>
		<fieldset>
			<div class="line cmt">
				<div class="l-col"><label for="comment">Примечание:</label></div>
				<div class="w-textarea"><textarea name="comment" maxlength="255"></textarea><i class="ct"></i><i class="cb"></i><i class="cr"></i><i class="cl"></i></div>
			</div>
			<button type="submit" class="btn-frmcheck"><span>Оформить заказ</span></button>
		</fieldset>
	</form>
</div>				