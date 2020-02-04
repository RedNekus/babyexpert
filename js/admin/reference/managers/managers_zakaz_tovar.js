$(document).ready(function() {
	var baseUrl			= "/adminpanel/couriers/";

	var table 			= $('#TableTovar');
	var pager 			= '#TableTovarPager';
	
	
	table.jqGrid({
		datatype: 'local',
		width: 860,
		height: 'auto',
        colNames:['Наименование','Кол-во','Цена $','Цена Br', 'Сумма $', 'Сумма Br', 'Подарок', 'Розыгрыш', 'Статус'],
        colModel :[
            {name:'name', 		index:'name', 		width:310, 	align:'left', 	search:false, },
            {name:'kolvo', 		index:'kolvo', 		width:60, 	align:'center', search:false, },
            {name:'cena', 		index:'cena', 		width:60, 	align:'center', search:false, },
            {name:'cena_blr', 	index:'cena_blr', 	width:120, 	align:'left',   search:false, },
            {name:'summa', 		index:'summa', 		width:80, 	align:'center', search:false, },
            {name:'summa_blr',	index:'summa_blr', 	width:120, 	align:'center', search:false, },
            {name:'gift', 		index:'gift', 		width:60, 	align:'center', search:false, },
            {name:'raffle', 	index:'raffle', 	width:60, 	align:'center', search:false, },
            {name:'status', 	index:'status', 	width:110, 	align:'center', search:false, },
			],			
		pager: pager,	
        sortname: 'id',
		toolbar: [true,"top"],
        sortorder: 'asc',
		footerrow: true,
		userDataOnFooter: true,			
    }).jqGrid('navGrid', pager,{refresh: true, add: false, del: false, edit: false, search: true, view: false},
					{}, // параметры редактирования
					{}, // параметры добавления
					{}, // параметры удаления
					{closeOnEscape:true, multipleSearch:false, closeAfterSearch:true}, // параметры поиска
					{} /* параметры просмотра */
	);
	
});