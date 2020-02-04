<script type="text/javascript" src="/js/admin/raffle/grid_raffle.js"></script>
<script type="text/javascript" src="/js/admin/raffle/grid_tovar.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="path"]');
  })
</script>
<div>

	<div>
	
		<div id="deldialog" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить розыгрыш?</p>
		</div>
	
		<div id="catalogForm">
			<form method="post" name="catalog_form" id="catalog_form" action="/adminpanel/raffle/edit" target="hiddenraffleframe" enctype="multipart/form-data">
				<div id="tabs-raffle">
					<ul>
						<li><a href="#tabs-raffle-1">Общее</a></li>
						<li><a href="#tabs-raffle-2">Описание</a></li>
						<li><a href="#tabs-raffle-3">Meta</a></li>					
						<li><a href="#tabs-raffle-4">Список участников</a></li>										
						<li><a href="#tabs-raffle-5">Товары</a></li>					
					</ul>
	<!-- Вкладка общие !-->				
					<div id="tabs-raffle-1">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="action" id="action_pole" />						
					<table class="table-tabs-content">
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен:</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>															
							<tr>
								<td> Название:</td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>															
							<tr>
								<td> URL видео:</td>				
								<td><input type="text" name="video_url" id="val_video_url" /></td>
							</tr>							
							<tr>
								<td> Путь:</td>				
								<td><input type="text" name="path" id="val_path" /></td>
							</tr>							
							<tr>
								<td> Приоритет:</td>				
								<td><input type="text" name="prioritet" id="val_prioritet" /></td>
							</tr>
							<tr>
								<td> Дата начала 1:</td>
								<td><input type="text" name="timestamp" id="val_timestamp" value=""/></td>
							</tr>
							<tr>
								<td> Дата окончания 1:</td>
								<td><input type="text" name="timestampend" id="val_timestampend" value=""/></td>
							</tr>							
							<tr>
								<td> Дата начала 2:</td>
								<td><input type="text" name="timestamp2" id="val_timestamp2" value=""/></td>
							</tr>
							<tr>
								<td> Дата окончания 2:</td>
								<td><input type="text" name="timestampend2" id="val_timestampend2" value=""/></td>
							</tr>							
							<tr>
								<td> Дата начала 3:</td>
								<td><input type="text" name="timestamp3" id="val_timestamp3" value=""/></td>
							</tr>
							<tr>
								<td> Дата окончания 3:</td>
								<td><input type="text" name="timestampend3" id="val_timestampend3" value=""/></td>
							</tr>							
							<tr>
								<td> Дата начала 4:</td>
								<td><input type="text" name="timestamp4" id="val_timestamp4" value=""/></td>
							</tr>
							<tr>
								<td> Дата окончания 4:</td>
								<td><input type="text" name="timestampend4" id="val_timestampend4" value=""/></td>
							</tr>							
							<tr>
								<td> Картинка:</td>				
								<td>
									<input type="file" name="image" size="105" id="valueRaffle_file"/>
									<i style="font-size: 10px;"><?php echo "min размер: ".$img_size['width']."x".$img_size['height']; ?></i>
								</td>
							</tr>
							<tr>
								<td> &nbsp;</td>	
								<td> 
									<img id="loading" src="/img/admin/loading.gif"/>
									<div id="image_insert"></div>
									<iframe id="hiddenraffleframe" name="hiddenraffleframe" style="width:0px; height:0px; border:0px"></iframe>	
								</td>
							</tr>							
						</tbody>
					</table>
					</div>
	<!-- Вкладка описание !-->
					<div id="tabs-raffle-2">
						<table>							
							<tr>
							<td><label>Условия розыгрыша:</label></td>
							</tr>
							<tr>
								<td><textarea name="short_description" class="tinymce" cols="50" rows="8" id="val_short_description"></textarea></td>
							</tr>
						</table>
					</div>
	<!-- Вкладка МЕТА !-->						
					<div id="tabs-raffle-3">
						<table class="table-tabs-content">
							<tbody>					
								<tr>
									<td class="td-name-width"> Title: </td>				
									<td><input type="text" name="title" id="val_title" /></td>
								</tr>
								<tr>
									<td class="td-name-align"> Meta Keywords:</td>				
									<td><textarea name="keywords" id="val_keywords"></textarea></td>
								</tr>
								<tr>
									<td class="td-name-align"> Meta Description: </td>				
									<td><textarea name="description" id="val_description"></textarea></td>
								</tr>						
							</tbody>
						</table>				
					</div>		
		<!-- Список участников !-->						
					<div id="tabs-raffle-4">
						<div id="tabs-s-raffle">
							<ul>
								<li><a href="#tabs-s-raffle-1">1 розыгрыш</a></li>
								<li><a href="#tabs-s-raffle-2">2 розыгрыш</a></li>
								<li><a href="#tabs-s-raffle-3">3 розыгрыш</a></li>					
								<li><a href="#tabs-s-raffle-4">4 розыгрыш</a></li>										
								<li><a href="#tabs-s-raffle-5">5 розыгрыш</a></li>					
							</ul>		
							<div id="tabs-s-raffle-1">
								<div id="rafle_table1"></div>
							</div>		
							<div id="tabs-s-raffle-2">
								<div id="rafle_table2"></div>
							</div>		
							<div id="tabs-s-raffle-3">
								<div id="rafle_table3"></div>
							</div>		
							<div id="tabs-s-raffle-4">
								<div id="rafle_table4"></div>
							</div>		
							<div id="tabs-s-raffle-5">
								<div id="rafle_table5"></div>
							</div>		
						</div>
					</div>				
		<!-- Товары !-->						
					<div id="tabs-raffle-5">
						<input type="text" name="ids_catalog" id="val_ids_catalog" value="" style="display: none;"/>
						<div class="left-tc10" >	
							<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
								<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Разделы</span></div>
								<div class="ui-jqgrid-bdiv" style="height: 408px;">
								
									<ul id="tree_catalog_tovar" class="filetree">
										<?php echo $trees; ?>
									</ul>
									
								</div>

							</div>
						</div>
						<div class="right-tc10" >
							<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"><span style="margin-left: 5px; color: #04408c;font-size: 11px;font-weight: bold;font-family: tahoma,arial,verdana,sans-serif;line-height: 20px;">Товары</span></div>
							<table id="TableTovar"></table>
						</div>
							
					</div>
					
				</div>	
			</form>
		</div>


        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	
