<p>&nbsp;</p>
<?php if (!empty($char_groupe)): ?>
<div id="cmp-features" class="b-compare-features">
	<table>
		<tbody class="features">
			<?php foreach ($char_groupe as $item): ?>
				<tr class="head">
					<td colspan="5"><?php echo $item['name']; ?></td>
				</tr>
				<?php foreach (Characteristics::getCollections($item['id']) as $char_item) : ?>
				<tr class="rb">
					<?php $i=0; $k=2; $class=""; ?>
					<td><div><span class="hide-row"></span><?php echo $char_item['name']; ?></div></td>
					<?php foreach (@$session_compare as $session_item) : 
					if($i++ > 3) $class='hidden'; else $class='c'.$k++;
					?>
					<td class="<?php echo $class; ?>">
						<?php echo getValueFromSql($session_item['id'],$char_item);	?>
					</td>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>		
		</tbody>
	<tbody class="buy">
		<tr class="rb">
			<?php $i=0; ?>
			<td>&nbsp;</td>
			<?php foreach (@$session_compare as $session_item) : ?>
			<td <?php if($i++ > 3) echo 'class="hidden"'; ?>>
				<a href="/category/<?php echo $session_item['path']; ?>" class="name"><?php echo $session_item['name']; ?></a>
			</td>
			<?php endforeach; ?>
		</tr>
		<tr class="rb">
			<?php $i=0; ?>
			<td>&nbsp;</td>
			<?php foreach (@$session_compare as $session_item) : ?>
			<td <?php if($i++ > 3) echo 'class="hidden"'; ?>>
				<span class="price"><?php echo transform_to_currency($session_item); ?></span>
			</td>
			<?php endforeach; ?>
		</tr>		
		<tr class="rb">
			<?php $i=0; ?>
			<td>&nbsp;</td>
			<?php foreach (@$session_compare as $session_item) : ?>
			<td <?php if($i++ > 3) echo 'class="hidden"'; ?>>
				<?php get_star_by_product($session_item); ?>
			</td>
			<?php endforeach; ?>
		</tr>
		<tr class="rb">
			<?php $i=0; ?>
			<td>&nbsp;</td>
			<?php foreach (@$session_compare as $session_item) : ?>
			<td <?php if($i++ > 3) echo 'class="hidden"'; ?>>
				<form class="form-product-buy" action="/cart/addtocart/" method="post">
					<input type="hidden" name="mid" value="<?php echo $session_item['id_image']; ?>">
					<input type="hidden" name="pid" value="<?php echo $session_item['id']; ?>">
					<button type="submit" class="btn cmpbuy"><span>Купить</span></button>
				</form>				
			</td>
			<?php endforeach; ?>
		</tr>
	</tbody>
	</table>
</div>
<?php endif; ?>