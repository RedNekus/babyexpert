$(document).ready(function() {
	var baseUrl				= "/adminpanel/kassa/";
	var accessURL			= "/adminpanel/adminaccess/";
		
	var pager				= '#le_tablePager';
	var table 				= $('#le_table');
	var dialogEdit 			= $("#dialog-edit");
	var dialogDel 			= $("#dialog-del");	
	var dialogMove 			= $("#dialog-move");	
	var dialogConversion 	= $("#dialog-conversion");	
	var dialogFiltr			= $("#dialog-filtr");
	var AddCharDialog		= $("#AddConnectionDialog");
	var b_loadUrl			= baseUrl+"load";
	var b_editUrl 			= baseUrl+"edit";
	var b_openUrl 			= baseUrl+"open";
	var b_filtrUrl 			= baseUrl+"filtr";
	var b_getSelect 		= baseUrl+"getselect";
	
	var tree				= $("#tree_connection");
	
	var b_dataAccess			= accessURL+"access";

	var height 			= $(window).height()-135;
	window.onresize = function () { height = $(".ui-jqgrid-bdiv").height($(window).height()-135);} 


	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_le_table').height('auto');

			if (access['kassa_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
			
			if (access['kassa_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';
			
			if (access['kassa_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';

			if (access['kassa_add']==1) move = '<div id="movedata" class="button move"></div>';
			else move = '<div class="button move noactive"></div>';
			
			if (access['kassa_add']==1) conversion = '<div id="conversiondata" class="button conversion"></div>';
			else conversion = '<div class="button conversion noactive"></div>';
							
			filtr = '<div id="filtrdata" class="button filtr search"></div>';
					
			date_ot =  '<label>ДАТА ОТ </label><input type="text" id="date_ot">';
			date_do =  '<label>ДО </label><input type="text" id="date_do">';
			date_btn =  '<a href="" class="date_btn btn-cur">Найти</a>';
						
			input_html = '<div class="block-input"><input type="text" class="total_usd"/><input type="text" class="total_blr"/><input type="text" class="total_eur"/><input type="text" class="total_rur"/></div>';
									
			$('#t_le_table').append('<div class="toolbar_top" id="select_form"><div class="toolbar-block">' + add + edit + del + move + conversion + filtr + date_ot + date_do + date_btn + input_html + '</div></div>');

			// Обработка события кнопки Редактировать	
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
			
			$("#movedata").click(function(){
				gridMove();
			});
					
			$("#conversiondata").click(function(){
				gridConversion();
			});
							
			$("#filtrdata").click(function(){
				gridFiltr();
			});
			
		}						
	});

	// при нажатии на ссылку на дереве
	tree.delegate(".linkrel", "click", function(){
		var id_tree = $(this).attr('id');
		$("#val_id_tree").val(id_tree);
		$("#val_filtr_id_tree").val(id_tree);
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
        colNames:['№','Операция','Автор','Касса','Касса 2','Дата создания','Время создания','USD','BYR','EUR','RUR','Тип','Примечание','№ заказа','Курс'],
        colModel :[
            {name:'nomer', 			index:'nomer', 			width:25, 	align:'center', 	search:true,  	},
            {name:'img', 			index:'img', 			width:50, 	align:'center', 	search:false,  	},
            {name:'author', 		index:'author', 		width:60, 	align:'left', 		search:false,  	},
            {name:'id_tree', 		index:'id_tree', 		width:60, 	align:'left', 		search:false,  	},
            {name:'id_tree_end', 	index:'id_tree_end', 	width:60, 	align:'left', 		search:false,  	},
            {name:'date_create', 	index:'date_create', 	width:80, 	align:'center', 	search:false, 	},
            {name:'time_create', 	index:'time_create', 	width:80, 	align:'center', 	search:false, 	},			
            {name:'cena_usd', 		index:'cena_usd', 		width:80, 	align:'center', 	search:false,  	},
            {name:'cena_blr',		index:'cena_blr', 		width:80, 	align:'center', 	search:false,  	},
            {name:'cena_eur',		index:'cena_eur', 		width:80, 	align:'center', 	search:false,  	},
            {name:'cena_rur',		index:'cena_rur', 		width:80, 	align:'center', 	search:false,  	},
            {name:'id_tip',			index:'id_tip', 		width:100, 	align:'center', 	search:false,  	},
            {name:'comment',		index:'comment', 		width:300, 	align:'left', 		search:false,  	},
		    {name:'id_client', 		index:'id_client', 		width:90, 	align:'center', 	search:false,   },			
            {name:'kurs',			index:'kurs', 			width:50, 	align:'center', 	search:false,  	},
			],
        pager: pager,
		toolbar: [true,"top"],		
        sortname: 'nomer',
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,
		ondblClickRow: function(id) {
			if (access['kassa_edit']==1) gridEdit();
		},
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
		
			get_total_sum();			

		},				
		rowList:[50,100,150,200],
		footerrow: true,
		userDataOnFooter: true,			
		rownumbers: true,
        rownumWidth: 30,
		editurl:b_editUrl		
    }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{});

	
	$("#t_le_table").delegate(".date_btn", "click", function(){
		var id_tree = tree.find(".active").attr('id'),
			date_ot = $("#date_ot").val(),
			date_do = $("#date_do").val();

			table.jqGrid('setGridParam',{url: b_loadUrl+'?id_tree='+id_tree+'&date_ot='+date_ot+'&date_do='+date_do,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");

		return false;
	});	
	
	function get_total_sum() {
		var myUserData = table.getGridParam('userData');
		$(".total_usd").val(myUserData.cena_usd);
		$(".total_blr").val(myUserData.cena_blr);
		$(".total_eur").val(myUserData.cena_eur);		
		$(".total_rur").val(myUserData.cena_rur);	
		$(".tip_operation").empty();
		$(".tip_operation").html(myUserData.k_html);	
	}
	
	$("#val_m_id_kontragenty_tip").change(function() {
		$.ajax({
			type: "POST",
			url: b_getSelect,
			data: "id="+$(this).val()+"&kassi=1",
			dataType: 'json',
			success:function (res) {//возвращаемый результат от сервера
				$("#val_m_id_tree_tmp").empty();
				$("#val_m_id_tree_tmp").html(res);
			},
			error: function(jqXHR, textStatus, errorThrown) {alert("Error! "+textStatus);}
		});		
		
	});
		
	// Функция добавления 
	function gridAdd() {
		var id_tree 	= $("#tree_connection").find(".active").attr('id');	
		
		if (id_tree) {
			$(".readonly").prop('readonly', false);
			$(".disabled").prop('disabled', false);				
			$("#form").trigger("reset");
			get_total_sum();
			$("#action_pole").attr({value: "add"});
			$("#val_active").attr({'checked': true});
			$('#val_id_tree').val(id_tree);			
			dialogEdit.dialog( "open" );
		} else {
			showMsgBox('Не выбрана касса!', "white", "center");
		}		
	}
	
	// Функция редактирования 
	function gridEdit() {
		var gsr = table.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					url: b_openUrl,
					dataType: "json",
					type: "POST",	
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера		
						for(var key in res){ 	
							if (key == "active") {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else {								
								$('#val_'+key).val(res[key]);
							}							
						}	
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
			$("#action_pole").attr({value: "edit"});
			if (access['id']!=1) {
				$(".readonly").prop('readonly', true);
				$(".disabled").prop('disabled', true);
			}
			dialogEdit.dialog( "open" );	
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}	
	
	// Функция перевода 
	function gridMove() {
		var id_tree 	= $("#tree_connection").find(".active").attr('id');	
		
		if (id_tree) {	
			$("#form-move").trigger("reset");
			get_total_sum();	
			if (access['id']!=1) $(".hide").hide();			
			$("#action_move").attr({value: "add"});
			$('#val_m_id_tree').val(id_tree);			
			dialogMove.dialog( "open" );
		} else {
			showMsgBox('Не выбрана касса!', "white", "center");
		}
	}
		
	// Функция конверсии 
	function gridConversion() {
		var id_tree 	= $("#tree_connection").find(".active").attr('id');	
		if (id_tree) {
			$("#action_conversion").attr({value: "add"});
			$("#val_con_id_tree").val(id_tree);		
			dialogConversion.dialog( "open" );
		} else {
			showMsgBox('Не выбрана касса!', "white", "center");
		}
	
	}
	
	$("#val_con_kurs").keyup(function () {
		if ($(this).val()<=0) $(this).val($(this).val().match(/^[1-9\.1-9]+/));
    });
	
	function conversionValute(val) {
		var	v1 = $("#val_con_valute1").val(),
			v2 = $("#val_con_valute2").val(),
			kurs = $("#val_con_kurs").val();

		if (kurs > 0) {	
		//BYR to USD
		if (v1==1 && v2==2) result = val / kurs;						
		//BYR to EUR
		if (v1==1 && v2==3) result = val / kurs;						
		//BYR to RUR
		if (v1==1 && v2==4) result = val / kurs;			
		
		//USD to EUR
		if (v1==2 && v2==3) result = val / kurs;							
		//USD to BYR
		if (v1==2 && v2==1) result = val * kurs;							
		//USD to RUR
		if (v1==2 && v2==4) result = val * kurs;
		
		//EUR to BYR
		if (v1==3 && v2==1) result = val * kurs;					
		//EUR to USD
		if (v1==3 && v2==2) result = val * kurs;					
		//EUR to RUR
		if (v1==3 && v2==4) result = val * kurs;
		
		//RUR to BYR
		if (v1==4 && v2==1) result = val * kurs;					
		//RUR to USD
		if (v1==4 && v2==2) result = val / kurs;					
		//RUR to EUR
		if (v1==4 && v2==3) result = val / kurs;

		return result;
		}
	}
	
	$(".changeVal").keyup(function(){
		var val = $("#val_con_cena_old").val();

		$("#val_con_cena_new").val(conversionValute(val));	
	});
	
	$(".cur-pointer").toggle(function(){
		var elem = ".tmp-" + $(this).attr('rel');
		$(elem).each(function() {
			$(this).attr({'checked': true});
		});
	},function() {
		var elem = ".tmp-" + $(this).attr('rel');
		$(elem).each(function() {
			$(this).attr({'checked': false});
		});
	});
	
	// Функция фильтра 
	function gridFiltr() {
		var id_tree 	= $("#tree_connection").find(".active").attr('id');	
		
		//$("#form-filtr").trigger("reset");
		$("#action_filtr").attr({value: "add"});		
		dialogFiltr.dialog( "open" );	
	}
			
	// Функция удаления итема из таблицы
	function gridDelete(){
		var gr = table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				dialogDel.dialog( "open" );
					$("#del_id").val(gr);
			} else alert("Пожалуйста выберите запись!");
	}	

	function saveData(str,thatDialog) {
		$.ajax({
			type: "POST",
			url: b_editUrl,
			data: str
		});
		table.trigger("reloadGrid");
		thatDialog.dialog( "close" );
	}
	
	// Инициализация формы добавления и редактирования 					
	dialogEdit.dialog({
		autoOpen: false,
		width: 620,
		minheight: 'auto',
		title: "Свойства",
		buttons: [
			{
				text: "Сохранить",
				click: function() {

					cena_usd = $("#val_cena_usd").val();
					cena_blr = $("#val_cena_blr").val();
					cena_eur = $("#val_cena_eur").val();
					cena_rur = $("#val_cena_rur").val();
	
					if ($("#val_operation").val()==2) {
						var myUserData = table.getGridParam('userData');
						if (myUserData.cena_usd >= cena_usd && myUserData.cena_blr >= cena_blr && myUserData.cena_eur >= cena_eur && myUserData.cena_rur >= cena_rur) {				
							saveData($("#form").serialize(),dialogEdit);						
						} else {
							if (myUserData.minus == 1) saveData($("#form").serialize(),dialogEdit);								
							else showMsgBox("В кассе не достаточно средств!",'white','center');	
						}
					} else {
						saveData($("#form").serialize(),dialogEdit);	
					}					
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
	
	// Инициализация формы добавления и редактирования 					
	dialogMove.dialog({
		autoOpen: false,
		width: 620,
		minheight: 'auto',
		title: "Переместить",
		buttons: [
			{
				text: "Сохранить",
				click: function() {

					cena_usd = $("#val_m_cena_usd").val();
					cena_blr = $("#val_m_cena_blr").val();
					cena_eur = $("#val_m_cena_eur").val();
					cena_rur = $("#val_m_cena_rur").val();
	
					var myUserData = table.getGridParam('userData');
							
					if ($("#val_m_id_tree").val()>0 && $("#val_m_id_tree_tmp").val()!=0) { 		
							
						if (myUserData.cena_usd >= cena_usd && myUserData.cena_blr >= cena_blr && myUserData.cena_eur >= cena_eur && myUserData.cena_rur >= cena_rur) {					
							saveData($("#form-move").serialize(),dialogMove);						
						} else {
							if (myUserData.minus == 1) saveData($("#form-move").serialize(),dialogMove);								
							else showMsgBox("В кассе не достаточно средств!",'white','center');	
						}

					} else {

						showMsgBox("Поле куда, обязательно!",'white','center');	
					
					}					
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
	
	// Инициализация формы добавления и редактирования 					
	dialogConversion.dialog({
		autoOpen: false,
		width: 620,
		minheight: 'auto',
		title: "Конверсия валют",
		buttons: [
			{
				text: "Обменять",
				click: function() {
					
					var selectVal = $("#val_con_valute1").val();
					
					var myUserData = table.getGridParam('userData');
					
					if (selectVal == 1) summa = myUserData.cena_blr;
					if (selectVal == 2) summa = myUserData.cena_usd;
					if (selectVal == 3) summa = myUserData.cena_eur;		
					if (selectVal == 4) summa = myUserData.cena_rur;		
					
					var kurs = $("#val_con_kurs").val(),
						cena_old = $("#val_con_cena_old").val(),
						cena_new = $("#val_con_cena_new").val(),
						valute1 = $( "#val_con_valute1 option:selected" ).text(),
						valute2 = $( "#val_con_valute2 option:selected" ).text();
						
					if (cena_new > 0) {	
						if (summa >= cena_old) {	
							textMsg = 'Вы действительно хотите конвертировать: ' + cena_old + ' ' + valute1 + ' на ' + cena_new + ' ' + valute2 + ' по курсу ' + kurs;
							if (confirm(textMsg)) {
								saveData($("#form-conversion").serialize(),dialogConversion);	
							} 
						} else {
							if (myUserData.minus == 1) saveData($("#form-conversion").serialize(),dialogConversion);								
							else showMsgBox("В кассе не достаточно средств!",'white','center');	
						}
					}
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
	
	// Форма удаления 	
	dialogDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: 	function () {
					var id = $("#del_id").val();
						$.ajax({
							type: "POST",
							url: b_editUrl,
							data: "del_id="+id,
						});
						table.trigger("reloadGrid");
						$( this ).dialog( "close" );						
				}
			},
			{
				text: "Нет",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});		
	
	// Форма фильтра 	
	dialogFiltr.dialog({
		autoOpen: false,
		title: "Фильтр",
		width: 740,
		minheight: 'auto',
		buttons: [
			{
				text: "Фильтр",
				click: 	function () {
					var str = $("#form-filtr").serialize();

					table.jqGrid('setGridParam',{url: b_loadUrl+'?'+str,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");

					dialogFiltr.dialog( "close" );						
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
		
});