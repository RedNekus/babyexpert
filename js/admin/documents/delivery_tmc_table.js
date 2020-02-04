$(document).ready(function() {
	var baseUrl			= "/adminpanel/delivery_tmc/";
	var accessURL		= "/adminpanel/adminaccess/";

	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var dialogEdit 		= $("#dialog-edit");
	var dialogDel 		= $("#dialog-del");
	var dialogOplata 	= $("#dialog-oplata");
	var tabs			= $(".admin-tabs");	
	var loadDataUrl	 	= baseUrl+"load";
	var editDataUrl 	= baseUrl+"edit";
	var openDataUrl 	= baseUrl+"open";
	var blockEditDataUrl= baseUrl+"block_edit";
	var oplataDataUrl	= baseUrl+"oplata";
	var oplataHtmlUrl	= baseUrl+"get_html_oplata";
	var b_dataAccess	= accessURL+"access";
	var loadTableUrl	= baseUrl+"load_sklad_tovar";	
	var blockEdit		= 0;
	var access;
	var height 			= $(window).height()-135;

	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height('auto');
			
			if (access['delivery_tmc_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['delivery_tmc_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['delivery_tmc_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';

	
			html = '<label>ДАТА ОТ </label><input type="text" id="date_ot"><label>ДО </label><input type="text" id="date_do"><a href="" class="date_btn btn-cur">Найти</a>';

			select = '<select name="suppliers" class="sel-select" id="sel-suppliers"></select>';			
			select += '<select name="valute" class="sel-select" id="sel-valute"></select>';		
			select += '<select name="author" class="sel-select" id="sel-author"></select>';			
			select += '<select name="sklad" class="sel-select" id="sel-sklad"></select>';			
			select += '<select name="storekeepers" class="sel-select" id="sel-storekeepers"></select>';			
			select += '<select name="carriers" class="sel-select" id="sel-carriers"></select>';						

			$('#t_le_table').append('<div class="toolbar_top"><div class="toolbar-block">' + add + edit + del + html + select + '</div></div>');		

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
        colNames:[' ','№ накладной','Вид документа','Автор','Сумма','Валюта','Сумма $','Склад','Кладовщики','Перевозчики','Поставщики','Дата'],
        colModel :[
			{name:'actedit',			index:'actedit', 			width:15,   align:'center'},
            {name:'nomer_nakladnoy', 	index:'nomer_nakladnoy', 	width:35, 	align:'center'},
            {name:'status', 			index:'status', 			width:75, 	align:'left'},
			{name:'author', 			index:'author', 			width:35, 	align:'left'},			
			{name:'sum', 				index:'sum', 				width:55, 	align:'center'},
			{name:'valute', 			index:'valute', 			width:25, 	align:'center'},					
			{name:'sum_ye', 			index:'sum_ye', 			width:55, 	align:'center'},				
			{name:'id_sklad', 			index:'id_sklad', 			width:90, 	align:'center'},
			{name:'id_storekeepers', 	index:'id_storekeepers', 	width:90, 	align:'center'},
			{name:'id_carriers', 		index:'id_carriers', 		width:90, 	align:'center'},
			{name:'id_suppliers', 		index:'id_suppliers', 		width:90, 	align:'center'},
			{name:'date_delivery', 		index:'date_delivery', 		width:90, 	align:'center'},
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,		
		ondblClickRow: function(id) {
			if (access['delivery_tmc_edit']==1) gridEdit();
		},
		gridComplete : function() {	
			var ids = table.jqGrid('getDataIDs');
			for(var i=0;i < ids.length;i++) {
				var cl = ids[i];
				be = '<div" rel="'+cl+'" class="button edit editdata"></div>';
				table.jqGrid('setRowData',ids[i],{actedit:be});
			}				

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

			if ($("#sel-author option").length == 0) $("#sel-author").append(getSelectHtml('author'));
			if ($("#sel-valute option").length == 0) $("#sel-valute").append(getSelectHtml('valute'));
			if ($("#sel-sklad option").length == 0) $("#sel-sklad").append(getSelectHtml('sklad'));
			if ($("#sel-carriers option").length == 0) $("#sel-carriers").append(getSelectHtml('carriers'));
			if ($("#sel-storekeepers option").length == 0) $("#sel-storekeepers").append(getSelectHtml('storekeepers'));
			if ($("#sel-suppliers option").length == 0) $("#sel-suppliers").append(getSelectHtml('suppliers'));

		
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
	
	function getSelectHtml(method) {
		var re = '';
		$.ajax({type: "POST",url: baseUrl+'getselecthtml/', async: false, data: 'method='+method,
			success:function (res) {//возвращаемый результат от сервера	
				re = res;
			}						
		});	
		return re;	
	}
		
	function reloadTable() {
		var carriers = $("#sel-carriers").val(),
			storekeepers = $("#sel-storekeepers").val(),
			suppliers = $("#sel-suppliers").val(),
			sklad = $("#sel-sklad").val(),
			valute = $("#sel-valute").val(),
			author = $("#sel-author").val(),
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val();
		
		table.jqGrid('setGridParam',{url: loadDataUrl+'?carriers='+carriers
			+'&storekeepers='+storekeepers
			+'&author='+author
			+'&valute='+valute
			+'&sklad='+sklad
			+'&suppliers='+suppliers
			+'&date_ot='+date_ot
			+'&date_do='+date_do
			+'&status='+status}).trigger("reloadGrid");		
	}
		
	$('#t_le_table').delegate(".sel-select", "change", function() {
		reloadTable()
	});	
	
	$("#t_le_table").delegate(".date_btn", "click", function() {
		reloadTable()
		return false;
	});
						
	table.delegate(".editdata", "click", function(){
		var id = $(this).attr('rel');
		gridEdit(id)
	});	
	
	// Функция добавление итема в таблицу		
	function gridAdd(){
		$("#form").trigger("reset");
		$("#form-sklad-tovar").trigger("reset");
		tabs.tabs({active:'0'});	
		tabs.tabs( "disable", 1 );
		tabs.tabs( "disable", 2 );		
		tabs.tabs( "disable", 3 );		
		$("#action_pole").attr({value: "add"});
		dialogEdit.dialog( "open" );
		}

	// Функция редактирование итема в тиблице	
	function gridEdit(gsr){
		if (typeof(gsr) != "undefined") id = gsr;
		else id = table.jqGrid('getGridParam','selrow');
			if(id){
			tabs.tabs({active:'0'});			
			tabs.tabs( "enable", 1 );
			tabs.tabs( "enable", 2 );			
			tabs.tabs( "enable", 3 );			
				$.ajax({
					type: "POST",
					dataType: "json",
					url: openDataUrl,
					data: "id="+id,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res) {
							if (key == "beznal") {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else {							
								$('#val_'+key).val(res[key]);
							}
							blockEdit = res['block_edit'];
							if (blockEdit==1) {
								tabs.tabs( "disable", 1 );
							}
							if (access['id']==1) {
								tabs.tabs( "enable", 1 );
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
		width: 1200,
        minHeight: 'auto',
		position: 'top',
		modal: true,
		resizable: false,
		title: "Свойства",
		buttons: [
				{
				text: "Подтвердить",
				click: function() {
					
					if ($("#val_return_tmc").val()==1) {
						showMsgBox("Подтвердить возврат ТМЦ возможно только через модуль Возврат ТМЦ!", "white", "center");
						return;
					}
					
					var str = $("#form").serialize();
					if (access['id']==7) blockEdit = 0; else blockEdit = 1;
					//if (access['id']==1) blockEdit = 0; else blockEdit = 1;
					if (blockEdit==1) {
						blockEdit = 0;
						showMsgBox('Вам запрещено использование данной функции в соответствии с правами доступа. Подтверждать приход товара на склад имеет право только кладовщик!', "white", "center");
					} else {
						if ($("#val_id_valute").val()!=0 && $("#val_kurs").val()>0 && $("#val_id_suppliers").val()!=0) {
						var id = $("#val_id").val();
						$.ajax({
							type: "POST",
							url: blockEditDataUrl,
							dataType: "json",
							data: "block_edit=1&"+str,
							success:function (res, f) {
								if (f === "success" && res['succes']) {
									dialogEdit.dialog( "close" );
									table.trigger("reloadGrid");	
								} else {
									showMsgBox(res['message'], "white", "center");
								}								
							},							
						});
						} else {
							showMsgBox('Поля поставщик, валюта и курс обязательны для заполнения!', "white", "center");
						}						
					}
					
				}
				},
				{
				text: "На склад",
				click: function() {
					if (access['id']==1) blockEdit=0;
					if (blockEdit==1) {
						showMsgBox('Редактирование запрещено!', "white", "center");
					} else {	
						if ($("#val_id_valute").val()!=0 && $("#val_kurs").val()>0 && $("#val_id_suppliers").val()!=0) {					
						var str = $("#form").serialize();
						$.ajax({
							type: "POST",
							url: editDataUrl,
							dataType: "json",
							data: str+"&status=2",
							success:function (res) {//возвращаемый результат от сервера
								if (res['action']=='add') {		
									$("#val_id").val(res['last_id']);
									$("#action_pole").val("edit");
									tabs.tabs( "enable", 1 );
									tabs.tabs( "enable", 2 );
									tabs.tabs( "enable", 3 );
								} else {
									table.trigger("reloadGrid");
								}						
							},							
						});	
						} else {
							showMsgBox('Поля поставщик, валюта и курс обязательны для заполнения!', "white", "center");
						}						
					}
					}
				},				
				{
				text: "Сохранить",
				click: function() {
					if (access['id']==1) blockEdit = 0;
					if (blockEdit==1) {
						showMsgBox('Редактирование запрещено!', "white", "center");
					} else {
						if ($("#val_id_valute").val()!=0 && $("#val_kurs").val()>0 && $("#val_id_suppliers").val()!=0) { 	
						var str = $("#form").serialize();
						$.ajax({
							type: "POST",
							url: editDataUrl,
							dataType: "json",
							data: str+"&status=1",
							success:function (res) {//возвращаемый результат от сервера
								if (res['action']=='add') {		
									$("#val_id").val(res['last_id']);
									$("#action_pole").val("edit");
									tabs.tabs( "enable", 1 );
									tabs.tabs( "enable", 2 );
									tabs.tabs( "enable", 3 );
								} else {
									table.trigger("reloadGrid");
								}						
							},							
						});
						} else {
							showMsgBox('Поля поставщик, валюта и курс обязательны для заполнения!', "white", "center");
						}
					}
					}
				},
				{
				text: "Подтвердить оплату",
				click: function() {
					if (access['id']==1 && blockEdit==0) {
						showMsgBox('Действие запрещено!', "white", "center");
					} else {
						$.ajax({
							type: "POST",
							url: oplataHtmlUrl,
							dataType: "json",
							data: "id="+$("#val_id").val(),
							success:function (res) {//возвращаемый результат от сервера
								for(var key in res){ 						
									$('.valo_valute').html(res['valute']);
									$('#valo_'+key).val(res[key]);
					
									$('#id_delivery_tmc').val(res['id']);
								}							
								dialogOplata.dialog("open");						
							},							
						});						
						
					}
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
		
	function funcOplata() {
		var str = $("#form-oplata").serialize();
		$.ajax({
			type: "POST",
			url: oplataDataUrl,
			dataType: "json",
			data: str,
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {					
					dialogEdit.dialog('close');	
					dialogOplata.dialog('close');	
					table.trigger("reloadGrid");					
				} else {
					showMsgBox(res['message'], "white", "center");
				}										
			},							
		});		
	}
		
	dialogOplata.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 580,
		minHeight: 'auto',
		buttons: [
			{
				text: "Ок",
				click: funcOplata
			},
			{
				text: "Отмена",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
	
});