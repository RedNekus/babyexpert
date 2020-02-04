$(document).ready(function() {
	var baseUrl				= "/adminpanel/couriers/";
	var accessURL			= "/adminpanel/adminaccess/";
	
	var tableTovar	    	= $('#TableTovar');	
	var urlTovar			= baseUrl+"loadcouriers";
	
	var tablePager			= '#TableCatalogPager';
	var table 				= $('#TableCatalog');
	var CharGroupForm 		= $("#couriersForm");
	var CharGroupFormDel 	= $("#couriersFormDel");	
	var AddCharDialog		= $("#AddCouriersDialog");
	var tree				= $("#tree_couriers");
	var b_loadUrl			= baseUrl+"load";
	var b_editUrl 			= baseUrl+"edit";
	var b_saveUrl 			= baseUrl+"save";
	var b_openUrl 			= baseUrl+"datahandling";
	var b_dataHandlingChar	= baseUrl+"datahandlingchar";
	var b_adoptedupdate		= baseUrl+"adoptedupdate";
	var b_zpupdate			= baseUrl+"zpupdate";
	var b_resetdata			= baseUrl+"resetdata";
	var b_dataprint 		= baseUrl+"dataprint";	
	var b_dataAccess		= accessURL+"access";
	var printForm 			= $("#print_dialog");	
	var scrollPosition;
	var lastsel;
	var array 				= [];
	var adopted				= 0;
	
	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_TableCatalog').height(56);

			if (access['couriers_edit']==1) edit_g = '<div id="editdata" class="button edit"></div>';
			else edit_g = '<div class="button edit noactive"></div>';
			
			date_ot =  '<label>ОТ </label><input type="text" id="date_ot">';
			date_do =  '<label>ДО </label><input type="text" id="date_do">';
			date_btn =  '<a href="" class="date_btn btn-cur">Найти</a>';
			
			if (access['id']==1 || access['id']==2 || access['id']==4 || access['id']==5) {
				btn_ok = '<a href="#" class="btn-cur" id="btn-apply">Принять</a>';
				print = '<div id="printdata" class="button print"></div>';
				adopted_html = '<div class="block-adopted"><input type="checkbox" name="adopted" id="chk-adopted" /><label for="chk-adopted">История</label></div>';
				btn_reset = '<a href="#" class="btn-cur" id="btn-reset">Вернуть/Обнулить</a>';
				btn_zp = '<a href="#" class="btn-cur" id="btn-zp" style="display:none;">Оплатить</a>';
			} else {
				btn_ok = '';
				print = '';
				adopted_html = '';
				btn_reset = '';
				btn_zp = '';
			}

			input_html = '<div class="block-input"><input type="text" class="total_usd"/><input type="text" class="total_blr"/><label>ЗП:</label><input type="text" class="total_zp"/><input type="text" class="total_zp_blr"/></div>';
			
			$('#t_TableCatalog').append('<div class="toolbar_top" id="select_form"><div class="toolbar-block">' + edit_g + print + btn_ok + adopted_html + btn_zp + input_html + date_ot + date_do + date_btn + btn_reset + '</div></div>');
			
			// Обработка события кнопки Редактировать	
			$("#editdata").click(function(){
				gridEdit();
			});
		
			// Обработка события кнопки Печать	
			$("#printdata").click(function(){
				gridPrint();
			});	
			
			$("#TableCatalog").delegate(".editdata", "click", function(){
				gridEdit($(this).attr('rel'));
			});	
			
		}						
	});
	
	function reloadTableWidthParam() {
		var id 	    = tree.find(".active").attr('id'),
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val();

			table.jqGrid('setGridParam',{url: b_loadUrl+'?id_tree='+id+'&adopted='+adopted+'&date_ot='+date_ot+'&date_do='+date_do,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
		
		//loadajax();
	}
	
	// при нажатии на ссылку на дереве
	tree.delegate(".linkrel", "click", function(){

		tree.find(".active").removeClass("active");
		$(this).addClass('active');	
		reloadTableWidthParam();
	});
	

	table.jqGrid({
		url:b_loadUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
        colNames:[' ','№з','Наименование','Адрес','К','Сумма $','Сумма byr','Сдал','Принял','ЗП $','ЗП byr','ИТОГ $','ИТОГ byr','Примечание','Дата доставки'],
        colModel :[
			{name:'act',			index:'act', 			width:12,   align:'center', search:false, sortable:false},
            {name:'nomer_zakaza', 	index:'nomer_zakaza', 	width:25, 	align:'center', search:true,  editable: false },
            {name:'name',			index:'name', 			width:200, 	align:'left', 	search:false, editable: false },
            {name:'adres', 			index:'adres', 			width:120, 	align:'left', 	search:false, editable: false },
            {name:'id_couriers', 	index:'id_couriers', 	width:20, 	align:'center', search:false, editable: false },
            {name:'sum', 			index:'sum', 			width:30, 	align:'center', search:false, editable: false, sorttype:'int', formatter:'int',summaryType:'sum' },
            {name:'sumbyr', 		index:'sumbyr', 		width:40, 	align:'center', search:false, editable: false, sorttype:'int', formatter:'int',summaryType:'sum' },
            {name:'passed',			index:'passed',			width:25,	align:'center', search:false, editable: true,  edittype:"select", 	editoptions: {value:"0:нет;1:usd;2:byr"}},          
            {name:'adopted',		index:'adopted',		width:30,	align:'center', search:false, editable: false },          
		    {name:'zp', 			index:'zp', 			width:30, 	align:'center', search:false, editable: false, sorttype:'int', formatter:'int',summaryType:'sum' },
		    {name:'zp_blr', 		index:'zp_blr', 		width:30, 	align:'center', search:false, editable: false, sorttype:'int', formatter:'int',summaryType:'sum' },
            {name:'total_usd', 		index:'total_usd', 		width:30, 	align:'center', search:false, editable: false, sorttype:'int', formatter:'int',summaryType:'sum'  },
            {name:'total_blr', 		index:'total_blr', 		width:40, 	align:'center', search:false, editable: false, sorttype:'int', formatter:'int',summaryType:'sum'  },    
		    {name:'comment', 		index:'comment', 		width:90, 	align:'left', 	search:false, editable: false },
			{name:'t2.`date_dostavka`', 	index:'t2.`date_dostavka`', 	width:45,   align:'center', search:false, formatter: getDayWeek},
			],
        pager: tablePager,
		toolbar: [true,"top"],		
        sortname: 't1.`id`',
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

			var ids = table.jqGrid('getDataIDs');
			for(var i=0;i < ids.length;i++) {
				var cl = ids[i];
				if (access['couriers_edit']==1) be = '<div" rel="'+cl+'" class="button edit editdata"></div>';
				else be = '<div class="button edit noactive" rel="'+cl+'"></div>';
				table.jqGrid('setRowData',ids[i],{act:be});
			}		

			var myUserData = table.getGridParam('userData');
			$(".total_usd").val(myUserData.total_usd);
			$(".total_blr").val(myUserData.total_blr);
			$(".total_zp").val(myUserData.zp);	
			$(".total_zp_blr").val(myUserData.zp_blr);	
			
			if (scrollPosition)	table.closest(".ui-jqgrid-bdiv").scrollTop(scrollPosition);	
			
		},		
		grouping:true,
		groupingView : {
			groupField : ['t2.`date_dostavka`'],
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
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30,
		editurl:b_editUrl,
		footerrow: true,
		userDataOnFooter: true,			
    }).jqGrid('navGrid', tablePager,{refresh: true, add: false, del: false, edit: false, search: true, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{});
	
	function ina(looking_for, list) {
		for(i in list) {
			if(looking_for == list[i]){
				return true;
			}
		}
		return false;
	}
	
	// Функция печати
	function gridPrint() {
		$('#print_table_elem').empty();
		
		printForm.dialog( "open" );
		$("#print_date_ot").datepicker({
			dateFormat: 'yy-mm-dd', 
			showOn: "button",
			buttonImage: "/img/admin/icons/calendar.gif",
			buttonImageOnly: true
		});		
		$("#print_date_do").datepicker({
			dateFormat: 'yy-mm-dd', 
			showOn: "button",
			buttonImage: "/img/admin/icons/calendar.gif",
			buttonImageOnly: true
		});

		$("#click-print").click(function(){
			loadajax();
			return false;
		});
		
		loadajax();
		
	}
	
	function loadajax() {
		var date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val(),
			id_couriers = tree.find(".active").attr('id');
			
		$.ajax({
			type: "GET",
			dataType: "json",
			url: b_dataprint,
			data: 'id_couriers='+id_couriers+'&adopted='+adopted+'&date_ot='+date_ot+'&date_do='+date_do,
			success:function (res) {//возвращаемый результат от сервера
					$('#print_table_elem').empty();
					$('#print_table_elem').html(res['html']);
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});		
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
							if ($.inArray(key, Array('was', 'delivered', 'passed')) != -1) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }	
							} else {							
								$('#val_'+key).val(res[key]);	
								if (key == 'id_client') 
								tableTovar.jqGrid('setGridParam',{url: urlTovar+"?id_client="+res[key],page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
							}							
						}	
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});				
			$("#action_pole").attr({value: "edit"});
			CharGroupForm.dialog( "open" );	
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}
	
	// Инициализация формы добавления и редактирования данных					
	CharGroupForm.dialog({
		autoOpen: false,
		width: 910,
		minheight: 'auto',
		title: "Примечание",
		close: function( event, ui ) {					
			$( this ).dialog( "close" );
			scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
			array = [];
			table.trigger("reloadGrid");
		},		
		buttons: [
			{
				text: "Сохранить ЗП",
				click: function() {
					var str = $("#couriers_form").serialize();
						$.ajax({
							type: "POST",
							url: b_saveUrl,
							dataType: "json",
							data: str,
							success:function (res, f) {//возвращаемый результат от сервера		
								if (f === "success" && res['succes']) {
									table.trigger("reloadGrid");	
									$("#action_courierss").attr({value: "edit"});
									scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
									array = [];
									CharGroupForm.dialog( "close" );
								} else {
									showMsgBox(res['message'], "white", "center");
								}
							},
							error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}									
						});
				}
			},
			{
				text: "Сохранить",
				click: function() {
					var str = $("#couriers_form").serialize();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							dataType: "json",
							data: str,
							success:function (res, f) {//возвращаемый результат от сервера		
								if (f === "success" && res['succes']) {
									table.trigger("reloadGrid");	
									$("#action_courierss").attr({value: "edit"});
									scrollPosition = table.closest(".ui-jqgrid-bdiv").scrollTop();
									array = [];
									CharGroupForm.dialog( "close" );
								} else {
									showMsgBox(res['message'], "white", "center");
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
					table.trigger("reloadGrid");
					}
			}
		]
	});
	var t_TableCatalog = $("#t_TableCatalog");
	
	t_TableCatalog.delegate("#btn-apply", "click", function(){
		var id_couriers = tree.find(".active").attr('id');
		
		$.ajax({
			type: "POST",
			url: b_adoptedupdate,
			dataType: "json",
			data: "array="+array,
			success:function (res, f) {
				if (f === "success" && res['succes']) {					
					//loadajax();
					table.trigger("reloadGrid");
					array = [];
					return false;		
				} else {
					showMsgBox(res['message'], "white", "center");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}			
		});

	});	
	
	t_TableCatalog.delegate("#btn-reset", "click", function(){
		var id_couriers = tree.find(".active").attr('id');
		
		$.ajax({type: "POST",url: b_resetdata,dataType: "json",data: "array="+array});
		//loadajax();
		table.trigger("reloadGrid");
		array = [];
		return false;
	});
	
	t_TableCatalog.delegate("#btn-zp", "click", function(){
		var id_couriers = tree.find(".active").attr('id');
		
		$.ajax({type: "POST",url: b_zpupdate,dataType: "json",data: "array="+array});
		table.trigger("reloadGrid");
		array = [];
		return false;
	});	
	
	t_TableCatalog.delegate(".date_btn", "click", function(){
		reloadTableWidthParam();
		return false;
	});	
	
	t_TableCatalog.delegate("#chk-adopted", "click", function(){
		adopted = ($("#chk-adopted").prop("checked")) ? 1 : 0;
		if (adopted == 0) $("#btn-zp").hide();
		else $("#btn-zp").show();
		reloadTableWidthParam();
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
		title: "Отчет по дате",
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