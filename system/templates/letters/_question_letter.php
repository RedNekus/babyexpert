<head>
<title>ЛАКОРД</title>
</head>
<body>
<p>
Наименование организации: <b><?php echo $_POST['name']; ?></b> <br/>
Телефон: <b><?php echo $_POST['tel']; ?></b> <br/>
E-Mail: <b><?php echo $_POST['email']; ?></b> <br/>
Хочет стать: <?php if (@$_POST['client']==1) : ?> КЛИЕНТОМ <?php else: ?> ПОСТАВЩИКОМ <?php endif; ?> <br/>
Дата: <b><?php echo date('d-m-Y H:i:s'); ?></b> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Вопрос: <br/>
<p><?php echo $_POST['question']; ?></p> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Browser: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b> <br/>
IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b> <br/>
</p>
</body>
