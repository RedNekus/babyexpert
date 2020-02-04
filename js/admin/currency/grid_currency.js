$(document).ready(function() {
	var $tabs 			= $('#tabs-item').tabs();
	var baseUrl			= "/adminpanel/currency/";
	
	var pager 				= '#le_tablePager';
	var table 				= $('#le_table');
	var dialogForm 			= $("#dialogForm");
	var b_gridUrl	 		= baseUrl+"load";
	var b_editUrl 			= baseUrl+"edit";
	var b_dataHandling 		= baseUrl+"datahandling";
	var b_dataTreeInit 		= baseUrl+"datatreeinit";
	var b_dataCurAdd 		= baseUrl+"curgroupadd";
	var b_dataSelect 		= baseUrl+"select";
	var id_currency_tree 	= 1;
	var select_form 		= "";
	var array 				= [];
			
	$("#tree_catalog").delegate(".linkrel", "click", function(){

		id_currency_tree = $(this).attr('id');
		pid = $(this).parent();
		$('#tree_catalog').find(".active").removeClass("active");
		$(this).addClass('active');
		
		$.ajax({type: "POST",url: b_dataTreeInit,dataType: "json",data: "id="+id_currency_tree,
					success:function (res) {//возвращаемый результат от сервера
						$("#select_form").empty();
						$("#select_form").html(res['select_form']);
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});
		
		
		table.jqGrid('setGridParam',{url: b_gridUrl+"?id_currency_tree="+id_currency_tree,page:1}).trigger("reloadGrid");

	});	
	
	table.jqGrid({
		url:b_gridUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,		
        colNames:['ID', 'Название', 'Производитель', 'Название раздела','Курс','Название курса'],
        colModel :[
			{name:'id', 			index:'id', 			width:35, 	search:false, },
            {name:'name', 			index:'name', 			width:200, 	search:true,  searchoptions:{sopt:['cn']} },
			{name:'id_maker', 		index:'id_maker', 		width:120, 	search:true,  searchoptions:{sopt:['cn']}, },
			{name:'id_razdel1', 	index:'id_razdel1', 	width:120, 	sortable:false, stype:'select', search:true, surl:b_dataSelect, searchoptions:{sopt:['cn']},  },
			{name:'kurs', 			index:'kurs', 			width:120, 	search:false, },
			{name:'id_currency', 	index:'id_currency', 	width:120, 	search:false, },
			],
        pager: pager,
		toolbar: [true,"top"],
		sortname: 'id',	
		multiselect: true,
        sortorder: 'asc',
		forceFit: true,				
		rowNum:50,	
		viewrecords: true,
		gridComplete: function() {
			searchColumn = table.jqGrid('getCol','name',true);			
			searchColumnMaker = table.jqGrid('getCol','id_maker',true);			
			searchColumnRazdel = table.jqGrid('getCol','id_razdel1',true);			
		},
		onSelectRow : function (rowid, status) {

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
		},
		onSelectAll	: function (aRowids, status) {
			array = [];
			if (status)
			{
				var i = aRowids.length;
					while(i--) {
						if (!ina(aRowids[i],array)) { array.push(aRowids[i]);	}
					}		
			} else {
				array = [];	
			}		
		},		
		rownumbers: true,
        rownumWidth: 30,		
		editurl:b_editUrl
        }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: false},
					{}, // параметры редактирования
					{}, // параметры добавления
					{}, // параметры удаления
					{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true}, // параметры поиска
					{} /* параметры просмотра */
				);
		table.jqGrid('navSeparatorAdd','#le_tablePager');
		table.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$('#t_le_table').height(24);
		filtr_form = '<div class="toolbar_top"><label for="">Название:</label> <input type="search" id="gridsearch" placeholder="" results="0" class="gridsearch" /></div>';
		filtr_form2 = '<div class="toolbar_top"><label for="">Производитель:</label> <input type="search" id="gridsearch_maker" placeholder="" results="0" class="gridsearch_maker" /></div>';
		filtr_form3 = '<div class="toolbar_top"><label for="">Название раздела:</label> <input type="search" id="gridsearch_razdel" placeholder="" results="0" class="gridsearch_razdel" /></div>';
		select_form = '<div class="toolbar_top" id="select_form"></div>';
		$('#t_le_table').append(select_form);		

	
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
	
	//for live filtering search
	$('#gridsearch_maker').keyup(function () {
		var searchString = $(this).val().toLowerCase()
		$.each(searchColumnMaker,function() {
			if(this.value.toLowerCase().indexOf(searchString) == -1) {
				$('#'+this.id).hide()
			} else {
				$('#'+this.id).show()
			}
		})
	});		
	
	//for live filtering search
	$('#gridsearch_razdel').keyup(function () {
		var searchString = $(this).val().toLowerCase()
		$.each(searchColumnRazdel,function() {
			if(this.value.toLowerCase().indexOf(searchString) == -1) {
				$('#'+this.id).hide()
			} else {
				$('#'+this.id).show()
			}
		})
	});		

	$("#t_le_table").delegate("#btn-currency", "click", function(){
		var id_cur = $("#select-currency").val();
		
		$.ajax({type: "POST",url: b_dataCurAdd,dataType: "json",data: "id="+id_cur+"&array="+array});
		table.trigger("reloadGrid");
		return false;
	});

});