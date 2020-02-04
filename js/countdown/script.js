$(function(){
	ts = (new Date(2014,08,30)).getTime();
	
	$('#countdown').countdown({
		timestamp	: ts,
		callback	: function(d, h, m, s){
			
			if (d < 10) $("#cday").html("0"+d); else $("#cday").html(d);
			if (h < 10) $("#chas").html("0"+h); else $("#chas").html(h);
			if (m < 10) $("#min").html("0"+m); else $("#min").html(m);
			if (s < 10) $("#sec").html("0"+s); else $("#sec").html(s);
			
		}
	});

});
