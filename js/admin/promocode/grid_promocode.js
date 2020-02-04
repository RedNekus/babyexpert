$(document).ready(function() {
	var baseUrl			= "/adminpanel/promocode/";
	var accessURL		= "/adminpanel/adminaccess/";
	
	$('.admin-tabs').tabs();
	
	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var catalogForm 	= $("#catalogForm");
	var b_gridUrl	 	= baseUrl+"load";
	var b_editUrl 		= baseUrl+"edit";
	var b_dataHandling 	= baseUrl+"datahandling";
	var b_dataAccess	= accessURL+"access";
	var SavePromoUrl    = baseUrl+"savetoxls";
	
	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 


	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['promocode_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['promocode_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['promocode_del']==1) del = '<div id="deldata" class="button del"></div>';
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
        colNames:['Код', 'Название','Активен (отзыв)','Активен (розыгрыш)'],
        colModel :[
			{name:'id', 			index:'id', 			width:15, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
            {name:'name', 			index:'name', 			width:500, 	align:'left', 		search:true, 	editable:true, 	edittype:"text", searchoptions:{sopt:['cn']}},
			{name:'active', 		index:'active', 		width:70, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			{name:'active_raffle', 	index:'active_raffle', 	width:70, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'asc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['promocode_edit']==1) gridEdit();
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
		).jqGrid('navButtonAdd', pager,{
				caption: 'Экспорт в exel',
				title: 'Обновить данные в promocode.xls',
				buttonicon: 'ui-icon-calculator',
					onClickButton: function(){					
							$.ajax({type: "POST",url: SavePromoUrl,dataType: "json",
								success:function (res) {//возвращаемый результат от сервера
									showMsgBox(res, "white", "center");
								},
								error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
							});					
						},
				position:'last'					
		});

	// Функция добавление итема в таблицу		
	function gridAdd(){
		$("#catalog_form").trigger("reset");
		$("#action_pole").attr({value: "add"});		
		catalogForm.dialog( "open" );
		}

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
							if ($.inArray(key, Array('active', 'active_raffle')) != -1) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else {
								if (key == "language") {
									baza = res['language'];
									for(var ley in baza){ 	
										$('#val_'+ley).val(baza[ley]);
									}
								}							
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
		width: 910,
		height: 600,
		title: "Свойства страницы",
		buttons: [
				{
				text: "Ok",
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
				text: "Cancel",
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
				text: "Ok",
				click: DelItemCatalog
			},
			{
				text: "Cancel",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
	
});