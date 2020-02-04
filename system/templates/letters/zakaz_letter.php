<head>
<title>Заказ с сайта!</title>
</head>
<body>
<p>
Дата: <b><?php echo date('d-m-Y H:i:s'); ?></b> <br/> <br/>
<table style="width: 700px; border: 1px solid #000;" border=1>
	<tr style="height: 30px; padding: 5px;">
		<td>Номер</td>
		<td>Наименование</td>
		<td>Кол-во</td>
		<td>Цена</td>
		<td>Сумма</td>
		<td>Ссылка</td>
		<td>Подарок</td>
		<td>Розыгрыш</td>
	</tr>
<?php 
if (isset($_SESSION['collection'])) :
foreach($_SESSION['collection'] as $item) : 
	if (isset($item['name'])) :
	$total_cena = $item['kolvo'] * intval($item['cena']);
	$summa = $total_cena + @$summa;	
?>
	<tr style="height: 30px; padding: 5px; border: 1p solid #000;">
		<td><b><?php echo $item['id']; ?></b></td>
		<td><b><?php echo $item['name']; ?></b></td>
		<td style="text-align: center;"><b><?php echo $item['kolvo']; ?></b></td>
		<td style="text-align: center;"><b><?php echo $item['cena']; ?></b></td>
		<td style="text-align: center;"><b><?php echo $total_cena; ?></b></td>
		<td><a href="http://babyexpert.by/product/<?php echo $item['path']; ?>" target="_ablank"><?php echo $item['name']; ?></a></td>
		<?php if (isset($item['id_gift'])): ?>
		<?php $gift = Catalog::getCollectionById($item['id_gift']); ?>
		<td><a href="http://babyexpert.by/product/<?php echo $gift['path']; ?>" target="_ablank"><?php echo $gift['name']; ?></a></td>
		<?php else: ?>
		<td>&nbsp;</td>
		<?php endif; ?>
		<td><?php echo ($item['raffle']!=0) ? "Да" : "Нет"; ?></td>
	</tr>
<?php 
	endif;
	endforeach; 
	endif;
?>
</table>
ИТОГОВАЯ СУММА: <b><?php echo @$summa; ?></b> <br/>
<br/>
Контактные данные <br/>
<?php 
if(!empty($_POST['firstname'])) echo 'Имя: <b>'.$_POST['firstname'].'</b> <br/>';
$phone = '';
foreach($_POST['phone'] as $item) {
	$phone .= $item['name'].',';
}
if(!empty($phone)) echo 'Телефон: <b>'.$phone.'</b> <br/>';

if(!empty($_POST['email'])) echo 'E-Mail: <b>'.$_POST['email'].'</b> <br/>';
echo '<br/>';
echo 'Адрес доставки <br/>';
if(!empty($_POST['city'])) echo 'Город: <b>'.$_POST['city'].'</b> <br/>';
if(!empty($_POST['street'])) echo 'Улица: <b>'.$_POST['street'].'</b> <br/>';
if(!empty($_POST['house'])) echo 'Дом: <b>'.$_POST['house'].'</b> <br/>';
if(!empty($_POST['building'])) echo 'Корпус: <b>'.$_POST['building'].'</b> <br/>';
if(!empty($_POST['apartment'])) echo 'Квартира: <b>'.$_POST['apartment'].'</b> <br/>';
if(!empty($_POST['floor'])) echo 'Этаж: <b>'.$_POST['floor'].'</b> <br/>';
if(!empty($_POST['entrance'])) echo 'Подъезд: <b>'.$_POST['entrance'].'</b> <br/>';
?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Примечание: <br/>
<p><?php echo $_POST['comment']; ?></p> <br/>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~ <br/>
Browser: <b><?php echo $_SERVER['HTTP_USER_AGENT']; ?></b> <br/>
IP: <b><?php echo $_SERVER['REMOTE_ADDR']; ?></b> <br/>
</p>
</body>
