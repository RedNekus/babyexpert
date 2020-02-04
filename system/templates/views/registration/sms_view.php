<?php
	UI::addCSS('/css/registration/sms.css');	
?>
<div class="recovery_form">
	<form action="/registration/activeuser/" method="post" id="form-active-user" class="form">
		<p>Вам на мобильный было отправлено сообщение<br/> с кодом подтверждения регистрации: </p>	
		<fieldset>
			<input type="hidden" name="login" value="<?Php echo $_GET['login']; ?>" maxlength="32" class="required asterisk">
			<div class="line">
				<div class="l-col"><label for="code">Введите код:</label></div>
				<div class="w-input"><input id="code" type="text" name="code" value="*" maxlength="32" class="required asterisk"><i class="cl"></i><i class="cr"></i></div>
			</div>
			<button type="submit" class="btn-register"><span>Отправить</span></button>
		</fieldset>  
  </form>
</div>
