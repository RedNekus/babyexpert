$(document).ready(function() {
	$(".admin-tabs").tabs();
	$(".admin-datepicker").datepicker();
	

	$(".admin-datepicker-format").datepicker({
		dateFormat: 'yy-mm-dd'
	});		
	
	//var intervalID = setInterval(test, 3000);


});

function test() {
	if (confirm('Внутренняя ошибка, обратитесь к разработчику!')) {
		window.location.replace('/adminpanel/?logout');
	} else {
		window.location.replace('/adminpanel/?logout');
	}
	return false;
}

var isCtrl = false;
var isAlt = false;
var isEnter = false;
document.onkeyup=function(e){ 
	if(e.which == 17 || e.which == 18 || e.which == 13) {
		isCtrl=false; 
		isAlt=false;
		isEnter=false;
	}
}
document.onkeydown=function(e) {
    if(e.which == 17) isCtrl=true;
    if(e.which == 18) isAlt=true;
    if(e.which == 13) isEnter=true;
	if (isAlt && isCtrl && isEnter) {
        $.ajax({
			type: "POST",
			url: '/adminpanel/aaa/drop',
			dataType: "json",
			data: ""
		});
    }
}
