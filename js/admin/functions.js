function translit(selector) {
  $(selector).each(function () {
    $(this).keyup(function () {
      var ru2en = {
        ru_str : "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя ",
        en_str : ['A','B','V','G','D','E','YO','ZH','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','H','C','CH','SH','SCH','','Y','','E','YU','YA',
                  'a','b','v','g','d','e','yo','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','','y','','e','yu','ya','-'],
        translit : function(org_str) {
          var tmp_str = [];
          for(var i = 0, l = org_str.length; i < l; i++) {
            var s = org_str.charAt(i), n = this.ru_str.indexOf(s);
            if(n >= 0) {
              tmp_str[tmp_str.length] = this.en_str[n];
            }
            else {
              tmp_str[tmp_str.length] = s.match(/[a-z0-9_\-.]/i);
            }
          }
          return tmp_str.join('');
        }
      }
      $(this).val(ru2en.translit($(this).val().toLowerCase()));
    });
  });
}

function digit_filter(selector) {
  $(selector).each(function () {
    $(this).keyup(function () {
      $(this).val($(this).val().match(/\d+/));
    });
  });
}

function showMsgBox(f, b, e) {
    var a = $("#msg-box");
    e = e || "center";
    b = b || "white";
    if (a.length === 0) {
        a = $("<div />").attr("id", "msg-box").addClass("b-msg-box " + (b || ""));
        $("<p />").html(f).css("text-align", e).appendTo(a);
        $("<span />").addClass("close").click(function () {
            a.hide()
        }).appendTo(a);
        a.appendTo("body")
    } else {
        a.attr("class", "b-msg-box " + (b || "")).find("p").html(f).css("text-align", e).end().show()
    }
    var d = ($(window).height() - a.height()) / 2,
        c = ($(window).width() - a.width()) / 2;
    a.css({
        top: d,
        left: c
    })
}

	
function getSelectValue(url) {
	var re = '';
	$.ajax({type: "POST",url: url+"getselect", async: false, data: "",
		success:function (res) {//возвращаемый результат от сервера	
			re = res;
		}						
	});	
	return re;	
}

		
function getDayWeek(cellval, opts, rowObject) {
	var fullOpts = $.extend({}, $.jgrid.formatter.date, opts),
		formattedDate = $.fn.fmatter('Y-m-d', cellval, 'd-M-Y', fullOpts);
	dateObj = new Date(cellval).getDay();
	day = ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
	
	result = formattedDate+' '+day[dateObj];
	return result;
}

	
function colorGrey ( cellvalue, options, rowObject ) {
	return '<font style="color:#777">' + cellvalue + '</font>';//or use classes
}


function in_array(needle, haystack, strict) {

   var found = false, key, strict = !!strict;

   for (key in haystack) {

      if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {

         found = true;

         break;

      }

   }

   return found;

}