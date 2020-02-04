$(document).ready(function() {
	var baseUrl				= "/adminpanel/raffle/";
	var TableTovar	   		= $('#TableTovar');
	var bTovarUrl			= baseUrl+"loadPS";	
	var treeCatalogTovar 	= $("#tree_catalog_tovar");
	var ValTovar			= $("#val_ids_catalog");

	var options = {
		collapsed: true,
		animated: "fast",
		unique: false
	}		

	treeCatalogTovar.treeview(options );
	
	// при нажатии на ссылку на дереве
	treeCatalogTovar.delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
			if (id) {	
				var level = $("#"+id).parents('li').length;
			} else { 
				var level = 1;
					   id = 1; 
			}
	
		treeCatalogTovar.find(".active").removeClass("active");
		$(this).addClass('active');
	
		TableTovar.jqGrid('setGridParam',{url: bTovarUrl+"?id="+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
		
	});		

	
	// Инициализация таблицы картинок
	TableTovar.jqGrid({
		//url:bTovarUrl,
		datatype: 'local',
		//mtype: 'GET',
		autowidth: true,
		height: 354,
		colNames:['Название'],
		colModel :[
			{name:'name', 		index:'name', 		width:440, 	align:'left'},
			],
		sortname: 'raffle',
		caption:'Фильтр: <input type="search" id="gridsearchP" placeholder="" results="0" class="gridsearch" />',		
		multiselect: true,
        sortorder: 'desc',
		forceFit: true,
		rowNum:999,
		//idPrefix: "tovarId",
		//loadonce: true,		
		viewrecords: true,
		loadComplete: function(){ 
				
			var arr = ValTovar.val().split(',');
			var i = arr.length;

			while(i--){

				TableTovar.jqGrid('setSelection',arr[i]);

			}
		},
		gridComplete: function() {
			searchColumn = TableTovar.jqGrid('getCol','name',true);			
		},		
		onSelectRow : function (rowid, status) {
			var array = ValTovar.val().split(',');

			if (status)
			{
				if (!ina(rowid,array)) { array.push(rowid);	}
			} else {
				if (ina(rowid,array)) {
					for (i in array)
						{
							if(rowid == array[i]){ array.splice(i,1); }
						}
				}	
			}
			ValTovar.val(array);
		},
		onSelectAll	: function (aRowids, status) {
			var array = ValTovar.val().split(',');
			if (status)
			{
				var i = aRowids.length;
					while(i--) {
						if (!ina(aRowids[i],array)) { array.push(aRowids[i]);	}
					}		
			} else {
				array = [];	
			}
			ValTovar.val(array);		
		}
	});	
	
	function ina(looking_for, list){
		for(i in list){
			if(looking_for == list[i]){
				return true;
			}
		}
		return false;
	}	

	//for live filtering search
	$('#gridsearchP').keyup(function () {
		var searchString = $(this).val().toLowerCase()
		$.each(searchColumn,function() {
			if(this.value.toLowerCase().indexOf(searchString) == -1) {
				$('#'+this.id).hide()
			} else {
				$('#'+this.id).show()
			}
		})
	});	
	
	$('#ui-id-5').click(function () {
		
		$("#tree_catalog_tovar ul li a:first").click();
		
	});
	
});