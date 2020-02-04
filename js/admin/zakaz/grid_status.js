$(document).ready(function() {
	var baseUrl			= "/adminpanel/zakaz/";
	var accessURL		= "/adminpanel/adminaccess/";
	var baseUrlTovar	= "/adminpanel/zakaz_tovar/";
	
	var tableTovar	    = $('#TableTovar');	
	var urlTovar		= baseUrlTovar+"load";	

	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var catalogForm 	= $("#catalogForm");
	var delForm 		= $("#deldialog");
	var printForm 		= $("#print_dialog");
	var b_gridUrl	 	= baseUrl+"load_status";
	var b_editUrl 		= baseUrl+"edit";
	var b_openUrl 		= baseUrl+"open";
	var b_dataprint 	= baseUrl+"dataprint";
	var b_dataAccess	= accessURL+"access";
	var lastsel;
	var height 			= $(window).height()-135;
	
	var stop_save  		= false;
	
	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(28);

			if (access['zakaz_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['zakaz_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['zakaz_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';
	
			html = '<label>ДАТА ОТ </label><input type="text" id="date_ot"><label>ДО </label><input type="text" id="date_do"><a href="" class="date_btn btn-cur">Найти</a>';
				
			html += '<select name="status" id="sel-status"><option value="0">Нет</option><option value="1">Отказ</option><option value="2">Не продан</option><option value="3">Отгружен</option><option value="4">Предзаказ</option><option value="5">Резерв</option><option value="6">Доставлен</option><option value="7">Не доставлен</option><option value="8">Возвращен</option><option value="9">Возврат</option></select>';
					
			select = '<select name="manager" class="sel-select" id="sel-manager"></select>';
			select += '<select name="diler" class="sel-select" id="sel-diler"></select>';
							
			$('#t_le_table').append('<div class="toolbar_top"><div class="toolbar-block">' + add + edit + del + html + select + '</div></div>');		

			// Обработка события кнопки Добавить	
			$("#adddata").click(function(){
				gridAdd();
			});		
			
			// Обработка события кнопки Удалить	
			$("#deldata").click(function(){
				gridDelete();
			});		
			
			// Обработка события кнопки Редактировать	
			$("#editdata").click(function(){
				gridEdit();
			});

			$("#le_table").delegate(".editdata", "click", function(){
				gridEdit($(this).attr('rel'));
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
        colNames:[' ','№з','Менеджер','Дилер','Наименование товара','Телефон','Кол-во','Дата заказа','Время заказа','Статус', 'Примечание менеджера'],
        colModel :[	
			{name:'act',			index:'act', 			width:12,  align:'center', search:false, sortable:false},
			{name:'nomer_zakaza', 	index:'nomer_zakaza', 	width:25,  align:'center', search:true,  searchoptions:{sopt:['cn']}},
			{name:'id_adminuser', 	index:'id_adminuser',	width:35,  align:'left',   search:false, },		
			{name:'id_diler', 		index:'id_diler',		width:35,  align:'left',   search:false, },		
			{name:'name_tovar', 	index:'name_tovar',		width:220, align:'left',   search:false, sortable:false},
			{name:'phone', 			index:'phone',			width:45,  align:'left',   search:true,  searchoptions:{sopt:['cn']}},
			{name:'kolvo', 			index:'kolvo',			width:45,  align:'center', search:false  },
			{name:'date_zakaz', 	index:'date_zakaz', 	width:45,  align:'center', search:false, },		
			{name:'time_zakaz', 	index:'time_zakaz', 	width:45,  align:'center', search:false, },		
			{name:'status', 		index:'status', 		width:45,  align:'left',   search:false, },	
			{name:'comment_m', 		index:'comment_m', 		width:150, align:'left',   search:false, },	
			],
        pager: pager,
        sortname: 'date_zakaz',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:100,	
		viewrecords: true,	
		rowList:[100,500,1000,2000],
		rownumbers: true,
        rownumWidth: 30,	
		grouping:true,	
		ondblClickRow: function(id) {
			if (access['zakaz_edit']==1) gridEdit();
		},		
		groupingView : {
			groupField : ['date_zakaz'],
			groupColumnShow : [true], 
			groupText : ['<b style="color: green; font-size: 18px;">{0}</b>'],
			groupCollapse : false,
			groupOrder: ['desc'],
			groupSummary : [true],
			showSummaryOnHide: true,
			groupDataSorted : true
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

			if ($("#sel-manager option").length == 0) $("#sel-manager").append(getSelectHtml('manager'));
			if ($("#sel-diler option").length == 0) $("#sel-diler").append(getSelectHtml('diler'));

		},		
		editurl:b_editUrl
        }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: false});
		
	function reloadTable() {
		var manager = $("#sel-manager").val(),
			diler = $("#sel-diler").val(),
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val(),
			status = $("#sel-status").val();
		
		table.jqGrid('setGridParam',{url: b_gridUrl+'?manager='+manager+'&diler='+diler+'&date_ot='+date_ot+'&date_do='+date_do+'&status='+status}).trigger("reloadGrid");		
	}
	
	$('#t_le_table').delegate(".sel-select", "change", function() {
		reloadTable()
	});	
	
	$("#t_le_table").delegate(".date_btn", "click", function() {
		reloadTable()
		return false;
	});
	
	$('#t_le_table').delegate("#sel-status", "change", function() {
		reloadTable()
	});	
				
	
	function gridAdd() {
		$("#gbox_TableTovar").hide();
		$("#catalog_form").trigger("reset");		
		$("#action_pole").attr({value: "add"});
		catalogForm.dialog( "open" );		
	}
				
	// Функция редактирование итема в тиблице	
	function gridEdit(gsr){
		if (typeof(gsr) != "undefined") id = gsr;
		else id = table.jqGrid('getGridParam','selrow');
		$("#gbox_TableTovar").show();
			if(id){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_openUrl,
					data: "id="+id,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if ($.inArray(key, Array('samovivoz', 'samovivoz_ofice', 'dostavka', 'print_ready', 'dumayut')) != -1) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else {	
								if (key == "stop_save") {
									stop_save = res[key];
								}
								$('#val_'+key).val(res[key]);
							}
						}
						tableTovar.jqGrid('setGridParam',{url: urlTovar+"?id_client="+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
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
				delForm.dialog( "open" );
					$("#del_id").val(gr);
			} else alert("Пожалуйста выберите запись!");
	}	
	
	// Функция печати
	function gridPrint() {
		$('#print_table_elem').empty();
		var date_zakaz = $("#date_zakaz").val();
				
		printForm.dialog( "open" );
		$("#date_zakaz").datepicker({
			dateFormat: 'yy-mm-dd', 
			showOn: "button",
			buttonImage: "/img/admin/icons/calendar.gif",
			buttonImageOnly: true,
			onSelect: function(date_zakaz) {
				$.ajax({
					type: "GET",
					dataType: "json",
					url: b_dataprint,
					data: "date_zakaz="+date_zakaz,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if (key == "html") {
								$('#print_table_elem').empty();
								$('#print_table_elem').html(res[key]);
							}
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});				
			}
		});

		$.ajax({
			type: "GET",
			dataType: "json",
			url: b_dataprint,
			data: "date_zakaz="+date_zakaz,
			success:function (res) {//возвращаемый результат от сервера
				for(var key in res){ 
					if (key == "html") {
						$('#print_table_elem').empty();
						$('#print_table_elem').html(res[key]);
					}
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});

		
	}	

	// Инициализация формы добавления и редактирования данных					
	catalogForm.dialog({
		autoOpen: false,
		width: 910,
		minHeight: 'auto',
		title: "Карточка заказа",
		buttons: [			
				{
				text: "Сохранить",
				click: function() {
					if (stop_save == false) {
						var str = $("#catalog_form").serialize();
							$.ajax({
								type: "POST",
								url: b_editUrl,
								data: str,
								});
							table.trigger("reloadGrid");
							$( this ).dialog( "close" );											
					} else {
						showMsgBox('Выбран курьер, запрещено менять данные!', "white", "center");
					}				
						
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
		
	delForm.dialog({
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