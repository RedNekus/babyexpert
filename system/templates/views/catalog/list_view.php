<?php 

	UI::addCSS(array(
		'/css/catalog/list.css'
	));

?>
<div class="h-mb">
	<?php echo list_product_html($collections,$imagepath,$getLimitas,$pagination,$rows_on_page,$anchor,@$razdel0,$h1item,$totals); ?>
</div>	

<?php if (@$useful_info and (!isset($_GET['page']) or $_GET['page']==1)) : ?>	
	<div class="useful-info">
		<div class="b-page-title"><span>Полезная информация</span></div>
			<div class="content"><?php echo @$useful_info; ?></div>
	</div>
<?php endif; ?>	