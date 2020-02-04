$(document).ready(function() {
	var baseUrl			= "/cart/";

	var table 			= $('#TableTovar');
	var pager 			= '#TableTovarPager';
	
	var tovarForm 		= $("#tovarForm");
	var delForm  		= $("#delTovarForm");
		
	var b_loadUrl	= baseUrl+"load";	
	var b_editUrl 	= baseUrl+"edit";
	var b_openUrl 	= baseUrl+"open";

	table.jqGrid({
		url:b_loadUrl,
		datatype: 'json',
        mtype: 'GET',
		width: 850,
		height: 'auto',
        colNames:[' ','Наименование','Кол-во','Цена $','Цена Br', 'Сумма $', 'Сумма Br', 'Подарок', 'Розыгрыш', 'Статус'],
        colModel :[
			{name:'act', 		index:'act', 		width:30, 	align:'center', search:false, },
            {name:'name', 		index:'name', 		width:310, 	align:'left', 	search:false, },
            {name:'kolvo', 		index:'kolvo', 		width:60, 	align:'center', search:false, },
            {name:'cena', 		index:'cena', 		width:60, 	align:'center', search:false, },
            {name:'cena_blr', 	index:'cena_blr', 	width:120, 	align:'left',   search:false, },
            {name:'summa', 		index:'summa', 		width:80, 	align:'center', search:false, },
            {name:'summa_blr',	index:'summa_blr', 	width:120, 	align:'center', search:false, },
            {name:'gift', 		index:'gift', 		width:60, 	align:'center', search:false, },
            {name:'raffle', 	index:'raffle', 	width:60, 	align:'center', search:false, },
            {name:'status', 	index:'status', 	width:110, 	align:'center', search:false, },
			],		
		pager: pager,	
		toolbar: [true,"top"],
		ondblClickRow: function(id) {	gridEdit();	},
		footerrow: true,
		userDataOnFooter: true,			
    }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: false},
					{}, // параметры редактирования
					{}, // параметры добавления
					{}, // параметры удаления
					{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true}, // параметры поиска
					{} /* параметры просмотра */
				);
	 
	add = '<div id="addtovar" class="button add"></div>';
	edit = '<div id="edittovar" class="button edit"></div>';
	del = '<div id="deltovar" class="button del"></div>';	
	$("#t_TableTovar").height(24);	
	$("#t_TableTovar").append(add + edit + del);		
	
	
	// Обработка события кнопки Добавить
	$("#addtovar").click(function(){
		gridAdd();
	});
	
	// Обработка события кнопки Редактировать	
	$("#edittovar").click(function(){
		gridEdit();
	});
	
	// Обработка события кнопки Удалить	
	$("#deltovar").click(function(){
		gridDelete();		
	});	

	$("#TableTovar").delegate(".editdata", "click", function(){
		gridEdit($(this).attr('rel'));
	});			
	
	function gridAdd() {
		$("#action_tovar").attr({value: "add"});
		$(".hide-tr").show();
		$("#form_tovar").trigger("reset");
		$("#value_id_client").val($("#val_id").val());
		$("#value_nomer_zakaza").val($("#val_nomer_zakaza").val());
		tovarForm.dialog( "open" );
	}
	
	function gridEdit(gsr) {
		if (typeof(gsr) != "undefined") id = gsr;
		else id = table.jqGrid('getGridParam','selrow');
			if(id){
				$.ajax({
					url: b_openUrl,
					dataType: "json",
					type: "POST",	
					data: "id="+id,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){
							if ($.inArray(key, Array('predzakaz', 'rezerv')) != -1) {
								if (res[key] != 0) 	 {	$('#value_'+key).attr('checked', true);} else { $('#value_'+key).attr('checked', false); }						
							} else {	
								if (key == 'html') {
									$('#table-skald-tovar').empty();
									$('#table-skald-tovar').html(res[key]);
								}							
								$('#value_'+key).val(res[key]);
							}
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#action_tovar").attr({value: "edit"});
				$(".hide-tr").hide();
				tovarForm.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}	
	
	// Функция удаления итема из таблицы
	function gridDelete(){
		var gr = table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				delForm.dialog( "open" );
					$("#del_id_tovar").val(gr);
			} else alert("Пожалуйста выберите запись!");
	}	
	
	// Инициализация формы добавления и редактирования поля					
	tovarForm.dialog({
		autoOpen: false,
		width: 570,
		minHeight: 'auto',
		title: "Свойства",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#form_tovar").serialize();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							data: str,
							dataType: 'json',
							success:function (res) {//возвращаемый результат от сервера
								if (res['msg']) showMsgBox(res['msg'], "white", "center");
							},
							error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}							
						});
						table.trigger("reloadGrid");
						tovarForm.dialog( "close" );
					}
				},
				{
				text: "Отмена",
				click: function() {
					$( this ).dialog( "close" );
					}
				}
			]
	});
	
	// Форма удаления поля
	delForm.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: function () {
						var id = $("#del_id_tovar").val();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							dataType: "json",
							data: "del_id="+id
						});
						
						table.trigger("reloadGrid");
						$( this ).dialog( "close" );						
				}
			},
			{
				text: "Нет",
				click: function() {
						$( this ).dialog( "close" );
				}
			}
		]
	});		
});