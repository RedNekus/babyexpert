<head>
<title>Хочу товар которого нет на сайте!</title>
</head>
<body>
<p>
Ваше имя: <b><?php echo $_POST['firstname']; ?></b> <br/>
Ваш телефон / E-mail: <b><?php echo $_POST['phone_email']; ?></b> <br/>
Ссылка на товар: <b><?php echo $_POST['link']; ?></b> <br/>

Дата: <b><?php echo date('d-m-Y H:i:s'); ?></b> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Примечание: <br/>
<p><?php echo $_POST['comment']; ?></p> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Browser: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b> <br/>
IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b> <br/>
</p>
</body>
