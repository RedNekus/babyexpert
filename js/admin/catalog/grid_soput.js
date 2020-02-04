$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog/";
	var TableSoput	   		= $('#TableSoput');
	var bSoputUrl			= baseUrl+"loadPS";	
	var treeCatalogSoput 	= $("#tree_catalog_soput");
	var ValSoput			= $("#val_soput");

	var options = {
		collapsed: true,
		animated: "fast",
		unique: false
	}		

	treeCatalogSoput.treeview(options );
	
	// при нажатии на ссылку на дереве
	treeCatalogSoput.delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
			if (id) {	
				var level = $("#"+id).parents('li').length;
			} else { 
				var level = 1;
					   id = 1; 
			}
	
		treeCatalogSoput.find(".active").removeClass("active");
		$(this).addClass('active');
	
		TableSoput.jqGrid('setGridParam',{url: bSoputUrl+"?id="+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
		
	});		

	
	// Инициализация таблицы картинок
	TableSoput.jqGrid({
		//url:bSoputUrl,
		datatype: 'local',
		//mtype: 'GET',
		autowidth: true,
		height: 354,
		colNames:['Название'],
		colModel :[
			{name:'name', 		index:'name', 		width:440, 	align:'left'},
			],
		sortname: 'name',
		caption:'Фильтр: <input type="search" id="gridsearchS" placeholder="" results="0" class="gridsearch" />',		
		multiselect: true,
        sortorder: 'asc',
		forceFit: true,
		rowNum:999,
		idPrefix: "soputId",
		//loadonce: true,		
		viewrecords: true,
		loadComplete: function(){ 
				
			var arr = ValSoput.val().split(',');
			var i = arr.length;

			while(i--){

				TableSoput.jqGrid('setSelection',arr[i]);

			}
		},
		gridComplete: function() {
			searchColumn = TableSoput.jqGrid('getCol','name',true);			
		},		
		onSelectRow : function (rowid, status) {
			var array = ValSoput.val().split(',');

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
			ValSoput.val(array);
		},
		onSelectAll	: function (aRowids, status) {
			var array = ValSoput.val().split(',');
			if (status)
			{
				var i = aRowids.length;
					while(i--) {
						if (!ina(aRowids[i],array)) { array.push(aRowids[i]);	}
					}		
			} else {
				array = [];	
			}
			ValSoput.val(array);		
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
	$('#gridsearchS').keyup(function () {
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