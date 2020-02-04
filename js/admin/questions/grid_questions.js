$(document).ready(function() {
	var baseUrl			= "/adminpanel/question/";
	var accessURL		= "/adminpanel/adminaccess/";
	
	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var catalogForm 	= $("#catalogForm");
	var b_gridUrl	 	= baseUrl+"load";
	var b_editUrl 		= baseUrl+"edit";
	var b_dataHandling 	= baseUrl+"datahandling";
	var b_dataAccess	= accessURL+"access";
	
	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 	

	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['question_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['question_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';
			
			setcol = '<div id="set_columns" class="button set_columns"></div>';
			
			$('#t_le_table').append(edit+del+setcol);		

			$("#set_columns").click(function(){
					table.jqGrid('setColumns',{
								colnameview:false,
								updateAfterCheck: true
						});
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
        colNames:['Код', 'Название','Вопрос','Ответ','Активен','Уведомление','Дата создания'],
        colModel :[
			{name:'id', 		index:'id', 		width:30, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
            {name:'name', 		index:'name', 		width:200, 	align:'left', 		search:true, 	editable:true, 	edittype:"text", searchoptions:{sopt:['cn']}},
            {name:'question',	index:'question', 	width:200, 	align:'left',	 	search:true, 	editable:true, 	edittype:"text", searchoptions:{sopt:['cn']}},
			{name:'answer', 	index:'answer', 	width:200, 	align:'left', 		search:false, 	editable:true, 	edittype:"text" },
			{name:'active', 	index:'active', 	width:40, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			{name:'notice', 	index:'notice', 	width:60, 	align:'left', 		search:false, 	editable:true, 	edittype:"text" },
			{name:'timestamp', 	index:'timestamp', 	width:60, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['question_edit']==1) gridEdit();
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
		//table.jqGrid('hideCol',["id","id_image"]);   /* Прячем стобцы */

	// Функция редактирование итема в тиблице	
	function gridEdit(){
		gsr = table.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_dataHandling,
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if ($.inArray(key, Array('active', 'notice_yes')) != -1) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else if (key == "notice") {
								if (res[key] != 0) {$('#val_'+key).val('Отправлено');} else { $('#val_'+key).val('Не отправлено'); }
								} else {
									$('#val_'+key).val(res[key]);
								}
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
					});
				$("#action_pole").attr({value: "edit"});
				catalogForm.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}
	}	

	// Функция удаления итема из таблицы
	function gridDelete(){
		var gr = table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				$("#deldialog").dialog( "open" );
					$("#del_id").val(gr);
			} else alert("Пожалуйста выберите запись!");
	}	

	// Инициализация формы добавления и редактирования данных					
	catalogForm.dialog({
		autoOpen: false,
		width: 880,
		minHeight: 'auto',
		title: "Вопрос-ответ",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#catalog_form").serialize();
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
		
	$("#deldialog").dialog({
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