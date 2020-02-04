function HideMenu(selector) {
	$(selector).append($(selector).find(".dropdown-menu li"));
	var len = $(selector).width() - 100;
	var slen = 0;
	var menuItems = $(selector).children();
	var li = $(selector).find('.dropdown');
	if(li.length) {
		li.remove();
		console.log('remove');
	} else {
		li = $(document.createElement("LI"));
		li.addClass('nav-item dropdown');
		li.append("<span class='dropdown-toggle nav-link' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Еще</span>");
		if(selector == '#catalog-menu')
			li.append("<div class='dropdown-menu dropdown-menu-right'><ul class='navbar-nav'></ul></div>");
		else
			li.append("<div class='dropdown-menu'><ul class='navbar-nav'></ul></div>");
	}
	menuItems.each(function(i){
		slen += $(this).width();
		if(slen > len && i < menuItems.length - 1) {
			var hidden = $(selector + " li:gt(" + i + "):not(.dropdown)");
			li.find("ul").append(hidden);
			$(selector).append(li);
			return false;
		}
	});
}
function phoneMask() { 
    var num = $(this).val().replace(/^\+375/g, '').replace(/\D/g,'');
	if(num)
		$(this).val('+375(' + num.substring(0,2) + ')' + num.substring(2,5) + '-' + num.substring(5,7) + '-' + num.substring(7,11)); 
}

$(document).ready(function(){
	console.log("ready");
	HideMenu('#MainMenu');
	HideMenu('#catalog-menu');
	$('[type="tel"]').keyup(phoneMask);
	$('.search-input').keyup(function(){
		console.log("keyup");
		$(this).parent().next('.search-results').show();
	});
	$('.search-input').focusout(function() {
		$('.search-results').hide();
	});
	$(window).resize(function(e) {
		HideMenu('#MainMenu');
		HideMenu('#catalog-menu');
		if($(window).width() < 768) {
			if(!$('.alphabet-list').hasClass('dropdown-menu')) {
				$('.alphabet-list').addClass('dropdown-menu');
				$('.filter').addClass('collapse');
				$('.sortby').addClass('dropdown-menu');
			}
		} else {
			$('.alphabet-list').removeClass('dropdown-menu');
			$('.alphabet-list').removeClass('dropdown-menu-right');
			$('.filter').removeClass('collapse');
			$('.sortby').removeClass('dropdown-menu');
		}
		var top = document.getElementsByClassName('top')[0];
		$(top).remove();
		if($(window).width() > 992) {
			$('.col-right').prepend(top);
		} else {
			$('main .container-xl > div').prepend(top);
		}
		if($(window).width() < 576) {
			if(!$('.goods-slider .sale').hasClass('sale-lg')) {
				$('.goods-slider .sale').addClass('sale-lg');
			}
		} else {
			$('.goods-slider .sale').removeClass('sale-lg');
		}
		console.log(e);
	});
	$(window).scroll(function() {
		var position = $( document ).scrollTop();
		if( position > 100) {
			$('.navbar-light').addClass('fixed-block');
		} else {
			$('.navbar-light').removeClass('fixed-block');
		}
	});
	$('.up').click(function(){
		$( 'html, body' ).animate({ scrollTop: '0px' });
		return false;
	});
	$('.number-input span').click(function() {
		var dir = $(this).data('dir');
		var input = this.parentNode.querySelector('input[type=number]');
		if(dir == 'up') input.stepUp();
		if(dir == 'down') input.stepDown();
	});
	$('.t-goods .text-danger.underline').click(function() {
		$(this.parentNode.parentNode).remove();
	});
	$("i.fa-times[data-action='del']").click(function() {
		$(this).parents('tr').remove();
	});
	$('.card-item span[data-action=del]').click(function() {
		var item = $(this).parents('.compare-item');
		var index = $('.compare-item').index(item);
		console.log(index);
		item.remove();
		console.log($('.compare-table td:nth-child(' + (index + 1) + ')'));
		$('.compare-table td:nth-child(' + (index + 1) + ')').remove();
	});
	$('.carousel-compare .compare-control').click(function() {
		var elem = $(this).siblings('.carousel-compare-inner').children();
		var index = elem.index(elem.filter('.active'));
		elem.eq(index + 3).removeClass('active');
		elem.eq(index - 1).addClass('active');
	});
	
	$(".goods-slider").carousel('dispose');
	$("#carouselReviews").carousel('dispose');
	
	$('#catalog-menu').on({
		"shown.bs.dropdown": function() { this.closable = false; },
		"click":             function() { this.closable = true; },
		"hide.bs.dropdown":  function() { return this.closable; }
	});
	$('#catalog-menu .submenu .row div:nth-child(n + 2)').on({
		"click.bs.dropdown.data-api": function(e) { console.log(this); e.stopPropagation(); }
	});
	
	if($(window).width() < 768) {
		$('.alphabet-list').addClass('dropdown-menu');
		$('.sortby').addClass('dropdown-menu');
		$('.filter').addClass('collapse');
	}
	if($(window).width() > 992) {
		$('.col-right').prepend($('.top'));
	}
	if($(window).width() < 576) {
		$('.goods-slider .sale').addClass('sale-lg');
	}
});