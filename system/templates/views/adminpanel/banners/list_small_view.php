<script type="text/javascript" src="/js/admin/banners/grid_banners_small.js"></script>
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
								<td> Название:</td>				
								<td><input type="text" name="name" id="val_name" /></td>
							</tr>							
							<tr>
								<td> Картинка:</td>				
								<td>
									<input type="file" name="image" size="105" id="valueBanners_file"/>
									<i style="font-size: 10px;"><?php echo "Размер картинки: ".$img_size['width']."x".$img_size['height']; ?></i>
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
				</div>	
			</form>
		</div>


        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	
