$(document).ready(function() {
	var baseUrl					= "/adminpanel/catalog/";
	var TableKomplect	   		= $('#TableKomplect');
	var bKomplectUrl			= baseUrl+"loadPS";	
	var treeCatalogKomplect 	= $("#tree_catalog_komplect");
	var ValKomplect				= $("#val_komplect");
	var ValKomplectT			= $("#val_komplect_t");

	var options = {
		collapsed: true,
		animated: "fast",
		unique: false
	}		

	treeCatalogKomplect.treeview(options );
	
	// при нажатии на ссылку на дереве
	treeCatalogKomplect.delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
			if (id) {	
				var level = $("#"+id).parents('li').length;
			} else { 
				var level = 1;
					   id = 1; 
			}
	
		treeCatalogKomplect.find(".active").removeClass("active");
		$(this).addClass('active');
	
		TableKomplect.jqGrid('setGridParam',{url: bKomplectUrl+"?id="+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
		
	});		

	
	// Инициализация таблицы картинок
	TableKomplect.jqGrid({
		//url:bKomplectUrl,
		datatype: 'local',
		//mtype: 'GET',
		autowidth: true,
		height: 254,
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
		idPrefix: "komplectId",
		//loadonce: true,		
		viewrecords: true,
		loadComplete: function(){ 
				
			var arr = ValKomplect.val().split(',');
			var i = arr.length;

			while(i--){

				TableKomplect.jqGrid('setSelection',arr[i]);

			}
		},
		gridComplete: function() {
			searchColumn = TableKomplect.jqGrid('getCol','name',true);			
		},		
		onSelectRow : function (rowid, status) {
			var array = ValKomplect.val().split(',');
			var arrayT = ValKomplectT.val().split(',');
			var row = $(this).getRowData(rowid);
			var	name = row['name']+'\n';	
			
			if (status) {
				if (!ina(rowid,array)) { array.push(rowid);	}
				if (!ina(name,arrayT)) { arrayT.push(name);	}				
			} else {
				if (ina(rowid,array)) {
					for (i in array) {
							if(rowid == array[i]){ array.splice(i,1); }
						}
				}	
				if (ina(name,arrayT)) {
					for (i in arrayT) {
							if(name == arrayT[i]){ arrayT.splice(i,1); }
						}
				}				
			}
			ValKomplect.val(array);
			ValKomplectT.val(arrayT);
		},
		onSelectAll	: function (aRowids, status) {
			var array = ValKomplect.val().split(',');
			if (status) {
				var i = aRowids.length;
					while(i--) {
						if (!ina(aRowids[i],array)) { array.push(aRowids[i]);	}
					}		
			} else {
				array = [];	
			}
			ValKomplect.val(array);		
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
	
	$('#ui-id-12').click(function () {
		
		$("#tree_catalog_komplect ul li a:first").click();
		
	});
	
});