<?php

$config = array (
  'env' => Array (
    'timezone' => 'Europe/Minsk',
    'doc_encoding' => 'utf-8',
    'session_auto_start' => true,
    'cache' => false,
  ),
  'components' => Array (
    'log' => Array (
      'display_errors' => false,
      'email_notify' => Array (
        'enable' => false,
        'email' => '',
      ),
      'logging' => Array (
        'enable' => true,
        'log_file' => Array (
          'path' => 'system/errors.log',
          'max_size' => '1m',
        ),
      ),
    ),
    'db' => Array (
      'db_host' => 'localhost',
      'db_name' => 'babyexpert_newbd',
      'db_user' => 'babyexpert_user',
      'db_pass' => 'Hei1feib',
      'db_charset' => 'utf8',
    ),	
    'load' => Array (
      'dirs' => Array (
        'components' => 'system/components/',
        'controllers' => 'system/controllers/',
        'models' => 'system/models/',
        'library' => 'system/library/',
      ),
      'autoload' => Array (
        'library' => Array (
          0 => 'functions.php',
		  1 => 'Pagination.php',
		  2 => 'dictionary.php',
		  3 => 'video.php',		  
		  4 => 'math.php',		  
		  5 => 'garant.php',		  
		  6 => 'func_site.php',		  
        ),
        'components' => Array (
          0 => 'Config',
          1 => 'DB',
          2 => 'URL',
          3 => 'Render',
          4 => 'UI',
        ),
        'models' => Array (
		  0 => 'Pages',
		  1 => 'Registration',
		  2 => 'Banners',
		  3 => 'Banners_left',
		  4 => 'Banners_small',
		  5 => 'Tree',
		  6 => 'Maker',
		  7 => 'Adminusers',
		  8 => 'Adminaccess',
		  9 => 'Catalog_category',
		  10 => 'Currency_tree',
		  11 => 'Prefix',
		  12 => 'Database',
        ),
        'controller' => 'main',
      ),
    ),
    'url' => Array (
      'permitted_url_chars' => 'a-z0-9&~%?.:+=_\-/',
      'routes' => Array (
        '/^$/' => 'home',
        '/^adminpanel$/' => 'adminpanel/adminpanel',
        '/^main$/' => 'page_404',
        '/^page$/' => 'page_404',
        '/^cart\/(\d+)$/' => 'cart/default/$1',
        '/^cart\/addtocart$/' => 'cart/addtocart',
        '/^cart\/clearcart$/' => 'cart/clearcart',		
        '/^cart\/compare$/' => 'cart/compare',	
        '/^cart\/addtocompare$/' => 'cart/addtocompare',
        '/^cart\/clearcompare$/' => 'cart/clearcompare',	
        '/^category\/pred_search$/' => 'category/pred_search',
        '/^category\/search$/' => 'category/search',
        '/^category\/podbor$/' => 'category/podbor',
        '/^category\/addreview$/' => 'category/addreview',
		'/^category\/addquestion$/' => 'category/addquestion',		
        '/^category\/(\d+)$/' => 'category/default/$1',
        '/^category\/(.+)$/' => 'category/detailed/$1',	        
		'/^product\/(\d+)$/' => 'product/default/$1',
        '/^product\/(.+)$/' => 'product/detailed/$1',
        '/^manufacturer\/(\d+)$/' => 'manufacturer/default/$1',
        '/^manufacturer\/(.+)$/' => 'manufacturer/detailed/$1',			
		'/^sale\/(\d+)$/' => 'sale/default/$1',
        '/^sale\/(.+)$/' => 'sale/detailed/$1',			
		'/^raffle\/(\d+)$/' => 'raffle/default/$1',
        '/^raffle\/(.+)$/' => 'raffle/detailed/$1',			
      ),
    ),
    'render' => Array (
      'dirs' => Array (
        'letters' => 'system/templates/letters/',
        'views' => 'system/templates/views/',
        'layouts' => 'system/templates/layouts/',
      ),
    ),
  ),
  'modules' => Array (
    'catalog' => Array (
      'table' => 'np_catalog',
	  'table_lng' => 'np_catalog_language',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/catalog/small/',
          'width' => 365,
          'height' => 301,
        ),
        'big' => Array (
          'path' => '/assets/images/catalog/big/',
          'width' => 2800,
        ),
      ),
      'pagination' => Array (
        'rows_on_page' => 27,
        'link_by_side' => 2,
        'url_segment' => 3,
        'base_url' => '/catalog/',
      ),
	  'zakaz' => Array (
		'title' => 'babyexpert',
		'letter' => 'zakaz',
	  ),	  
    ),			
    'wantdiscount' => Array (
	  'zakaz' => Array (
		'title' => 'babyexpert',
		'letter' => 'wantdiscount',
	  ),	  
    ),    
	'wantproduct' => Array (
	  'zakaz' => Array (
		'title' => 'babyexpert',
		'letter' => 'wantproduct',
	  ),	  
    ),	
	'callback' => Array (
	  'zakaz' => Array (
		'title' => 'babyexpert',
		'letter' => 'callback',
	  ),	  
    ),	
	'catalog_razmer' => Array (
      'table' => 'np_catalog_razmer',
    ),	
	'razmer' => Array (
      'table' => 'np_razmer',
    ),
	'description' => Array (
      'table' => 'np_description',
    ),		
	'couriers' => Array (
      'table' => 'np_couriers',
    ),			
    'connection' => Array (
      'table' => 'np_connection',
    ),    
	'connection_tree' => Array (
      'table' => 'np_connection_tree',
    ),	
    'characteristics_tree' => Array (
      'table' => 'np_characteristics_tree',
    ),	
    'characteristics' => Array (
      'table' => 'np_characteristics',
      'table_lng' => 'np_characteristics_language',
    ),
    'characteristics_group' => Array (
      'table' => 'np_characteristics_group',
      'table_lng' => 'np_characteristics_group_language',
    ),	
    'characteristics_group_tip' => Array (
      'table' => 'np_characteristics_group_tip',
    ),
    'catalog_tree' => Array (
      'table' => 'np_catalog_tree',
      'table_lng' => 'np_catalog_tree_language',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/catalog_tree/small/',
          'width' => 161,
          'height' => 193,
        ),
        'big' => Array (
          'path' => '/assets/images/catalog_tree/big/',
          'width' => 800,
        ),
      ),	  
    ),	
    'catalog_categories' => Array (
      'table' => 'np_catalog_categories',
    ),		
    'catalog_3d' => Array (
      'table' => 'np_catalog_3d',
	  'swf' => Array (
	    'path' => 'assets/swf/',
	  ),
    ),	
    'product_week' => Array (
      'table' => 'np_product_week',
    ),	
    'images' => Array (
      'table' => 'np_catalog_images',
    ),
    'images_language' => Array (
      'table' => 'np_catalog_images_language',
    ),	
    'catalog_complect' => Array (
      'table' => 'np_catalog_complect',
    ),	
    'pages' => Array (
      'table' => 'np_pages',
      'table_lng' => 'np_pages_language',
    ),    
	'promocode' => Array (
      'table' => 'np_promocode',
    ),    
	'currency' => Array (
      'table' => 'np_currency',
    ),	
	'currency_tree' => Array (
      'table' => 'np_currency_tree',
    ),
    'brand' => Array (
      'table' => 'np_brand',	  
      'table_lng' => 'np_brand_language',	  
    ),
    'news' => Array (
      'table' => 'np_news',
      'table_lng' => 'np_news_language',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/news/small/',
          'width' => 402,
          'height' => 153,
        ),
        'big' => Array (
          'path' => '/assets/images/news/big/',
          'width' => 800,
        ),
      ),
      'pagination' => Array (
        'rows_on_page' => 10,
        'link_by_side' => 3,
        'url_segment' => 3,
        'base_url' => '/news/',
        'admin' => Array (
          'url_segment' => 4,
          'base_url' => '/admin/akcii/list/',
        ),
      ),
    ),	
	'kontragenty' => Array (
      'table' => 'np_kontragenty',
    ),	        
	'kontragenty_tip' => Array (
      'table' => 'np_kontragenty_tip',
    ),		
	'fuel' => Array (
      'table' => 'np_fuel',
    ),
	'fuel_tree' => Array (
      'table' => 'np_fuel_tree',
    ),
	'tip_operation' => Array (
      'table' => 'np_tip_operation',
    ),
	'competitors' => Array (
      'table' => 'np_competitors',
    ),	
	'price_monitoring' => Array (
      'table' => 'np_price_monitoring',
    ),	
	'price_competitors' => Array (
      'table' => 'np_price_competitors',
    ),	     
	'valute' => Array (
      'table' => 'np_valute',
    ),	    
	'sklad' => Array (
      'table' => 'np_sklad',
    ),	    
	'zpmanager' => Array (
      'table' => 'np_zpmanager',
    ),	    
	'sklad_tovar' => Array (
      'table' => 'np_sklad_tovar',
    ),		    
	'delivery_tmc' => Array (
      'table' => 'np_delivery_tmc',
    ),		    
	'return_tmc' => Array (
      'table' => 'np_return_tmc',
    ),		    
	'kassa' => Array (
      'table' => 'np_kassa',
    ),			    
	'kassa_tree' => Array (
      'table' => 'np_kassa_tree',
    ),	
    'akcii' => Array (
      'table' => 'np_akcii',
      'table_lng' => 'np_akcii_language',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/akcii/small/',
          'width' => 402,
          'height' => 153,
        ),
        'big' => Array (
          'path' => '/assets/images/akcii/big/',
          'width' => 800,
        ),
      ),
      'pagination' => Array (
        'rows_on_page' => 10,
        'link_by_side' => 3,
        'url_segment' => 3,
        'base_url' => '/akcii/',
        'admin' => Array (
          'url_segment' => 4,
          'base_url' => '/admin/akcii/list/',
        ),
      ),
    ),
    'raffle' => Array (
      'table' => 'np_raffle',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/raffle/small/',
          'width' => 270,
          'height' => 300,
        ),
        'big' => Array (
          'path' => '/assets/images/raffle/big/',
          'width' => 800,
        ),
      ),
      'pagination' => Array (
        'rows_on_page' => 10,
        'link_by_side' => 3,
        'base_url' => '/raffle/',
      ),
    ),    
	'adminaccess' => Array (
      'table' => 'np_adminaccess',
    ),
	'adminusers' => Array (
      'table' => 'np_adminusers',
    ),	
	'adminusers_stats' => Array (
      'table' => 'np_adminusers_stats',
    ),	
    'banners' => Array (
      'table' => 'np_banners',
      'table_lng' => 'np_banners_language',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/banners/small/',
          'width' => 729,
          'height' => 251,
        ),
        'big' => Array (
          'path' => '/assets/images/banners/big/',
          'width' => 729,
        ),
      ),
    ),		
    'banners_left' => Array (
      'table' => 'np_banners_left',
      'image' => Array (
          'path' => '/assets/images/banners_left/',
          'width' => 218,
          'height' => 317,
      ),
    ),    
	'banners_small' => Array (
      'table' => 'np_banners_small',
      'image' => Array (
          'path' => '/assets/images/banners_small/',
          'width' => 218,
          'height' => 137,
      ),
    ),	
    'article' => Array (
      'table' => 'np_article',
      'table_lng' => 'np_article_language',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/article/small/',
          'width' => 327,
          'height' => 229,
        ),
        'big' => Array (
          'path' => '/assets/images/article/big/',
          'width' => 800,
        ),
      ),
      'pagination' => Array (
        'rows_on_page' => 10,
        'link_by_side' => 3,
        'url_segment' => 3,
        'base_url' => '/poleznoe',
        'admin' => Array (
          'url_segment' => 4,
          'base_url' => '/admin/article/list/',
        ),
      ),
    ),		
    'catalog_characteristics' => Array (
      'table' => 'np_catalog_characteristics',
    ),
    'maker' => Array (
      'table' => 'np_maker',
      'table_lng' => 'np_maker_language',	  
    ),
    'manufacturer' => Array (
      'table' => 'np_manufacturer',  
    ),
    'importer' => Array (
      'table' => 'np_importer',  
    ),	
    'registration' => Array (
      'table' => 'np_registration',
      'cookie' => Array (
        'expire' => 31536000,
      ),	  
    ),    
	'zakaz' => Array (
      'table' => 'np_zakaz',	  
    ), 	
	'zakaz_client' => Array (
      'table' => 'np_zakaz_client',	  
    ),    	
    'reviews' => Array (
      'table' => 'np_catalog_reviews',
    ),
    'question' => Array (
      'table' => 'np_catalog_question',
	  'letter' => 'question',
    ),	
    'prefix' => Array (
      'table' => 'np_catalog_tree_prefix',
    ),    
	'friend_send' => Array (
      'table' => 'np_friend_send',
    ),   
	'logs' => Array (
      'table' => 'np_logs',
    ),
	'log_sell' => Array (
      'table' => 'np_log_sell',
    ),   
	'log_currency_tree' => Array (
      'table' => 'np_log_currency_tree',
    ),	
	'spros' => Array (
      'table' => 'np_spros',
    ),	   
	'managers' => Array (
      'table' => 'np_managers',
    ),	
  ),
);