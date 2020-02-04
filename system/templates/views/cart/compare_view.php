<div id="cmp-carousel" class="b-compare-products">
	<span class="prev"></span>
		<ul>
			<?php foreach ($session_compare as $key => $item) : ?>
			<li class="item">
				<ul>
					<li class="image">
						<a href="/product/<?php echo $item['path']; ?>/" class="img">
							<img src="<?php echo $imagepath['small']['path'].insert_image($item['id']); ?>" alt="<?php echo $item['name']; ?>">
						</a>
						<a href="#" class="rm btn-remove-wish" data-id-char="<?php echo $id_char; ?>" rel="<?php echo $key; ?>"></a>
					</li>
					<li class="name">
						<a href="/product/<?php echo $item['path']; ?>/"><?php echo $item['name']; ?></a>
					</li>
					<li class="price"><?php echo transform_to_currency($item); ?></li>
					<li><?php get_star_by_product($item); ?></li>
					<li>
						<form class="form-product-buy" action="/cart/addtocart/" method="post">
							<input type="hidden" name="mid" value="<?php echo $item['id_image']; ?>">
							<input type="hidden" name="pid" value="<?php echo $item['id']; ?>">
							<button type="submit" class="btn cmpbuy"><span>Купить</span></button>
						</form>
					</li>
				</ul>
			</li>
			<?php endforeach; ?>
		</ul>
	<span class="next"></span>
</div>