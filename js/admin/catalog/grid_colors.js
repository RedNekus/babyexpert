$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog_colors/";
	var TableColors	   	 	= $('#TableColors');
	var catalogFormColor 	= $("#catalogFormColor");
	var DelColorForm  		= $("#DelColorForm");
	var EditCatalogUrl 		= baseUrl+"edit";	
	var b_dataHandlingImg	= baseUrl+"datahandling";
	var b_colorUrl			= baseUrl+"load";	


	// Инициализация таблицы картинок
	TableColors.jqGrid({
		//url:b_colorUrl,
		datatype: 'local',
		//mtype: 'GET',
		width: 901,
		height: 404,
		colNames:['Фото','Файл','Название','Активен','Приоритет','Базовая'],
		colModel :[
			{name:'picture', 	index:'picture', 	width:50, 	align:'center'},
			{name:'image', 		index:'image', 		width:250, 	align:'left'},			
			{name:'name',		index:'name', 		width:250, 	align:'left'},
			{name:'active',		index:'active', 	width:60, 	align:'center'},
			{name:'prioritet',	index:'prioritet', 	width:60, 	align:'left'},
			{name:'baza', 		index:'baza', 		width:60, 	align:'center'},
			],
		toolbar: [true,"top"],
		rowNum:50,
		cache: false,
		viewrecords: true,
		ondblClickRow: function(id) {gridEditColor();}
	});	
		
	$('#t_TableColors').height(24);			
	$('#t_TableColors').append('<div id="addcolor" class="button add"></div>		<div id="editcolor" class="button edit"></div>		<div id="delcolor" class="button del"></div>	 ');		
	
	// Обработка события кнопки Добавить изображение
	$("#addcolor").click(function(){
		$("#catalog_frm_color").trigger("reset");

		var text = "",
			text_name = "",
			text_prefix = "",
			flag = false,
			flag2 = false;
	
		if ($("#val_name").val()=="") {
			text += "Поле НАЗВАНИЕ не заполнено\n";
			flag2 = true;
		} else {
			text_name = $("#val_name").val();
			flag2 = false;			
		}	
			
		if ($("#prefix_select").val()==0) {
			text += "Поле Префикс не заполнено\n";	
			flag = true;
		} else {
			text_prefix = $("#prefix_select").val();
			flag = false;
		}
		
		if (flag || flag2) {
			alert(text);
		} else {
			$("#action_color").attr({value: "add"});
			$('#valueColor_id_catalog').val($("#val_id").val());
			$('#valueColor_name_tovar').val(text_name);
			$('#valueColor_prefix_id_tovar').val(text_prefix);
			$("#valueColor_active").attr({'checked': true});
			catalogFormColor.dialog( "open" );			
		}
	});
	
	// Обработка события кнопки Редактировать изображение	
	$("#editcolor").click(function(){
		gridEditColor();
	});
	
	// Обработка события кнопки Удалить изображение	
	$("#delcolor").click(function(){
		delItem(TableColors,DelColorForm,$("#del_id_color"));
	});	
	
	function gridEditColor() {
		var gsr = TableColors.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					url: b_dataHandlingImg,
					dataType: "json",
					type: "POST",	
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if ((key == "active") || (key == "baza")) {
								if (res[key] != 0) 	 {	$('#valueColor_'+key).attr('checked', true);} else { $('#valueColor_'+key).attr('checked', false); }						
							} else {
								if (key == "url") {	
									$('#color_insert').html("<img src='"+res['url']+"' />")
								} else {
									$('#valueColor_'+key).val(res[key]);
								}
								$('#valueColor_prefix_id_tovar').val($("#prefix_select").val());
								$('#valueColor_name_tovar').val($("#val_name").val());
							}
						}
						$("#valueColor_file").val("");	
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#action_color").attr({value: "edit"});
				catalogFormColor.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}	
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
	catalogFormColor.dialog({
		autoOpen: false,
		width: 570,
		height: 440,
		title: "Свойства картинки",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					$('#catalog_frm_color').submit();
					var iframe = $("#hiddencolorframe");
					$("#loading").show();
					iframe.load(function(){
						$("#loading").hide();
						TableColors.trigger("reloadGrid");
						catalogFormColor.dialog( "close" );
					});
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
	DelColorForm.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: function () {
						var id = $("#del_id_color").val();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							data: "del_id_color="+id,
						});
						TableColors.trigger("reloadGrid");
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