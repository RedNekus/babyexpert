jQuery.fn.exists = function() {
   return $(this).length;
}
$(document).ready(function() {
	var baseUrl			= "/adminpanel/advantages/";
	var accessURL		= "/adminpanel/adminaccess/";

	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var dialogEdit 		= $("#dialog-edit");
	var dialogDel 		= $("#dialog-del");
	var loadDataUrl	 	= baseUrl+"load";
	var editDataUrl 	= baseUrl+"edit";
	var openDataUrl 	= baseUrl+"open";
	var b_dataAccess	= accessURL+"access";

	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 

	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['advantages_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['advantages_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['advantages_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';

			$('#t_le_table').append(add + edit + del);

			// Обработка события кнопки Добавить
			$("#adddata").click(function(){
				gridAdd();
			});
			
			// Обработка события кнопки Редактировать
			$("#editdata").click(function(){
				gridEdit();
			});

			// Обработка события кнопки Удалить	
			$("#deldata").click(function(){
				gridDelete();
			});
		}
	});
		
	table.jqGrid({
		url:loadDataUrl,
		datatype: 'json',
		mtype: 'GET',
		autowidth: true,
		height: height,		
		colNames:['Код', 'Название','Активен','Приоритет','Дата создания'],
		colModel :[
			{name:'id', 		index:'id', 			width:15, 	align:'center', 	search:false, 	editable:false, 	edittype:"text" },
			{name:'title', 		index:'title', 			width:400, 	align:'left', 		search:true, 	editable:true, 		edittype:"text", searchoptions:{sopt:['cn']}},
			{name:'active', 	index:'active', 		width:50, 	align:'center', 	search:false, 	editable:true, 		edittype:"text" },
			{name:'prioritet', 	index:'prioritet', 		width:45, 	align:'center', 	search:false, 	editable:false, 	edittype:"text" },
			{name:'timestamp', 	index:'timestamp', 		width:40, 	align:'left', 		search:true, 	editable:false, 	edittype:"text", searchoptions:{sopt:['cn']}},
			],
		pager: pager,
		sortname: 'timestamp',
		toolbar: [true,"top"],
		sortorder: 'desc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['advantages_edit']==1) gridEdit();
		},
		rowList:[50,100,150,200],
		rownumbers: true,
		rownumWidth: 30,
		editurl:editDataUrl
		}).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: false},
					{}, // параметры редактирования
					{}, // параметры добавления
					{}, // параметры удаления
					{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true}, // параметры поиска
					{} /* параметры просмотра */
				);

	// Функция добавление итема в таблицу
	function gridAdd(){
		$("#form").trigger("reset");
		$("#action_pole").attr({value: "add"});
		dialogEdit.dialog( "open" );
		}

	// Функция редактирование итема в таблице
	function gridEdit(){
		gsr = table.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: openDataUrl,
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){
							if(key == "timestamp") {
								continue;
							} else if (key == "active") {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }
							} else {
								if (key == "language") {
									baza = res['language'];
									for(var ley in baza){ 	
										$('#val_'+ley).val(baza[ley]);
									}
								}
								$('#val_'+key).val(res[key]);
								if(key == 'image' && res[key]) {
									if($('#adv-img').exists())
										$('#adv-img').attr('src', res[key]);
									else
										$('#val_file_image').parents('tr').before('<tr><td colspan="2"><img id="adv-img" src="' + res[key] + '"></td><tr>');
								}
							}
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}
					});
				$("#action_pole").attr({value: "edit"});
				dialogEdit.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}
	}	

	// Функция удаления итема из таблицы
	function gridDelete(){
		var gr = table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				dialogDel.dialog( "open" );
					$("#del_id").val(gr);
			} else alert("Пожалуйста выберите запись!");
	}	

	// Инициализация формы добавления и редактирования данных
	dialogEdit.dialog({
		autoOpen: false,
		width: 910,
		minHeight: 'auto',
		title: "Свойства преимущества",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
						tinyMCE.triggerSave(); 
						var image = document.getElementById("val_file_image").files[0];
						if(image)
							$("#form #val_image").val('/assets/images/advantages/' + image.name);
						//var str = $("#form").serialize();
						var data = new FormData(form);
						$.ajax({
							type: "POST",
							enctype: 'multipart/form-data',
							url: editDataUrl,
							cache: false,
							contentType: false,
							processData: false,
							data: data,
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

	function DelItemCatalog() {
		var id = $("#del_id").val();
			$.ajax({
				type: "POST",
				url: editDataUrl,
				data: "del_id="+id,
			});
			table.trigger("reloadGrid");
			$( this ).dialog( "close" );
	}
		
	dialogDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: DelItemCatalog
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