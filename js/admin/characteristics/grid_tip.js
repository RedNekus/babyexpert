$(document).ready(function() {
	var baseUrl				= "/adminpanel/characteristics_tip/";
	var TableCharacter	   	= $('#TableCharacter');
	var catalogFormTip 		= $("#catalogFormTip");
	var DelTipForm  		= $("#DelTipForm");
	var EditCatalogUrl 		= baseUrl+"edit";	
	var b_dataHandlingprfx	= baseUrl+"datahandling";
	var LoadGroupTipUrl		= baseUrl+"load";	


	// Инициализация таблицы картинок
	
	TableCharacter.jqGrid({
		url:LoadGroupTipUrl,
		datatype: 'json',
        mtype: 'GET',
		width: 720,
		height: 'auto',
        colNames:['Название'],
        colModel :[
            {name:'n1', 	index:'n1', 	width:700, 	align:'left', 		search:true, 	editable:true, 	edittype:"text" },
			],
		toolbar: [true,"top"],		
        sortname: 'id',
        sortorder: 'asc',
		ondblClickRow: function(id) {	gridEditTip();	}
    });
		
	$('#t_TableCharacter').height(24);			
	$('#t_TableCharacter').append('<div id="addtip" class="button add"></div>		<div id="edittip" class="button edit"></div>		<div id="deltip" class="button del"></div>	 ');		
	
	// Обработка события кнопки Добавить префикс
	$("#addtip").click(function(){
		$("#catalog_frm_tip").trigger("reset");
		$("#action_tip").attr({value: "add"});
		$("#valueTip_id_characteristics").val($("#valueChar_id").val());
		catalogFormTip.dialog( "open" );
	});
	
	// Обработка события кнопки Редактировать префикс	
	$("#edittip").click(function(){
		gridEditTip();
	});
	
	// Обработка события кнопки Удалить префикс	
	$("#deltip").click(function(){
		var gr = TableCharacter.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				DelTipForm.dialog( "open" );
				$("#del_id_tip").val(gr);
			} else alert("Пожалуйста выберите запись!");		
	});	
	
	function gridEditTip() {
		var gsr = TableCharacter.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					url: b_dataHandlingprfx,
					dataType: "json",
					type: "POST",	
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							$('#valueTip_'+key).val(res[key]);
						}							
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#action_tip").attr({value: "edit"});
				catalogFormTip.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}	
	
	// Инициализация формы добавления и редактирования поля					
	catalogFormTip.dialog({
		autoOpen: false,
		width: 570,
		height: 145,
		title: "Свойства поля",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#catalog_frm_tip").serialize();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							data: str,
							success:function (res) {
								var id = $("#valueTip_id_characteristics").val();
								TableCharacter.jqGrid('setGridParam',{url: LoadGroupTipUrl+"?id="+id,page:1}).trigger("reloadGrid");								
							},
							error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}
							});
						catalogFormTip.dialog( "close" );
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
	DelTipForm.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: function () {
						var id = $("#del_id_tip").val();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							dataType: "json",
							data: "del_id_tip="+id
						});
						
						TableCharacter.trigger("reloadGrid");
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