$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog_complect/";
	var tableComplect 		= $('#TableComplect');
	var pagerComplect		= '#pager-complect';
	var dialogEdit 			= $("#catalogFormCompt");
	var dialogDel  			= $("#DelComptForm");
	var b_editUrl 			= baseUrl+"edit";	
	var b_openUrl			= baseUrl+"open";
	var loadSelectMakerUrl	= baseUrl+"load_select_maker";
	var loadSelectTovarUrl	= baseUrl+"load_select_tovar";
	
	// Инициализация таблицы
	tableComplect.jqGrid({
		datatype: 'local',
		width: 951,
		height: 404,
		colNames:['Размер','Товар','Цена','Тип','Кол-во','Скидка $'],
		colModel :[		
			{name:'id_razdel',		index:'id_razdel', 		width:100, 	align:'left'},
			{name:'id_product',		index:'id_product', 	width:400, 	align:'left'},
			{name:'cena',			index:'cena', 			width:100, 	align:'center'},
			{name:'type_complect',	index:'type_complect', 	width:100, 	align:'left'},
			{name:'kolvo',			index:'kolvo', 			width:60, 	align:'center'},
			{name:'skidka_usd', 	index:'skidka_usd', 	width:60, 	align:'center'},
			],
		pager: pagerComplect,
		toolbar: [true,"top"],		
		rowNum:50,
		ondblClickRow: function(id) {gridEdit();},	
		viewrecords: true,		
		rowList:[50,100,200,500],
		rownumbers: true,
		rownumWidth: 30		
	}).jqGrid('navGrid', pagerComplect,{refresh: true, add: false, del: false, edit: false, search: false, view: false});
		
	$('#t_TableComplect').height(24);			
	$('#t_TableComplect').append('<div id="addcomplect" class="button add"></div>		<div id="editcomplect" class="button edit"></div>		<div id="delcomplect" class="button del"></div>	 ');		
	
	// Обработка события кнопки Добавить изображение
	$("#addcomplect").click(function(){
		gridAdd();	
	});
	
	// Обработка события кнопки Редактировать изображение	
	$("#editcomplect").click(function(){
		gridEdit();
	});
	
	// Обработка события кнопки Удалить изображение	
	$("#delcomplect").click(function(){
		delItem(tableComplect,dialogDel,$("#del_id_complect"));
	});	
	
	function gridAdd() {
		$(".hide-block").show();
		$("#catalog_frm_complect").trigger("reset");
		$("#action_complect").attr({value: "add"});
		$('#valueCompt_id_catalog').val($("#val_id").val());
		dialogEdit.dialog( "open" );		
	}
	
	function gridEdit() {
		var gsr = tableComplect.jqGrid('getGridParam','selrow');
		if(gsr){
			$(".hide-block").hide();
			$.ajax({
				url: b_openUrl,
				dataType: "json",
				type: "POST",	
				data: "id="+gsr,
				success:function (res) {//возвращаемый результат от сервера
					for(var key in res){ 
						$('#valueCompt_'+key).val(res[key]);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
			});
			$("#action_complect").attr({value: "edit"});
			dialogEdit.dialog( "open" );
		} else {
			alert("Пожалуйста выберите запись!")
		}	
	}	
	
	// Функция удаления данных из бд
	function delItem(bi_tableComplect, dialogForm, delValue){
		var gr = bi_tableComplect.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				dialogForm.dialog( "open" );
				delValue.val(gr);
			} else alert("Пожалуйста выберите запись!");		
	}
	
	// Инициализация формы добавления и редактирования					
	dialogEdit.dialog({
		autoOpen: false,
		width: 570,
		minHeight: 'auto',
		title: "Свойства",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#catalog_frm_complect").serialize();
					$.ajax({
						type: "POST",
						url: b_editUrl,
						data: str,
					});
					tableComplect.trigger("reloadGrid");
					dialogEdit.dialog( "close" );
				
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
	dialogDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: function () {
						var id = $("#del_id_complect").val();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							data: "del_id_complect="+id,
						});
						tableComplect.trigger("reloadGrid");
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


	$(".val_id_tree").change(function() {
		var id_tree = $(this).val();
		
		$.ajax({
			type: "POST",
			url: loadSelectMakerUrl,
			data: "id_tree="+id_tree,
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера		
				$( ".val_id_maker" ).empty();
				$( ".val_id_maker" ).append(res['makers']);
				$( ".val_id_tovar" ).empty();
				$( ".val_id_tovar" ).append(res['tovars']);								
			}	
		});
	});
	
	$(".val_id_maker").change(function() { 
		var id_maker = $(this).val(),
			id_tree = $(".val_id_tree").val();
		
		$.ajax({
			type: "POST",
			url: loadSelectTovarUrl,
			data: "id_tree="+id_tree+"&id_maker="+id_maker,
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера			
				$( ".val_id_tovar" ).empty();
				$( ".val_id_tovar" ).append(res['tovars']);								
			}	
		});			
	});
	
});