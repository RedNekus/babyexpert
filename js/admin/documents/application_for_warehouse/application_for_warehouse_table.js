$(document).ready(function() {
	var baseUrl				= "/adminpanel/application_for_warehouse/";
	var accessURL			= "/adminpanel/adminaccess/";
	
	var tableTovar	    	= $('#le_tableTovar');	
	var urlTovar			= baseUrl+"loadwarehouse";
	
	var tablePager			= '#le_tablePager';
	var table 				= $('#le_table');
	var t_le_table 			= "#t_le_table";	
	var dialogEdit 			= $("#dialog-edit");
	var printForm 			= $("#print_dialog");
	var b_loadUrl			= baseUrl+"load";
	var b_editUrl 			= baseUrl+"edit";
	var b_openUrl 			= baseUrl+"open";
	var b_dataprint 		= baseUrl+"dataprint";
	
	var b_dataAccess		= accessURL+"access";
	var RefreshOstatkiUrl	= baseUrl+"refresh_ostatki";
	
	var lastsel;
	var access;
	var scrollPosition;
	var array 				= [];
	var height 				= $(window).height()-135;
	
	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
	
			$(t_le_table).height(28);

			if (access['application_for_warehouse_edit']==1) edit_g = '<div id="editdata" class="button edit"></div>';
			else edit_g = '<div class="button edit noactive"></div>';
			
			if (access['application_for_warehouse_edit']==1) print = '<div id="printdata" class="button print"></div>';
			else print = '<div class="button print noactive"></div>';
			
			date_ot =  '<label>ПОИСК ПО ДАТЕ: ОТ </label><input type="text" id="date_ot">';
			date_do =  '<label>ДО </label><input type="text" id="date_do">';
			date_btn =  '<a href="" class="date_btn btn-cur">Найти</a>';
			
			select = getSelectHtml(baseUrl)+'<a href="" class="btn-cur" id="btn-couriers">Применить</a>'; 

			$(t_le_table).append('<div class="toolbar_top" id="select_form"><div class="toolbar-block">' + edit_g + print + date_ot + date_do + date_btn + select + '</div></div>');
			
			// Обработка события кнопки Редактировать	
			$("#editdata").click(function(){
				gridEdit();
			});
			
			table.delegate(".editdata", "click", function(){
				gridEdit($(this).attr('rel'));
			});		
			
			table.delegate(".savedata", "click", function(){
				saveRowData($(this).attr('rel'));
			});	
		
			// Обработка события кнопки Печать	
			$("#printdata").click(function(){
				gridPrint();
			});	
			
		}						
	});
	
	function getSelectHtml(url) {
		var re = '';
		$.ajax({type: "POST",url: url+"getselecthtml", async: false, data: "",
			success:function (res) {//возвращаемый результат от сервера	
				re = res;
			}						
		});	
		return re;	
	}

	function reloadTableWidthParam() {
		var date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val();

			table.jqGrid('setGridParam',{url: b_loadUrl+'?&date_ot='+date_ot+'&date_do='+date_do,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");

	}


	table.jqGrid({
		url:b_loadUrl,
		datatype: 'json',
        mtype: 'GET',
		//cmTemplate:{resizable:false},
		autowidth: true,
		height: height,
        colNames:[' ','№з','К',' ','Д','М','Кол','Наименование','Адрес','Безнал','Сумма $','Сумма byr','Кол-во','Отгрузил','Примечание','Примечание менеджера','Примечание кладовщика','Дата доставки'],
        colModel :[
			{name:'act',			index:'act', 			width:22,   align:'center', search:false, sortable:false},
            {name:'nomer_zakaza', 	index:'nomer_zakaza', 	width:15, 	align:'center', search:true,  editable: false },
            {name:'id_couriers',	index:'id_couriers',	width:25, 	align:'center', search:false, editable: true,  edittype:"select"},	
			{name:'actsave',		index:'actsave', 		width:20,   align:'center', search:false, sortable:false},			
            {name:'id_diler', 		index:'id_diler', 		width:40, 	align:'center', search:false, editable: false },			
            {name:'manager', 		index:'manager', 		width:20, 	align:'center', search:false, editable: false },			
            {name:'kolvo_only',		index:'kolvo_only', 	width:15, 	align:'center', search:false, editable: false },
            {name:'name',			index:'name', 			width:120, 	align:'left', 	search:false, editable: false },
            {name:'adres', 			index:'adres', 			width:90, 	align:'left', 	search:false, editable: false },
            {name:'beznal', 		index:'beznal', 		width:20, 	align:'center', search:false, editable: false },
            {name:'sum', 			index:'sum', 			width:30, 	align:'center', search:false, editable: false, sorttype:'int', 		formatter:'int',summaryType:'sum' },
            {name:'sumbyr', 		index:'sumbyr', 		width:40, 	align:'center', search:false, editable: false, sorttype:'int', 		formatter:'int',summaryType:'sum' },
            {name:'kolvo',			index:'kolvo',			width:25,	align:'center', search:false, editable: false, sorttype:'int', 		formatter:'int',summaryType:'sum' },           
            {name:'shipped',		index:'shipped',		width:25,	align:'center', search:false },           
		    {name:'comment', 		index:'comment', 		width:90, 	align:'left', 	search:false },
		    {name:'comment_m', 		index:'comment_m', 		width:90, 	align:'left', 	search:false },
		    {name:'comment_w', 		index:'comment_w', 		width:90, 	align:'left', 	search:false },
			{name:'date_dostavka', 	index:'date_dostavka', 	width:45,   align:'center', search:false, formatter: getDayWeek},
			],
        pager: tablePager,
		toolbar: [true,"top"],		
        sortname: 'nomer_zakaza',
        sortorder: 'asc',
		rowNum:100,	
		viewrecords: true,	
		rowList:[100,500,1000,2000],
		multiselect: true,
		onSelectAll	: function (aRowids, status) {
			array = [];
			if (status) {
				var i = aRowids.length;
					while(i--) {
						if (!ina(aRowids[i],array)) { array.push(aRowids[i]);	}
					}		
			} else {
				array = [];	
			}		
		},			
		gridComplete : function() {
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

			if (scrollPosition)	table.closest(".ui-jqgrid-bdiv").scrollTop(scrollPosition);
			
		},		
		grouping:true,
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
		onSelectRow: function(id, status) {

			if (status) {
				if (!ina(id,array)) { array.push(id);	}
			} else {
				if (ina(id,array)) {
					for (i in array) {
						if(id == array[i]){ array.splice(i,1); }
					}
				}	
			}
		},		
		onCellSelect: function (id, iCol, cellcontent, e) {
			if (iCol == 3) {
				if (access['application_for_warehouse_edit']==1) {
					if(id && id!==lastsel){
						table.jqGrid('restoreRow',lastsel);
						params = {
							"keys" : true,
							"aftersavefunc" : function(id, res, option) {

								var arr = JSON.parse(res.responseText);
								if (arr['succes']==true) {
									if (arr['dialog_display']==true) {
										gridEdit();	
									} else {
										table.trigger("reloadGrid");
									}							
								} else {
									table.jqGrid('restoreRow',lastsel);
									if (arr['message']) showMsgBox(arr['message'], "white", "center");									
								}


							},
							"mtype" : "POST"
						}					
						table.jqGrid('editRow',id,params);
						lastsel=id;
					}
				}
			}
		},	
		loadComplete: function(){
			table.setColProp('id_couriers',{'editoptions': {'value':getSelectValue(baseUrl)}});
		},		 
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30,
		editurl:b_editUrl	
    }).jqGrid('navGrid', tablePager,{
		refresh: true, 
		add: false, 
		del: false, 
		edit: false, 
		search: true, 
		view: true},{},{},{},{
			closeOnEscape:true, 
			multipleSearch:false, 
			closeAfterSearch:true},{}
		).jqGrid('navButtonAdd', tablePager,{
			caption: 'Обновить остатки',
			title: 'Обновить остатки',
			buttonicon: 'ui-icon-refresh',
				onClickButton: function(){					
					$.ajax({type: "POST", data: "action=2", url: RefreshOstatkiUrl, dataType: "json",
						success:function (res) {//возвращаемый результат от сервера
							showMsgBox(res, "white", "center");
						},
						error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
					});				
				},
			position:'last'					
		});

	function ina(looking_for, list){
		for(i in list){
			if(looking_for == list[i]){
				return true;
			}
		}
		return false;
	}
		
	// Функция редактирования данных
	function gridEdit(gsr){
		if (typeof(gsr) != "undefined") id = gsr;
		else id = table.jqGrid('getGridParam','selrow');
			if(id){
				$.ajax({
					url: b_openUrl,
					dataType: "json",
					type: "POST",	
					data: "id="+id,
					success:function (res) {//возвращаемый результат от сервера		
						for(var key in res){ 	
							if ($.inArray(key, Array('was', 'delivered', 'passed', 'beznal')) != -1) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }	
							} else {							
								$('#val_'+key).val(res[key]);	
							}							
						}	
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});			
				tableTovar.jqGrid('setGridParam',{url: urlTovar+"?id_client="+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");	
				$("#action").attr({value: "edit"});
				dialogEdit.dialog( "open" );
			
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}

	function saveRowData(id) {

		params = {
			"keys" : true,
			"aftersavefunc" : function(id, res, option) {

				var arr = JSON.parse(res.responseText);
				if (arr['succes']==true) {
					table.trigger("reloadGrid");						
				} else {
					table.jqGrid('restoreRow',id);
					if (arr['message']) showMsgBox(arr['message'], "white", "center");
					table.trigger("reloadGrid");
				}


			},
			"mtype" : "POST"
		}					
		table.jqGrid('saveRow',id,params);	
		
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
			cur_id = $("#select-html").val();
				
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
	dialogEdit.dialog({
		autoOpen: false,
		width: 910,
		minheight: 'auto',
		title: "Примечание",
		close: function( event, ui ) {					
			$( this ).dialog( "close" );
			scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
			array = [];
			table.trigger('reloadGrid');
		},
		buttons: [
			{
				text: "Сохранить",
				click: function() {
					var str = $("#form").serialize(),
						id  = $("#val_id").val();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							dataType: "json",
							data: str,
							success:function (res, f) {//возвращаемый результат от сервера		
								if (f === "success" && res['succes']) {					
									dialogEdit.dialog( "close" );	
									scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
									array = [];
									table.trigger('reloadGrid');		
								} else {
									showMsgBox('Заполните примечание! '+res['message'], "white", "center");
								}
							},
							error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}									
						});
				}
			},
			{
				text: "Отмена",
				click: function() {
					$( this ).dialog( "close" );
					scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
					array = [];
					table.trigger('reloadGrid');
					}
			}
		]
	});

	
	$(t_le_table).delegate(".date_btn", "click", function(){
		reloadTableWidthParam();
		return false;
	});	
	
	$(t_le_table).delegate("#btn-couriers", "click", function(){
		var id_couriers = $("#select-html").val();
		$.ajax({
			type: "POST",
			url: b_editUrl,
			dataType: "json",
			data: "array="+array+"&id_couriers="+id_couriers,
			success:function (res, f) {//возвращаемый результат от сервера		
				if (f === "success" && res['succes']) {					
					if (res['message']) showMsgBox(res['message'], "white", "center");	
				} 
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}			
		});
		table.trigger("reloadGrid");
		array = [];
		return false;
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
				text: "Cancel",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
		
});