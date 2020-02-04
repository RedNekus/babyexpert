<?php
Load::model(array('Tree','Characteristics_group','Maker'));

UI::addCSS(array(
	'/css/catalog/left_filtr.css',
	'/css/catalog/razdel.css'
	));

if (URL::getSegment(1) == 'product') {
	$collection = Catalog::getCollectionByUrl(URL::getSegment(3));
	if (!empty($collection)) $tree = Tree::getTreeByChar($collection['id_char']);
} else {
	$tree = Tree::getTreeByPath(URL::getSegment(3));
}
$razdels = Tree::getTreesForSite("pid=1 and active=1");

?>
<div id="category-tabs" class="">
	<ul class="category-tabs-header">
		<li><a href="#category-tab-1">Расширенный поиск</a></li>	
		<li><a href="#category-tab-2">Показать разделы</a></li>
	</ul>
	
	<div id="category-tab-1">
		<div id="filtr_content">
			<?Php if (isset($tree['id'])): ?>
			<form action="/category/podbor" method="GET" rel="<?php echo $tree['path']; ?>" data-path2="<?php echo URL::getSegment(4);?>" id="filtr-form">
				<a href="/category/<?php echo $tree['path']; ?>/" class="podbor_clear">Очистить фильтр</a>				
				<table class="filtr_catalog">
					<?php echo filtr_create($tree['characteristic'],$tree['id']); ?>
				</table>
				<a href="/category/<?php echo $tree['path']; ?>/" class="podbor_clear">Очистить фильтр</a>
			</form>
			<?php endif; ?>
		</div>
	</div>

	<div id="category-tab-2">
		<div class="b-categories">
			<ul>
				<?php foreach($razdels as $razdel): ?>
					<?php if ($razdel['path']!='glavnaya_stranica'): ?>				
					<li>
						<a href="/category/<?php echo $razdel['path']; ?>/"><?php echo $razdel['name']; ?></a>
							<?php if (@$subcats = Tree::getTreesForSite("pid=".$razdel['id'])) : ?>
								<div class="subcats">
									<ul>
										<?php foreach($subcats as $subcat): ?>
										<li><a href="/category/<?php echo $subcat['path']; ?>/"><?php echo $subcat['name']; ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>	
</div>