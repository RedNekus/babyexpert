$(document).ready(function() {
	var baseUrl				= "/adminpanel/couriers/";
	var height 				= $(window).height();
	var EditCatalogUrl 		= baseUrl+"edit";
	var b_dataHandling 		= baseUrl+"datatreehandling";
	var AddTreeForm     	= $("#AddTreeDialog");
	var tree_couriers    	= $("#tree_couriers");
	
	$("#left .ui-jqgrid-bdiv").height(height-120);
	$("#right .ui-jqgrid-bdiv").height(height-170);	
			 	
	var options = {
		collapsed: true,
		animated: "fast",
		unique: false,
		persist: "cookie"
	}			
				
	tree_couriers.treeview(options );	

	function EditToForm() {
		var find 	= tree_couriers.find(".active")	
		var id 		= find.attr('id');
			if(id){
				$.ajax({
					type: "POST",
					dataType: "json",
					url: b_dataHandling,
					data: "id="+id,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if (key == "active") {
								if (res[key] != 0) 	 {	$('#valueTree_'+key).attr('checked', true);} else { $('#valueTree_'+key).attr('checked', false); }						
							} else {							
								$('#valueTree_'+key).val(res[key]);
							}							
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
					});
				AddTreeForm.dialog( "open" );	
			} else {
				alert("Пожалуйста выберите раздел!")
			}
	}
	
	// Кнопка добавления папки
	$("#addtree").click(function(){	
		$("#couriers_form_tree").trigger("reset");
		$("#action_tree").attr({value: "add"});			
		AddTreeForm.dialog( "open" );		
	});	

	// Кнопка редактирования папки
	$("#edittree").click(function(){	
		$("#action_tree").attr({value: "edit"});		
		EditToForm();			
	});	
	
	// Функция добавления папки
	function AddTreeDialog() {
		var str = $("#couriers_form_tree").serialize();
		var id  = $("#valueTree_id").val();
			$.ajax({
				type: "POST",
				url: EditCatalogUrl,
				data: str,
				dataType: 'json',
				success:function (res) {//возвращаемый результат от сервера
					tree_couriers.empty();
					tree_couriers.html(res);
					tree_couriers.treeview(options );
					$("#"+id).addClass('active');
				},
				error: function(jqXHR, textStatus, errorThrown) {alert("Error! "+textStatus);}
			});
						
			$( this ).dialog( "close" );						
	}
	
	// Форма добавления
	AddTreeForm.dialog({
		autoOpen: false,
		title: "Свойства набора",
		width: 620,
		maxheight: 'auto',
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

		var id = $("#del_id_couriers").val();
			$.ajax({
				type: "POST",
				url: EditCatalogUrl,
				data: "del_id_couriers="+id,
				dataType: 'json',
				success:function (res) {//возвращаемый результат от сервера
					tree_couriers.empty();
					tree_couriers.html(res);
					tree_couriers.treeview(options );
				},
				error: function(jqXHR, textStatus, errorThrown) {alert("Error! "+textStatus);}
			});	
			$( this ).dialog( "close" );						
	}

	// Функция удаления итема из таблицы
	function treeDelete(){
		var find = tree_couriers.find(".active")	
		var id 	 = find.attr('id');
			if( id != null ) {
				$("#delrazdel").dialog( "open" );
					$("#del_id_couriers").val(id);
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