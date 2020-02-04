$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog/";
	var TablePohozhie	   	= $('#TablePohozhie');
	var b_pohozhieUrl		= baseUrl+"loadPS";	
	var treeCatalogPohozhie = $("#tree_catalog_pohozhie");
	var ValPohozhie			= $("#val_pohozhie");

	var options = {
		collapsed: true,
		animated: "fast",
		unique: false
	}		

	treeCatalogPohozhie.treeview(options );
	
	// при нажатии на ссылку на дереве
	treeCatalogPohozhie.delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
			if (id) {	
				var level = $("#"+id).parents('li').length;
			} else { 
				var level = 1;
					   id = 1; 
			}
	
		treeCatalogPohozhie.find(".active").removeClass("active");
		$(this).addClass('active');
	
		TablePohozhie.jqGrid('setGridParam',{url: b_pohozhieUrl+"?id="+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
		
	});		

	
	// Инициализация таблицы картинок
	TablePohozhie.jqGrid({
		//url:b_pohozhieUrl,
		datatype: 'local',
		//mtype: 'GET',
		autowidth: true,
		height: 354,
		colNames:['Название'],
		colModel :[
			{name:'name', 		index:'name', 		width:440, 	align:'left'},
			],
		sortname: 'name',
		caption:'Фильтр: <input type="search" id="gridsearch" placeholder="" results="0" class="gridsearch" />',		
		multiselect: true,
        sortorder: 'asc',
		forceFit: true,				
		rowNum:999,		
		idPrefix: "pohozhieId",
		//loadonce: true,		
		viewrecords: true,
		loadComplete: function(){ 
				
			var arr = ValPohozhie.val().split(',');
			var i = arr.length;

			while(i--){

				TablePohozhie.jqGrid('setSelection',arr[i]);

			}
		},
		gridComplete: function() {
			searchColumn = TablePohozhie.jqGrid('getCol','name',true);			
		},		
		onSelectRow : function (rowid, status) {
			var array = ValPohozhie.val().split(',');

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
			ValPohozhie.val(array);
		},
		onSelectAll	: function (aRowids, status) {
			var array = ValPohozhie.val().split(',');
			if (status)
			{
				var i = aRowids.length;
					while(i--) {
						if (!ina(aRowids[i],array)) { array.push(aRowids[i]);	}
					}		
			} else {
				array = [];	
			}
			ValPohozhie.val(array);		
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
	$('#gridsearch').keyup(function () {
		var searchString = $(this).val().toLowerCase()
		$.each(searchColumn,function() {
			if(this.value.toLowerCase().indexOf(searchString) == -1) {
				$('#'+this.id).hide()
			} else {
				$('#'+this.id).show()
			}
		})
	});	
	
});