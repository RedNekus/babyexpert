<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru ">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $title; ?></title>
    <link type="text/css" rel="stylesheet" href="/css/admin/admin.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="/css/admin/content.css" />
    <script src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/admin/functions.js"></script>
    <script type="text/javascript" src="/js/admin/admin.js"></script>	
    <script type="text/javascript" src="/js/admin/print.js"></script>	
	
	<script type="text/javascript" src="/js/admin/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="/js/admin/tiny_mce/tiny_init.js"></script>
		
	<link rel="stylesheet" type="text/css" media="screen" href="/js/jqueryui/css/cupertino/jquery-ui-1.10.0.custom.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="/js/jqueryui/css/ui.jqgrid.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="/js/jqueryui/jquery.treeview.css" />

	<script src="/js/jqueryui/js/jquery-ui-1.10.0.custom.js" type="text/javascript"></script>
	<script src="/js/jqueryui/js/i18n/grid.locale-ru.js" type="text/javascript"></script>
	<script src="/js/jqueryui/js/jquery.jqgrid.min.js" type="text/javascript"></script>
	
		<script src="/js/jqueryui/src/grid.base.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.common.js" type="text/javascript"></script> 
		<script src="/js/jqueryui/src/grid.formedit.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.inlinedit.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.celledit.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.subgrid.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.treegrid.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.grouping.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.custom.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.postext.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.tbltogrid.js" type="text/javascript"></script> 
		<script src="/js/jqueryui/src/grid.setcolumns.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.import.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/jquery.fmatter.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/JsonXml.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/grid.jqueryui.js" type="text/javascript"></script>
		<script src="/js/jqueryui/src/jquery.searchFilter.js" type="text/javascript"></script>	

	<script src="/js/jqueryui/js/jquery.cookie.js"></script>
	<script src="/js/jqueryui/js/jquery.treeview.js" type="text/javascript"></script>	
  </head>
  <body>
    <div>
      <div id="wrapper">
        <div id="header" class="fieldset">
			<?php Render::view('adminpanel/menu', '', TRUE); ?>
        </div>
		<div>
            <?php echo $content; ?>
          <br class="clear" />
        </div>
      </div>
    </div>
  </body>
</html>