$(document).ready(function() {
	var baseUrl			= "/adminpanel/product_week/";
	var accessURL		= "/adminpanel/adminaccess/";
	
	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var dialogEdit 		= $("#dialog-edit");
	var dialogDel 		= $("#dialog-del");
	var b_gridUrl	 	= baseUrl+"load";
	var b_editUrl 		= baseUrl+"edit";
	var b_openUrl 		= baseUrl+"open";
	var b_dataAccess	= accessURL+"access";
	
	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 	

	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['product_week_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['product_week_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['product_week_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';
			
			setcol = '<div id="set_columns" class="button set_columns"></div>';
			
			$('#t_le_table').append(add+edit+del+setcol);		

			$("#set_columns").click(function(){
					table.jqGrid('setColumns',{
								colnameview:false,
								updateAfterCheck: true
						});
			});

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
		url:b_gridUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:['Код','Название','Скидка %','Дата от','Дата до','Активен'],
        colModel :[
			{name:'id', 		index:'id', 		width:30, 	align:'center', },
            {name:'name', 		index:'name', 		width:350, 	align:'left', 	},
            {name:'skidka', 	index:'skidka', 	width:50, 	align:'center', },
            {name:'date_ot',	index:'date_ot', 	width:100, 	align:'center',	},
			{name:'date_do', 	index:'date_do', 	width:100, 	align:'center', },
			{name:'active', 	index:'active', 	width:50, 	align:'center', },
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'asc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['product_week_edit']==1) gridEdit();
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
							if (key == "active") {
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
		title: "Свойства",
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

	dialogDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: function() {
					var id = $("#del_id").val();
					$.ajax({
						type: "POST",
						url: b_editUrl,
						data: "del_id="+id,
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