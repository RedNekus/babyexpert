<head>
<title>Хочу скидку!</title>
</head>
<body>
<p>
<?php if (@$_POST['discount_choise']==1) echo "<b>Мы нашли дешевле</b> <br/>";  ?>
<?php if (!empty($_POST['site_url'])) echo 'Ссылка на сайт: <b>'.$_POST['site_url'].'</b> <br/>'; ?>
<?php if (@$_POST['discount_choise']==2) echo "<b>Я зарегистрировался на сайте</b> <br/>";  ?>
<?php if (@$_POST['discount_choise']==3) echo "<b>Мы оставили отзыв о товаре на вашем сайте</b> <br/>";  ?>
<?php if (@$_POST['discount_choise']==8) echo "<b>Мы оставим отзыв о приобретенном здесь товаре</b> <br/>";  ?>
<?php if (@$_POST['discount_choise']==4) echo "<b>Приедем сами</b> <br/>";  ?>
<?php if (@$_POST['discount_choise']==5) echo "<b>Я постоянный клиент</b> <br/>";  ?>
<?php if (@$_POST['discount_choise']==6) echo "<b>Мы написали отзыв о вашем магазине со ссылкой на сайт!</b> <br/>";  ?>
<?php if (@$_POST['discount_choise']==9) echo "<b>Мы вступил в группу Вконтакте!</b> <br/>";  ?>

<?php if (!empty($_POST['review_url'])) echo 'Ссылка на отзыв: <b>'.$_POST['review_url'].'</b> <br/>'; ?>

Ваш телефон / E-mail: <b><?php echo $_POST['phone_email']; ?></b> <br/>

Дата: <b><?php echo date('d-m-Y H:i:s'); ?></b> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Примечание: <br/>
<p><?php echo $_POST['comment']; ?></p> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Browser: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b> <br/>
IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b> <br/>
</p>
</body>
