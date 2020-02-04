$(document).ready(function() {
	var baseUrl				= "/adminpanel/adminusers_stats/";
	var accessURL			= "/adminpanel/adminaccess/";
	
	var TableStatsPager			= '#TableStatsPager';
	var TableStats 				= $('#TableStats');	
	var LoadStatsUrl			= baseUrl+"load";
	var tree					= $('#tree_stats');
	var treePaid				= $('#tree_statsPaid');
	var selectRow;
	var b_dataAccess			= accessURL+"access";
	var t_TableStats			= '#t_TableStats';
	
	var b_dataStatsAdd 			= baseUrl+"statsadd";	
	var array 					= [];
	var cena					= 1;
	
	var options = {
		collapsed: true,
		animated: "fast",
		unique: false,
		persist: "cookie"
	}			
		
	tree.treeview(options );
	
	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$(t_TableStats).height(60);
			$(t_TableStats).append('<div class="toolbar_top" id="select_form"></div>');
			
			pay = '<a href="" class="btn-cur" id="btn-stats">Оплатить</a>';	

			sum = '<div id="summa">Сумма выделенных столбцов = <span>0</span> $</div>';
			
			add = '<a href="" class="action btn-cur active" rel="add">Добавлено</a>';
			edit = '<a href="" class="action btn-cur" rel="edit">Изменено</a>';
			view = '<a href="" class="action btn-cur" rel="view">Просмотрено</a>';

			paid0 = '<a href="" class="paid btn-cur select" paid="0">Не оплачено</a>';
			paid1 = '<a href="" class="paid btn-cur" paid="1">Оплачено</a>';
			
			date_ot =  '<label>ПОИСК ПО ДАТЕ: от </label><input type="text" id="date_ot">';
			date_do =  '<label>до </label><input type="text" id="date_do">';
			date_btn =  '<a href="" class="date_btn btn-cur">Найти</a>';
			
			tbUp = add + edit + view + paid0 + paid1 + date_ot + date_do + date_btn;
			tbDown = pay + sum;
			
			$("#select_form").empty();
			$("#select_form").html('<div class="toolbar-block">' + tbUp + '</div><div class="toolbar-block">' + tbDown + '</div>');
			
			$('#t_TableStatsPaid').height(24);

		}						
	});
	
	// при нажатии на ссылку на дереве
	tree.delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
		tree.find(".active").removeClass("active");
		$(this).addClass('active');	
		filtReset();
		var action = $(t_TableStats).find(".active").attr("rel");
		var paid = $(t_TableStats).find(".select").attr("paid");
		TableStats.jqGrid('setGridParam',{url: LoadStatsUrl+"?id="+id+'&paid='+paid+'&action="'+action+'"',page:1}).trigger("reloadGrid");
	});	

	function filtReset() {
		console.log('55678');
		TableStats.jqGrid('setGridParam',{search:false});

		var postData = TableStats.jqGrid('getGridParam','postData');

		$.extend(postData, { filters: "" });

		for (k in postData) {
			if (k == "_search")
				postData._search = false;
			else if ($.inArray(k, ["nd", "sidx", "rows", "sord", "page", "filters"]) < 0) {
				try {
					delete postData[k];
				} catch (e) { }
			}
		}
	}
	
	TableStats.jqGrid({
		url:LoadStatsUrl,
		datatype: 'json',
        mtype: 'GET',
		//cmTemplate:{resizable:false},
		autowidth: true,
		shrinkToFit: true,
        colNames:['Название товара','Действие','Дата и время','Сумма','Оплачено'],
        colModel :[
            {name:'name', 		index:'name', 		width:250, 	align:'left', 	search:true, searchoptions:{sopt:['cn']}},
            {name:'n2',			index:'n2', 		width:180, 	align:'center', search:false},
            {name:'created', 	index:'created', 	width:150, 	align:'center', search:false},
			{name:'n4', 		index:'n4', 		width:150, 	align:'center', search:false},
			{name:'n5', 		index:'n5', 		width:150, 	align:'center', search:false},
			],
        pager: TableStatsPager,
		toolbar: [true,"top"],	
		multiselect: true,	
		rowNum:50,	
		rowList:[50,100,150,200],		
        sortname: 'created',
        sortorder: 'desc',
		viewrecords: true,
		idPrefix: "grid",
		onSelectRow : function (rowid, status) {

			if (status) {
				if (!ina(rowid,array)) { 
					array.push(rowid);
					$("#summa span").html(array.length * cena);		
				}
			} else {
				if (ina(rowid,array)) {
					for (i in array) {
						if(rowid == array[i]){ array.splice(i,1); }
						$("#summa span").html(array.length * cena);
					}
				}	
			}
		},
		onSelectAll	: function (aRowids, status) {
			array = [];
			if (status) {	
				var i = aRowids.length;
				$("#summa span").html(i * cena);
					while(i--) {
						if (!ina(aRowids[i],array)) { array.push(aRowids[i]);	}
					}		
			} else {		
				$("#summa span").html('0');
				array = [];	
			}		
		},	
		gridComplete : function() {
		
			$( "#date_ot" ).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});		
			
			$( "#date_do" ).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});	
			
		},
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
				colNames: ['Название','Тип','Фильтр','Фильтр (toolbar)','Приоритет фильтра'],
				colModel: [
					{name:'sn1', 	index:'n1', 	width:(colModel[2].width), 	align:'left', 		search:false},
					{name:'sn2',	index:'n2', 	width:(colModel[3].width), 	align:'left', 		search:false},
					{name:'sn3', 	index:'n3', 	width:(colModel[4].width), 	align:'center', 	search:false},
					{name:'sn4', 	index:'n4', 	width:(colModel[5].width), 	align:'center', 	search:false},
					{name:'sn5', 	index:'n5', 	width:(colModel[6].width), 	align:'center', 	search:false},
					],
				autowidth: true,	
				height: 'auto',
				rowNum:999,
				ondblClickRow: function(id) {  },
				sortname: 'id',
				sortorder: 'asc',
				onSelectRow: function(id){	}
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
		}
    }).jqGrid('navGrid', TableStatsPager,{refresh: true, add: false, del: false, edit: false, search: true, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{});

	function ina(looking_for, list){
		for(i in list){
			if(looking_for == list[i]){
				return true;
			}
		}
		return false;
	}
	
	$(t_TableStats).delegate("#btn-stats", "click", function(){

		$.ajax({type: "POST",url: b_dataStatsAdd,dataType: "json",data: "array="+array});
		TableStats.trigger("reloadGrid");
		return false;
	});
	
	$(t_TableStats).delegate(".action", "click", function(){
		var id 	 = tree.find(".active").attr('id');
		$("#date_ot").val("");
		$("#date_do").val("");
		if (id) {
			var action = $(this).attr('rel');
			var paid = $(t_TableStats).find(".select").attr("paid");
			$(t_TableStats).find(".active").removeClass("active");
			$(this).addClass('active');	
			filtReset();
			TableStats.jqGrid('setGridParam',{url: LoadStatsUrl+"?id="+id+'&paid='+paid+'&action="'+action+'"',page:1}).trigger("reloadGrid");
		} else {
			alert("Необходимо выбрать пользователя");
		}
		return false;
	});	

	$(t_TableStats).delegate(".paid", "click", function(){
		var id 	 = tree.find(".active").attr('id');
		$("#date_ot").val("");
		$("#date_do").val("");
		if (id) {	
			var action = $(t_TableStats).find(".active").attr("rel");
			var paid = $(this).attr("paid");
			$(t_TableStats).find(".select").removeClass("select");
			$(this).addClass('select');	
			TableStats.jqGrid('setGridParam',{url: LoadStatsUrl+"?id="+id+'&paid='+paid+'&action="'+action+'"',page:1}).trigger("reloadGrid");
		} else {
			alert("Необходимо выбрать пользователя");
		}
		return false;
	});	
	
	$(t_TableStats).delegate(".date_btn", "click", function(){
		var id 	 = tree.find(".active").attr('id'),
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val();
			
		if (id) {	
			var action = $(t_TableStats).find(".active").attr("rel");
			var paid = $(t_TableStats).find(".select").attr("paid");
			TableStats.jqGrid('setGridParam',{url: LoadStatsUrl+"?id="+id+'&paid='+paid+'&action="'+action+'"&date_ot='+date_ot+'&date_do='+date_do,page:1}).trigger("reloadGrid");
		} else {
			alert("Необходимо выбрать пользователя");
		}
		return false;
	});	
				
});