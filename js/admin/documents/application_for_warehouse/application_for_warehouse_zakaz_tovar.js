$(document).ready(function() {
	var baseUrl			= "/adminpanel/application_for_warehouse/";

	var table 			= $('#le_tableTovar');
	var pager 			= '#le_tableTovarPager';

	var EditTovarUrl 	= baseUrl+"editwarehouse";
	var SkladTovarUrl 	= baseUrl+"getskladhtml";
	var lastsel;
	

	table.jqGrid({
		datatype: 'local',
		width: 860,
		height: 'auto',
        colNames:[' ','Наименование',' ','Отгр','Кол-во','Сумма $','Сумма б.р.','Возврат','Курьеры','Статус',' '],
        colModel :[
			{name:'actsklad',	index:'actsklad', 	width:20,   align:'center', search:false, sortable:false},		
            {name:'name', 		index:'name', 		width:400, 	align:'left', 	search:false, },
			{name:'actedit',	index:'actedit', 	width:16,   align:'center', search:false, sortable:false},						
            {name:'shipped', 	index:'shipped', 	width:25, 	align:'center', search:false, editable: true, edittype: "checkbox", editoptions:{value:"Да:Нет"}},
            {name:'kolvo', 		index:'kolvo', 		width:30, 	align:'center', search:false, },
            {name:'cena', 		index:'cena', 		width:50, 	align:'center', search:false, },
            {name:'cena_blr', 	index:'cena_blr', 	width:60, 	align:'center', search:false, },
            {name:'vozvrat',	index:'vozvrat',	width:40, 	align:'center', search:false, editable: true, edittype:"select"},
            {name:'id_couriers',index:'id_couriers',width:70, 	align:'center', search:false, },			
            {name:'status',		index:'status',		width:50, 	align:'center', search:false, },			
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
				bsklad = '<div" rel="'+cl+'" class="button copy sklad-show"></div>';
				table.jqGrid('setRowData',ids[i],{actedit:be,actsave:bs,actsklad:bsklad});
			}				
		
		},	
		loadComplete: function(){ 
			table.setColProp('vozvrat',{'editoptions': {'value':'0:нет;1:склад;2:офис;3:брак'}});
		},			
		editurl:EditTovarUrl,
		footerrow: true,
		userDataOnFooter: true,			
    }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: false},
					{}, // параметры редактирования
					{}, // параметры добавления
					{}, // параметры удаления
					{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true}, // параметры поиска
					{} /* параметры просмотра */
	);
	
	table.delegate(".editdata", "click", function(){
		var id = $(this).attr('rel');
		if(id){
			table.jqGrid('editRow',id,true);
		}
	});
	table.delegate(".savedata", "click", function(){
		var id = $(this).attr('rel');
		
		if(id) {
			params = {
				"keys" : true,
				"aftersavefunc" : function(id, res, option) {

					var arr = JSON.parse(res.responseText);
					if (arr['succes']==true) {	
						if (arr['total']!=0) $("#val_total").val(arr['total']);
						table.trigger("reloadGrid");
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
	
	table.delegate(".sklad-show","click",function() {
		var id = $(this).attr('rel');
		if (id) {
			
			$.ajax({
				type: "POST",
				url: SkladTovarUrl,
				data: "id="+id,
				dataType: "json",
				success:function (res) {		
					showMsgBox(res['html'], "white", "center");							
				}	
			});
			
		}		
	});
	
});