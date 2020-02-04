$(document).ready(function() {
	var baseUrl			= "/adminpanel/catalog_sklad/";
	var accessUrl		= "/adminpanel/adminaccess/";
	var tmcUrl			= "/adminpanel/delivery_tmc/";
	var pager			= '#le_tablePager';
	var t_table			= '#t_le_table';
	var table 			= $('#le_table');
	var b_loadUrl		= baseUrl+"load";
	var b_accessUrl		= accessUrl+"access";
	var access 			= [];
	var height 			= $(window).height()-175;
	
	var loadSelectMakerUrl	= tmcUrl+"load_select_maker";
	var loadSelectTovarUrl	= tmcUrl+"load_select_tovar";
	
	var printForm 			= $("#dialog-print");
	
	var printOstDialog		= $("#dialog-print-ost");
	var b_printOstUrl 		= baseUrl+"printost";
	
	var printPostavkiDialog = $("#dialog-get-postavki");
	var b_PostavkiUrl  		= baseUrl+"get_postavki";
	
	var SavePriceUrl		= baseUrl+"saveprice";
	var RefreshOstatkiUrl	= baseUrl+"refresh_ostatki";
	
	$("#right .ui-jqgrid-bdiv").height(height);
	window.onresize = function () { 
		$("#right .ui-jqgrid-bdiv").height(height);	
	} 	
		
	
	$.ajax({type: "POST",url: b_accessUrl,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$(t_table).height(24);

			setcol = '<div id="set_columns" class="button set_columns"></div>';
			
			print = '<div id="printdata" class="button print"></div>';
			
			printOstatki = '<div id="print-ostatki" class="button copy" title="Обновить остатки"></div>';
			
			printOstatki = '<div id="print-postavki" class="button ui-icon ui-icon-contact" title="Показать приходы"></div>';

			input_html = '<div class="block-input"><input type="text" class="total_price"/></div>';
			
			$(t_table).append(setcol  + print + printOstatki + input_html);

			$("#set_columns").click(function(){table.jqGrid('setColumns',{colnameview:false,updateAfterCheck: true});});		
		
			// Обработка события кнопки Печать	
			$("#printdata").click(function(){
				gridPrint();
			});	
			
			// Обработка события кнопки Печать	
			$("#print-ostatki").click(function(){
				gridPrintOst();
			});		
			
			// Обработка события кнопки Печать	
			$("#print-postavki").click(function(){
				gridPrintPostavki();
			});	

		}						
	});

	function saveprice() {
		$.ajax({type: "POST", data: "action=2", url: SavePriceUrl, dataType: "json",
			success:function (res) {//возвращаемый результат от сервера
				showMsgBox(res, "white", "center");
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});		
	}
		
	table.jqGrid({
		url:b_loadUrl,
		//scroll: 1,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,
        colNames:['Префикс', 'Производитель', 'Название', 'Цвет','Ссылка','С','О','СО','РО','РОС','Р','Р1','Б','Д','П','ОП'],
        colModel :[
            {name:'prefix', 			index:'prefix', 			width:200, 	align:'left', 	search:true },
            {name:'maker', 				index:'maker', 				width:200, 	align:'left', 	search:true },
			{name:'name', 				index:'name', 				width:300, 	align:'left', 	search:true },
            {name:'color',				index:'color', 				width:200, 	align:'left', 	search:true },
            {name:'path', 				index:'path', 				width:70, 	align:'center' },
			{name:'sklad_ostatok', 		index:'sklad_ostatok', 		width:50, 	align:'center' },
			{name:'office_ostatok', 	index:'office_ostatok', 	width:50, 	align:'center' },
			{name:'free_ostatok', 		index:'free_ostatok', 		width:50, 	align:'center' },
			{name:'real_ostatok', 		index:'real_ostatok', 		width:50, 	align:'center' },
			{name:'real_ostatok_sklad', index:'real_ostatok_sklad', width:50, 	align:'center' },
			{name:'rezerv', 			index:'rezerv', 			width:50, 	align:'center' },
            {name:'vozvrat', 			index:'vozvrat', 			width:50, 	align:'center' },			
            {name:'brak',				index:'brak', 				width:50, 	align:'center' },			
            {name:'dostavka', 			index:'dostavka', 			width:50, 	align:'center' },			
			{name:'predzakaz', 			index:'predzakaz', 			width:50, 	align:'center' },
            {name:'ozhidaemiy', 		index:'ozhidaemiy', 		width:50, 	align:'center' },
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,	
		gridComplete : function() {

			var myUserData = table.getGridParam('userData');
			$(".total_price").val(myUserData.total_price);

		},	
		ondblClickRow : function() {

			gridPrintPostavki();

		},
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30
        }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{})
		.jqGrid('navButtonAdd', pager,{
				caption: 'Экспорт excel',
				title: 'Экспорт excel',
				buttonicon: 'ui-icon-calculator',
					onClickButton: function(){					
							saveprice();				
						},
				position:'last'					
		}).jqGrid('navButtonAdd', pager,{
				caption: 'Обновить остатки',
				title: 'Обновить остатки',
				buttonicon: 'ui-icon-refresh',
					onClickButton: function(){					
						$.ajax({type: "POST", data: "action=2", url: RefreshOstatkiUrl, dataType: "json",
							success:function (res) {//возвращаемый результат от сервера
								showMsgBox(res, "white", "center");
							},
							error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
						});				
					},
				position:'last'					
		});	

	$(".val_id_tree").change(function() {
		var id_tree = $(this).val();
		
		$.ajax({
			type: "POST",
			url: loadSelectMakerUrl,
			data: "id_tree="+id_tree,
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера		
				$( ".val_id_maker" ).empty();
				$( ".val_id_maker" ).append(res['makers']);
				$( ".val_id_tovar" ).empty();
				$( ".val_id_tovar" ).append(res['tovars']);								
			}	
		});
	});
	
	$(".val_id_maker").change(function() { 
		var id_maker = $(this).val(),
			id_tree = $(".val_id_tree").val();
		
		$.ajax({
			type: "POST",
			url: loadSelectTovarUrl,
			data: "id_tree="+id_tree+"&id_maker="+id_maker,
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера			
				$( ".val_id_tovar" ).empty();
				$( ".val_id_tovar" ).append(res['tovars']);								
			}	
		});			
	});
	
	$(".podbor").change(function() {
		var data = $("#form-sklad-tovar").serialize();
		table.jqGrid('setGridParam',{url: b_loadUrl+"?"+data,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
	});
	
	
	// Функция печати
	function gridPrint() {
		$('#table-print').empty();
		$('#table-print').append($(t_table).next().find("thead").html());
		$('#table-print').append($(table).html());	
		printForm.dialog( "open" );		
	}	
		
	// Функция печати
	function gridPrintOst() {
		
		$.ajax({
			type: "POST",
			url: b_printOstUrl,
			data: "ros=1",
			dataType: "json",
			success:function (res) {//возвращаемый результат от сервера			
				$('#table-print-ost').empty();
				$('#table-print-ost').html(res['html']);		
			}	
		});			

		printOstDialog.dialog( "open" );		
	}	
			
	// Функция печати
	function gridPrintPostavki() {
		var gsr = table.jqGrid('getGridParam','selrow');
		if(gsr){
				
			$.ajax({
				type: "POST",
				url: b_PostavkiUrl,
				data: "id="+gsr,
				dataType: "json",
				success:function (res) {//возвращаемый результат от сервера			
					$('#table-print-postavki').empty();
					$('#table-print-postavki').html(res['html']);		
				}	
			});			

		printPostavkiDialog.dialog( "open" );	
		} else {
			alert("Пожалуйста выберите запись!")
		}			
	}	
	
	function printBlock() {
		$("#table-print").printElement({
            overrideElementCSS:[
				'jquery-ui-1.10.0.custom.css',
				{ href:'/js/jqueryui/css/cupertino/jquery-ui-1.10.0.custom.css',media:'print'}]
            });
	}
		
	function printBlockOst() {
		$("#table-print-ost").printElement({
            overrideElementCSS:[
				'jquery-ui-1.10.0.custom.css',
				{ href:'/js/jqueryui/css/cupertino/jquery-ui-1.10.0.custom.css',media:'print'}]
            });
	}
	
	printForm.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 880,
		minHeight: 'auto',
		position: 'top',
		buttons: [
			{
				text: "Печать",
				click: printBlock
			},
			{
				text: "Cancel",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
	
	printOstDialog.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 880,
		minHeight: 'auto',
		position: 'top',
		buttons: [
			{
				text: "Печать",
				click: printBlockOst
			},
			{
				text: "Cancel",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
		
	printPostavkiDialog.dialog({
		autoOpen: false,
		title: "Поставки на складе",
		width: 880,
		minHeight: 'auto',
		buttons: [
			{
				text: "Cancel",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
	
});