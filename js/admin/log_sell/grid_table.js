$(document).ready(function() {
	var baseUrl			= "/adminpanel/log_sell/";
	
	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var b_gridUrl	 	= baseUrl+"load";
	
	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 	
		
	table.jqGrid({
		url:b_gridUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:['Код','№ заказа','Код товара','Кол-во','Дата/время','Автор'],
        colModel :[
			{name:'id', 			index:'id', 			width:30, 	align:'center', 	search:false, 	},
            {name:'id_client', 		index:'id_client', 		width:150, 	align:'left', 		search:false, 	},
            {name:'id_item',		index:'id_item', 		width:150, 	align:'left',	 	search:false, 	},
			{name:'kolvo', 			index:'kolvo', 			width:150, 	align:'left', 		search:false, 	},
			{name:'date_create', 	index:'date_create', 	width:150, 	align:'left', 		search:false, 	},
			{name:'id_adminuser', 	index:'id_adminuser', 	width:150, 	align:'left', 		search:false, 	},
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'asc',
		rowNum:50,
		viewrecords: true,
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30
        }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: false, view: false},
					{}, // параметры редактирования
					{}, // параметры добавления
					{}, // параметры удаления
					{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true}, // параметры поиска
					{} /* параметры просмотра */
				);

});