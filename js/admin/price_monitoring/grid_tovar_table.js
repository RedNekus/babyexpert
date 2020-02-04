$(document).ready(function() {
	var baseUrl				= "/adminpanel/price_monitoring/";

	var pager 				= '#le_table_tovarPager';
	var table				= $('#le_table_tovar');

	var loadSelectMakerUrl	= baseUrl+"load_select_maker";
	var loadSelectTovarUrl	= baseUrl+"load_select_tovar";

	var b_editUrl			= baseUrl+"edit_tovar";
	var b_loadUrl			= baseUrl+"load_tovar";	
	var b_openUrl			= baseUrl+"open_tovar";	
	var b_logsUrl 			= baseUrl+"logs_tovar";	
	var b_saveLogsUrl 		= baseUrl+"save_logs";	
	var b_kolvoUrl			= baseUrl+"kolvo_tovar"
	var dialogTovarAdd		= $("#dialog-tovar-add");
	var dialogLogs			= $("#dialog-logs");
	
	var openDataUrl 		= baseUrl+"open";
	
	var tab					= $('.admin-tabs ul li:nth-child(2)');	
	
	tab.click(function() {

		var id = $("#val_id").val();

		table.jqGrid('setGridParam',{url: b_loadUrl+'?id_price_monitoring='+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
				
		$.ajax({
			type: "POST",
			dataType: "json",
			url: openDataUrl,
			data: "id="+id,
			success:function (res) {//возвращаемый результат от сервера
				for(var key in res){ 						
					$('#val_'+key).val(res[key]);
					$('#link-product').html(res['link']);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});
				
	});	
	
	table.jqGrid({
		datatype: 'local',
		width: 700,
		height: 200,		
		colNames:[' ','Магазин','Ссылка','Лог','Цена $','Цена Br','Дата','Просмотров',' '],
		colModel :[
			{name:'actedit',	index:'actedit', 		width:25,   align:'center', search:false, sortable:false},
			{name:'competitors',index:'competitors', 	width:150, 	align:'left'},				
			{name:'link', 		index:'link', 			width:150, 	align:'left'},
			{name:'logs', 		index:'logs', 			width:70, 	align:'center'},
			{name:'cena', 		index:'cena', 			width:50, 	align:'center', editable: true, edittype: "text"},
			{name:'cena_blr', 	index:'cena_blr', 		width:70, 	align:'center', editable: true, edittype: "text"},
			{name:'date_create',index:'date_create', 	width:80,   align:'center', search:false  },
			{name:'kolvo',		index:'kolvo', 			width:90,   align:'center', search:false  },
			{name:'actsave',	index:'actsave', 		width:25,   align:'center', search:false, sortable:false},
			],
		pager: pager,
		sortname: 'id',
		sortorder: 'desc',
		rowNum:50,		
		toolbar: [true,"top"],
		viewrecords: true,	
		rowList:[50,100,150,200],
		rownumbers: true,
		rownumWidth: 30,
		gridComplete : function() {	
			var ids = table.jqGrid('getDataIDs');
			for(var i=0;i < ids.length;i++) {
				var cl = ids[i];
				be = '<div" rel="'+cl+'" class="button edit editdata"></div>';
				bs = '<div" rel="'+cl+'" class="button save savedata"></div>';
				table.jqGrid('setRowData',ids[i],{actedit:be,actsave:bs});
			}
			id = $("#val_id").val();
			table.jqGrid('setGridParam',{url: b_loadUrl+'?id_price_monitoring='+id,page:1,datatype: 'json',mtype: 'GET'});
			
		},
		ondblClickRow: function(id) {
			gridEdit();
		},				
		editurl:b_editUrl
	}).jqGrid('navGrid', pager,{refresh: true, add: false, del: true, edit: false, search: false, view: false});

	$('#t_le_table_tovar').height(24);
	add = '<div id="adddata" class="button add adddata"></div>';
	edit = '<div id="editdata" class="button edit"></div>';

	$('#t_le_table_tovar').append(add + edit);	
	
	// Обработка события кнопки Добавить	
	$("#adddata").click(function(){
		gridAdd();
	});
	
	// Обработка события кнопки Редактировать	
	$("#editdata").click(function(){
		gridEdit();
	});
	
	$("#value_id_competitors").change(function() {
		var val = $('option:selected', this).text();
		$("#link-concurent").empty();
		$("#link-concurent").html('<a href="'+val+'" target="_ablank">'+val+'</a>');
		
	});
	
	$('#le_table_tovar').delegate(".kolvo-click", "click", function(){	
		var id = $(this).attr('rel');		
		$.ajax({
			type: "POST",
			dataType: "json",
			url: b_kolvoUrl,		
			data: "id="+id
		});
		table.trigger("reloadGrid");
	});
	
	$('#le_table_tovar').delegate("#show-log", "click", function(){
		var id = $(this).attr('rel'),
			id_catalog = $("#val_id_catalog").val();
		
		showLogs(id,id_catalog,'price_competitors');
		dialogLogs.dialog("open");	
		return false;
	});
		
	$("#logs-product").click(function(){
		var id = $("#val_id").val(),
			id_catalog = $("#val_id_catalog").val();
		
		showLogs(id,id_catalog,'price_monitoring');
		dialogLogs.dialog("open");	
		return false;
	});
	
	function showLogs(id,id_catalog,table_name) {
		$.ajax({
			type: "POST",
			dataType: "json",
			url: b_logsUrl,
			data: "id="+id+"&table_name="+table_name+"&id_catalog="+id_catalog,
			success:function (res) {//возвращаемый результат от сервера
				$("#table-logs").empty();
				$("#table-logs").html(res['msg']);
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});	
	}
	
	function gridAdd() {			
		var id = $(this).attr('rel');
		
		$("#form-tovar").trigger("reset");
		$("#value_id_tovar").val(id);
		$("#value_id_price_monitoring").val($("#val_id").val());
		$("#value_action").val('add');			
		dialogTovarAdd.dialog('open');	
	}
	
	function gridEdit() {			
		gsr = table.jqGrid('getGridParam','selrow');		
			if(gsr){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_openUrl,
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 						
							$('#value_'+key).val(res[key]);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#value_action").val('edit');
				dialogTovarAdd.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}
	}
	
	table.delegate(".editdata", "click", function(){
		var id = $(this).attr('rel');
		if(id) {
			params = {
				"keys" : true,
				"aftersavefunc" : function() {table.trigger("reloadGrid");},
				"mtype" : "POST"
			}				
			table.jqGrid('editRow',id,params);
		}
	});
	
	table.delegate(".savedata", "click", function(){
		var id = $(this).attr('rel');
		
		if(id) {
			params = {
				"keys" : true,
				"aftersavefunc" : function() {table.trigger("reloadGrid");},
				"mtype" : "POST"
			}				
			table.jqGrid('saveRow',id,params);
		}
	});	

	$(".val_id_tree").change(function() {
		var id_tree = $(this).val();
		
		$.ajax({
			type: "POST",
			url: loadSelectMakerUrl,
			data: "id_tree="+id_tree,
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера		
				$( ".val_id_maker" ).empty();
				$( ".val_id_maker" ).append(res['makers']);
				$( ".val_id_tovar" ).empty();
				$( ".val_id_tovar" ).append(res['tovars']);								
			}	
		});
	});
	
	$(".val_id_maker").change(function() { 
		var id_maker = $(this).val(),
			id_tree = $(".val_id_tree").val();
		
		$.ajax({
			type: "POST",
			url: loadSelectTovarUrl,
			data: "id_tree="+id_tree+"&id_maker="+id_maker,
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера			
				$( ".val_id_tovar" ).empty();
				$( ".val_id_tovar" ).append(res['tovars']);								
			}	
		});			
	});

	dialogTovarAdd.dialog({
		autoOpen: false,
		title: "Параметры",
		width: 620,
		buttons: [
			{
				text: "Сохранить",
				click: function() {
					var str = $("#form-tovar").serialize();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							data: str,
							});
						id = $("#val_id").val();
						table.jqGrid('setGridParam',{url: b_loadUrl+'?id_price_monitoring='+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
						
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
	
	dialogLogs.dialog({
		autoOpen: false,
		title: "История",
		width: 420,
		minHeight: 'auto',
		buttons: [
			{
				text: "Экспорт",
				click: function() {
					var id = $("#id_export").val(),
						id_catalog = $("#val_id_catalog").val(),
						table_name = $("#table_name").val();
						
					if (id) {
						$.ajax({
							type: "POST",
							dataType: "json",
							url: b_saveLogsUrl,
							data: "id="+id+"&table_name="+table_name+"&id_catalog="+id_catalog,
							success:function (res) {//возвращаемый результат от сервера
								showMsgBox(res, "white", "center");
							},
							error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
						});
					} else {
						alert("Пожалуйста выберите запись!");
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
});