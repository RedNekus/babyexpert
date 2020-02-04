$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog/";
	var baseUrlImages		= "/adminpanel/catalog_images/";
	var baseUrlComplect		= "/adminpanel/catalog_complect/";
	var baseUrl3d			= "/adminpanel/catalog_3d/";
	var accessUrl			= "/adminpanel/adminaccess/";
	var baseUrlRazmer		= "/adminpanel/catalog_razmer/";
	
	var tabs 				= $('#tabs-catalog').tabs();
	var tabImages			= $('#tabs-catalog ul li:nth-child(5)');
	var tabComplect			= $('#tabs-catalog ul li:nth-child(6)');
	var tab3d				= $('#tabs-catalog ul li:nth-child(7)');
	var tabRazmer			= $('#tabs-catalog ul li:nth-child(15)');	
	var tabs2lng			= $('#tabs-catalog-2-lng').tabs();
	var tabs3lng			= $('#tabs-catalog-3-lng').tabs();
	var tabs4lng			= $('#tabs-catalog-4-lng').tabs();	
	var TableCatalogPager	= '#TableCatalogPager';
	var TableCatalog 		= $('#TableCatalog');
	var catalogForm 		= $("#catalogForm");
	var catalogFormDel 		= $("#catalogFormDel");	
	var catalogFormCopy 	= $("#catalogFormCopy");	
	var LoadCatalogUrl		= baseUrl+"load";
	var EditCatalogUrl 		= baseUrl+"edit";
	var b_dataHandling 		= baseUrl+"datahandling";
	var b_dataInit			= baseUrl+"datainit";
	var b_dataAccess		= accessUrl+"access";
	var NextItemCatalog		= baseUrl+"nextitemcatalog";
	var СhangeСharUrl		= baseUrl+"changechar";
	var SavePriceUrl		= baseUrl+"saveprice";
	var SavePriceUrlMigom	= baseUrl+"savepricemigom";
	var SavePriceUrlPokupay	= baseUrl+"savepricepokupay";
	var SavePriceUrlOnliner	= baseUrl+"savepriceonliner";
	var SaveSitemapUrl		= baseUrl+"savesitemap";
	var SaveYandexmapUrl	= baseUrl+"saveyandexmap";
	var reloadPathUrl		= baseUrl+"reload_path";
	
	var TableImages	   	 	= $('#TableImages');	
	var b_imageUrl			= baseUrlImages+"load";	
	
	var TableComplect	   	 	= $('#TableComplect');	
	var b_colorUrl			= baseUrlComplect+"load";	
	
	var b_3dUrl				= baseUrl3d+"load";	
	
	var TableRazmer	   	 	= $('#TableRazmer');	
	var b_razmerUrl			= baseUrlRazmer+"load";	
	
	var id_char				= 3;
	var id_prefix			= 1;
	
	var bPrefixUrl			= baseUrl+"getPrefixId";
	
	var b_dataHandlingTree	= baseUrl+"datatreehandling";	
	
	var urlCategory			= baseUrl+"loadcategory";
	var tableRazdel 		= $("#TableRazdel");
	var urlEditCategory		= baseUrl+"editcategory";
	var tmp_id_razdel 		= [];
	var access 				= [];
	var gridCompleteId;
	var lastsel;

	$.ajax({type: "POST",url: b_dataAccess,dataType: "json",data: "",
		success:function (res) {//возвращаемый результат от сервера
	
			access = res;
			$('#t_TableCatalog').height(24);
			
			if (access['catalog_add']==1) add = '<div id="adddata" class="button add"></div>';
			else add = '<div class="button add noactive"></div>';
				
			if (access['catalog_edit']==1) edit = '<div id="editdata" class="button edit"></div>';
			else edit = '<div class="button edit noactive"></div>';	
			
			if (access['catalog_edit']==1) copy = '<div id="copydata" class="button copy"></div>';
			else copy = '<div class="button copy noactive"></div>';
			
			if (access['catalog_del']==1) del = '<div id="deldata" class="button del"></div>';
			else del = '<div class="button del noactive"></div>';
			
			yandex = '<div id="yandex" class="price yandex" title="Обновить прайс yandex"></div>';
			tcsv = '<div id="tcsv" class="price tcsv" title="Обновить прайс tcsv"></div>';
			k1 = '<div id="k1" class="price k1" title="Обновить прайс 1k"></div>';
			onliner = '<div id="onliner" class="price onliner" title="Обновить прайс onliner"></div>';
			migom = '<div id="migom" class="price migom" title="Обновить прайс migom"></div>';
			pokupay = '<div id="pokupay" class="price pokupay" title="Обновить прайс pokupay"></div>';
			unishop = '<div id="unishop" class="price unishop" title="Обновить прайс unishop"></div>';
			productWeek = '<a href="#" class="label-toolbar" id="product-week">Товары недели</a>';
			
			
			setcol = '<div id="set_columns" class="button set_columns"></div>';
			$('#t_TableCatalog').append(add + edit + del + setcol + copy + productWeek + k1 + onliner + migom + pokupay + tcsv + yandex + unishop);			

			$("#set_columns").click(function(){TableCatalog.jqGrid('setColumns',{colnameview:false,updateAfterCheck: true});});		
			
			// Обработка события кнопки Добавить товар
			$("#adddata").click(function(){
				gridAdd();
			});

			// Обработка события кнопки Редактировать товар	
			$("#editdata").click(function(){
				gridEdit();
			});
			
			// Обработка события кнопки Удалить	товар
			$("#deldata").click(function(){
				delItem(TableCatalog,catalogFormDel,$("#del_id_catalog"));	
			});	
						
			// Обработка события кнопки копировать товар
			$("#copydata").click(function(){
				delItem(TableCatalog,catalogFormCopy,$("#copy_id_catalog"));	
			});	
			
			// Обработка события кнопки прайс 1k
			$(".price").click(function(){
				var action = $(this).attr('id');
				saveprice(action);
			});
			
			$("#product-week").click(function() {

				TableCatalog.jqGrid('setGridParam',{url: LoadCatalogUrl+"?tovar_nedeli=1",page:1}).trigger("reloadGrid");

			});

			
		}						
	});
	
	// при нажатии на ссылку на дереве
	
	$("#tree_catalog ul li a:first").click();
	
	$("#tree_catalog").delegate(".linkrel", "click", function(){

		var id 	 = $(this).attr('id');

		$.ajax({type: "POST",url: b_dataInit,dataType: "json",data: "id="+id,
			success:function (res) {//возвращаемый результат от сервера
				for (var i = 0; i <= 5; i++) {
					tmp_id_razdel[i] = res[i];
				}
				tmp_id_razdel[6] = res['name'];
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});
			
		$.ajax({type: "POST",url: b_dataHandlingTree,dataType: "json",data: "id="+id,
			success:function (res) {//возвращаемый результат от сервера
				id_char = res['characteristic'];
				id_prefix = res['id'];
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});	
						
		$('#tree_catalog').find(".active").removeClass("active");
		$(this).addClass('active');
	
		filtReset();
		TableCatalog.jqGrid('setGridParam',{url: LoadCatalogUrl+"?id="+id,page:1}).trigger("reloadGrid");
	});	

	function saveprice(action) {
		$.ajax({type: "POST", data: "action="+action, url: SavePriceUrl, dataType: "json",
			success:function (res) {//возвращаемый результат от сервера
				showMsgBox(res, "white", "center");
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});		
	}
	
	function filtReset() {
		console.log('TEST TEST TEST');
		TableCatalog.jqGrid('setGridParam',{search:false});

		var postData = TableCatalog.jqGrid('getGridParam','postData');

		$.extend(postData, { filters: "" });

		for (k in postData) {
			if (k == "_search")
				postData._search = false;
			else if ($.inArray(k, ["nd", "sidx", "rows", "sord", "page", "filters"]) < 0) {
				try {
					delete postData[k];
				} catch (e) { }
				//$("#gs_" + $.jgrid.jqID(k), TableCatalog.get(0).grid.hDiv).val("");
			}
		}
	}
	
	TableCatalog.jqGrid({
		url:LoadCatalogUrl,
		//scroll: 1,
		datatype: 'json',
        mtype: 'GET',
		autowidth: true,
        colNames:['Код', 'Бренд', 'Название', 'Ссылка','Цена $','Цена Br','Цена бел','Курс','Новинка','Хит продаж','Спец. предложение', 'Активен', 'Приоритет'],
        colModel :[
			{name:'id', 		index:'id', 			width:20, 	align:'center', 	search:true, 	editable:false, edittype:"text", searchoptions:{sopt:['cn']} },
            {name:'id_maker', 	index:'id_maker', 		width:100, 	align:'left', 		search:true, 	editable:false, edittype:"text", searchoptions:{sopt:['cn']}},
			{name:'name', 		index:'name', 			width:200, 	align:'left', 		search:true, 	editable:false, edittype:"text", searchoptions:{sopt:['cn']}},
            {name:'path', 		index:'path', 			width:50, 	align:'center', 	search:false, 	editable:false, edittype:"text" },
            {name:'cena',		index:'cena', 			width:40, 	align:'center', 	search:true, 	editable:true, 	edittype:"text", searchoptions:{sopt:['cn']}},
			{name:'cena_br',	index:'cena_br', 		width:40, 	align:'center', 	search:true, 	editable:false, edittype:"text", searchoptions:{sopt:['cn']}},
		    {name:'cena_blr',	index:'cena_blr', 		width:40, 	align:'center', 	search:true, 	editable:true,  edittype:"text", searchoptions:{sopt:['cn']}},
			{name:'kurs',		index:'kurs', 			width:40, 	align:'center', 	search:true, 	editable:false, edittype:"text", searchoptions:{sopt:['cn']}},			
			{name:'new', 		index:'new', 			width:40, 	align:'center', 	search:false, 	editable:true, 	edittype:"text", edittype:"checkbox",editoptions: {value:"Да:Нет"} },
			{name:'hit', 		index:'hit', 			width:40, 	align:'center', 	search:false, 	editable:true, 	edittype:"text", edittype:"checkbox",editoptions: {value:"Да:Нет"} },
			{name:'spec_pred', 	index:'spec_pred', 		width:50, 	align:'center', 	search:false, 	editable:true, 	edittype:"text", edittype:"checkbox",editoptions: {value:"Да:Нет"} },
			{name:'active', 	index:'active', 		width:30, 	align:'center', 	search:false, 	editable:true, 	edittype:"text", edittype:"checkbox",editoptions: {value:"Да:Нет"} },
            {name:'prioritet', 	index:'prioritet', 		width:30, 	align:'center', 	search:false, 	editable:false, edittype:"text" },
			],
        pager: TableCatalogPager,
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'desc',
		rowNum:50,
		viewrecords: true,
		onSelectRow: function(id) {
			if (access['catalog_edit']==1) {
				if(id && id!==lastsel){
					TableCatalog.jqGrid('restoreRow',lastsel);
					TableCatalog.jqGrid('editRow',id,true);
					lastsel=id;
				}
			}
		},
		ondblClickRow: function(id) {
			if (access['catalog_edit']==1) gridEdit();
		},
		gridComplete: function() {
			if (gridCompleteId)	TableCatalog.setSelection(gridCompleteId);	
		},
		rowList:[50,100,150,200],
		rownumbers: true,
        rownumWidth: 30,		
		editurl:EditCatalogUrl
        }).jqGrid('navGrid', TableCatalogPager,{refresh: true, add: false, del: false, edit: false, search: true, view: true},{},{},{},{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true},{})
		.jqGrid('navButtonAdd', TableCatalogPager,{
				caption: 'Экспорт Excel ',
				title: 'Экспортировать данные в таблицу Microsoft Excel',
				buttonicon: 'ui-icon-calculator',		
				onClickButton: function(){					
					location.href = '/assets/files/csv.csv';					
				},
		}).jqGrid('navButtonAdd', TableCatalogPager,{
				caption: 'Обновить sitemap ',
				title: 'Обновить данные в sitemap.xml',
				buttonicon: 'ui-icon-refresh',
					onClickButton: function(){					
							$.ajax({type: "POST",url: SaveSitemapUrl,dataType: "json",
								success:function (res) {//возвращаемый результат от сервера
									showMsgBox(res, "white", "center");
								},
								error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
							});					
						},
				position:'last'					
		}).jqGrid('navButtonAdd', TableCatalogPager,{
				caption: 'Обновить yml ',
				title: 'Обновить данные в yml.xml',
				buttonicon: 'ui-icon-refresh',
					onClickButton: function(){					
							$.ajax({type: "POST",url: SaveYandexmapUrl,dataType: "json",
								success:function (res) {//возвращаемый результат от сервера
									showMsgBox(res, "white", "center");
								},
								error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
							});					
						},
				position:'last'					
		}).jqGrid('navButtonAdd', TableCatalogPager,{
				caption: 'Обновить URL ',
				title: 'Обновить url всех товаров (префикс + название)',
				buttonicon: 'ui-icon-refresh',
					onClickButton: function(){					
							$.ajax({type: "POST",url: reloadPathUrl,dataType: "json",
								success:function (res,f) {//возвращаемый результат от сервера
									if (f === "success" && res['succes']) {
										TableCatalog.trigger("reloadGrid");	
										showMsgBox(res['message'], "white", "center");
									} else {
										showMsgBox(res['message'], "white", "center");
									}
								},
								error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
							});					
						},
				position:'last'					
		});	
				
		//TableCatalog.jqGrid('hideCol',["id"]);   /* Прячем стобцы */


	// Функция инициализации разделов
	function gridInitRazdel(id){
		tableRazdel.jqGrid("clearGridData");
		$.ajax({type: "POST",url: urlCategory,dataType: "json",data: "id_catalog="+id,
			success:function (res) {//возвращаемый результат от сервера
				if(res[0]) {
					for(var i = 0;i <= res.length; i++){
						tableRazdel.jqGrid('addRowData',i+1,res[i]);
					}
					
					$("#tabs-formcreate-9").empty();
					$("#tabs-formcreate-9").html(res[0]['formcreate']);
					
					$("#val_id_prefix").empty();
					$("#val_id_prefix").html(res[0]['prefix']);	
				}
					
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});	
	}
			
	// Функция инициализации таблицы изображения
	function gridInitData(tableInitData,url,id_catalog){
		tableInitData.jqGrid('setGridParam',{url: url+"?id_catalog="+id_catalog,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
	}

	// Функция добавления данных в БД
	function gridAdd() {
		$("#catalog_form").trigger("reset");
		$("#catalog_frm_3d").trigger("reset");
		tabImages.hide();
		tabComplect.hide();
		tab3d.hide();
		$("#catalog_form_character").trigger("reset");
		$("#hidden3dframe").contents().find("body").empty();
		$("#action_pole").attr({value: "add"});
		$("#val_active").attr({'checked': true});
		$("#val_id_prefix").empty();
		$("#tabs-formcreate-9").empty();
		$("#raffle_create").empty();
		
		$.ajax({
			type: "POST",
			url: NextItemCatalog,
			dataType: "json",
			data: "id_characteristics="+id_char+'&id_prefix='+id_prefix,
			success:function (res) {
				$("#val_id").val(res['auto_increment']);
				$("#value3d_id_catalog").val(res['auto_increment']);				
				$("#val_id_prefix").html(res['prefix']);
				$("#tabs-formcreate-9").html(res['formcreate']);	
				$("#raffle_create").html(res['rafflecreate']);	
				$("#val_id_char").val(id_char);				
			}
		});	

		tableRazdel.jqGrid("clearGridData");
		tableRazdel.jqGrid('addRowData',1,{
			no:1,
			id_razdel0:tmp_id_razdel[0],
			id_razdel1:tmp_id_razdel[1],
			id_razdel2:tmp_id_razdel[2],
			id_razdel3:tmp_id_razdel[3],
			id_razdel4:tmp_id_razdel[4],
			id_razdel5:tmp_id_razdel[5],
			name:tmp_id_razdel[6]
		});
			
		catalogForm.dialog( "open" );
		tabs.tabs({active:'0'});	
	}
	
	$("#val_id_char").change(function () {
        
		var id = $("#val_id").val(),
			id_char = $(this).val();
		$.ajax({
			type: "POST",
			url: СhangeСharUrl,
			dataType: "json",
			data: "id_char="+id_char+'&id='+id,
			success:function (res) {
				$("#tabs-formcreate-9").html(res['formcreate']);	
			}
		});			
		
    });
	
	// Функция редактирования товара
	function gridEdit() {
		$("#catalog_form").trigger("reset");
		tabImages.show();
		tabComplect.show();
		tab3d.show();
		var gsr = TableCatalog.jqGrid('getGridParam','selrow');
		if(gsr){
			gridInitRazdel(gsr);			
			$.ajax({
				url: b_dataHandling,
				dataType: "json",
				type: "POST",	
				data: "id="+gsr,
				success:function (res) {//возвращаемый результат от сервера			
					for(var key in res){ 
						if ($.inArray(key, Array('active', 'no_active_color', 'hit', 'new', 'spec_pred', 'expert', 'show_opt')) != -1) {
							if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
						} else {
							$('#val_'+key).val(res[key]);
						}
						if (key == "language") {
							baza = res['language'];
							for(var ley in baza){ 	
								$('#val_'+ley).val(baza[ley]);
							}
						}
						$("#tabs-formcreate-9").html(res['formcreate']);
						$("#raffle_create").html(res['rafflecreate']);
				
					}						
				},
				error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
			});
			$("#action_pole").attr({value: "edit"});
			$('#value3d_id_catalog').val(gsr);
			catalogForm.dialog( "open" );
			tabs.tabs({active:'0'});
		} else {
			alert("Пожалуйста выберите запись!")
		}	
	}
	
	// Функция удаления данных из БД
	function delItem(bi_table, dialogForm, delValue){
		var gr = bi_table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				dialogForm.dialog( "open" );
				delValue.val(gr);
			} else alert("Пожалуйста выберите запись!");		
	}

	// Инициализация формы добавления и редактирования товара					
	catalogForm.dialog({
		autoOpen: false,
		width:'auto',
		minHeight: 'auto',
		title: "Свойства товара",
		close: function( event, ui ) {					
			var id = $("#val_id").val(),
				action = "view";
			str = 'id='+id+'&action='+action;
			$.ajax({type: "POST",url: EditCatalogUrl,data: str});					
			$( this ).dialog( "close" );
		},		
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					if (($("#prefix_select").val()!=0) && ($("#val_name").val()!="")) {
					var str = $("#catalog_form").serialize();
						var str_char = $("#catalog_form_character").serialize();
						var id  = $("#val_id").val();
						var ap	= $("#action_pole").val();
						var gridData  = tableRazdel.jqGrid('getGridParam', 'data');			
						var postData = JSON.stringify(gridData);
						
						if (gridData.length > 0) {
							$.ajax({
								type: "POST",
								url: EditCatalogUrl,
								dataType: "json",
								data: str+"&json="+postData+"&str_char=1&"+str_char
							});									
							$("#action_pole").attr({value: "edit"});	
							$( this ).dialog( "close" );
							gridCompleteId = id;
							TableCatalog.trigger("reloadGrid");	
						} else {
							alert("Заполните пожалуйста раздел товара");
						}
					} else {
						alert("Заполните поле префикс\nЗаполните поле Название");
					}
				}
				},
				{
				text: "Отмена",
				click: function() {
					var id = $("#val_id").val(),
						action = "view";
					str = 'id='+id+'&action='+action;
					$.ajax({type: "POST",url: EditCatalogUrl,data: str});					
					$( this ).dialog( "close" );
					}
				}
			]
	});
		
	// Форма удаления товара	
	catalogFormDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: 	function () {
					var id = $("#del_id_catalog").val();
					$.ajax({
						type: "POST",
						url: EditCatalogUrl,
						data: "del_id_catalog="+id,
					});
					$( this ).dialog( "close" );						
					TableCatalog.trigger("reloadGrid");					
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
	
	// Форма копирования товара	
	catalogFormCopy.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: 	function () {
					var id = $("#copy_id_catalog").val();
					$.ajax({
						type: "POST",
						url: EditCatalogUrl,
						data: "copy_id_catalog="+id,
					});
					$( this ).dialog( "close" );						
					TableCatalog.trigger("reloadGrid");					
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
	
	
	tabImages.click(function () {
		
		var id_catalog = $("#val_id").val();
		
		gridInitData(TableImages,b_imageUrl,id_catalog);
		
	});	
	
	tabComplect.click(function () {
		
		var id_catalog = $("#val_id").val();
		
		gridInitData(TableComplect,b_colorUrl,id_catalog);
		
	});	
	
	tab3d.click(function () {
		
		var id_catalog = $("#val_id").val();
		
		$.ajax({
			type: "POST",
			url: b_3dUrl,
			dataType: "json",
			data: "3d_id="+id_catalog,
			success:function (res) {//возвращаемый результат от сервера	
				iframe = $("#hidden3dframe").contents().find("body");
				iframe.empty();
				iframe.append(res['picture']);
				$("#value3d_file").val("");
				$("#value3d_id").val(res['id']);
				
			},
			error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
		});
		
	});			
					
		
	tabRazmer.click(function () {
		
		var id_catalog = $("#val_id").val();
		
		gridInitData(TableRazmer,b_razmerUrl,id_catalog);
		
	});		
});