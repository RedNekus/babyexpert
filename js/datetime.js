
	function timer(cdchas,cdmin,cdsec,tag) {
		
		var today=new Date();
		
		var todaychas=today.getHours();
		var todaymin=today.getMinutes();
		var todaysec=today.getSeconds();

		futurestring = (cdchas*60*60)+(cdmin*60)+cdsec;
		todaystring = (todaychas*60*60)+(todaymin*60)+todaysec;
		
		dif=futurestring-todaystring;
		
		h = parseInt(dif / 3600);  
		m = parseInt((dif - h * 3600) / 60); 
		s = dif - h * 3600 - m * 60;
		
		if (dif<0) return;
		if(h==0&&m==0&&s==0){
			history.go(0);
			return;
		} else {
			if (h < 10) $("#chas"+tag).html("0"+h); else $("#chas"+tag).html(h);
			if (m < 10) $("#min"+tag).html("0"+m); else $("#min"+tag).html(m);
			if (s < 10) $("#sec"+tag).html("0"+s); else $("#sec"+tag).html(s);
		}

	}
