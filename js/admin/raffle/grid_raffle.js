$(document).ready(function() {

	var baseUrl			= "/adminpanel/raffle/";
	var accessURL		= "/adminpanel/adminaccess/";	
	var $tabs 			= $('#tabs-raffle').tabs();
	var $tabs2 			= $('#tabs-s-raffle').tabs();
	$( "#val_timestamp" ).datepicker({ dateFormat: 'yy-mm-dd' });	
	$( "#val_timestampend" ).datepicker({ dateFormat: 'yy-mm-dd' });	
	$( "#val_timestamp2" ).datepicker({ dateFormat: 'yy-mm-dd' });	
	$( "#val_timestampend2" ).datepicker({ dateFormat: 'yy-mm-dd' });	
	$( "#val_timestamp3" ).datepicker({ dateFormat: 'yy-mm-dd' });	
	$( "#val_timestampend3" ).datepicker({ dateFormat: 'yy-mm-dd' });	
	$( "#val_timestamp4" ).datepicker({ dateFormat: 'yy-mm-dd' });	
	$( "#val_timestampend4" ).datepicker({ dateFormat: 'yy-mm-dd' });
	var pager 			= '#le_tablePager';
	var table 			= $('#le_table');
	var catalogForm 	= $("#catalogForm");
	var b_gridUrl	 	= baseUrl+"load";
	var b_editUrl 		= baseUrl+"edit";
	var b_dataHandling 	= baseUrl+"datahandling";
	var b_SendSms 		= baseUrl+"sendsms";
	var b_dataAccess	= accessURL+"access";
	
	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 
	
	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height(24);

			if (access['raffle_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['raffle_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['raffle_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';
			
			setcol = '<div id="set_columns" class="button set_columns"></div>';
			
			$('#t_le_table').append(add+edit+del+setcol);		

			$("#set_columns").click(function(){
					table.jqGrid('setColumns',{
								colnameview:false,
								updateAfterCheck: true
						});
			});

			// Обработка события кнопки Добавить	
			$("#adddata").click(function(){
				gridAdd();
			});
			
			// Обработка события кнопки Редактировать	
			$("#editdata").click(function(){
				gridEdit();
			});

			// Обработка события кнопки Удалить	
			$("#deldata").click(function(){
				gridDelete();
			});

		}						
	});	
	table.jqGrid({
		url:b_gridUrl,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
		height: height,		
        colNames:['Код', 'Название','Активен','Дата начала','Дата окончания'],
        colModel :[
			{name:'id', 			index:'id', 			width:15, 	align:'center', 	search:false},
            {name:'name', 			index:'name', 			width:500, 	align:'left', 		search:true, searchoptions:{sopt:['cn']}},
			{name:'active', 		index:'active', 		width:30, 	align:'center', 	search:false},
			{name:'timestamp', 		index:'timestamp', 		width:40, 	align:'left', 		search:false},
			{name:'timestampend', 	index:'timestampend', 	width:40, 	align:'left', 		search:false},
			],
        pager: pager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['raffle_edit']==1) gridEdit();
		},
		rowList:[50,100,150,200],
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

	// Функция добавление итема в таблицу		
	function gridAdd(){
		$("#catalog_form").trigger("reset");
		$("#action_pole").attr({value: "add"});		
		$('#ui-id-5').parent().hide();
		catalogForm.dialog( "open" );
	}

	// Функция редактирование итема в тиблице	
	function gridEdit(){
		gsr = table.jqGrid('getGridParam','selrow');
		$('#ui-id-5').parent().show();
			if(gsr){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_dataHandling,
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if ($.inArray(key, Array('active')) != -1) {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else {
								if (key == "url") {	
									$('#image_insert').html("<img src='"+res['url']+"' />")
								} 
								if (key == "html1") $('#rafle_table1').html(res[key]);
								if (key == "html2") $('#rafle_table2').html(res[key]);
								if (key == "html3") $('#rafle_table3').html(res[key]);
								if (key == "html4") $('#rafle_table4').html(res[key]);
								if (key == "html5") $('#rafle_table5').html(res[key]);
								
								//$('#input_winer').val($('#nomer').val());
		
								$('#val_'+key).val(res[key]);
							}
						}
					$("#valueRaffle_file").val("");	
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#action_pole").attr({value: "edit"});
				catalogForm.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}
	}	

	// Функция удаления итема из таблицы
	function gridDelete(){
		var gr = table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				$("#deldialog").dialog( "open" );
					$("#del_id").val(gr);
			} else alert("Пожалуйста выберите запись!");
	}	
	
	// Обработка события кнопки Добавить
	$("#adddata").click(function(){
		gridAdd();
	});

	// Обработка события кнопки Редактировать	
	$("#editdata").click(function(){
		gridEdit();
	});

	// Обработка события кнопки Удалить	
	$("#deldata").click(function(){
		gridDelete();
	});

	// Инициализация формы добавления и редактирования данных					
	catalogForm.dialog({
		autoOpen: false,
		width: 940,
		minHeight: 'auto',
		title: "Свойства розыгрыш",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					$('#catalog_form').submit();
					var iframe = $("#hiddenraffleframe");
					$("#loading").show();
					iframe.load(function(){
						$("#loading").hide();
						table.trigger("reloadGrid");
						catalogForm.dialog( "close" );
					});						
					}
				},
				{
				text: "Отмена",
				click: function() {
					$( this ).dialog( "close" );
					}
				}
			]
	});

	function DelItemCatalog() {
		var id = $("#del_id").val();
			$.ajax({
				type: "POST",
				url: b_editUrl,
				data: "del_id="+id,
			});
			table.trigger("reloadGrid");
			$( this ).dialog( "close" );						
	}
		
	$("#deldialog").dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: DelItemCatalog
			},
			{
				text: "Нет",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});	
	
	// использование Math.round() даст неравномерное распределение!
	function getRandomInt(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	clickRaffleTable("#rafle_table1");
	clickRaffleTable("#rafle_table2");
	clickRaffleTable("#rafle_table3");
	clickRaffleTable("#rafle_table4");
	clickRaffleTable("#rafle_table5");
	
	function clickRaffleTable(TraffleTable) {
	
		$(TraffleTable).delegate(".click_random", "click", function(){
			var massive = [];
			var opts = {
				raffleTable : $(TraffleTable), 
				inputWiner  : $(TraffleTable+" .input_winer"),
				rCode 		: '.raffle_code',
			}
			var i = 1;
			opts.raffleTable.find(opts.rCode).each(function () {			
				massive[i] = $(this).attr('id');
				$(this).removeClass('active').addClass('noactive');
				i++;
			});
			var count = massive.length;
			if (opts.inputWiner.val() == "") {
				var win = getRandomInt(1, count - 1);
				opts.inputWiner.val(win);
			} else {
				var win = opts.inputWiner.val();
			}
			
			$('#'+massive[win]).removeClass('noactive').addClass('active');
		});
		
		$(TraffleTable).delegate(".raffle_code.active", "click", function(){
			
			var id = $(this).attr('id'),
				phone = $(this).attr('rel'),
				tur = $(this).attr('data-tur');
			
			if (id) {
				$("#val_winner").val(id);
				
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_SendSms,
					data: "id="+id+"&phone="+phone+"&tur="+tur,
					success:function (res, f) {//возвращаемый результат от сервера
						if (f === "success" && res['succes']) {
							showMsgBox(res['message'], "white", "center");
						} else {
							showMsgBox(res['message'], "white", "center");
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});		
			}
		});
	}
	
});