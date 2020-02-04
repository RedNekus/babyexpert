$(document).ready(function() {
	var baseUrl			= "/adminpanel/registration/";
	var accessURL		= "/adminpanel/adminaccess/";
	
	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var dialogEdit 		= $("#dialog-edit");
	var dialogDel 		= $("#dialog-del");
	var b_loadUrl	 	= baseUrl+"load";
	var b_editUrl 		= baseUrl+"edit";
	var b_openUrl	 	= baseUrl+"open";
	var b_dataAccess	= accessURL+"access";
	
	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 	

	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['registration_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['registration_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['registration_del']==1) del = '<div id="deldata" class="button del"></div>';
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
		url:b_loadUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:['Код', 'Наименование организации','Логин','Телефон','Email','Касса','Активен'],
        colModel :[
			{name:'id', 		index:'id', 		width:30, 	align:'center', 	search:false, 	},
            {name:'name', 		index:'name', 		width:300, 	align:'left', 		search:true, 	searchoptions:{sopt:['cn']}},
            {name:'login', 		index:'login', 		width:200, 	align:'left', 		search:true, 	searchoptions:{sopt:['cn']}},
            {name:'phone', 		index:'phone', 		width:200, 	align:'left', 		search:true, 	searchoptions:{sopt:['cn']}},
			{name:'email', 		index:'email', 		width:200, 	align:'left', 		search:false, 	},
			{name:'id_kassa_tree', 		index:'id_kassa_tree', 		width:100, 	align:'left', 		search:false, 	},
			{name:'active', 	index:'active', 	width:50, 	align:'center', 	search:false, 	},
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'asc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['registration_edit']==1) gridEdit();
		},
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30,		
		editurl:b_editUrl
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

	// Функция редактирование итема в тиблице	
	function gridEdit(){
		gsr = table.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_openUrl,
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if ((key == "active") || (key == "discount_price") || (key == "newsletter")) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else {
							$('#val_'+key).val(res[key]);
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
		title: "Регистрация",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#form").serialize();
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

	function DelItemCatalog() {
		var id = $("#del_id").val();
			$.ajax({
				type: "POST",
				url: b_editUrl,
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