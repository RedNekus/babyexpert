<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta charset="utf-8">
<title>Вход</title>
<link type="text/css" rel="stylesheet" href="/css/admin/admin_login.css" />
</head>
<body>
<div id="login-box">
    <h1>Вход</h1>
    <form action="" method="post">
	<?php 
	if (isset($error['no_valid'])) : ?>
	<p class="errors">Не верный логин или пароль</p>
	<?php endif; ?>
	
        <div>
			<label for="login">Логин:</label> 
            
			<input type="text" name="login" id="login" value="<?php echo @$_POST['login']; ?>" maxlength="30">        
			
		</div>
        
		<div>
            <label for="pass">Пароль:</label> 
            
			<input type="password" name="password" id="pass" value="" maxlength="30">        
		
		</div>
        
		<button type="submit" name="login_form"><span>Вход</span></button>
    </form>
</div>
</body>
</html>