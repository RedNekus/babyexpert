<?php 
$summa = 0;
$i = 1;
UI::addCSS('/css/cart/list.css');
?>
<div id="cart-page" class="h-mb">
	
	<h1 class="b-page-title"><span>Оформление заказа</span></h1>
	
	<div id="cp-content">
		<div class="icon"></div>
		<table>
			<thead>
				<tr>
					<th colspan="3" class="name">Наименование товара</th>
					<th>Кол-во</th>
					<th class="qty"><span>Стоимость</span></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($items as $item) : ?>
			<?php 
				if (@$item['name']) :
				$total_cena = $item['kolvo'] * transform_to_currency($item, FALSE);
				$summa += $total_cena;
			?>		
				<tr data-item-id="<?php echo $item['id_item']; ?>">
					<td class="num"><?php echo $i++;?>.</td>
					<td class="ec"><span class="btn-show-details">&nbsp;</span></td>
					<td class="name">
						<div><a href="/dengi_za_otzyv" target="_ablank">Деньги за отзыв</a></div>					
						<span><?php echo $item['name']; ?></span>
					</td>
					<td class="qty"><input type="text" data-item-id="<?php echo $item['id_item']; ?>" value="<?php echo $item['kolvo']; ?>"></td>
					<td class="price ci-price-<?php echo $item['id_item']; ?>"><?php echo transform_to_currency($item); ?></td>
					<td class="rm lt"><a href="#" class="btn-remove" rel="<?php echo $item['id_item']; ?>">&nbsp;</a></td>
				</tr>
				<tr class="item-details" style="display: none;">
					<td class="num"></td>
					<td class="ec"></td>
					<td class="con">
						<div class="image">
							<a href="/product/<?php echo $item['path']; ?>/">
								<img src="<?php echo $imagepath['small']['path'].$item['image_path']; ?>" alt="Детские качели Cam Gironanna цвет 198">
							 </a>
						</div>
						<dl class="desc">
							<?php echo filtr_create_to_site($item); 
							$maker = Maker::getMakerByID($item['id_maker'])
							?>
							<div>- <b>Производитель:</b> <?php echo @$maker['name']; ?></div>
							<div>- <b>Страна:</b> <?php echo @$maker['country']; ?></div>
							<?php if (isset($item['id_gift'])): ?>
							<?php $gift = Catalog::getCollectionById($item['id_gift']); ?>
							<div>- <b style="color:red">Ваш подарок:</b> <?php echo $gift['name']; ?></div>
							<?php endif; ?>
						</dl>
					</td>
					<td class="qty">
						<ul>
							<li>Количество:</li>
							<li>Стоимость:</li>
							<li class="total"><b>Всего:</b></li>
						</ul>
					</td>
					<td class="price">
						<ul>
							<li><span id="ci-qty-<?php echo $item['id_item']; ?>"><b><?php echo $item['kolvo']; ?></b></span> шт.</li>
							<li id="ci-price-<?php echo $item['id_item']; ?>"><?php echo transform_to_currency($item); ?></li>
							<li class="total ci-total-<?php echo $item['id_item']; ?>"><?php echo format_total_currency($total_cena); ?></li>
							<?php 
							$header = Tree::getTreeByID($item['id_razdel1']);
							if (($header['id']==4) or ($header['id']==8) or ($header['id']==191)) : ?>
							<li><a href="/sborka/" class="btn_sborka"><span></span>Сборка мебели</a></li>	
							<?php endif; ?>
						</ul>
					</td>
					<td class="rm lt"></td>
				</tr>
				<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td class="num"></td>
					<td class="ec"></td>
					<td class="name">
					</td>
					<td class="qty">
						<span>Общая сумма:</span>
					</td>
					<td colspan="2" class="price">
						<span id="cp-total-amount"><?php echo format_total_currency($summa); ?></span>
					</td>
				</tr>
			</tfoot>				
		</table>			
		<ul class="buttons">
			<li><a href="/cart/zakaz/" class="btn-checkout" id="oformit_zakaz">Оформить заказ</a></li>
			<li><a href="/" class="btn-backtocat">Дозаказать</a></li>
		</ul>
	</div>

</div>
