<script type="text/javascript" src="/js/admin/advantages/grid_advantages.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="path"]');
  })
</script>
<div>

	<div>
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить новость?</p>
		</div>
	
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Общее</a></li>
						<li><a href="#tabs-content-2">Описание</a></li>
						<li><a href="#tabs-content-3">Meta</a></li>					
					</ul>
	<!-- Вкладка общие !-->				
					<div id="tabs-content-1">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="id_lng" id="val_id_lng"/>
					<input type="hidden" name="action" id="action_pole" />	
					<input type="hidden" name="timestamp" id="val_timestamp" value="<?php echo time();?>" />					
					<table class="table-tabs-content">
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен:</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>							
							<tr>
								<td> Краткое название:</td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>
							<tr>
								<td> Краткое название: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>				
								<td><input type="text" name="name_lng" id="val_name_lng" /></td>
							</tr>							
							<tr>
								<td> Полное название:</td>				
								<td><input type="text" name="namefull" id="val_namefull" /></td>
							</tr>	
							<tr>
								<td> Полное название: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>				
								<td><input type="text" name="namefull_lng" id="val_namefull_lng" /></td>
							</tr>							
							<tr>
								<td> Путь:</td>				
								<td><input type="text" name="path" id="val_path" /></td>
							</tr>							
							<tr>
								<td> Приоритет:</td>				
								<td><input type="text" name="prioritet" id="val_prioritet" /></td>
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

        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	