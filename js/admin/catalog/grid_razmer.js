$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog_razmer/";
	var table	   	 		= $('#TableRazmer');
	var catalogFormRazmer 	= $("#catalogFormRazmer");
	var DelRazmerForm  		= $("#DelRazmerForm");
	var b_editUrl 			= baseUrl+"edit";	
	var b_openUrl			= baseUrl+"open";
	var b_loadUrl			= baseUrl+"load";	
	var b_newopenUrl		= baseUrl+"newopen";	

	// Инициализация таблицы картинок
	table.jqGrid({
		//url:b_loadUrl,
		datatype: 'local',
		//mtype: 'GET',
		width: 901,
		height: 404,
		colNames:['Название','Размер','Описание','Цена','Активен'],
		colModel :[		
			{name:'name',			index:'name', 			width:250, 	align:'left'},
			{name:'id_razmer',		index:'id_razmer', 		width:160, 	align:'left'},
			{name:'id_description',	index:'id_description', width:360, 	align:'left'},
			{name:'cena',			index:'cena', 			width:80, 	align:'center'},
			{name:'active', 		index:'active', 		width:60, 	align:'center'},
			],
		toolbar: [true,"top"],
		rowNum:150,
        sortname: 'id',
        sortorder: 'asc',		
		cache: false,
		viewrecords: true,
		ondblClickRow: function(id) {gridEditRazmer();}
	});	
		
	$('#t_TableRazmer').height(24);			
	$('#t_TableRazmer').append('<div id="addrazmer" class="button add"></div>		<div id="editrazmer" class="button edit"></div>		<div id="delrazmer" class="button del"></div>	 ');		
	
	// Обработка события кнопки Добавить изображение
	$("#addrazmer").click(function(){
		$("#catalog_frm_razmer").trigger("reset");
		$("#action_razmer").attr({value: "add"});
		$('#valueRazmer_id_catalog').val($("#val_id").val());
		$("#valueRazmer_active").attr({'checked': true});
		htmlSelect();
		catalogFormRazmer.dialog( "open" );			

	});
	
	// Обработка события кнопки Редактировать изображение	
	$("#editrazmer").click(function(){
		gridEditRazmer();
	});
	
	// Обработка события кнопки Удалить изображение	
	$("#delrazmer").click(function(){
		delItem(table,DelRazmerForm,$("#del_id_razmer"));
	});	
	
	function gridEditRazmer() {
		$("#catalog_frm_razmer").trigger("reset");
		var gsr = table.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					url: b_openUrl,
					dataType: "json",
					type: "POST",	
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if (key == "active") {
								if (res[key] != 0) 	 {	$('#valueRazmer_'+key).attr('checked', true);} else { $('#valueRazmer_'+key).attr('checked', false); }						
							} else {
								$('#valueRazmer_'+key).val(res[key]);
								if (key = "razmer_html") {
									$("#valueRazmer_id_razmer").empty();
									$("#valueRazmer_id_razmer").html(res[key]);								
								}
								if (key = "description_html") {
									$("#valueRazmer_id_description").empty();
									$("#valueRazmer_id_description").html(res[key]);								
								}
							}
						}
						$("#valueRazmer_file").val("");	
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#action_razmer").attr({value: "edit"});
				catalogFormRazmer.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}	
	
	function htmlSelect() {
		$.ajax({
			url: b_newopenUrl,
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера
				$("#valueRazmer_id_razmer").empty();
				$("#valueRazmer_id_razmer").html(res['razmer_html']);
				$("#valueRazmer_id_description").empty();
				$("#valueRazmer_id_description").html(res['description_html']);
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});	
	}
	
	// Функция удаления данных из бд
	function delItem(bi_table, dialogForm, delValue){
		var gr = bi_table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				dialogForm.dialog( "open" );
				delValue.val(gr);
			} else alert("Пожалуйста выберите запись!");		
	}
	
	// Инициализация формы добавления и редактирования картинки					
	catalogFormRazmer.dialog({
		autoOpen: false,
		width: 570,
		minHeight: 'auto',
		title: "Свойства",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#catalog_frm_razmer").serialize();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							data: str,
							});
						table.trigger("reloadGrid");
						$( this ).dialog( "close" );
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
	
	// Форма удаления картинки
	DelRazmerForm.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: function () {
						var id = $("#del_id_razmer").val();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							data: "del_id_razmer="+id,
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