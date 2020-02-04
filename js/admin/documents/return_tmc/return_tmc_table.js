$(document).ready(function() {
	var baseUrl			= "/adminpanel/return_tmc/";
	var accessURL		= "/adminpanel/adminaccess/";

	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var dialogEdit 		= $("#dialog-edit");
	var dialogDel 		= $("#dialog-del");
	var tabs			= $(".admin-tabs");	
	var dialogOplata 	= $("#dialog-oplata");	
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
			$('#t_le_table').height(28);
			
			if (access['return_tmc_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['return_tmc_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['return_tmc_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';
			
			html = '<label>ДАТА ОТ </label><input type="text" id="date_ot"><label>ДО </label><input type="text" id="date_do"><a href="" class="date_btn btn-cur">Найти</a>';
			
			select = '<select name="kontragent" class="sel-select" id="sel-kontragent"></select>';	
			
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
        colNames:[' ','№ накладной','Автор','Сумма','Валюта','Сумма $','От кого','Куда','Дата'],
        colModel :[
			{name:'actedit',			index:'actedit', 			width:15,   align:'center'},
            {name:'nomer_nakladnoy', 	index:'nomer_nakladnoy', 	width:35, 	align:'center'},
            {name:'author', 			index:'author', 			width:150, 	align:'left'},
			{name:'sum', 				index:'sum', 				width:55, 	align:'center'},
			{name:'valute', 			index:'valute', 			width:25, 	align:'center'},					
			{name:'sum_ye', 			index:'sum_ye', 			width:55, 	align:'center'},			
            {name:'id_kontragent', 		index:'id_kontragent', 		width:150, 	align:'left'},
            {name:'id_sklad', 			index:'id_sklad', 			width:50, 	align:'left'},
			{name:'date_create', 		index:'date_create', 		width:90, 	align:'center'},
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,		
		ondblClickRow: function(id) {
			if (access['return_tmc_edit']==1) gridEdit();
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

			if ($("#sel-kontragent option").length == 0) $("#sel-kontragent").append(getSelectHtml('kontragent'));
		
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
		var kontragent = $("#sel-kontragent").val(),
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val();
		
		table.jqGrid('setGridParam',{url: loadDataUrl+'?kontragent='+kontragent
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
	function gridAdd() {
		$("#form").trigger("reset");
		$("#form-sklad-tovar").trigger("reset");
		tabs.tabs({active:'0'});	
		tabs.tabs( "disable", 1 );	
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
				$.ajax({
					type: "POST",
					dataType: "json",
					url: openDataUrl,
					data: "id="+id,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 						
							$('#val_'+key).val(res[key]);
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
				text: "Подтвердить возврат",
				click: function() {
					var str = $("#form").serialize();
					if (access['id']==7) blockEdit = 0; else blockEdit = 1;
					//if (access['id']==1) blockEdit = 0; else blockEdit = 1;
					if (blockEdit==1) {
						blockEdit = 0;
						showMsgBox('Вам запрещено использование данной функции в соответствии с правами доступа. Подтверждать приход товара на склад имеет право только кладовщик!', "white", "center");
					} else {
						if ($("#val_id_valute").val()!=0 && $("#val_id_suppliers").val()!=0) {
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
							showMsgBox('Поля контрагент и валюта обязательны для заполнения.!', "white", "center");
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
							data: "id="+$("#val_id_delivery_tmc").val()+"&id_kontragent="+$("#val_id_kontragent").val(),
							success:function (res) {//возвращаемый результат от сервера
								for(var key in res){ 						
									$('.valo_valute').html(res['valute']);
									$('#valo_'+key).val(res[key]);
									$('#valo_id_kontragent').val($("#val_id_kontragent").val());
								}							
								dialogOplata.dialog("open");						
							},							
						});						
						
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
						if ($("#val_id_valute").val()!=0 && $("#val_id_suppliers").val()!=0) {
						var str = $("#form").serialize();
						$.ajax({
							type: "POST",
							url: editDataUrl,
							dataType: "json",
							data: str+"&status=1",
							success:function (res) {//возвращаемый результат от сервера
								if (res['action']=='add') {		
									$("#val_id").val(res['last_id']);
									$("#val_id_delivery_tmc").val(res['last_id_delivery']);
									$("#action_pole").val("edit");
									tabs.tabs( "enable", 1 );
								} else {
									table.trigger("reloadGrid");
								}						
							},							
						});	
						} else {
							showMsgBox('Поля контрагент и валюта обязательны для заполнения.!', "white", "center");
						}		
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