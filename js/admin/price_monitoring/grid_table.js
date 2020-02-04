$(document).ready(function() {
	var baseUrl			= "/adminpanel/price_monitoring/";
	var accessURL		= "/adminpanel/adminaccess/";

	var tabs			= $(".admin-tabs");			
	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var tableT			= $('#le_table_tovar');
	var dialogEdit 		= $("#dialog-edit");
	var dialogDel 		= $("#dialog-del");
	var b_loadUrl	 	= baseUrl+"load";
	var b_editUrl 		= baseUrl+"edit";
	var b_openUrl 		= baseUrl+"open";
	var b_loadTUrl		= baseUrl+"load_tovar";	
	var b_dataAccess	= accessURL+"access";

	var height 			= $(window).height()-135;
	//window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 

	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			var t_table 		= $('#t_le_table');	
			access = res;
			t_table.height(24);

			if (access['price_monitoring_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['price_monitoring_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['price_monitoring_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';

			t_table.append(add + edit + del);		

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

			$("#le_table").delegate(".editdata", "click", function(){
				gridEdit($(this).attr('rel'));
			});				
			

		}						
	});
		
	table.jqGrid({
		url:b_loadUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:[' ','Название','Цена $','Цена Br','Ссылка','Создатель','Дата'],
        colModel :[
			{name:'actedit',		index:'actedit', 		width:15,   align:'center', 	search:false, sortable:false},
            {name:'name', 			index:'name', 			width:400, 	align:'left', 		search:true, 	},
            {name:'cena', 			index:'cena', 			width:100, 	align:'center', 	search:false, 	},
            {name:'cena_blr', 		index:'cena_blr', 		width:100, 	align:'center', 	search:false, 	},			
            {name:'path', 			index:'path', 			width:200, 	align:'left', 		search:true, 	},
			{name:'id_adminuser', 	index:'id_adminuser', 	width:120, 	align:'left', 		search:true, 	},
			{name:'date_create', 	index:'date_create', 	width:120, 	align:'left', 		search:false, 	},
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,		
		ondblClickRow: function(id) {
			if (access['price_monitoring_edit']==1) gridEdit();
		},
		gridComplete : function() {	
			var ids = table.jqGrid('getDataIDs');
			for(var i=0;i < ids.length;i++) {
				var cl = ids[i];
				be = '<div" rel="'+cl+'" class="button edit editdata"></div>';
				table.jqGrid('setRowData',ids[i],{actedit:be});
			}			
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

	// Функция добавление итема в таблицу		
	function gridAdd(){
		$("#form").trigger("reset");	
		$("#action_pole").attr({value: "add"});
		tabs.tabs({active:'0'});	
		tabs.tabs( "disable", 1 );		
		dialogEdit.dialog( "open" );
		}
		
	// Функция редактирование итема в тиблице	
	function gridEdit(gsr){
		if (typeof(gsr) != "undefined") id = gsr;
		else id = table.jqGrid('getGridParam','selrow');

		tabs.tabs({active:'1'});			
		tabs.tabs( "enable", 1 );		
			if(id){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_openUrl,
					data: "id="+id,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 						
							$('#val_'+key).val(res[key]);
							$('#link-product').html(res['link']);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#action_pole").attr({value: "edit"});
				dialogEdit.dialog( "open" );
				tableT.jqGrid('setGridParam',{url: b_loadTUrl+'?id_price_monitoring='+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");		
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

	$("#val_cena").keyup(function() {
		
		var cena = $(this).val(),
			kurs = $("#val_kurs").val(),
			cenaZakup = $("#val_cena_zakup").val();
			
		cenaBlr = cena * kurs;
		delta = cena - cenaZakup;
		
		$("#val_cena_blr_kurs").val(cenaBlr);
		$("#val_delta").val(delta);
		
	});
	
	$("#val_cena_blr_kurs").keyup(function() {
		
		var cenaBlr = $(this).val(),
			kurs = $("#val_kurs").val(),
			cenaZakup = $("#val_cena_zakup").val();
			
		cena = (cenaBlr / kurs).toFixed(2);
		delta = cena - cenaZakup;
		
		$("#val_cena").val(cena);
		$("#val_delta").val(delta);
		
	});
	
	$("#val_cena_blr").keyup(function() {
		
		var cenaBlr = $(this).val(),
			kurs = $("#val_kurs").val(),
			cenaZakup = $("#val_cena_zakup").val();
			
		cena = (cenaBlr / kurs).toFixed(2);
		delta = cena - cenaZakup;
	
		$("#val_cena_usd_kurs").val(cena);
		$("#val_delta").val(delta);
		
	});
	
	// Инициализация формы добавления и редактирования данных					
	dialogEdit.dialog({
		autoOpen: false,
		width: 740,
		minHeight: 'auto',
		modal: true,
		resizable: false,		
		title: "Свойства",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#form").serialize();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							dataType: "json",
							data: str,
							success:function (res) {
								if (res['success']==true) {
									if (res['action']=='add') {		
										$("#val_id").val(res['last_id']);
										$("#action_pole").val("edit");
										tabs.tabs( "enable", 1 );
									} else {
										table.trigger("reloadGrid");
										dialogEdit.dialog( "close" );
									}
								} else {
									if (res["msg"]) showMsgBox(res["msg"], "white", "center");
								}			
							},							
						});					
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
	
});