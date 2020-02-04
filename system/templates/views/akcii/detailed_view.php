<?php
  if ($item):
  UI::addCSS('/css/akcii/detailed.css');
?>
  <h2 id="akcii_header">
    <?php echo $item['name']; ?>
  </h2>
  <div id="akcii_content">
	<?php echo $item['short_description']; ?>
  </div>
  <div id="akcii_date">
    <?php echo $item['timestamp']; ?>
  </div>
  <a href="javascript:history.back();" class="btn btn-white" style="float: left;">Назад</a>
<?php else: ?>
  <div class="not_available">
    Нет новостей!
  </div>
<?php endif; ?>
