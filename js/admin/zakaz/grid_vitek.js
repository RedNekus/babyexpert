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
	var b_gridUrl	 	= baseUrl+"load_vitek";
	var b_editUrl 		= baseUrl+"edit";
	var b_openUrl 		= baseUrl+"open";
	var b_dataprint 	= baseUrl+"dataprint";
	var b_dataAccess	= accessURL+"access";
	var lastsel;
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
			
			input_html = '<div class="block-input"><input type="text" class="sum_raznica"/></div>';

			$('#t_le_table').append(add + edit + del + input_html);		

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

		}						
	});
	
	table.jqGrid({
		url:b_gridUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:[' ','№з','Менеджер','Наименование товара','Адрес','Кол-во','Цена розн','Цена дилер','Цена доставки','Цена доставки службы','Разница','Дата заказа','Примечание менеджера'],
        colModel :[	
			{name:'act',			index:'act', 			width:12,  align:'center', search:false, sortable:false},
			{name:'nomer_zakaza', 	index:'nomer_zakaza', 	width:15,  align:'center', search:true,  searchoptions:{sopt:['cn']}},
			{name:'id_adminuser', 	index:'id_adminuser',	width:35,  align:'left',   search:false, },		
			{name:'name_tovar', 	index:'name_tovar',		width:220, align:'left',   search:false, sortable:false},
			{name:'adres', 			index:'adres',			width:145,  align:'left',   search:true,  searchoptions:{sopt:['cn']}},
			{name:'kolvo', 			index:'kolvo',			width:35,  align:'center', search:false  },
			{name:'product_cena', 	index:'product_cena',	width:45,  align:'center', search:false  },
			{name:'diler_cena', 	index:'diler_cena',		width:45,  align:'center', search:false  },
			{name:'dostavka_cena', 	index:'dostavka_cena',	width:45,  align:'center', search:false  },
			{name:'dostavka_cena_s',index:'dostavka_cena_s',width:55,  align:'center', search:false  },
			{name:'raznica',		index:'raznica',		width:45,  align:'center', search:false  },
			{name:'date_zakaz', 	index:'date_zakaz', 	width:45,  align:'center', search:false, formatter: getDayWeek},			
			{name:'comment_m', 		index:'comment_m', 		width:150, align:'left',   search:false, },	
			],
        pager: pager,
        sortname: 'date_zakaz',
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
		gridComplete : function() {
	
			var myUserData = table.getGridParam('userData');
			$(".sum_raznica").val(myUserData.sum_raznica);

		},		
		groupingView : {
			groupField : ['date_zakaz'],
			groupColumnShow : [true], 
			groupText : ['<b style="color: green; font-size: 18px;">{0}</b>'],
			groupCollapse : false,
			groupOrder: ['desc'],
			groupSummary : [true],
			showSummaryOnHide: true,
			groupDataSorted : true
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
							if ($.inArray(key, Array('samovivoz', 'samovivoz_ofice', 'dostavka', 'print_ready', 'dumayut')) != -1) {
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

	// Инициализация формы добавления и редактирования данных					
	catalogForm.dialog({
		autoOpen: false,
		width: 910,
		minHeight: 'auto',
		title: "Карточка заказа",
		buttons: [			
				{
				text: "Сохранить",
				click: function() {
					if (stop_save == false) {
						var str = $("#catalog_form").serialize();
							$.ajax({
								type: "POST",
								url: b_editUrl,
								data: str,
								});
							table.trigger("reloadGrid");
							$( this ).dialog( "close" );											
					} else {
						showMsgBox('Выбран курьер, запрещено менять данные!', "white", "center");
					}				
						
					}
				},					
				{
				text: "Cancel",
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
				text: "Cancel",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
	
});