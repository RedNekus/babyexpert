$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog_prefix/";
	var TablePrefix	   	 	= $('#TablePrefix');
	var catalogFormPrefix 	= $("#catalogFormPrefix");
	var DelPrefixForm  		= $("#DelPrefixForm");
	var EditCatalogUrl 		= baseUrl+"edit";	
	var b_dataHandlingprfx	= baseUrl+"datahandling";
	var b_prefixUrl			= baseUrl+"load";	


	// Инициализация таблицы картинок
	TablePrefix.jqGrid({
		url:b_prefixUrl,
		datatype: 'local',
		mtype: 'GET',
		width: 901,
		height: 401,
		colNames:['Название','Базовый'],
		colModel :[
			{name:'name', 		index:'name', 		width:801, 	align:'left'},
			{name:'baza', 		index:'baza', 		width:100, 	align:'center'},
			],
		toolbar: [true,"top"],
		rowNum:20,
		cache: false,
		viewrecords: true,
		ondblClickRow: function(id) {gridEditPrefix();}
	});	
		
	$('#t_TablePrefix').height(24);			
	$('#t_TablePrefix').append('<div id="addprefix" class="button add"></div>		<div id="editprefix" class="button edit"></div>		<div id="delprefix" class="button del"></div>	 ');		
	
	// Обработка события кнопки Добавить префикс
	$("#addprefix").click(function(){
		$("#catalog_frm_prefix").trigger("reset");
		$("#action_prefix").attr({value: "add"});
		$('#valuePrefix_id_tree').val($("#valueTree_id").val());
		catalogFormPrefix.dialog( "open" );
	});
	
	// Обработка события кнопки Редактировать префикс	
	$("#editprefix").click(function(){
		gridEditPrefix();
	});
	
	// Обработка события кнопки Удалить префикс	
	$("#delprefix").click(function(){
		delItem(TablePrefix,DelPrefixForm,$("#del_id_prefix"));
	});	
	
	function gridEditPrefix() {
		var gsr = TablePrefix.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					url: b_dataHandlingprfx,
					dataType: "json",
					type: "POST",	
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if (key == "baza") {
								if (res[key] != 0) 	 {	$('#valuePrefix_'+key).attr('checked', true);} else { $('#valuePrefix_'+key).attr('checked', false); }						
							} else {
							$('#valuePrefix_'+key).val(res[key]);
							}
						}							
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#action_prefix").attr({value: "edit"});
				catalogFormPrefix.dialog( "open" );
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
	
	// Инициализация формы добавления и редактирования префикса					
	catalogFormPrefix.dialog({
		autoOpen: false,
		width: 570,
		height: 185,
		title: "Свойства префикса",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#catalog_frm_prefix").serialize();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							data: str,
							success:function (res) {
								var prefix_id = $("#valueTree_id").val();
								TablePrefix.jqGrid('setGridParam',{url: b_prefixUrl+"?prefix_id="+prefix_id,page:1}).trigger("reloadGrid");								
							},
							error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}
							});
						catalogFormPrefix.dialog( "close" );
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
	
	// Форма удаления префикса
	DelPrefixForm.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: function () {
						var id = $("#del_id_prefix").val();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							dataType: "json",
							data: "del_id_prefix="+id
						});
						
						TablePrefix.trigger("reloadGrid");
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