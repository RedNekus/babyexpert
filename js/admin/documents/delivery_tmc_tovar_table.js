$(document).ready(function() {
	var baseUrl				= "/adminpanel/delivery_tmc/";

	var pager1 				= '#le_table_sklad_tovarPager';
	var table1				= $('#le_table_sklad_tovar');
	
	var pager2 				= '#le_table_sklad_tovar2Pager';
	var table2 				= $('#le_table_sklad_tovar2');
		
	var pager3 				= '#le_table_sklad_tovar3Pager';
	var table3 				= $('#le_table_sklad_tovar3');
	
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
	var tabPredvar			= $('.admin-tabs ul li:nth-child(3)');
	var tabSklad			= $('.admin-tabs ul li:nth-child(4)');	
	
	var searchPrefix;			
	var searchMaker;	
				
	tableTovar.jqGrid({
		datatype: 'local',
		width: 1151,
		height: 300,		
		colNames:[' ','Префикс','Производитель','Название'],
		colModel :[
			{name:'actadd',		index:'actadd',		width:25,	align:'center'},		
			{name:'prefix', 	index:'prefix', 	width:250, 	align:'left'},
			{name:'maker', 		index:'maker', 		width:250, 	align:'left'},
			{name:'name', 		index:'name', 		width:350, 	align:'left'},
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
		$("#vals_id_item").val(id);
		$("#vals_id_delivery_tmc").val($("#val_id").val());
		$("#vals_action").val('add');			
		dialogSkladAdd.dialog('open');	
	}
	
	tableTovar.delegate(".adddata", "click", function(){			
		var id = $(this).attr('rel');
		podborTovar(id);
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
	
	$(".podbor").change(function() {
		var data = $("#form-sklad-tovar").serialize();
		tableTovar.jqGrid('setGridParam',{url: loadTableTovarUrl+"?"+data,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
	});

	function loadGrid(grid,pager,height) {

		grid.jqGrid({
			//url:loadTableUrl,
			datatype: 'local',
			//mtype: 'GET',
			width: 1151,
			height: height,		
			colNames:[' ','Префикс','Производитель','Наименование','Кол-во','Цена','Сумма','Цена $','Сумма $','Роз цена','Дельта','Сумма дельта',' '],
			colModel :[
				{name:'actedit',	index:'actedit', 	width:25,   align:'center', search:false, sortable:false},
				{name:'prefix', 	index:'prefix', 	width:150, 	align:'left'},
				{name:'maker', 		index:'maker', 		width:150, 	align:'left'},				
				{name:'name', 		index:'name', 		width:200, 	align:'left'},
				{name:'kolvo_hold', index:'kolvo_hold', width:45, 	align:'center', editable: true, edittype: "text"},
				{name:'cena_opt', 	index:'cena_opt', 	width:70, 	align:'center', editable: true, edittype: "text"},
				{name:'sum_opt', 	index:'sum_opt', 	width:70, 	align:'center'},
				{name:'cena_ye', 	index:'cena_ye', 	width:70, 	align:'center'},		
				{name:'sum_ye', 	index:'sum_ye', 	width:70, 	align:'center'},		
				{name:'cena', 		index:'cena', 		width:70, 	align:'center'},
				{name:'delta', 		index:'delta', 		width:70, 	align:'center'},
				{name:'sum_delta', 	index:'sum_delta', 	width:70, 	align:'center'},
				{name:'actsave',	index:'actsave', 	width:25,   align:'center', search:false, sortable:false},
				],
			pager: pager,
			sortname: 'id',
			sortorder: 'desc',
			rowNum:50,		
			viewrecords: true,	
			rowList:[50,100,150,200],
			rownumbers: true,
			rownumWidth: 30,
			gridComplete : function() {	
				var ids = grid.jqGrid('getDataIDs');
				for(var i=0;i < ids.length;i++) {
					var cl = ids[i];
					be = '<div" rel="'+cl+'" class="button edit editdata"></div>';
					bs = '<div" rel="'+cl+'" class="button save savedata"></div>';
					grid.jqGrid('setRowData',ids[i],{actedit:be,actsave:bs});
				}				

				searchPrefix = grid.jqGrid('getCol','prefix',true);			
				searchMaker = grid.jqGrid('getCol','maker',true);			
				
				var allRowsInGrid = grid.jqGrid('getRowData');
				array_m = [];
				array_p = [];
				for (i = 0; i < allRowsInGrid.length; i++) {
					maker = allRowsInGrid[i].maker;
					prefix = allRowsInGrid[i].prefix;
					
					if (!in_array(maker, array_m)) array_m.push(maker);				
					if (!in_array(prefix, array_p)) array_p.push(prefix);
				}
				array_m.sort();
				array_p.sort();
				
				select_m = '<select name="maker" class="search-maker filtr">';
				select_m += '<option value="">Производитель</option>';				
				for(key in array_m) {
					select_m += '<option value="'+array_m[key]+'">'+array_m[key]+'</option>';
				}
				select_m += '</select>';
								
				select_p = '<select name="prefix" class="search-prefix filtr">';
				select_p += '<option value="">Префикс</option>';
				for(key in array_p) {
					select_p += '<option value="'+array_p[key]+'">'+array_p[key]+'</option>';
				}
				select_p += '</select>';
				
				$(".maker-app").empty();
				$(".maker-app").append(select_m);				
				$(".prefix-app").empty();
				$(".prefix-app").append(select_p);
			},		
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

		
		grid.delegate(".editdata", "click", function(){
			var id = $(this).attr('rel');
			if(id) {
				params = {
					"keys" : true,
					"aftersavefunc" : function(res) {
						grid.trigger("reloadGrid");
						alert(res);
						if (res['msg']) alert(res['msg']);
					},
					"mtype" : "POST"
				}				
				grid.jqGrid('editRow',id,params);
			}
		});
		
		grid.delegate(".savedata", "click", function(){
			var id = $(this).attr('rel');
			
			if(id) {
				params = {
					"keys" : true,
					"aftersavefunc" : function() {grid.trigger("reloadGrid");},
					"mtype" : "POST"
				}				
				grid.jqGrid('saveRow',id,params);
			}
		});	

		$(".tmp_kurs").keyup(function() {

			var id = $("#val_id").val(),
				valute = $("#val_id_valute").val(),
				kurs = $(this).val(),
				status = $(this).attr('rel');

			grid.jqGrid('setGridParam',{url: loadTableUrl+'?id_delivery_tmc='+id+'&valute='+valute+'&kurs='+kurs+'&status='+status,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
					
		});
	
		/*$(".filtr").change(function() {
			var data = $(this).closest("form").serialize()
			grid.jqGrid('setGridParam',{url: loadTableTovarUrl+"?"+data,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
		});	*/

	}

	
	loadGrid(table1,pager1,150);
	
	loadGrid(table2,pager2,450);
	
	loadGrid(table3,pager3,450);

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

		var id = $("#val_id").val(),
			valute = $("#val_id_valute").val(),
			kurs = $("#val_kurs").val();

		table1.jqGrid('setGridParam',{url: loadTableUrl+'?id_delivery_tmc='+id+'&valute='+valute+'&kurs='+kurs+'&status=0',page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
	
	});	
	
	tabPredvar.click(function() {

		var id = $("#val_id").val(),
			valute = $("#val_id_valute").val(),
			kurs = $("#val_kurs").val();

		table2.jqGrid('setGridParam',{url: loadTableUrl+'?id_delivery_tmc='+id+'&valute='+valute+'&kurs='+kurs+'&status=1',page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
				
	});	
	
	tabSklad.click(function() {

		var id = $("#val_id").val(),
			valute = $("#val_id_valute").val(),
			kurs = $("#val_kurs").val();

		table3.jqGrid('setGridParam',{url: loadTableUrl+'?id_delivery_tmc='+id+'&valute='+valute+'&kurs='+kurs+'&status=2',page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
				
	});	
	
	function AddTovarToSklad() {
			
		var str = $("#form-tovar-add").serialize();
		$.ajax({
			type: "POST",
			url: editDataUrl,
			data: str,
			});
		var id = $("#val_id").val(),
			valute = $("#val_id_valute").val(),
			kurs = $("#val_kurs").val();
		table1.jqGrid('setGridParam',{url: loadTableUrl+'?id_delivery_tmc='+id+'&valute='+valute+'&kurs='+kurs,page:1,datatype: 'json',mtype: 'GET'});
		
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