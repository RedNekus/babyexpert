<?php
  UI::addCSS('/css/registration/recovery.css');
  UI::addJS('/js/registration.js');
?>
<h1><?php echo @$h1item; ?></h1>
<div id="recovery_form">
  <form action="/registration/recover/" method="post" id="recoveryForm">
    <table>
		<tr>
			<td colspan=2>Введите Email, указанный при регистрации</td>
		</tr>
		<tr>
			<td colspan=2><span style="color: red;"><?php print_r(@$error); ?></span></td>
		</tr>		
		<tr>
			<td><input type="text" name="email" value="<?php echo @$_POST['email']; ?>" class="required_reg" maxlength="100" /></td>
			<td class="reg_t3"><div class="btn"></div></td>
		</tr>		  
    </table>
  </form>
</div>
