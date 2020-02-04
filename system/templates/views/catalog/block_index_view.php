<?php 

  UI::addCSS(array(
	
    '/css/catalog/list.css'
	
  ));

  $active = "";
  $url_path = URL::getSegment(1);
  Pages::fetchContent(URL::getPath());
  
?>
<div class="b-home-tabs h-mb">
	<ul class="tabs-panel">
		<li class="new <?php if ($url_path == "novinki") echo 'ui-tabs-selected'; ?>"><a href="/novinki/" >Новинки</a></li>
		<li class="best <?php if ($url_path == "hity_prodazh") echo 'ui-tabs-selected'; ?>"><a href="/hity_prodazh/" >Хиты продаж</a></li>
		<li class="spec <?php if ($url_path == "specpredlozheniya") echo 'ui-tabs-selected'; ?>"><a href="/specpredlozheniya/" >Спецпредложения</a></li>
		<li class="tovarnedeli <?php if ($url_path == "tovary_nedeli") echo 'ui-tabs-selected'; ?>"><a href="/tovary_nedeli/" >Товары недели</a></li>
		<li class="expert <?php if ($url_path == "vybor_eksperta") echo 'ui-tabs-selected'; ?>"><a href="/vybor_eksperta/" >Выбор эксперта</a></li>
	</ul>
	<div id="home-tab-1">
		<div class="b-products">
			<ul class="grid">
				<?php foreach ($items as $item) : ?>
				<li>
					<div class="pict">
						<a href="/product/<?php echo $item['path']; ?>/" class="name"><?php echo get_product_name($item); ?></a>
						<a href="/product/<?php echo $item['path']; ?>/" class="image">
						<img src="<?php echo $imagepath['small']['path'].insert_image($item['id']); ?>" alt="<?php echo $item['name']; ?>">
						</a>
						<div class="buttons">
							<a href="/product/<?php echo $item['path']; ?>/#pit-tab-3" class="kol-colors"><?php get_count_colors($item['id']); ?></a>
							<a href="#" class="status r<?php echo $item['status']; ?>"><?php get_status($item['status']); ?></a>
							<a href="/product/<?php echo $item['path']; ?>/" class="btn-details">Подробнее</a>
							<?php 
							echo get_buttons_catalog($item['id']);  
							if ($item['status'] != 4) {
								if ($item['cena_old']>0 or $item['cena_blr_old']>0) {
									echo '<span class="price price-old">
										<div class="cena-old">'.transform_to_currency_old($item,true,true).'</div>
										Цена: '.transform_to_currency($item).'
									</span>';
								} else {
									echo '<span class="price">Цена: '.transform_to_currency($item).'</span>';
								}
							} else {
								echo '<a href="#" class="status nvn">Снят с производства</a>';
							}
							?>
						</div>
						<?php if ($item['new']==1): ?><i class="icon new"></i><?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>			    
		</div>


        <!-- Cloud -->
        <div class="tag-cloud block cloud-kresla">
            <div class="b-page-title">
            <span>Популярные бренды автокресел</span></div>
            <ul>
                <li>
                    <a href="/category/avtokresla/baby-design/">Baby Design</a>|
                    <a href="/category/avtokresla/cybex/">Cybex</a>|
                    <a href="/category/avtokresla/espiro/">Espiro</a>|
                    <a href="/category/avtokresla/heyner/">Heyner</a>|
                    <a href="/category/avtokresla/maxi-cosi/">Maxi Cosi</a>|
                    <a href="/category/avtokresla/recaro/">Recaro</a>|
                    <a href="/category/avtokresla/stm/">STM (Recaro)</a>|
                    <a href="/category/avtokresla/baby-care/">Baby Care</a>|
                    <a href="/category/avtokresla/bertoni/">Bertoni (Lorelli)</a>|
                    <a href="/category/avtokresla/Brevi/">Brevi</a>|
                    <a href="/category/avtokresla/Cam/">Cam</a>|
                    <a href="/category/avtokresla/Caretero/">Caretero</a>|
                    <a href="/category/avtokresla/Chicco/">Chicco</a>|
                    <a href="/category/avtokresla/Graco/">Graco</a>|
                    <a href="/category/avtokresla/Happy-Baby/">Happy Baby</a>|
                    <a href="/category/avtokresla/Inglesina/">Inglesina</a>|
                    <a href="/category/avtokresla/Peg-Perego/">Peg-Perego</a>|
                    <a href="/category/avtokresla/Britax-Romer-/">Britax Romer</a>|
                    <a href="/category/avtokresla/KinderKraft/">KinderKraft</a>
                </li>
            </ul>
        </div>
        <div class="tag-cloud block cloud-kalyaska">
            <div class="b-page-title">
                <span>Популярные бренды колясок</span></div>
            <ul>
                <li>
                    <a href="/category/kolyaski/4baby/">4Baby</a>|
                    <a href="/category/kolyaski/adamex/">Adamex</a>|
                    <a href="/category/kolyaski/adbor/">Adbor</a>|
                    <a href="/category/kolyaski/Aneco/">Aneco</a>|
                    <a href="/category/kolyaski/Anmar/">Anmar</a>|
                    <a href="/category/kolyaski/Aprica/">Aprica</a>|
                    <a href="/category/kolyaski/Baby-Care/">Baby Care</a>|
                    <a href="/category/kolyaski/Baby-Design/">Baby Design</a>|
                    <a href="/category/kolyaski/BabyPoint/">BabyPoint</a>|
                    <a href="/category/kolyaski/Bebetto/">Bebetto</a>|
                    <a href="/category/kolyaski/bertoni/">Bertoni (Lorelli)</a>|
                    <a href="/category/kolyaski/Brevi/">Brevi</a>|
                    <a href="/category/kolyaski/Cam/">Cam</a>|
                    <a href="/category/kolyaski/Camarelo/">Camarelo</a>|
                    <a href="/category/kolyaski/Capella/">Capella</a>|
                    <a href="/category/kolyaski/Chicco/">Chicco</a>|
                    <a href="/category/kolyaski/Cozy/">Cozy</a>|
                    <a href="/category/kolyaski/Cybex/">Cybex</a>|
                    <a href="/category/kolyaski/EasyGo/">EasyGo</a>|
                    <a href="/category/kolyaski/Espiro/">Espiro</a>|
                    <a href="/category/kolyaski/FD-Design/">FD Design (ABC Design)</a>|
                    <a href="/category/kolyaski/Geoby/">Geoby</a>|
                    <a href="/category/kolyaski/Graco/">Graco</a>|
                    <a href="/category/kolyaski/Happy-Baby/">Happy Baby</a>|
                    <a href="/category/kolyaski/Inglesina/">Inglesina</a>|
                    <a href="/category/kolyaski/Jetem/">Jetem</a>|
                    <a href="/category/kolyaski/Lonex/">Lonex</a>|
                    <a href="/category/kolyaski/Maclaren/">Maclaren</a>|
                    <a href="/category/kolyaski/Marimex/">Marimex</a>|
                    <a href="/category/kolyaski/Peg-Perego/">Peg-Perego</a>|
                    <a href="/category/kolyaski/Recaro/">Recaro</a>|
                    <a href="/category/kolyaski/Riko/">Riko</a>|
                    <a href="/category/kolyaski/Roan/">Roan</a>|
                    <a href="/category/kolyaski/Quatro/">Quatro</a>|
                    <a href="/category/kolyaski/Tako/">Tako</a>|
                    <a href="/category/kolyaski/Deltim/">Deltim</a>|
                    <a href="/category/kolyaski/Hoco/">Hoco</a>|
                    <a href="/category/kolyaski/NeoNato/">NeoNato</a>|
                    <a href="/category/kolyaski/Silver-Cross/">Silver Cross</a>
                </li>
            </ul>
        </div>
        <div class="tag-cloud block cloud-stulchik">
            <div class="b-page-title">
                <span>Популярные бренды детских стульчиков</span></div>
            <ul>
                <li>
                    <a href="/category/stulchiki/Amalfy/">Amalfy</a>|
                    <a href="/category/stulchiki/Baby-Care/">Baby Care</a>|
                    <a href="/category/stulchiki/Baby-Design/">Baby Design</a>|
                    <a href="/category/stulchiki/BabyPoint/">BabyPoint</a>|
                    <a href="/category/stulchiki/Bertoni/">Bertoni (Lorelli)</a>|
                    <a href="/category/stulchiki/Cam/">Cam</a>|
                    <a href="/category/stulchiki/Caretero/">Caretero</a>|
                    <a href="/category/stulchiki/Chicco/">Chicco</a>|
                    <a href="/category/stulchiki/Espiro/">Espiro</a>|
                    <a href="/category/stulchiki/Globex/">Globex</a>|
                    <a href="/category/stulchiki/Graco/">Graco</a>|
                    <a href="/category/stulchiki/Inglesina/">Inglesina</a>|
                    <a href="/category/stulchiki/Jetem/">Jetem</a>|
                    <a href="/category/stulchiki/Kinderwood/">Kinderwood</a>|
                    <a href="/category/stulchiki/Pali/">Pali</a>|
                    <a href="/category/stulchiki/Peg-Perego/">Peg-Perego</a>|
                    <a href="/category/stulchiki/S-line/">S-line</a>|
                    <a href="/category/stulchiki/Shenma/">Shenma</a>|
                    <a href="/category/stulchiki/vedruss/">Ведрусс</a>|
                    <a href="/category/stulchiki/skv-kompani/">СКВ-компани</a>
                </li>
            </ul>
        </div>
        <!-- Cloud -->



<!--		--><?php //echo get_clouds($makers); ?>

		<div class="useful-info">
			<div class="b-page-title"><span>Полезная информация</span></div>
				<div class="content">
					<?php echo Pages::getContent(); ?>
				</div>
		</div>			
	</div>
</div>
