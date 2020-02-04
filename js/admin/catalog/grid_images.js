$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog_images/";
	var TableImages	   	 	= $('#TableImages');
	var catalogFormImage 	= $("#catalogFormImage");
	var DelImageForm  		= $("#DelImageForm");
	var EditCatalogUrl 		= baseUrl+"edit";	
	var b_dataHandlingImg	= baseUrl+"datahandlingimg";
	var b_imageUrl			= baseUrl+"load";	
	// Инициализация таблицы картинок
	TableImages.jqGrid({
		//url:b_imageUrl,
		datatype: 'local',
		//mtype: 'GET',
		width: 901,
		height: 404,
		colNames:['Фото','Файл','Описание','Базовая'],
		colModel :[
			{name:'picture', 		index:'picture', 		width:50, 	align:'center'},
			{name:'image', 			index:'image', 			width:200, 	align:'left'},
			{name:'description',	index:'description', 	width:300, 	align:'left'},
			{name:'showfirst', 		index:'showfirst', 		width:50, 	align:'center'},
			],
		toolbar: [true,"top"],
		rowNum:50,
		cache: false,
		viewrecords: true,
		ondblClickRow: function(id) {gridEditImage();}
	});	
		
	$('#t_TableImages').height(24);			
	$('#t_TableImages').append('<div id="addimage" class="button add"></div>		<div id="editimage" class="button edit"></div>		<div id="delimage" class="button del"></div>	 ');		
	
	// Обработка события кнопки Добавить изображение
	$("#addimage").click(function(){
		$("#catalog_frm_image").trigger("reset");
		
		var text = "",
			text_name = "",
			text_prefix = "",
			flag = false,
			flag2 = false;
	
		if ($("#val_name").val()=="") {
			text += "Поле НАЗВАНИЕ не заполнено\n";
			flag2 = true;
		} else {
			text_name = $("#val_name").val();
			flag2 = false;			
		}	
			
		if ($("#prefix_select").val()==0) {
			text += "Поле Префикс не заполнено\n";	
			flag = true;
		} else {
			text_prefix = $("#prefix_select").val();
			flag = false;
		}	

		if (flag || flag2) {
			alert(text);
		} else {		
			$("#action_image").attr({value: "add"});	
			$('#image_insert').html("<img src='' />");
			$('#valueImage_name_tovar').val(text_name);
			$('#valueImage_prefix_id_tovar').val(text_prefix);
			$('#valueImage_maker_id_tovar').val($("#val_id_maker").val());
			$('#valueImage_id_catalog').val($("#val_id").val());
			$("#valueImage_showfirst").attr({'checked': true});
			catalogFormImage.dialog( "open" );
		}
	});
	
	// Обработка события кнопки Редактировать изображение	
	$("#editimage").click(function(){
		gridEditImage();
	});
	
	// Обработка события кнопки Удалить изображение	
	$("#delimage").click(function(){
		delItem(TableImages,DelImageForm,$("#del_id_image"));
	});	
	
	function gridEditImage() {
		var gsr = TableImages.jqGrid('getGridParam','selrow');
			if(gsr){
				$.ajax({
					url: b_dataHandlingImg,
					dataType: "json",
					type: "POST",	
					data: "id="+gsr,
					success:function (res) {//возвращаемый результат от сервера
						for(var key in res){ 
							if (key == "showfirst") {
								if (res[key] != 0) 	 {	$('#valueImage_'+key).attr('checked', true);} else { $('#valueImage_'+key).attr('checked', false); }						
							} else {
								if (key == "image") {	
									$('#image_insert').html("<img src='"+res['url']+"' />");
								} else {
									$('#valueImage_'+key).val(res[key]);
								}
							}		
							if (key == "language") {								
							
								baza = res['language'];								
								
								for(var ley in baza){ 										
								$('#valueImage_'+ley).val(baza[ley]);								
								}							
							}	
							$('#valueImage_prefix_id_tovar').val($("#prefix_select").val());
							$('#valueImage_maker_id_tovar').val($("#val_id_maker").val());
							$('#valueImage_name_tovar').val($("#val_name").val());							
						}
						$("#valueImage_file").val("");	
					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
				$("#action_image").attr({value: "edit"});
				catalogFormImage.dialog( "open" );
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}	
	
	// Функция удаления данных из бд
	function delItem(bi_table, dialogForm, delValue){
		var gr = bi_table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				dialogForm.dialog( "open" );
				delValue.val(gr);
			} else alert("Пожалуйста выберите запись!");		
	}
	
	// Инициализация формы добавления и редактирования картинки					
	catalogFormImage.dialog({
		autoOpen: false,
		width: 570,
		height: 460,
		title: "Свойства картинки",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					$('#catalog_frm_image').submit();
					var iframe = $("#hiddenimageframe");
					$("#loading").show();
					iframe.load(function(){
						$("#loading").hide();
						TableImages.trigger("reloadGrid");
						catalogFormImage.dialog( "close" );
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
	
	// Форма удаления картинки
	DelImageForm.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: function () {
						var id = $("#del_id_image").val();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							data: "del_id_image="+id,
						});
						TableImages.trigger("reloadGrid");
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
});