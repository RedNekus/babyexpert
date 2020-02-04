<script type="text/javascript" src="/js/admin/brand/grid_brand.js"></script>
<script type="text/javascript" src="/js/admin/brand/grid_tree.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="path"]');
  })
</script>
<div>

	<div id="left">
	
		<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
			<div style="height: 24px;" class="ui-userdata ui-state-default"></div>
			<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"></div>
			<div class="ui-jqgrid-bdiv">
			
				<ul id="tree_brand" class="filetree">
					<?php echo $trees; ?>
				</ul>
				
			</div>
				
			<div class="ui-state-default ui-jqgrid-pager corner-bottom" style="width: 100%;" dir="ltr"></div>
		</div>
	
	</div>
	
	<div id="right">
        <table id="TableCatalog"></table>
        <div id="TableCatalogPager"></div>
		
		<div id="brandFormDel" class="del-form">
			<input type="hidden" id="del_id_brand"/>
			<p>Вы действительно хотите удалить элемент?</p>
		</div>
	
		<div id="brandForm">
		
			<form method="post" name="brand_form" id="brand_form" action="/adminpanel/brand/edit" target="HiddenBrandFrame" enctype="multipart/form-data">				
			
				<div class="admin-tabs">

					<ul>
						<li><a href="#tabs-content-1">Общее</a></li>
						<li><a href="#tabs-content-2">Описание</a></li>
						<li><a href="#tabs-content-3">Meta</a></li>					
					</ul>
					
					<div id="tabs-content-1">
						<div style="display: none;">
						<input type="text" name="id" id="val_id"  />
						<input type="text" name="id_lng" id="val_id_lng" />
						<input type="text" name="action_group" id="action_pole" />
						<input type="text" name="id_catalog_tree" id="val_id_catalog_tree" />	
						</div>						
						<table class="table-tabs-content">
							<tbody>	
								<tr>
									<td> Производитель:</td>	
									<td id="val_id_maker"></td>
								</tr>							
								<tr>
									<td class="td-name-width"> Название h1:</td>				
									<td><input type="text" name="name" id="val_name" /></td>
								</tr>	
								<tr>
									<td> Название h1: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>				
									<td><input type="text" name="name_lng" id="val_name_lng" /></td>
								</tr>																															
							</tbody>
						</table>	
					</div>	
	<!-- Вкладка описание !-->
					<div id="tabs-content-2" class="without-indent">
						<div class="admin-tabs">
							<ul>
								<li><a href="#tabs-content-2-lng1">Русский</a></li>
								<li><a href="#tabs-content-2-lng2">Белорусский</a></li>					
							</ul>
							<div id="tabs-content-2-lng1">
								<table>
									<tr>
										<td><label class="opisanie-label">Описание:</label></td>
									</tr>
									<tr>
										<td><textarea name="short_description" class="tinymce" cols="50" rows="8" id="val_short_description"></textarea></td>
									</tr>
								</table>
							</div>							
							<div id="tabs-content-2-lng2">
								<table>
									<tr>
										<td><label class="opisanie-label">Описание:</label></td>
									</tr>
									<tr>
										<td><textarea name="short_description_lng" class="tinymce" cols="50" rows="8" id="val_short_description_lng"></textarea></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
	<!-- Вкладка МЕТА !-->						
					<div id="tabs-content-3" class="without-indent">	
						<div class="admin-tabs">
							<ul>
								<li><a href="#tabs-content-3-lng1">Русский</a></li>
								<li><a href="#tabs-content-3-lng2">Белорусский</a></li>					
							</ul>	
							<div id="tabs-content-3-lng1">
								<table class="table-tabs-content">
									<tbody>					
										<tr>
											<td class="td-name-width"> <label>Title:</label> </td>				
											<td><input type="text" name="title" id="val_title" /></td>
										</tr>
										<tr>
											<td class="td-name-align"> <label>Meta Keywords:</label> </td>				
											<td><textarea name="keywords" id="val_keywords"></textarea></td>
										</tr>
										<tr>
											<td class="td-name-align"> <label>Meta Description:</label> </td>				
											<td><textarea name="description" id="val_description"></textarea></td>
										</tr>						
									</tbody>
								</table>
							</div>	
							<div id="tabs-content-3-lng2">
								<table class="table-tabs-content">
									<tbody>					
										<tr>
											<td class="td-name-width"> <label>Title:</label> </td>				
											<td><input type="text" name="title_lng" id="val_title_lng" /></td>
										</tr>
										<tr>
											<td class="td-name-align"> <label>Meta Keywords:</label> </td>				
											<td><textarea name="keywords_lng" id="val_keywords_lng"></textarea></td>
										</tr>
										<tr>
											<td class="td-name-align"> <label>Meta Description:</label> </td>				
											<td><textarea name="description_lng" id="val_description_lng"></textarea></td>
										</tr>						
									</tbody>
								</table>
							</div>	
						</div>
					</div>
				</div>	
			</form>	
		</div>

		
	</div>
	
</div>	