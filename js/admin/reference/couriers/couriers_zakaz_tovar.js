$(document).ready(function() {
	var baseUrl			= "/adminpanel/couriers/";

	var table 			= $('#TableTovar');
	var pager 			= '#TableTovarPager';

	var EditTovarUrl 	= baseUrl+"editcouriers";
	var lastsel;
	
	
	table.jqGrid({
		datatype: 'local',
		width: 860,
		height: 'auto',
        colNames:['Наименование',' ','Б','Д','Кол-во','Сумма $','Сумма б.р.','Сдал','Статус','Продано',' '],
        colModel :[
            {name:'name', 		index:'name', 		width:200, 	align:'left', 	search:false, },
			{name:'actedit',	index:'actedit', 	width:16,   align:'center', search:false, sortable:false},			
            {name:'was', 		index:'was', 		width:20, 	align:'center', search:false  },
            {name:'delivered', 	index:'delivered', 	width:20, 	align:'center', search:false  },
            {name:'kolvo', 		index:'kolvo', 		width:30, 	align:'center', search:false, },
            {name:'cena', 		index:'cena', 		width:60, 	align:'center', search:false, },
            {name:'cena_blr', 	index:'cena_blr', 	width:90, 	align:'center', search:false, },
            {name:'passed', 	index:'passed', 	width:90, 	align:'center', search:false, },
			{name:'status',		index:'status', 	width:50,   align:'center', search:false, sortable:false},
			{name:'checked',	index:'checked', 	width:50,   align:'center', search:false, editable: true, edittype: "checkbox", editoptions:{value:"Да:Нет"}},
			{name:'actsave',	index:'actsave', 	width:16,   align:'center', search:false, sortable:false},
			],		
		pager: pager,	
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'asc',
		gridComplete : function() {	
			var ids = table.jqGrid('getDataIDs');
			for(var i=0;i < ids.length;i++) {
				var cl = ids[i];
				be = '<div" rel="'+cl+'" class="button edit editdata"></div>';
				bs = '<div" rel="'+cl+'" class="button save savedata"></div>';
				table.jqGrid('setRowData',ids[i],{actedit:be,actsave:bs});
			}				
		
		},		
		editurl:EditTovarUrl
    }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: false},
					{}, // параметры редактирования
					{}, // параметры добавления
					{}, // параметры удаления
					{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true}, // параметры поиска
					{} /* параметры просмотра */
	);
	
	$("#TableTovar").delegate(".editdata", "click", function(){
		var id = $(this).attr('rel');
		if(id){
			//table.jqGrid('restoreRow',lastsel);
			table.jqGrid('editRow',id,true);
		}
	});
	
	$("#TableTovar").delegate(".savedata", "click", function(){
		var id = $(this).attr('rel');
		
		if(id) {
			//table.jqGrid('restoreRow',lastsel);
			params = {
				"keys" : true,
				"aftersavefunc" : function(id, res, option) {

					var arr = JSON.parse(res.responseText);
					if (arr['succes']==true) {			
						table.trigger("reloadGrid");
						$("#val_total").val(arr['total']);
						$("#val_t_total").val(arr['total']);
						$("#val_total_blr").val(arr['total_blr']);
						$("#val_t_total_blr").val(arr['total_blr']);
						$("#val_zp").val(arr['zp']);
					} else {
						table.trigger("reloadGrid");
						if (arr['msg']!='') showMsgBox(arr['msg'], "white", "center");
					}

				},
				"mtype" : "POST"
			}					
			table.jqGrid('saveRow',id,params);
		}
	});	
	
	$("#val_total").change(function() {
		
		var usd = $(this).val(),
			kurs = $("#val_kurs").val(),
			usd_t = $("#val_t_total").val(),
			bur = $("#val_t_total_blr").val();
			
			result = usd_t - usd;
			
			bur_t = result * kurs;
			
			bur = parseInt(bur) + parseInt(bur_t);
			
		$("#val_total_blr").val(bur);
		
	});
	
});