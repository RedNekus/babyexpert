<div class="h-plr">
<div class="h-mb">
	<h1 class="b-page-title"><span>Хочу товар которого нет на сайте!</span></h1>
		<form action="/wantproduct/send/" method="post" id="form-want-product" class="form">
		<p>Поля отмеченные <b>*</b> обязательны для заполнения</p>
		<fieldset>
			<div class="wrap">
				<div class="line">
					<div class="l-col"><label for="firstname">Ваше имя:</label></div>
					<div class="w-input">
						<input id="firstname" type="text" name="firstname" value="*" maxlength="60" class="required asterisk"><i class="cl"></i><i class="cr"></i>
					</div>
				</div>
				<div class="line">
					<div class="l-col"><label for="phone">Ваш телефон / E-mail:</label></div>
					<div class="w-input">
						<input id="phone" type="text" name="phone_email" value="*" maxlength="60" class="required asterisk"><i class="cl"></i><i class="cr"></i>
					</div>
				</div>
				<div class="line">
					<div class="l-col"><label for="link">Ссылка на товар:</label></div>
					<div class="w-input"><input id="link" type="text" name="link" value="*" maxlength="100" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
				</div>
				<div class="line">
					<div class="l-col"><label for="comment">Примечание:</label></div>
					<div class="w-textarea"><textarea name="comment" maxlength="255"></textarea><i class="ct"></i><i class="cb"></i><i class="cr"></i><i class="cl"></i></div>
				</div>
			</div>
			<button type="submit" class="btn-sendreq"><span>Отправить</span></button>
		</fieldset>
	</form>
</div>								
</div>