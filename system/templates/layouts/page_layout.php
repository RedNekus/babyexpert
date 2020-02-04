<?php Pages::fetchContent(URL::getPath()); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <title><?php if (isset($title)) { echo @$title; } else { echo Pages::getTitle(); } ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="generator" content="Aver CMS" />
    <meta name="description" content="<?php  if (isset($description)) { echo @$description; } else { echo Pages::getDescription(); } ?>" />	
	<meta name="keywords" content="<?php if (isset($keywords)) { echo @$keywords; } else { echo Pages::getKeywords(); } ?>" />
    <meta name="robots" content="index, follow" />
    <link rel="stylesheet" type="text/css" href="/css/site.css" />
	<link rel="stylesheet" type="text/css" href="/css/menu.css" />
	<link rel="stylesheet" type="text/css" href="/css/catalog/basket.css" />
	<link rel="canonical" href="<?php echo get_url_canonical(); ?>">		
	<?php foreach(UI::getCSS() as $css): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $css; ?>" />
	<?php endforeach; ?>	
	<script type="text/javascript" src="/js/jquery.js" ></script> 	
	<!--<script type="text/javascript" src="/js/snow.js" ></script> --> 
<meta name="yandex-verification" content="34515885633bedad" />	
	
</head>

<body>
 
	<div id="container">
	
	<div class="wrapper">	
		
		<?php Render::view('cart/basket', '', TRUE); ?>
			
		<div id="header">
				
			<div class="logo">
				<a href="/"><img src="/img/logo.jpg" width="139" height="135" alt="BabyExpert.by"></a>
			</div>
			
			<?php Render::view('menu', '', TRUE); ?>
			
			<div class="contacts">
				<div class="phones">
					<a href="tel:+375296610166" class="telefon vel" title="+375(29)661-01-66"></a>
					<a href="tel:+375336610166" class="telefon mts" title="+375(33)661-01-66"></a>
					<a href="tel:+375296610166" class="telefon viber" title="+375(29)661-01-66"></a>
				</div>
				<div>
					<?php if(@$_SESSION['user']['manager']!=1): ?>
					<ul class="rezhim-work">
						<li>Режим работы
							<ul>
								<li>Понедельник-суббота <br/><strong>с 9.00 до 19.00</strong> <br/>Выходной воскресенье</li>
							</ul>
						</li>
					</ul>
					<?php endif; ?>
					<span>Детский магазин в Минске</span>
					
						<?php if(@$_SESSION['user']['manager']==1): ?>
						<ul class="currency">
							<?php $usd = "usd"; $byr = "byr"; 
							if (!isset($_SESSION['currency'])) $_SESSION['currency'] = $usd;
							?>
							<?php if (@$_SESSION['currency']==$byr): ?>
							<li>
								<i></i><a href="/home/setcurrency/?currency=<?php echo $byr; ?>" class="cur-item">BYR (бел.руб)</a>
								<ul>
									<li>
									<a href="/home/setcurrency/?currency=<?php echo $usd; ?>">USD (доллар)</a></li>					
								</ul>
							</li>	
							<?php endif; ?>
							<?php if (@$_SESSION['currency']==$usd): ?>							
							<li>
								<i></i><a href="/home/setcurrency/?currency=<?php echo $usd; ?>" class="cur-item">USD (доллар)</a>
								<ul>
									<li>
									<a href="/home/setcurrency/?currency=<?php echo $byr; ?>">BYR (бел.руб)</a></li>					
								</ul>
							</li>
							<?php endif; ?>
							
						</ul>
						<?php endif; ?>
				</div>
			</div>
		
		</div>			
		 
		<div id="content">
			
			<div class="l-content__sidebar">
				
				<?php echo @$left; ?>
				
				<?php if (URL::getSegment(2)!='compare')	Render::view('slidelinks','', TRUE); ?>
				
			</div>
		
			<div class="l-content__main">
			
				<?php Render::view('banners','', TRUE); ?>
				
				<div class="h-plr">
					
				<?php
							
					$chapters = URL::getSegment(1);  
								
					$nochapters = array ('home', 'category', 'novinki', 'specpredlozheniya', 'hity_prodazh', 'manufacturer', 'product', 'cart','wantproduct', 'wantdiscount');
								
					if ($chapters and URL::getPath() == $chapters and !in_array(URL::getPath(), $nochapters)) {
								
							echo "<h1 class='b-page-title'><span>".Pages::getName()."</span></h1>";
								
					}
																			
					if (isset($content)) {
										
						echo $content;
									
					} elseif (Pages::getContent()){
									  
						echo Pages::getContent();
									
					} else {
									  
						echo '&nbsp;';
									  
					}
							
				?> 		
					
				</div>
			
				<?php 
								
				$noarray = array ('cart', 'home', 'category', 'product', 'novinki', 'specpredlozheniya', 'hity_prodazh');
									
				if (!in_array(URL::getPath(), $noarray))
				
				Render::view('bottom', '', TRUE); 
				?>
				
			</div>

		<?php echo @$compare_table; ?>

		</div>
		
	</div>	
		
	<div id="footer">
				<ul class="nav">
						<li>
				<a href="/">Главная</a>
			</li>
								<li>
				<a href="/news">Новости</a>
			</li>
								<li>
				<a href="/shipping">Доставка</a>
			</li>
								<li>
				<a href="/warranty">Гарантия</a>
			</li>
								<li>
				<a href="/about-us">О магазине</a>
			</li>
								<li class="last">
				<a href="/contacts">Контакты</a>
			</li>
				</ul>
			<div class="inner">
				<div class="fs">
					<a href="http://www.faberstudio.by/" target="_blank">Faber Studio</a> - дизайн сайтов в Минске
				</div>
				<div class="copyright">
					&copy; 2012 Все права защищены BabyExpert.by</br>
					Индивидуальный предприниматель Тарасевич Егор Викторович
					Юр. адрес: 220078 г .Минск, ул. Семеняко 42-49
					УНП 190232099 выдано 24 июля 2019г. Минским горисполкомом
				</div>
				<div class="copy-data">
					Дата регистрации в Торговом реестре Республики Беларусь 25.04.2016
					<!--LiveInternet counter--><script type="text/javascript"><!--
					document.write("<img src='//counter.yadro.ru/hit?t25.12;r"+
					escape(document.referrer)+((typeof(screen)=="undefined")?"":
					";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
					screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
					";"+Math.random()+
					"' alt='' title='LiveInternet: показано число посетителей за"+
					" сегодня' "+
					"border='0' width='88' height='15' >")
					//--></script><!--/LiveInternet-->						
				</div>
			</div>
	
	</div>


</div>
<?php if ($_SERVER['HTTP_HOST']!='babyexpert.loc'): ?>
<script type='text/javascript'>
    window['l'+'ive'+'T'+'ex'] = true,
    window['live'+'T'+'ex'+'ID'] = 112546,
    window['liveT'+'ex_obj'+'ect'] = true;
    (function() {
        var t = document['cre'+'ate'+'Eleme'+'nt']('script');
        t.type ='text/javascript';
        t.async = true;
        t.src = '//cs'+'15.'+'liv'+'e'+'tex'+'.r'+'u/js/client'+'.js';
        var c = document['g'+'etElements'+'ByTagN'+'ame']('script')[0];
        if ( c ) c['paren'+'tN'+'ode']['in'+'s'+'e'+'rtBef'+'ore'](t, c);
        else document['do'+'cu'+'ment'+'Elemen'+'t']['first'+'C'+'hi'+'ld']['appe'+'n'+'dChi'+'ld'](t);
    })();
</script>
<!-- {/literal} -->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
var yaParams = {/*Здесь параметры визита*/};
</script>

<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter23350435 = new Ya.Metrika({id:23350435,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,params:window.yaParams||{ }});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/23350435" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70023582-1', 'auto');
  ga('send', 'pageview');

</script>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = '1HlKUKycJ7';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
<?php endif; ?>
	<script type="text/javascript" src="/js/jq-uniform.js" ></script> 	
	<script type="text/javascript" src="/js/main.js" ></script> 	
	<?php if (URL::getSegment(2)!='zakaz_manager'): ?>	
	<script type="text/javascript" src="/js/jquery-ui.js" ></script> 
	<script type="text/javascript" src="/js/jquery.ui.widget.js" ></script> 
	<script type="text/javascript" src="/js/jquery.ui.position.js" ></script> 
	<script type="text/javascript" src="/js/jquery.ui.tabs.js" ></script> 
	<script type="text/javascript" src="/js/jquery.ui.mouse.js" ></script> 
	<script type="text/javascript" src="/js/jquery.ui.slider.js" ></script> 
	<script type="text/javascript" src="/js/jplugins.js" ></script> 
	<script type="text/javascript" src="/js/social.js" ></script> 
	<script type="text/javascript" src="/js/main-ui.js" ></script> 
	<?php endif; ?>
	<script type="text/javascript" src="/js/cart.js" ></script> 	
	<script type="text/javascript" src="/js/fancybox2/jquery.fancybox.js" ></script> 	
	<script type="text/javascript" src="/js/fancybox2/helpers/jquery.fancybox-buttons.js" ></script> 
	
	<?php foreach(UI::getJS() as $js): ?>
		<script type="text/javascript" src="<?php echo $js; ?>" ></script>
	<?php endforeach; ?>
	
</body>

</html>