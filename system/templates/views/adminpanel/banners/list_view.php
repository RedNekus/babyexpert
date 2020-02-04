<script type="text/javascript" src="/js/admin/banners/grid_banners.js"></script>
<div>

	<div>
	
		<div id="dialog-del" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить баннер?</p>
		</div>
	
		<div id="dialog-edit">
			<form method="post" name="form" id="form" action="<?php echo get_url(2).'/edit'; ?>" target="hiddenframe" enctype="multipart/form-data">
				<div class="admin-tabs">
					<ul>
						<li><a href="#tabs-content-1">Общее</a></li>
						<li><a href="#tabs-content-2">Описание</a></li>				
					</ul>
	<!-- Вкладка общие !-->				
					<div id="tabs-content-1">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="id_lng" id="val_id_lng"/>
					<input type="hidden" name="action" id="action_pole" />						
					<table class="table-tabs-content">
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен:</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>								
							<tr>
								<td> Показывать в разделе:</td>	
								<td>
									<select name="id_categories" id="val_id_categories">
										<option value="0">-- Выберите --</option>
										<?php 
											foreach ($trees as $tree) {													
												echo '<option value="'.$tree['id'].'" class="o-strong">'.$tree['name'].'</option>';
												if (@$subcats = Tree::getTreesForSite("pid=".$tree['id'])) {
													foreach($subcats as $subcat) { 
														echo '<option value="'.$subcat['id'].'"> > '.$subcat['name'].'</option>';
													}
												}
											}
										?>
									</select>
								</td>
							</tr>							
							<tr>
								<td> Название:</td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>
							<tr>
								<td> Название: <img class="blr" src="/img/admin/icons/blr.png" alt=""/></td>				
								<td><input type="text" name="name_lng" id="val_name_lng" /></td>
							</tr>							
							<tr>
								<td> Картинка:</td>				
								<td>
									<input type="file" name="image" size="105" id="valueBanners_file"/>
									<i style="font-size: 10px;"><?php echo "min размер: ".$img_size['width']."x".$img_size['height']; ?></i>
								</td>
							</tr>							
							<tr>
								<td> Ссылка:</td>				
								<td><input type="text" name="path" id="val_path" /></td>
							</tr>							
							<tr>
								<td> Приоритет:</td>				
								<td><input type="text" name="prioritet" id="val_prioritet" /></td>
							</tr>	
							<tr>
								<td> &nbsp;</td>	
								<td> 
									<img id="loading" src="/img/admin/loading.gif"/>
									<div id="banners_insert"></div>
									<iframe id="hiddenframe" name="hiddenframe" style="width:0px; height:0px; border:0px"></iframe>	
								</td>
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
				</div>	
			</form>
		</div>


        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	
