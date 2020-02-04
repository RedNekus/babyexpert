<form action="/callback/send/" method="post" id="form-callback" class="form callback">
	<p>Поля отмеченные <b>*</b> обязательны для заполнения</p>
	<fieldset>
		<div class="wrap">
			<div class="line">
				<div class="l-col"><label for="firstname">Ваше имя:</label></div>
				<div class="w-input"><input id="firstname" type="text" name="firstname" value="*" maxlength="60" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="phone">Ваш телефон:</label></div>
				<div class="w-input"><input id="phone" type="text" name="phone" value="*" maxlength="60" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<div class="line">
				<div class="l-col"><label for="comment">Примечание:</label></div>
				<div class="w-textarea"><textarea name="comment" maxlength="255"></textarea><i class="ct"></i><i class="cb"></i><i class="cr"></i><i class="cl"></i></div>
			</div>
		</div>
		<button type="submit" class="btn-sendreq"><span>Отправить</span></button>
	</fieldset>
</form>
