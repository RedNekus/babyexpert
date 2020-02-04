<?php
Load::model('Tree');
$razdels = Tree::getTreesForSite("pid=1 and active=1");
if ($razdels):
  UI::addCSS('/css/catalog/razdel.css');

?>
<div class="b-categories">
	<ul>
		<?php foreach($razdels as $razdel): ?>
			<li>
				<?php if ($razdel['path']!='glavnaya_stranica'): ?>
				<a href="/category/<?php echo $razdel['path']; ?>/"><?php echo $razdel['name']; ?></a>
					<?php if (@$subcats = Tree::getTreesForSite("pid=".$razdel['id']." and active=1")) : ?>
						<div class="subcats">
							<ul>
								<?php foreach($subcats as $subcat): ?>
								<li><a href="/category/<?php echo $subcat['path']; ?>/"><?php echo $subcat['name']; ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>