$(document).ready(function() {
	var baseUrl			= "/adminpanel/adminaccess/";
	var $tabs 			= $('#tabs-adminaccess').tabs();
	
	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var dialogEdit	 	= $("#dialog-edit");
	var dialogDel 		= $("#dialog-del");
	var b_gridUrl	 	= baseUrl+"load";
	var b_editUrl 		= baseUrl+"edit";
	var b_openUrl 		= baseUrl+"open";
	var b_dataAccess	= baseUrl+"access";
	
	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 

	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['adminaccess_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['adminaccess_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['adminaccess_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';

			$('#t_le_table').append(add + edit + del);		

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
		url:b_gridUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:['Код', 'Название','Активен','Приоритет'],
        colModel :[
			{name:'id', 		index:'id', 			width:15, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
            {name:'name', 		index:'name', 			width:500, 	align:'left', 		search:true, 	editable:true, 	edittype:"text", searchoptions:{sopt:['cn']}},
			{name:'active', 	index:'active', 		width:30, 	align:'center', 	search:false, 	editable:true, 	edittype:"text"},
			{name:'prioritet', 	index:'prioritet', 		width:25, 	align:'center', 	search:false, 	editable:true, 	edittype:"text" },
			],
        pager: pager,
        sortname: 'prioritet',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['adminaccess_edit']==1) gridEdit();
		},
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30
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
		dialogEdit.dialog( "open" );
		}

	// Функция редактирование итема в тиблице	
	function gridEdit(){
		gsr = table.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_openUrl,
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 	
							if ($.inArray(key, Array('active','catalog_add','catalog_del','catalog_edit','catalog_review','characteristics_add','characteristics_del','characteristics_edit','characteristics_review',
'maker_add','maker_del','maker_edit','maker_review','question_add','question_del','question_edit','question_review','reviews_add','reviews_del','reviews_edit','reviews_review',
'pages_add','pages_edit','pages_del','pages_review','banners_add','banners_edit','banners_del','banners_review','akcii_add','akcii_edit','akcii_del','akcii_review',
'article_add','article_edit','article_del','article_review','news_add','news_edit','news_del','news_review','currency_edit','currency_add','currency_del','currency_review',
'adminusers_edit','adminusers_add','adminusers_del','adminusers_review','adminaccess_edit','adminaccess_add','adminaccess_del','adminaccess_review','registration_edit','registration_add','registration_del','registration_review',
'promocode_review','promocode_edit','promocode_del','promocode_add','adminusers_stats_review','raffle_add','raffle_edit','raffle_del','raffle_review','zakaz_edit','zakaz_add','zakaz_del','zakaz_review','zakaz_tovar_edit','zakaz_tovar_add','zakaz_tovar_del','zakaz_tovar_review',
'connection_edit','connection_add','connection_del','connection_review','couriers_edit','couriers_add','couriers_del','couriers_review',
'kontragenty_edit','kontragenty_add','kontragenty_del','kontragenty_review','kontragenty_tip_edit','kontragenty_tip_add','kontragenty_tip_del','kontragenty_tip_review',
'valute_edit','valute_add','valute_del','valute_review',
'delivery_tmc_edit','delivery_tmc_add','delivery_tmc_del','delivery_tmc_review','sklad_edit','sklad_add','sklad_del','sklad_review',
'application_for_warehouse_edit','application_for_warehouse_add','application_for_warehouse_del','application_for_warehouse_review',
'catalog_sklad_edit','catalog_sklad_add','catalog_sklad_del','catalog_sklad_review',
'kassa_edit','kassa_add','kassa_del','kassa_review','kassa_tree_edit','kassa_tree_add','kassa_tree_del',
'tip_operation_edit','tip_operation_add','tip_operation_del','tip_operation_review',
'competitors_edit','competitors_add','competitors_del','competitors_review',
'price_monitoring_edit','price_monitoring_add','price_monitoring_del','price_monitoring_review',
'fuel_edit','fuel_add','fuel_del','fuel_review','product_week_edit','product_week_add','product_week_del','product_week_review',
'importer_edit','importer_add','importer_del','importer_review',
'manufacturer_edit','manufacturer_add','manufacturer_del','manufacturer_review','brand_edit','brand_add','brand_del','brand_review',
'banners_left_edit','banners_left_add','banners_left_del','banners_left_review','banners_small_edit','banners_small_add','banners_small_del','banners_small_review',
'spros_edit','spros_add','spros_del','spros_review','zpmanager_edit','zpmanager_add','zpmanager_del','zpmanager_review',
'managers_edit','managers_add','managers_del','managers_review','catalog_complect_edit','catalog_complect_add','catalog_complect_del','catalog_complect_review',
'return_tmc_edit','return_tmc_add','return_tmc_del','return_tmc_review')) != -1) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else {
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
		width: 910,
		height: 600,
		title: "Свойства доступа",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#form").serialize();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							data: str,
							});
						table.trigger("reloadGrid");
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