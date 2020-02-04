$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog/";
	var TablePodarok	   	= $('#TablePodarok');
	var bPodarokUrl			= baseUrl+"loadPS";	
	var treeCatalogPodarok 	= $("#tree_catalog_podarok");
	var ValPodarok			= $("#val_podarok");

	var options = {
		collapsed: true,
		animated: "fast",
		unique: false
	}		

	treeCatalogPodarok.treeview(options );
	
	// при нажатии на ссылку на дереве
	treeCatalogPodarok.delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
			if (id) {	
				var level = $("#"+id).parents('li').length;
			} else { 
				var level = 1;
					   id = 1; 
			}
	
		treeCatalogPodarok.find(".active").removeClass("active");
		$(this).addClass('active');
	
		TablePodarok.jqGrid('setGridParam',{url: bPodarokUrl+"?id="+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
		
	});		

	
	// Инициализация таблицы картинок
	TablePodarok.jqGrid({
		//url:bPodarokUrl,
		datatype: 'local',
		//mtype: 'GET',
		autowidth: true,
		height: 354,
		colNames:['Название'],
		colModel :[
			{name:'name', 		index:'name', 		width:440, 	align:'left'},
			],
		sortname: 'name',
		caption:'Фильтр: <input type="search" id="gridsearchP" placeholder="" results="0" class="gridsearch" />',		
		multiselect: true,
        sortorder: 'asc',
		forceFit: true,
		rowNum:999,
		idPrefix: "podarokId",
		//loadonce: true,		
		viewrecords: true,
		loadComplete: function(){ 
				
			var arr = ValPodarok.val().split(',');
			var i = arr.length;

			while(i--){

				TablePodarok.jqGrid('setSelection',arr[i]);

			}
		},
		gridComplete: function() {
			searchColumn = TablePodarok.jqGrid('getCol','name',true);			
		},		
		onSelectRow : function (rowid, status) {
			var array = ValPodarok.val().split(',');

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
			ValPodarok.val(array);
		},
		onSelectAll	: function (aRowids, status) {
			var array = ValPodarok.val().split(',');
			if (status)
			{
				var i = aRowids.length;
					while(i--) {
						if (!ina(aRowids[i],array)) { array.push(aRowids[i]);	}
					}		
			} else {
				array = [];	
			}
			ValPodarok.val(array);		
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
	
	$('#ui-id-12').click(function () {
		
		$("#tree_catalog_podarok ul li a:first").click();
		
	});
	
});