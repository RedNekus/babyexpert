$(document).ready(function() {
	var baseUrl					= "/adminpanel/brand/";
	$('.admin-tabs').tabs();
	var TableCatalog 			= $('#TableCatalog');	
	var TableCatalogPager		= '#TableCatalogPager';
	var brandForm 				= $("#brandForm");
	var brandFormDel 			= $("#brandFormDel");	
	var LoadCatalogUrl			= baseUrl+"load";
	var EditCatalogUrl 			= baseUrl+"edit";
	var b_dataHandling 			= baseUrl+"datahandling";
	var b_dataInit				= baseUrl+"datainit";
	var NextItemCatalog			= baseUrl+"nextitemcatalog";	
	
	// при нажатии на ссылку на дереве
	$("#tree_brand").delegate(".linkrel", "click", function(){
		var id 	 = $(this).attr('id');
		$('#tree_brand').find(".active").removeClass("active");
		$(this).addClass('active');	
		TableCatalog.jqGrid('setGridParam',{url: LoadCatalogUrl+"?id="+id,page:1}).trigger("reloadGrid");
	});
	
	TableCatalog.jqGrid({
		url:LoadCatalogUrl,
		datatype: 'json',
        mtype: 'GET',
		//cmTemplate:{resizable:false},
		autowidth: true,
        colNames:['Код','Название','Title','Keywords','Description'],
        colModel :[
            {name:'n1', 	index:'n1', 	width:10, 	align:'center', search:true, 	editable:true, 	edittype:"text" },
            {name:'n2',		index:'n2', 	width:250, 	align:'left', 	search:true, 	editable:true, 	edittype:"text" },
			{name:'n3',		index:'n3', 	width:250, 	align:'left', 	search:true, 	editable:true, 	edittype:"text" },
			{name:'n4',		index:'n4', 	width:50, 	align:'center', search:true, 	editable:true, 	edittype:"text" },
			{name:'n5',		index:'n5', 	width:50, 	align:'center', search:true, 	editable:true, 	edittype:"text" },

			],
        pager: TableCatalogPager,
		toolbar: [true,"top"],		
        sortname: 'name',
        sortorder: 'asc',
		rowNum:50,		
		viewrecords: true,
		rownumbers: true,
        rownumWidth: 30,		
		ondblClickRow: function(id) {	gridEdit();	},	
    });
	
	var btn_group = '<div id="adddata" class="button add"></div><div id="editdata" class="button edit"></div><div id="deldata" class="button del"></div>';

	$('#t_TableCatalog').height(24);			
	$('#t_TableCatalog').append( btn_group );			
	
	// Функция удаления данных о группе характеристики
	function delItem(bi_table, dialogForm, delValue){
		var gr = bi_table.jqGrid('getGridParam','selrow');
			if( gr != null ) {
				dialogForm.dialog( "open" );
				delValue.val(gr);
			} else alert("Пожалуйста выберите запись!");		
	}
	
	function gridAdd() {
		$("#brand_form").trigger("reset");
		$("#action_pole").attr({value: "add"});	
		var id 		= $("#tree_brand").find(".active").attr('id');
		if (id) {
		$('#val_id_catalog_tree').val(id);	
		$.ajax({
			type: "POST",
			url: NextItemCatalog,
			dataType: "json",
			data: 'id_razdel='+id,
			success:function (res) {				
				$("#val_id_maker").html(res['maker']);			
			}
		});			
		brandForm.dialog( "open" );
		} else {
			alert("Выберите нужный раздел");
		}
	}
	
	// Функция редактирования группы
	function gridEdit() {
		var gsr = TableCatalog.jqGrid('getGridParam','selrow');
		var id_razdel = $("#tree_brand").find(".active").attr('id');
			if(gsr){
				$.ajax({
					url: b_dataHandling,
					dataType: "json",
					type: "POST",	
					data: "id="+gsr+"&id_razdel="+id_razdel,
					success:function (res) {//возвращаемый результат от сервера		
						for(var key in res){ 	
							if (key == "active") {
								if (res[key] != 0) 	 {	$('#val_'+key).attr('checked', true);} else { $('#val_'+key).attr('checked', false); }						
							} else {
								if (key == "language") {
									baza = res['language'];
									for(var ley in baza){ 	
										$('#val_'+ley).val(baza[ley]);
									}
								}								
								if (key == "maker") {
								$("#val_id_maker").html(res['maker']);
								}							
								$('#val_'+key).val(res[key]);
							}							
						}	

					},
					error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
				});
			$("#action_pole").attr({value: "edit"});
			brandForm.dialog( "open" );	
			} else {
				alert("Пожалуйста выберите запись!")
			}	
	}
	
	// Обработка события кнопки Добавить группу
	$("#adddata").click(function(){
		gridAdd();
	});

	// Обработка события кнопки Редактировать группу	
	$("#editdata").click(function(){
		gridEdit();		
	});
	
	// Обработка события кнопки Удалить	группу
	$("#deldata").click(function(){
		delItem(TableCatalog,brandFormDel,$("#del_id_brand"));	
	});

	// Инициализация формы добавления и редактирования группы					
	brandForm.dialog({
		autoOpen: false,
		width: 910,
		height: 600,
		title: "Свойства бренда",
		buttons: [
				{
				text: "Сохранить",
				click: function() {
					var str = $("#brand_form").serialize();
						$.ajax({
							type: "POST",
							url: EditCatalogUrl,
							data: str,
							});
						TableCatalog.trigger("reloadGrid");
						$( this ).dialog( "close" );								
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
	
	// Форма удаления группы	
	brandFormDel.dialog({
		autoOpen: false,
		title: "Подтверждение",
		width: 400,
		buttons: [
			{
				text: "Да",
				click: 	function () {
							var id = $("#del_id_brand").val();
								$.ajax({
									type: "POST",
									url: EditCatalogUrl,
									data: "del_id_brand="+id,
								});
								TableCatalog.trigger("reloadGrid");
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