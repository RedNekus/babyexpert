<script type="text/javascript" src="/js/admin/reviews/grid_reviews.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="path"]');
  })
</script>
<div>

	<div>
	
		<div id="deldialog" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить отзыв?</p>
		</div>
	
		<div id="catalogForm">
			<form method="post" name="catalog_form" id="catalog_form" action="">
				<table class="table-tabs-content">
					<input type="hidden" name="id" id="val_id"/>
					<input type="hidden" name="id_catalog" id="val_id_catalog"/>
					<input type="hidden" name="action" id="action_pole" />					
						<tbody>												
							<tr>
								<td class="td-name-width"> Активен:</td>				
								<td><input type="checkbox" name="active"  id="val_active"  /></td>
							</tr>
							<tr>
								<td> Товар:</td>				
								<td><input type="text" readonly name="nameTovar" id="val_nameTovar" /></td>
							</tr>							
							<tr>
								<td> Имя:</td>				
								<td><input type="text" readonly name="name" id="val_name" /></td>
							</tr>
							<tr>
								<td> Email:</td>				
								<td><input type="text" readonly name="email" id="val_email" /></td>
							</tr>							
							<tr>
								<td> Телефон:</td>				
								<td><input type="text" readonly name="telefon" id="val_telefon" /></td>
							</tr>							
							<tr>
								<td> Промокод:</td>				
								<td><input type="text" readonly name="promocod" id="val_promocod" /></td>
							</tr>							
							<tr>
								<td> Рейтинг:</td>				
								<td>
									<span class="rank_style"><input type="radio" name="rank" value="1" id="val_rank_1"><label for="val_rank_1">1</label></span>
									<span class="rank_style"><input type="radio" name="rank" value="2" id="val_rank_2"><label for="val_rank_2">2</label></span>
									<span class="rank_style"><input type="radio" name="rank" value="3" id="val_rank_3"><label for="val_rank_3">3</label></span>							
									<span class="rank_style"><input type="radio" name="rank" value="4" id="val_rank_4"><label for="val_rank_4">4</label></span>
									<span class="rank_style"><input type="radio" name="rank" value="5" id="val_rank_5"><label for="val_rank_5">5</label></span>
								</td>
							</tr>	
							<tr>
								<td class="td-name-align"> Отзыв:</td>				
								<td><textarea name="content" id="val_content"></textarea></td>
							</tr>							
						</tbody>
				</table>					
			</form>
		</div>


        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	
