$(document).ready(function() {
	var baseUrl				= "/adminpanel/stat_price_monitoring/";
	var accessURL			= "/adminpanel/adminaccess/";
		
	var pager				= '#le_tablePager';
	var table 				= $('#le_table');

	var b_loadUrl			= baseUrl+"load";
	var b_editUrl 			= baseUrl+"edit";

	
	var tree				= $("#tree_connection");
	
	var b_dataAccess			= accessURL+"access";

	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 


	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(28);

			date_ot =  '<label>ДАТА ОТ </label><input type="text" id="date_ot">';
			date_do =  '<label>ДО </label><input type="text" id="date_do">';
			date_btn =  '<a href="" class="date_btn btn-cur">Найти</a>';
															
			$('#t_le_table').append('<div class="toolbar_top" id="select_form"><div class="toolbar-block">' + date_ot + date_do + date_btn + '</div></div>');

		}						
	});

	// при нажатии на ссылку на дереве
	tree.delegate(".linkrel", "click", function(){
		var id_tree = $(this).attr('id');
		$("#val_id_tree").val(id_tree);
		tree.find(".active").removeClass("active");
		$(this).addClass('active');	
		table.jqGrid('setGridParam',{url: b_loadUrl+"?id_tree="+id_tree,page:1}).trigger("reloadGrid");
	});
	
	table.jqGrid({
		url:b_loadUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,
        colNames:['Пользователь','Товар','Кол-во конкурентов','Кол-во просмотров','Кол-во изменений цен','Дата'],
        colModel :[
            {name:'id_adminuser', 	index:'id_adminuser', 	width:85, 	align:'left', 	search:false,  	},
            {name:'product_name', 	index:'product_name', 	width:125, 	align:'left', 	search:false,  	},
            {name:'count_con', 		index:'count_con', 		width:80, 	align:'center', search:false,  	},
            {name:'kolvo', 			index:'kolvo', 			width:80, 	align:'center', search:false,  	},
            {name:'count_cena', 	index:'count_cena', 	width:80, 	align:'center', search:false,  	},
            {name:'date_create', 	index:'date_create', 	width:125, 	align:'center', search:false,  	},

			],
        pager: pager,
		toolbar: [true,"top"],		
        sortname: 'date_create',
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,
		gridComplete: function() {
		
			$( "#date_ot" ).datepicker({
				changeMonth: true,
				dateFormat: 'yy-mm-dd',
				changeYear: true
			});		
			
			$( "#date_do" ).datepicker({
				changeMonth: true,
				dateFormat: 'yy-mm-dd',
				changeYear: true
			});	

		},				
		rowList:[50,100,150,200],
		footerrow: true,
		userDataOnFooter: true,			
		rownumbers: true,
        rownumWidth: 30
    }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{});

	
	$("#t_le_table").delegate(".date_btn", "click", function(){
		var id_tree = tree.find(".active").attr('id'),
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val();

			if (id_tree) 
			table.jqGrid('setGridParam',{url: b_loadUrl+'?id_tree='+id_tree+'&date_ot='+date_ot+'&date_do='+date_do,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
			else 
			table.jqGrid('setGridParam',{url: b_loadUrl+'?date_ot='+date_ot+'&date_do='+date_do,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");				
			
		return false;
	});	

});