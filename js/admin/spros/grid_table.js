$(document).ready(function() {
	var baseUrl			= "/adminpanel/spros/";
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
			$('#t_le_table').height(28);

			if (access['spros_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['spros_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['spros_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';

			html = '<label for="chk-active"><input type="checkbox" name="active" id="chk-active" /> Завершен</labal>';
					
			html += '<label>ДАТА ОТ </label><input type="text" id="date_ot"><label>ДО </label><input type="text" id="date_do"><a href="" class="date_btn btn-cur">Найти</a>';
					
			html += '<select name="status" class="sel-select" id="sel-status"><option value="0">Нет</option><option value="1">Спрос</option><option value="2">Отказ</option><option value="3">Думают</option><option value="4">В работе</option><option value="5">Рекламация</option></select>';
					
			html += '<select name="manager" class="sel-select" id="sel-manager"></select>';		
					
			$('#t_le_table').append('<div class="toolbar_top"><div class="toolbar-block">' + add + edit + del + html + '</div></div>');		

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
	
	function getSelectHtml(method) {
		var re = '';
		$.ajax({type: "POST",url: baseUrl+'getselecthtml/', async: false, data: 'method='+method,
			success:function (res) {//возвращаемый результат от сервера	
				re = res;
			}						
		});	
		return re;	
	}
	
	table.jqGrid({
		url:b_gridUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:['Код','Менеджер','Название','Статус','Телефон','Дата','Время','Напомнить','Примечание','Завершен'],
        colModel :[
            {name:'id_item', 	index:'id_item', 	width:40, 	align:'center', 	search:true,  },
            {name:'manager', 	index:'manager', 	width:100, 	align:'center', 	search:true,  },
            {name:'name', 		index:'name', 		width:250, 	align:'left', 		search:true,  },
            {name:'status',		index:'status', 	width:80, 	align:'center',	 	search:false, },
            {name:'phone1',		index:'phone1', 	width:80, 	align:'center',	 	search:false, },
			{name:'date_spros', index:'date_spros', width:80, 	align:'center', 	search:false, },
			{name:'time_spros', index:'time_spros', width:80, 	align:'center', 	search:false, },
			{name:'interval', 	index:'interval', 	width:80, 	align:'left', 	search:false, },
			{name:'comment', 	index:'comment',	width:150, 	align:'left', 		search:false, },
			{name:'active', 	index:'active', 	width:50, 	align:'center', 	search:false, },
			],
        pager: pager,
        sortname: 'date_spros',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['spros_edit']==1) gridEdit();
		},
		gridComplete: function() {
		
			$( "#date_ot" ).datepicker({
				changeMonth: true,
				dateFormat: 'yy-mm-dd',
				changeYear: true
			});		
			
			$( "#date_do" ).datepicker({
				changeMonth: true,
				dateFormat: 'yy-mm-dd',
				changeYear: true
			});	
			
			if ($("#sel-manager option").length == 0) $("#sel-manager").append(getSelectHtml('registration'));

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
	
	function reloadTable() {
		var manager = $("#sel-manager").val(),
			active = ($("#chk-active").prop("checked")) ? 1 : 0,
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val(),
			status = $("#sel-status").val();
		
		table.jqGrid('setGridParam',{url: b_gridUrl+'?manager='+manager+'&active='+active+'&date_ot='+date_ot+'&date_do='+date_do+'&status='+status}).trigger("reloadGrid");		
	}
	
	$('#t_le_table').delegate("#chk-active", "click", function() {
		reloadTable()
	});	
	
	$("#t_le_table").delegate(".date_btn", "click", function() {
		reloadTable()
		return false;
	});
	
	$('#t_le_table').delegate(".sel-select", "change", function() {
		reloadTable()
	});	
		
	
	
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