$(document).ready(function() {
	var baseUrl				= "/adminpanel/managers/";
	var accessURL			= "/adminpanel/adminaccess/";
	
	var tablePager			= '#TableCatalogPager';
	var table 				= $('#TableCatalog');
	
	var tableTovar	    	= $('#TableTovar');	
	var urlTovar			= baseUrl+"loadzakaz";
	
	var dialogEdit	 		= $("#dialog-edit");
	
	var tree				= $("#tree_managers");
	var b_loadUrl			= baseUrl+"load";
	var b_openUrl			= baseUrl+"open";
	var b_callUrl			= baseUrl+"call";
	
	var b_adoptedupdate		= baseUrl+"adoptedupdate";
	var b_dataAccess		= accessURL+"access";	
	var lastsel;
	var array 				= [];
	var adopted				= 0;
	
	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_TableCatalog').height(28);

			if (access['couriers_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			date_ot =  '<label>ПОИСК ПО ДАТЕ: ОТ </label><input type="text" id="date_ot">';
			date_do =  '<label>ДО </label><input type="text" id="date_do">';
			date_btn =  '<a href="" class="date_btn btn-cur">Найти</a>';
			input_html = '<label>ЗП:</label><input type="text" class="total_zp"/></div>';
			
			if (access['id']==1) {
				btn_ok = '<a href="#" class="btn-cur" id="btn-apply">Заплатил</a>';
				adopted_html = '<div class="block-adopted"><input type="checkbox" name="adopted" id="chk-adopted" /><label for="chk-adopted">История</label></div>';
			} else {
				btn_ok = '';
				adopted_html = '';
			}

			$('#t_TableCatalog').append('<div class="toolbar_top" id="select_form"><div class="toolbar-block">' + edit + btn_ok + adopted_html + date_ot + date_do + date_btn + input_html + '</div></div>');

			
			// Обработка события кнопки Редактировать	
			$("#editdata").click(function(){
				gridEdit();
			});
		
			$("#TableCatalog").delegate(".editdata", "click", function(){
				gridEdit($(this).attr('rel'));
			});	

			
		}						
	});
	
	function reloadTableWidthParam() {
		var id 	    = (tree.find(".active").attr('id')) ? tree.find(".active").attr('id') : 0,
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val();

			table.jqGrid('setGridParam',{url: b_loadUrl+'?adopted='+adopted+'&date_ot='+date_ot+'&date_do='+date_do+'&id_tree='+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");

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
        colNames:[' ','№з','Наименование','М','Оплатил','ЗП','Время','Дата','Дата продажи'],
        colModel :[
			{name:'act',			index:'act', 			width:12,   align:'center', search:false, sortable:false},
            {name:'nomer_zakaza', 	index:'nomer_zakaza', 	width:25, 	align:'center', search:false, },
            {name:'name',			index:'name', 			width:200, 	align:'left', 	search:false, },
            {name:'id_managers', 	index:'id_managers', 	width:20, 	align:'center', search:false, },      
            {name:'adopted',		index:'adopted',		width:30,	align:'center', search:false, },          
		    {name:'total_zp', 		index:'total_zp', 		width:30, 	align:'center', search:false, sorttype:'int', formatter:'int',summaryType:'sum' },
		    {name:'time_call', 		index:'time_call', 		width:30, 	align:'center', search:false, },
		    {name:'date_call', 		index:'date_call', 		width:30, 	align:'center', search:false, },
			{name:'date_sell', 		index:'date_sell', 		width:45,   align:'center', search:false, formatter: getDayWeek},
			],
        pager: tablePager,
		toolbar: [true,"top"],		
        sortname: 'id',
        sortorder: 'asc',
		rowNum:50,
		viewrecords: true,
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
			$(".total_zp").val(myUserData.total_zp);		
					
		},		
		grouping:true,
		groupingView : {
			groupField : ['date_sell'],
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
		footerrow: true,
		userDataOnFooter: true,			
    }).jqGrid('navGrid', tablePager,{refresh: true, add: false, del: false, edit: false, search: false, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{});
	
	function ina(looking_for, list) {
		for(i in list) {
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
							if ($.inArray(key, Array('was', 'delivered', 'passed')) != -1) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }	
							} else {							
								$('#val_'+key).val(res[key]);
								if (key == 'id')
								tableTovar.jqGrid('setGridParam',{url: urlTovar+"?id_client="+res[key],page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");	
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

	var t_TableCatalog = $("#t_TableCatalog");
	
	t_TableCatalog.delegate("#btn-apply", "click", function(){
		var id_managers = tree.find(".active").attr('id');
		
		$.ajax({type: "POST",url: b_adoptedupdate,dataType: "json",data: "array="+array});
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
		reloadTableWidthParam();
	});	
	
	// Инициализация формы добавления и редактирования данных					
	dialogEdit.dialog({
		autoOpen: false,
		width: 910,
		minheight: 'auto',
		title: "Примечание",
		buttons: [
			{
				text: "Отзвонил",
				click: function() {
					var str = $("#form").serialize();
						$.ajax({
							type: "POST",
							url: b_callUrl,
							dataType: "json",
							data: str+"&call=1",
							success:function (res, f) {//возвращаемый результат от сервера		
								if (f === "success" && res['succes']) {
									table.trigger("reloadGrid");	
									dialogEdit.dialog( "close" );
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
					}
			}
		]
	});
		
});