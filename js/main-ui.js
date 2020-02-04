$(function () {

    $(".w-select select").selectmenu({
        maxHeight: 342,
		menuWidth: '130%',
    });  
	
	$(".f-select select").selectmenu({
        maxHeight: 342
    });
	
    $("#slider").nivoSlider({
        directionNav: false
    }); 

    $("#product-media-tabs").tabs();
    $("#pmodels-carousel").jcarousel();
    $("#pimages-carousel").jcarousel();
    $("#product-info-tabs").tabs({
        cookie: {
            expires: 1
        }
    });
    $("#product-desc-tabs").tabs();

    $("#pmodels-carousel a").click(function () {
        var m = $(this),
            i = $("#product-medium-img"),
            k = m.find("img").attr("alt");
        m.parents(".product-media-carousel").find("a").removeClass("active");
        m.addClass("active");

        $("#pheader").text(k);
 
        i.attr({
            href: m.attr("href"),
            title: k
        });
        var h = m.attr("href"),
            j = h.lastIndexOf("."),
            g = h.substr(j),
            l = h.substr(0, j),
            h = l + g;
        i.find("img").attr("src", h);
        $("#form-product-buy input[name=pid]").val(m.attr("data-model-id"));
        $("#form-product-wishlist input[name=pid]").val(m.attr("data-model-id"));
		$("#refresh-price > span").empty();
        $("#refresh-price > span").html(m.attr("data-model-price"));
		$("#manager_tmp").val(m.attr("data-model-id"));
        return false
    });
	

    $("#pimages-carousel a").click(function () {
        var i = $(this),
            h = $("#product-medium-img");
        i.parents(".product-media-carousel").find("a").removeClass("active");
        i.addClass("active");
        h.attr({
            href: i.attr("href"),
            title: i.find("img").attr("alt")
        });
        var e = i.attr("href"),
            f = e.lastIndexOf("."),
            g = e.substr(f),
            j = e.substr(0, f),
            e = j + g;
        h.find("img").attr("src", e);
        return false
    });

    //$("#product-medium-img").fancybox();
    $(".fancybox").fancybox({
		prevEffect		: 'none',
		nextEffect		: 'none',
		chooseBtn		: false,
	});
    $(".fancybox_color").fancybox({
		prevEffect		: 'none',
		nextEffect		: 'none',		
		helpers		: {
			title	: { type : 'inside' },
			buttons	: {}
		}
	});		

});