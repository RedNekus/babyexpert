$(document).ready(function() {
	var height = $(window).height();
	
	$("#left .ui-jqgrid-bdiv").height(height-150);
	$("#right .ui-jqgrid-bdiv").height(height-150);
	window.onresize = function () { 
		$("#left .ui-jqgrid-bdiv").height($(window).height()-150);
		$("#right .ui-jqgrid-bdiv").height($(window).height()-150);	
	} 	

	
});