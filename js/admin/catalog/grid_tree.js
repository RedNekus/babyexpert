$(document).ready(function() {
	var baseUrl			= "/adminpanel/catalog/";
	var baseUrlPrefix	= "/adminpanel/catalog_prefix/";
	var tabs 			= $('.admin-tabs').tabs();
	var height 			= $(window).height();
	var EditCatalogUrl 	= baseUrl+"edit";
	var b_dataHandling  = baseUrl+"datatreehandling";
	var AddTreeForm     = $("#AddTreeDialog");
	var tree_catalog    = $("#tree_catalog");
	var TablePrefix	   	= $('#TablePrefix');
	var b_prefixUrl		= baseUrlPrefix+"load";	
	
	var NextItemTree	= baseUrl+"nextitemtree";	
	
	$("#left .ui-jqgrid-bdiv").height(height-120);
	$("#right .ui-jqgrid-bdiv").height(height-120);
	window.onresize = function () { 
		$("#left .ui-jqgrid-bdiv").height($(window).height()-120);
		$("#right .ui-jqgrid-bdiv").height($(window).height()-120);	
	} 	
			 	
	var options = {
		collapsed: true,
		animated: "fast",
		unique: false,
		persist: "cookie"
	}			
				
	tree_catalog.treeview(options );	
	
	function AddToForm() {
		var find = tree_catalog.find(".active")	
		var id 	 = find.attr('id');
		if (id) {	
			var level = $("#"+id).parents('li').length;
		} else { 
			var level = 1;
				   id = 1; 
		}
		
		if (level > 5) { alert("Максимум подразделов для данного раздела!"); } 
		else {
			$("#catalog_form_tree").trigger("reset");
			$('#valueTree_pid').val(id);
			$('#valueTree_idr').val(level);
			$('#valueTree_active').attr('checked', true);
			AddTreeForm.dialog( "open" );
		}
	}

	function EditToForm() {
		var find 	= tree_catalog.find(".active")	
		var id 		= find.attr('id');
			if(id){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_dataHandling,
					data: "id="+id,
					success:function (res) {//возвращаемый результат от сервера
					TablePrefix.jqGrid('setGridParam',{url: b_prefixUrl+"?prefix_id="+id,page:1,datatype: 'json',mtype: 'GET'}).trigger("reloadGrid");
						for(var key in res){ 
							if ($.inArray(key, Array('active', 'show_opt', 'show_banner')) != -1) {
								if (res[key] != 0) 	 {	$('#valueTree_'+key).attr('checked', true);} else { $('#valueTree_'+key).attr('checked', false); }						
							} else {
								if (key == "language") {
									baza = res['language'];
									for(var ley in baza){ 	
										$('#valueTree_'+ley).val(baza[ley]);
									}
								}							
								$('#valueTree_'+key).val(res[key]);
							}	
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
					});
				AddTreeForm.dialog( "open" );	
			} else {
				alert("Пожалуйста выберите разедл!")
			}
	}
	
	// Кнопка добавления папки
	$("#addtree").click(function(){	
		$("#action_tree").attr({value: "add"});		
		$.ajax({
			type: "POST",
			url: NextItemTree,
			success:function (res) {
				$("#valueTree_id").val(res);
				TablePrefix.jqGrid('setGridParam',{url: b_prefixUrl+"?prefix_id="+res,page:1}).trigger("reloadGrid");
			}
			});		
		AddToForm();
		tabs.tabs({active:'0'});		
	});	

	// Кнопка редактирования папки
	$("#edittree").click(function(){	
		$("#action_tree").attr({value: "edit"});		
		EditToForm();	
		tabs.tabs({active:'0'});		
	});	
	
	// Функция добавления папки
	function AddTreeDialog() {
		var str = $("#catalog_form_tree").serialize();
		var id  = $("#valueTree_pid").val();
			$.ajax({
				type: "POST",
				url: EditCatalogUrl,
				data: str,
				dataType: 'json',
				success:function (res) {//возвращаемый результат от сервера
					tree_catalog.empty();
					tree_catalog.html(res);
					tree_catalog.treeview(options );
					$("#"+id).addClass('active');
				},
				error: function(jqXHR, textStatus, errorThrown) {alert("Error! "+textStatus);}
			});
						
			$( this ).dialog( "close" );						
	}
	
	// Форма добавления
	AddTreeForm.dialog({
		autoOpen: false,
		title: "Свойства раздела",
		width: 910,
		height: 600,
		buttons: [
			{
				text: "Сохранить",
				click: AddTreeDialog
			},
			{
				text: "Отмена",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});			

	function DelTreeDialog() {

		var id = $("#del_id_tree").val();
			$.ajax({
				type: "POST",
				url: EditCatalogUrl,
				data: "del_id_tree="+id,
				dataType: 'json',
				success:function (res) {//возвращаемый результат от сервера
					tree_catalog.empty();
					tree_catalog.html(res);
					tree_catalog.treeview(options );
				},
				error: function(jqXHR, textStatus, errorThrown) {alert("Error! "+textStatus);}
			});	
			$( this ).dialog( "close" );						
	}

	// Функция удаления итема из таблицы
	function treeDelete(){
		var find = tree_catalog.find(".active")	
		var id 	 = find.attr('id');
			if( id != null ) {
				$("#delrazdel").dialog( "open" );
					$("#del_id_tree").val(id);
			} else alert("Пожалуйста выберите запись!");
	}
	
	$("#delrazdel").dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: DelTreeDialog
			},
			{
				text: "Нет",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
	
	$("#deltree").click(function(){
		treeDelete();
	});	
	
});