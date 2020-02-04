$(document).ready(function() {
	var baseUrl					= "/adminpanel/characteristics/";
	var baseUrlGroupTip			= "/adminpanel/characteristics_tip/";
	var accessURL				= "/adminpanel/adminaccess/";
		
	var tabs 					= $('.admin-tabs').tabs();
	var TableCatalogPager		= '#TableCatalogPager';
	var TableCatalog 			= $('#TableCatalog');
	var CharGroupForm 			= $("#characteristicsForm");
	var CharGroupFormDel 		= $("#characteristicsFormDel");	
	var charFormDel 			= $("#charFormDel");
	var AddCharDialog			= $("#AddCharacteristicsDialog");
	var AddSelectItems			= baseUrl+"addselectitems";
	var LoadCatalogUrl			= baseUrl+"load";
	var EditCatalogUrl 			= baseUrl+"edit";
	var b_dataHandling 			= baseUrl+"datahandling";
	var b_dataHandlingChar		= baseUrl+"datahandlingchar";
	var b_dataInit				= baseUrl+"datainit";
	var NextItemCatalog			= baseUrl+"nextitemcharacteristics";
	var selectRow;
	var TableCharacter			= $('#TableCharacter');
	var LoadGroupTipUrl			= baseUrlGroupTip+"load";
	var b_dataAccess			= accessURL+"access";


	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_TableCatalog').height(24);

			
			
			if (access['characteristics_add']==1) add_g = '<div id="adddata" class="button add-folder"></div>';
			else add_g = '<div class="button add-folder noactive"></div>';
			
			if (access['characteristics_edit']==1) edit_g = '<div id="editdata" class="button edit-folder"></div>';
			else edit_g = '<div class="button edit-folder noactive"></div>';
			
			if (access['characteristics_del']==1) del_g = '<div id="deldata" class="button del-folder"></div>';
			else del_g = '<div class="button del-folder noactive"></div>';
						
			
			if (access['characteristics_add']==1) add = '<div id="addchar" class="button add"></div>';
			else add = '<div class="button edit noactive"></div>';
			
			if (access['characteristics_edit']==1) edit = '<div id="editchar" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['characteristics_del']==1) del = '<div id="delchar" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';
			
	
			$('#t_TableCatalog').append( add_g + edit_g + del_g + add + edit + del);
			
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
				delItem(TableCatalog,CharGroupFormDel,$("#del_id_characteristics"));
			});
			
			// Обработка события кнопки Добавить характеристику
			$("#addchar").click(function(){
				gridAddChar();
			});
	
			// Обработка события кнопки Редактировать характеристику	
			$("#editchar").click(function(){
				gridEditChar(selectRow);	
			});	
	
			// Обработка события кнопки Удалить	характеристику
			$("#delchar").click(function(){	
				var gr = selectRow;
					if( gr != null ) {
						charFormDel.dialog( "open" );
						$("#del_id_char").val(gr);
					} else alert("Пожалуйста выберите запись!");			
			});

		}						
	});

	
	
	// при нажатии на ссылку на дереве
	$("#tree_characteristics").delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
		$('#tree_characteristics').find(".active").removeClass("active");
		$(this).addClass('active');	
		TableCatalog.jqGrid('setGridParam',{url: LoadCatalogUrl+"?id="+id,page:1}).trigger("reloadGrid");
	});
	
	TableCatalog.jqGrid({
		url:LoadCatalogUrl,
		datatype: 'json',
        mtype: 'GET',
		//cmTemplate:{resizable:false},
		autowidth: true,
        colNames:['Название','Тип','Фильтр','Фильтр (toolbar)','Приоритет фильтра','В каталоге', 'Активен', 'Приоритет'],
        colModel :[
            {name:'n1', 	index:'n1', 	width:250, 	align:'left', 		search:true, 	editable:true, 	edittype:"text" },
            {name:'n2',		index:'n2', 	width:180, 	align:'center', 	search:true, 	editable:true, 	edittype:"text" },
            {name:'n3', 	index:'n3', 	width:150, 	align:'center', 	search:true, 	editable:true, 	edittype:"text" },
			{name:'n4', 	index:'n4', 	width:150, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			{name:'n5', 	index:'n5', 	width:150, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			{name:'n6', 	index:'n6', 	width:150, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			{name:'n7', 	index:'n7', 	width:150, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
            {name:'n8', 	index:'n8', 	width:150, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			],
        pager: TableCatalogPager,
		toolbar: [true,"top"],		
        sortname: 'id',
        sortorder: 'asc',
		viewrecords: true,
		idPrefix: "grid",
		//ondblClickRow: function(id) {	gridEdit();	},	
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) {
			var subgrid_table_id;
			subgrid_table_id = subgrid_id+'_t';
			$('#'+subgrid_id).html('<table id="'+subgrid_table_id+'"></table><div id="'+subgrid_table_id+'_pager"></div>');
			colModel = $(this).jqGrid("getGridParam", "colModel");
			$('#'+subgrid_table_id).jqGrid({
				url: baseUrl+"subload",
				datatype: 'json',
				mtype: 'POST',
				postData: {'get':'subgrid', 'id':row_id},
				colNames: ['Название','Тип','Фильтр','Фильтр (toolbar)','Приоритет фильтра','В каталоге', 'Активен', 'Приоритет'],
				colModel: [
					{name:'sn1', 	index:'n1', 	width:(colModel[1].width - 2), 	align:'left', 		search:true, 	editable:true, 	edittype:"text" },
					{name:'sn2',	index:'n2', 	width:(colModel[2].width), 	align:'left', 		search:true, 	editable:true, 	edittype:"text" },
					{name:'sn3', 	index:'n3', 	width:(colModel[3].width), 	align:'center', 	search:true, 	editable:true, 	edittype:"text" },
					{name:'sn4', 	index:'n4', 	width:(colModel[4].width), 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
					{name:'sn5', 	index:'n5', 	width:(colModel[5].width), 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
					{name:'sn6', 	index:'n6', 	width:(colModel[6].width), 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
					{name:'sn7', 	index:'n7', 	width:(colModel[7].width), 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
					{name:'sn8', 	index:'n8', 	width:(colModel[8].width), 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
					],
				autowidth: true,	
				height: 'auto',
				ondblClickRow: function(id) { if (access['characteristics_edit']==1) gridEditChar(id); },
				sortname: 'id',
				sortorder: 'asc',
				onSelectRow: function(id){
					selectRow = id;
				}
			});
			$('#'+subgrid_table_id).closest("div.ui-jqgrid-view").children("div.ui-jqgrid-hdiv").hide();			
		},	
		resizeStop: function (newWidth, index) {
        var widthChange = this.newWidth - this.width,
            $theGrid = $(this.bDiv).find(">div>.ui-jqgrid-btable"),
            $subgrids = $theGrid.find(">tbody>.ui-subgrid>.subgrid-data>.tablediv>.ui-jqgrid>.ui-jqgrid-view>.ui-jqgrid-bdiv>div>.ui-jqgrid-btable");
        $subgrids.each(function () {
            var grid = this.grid;
            grid.resizing = { idx: (index - 1) };
            grid.headers[index - 1].newWidth = (index - 1 === 0) || (index - 1 === grid.headers.length) ? newWidth - 0 : newWidth;
            grid.newWidth = grid.width + widthChange;
            grid.dragEnd.call(grid);
            $(this).jqGrid("setGridWidth", grid.newWidth, false);
        });
		//$("#jqgh_TableCatalog_n8").click();
		},		
		gridComplete: function(){ 
			var rowIds = TableCatalog.getDataIDs();
			$.each(rowIds, function (index, rowId) {
				TableCatalog.expandSubGridRow(rowId); 
			});	
		}
    });

	// Функция добавления группы
	function gridAdd() {
		$("#characteristics_form").trigger("reset");
		$("#action_pole").attr({value: "add"});
		var find 	= $("#tree_characteristics").find(".active")	
		var id 		= find.attr('id');
		$('#val_id_tree').val(id);	
		$.ajax({
			type: "POST",
			url: NextItemCatalog,
			success:function (res) {
				$("#valueChar_id").val(res);
			}
			});			
		CharGroupForm.dialog( "open" );	
	}
	
	// Функция редактирования группы
	function gridEdit() {
		var gsr = TableCatalog.jqGrid('getGridParam','selrow');
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


	
	function SqlToHtmlSelect() {
		var find 	= $("#tree_characteristics").find(".active")	
		var id 		= find.attr('id');
		$.ajax({
			url: AddSelectItems,
			dataType: "json",
			type: "POST",	
			data: "id="+id,
			success:function (res) {//возвращаемый результат от сервера		
				$('#valueChar_id_groupe').empty();
				var i = 0, html = "";
					for(var key in res){ 	
						html += '<option value="'+res[i]["id"]+'">'+res[i]["name"]+'</option>';
						i++;
					}
				$('#valueChar_id_groupe').html(html);
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});
	}
	
	function gridAddChar() {
		$("#catalog_form_сharacteristics").trigger("reset");
		$("#action_сharacteristics").attr({value: "add"});
		$.ajax({
			type: "POST", 
			url: NextItemCatalog, 
			success:function (res) { 
				$("#valueChar_id").val(res);
				TableCharacter.jqGrid('setGridParam',{url: LoadGroupTipUrl+"?id="+res,page:1}).trigger("reloadGrid");				
			}
		});	
		SqlToHtmlSelect();
		AddCharDialog.dialog( "open" );
		tabs.tabs({active:'0'});	
	}
	
	// Функция редактирования характеристики
	function gridEditChar(id) {	
		if(id){
			SqlToHtmlSelect();
			$.ajax({
				url: b_dataHandlingChar,
				dataType: "json",
				type: "POST",	
				data: "id="+id,
				success:function (res) {//возвращаемый результат от сервера		
					for(var key in res){ 
						if ((key == "active") || (key == "show_catalog") || (key == "filtr_toolbar") || (key == "valcharacter")) {
							if (res[key] != 0) 	 {	$('#valueChar_'+key).attr('checked', true);} else { $('#valueChar_'+key).attr('checked', false); }	
						} else {
							if (key == "language") {
								baza = res['language'];
								for(var ley in baza){ 	
									$('#valueChar_'+ley).val(baza[ley]);
								}
							}							
							$('#valueChar_'+key).val(res[key]);	
						}
						
					}	
				},
				error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
			});
			$("#action_сharacteristics").attr({value: "edit"});
			TableCharacter.jqGrid('setGridParam',{url: LoadGroupTipUrl+"?id="+id,page:1}).trigger("reloadGrid");
			AddCharDialog.dialog( "open" );		
		} else {
			alert("Пожалуйста выберите запись!")
		}	
	}
	
	// Инициализация формы добавления и редактирования группы					
	CharGroupForm.dialog({
		autoOpen: false,
		width: 620,
		height: 300,
		title: "Свойства группы",
		buttons: [
			{
				text: "Сохранить",
				click: function() {
					var str = $("#characteristics_form").serialize();
					$.ajax({
						type: "POST",
						url: EditCatalogUrl,
						data: str,
					});
					TableCatalog.trigger("reloadGrid");	
					$("#action_сharacteristics").attr({value: "edit"});
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
					var id = $("#del_id_characteristics").val();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							data: "del_id_characteristics="+id,
						});
						TableCatalog.trigger("reloadGrid");
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

	// Форма удаления группы	
	charFormDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: 	function () {
							var id = $("#del_id_char").val();
								$.ajax({
									type: "POST",
									url: EditCatalogUrl,
									data: "del_id_char="+id,
								});
								TableCatalog.trigger("reloadGrid");
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
	
// Инициализация формы добавления и редактирования характеристики					
	AddCharDialog.dialog({
		autoOpen: false,
		width: 760,
		minHeight: 'auto',
		title: "Свойства характеристики",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#catalog_form_сharacteristics").serialize();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							data: str,
							});
						TableCatalog.trigger("reloadGrid");	
						$("#action_сharacteristics").attr({value: "edit"});
						AddCharDialog.dialog( "close" );						
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