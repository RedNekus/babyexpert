$(document).ready(function() {
	var baseUrl				= "/adminpanel/catalog/";	
	var treeCatalogRazdel 	= $("#tree_catalog_razdel");
	var tableRazdel 		= $("#TableRazdel");
	var tableRazdelPager	= "#pTableRazdel";
	var b_dataInit			= baseUrl+"datainit";

	var options = {
		collapsed: true,
		animated: "fast",
		unique: false
	}		
	
	var val_id_razdel = [];
	
	var rowI = 9999;
	treeCatalogRazdel.treeview(options );
	
	// при нажатии на ссылку на дереве
	treeCatalogRazdel.delegate(".linkrel", "click", function(){
		var id 	 = 1;
	
		id = $(this).attr('id'); 

		treeCatalogRazdel.find(".active").removeClass("active");
		$(this).addClass('active');
		$("#loading_razdel").show();
		
			$.ajax({type: "POST",url: b_dataInit,dataType: "json",data: "id="+id,
						success:function (res) {//возвращаемый результат от сервера
							for (var i = 0; i <= 5; i++) {
								val_id_razdel[i] = res[i];
							}
							val_id_razdel[6] = res['name'];
							$("#loading_razdel").hide();
						},
						error: function(jqXHR, textStatus, errorThrown) {alert(textStatus);}						
			});
		
	});		

	tableRazdel.jqGrid({
		datatype: "local",
		autowidth: true,
		height: 358,
		colNames:['№','idr0','idr1','idr2','idr3','idr4','idr5','Наименование раздела'],
		colModel:[
			{name:'no',index:'no',width:30,hidden:true},
			{name:'id_razdel0',index:'id_razdel0',width:60,hidden:true},
			{name:'id_razdel1',index:'id_razdel1',width:60,hidden:true},
			{name:'id_razdel2',index:'id_razdel2',width:60,hidden:true},
			{name:'id_razdel3',index:'id_razdel3',width:65,hidden:true},
			{name:'id_razdel4',index:'id_razdel4',width:65,hidden:true},	
			{name:'id_razdel5',index:'id_razdel5',width:65,hidden:true},
			{name:'name',index:'name',width:465}
		],
        pager: tableRazdelPager,		
		sortname: 'id_razdel0'
	}).jqGrid('navGrid', tableRazdelPager,{refresh: false, add: false, del: false, edit: false, search: false, view: false},{},{},{},{},{})
		.jqGrid('navButtonAdd', tableRazdelPager,{
				caption: '',
				title: 'Добавить товар в выбранный раздел',
				buttonicon: 'ui-icon-plus',
					onClickButton: function(){
						tableRazdel.addRowData(rowI++,{
							no:rowI,
							id_razdel0:val_id_razdel[0],
							id_razdel1:val_id_razdel[1],
							id_razdel2:val_id_razdel[2],
							id_razdel3:val_id_razdel[3],
							id_razdel4:val_id_razdel[4],
							id_razdel5:val_id_razdel[5],
							name:val_id_razdel[6]
							});
						},
				position:'last'
				})	
		.jqGrid('navButtonAdd', tableRazdelPager,{
				caption: '',
				title: 'Удалить товар из выбранного раздела',
				buttonicon: 'ui-icon-minus',
					onClickButton: function(){
					var gsr = tableRazdel.jqGrid('getGridParam','selrow');
						tableRazdel.delRowData(gsr);
						},
				position:'last'
				});

				
});