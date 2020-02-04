<head>
<title>Babyexpert</title>
</head>
<body>
<p>
Ваш вопрос: </br>
<p><?php echo $_POST['question']; ?></p>
</br>
Ответ: </br>
<p><?php echo $_POST['answer']; ?></p>
</br>
<?php 
$item = Catalog::getCollectionById($_POST['id_catalog']);
$host = $_SERVER['HTTP_HOST'];
?>
</br>
Ссылка на ответ: <a href="http://<?php echo $host."/product/".@$item['path']; ?>#pit-tab-7" title="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></a>
</br>~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Browser: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b> <br/>
IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b> <br/>
</p>
</body>
