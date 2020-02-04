<div id="news-page" class="h-mb">
	<div class="list">
		<?php foreach($items as $item): ?>
		<dl>
			<dt>
				<a href="/sale/<?php echo $item['path']; ?>">
					<img src="<?php echo $imagepath.$item['image']; ?>" alt="<?php echo $item['name']; ?>">
				</a>
				<a href="/sale/<?php echo $item['path']; ?>"><?php echo $item['name']; ?></a>
			</dt>
			<dd>
				<p><?php echo $item['namefull']; ?></p>
				<div class="details">
					<span class="date"><?php echo $item['timestamp']; ?></span><a href="/sale/<?php echo $item['path']; ?>" class="btn-ndetails">Подробнее</a>
				</div>
			</dd>
		</dl>
		<?php endforeach; ?>
	</div>
	<div class="toolbar bottom">
		<span class="pagination"><?php echo @$pagination; ?></span>
	</div>
</div>