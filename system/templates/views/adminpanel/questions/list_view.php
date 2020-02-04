<script type="text/javascript" src="/js/admin/questions/grid_questions.js"></script>
<script type="text/javascript">
  $(function () {
    translit('input[name="path"]');
  })
</script>
<div>

	<div>
	
		<div id="deldialog" class="del-form">
			<input type="hidden" id="del_id"/>
			<p>Вы действительно хотите удалить вопрос?</p>
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
								<td> Уведомление:</td>				
								<td><input type="text" readonly name="notice" id="val_notice" /></td>
							</tr>
							<tr>
								<td class="td-name-width"> Отправить уведомление:</td>				
								<td><input type="checkbox" name="notice_yes"  /></td>
							</tr>							
							<tr>
								<td class="td-name-align"> Вопрос:</td>				
								<td><textarea name="question" id="val_question"></textarea></td>
							</tr>	
							<tr>
								<td class="td-name-align"> Ответ:</td>				
								<td><textarea name="answer" id="val_answer"></textarea></td>
							</tr>							
						</tbody>
				</table>					
			</form>
		</div>


        <table id="le_table"></table>
        <div id="le_tablePager"></div>
		
	</div>
	
</div>	
