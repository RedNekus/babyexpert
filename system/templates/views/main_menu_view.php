<?php
Load::model('Tree');
$razdels = Tree::getTreesForSite("pid=1 and active=1");
?>
<ul class="navbar-nav" id="catalog-menu">
	<?php foreach($razdels as $razdel): ?>
		<li class="nav-item">
			<?php if ($razdel['path']!='glavnaya_stranica'): ?>
				<a class="nav-link" href="/category/<?php echo $razdel['path']; ?>/" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $razdel['name']; ?></a>
				<?php if (@$subcats = Tree::getTreesForSite("pid=".$razdel['id']." and active=1")) : ?>
					<div class="submenu dropdown-menu">
						<div class="container-xl">
							<div class="row flex-wrap">
								<div class="col-12 text-right"><span class="close">&#xe807;</span></div>
								<div class="col-6 col-lg-3">
									<?php foreach($subcats as $subcat): ?>
									<a  class="dropdown-item" href="/category/<?php echo $subcat['path']; ?>/"><?php echo $subcat['name']; ?></a>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>