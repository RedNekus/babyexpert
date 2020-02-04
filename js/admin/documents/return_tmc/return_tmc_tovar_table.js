$(document).ready(function() {
	var baseUrl				= "/adminpanel/return_tmc/";

	var pager1 				= '#le_table_sklad_tovarPager';
	var table1				= $('#le_table_sklad_tovar');

	var pagerTovar 			= '#le_table_tovarPager';
	var tableTovar			= $('#le_table_tovar');
	
	var loadTableUrl		= baseUrl+"load_sklad_tovar";
	
	var loadTableTovarUrl	= baseUrl+"load_tovar";
	
	var loadSelectMakerUrl	= baseUrl+"load_select_maker";
	var loadSelectTovarUrl	= baseUrl+"load_select_tovar";
	
	var editDataUrl			= baseUrl+"edit_tovar";
	
	var dialogSkladAdd		= $("#dialog-sklad-add");
	var lastsel;
	
	var tabPodbor			= $('.admin-tabs ul li:nth-child(2)');	
	
	var getCenaUrl			= baseUrl+"get_cena";
	
	var searchPrefix;			
	var searchMaker;	
				
	tableTovar.jqGrid({
		datatype: 'local',
		width: 1151,
		height: 300,		
		colNames:[' ','Префикс','Производитель','Название','Цена $','Дата','№ заказа'],
		colModel :[
			{name:'actadd',			index:'actadd',			width:25,	align:'center'},		
			{name:'prefix', 		index:'prefix', 		width:250, 	align:'left'},
			{name:'maker', 			index:'maker', 			width:250, 	align:'left'},
			{name:'name', 			index:'name', 			width:350, 	align:'left'},
			{name:'cena', 			index:'cena', 			width:150, 	align:'left'},
			{name:'date_dostavka', 	index:'date_dostavka', 	width:150, 	align:'center'},
			{name:'nomer_zakaza', 	index:'nomer_zakaza', 	width:150, 	align:'center'},
			],
		pager: pagerTovar,	
		sortname: 'name',
		sortorder: 'asc',
		rowNum:200,
		gridComplete : function() {	
			var ids = tableTovar.jqGrid('getDataIDs');
			for(var i=0;i < ids.length;i++) {
				var cl = ids[i];
				ba = '<div" rel="'+cl+'" class="button add adddata"></div>';
				tableTovar.jqGrid('setRowData',ids[i],{actadd:ba});
			}				
		
		},		
		ondblClickRow: function(id) {
			podborTovar(id);
		},		
		viewrecords: true,		
		rowList:[200,400,700,1200],
		rownumbers: true,
		rownumWidth: 30		
	}).jqGrid('navGrid', pagerTovar,{refresh: true, add: false, del: false, edit: false, search: false, view: false});

	function podborTovar(id) {
		$("#form-tovar-add").trigger("reset");
		$("#vals_id_zakaz").val(id);
		$("#vals_id_delivery_tmc").val($("#val_id_delivery_tmc").val());
		
		$.ajax({
			type: "POST",
			url: getCenaUrl,
			data: "id_zakaz="+id,
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера		
				$("#vals_cena").val(res['cena']);								
				$("#vals_kolvo_hold").val(res['kolvo']);								
			}	
		});		
		
		$("#vals_action").val('add');			
		dialogSkladAdd.dialog('open');	
	}
	
	tableTovar.delegate(".adddata", "click", function(){			
		var id = $(this).attr('rel');
		podborTovar(id);
	});

	$(".val_id_tree").change(function() {
		var id_tree = $(this).val();
		
		$(".val_id_maker").val(0);
		$(".val_id_tovar").val(0);
		
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
			
		$(".val_id_tovar").val(0);	
		
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
	
	$(".podbor").change(function() {
		var data = $("#form-sklad-tovar").serialize(),
			id_kontragent = $("#val_id_kontragent").val();
		tableTovar.jqGrid('setGridParam',{url: loadTableTovarUrl+"?"+data+"&id_kontragent="+id_kontragent,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
	});

	function loadGrid(grid,pager,height) {

		grid.jqGrid({
			//url:loadTableUrl,
			datatype: 'local',
			//mtype: 'GET',
			width: 1151,
			height: height,		
			colNames:['Префикс','Производитель','Наименование','Кол-во','Цена','Цена продажи','Сумма','Цена $','Сумма $','Роз цена','№ заказа'],
			colModel :[
				{name:'prefix', 	index:'prefix', 	width:150, 	align:'left' },
				{name:'maker', 		index:'maker', 		width:150, 	align:'left' },				
				{name:'name', 		index:'name', 		width:200, 	align:'left' },
				{name:'kolvo_hold', index:'kolvo_hold', width:45, 	align:'center', editable: true, edittype: "text"},
				{name:'cena_opt', 	index:'cena_opt', 	width:90, 	align:'center', editable: true, edittype: "text"},
				{name:'cena_sell', 	index:'cena_sell', 	width:90, 	align:'center' },
				{name:'sum_opt', 	index:'sum_opt', 	width:70, 	align:'center' },
				{name:'cena_ye', 	index:'cena_ye', 	width:70, 	align:'center' },		
				{name:'sum_ye', 	index:'sum_ye', 	width:70, 	align:'center' },		
				{name:'cena', 		index:'cena', 		width:70, 	align:'center' },
				{name:'id_zakaz', 	index:'id_zakaz', 	width:70, 	align:'center' },
				],
			pager: pager,
			sortname: 'id',
			sortorder: 'desc',
			rowNum:50,		
			viewrecords: true,	
			rowList:[50,100,150,200],
			rownumbers: true,
			rownumWidth: 30,		
			loadComplete: function() {
				var sum_opt = grid.jqGrid('getCol', 'sum_opt', false, 'sum'),
					sum_delta = grid.jqGrid('getCol', 'sum_delta', false, 'sum'),
					cena_ye = grid.jqGrid('getCol', 'cena_ye', false, 'sum'),
					sum_ye = grid.jqGrid('getCol', 'sum_ye', false, 'sum'),
					sum_kolvo = grid.jqGrid('getCol', 'kolvo', false, 'sum');
				grid.jqGrid('footerData','set', {name: 'Итого:', sum_opt: sum_opt,sum_delta: sum_delta,kolvo: sum_kolvo,cena_ye: cena_ye,sum_ye: sum_ye});			
			},
			footerrow: true,		
			editurl:editDataUrl
		}).jqGrid('navGrid', pager,{refresh: true, add: false, del: true, edit: false, search: false, view: false});

	}

	
	loadGrid(table1,pager1,150);

	$(".prefix-app").delegate('.search-prefix','change',function() {
		var searchString = $(this).val().toLowerCase()
		$.each(searchPrefix,function() {
			if(this.value.toLowerCase().indexOf(searchString) == -1) {
				$('#'+this.id).hide()
			} else {
				$('#'+this.id).show()
			}
		})		
	});
	
	$(".maker-app").delegate('.search-maker','change',function() {
		var searchString = $(this).val().toLowerCase()
		$.each(searchMaker,function() {
			if(this.value.toLowerCase().indexOf(searchString) == -1) {
				$('#'+this.id).hide()
			} else {
				$('#'+this.id).show()
			}
		})		
	});

		
	tabPodbor.click(function() {

		var id = $("#val_id_delivery_tmc").val(),
			valute = $("#val_id_valute").val();

		table1.jqGrid('setGridParam',{url: loadTableUrl+'?id_delivery_tmc='+id+'&valute='+valute+'&kurs=1&status=0',page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
	
	});	

	function AddTovarToSklad() {
			
		var str = $("#form-tovar-add").serialize();
		$.ajax({
			type: "POST",
			url: editDataUrl,
			data: str,
			});
		var id = $("#val_id_delivery_tmc").val(),
			valute = $("#val_id_valute").val();
		table1.jqGrid('setGridParam',{url: loadTableUrl+'?id_delivery_tmc='+id+'&valute='+valute,page:1,datatype: 'json',mtype: 'GET'});
		
		function ttt() {
			table1.trigger("reloadGrid")		
		}
		
		setTimeout(ttt,500);
		
		dialogSkladAdd.dialog( "close" );						
		
	}

	
	dialogSkladAdd.dialog({
		autoOpen: false,
		title: "Параметры",
		width: 620,
		buttons: [
			{
				text: "Добавить",
				click: AddTovarToSklad
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