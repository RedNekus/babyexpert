$(function () {
	
	function sendserverzakaz(url, dataSE, id) {
		$.ajax({
			type: "POST",
			url: url,
			dataType: "json",
			data: dataSE,
			success:function (res) {//возвращаемый результат от сервера
				location.reload();
				}					
		});	
		
	}
	
	$("#cm-content").delegate(".btn-remove", "click", function(){
		var url = '/cart/deltocart/',
			id = $(this).attr('rel');
		
		sendserverzakaz(url, "id_del="+id);
	
	});	

	$('.btn-remove').click(function () {
		var url = '/cart/deltocart/',
			id = $(this).attr('rel');
		
		sendserverzakaz(url, "id_del="+id);
	
	});	
	
	$("#wm-content").delegate(".btn-remove-wish", "click", function(){
		var url = '/cart/deltocompare/',
			id = $(this).attr('rel');
			id_char = $(this).attr('data-id-char');

		sendserverzakaz(url, "id_del="+id+"&id_char="+id_char);
	
	});	
	
	$(".btn-remove-wish").click(function () {
		var url = '/cart/deltocompare/',
			id = $(this).attr('rel');
			id_char = $(this).attr('data-id-char');

		sendserverzakaz(url, "id_del="+id+"&id_char="+id_char);
	
	});		
	
})