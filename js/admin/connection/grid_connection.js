$(document).ready(function() {
	var baseUrl					= "/adminpanel/connection/";
	var accessURL				= "/adminpanel/adminaccess/";
		
	var pager					= '#le_tablePager';
	var table 					= $('#le_table');
	var dialogEdit 			= $("#dialog-edit");
	var dialogDel 		= $("#dialog-del");	
	var b_loadURL				= baseUrl+"load";
	var b_editURL 				= baseUrl+"edit";
	var b_openURL 				= baseUrl+"open";
	var b_openTreeURL			= baseUrl+"opentree";
	var catalogFormImport		= $("#catalogFormImport");	
	var b_dataAccess			= accessURL+"access";


	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['connection_add']==1) add_g = '<div id="adddata" class="button add"></div>';
			else add_g = '<div class="button add noactive"></div>';
			
			if (access['connection_edit']==1) edit_g = '<div id="editdata" class="button edit"></div>';
			else edit_g = '<div class="button edit noactive"></div>';
			
			if (access['connection_del']==1) del_g = '<div id="deldata" class="button del"></div>';
			else del_g = '<div class="button del noactive"></div>';
						
			xls = '<div id="import_files" class="button set_columns"></div>';
						
			$('#t_le_table').append( add_g + edit_g + del_g + xls);
			
			// Обработка события кнопки Редактировать	
			$("#adddata").click(function(){
				gridAdd();
			});
			
			// Обработка события кнопки Редактировать	
			$("#editdata").click(function(){
				gridEdit();
			});

			// Обработка события кнопки Удалить	
			$("#deldata").click(function(){
				delItem(table,dialogDel,$("#del_id"));
			});
			
			$("#import_files").click(function(){
				catalogFormImport.dialog( "open" );	
			});	
			
		}						
	});

	// при нажатии на ссылку на дереве
	$("#tree_connection").delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
		$('#tree_connection').find(".active").removeClass("active");
		$(this).addClass('active');	
		table.jqGrid('setGridParam',{url: b_loadURL+"?id="+id,page:1}).trigger("reloadGrid");
	});
	
	table.jqGrid({
		url:b_loadURL,
		datatype: 'json',
        mtype: 'GET',
		//cmTemplate:{resizable:false},
		autowidth: true,
        colNames:['Название портал','Название сайт', 'Активен'],
        colModel :[
            {name:'name_portal', 	index:'name_portal', 	width:450, 	align:'left', 	search:true,  },
            {name:'name_site',		index:'name_site', 		width:450, 	align:'left', 	search:true,  },
            {name:'active', 		index:'active', 		width:50, 	align:'center', search:false, },
			],
        pager: pager,
		toolbar: [true,"top"],		
        sortname: 'id',
        sortorder: 'asc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['connection_edit']==1) gridEdit();
		},
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30,		
    }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{});

	// Функция добавления группы
	function gridAdd() {
		$("#connection_form").trigger("reset");
		$("#action_pole").attr({value: "add"});
		var find 	= $("#tree_connection").find(".active")	
		var id 		= find.attr('id');
		$('#val_id_tree').val(id);			
		dialogEdit.dialog( "open" );	
	}
	
	// Функция редактирования группы
	function gridEdit() {
		var gsr = table.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					url: b_openURL,
					dataType: "json",
					type: "POST",	
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера		
						for(var key in res){ 	
							if ((key == "active") || (key == "name_vision")) {
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
			dialogEdit.dialog( "open" );	
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}
	
	// Функция удаления данных о группе характеристики
	function delItem(bi_table, dialogForm, delValue){
		var gr = bi_table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				dialogForm.dialog( "open" );
				delValue.val(gr);
			} else alert("Пожалуйста выберите запись!");		
	}
	
	function gridAddChar() {
		$("#catalog_form_connections").trigger("reset");
		$("#action_connections").attr({value: "add"});
		AddCharDialog.dialog( "open" );
		tabs.tabs({active:'0'});	
	}
	
	// Функция редактирования характеристики
	function gridEditChar(id) {	
		if(id){
			$.ajax({
				url: b_openTreeURL,
				dataType: "json",
				type: "POST",	
				data: "id="+id,
				success:function (res) {//возвращаемый результат от сервера		
					for(var key in res){ 
						if (key == "active") {
							if (res[key] != 0) 	 {	$('#valueChar_'+key).attr('checked', true);} else { $('#valueChar_'+key).attr('checked', false); }	
						} else {							
							$('#valueChar_'+key).val(res[key]);	
						}
						
					}	
				},
				error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
			});
			$("#action_connections").attr({value: "edit"});
			AddCharDialog.dialog( "open" );		
		} else {
			alert("Пожалуйста выберите запись!")
		}	
	}
	
	// Инициализация формы добавления и редактирования группы					
	dialogEdit.dialog({
		autoOpen: false,
		width: 620,
		minheight: 'auto',
		title: "Свойства группы",
		buttons: [
			{
				text: "Сохранить",
				click: function() {
					var str = $("#connection_form").serialize();
					$.ajax({
						type: "POST",
						url: b_editURL,
						data: str,
					});
					table.trigger("reloadGrid");	
					$("#action_connections").attr({value: "edit"});
					dialogEdit.dialog( "close" );								
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
	
	// Форма удаления группы	
	dialogDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: 	function () {
					var id = $("#del_id").val();
						$.ajax({
							type: "POST",
							url: b_editURL,
							data: "del_id="+id,
						});
						table.trigger("reloadGrid");
						$( this ).dialog( "close" );						
				}
			},
			{
				text: "Нет",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
	

	catalogFormImport.dialog({
		autoOpen: false,
		width: 'auto',
		minheight: 'auto',
		title: "Импорт файлов",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					$('#import_form').submit();
					var iframe = $("#hiddenimportframe");
					$("#loading").show();
					iframe.load(function(){
						$("#loading").hide();
						table.trigger("reloadGrid");
						catalogFormImport.dialog( "close" );
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
});