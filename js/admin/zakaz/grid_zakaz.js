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
	var garantDialog	= $("#garant-dialog");
	var b_gridUrl	 	= baseUrl+"load";
	var b_editUrl 		= baseUrl+"edit";
	var b_openUrl 		= baseUrl+"open";
	var b_dataprint 	= baseUrl+"dataprint";
	var b_datagarantUrl	= baseUrl+"datagarant";
	var b_dataAccess	= accessURL+"access";
	var lastsel;
	var scrollPosition;
	var height 			= $(window).height()-135;
	
	var stop_save  		= false;
	
	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['zakaz_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['zakaz_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['zakaz_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';
			
			if (access['zakaz_review']==1) print = '<div id="printdata" class="button print"></div>';
			else print = '<div class="button print noactive"></div>';

			$('#t_le_table').append(add + edit + del  + print );		

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
		
			// Обработка события кнопки Печать	
			$("#printdata").click(function(){
				gridPrint();
			});	
			
			// Обработка события кнопки Печать	
			$("#print_dialog").delegate(".garantdata", "click", function(){	
				gridGarant($(this).attr('rel'));
			});	
				
		}						
	});
	
	table.jqGrid({
		url:b_gridUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:[' ','№з','Курьер','Дилер','Менеджер','Кол','Наименование товара', 'Телефон', 'Адрес', 'Безнал', 'Сумма в y.e.', 'Коррекция суммы', 'Кол-во', 'Дата заказа', 'Дата обработки', 'Дата доставки', 'Примечание', 'Обработан'],
        colModel :[	
			{name:'act',			index:'act', 			width:12,  align:'center', search:false, sortable:false},
			{name:'nomer_zakaza', 	index:'nomer_zakaza', 	width:15,  align:'center', search:true,  searchoptions:{sopt:['cn']}},
			{name:'id_couriers', 	index:'id_couriers',	width:35,  align:'center', search:false  },
			{name:'id_diler', 		index:'id_diler',		width:35,  align:'left',   search:false, },
			{name:'id_adminuser', 	index:'id_adminuser',	width:35,  align:'left',   search:false, },
			{name:'kolvo_only', 	index:'kolvo_only',		width:15,  align:'center', search:false, },
			{name:'id_item', 		index:'id_item',		width:220, align:'left',   search:true,  searchoptions:{sopt:['cn']}, sortable:false},
			{name:'phone', 			index:'phone',			width:45,  align:'left',   search:true,  searchoptions:{sopt:['cn']}},
			{name:'adres', 			index:'adres',			width:110, align:'left',   search:false, sortable:false},				
			{name:'beznal', 		index:'beznal',			width:30,  align:'center', search:false, sortable:false},				
			{name:'summ', 			index:'summ', 			width:50,  align:'center', search:false, sortable:false, sorttype:'int', formatter:'int',summaryType:'sum'},	
			{name:'summ_skidka', 	index:'summ_skidka', 	width:50,  align:'center', search:false, sortable:false, sorttype:'int', formatter:'int',summaryType:'sum'},	
			{name:'kolvo', 			index:'kolvo', 			width:20,  align:'center', search:false, sortable:false, sorttype:'int', formatter:'int',summaryType:'sum' },	
			{name:'date_zakaz', 	index:'date_zakaz', 	width:45,  align:'center', search:false, },	
			{name:'date_active', 	index:'date_active', 	width:45,  align:'center', search:false, },	
			{name:'date_dostavka', 	index:'date_dostavka', 	width:45,  align:'center', search:false, formatter: getDayWeek},	
			{name:'comment', 		index:'comment', 		width:150, align:'left',   search:false, },	
			{name:'active', 		index:'active', 		width:30,  align:'center', search:false, sorttype:'char',summaryType:'count', summaryTpl : '({0}) шт'},	
			],
        pager: pager,
        sortname: 'date_dostavka',
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
			groupField : ['date_dostavka'],
			groupColumnShow : [true], 
			groupText : ['<b style="color: green; font-size: 18px;">{0}</b>'],
			groupCollapse : false,
			groupOrder: ['desc'],
			groupSummary : [true],
			showSummaryOnHide: true,
			groupDataSorted : true
		},	
		gridComplete : function() {
			if (scrollPosition)	table.closest(".ui-jqgrid-bdiv").scrollTop(scrollPosition);
		},		
		editurl:b_editUrl
        }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: false});
		
		//table.jqGrid('hideCol',["date_active"]);   /* Прячем стобцы */
	
		table.click(function() {
			var id = lastsel;
			$("#"+id+"_id_couriers").blur(function() {
				table.jqGrid('saveRow', id);
			});
			
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
							if ($.inArray(key, Array('samovivoz', 'samovivoz_ofice', 'dostavka', 'print_ready', 'dumayut', 'beznal')) != -1) {
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
	
	$("#print_dialog").delegate("#select-html", "change", function(){
		var date_zakaz = $("#date_zakaz").val();
			cur_id = $(this).val();
			
			$.ajax({
				type: "GET",
				dataType: "json",
				url: b_dataprint,
				data: "date_zakaz="+date_zakaz+"&cur_id="+cur_id,
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
	
	});
	
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
	
	// Функция печати
	function gridGarant(id) {
		$('#garant-talon').empty();
		
		garantDialog.dialog( "open" );
		
		$.ajax({
			type: "GET",
			dataType: "json",
			url: b_datagarantUrl,
			data: "id="+id,
			success:function (res) {//возвращаемый результат от сервера
				$('#garant-talon').empty();
				$('#garant-talon').html(res);	
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
		close: function( event, ui ) {					
			$( this ).dialog( "close" );
			scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
			table.trigger('reloadGrid');
		},		
		buttons: [			
				{
				text: "Сохранить",
				click: function() {
					if (stop_save == false) {
						var str = $("#catalog_form").serialize();
							$.ajax({
								type: "POST",
								url: b_editUrl,
								dataType: "json",
								data: str,
								success:function (res) {//возвращаемый результат от сервера
									if (res['success']) {
										catalogForm.dialog( "close" );
										scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
										table.trigger("reloadGrid");
									} else {
										if (res["msg"]) showMsgBox(res["msg"], "white", "center");
									}
								},
								error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}							
							});
											
					} else {
						showMsgBox('Выбран курьер, запрещено менять данные!', "white", "center");
					}				
						
					}
				},					
				{
				text: "Cancel",
				click: function() {
					$( this ).dialog( "close" );
					scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
					table.trigger('reloadGrid');
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
	
	function printBlock() {
		$("#print_table").printElement({
            overrideElementCSS:[
				'content.css',
				{ href:'/css/admin/content.css',media:'print'}]
            });
	}

	
	printForm.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 1030,
		minHeight: 'auto',
		buttons: [
			{
				text: "Печать",
				click: printBlock
			},
			{
				text: "Отмена",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
	
	function garantBlock() {
		$("#garant-talon").printElement({
            overrideElementCSS:[
				'print.css',
				{ href:'/css/admin/print.css',media:'print'}]
            });
	}
	
	garantDialog.dialog({
		autoOpen: false,
		title: "Гарантийный талон",
		width: 880,
		position: 'top',
		minHeight: 'auto',
		buttons: [
			{
				text: "Печать",
				click: garantBlock
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