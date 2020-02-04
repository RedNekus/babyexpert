$(document).ready(function() {
	var baseUrl					= "/adminpanel/kassa/";
	var accessURL				= "/adminpanel/adminaccess/";
		
	var tabs 					= $('.admin-tabs').tabs();
	var tablePager				= '#TableCatalogPager';
	var table 					= $('#TableCatalog');
	var CharGroupForm 			= $("#connectionForm");
	var CharGroupFormDel 		= $("#connectionFormDel");	
	var AddCharDialog			= $("#AddConnectionDialog");
	var LoadCatalogUrl			= baseUrl+"load";
	var EditCatalogUrl 			= baseUrl+"edit";
	var b_dataHandling 			= baseUrl+"open";
	var b_dataHandlingChar		= baseUrl+"datahandlingchar";
	var catalogFormImport		= $("#catalogFormImport");	
	var b_dataAccess			= accessURL+"access";


	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_TableCatalog').height(24);

			if (access['kassa_add']==1) add_g = '<div id="adddata" class="button add"></div>';
			else add_g = '<div class="button add noactive"></div>';
			
			if (access['kassa_edit']==1) edit_g = '<div id="editdata" class="button edit"></div>';
			else edit_g = '<div class="button edit noactive"></div>';
			
			if (access['kassa_del']==1) del_g = '<div id="deldata" class="button del"></div>';
			else del_g = '<div class="button del noactive"></div>';
						
			$('#t_TableCatalog').append( add_g + edit_g + del_g);
			
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
				delItem(table,CharGroupFormDel,$("#del_id_connection"));
			});

		}						
	});

	
	
	// при нажатии на ссылку на дереве
	$("#tree_connection").delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
		$("#val_id_tree").val(id);
		$('#tree_connection').find(".active").removeClass("active");
		$(this).addClass('active');	
		table.jqGrid('setGridParam',{url: LoadCatalogUrl+"?id="+id,page:1}).trigger("reloadGrid");
	});
	
	table.jqGrid({
		url:LoadCatalogUrl,
		datatype: 'json',
        mtype: 'GET',
		//cmTemplate:{resizable:false},
		autowidth: true,
        colNames:['Цена USD','Цена BYR','Цена EUR','Примечание','Дата создания','Время создания'],
        colModel :[
            {name:'cena', 			index:'cena', 			width:100, 	align:'center', 	search:false,  	},
            {name:'cena_blr',		index:'cena_blr', 		width:100, 	align:'center', 	search:false,  	},
            {name:'cena_eur',		index:'cena_eur', 		width:100, 	align:'center', 	search:false,  	},
            {name:'comment',		index:'comment', 		width:300, 	align:'left', 		search:false,  	},
            {name:'date_create', 	index:'date_create', 	width:50, 	align:'center', 	search:false, 	},
            {name:'time_create', 	index:'time_create', 	width:50, 	align:'center', 	search:false, 	},
			],
        pager: tablePager,
		toolbar: [true,"top"],		
        sortname: 'id',
        sortorder: 'asc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['kassa_edit']==1) gridEdit();
		},
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30,		
    }).jqGrid('navGrid', tablePager,{refresh: true, add: false, del: false, edit: false, search: true, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{});

	// Функция добавления группы
	function gridAdd() {
		$("#form").trigger("reset");
		$("#action_pole").attr({value: "add"});
		var find 	= $("#tree_connection").find(".active")	
		var id 		= find.attr('id');
		$('#val_id_tree').val(id);			
		CharGroupForm.dialog( "open" );	
	}
	
	// Функция редактирования группы
	function gridEdit() {
		var gsr = table.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					url: b_dataHandling,
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
			CharGroupForm.dialog( "open" );	
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
				url: b_dataHandlingChar,
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
	CharGroupForm.dialog({
		autoOpen: false,
		width: 620,
		minheight: 'auto',
		title: "Свойства",
		buttons: [
			{
				text: "Сохранить",
				click: function() {
					var str = $("#form").serialize();
					$.ajax({
						type: "POST",
						url: EditCatalogUrl,
						data: str,
					});
					table.trigger("reloadGrid");	
					$("#action_connections").attr({value: "edit"});
					CharGroupForm.dialog( "close" );								
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
	CharGroupFormDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: 	function () {
					var id = $("#del_id_connection").val();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							data: "del_id_connection="+id,
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
		
});