
<?php 

  UI::addCSS(array(

    '/js/fancybox2/jquery.fancybox.css',
	
	'/js/fancybox2/helpers/jquery.fancybox-buttons.css',
	
    '/css/catalog/detailed.css'
	
  ));

?>
<div class="h-mb">

	<section itemscope itemtype="http://schema.org/Product">
	<ul class="b-breadcrumbs">
		<li><a href="/category/"><b>Каталог</b></a>&nbsp;/&nbsp;</li>
		<li><a href="/category/<?php echo @$header['path']; ?>/"><?php echo @$header['name']; ?></a>&nbsp;/&nbsp;</li>
		<?php if (isset($header0)): ?>
		<li><a href="/category/<?php echo @$header0['path']; ?>/"><?php echo @$header0['name']; ?></a>&nbsp;/&nbsp;</li>
		<?php endif; ?>
		<?php echo get_product_name($collection); ?>					
	</ul>

	<div class="l-product-info">
	
		<div class="b-product-image">
			
			<div id="pheader"><?php echo get_product_name($collection); ?></div>
			
			<?php if (!empty($gifts)): ?>
			<div class="b-gift">
				<?php foreach($gifts as $gift): ?>
					<a href="<?php echo $imagepath['big']['path'].insert_image($gift['id']); ?>" title="<?php echo $gift['name']; ?>" class="image fancybox_color" gift-model-id="<?php echo $gift['id']; ?>" data-model-id="<?php echo $collection['id']; ?>" rel="gifts" >
						<img src="<?php echo $imagepath['small']['path'].insert_image($gift['id']); ?>" alt="" title="">
					</a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			
			<?php
			echo '<a href="'.$imagepath['big']['path'].insert_image($collection['id']).'" title="'.$collection['name'].'" class="image fancybox_color" rel="gruope" id="product-medium-img">';
			echo '<img itemprop="image" src="'.$imagepath['big']['path'].insert_image($collection['id']).'" alt="'.$collection['id_prefix'].' '.$collection['name'].'">';
			echo '</a>';
			?>				

		</div>
		
		<div class="b-product-short-desc">
			<div class="b-product-name">
				<h1 itemprop="name">
					<?php echo get_product_name($collection,true); ?>
				</h1>
			</div>
			<div class="b-product-short-desc_content">
				<?php if ($header['show_banner']==1): ?>
				<!--<div class="show-banner">
					<div class="h4">Нашли дешевле?</div>
					<div class="h3">получите еще дешевле</div>
					<div class="h2">на <strong>50 000</strong> руб</div>
					<div class="h1">ЗВОНИТЕ!</div>
				</div>-->
				<?php if (@$manager): ?>
				<div class="table-catalog-sklad">
				<?php echo get_table_sklad_tovar($collection['id']); ?>
				</div>
				<?php endif; ?>					
				<?php else: ?>
					<a href="/shipping/" class="icons dostavka"><i></i><span><b>Условия доставки</b></span></a>
					<a href="/shipping/" class="icons dostavka_blr"><i></i><span><b>доставка по</br>беларуси</b></span></a>
					<!--<a href="/wantdiscount/foundcheaper" class="icons deshevle"><i></i><span><b>нашли дешевле?</b></span></a>-->
					<a href="/diskontnaya_programma/" class="icons discont"><i></i><span><b>дисконтная<br/>программа</b></span></a>
					
					<a href="/wantdiscount/" class="btn_s skidka"></a>
					<?php if (@$manager): ?>
					<div class="table-catalog-sklad">
					<?php echo get_table_sklad_tovar($collection['id']); ?>
					</div>
					<?php else: ?>					
						<?php if ((@$header['id']==4) or (@$header['id']==8) or (@$header['id']==191)) : ?>
						<a href="/sborka/" class="btn_sborka"><span></span>Сборка мебели</a>	
						<?php endif; ?>
						<!--<a href="/akciya/" class="btn_s akciya"></a>-->	
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="b-fl-wrapper">
				<div class="b-fl-character">
					<?php echo filtr_create_to_site($collection); ?>
				</div>			
				<div class="b-product-main-features">
					<a href="#" class="importer-izgotovitel">Информация об <br/>изготовителе и импортере</a>
					<div id="ii-display">
					<?php 
					$importer = Database::getRow(get_table('importer'),$collection['importer']);
					if (!empty($maker['id_importer'])) {
						$importer = Database::getRow(get_table('importer'),$maker['id_importer']);	 
					}
					if (!empty($importer)) {
						$import_name = $importer['name'];
						$import_adres = $importer['adres'];
						$import_contact = $importer['contact'];
						$import_serv_centr = $importer['serv_centr'];
						$izgotovitel = Database::getRow(get_table('manufacturer'),$maker['id_manufacturer']);
						$import_izgotovitel = @$izgotovitel['name'];					
						echo '<div><strong>Импортер:</strong> '.$import_name.'</div>';
						echo '<div><strong>Адрес:</strong> '.$import_adres.'</div>';
						echo '<div><strong>Контакты:</strong> '.$import_contact.'</div>';
						echo '<div><strong>Сервисный центр:</strong> '.$import_serv_centr.'</div>';
						echo '<div><strong>Изготовитель:</strong> '.$import_izgotovitel.'</div>';	
					}					
					?>
					</div>
				</div>
				<ul class="b-product-links">
					<li><a href="/cart/compare/" id="compare_click">К сравнению</a></li>
					<li><a id="goto-detail-char" href="#product-info-tabs">Характеристики</a></li>
					<?php if(@$_SESSION['user']['manager']==1): ?>
					<li><input type="text" id="manager_tmp" rel="<?php echo $collection['id']; ?>" value="<?php echo $collection['id']; ?>"/></li>
					<?php else: ?>
					<li><a id="goto-detail-desc" href="#product-info-tabs">Оставить отзыв</a></li>
					<?php endif; ?>
					
				</ul>
			</div>
		</div><!-- /.l-product-info__right -->
	</div><!-- /.b-product-info -->
	
	<div id="product-media-tabs" class="b-product-media-tabs">
		<ul class="tabs-panel">
			<li><a href="#product-info-tabs" id="goto-detail-photo">Смотреть все расцветки (<?php echo count($colors).' шт.'; ?>)</a></li>
			<!--<li><a href="#pmt-tab-1">Расцветки</a>
			<li><a href="#pmt-tab-2">Доп. фотографии</a>-->
		</ul>
		<div class="tabs-content">
			<?php if (isset($colors) && !empty($colors)): ?>
			<div id="pmt-tab-1" class="b-product-media-carousel">
				<ul class="product-media-carousel" id="pmodels-carousel">
					<?php foreach($colors as $item) : ?>
					<li>
						<a href="<?php echo $imagepath['big']['path'].insert_image($item['id']); ?>" data-model-id="<?php echo $item['id']; ?>" data-model-price="<?= formatCena(transform_to_currency($item,false)); ?>">
							<img src="<?php echo $imagepath['small']['path'].insert_image($item['id']); ?>" alt="<?php echo get_product_name($item); ?>">
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div>
	
	
	<div class="b-product-buy">	
		<div class="inner clearfix">
			<div class="l-product-prices">
				<?php 
				get_star_by_product($collection);  
				if ($collection['status'] != 4) {
					if (isset($colors) && !empty($colors)) {
						$price = $colors[0];
					} else {
						$price = $collection;
					}
					if ($collection['cena_old']>0 or $collection['cena_blr_old']>0) {
						echo '<div class="b-product-price old"><span class="cena-old">'.transform_to_currency_old($price).'</span></div>';
						echo '<div class="b-product-price old" itemprop="offers" itemscope itemtype="http://schema.org/Offer">Цена: <span id="refresh-price">'.transform_to_currency($price).'<meta itemprop="price" content="'.transform_to_currency($price,false).'"><meta itemprop="priceCurrency" content="BYN"></span></div>';
					} else {
						echo '<div class="b-product-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">Цена: <span id="refresh-price">'.transform_to_currency($price).'<meta itemprop="price" content="'.transform_to_currency($price,false).'"><meta itemprop="priceCurrency" content="BYN"></span></div>';
					}
				} else {
					echo '<a href="#" class="b-product-price b-product-status r6">Снят с производства</a>';
				}
				?>				
				<div class="b-product-status r<?php echo $collection['status']; ?>"><?php get_status($collection['status']); ?></div>				
			</div>
			<?php if (!empty($collection['raffle'])): ?>
			<div class="b-raffle_icon">
			</div>
			<?php endif; ?>			
			<ul class="b-product-buttons">
				<li>
					<form id="form-product-wishlist" class="form-product-wishlist" action="/cart/addtocompare/" method="post">
						<input type="hidden" name="pid" value="<?php echo $collection['id']; ?>">
						<input type="submit" value="Отложить" class="btn wish">
					</form>
				</li>
				<li>
					<form id="form-product-buy" action="/cart/addtocart/" method="post">
						<input type="hidden" name="pid" value="<?php echo $collection['id']; ?>">
						<?php if(@$gifts[0]['id']): ?>
						<input type="hidden" name="gid" id="val-gifts" value="<?php echo @$gifts[0]['id']; ?>">
						<?php endif; ?>
						<input type="submit" value="Купить" class="btn buy">
					</form>
				</li>			
			</ul>
		</div>	
		<?php if(@$_SESSION['user']['manager']==1): ?>		
		<ul class="d-currency">
			<?php $usd = "usd"; $byr = "byr"; 
				if (!isset($_SESSION['currency'])) $_SESSION['currency'] = $usd;
			?>	
			<li <?php if (@$_SESSION['currency']==$usd): ?>class="active"<?php endif; ?>>
				<a href="/home/refreshcurrency/" rel="<?php echo $usd; ?>" data-id-catalog="<?php echo $collection['id']; ?>" class="refresh-curs">USD</a>
			</li><li <?php if (@$_SESSION['currency']==$byr): ?>class="active"<?php endif; ?>>
				<a href="/home/refreshcurrency/" rel="<?php echo $byr; ?>" data-id-catalog="<?php echo $collection['id']; ?>" class="refresh-curs">BYR</a>
			</li>
									
		</ul>
		<?php endif; ?>
		<div class="b-social">
			<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj"></div>
		</div>

	</div>	
		<div itemprop="description" style="display:none;">
			<?php echo $collection['description']; ?>
		</div>
	</section>
	
	<div id="product-info-tabs" class="b-product-info-tabs">
		<ul>
			<li class="ui-tabs-selected">
				<a href="#pit-tab-1">Характеристики</a>
			</li>			
			<li>
				<a href="#pit-tab-2">Описание</a>
			</li>
			<li>
				<a href="#pit-tab-3">Расцветки</a>
			</li>
			<li>
				<a href="#pit-tab-4">Сопутствующие</a>
			</li>			
			<li>
				<a href="#pit-tab-5">Похожие</a>
			</li>
			<li>
				<a href="#pit-tab-6">Отзывы <?php echo count(@$reviews); ?></a>
			</li>
			<li>
				<a href="#pit-tab-7">Вопрос-ответ <?php echo count(@$question); ?></a>
			</li>
		</ul>
		<div class="tabs-content">
			<div id="pit-tab-1">
				<table class="b-product-features">
					<?php echo $charactrform; ?>
				</table>
				<div class="b-product-warning">
					<strong>Внимание:</strong> <br/> Цвета товаров на сайте могут незначительно отличаться от оригинала в зависимости от настроек Вашего дисплея либо монитора.
					Производитель оставляет за собой право изменять комплектацию товара, вносить конструктивные и дизайнерские изменения 
					(в том числе дизайн колесных дисков, рисунок протектора шин и прочее) без предварительного уведомления.</br>
					Магазин не несет ответственности за действия производителя касаемо  этих изменений.
				</div>
			</div>		
			<div id="pit-tab-2">
				<div id="product-desc-tabs" class="b-product-desc-tabs">
					<ul class="tabs-panel">
						<li><a href="#pdt-tab-1">Описание</a></li>
						<li><a href="#pdt-tab-2">Инструкции</a></li>
					</ul>					
					<div id="pdt-tab-1">
						<div class="b-product-full-desc editor">
							<?php echo $collection['full_description']; ?>
						</div>
						<div class="b-product-video">
							<?php $v = new VideoThumb($collection['video_url']); ?>
							<iframe width="560" height="315" src="<?php echo $v->getVideo(); ?>" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
					<div id="pdt-tab-2">
						<div class="b-product-full-desc editor">
							<?php echo $collection['instructions']; ?>
						</div>
					</div>						
				</div>			
				<div class="editor">
					
				</div>
			</div>
			<div id="pit-tab-3" class="colors-tab">
				<?php if (isset($colors) && !empty($colors)): ?>
				<div class="b-product-colors h-product-colors">
					<div class="head"><span>Расцветки</span></div>
					<ul>
						<?php foreach($colors as $item) : ?>
						<li>
							<div class="photo">
								<div class="name"><?php echo $item['name']; ?></div>
								<div class="image">
									<a href="<?php echo $imagepath['big']['path'].insert_image($item['id']); ?>" title="<?php echo $item['name']; ?>" class="image fancybox_color" rel="gruope" data-model-id="<?php echo $item['id']; ?>">
										<img src="<?php echo $imagepath['small']['path'].insert_image($item['id']); ?>" alt="<?php echo get_product_name($item); ?>" >
									</a>
								</div>
							</div>
							<?php if (@$manager): ?>
							<div class="table-catalog-sklad">
							<?php echo get_table_sklad_tovar($item['id'],false); ?>
							</div>
							<?php endif; ?>	
							<span><?= transform_to_currency($item); ?></span>
							<span class="btn-choose-color" data-model-id="<?php echo $item['id']; ?>" data-model-price="<?= formatCena(transform_to_currency($item,false)); ?>">Выбрать</span>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
				<div class="b-product-colors">
					<div class="head"><span>Дополнительные фото</span></div>
					<ul>
						<?php foreach($images as $item) : ?>
						<li>
							<div class="photo">
								<div class="name"><?php echo $item['description']; ?></div>
								<div class="image">
								<a href="<?php echo $imagepath['big']['path'].$item['image']; ?>" class="image fancybox" title="<?php echo $item['description']; ?>"  rel="gruope_photo">
									<img src="<?php echo $imagepath['small']['path'].$item['image']; ?>" alt="<?php echo $item['description']; ?>">
								</a>
								</div>
							</div>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div id="pit-tab-4">
				<div class="b-related-products">
					<ul class="view-switcher">
						<li class="active">
							Табличный вид
						</li><li class="">
							Развернутый вид
						</li>
					</ul>
					<ul class="grid">
					<?php 
					foreach (explode(',',$collection['soput']) as $item): 
					if ($soput = Catalog::getCollectionByID(intval(substr($item, 7)))): 
					?>
						<li>
							<div class="l-left">
								<div class="photo">
									<a href="/product/<?php echo $soput['path']; ?>/" class="name"><?php echo get_product_name($soput); ?></a>
									<a href="/product/<?php echo $soput['path']; ?>/" class="image">
										<img src="<?php echo $imagepath['small']['path'].insert_image($soput['id']); ?>" alt="<?php echo get_product_name($soput); ?>">
									</a>
								</div>
								<div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">Цена: <?php echo transform_to_currency($soput); ?></div>
							</div>
							<div class="l-right">
								<div class="desc editor"><?php echo $soput['short_description']; ?></div>
								<ul class="buttons">
									<li>
										<form class="form-product-wishlist" action="/cart/addtocompare/" method="post">
											<input type="hidden" name="pid" value="<?php echo $soput['id']; ?>">
											<input type="submit" value="" class="btn wish">
										</form>										
									</li>
									<li>
										<a href="/product/<?php echo $soput['path']; ?>/" class="btn-rpdetails">Подробнее</a>
									</li>				
								</ul><!-- /.buttons -->
							</div>
						</li>
					<?php 
					endif; 
					endforeach; 
					?>
					</ul>
				</div>				
			</div>
			<div id="pit-tab-5">
				<div class="b-related-products ">	
					<ul class="view-switcher">
						<li class="active">
							Табличный вид
						</li><li class="">
							Развернутый вид
						</li>
					</ul>				
					<ul class="grid">
					<?php 
					$limit = 0;
					foreach (explode(',',$collection['pohozhie']) as $item):
					if ($limit++ > 6) continue;
					if ($pohoz = Catalog::getCollectionByID(intval(substr($item, 10)))): 
					?>
						<li>
							<div class="l-left">
								<div class="photo">
									<a href="/product/<?php echo $pohoz['path']; ?>/" class="name"><?php echo get_product_name($pohoz); ?></a>
									<a href="/product/<?php echo $pohoz['path']; ?>/" class="image">
										<img src="<?php echo $imagepath['small']['path'].insert_image($pohoz['id']); ?>" alt="<?php echo get_product_name($pohoz); ?>">
									</a>
								</div>	
								<div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">Цена: <?php echo transform_to_currency($pohoz); ?></div>									
							</div>
							<div class="l-right">
								<div class="desc editor">
									<?php echo $pohoz['short_description']; ?>
								</div>
								<ul class="buttons">
									<li>
										<form class="form-product-wishlist" action="/cart/addtocompare/" method="post">
											<input type="hidden" name="pid" value="<?php echo $pohoz['id']; ?>">
											<input type="submit" value="" class="btn wish">
										</form>											
									</li>
									<li>
										<a href="/product/<?php echo $pohoz['path']; ?>/" class="btn-rpdetails">Подробнее</a>
									</li>				
								</ul><!-- /.buttons -->
							</div>
						</li>
						<?php endif; ?>
					<?php endforeach; ?>
					</ul><!-- /.row -->
				</div><!-- /.b-products -->
			</div>			
			<div id="pit-tab-6">
				<form action="/category/addreview" method="post" id="form-review" class="b-user-write-form comment">
					<input type="hidden" name="id_catalog" value="<?php echo $collection['id']; ?>">
					<fieldset class="l-left">
						<div class="inner">
							<dl>
								<dt><label for="cf-name">Ваше Имя</label></dt>
								<dd><div class="w-uwf-input"><input type="text" id="cf-name" name="name" value="*" maxlength="60" class="required sred asterisk"></div></dd>
							</dl>
							<dl>
								<dt><label for="cf-email">Ваш Email</label></dt>
								<dd><div class="w-uwf-input"><input type="text" id="cf-email" name="email" value="" maxlength="30" class="asterisk"></div></dd>
							</dl>							
							<dl>
								<dt><label for="cf-email">Моб.тел.</label></dt>
								<dd><div class="w-uwf-input"><input type="text" id="cf-telefon" name="telefon" value="*" maxlength="30" class="required sblue asterisk onlydigit"></div></dd>
							</dl>							
							<dl>
								<dt><label for="cf-email">Промо код</label></dt>
								<dd><div class="w-uwf-input"><input type="text" id="cf-promocod" name="promocod" value="" maxlength="30" class="asterisk"></div></dd>
							</dl>
							<dl class="rating">
								<dt>Оценить</dt>
								<dd>
									<input type="radio" name="rating" value="1" class="star" title="1" />
									<input type="radio" name="rating" value="2" class="star" title="2" />
									<input type="radio" name="rating" value="3" class="star" title="3" />
									<input type="radio" name="rating" value="4" class="star" title="4" />
									<input type="radio" name="rating" value="5" class="star" title="5" checked />
								</dd>
							</dl>
						</div>
						<ul class="instructions">
							<li><span><b class="sred">*</b> - </span>поля обязательные для заполнения;</li>
							<li><span><b class="sblue">*</b> - </span>номер телефона в международном</br>формате (<i>пример: 375330000000</i>);</li>
							<li><span><b class="sgreen">*</b> - </span>код с карты клиента</br>(<i>для получения бонуса</i>)</li>
						</ul>
					</fieldset><fieldset class="l-right">
						<dl>
							<dt><label for="cf-comment">Отзыв</label></dt>
							<dd><div class="w-uwf-textarea"><textarea id="cf-comment" name="content" maxlength="1255"></textarea></div></dd>
						</dl>
						<dl>
							<dt><label style="margin-top: 10px; display: block;">Введите код с картинки</label></dt>
							<dd>
								<div class="w-uwf-input" style="float: left; margin-right: 15px;"><input type="text" name="kapcha"/></div>
								<img src = "/assets/kapcha.php" style="float: left; border: 2px solid #fff; "/> 				
							</dd>
						</dl>
						</br class="clear">
						<div class="bonus-image">
							<ul class="buttons">
								<li><input type="submit" value="Отправить" class="btn uwf-send"></li>
							</ul>
						</div>
					</fieldset>
				</form>	
				<?php if (@$reviews): ?>
					<div class="b-comments-list">
						<table>
							<tbody>
								<?php foreach($reviews as $item): ?>
									<tr>
										<td>
											<span><?php echo $item['name']; ?></span></br>
											<span><?php echo '<img src="/img/admin/rank_'.$item['rank'].'.png" width=75px alt="" />'; ?></span>
										</td>
										<td>
											<div><?php echo $item['content']; ?></div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>					
				<?php endif; ?>
			</div>			
			<div id="pit-tab-7">
				<form action="/category/addquestion" method="post" id="form-faq" class="b-user-write-form faq">
					<input type="hidden" name="id_catalog" value="<?php echo $collection['id']; ?>">
					<fieldset class="l-left">
						<div class="inner">
							<dl>
								<dt><label for="ff-name">Ваше Имя</label></dt>
								<dd><div class="w-uwf-input"><input type="text" id="ff-name" name="name" value="*" maxlength="60" class="required sred asterisk"></div></dd>
							</dl>
							<dl>
								<dt><label for="ff-email">Ваш Email</label></dt>
								<dd><div class="w-uwf-input"><input type="text" id="ff-email" name="email" value="*" maxlength="30" class="required sred asterisk"></div></dd>
							</dl>
							<dl>
								<dt><label for="ff-phone">Телефон</label></dt>
								<dd><div class="w-uwf-input"><input type="text" id="ff-telefon" name="telefon" value="" maxlength="60" class="onlydigit"></div></dd>
							</dl>
						</div>
						<p class="instructions">Поля отмеченные <b class="sred">*</b> обязательны для заполнения</p>
					</fieldset><fieldset class="l-right">
						<dl>
							<dt><label for="ff-question">Ваш вопрос</label></dt>
							<dd><div class="w-uwf-textarea"><textarea id="ff-question" name="question" maxlength="255"></textarea></div></dd>
						</dl>
						<ul class="buttons">
							<li><input type="submit" value="Отправить" class="btn uwf-send"></li>
						</ul>
					</fieldset>
				</form>					
				<?php if (@$question): ?>
				<div class="b-faq-list">
					<?php foreach($question as $item): ?>
					<dl class="question">
						<dt>Вопрос:</dt>
						<dd><?php echo $item['question']; ?></dd>
					</dl>
					<div class="answer">
						<dl>
							<dt>Ответ:</dt>
							<dd><?php echo $item['answer']; ?></dd>
						</dl>
						<i class="corner"></i>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</div><!-- /.tabs-content -->
		<div class="b-back-top">
			<a id="back-top" href="#pinfo-top">Вернуться к началу страницы</a>
		</div>		
	</div><!-- /.b-product-info-tabs -->
</div>