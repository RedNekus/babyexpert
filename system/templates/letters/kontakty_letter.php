<head>
<title>ЛАКОРД</title>
</head>
<body>
<p>
Имя: <b><?php echo $_POST['name']; ?></b> <br/>
E-Mail: <b><?php echo $_POST['tel_email']; ?></b> <br/>
Дата: <b><?php echo date('d-m-Y H:i:s'); ?></b> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Вопрос: <br/>
<p><?php echo $_POST['question']; ?></p> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Browser: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b> <br/>
IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b> <br/>
</p>
</body>
