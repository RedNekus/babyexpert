/**
 * jQuery Cookie plugin
 */
jQuery.cookie=function(d,e,b){if(arguments.length>1&&(e===null||typeof e!=="object")){b=jQuery.extend({},b);if(e===null){b.expires=-1}if(typeof b.expires==="number"){var g=b.expires,c=b.expires=new Date();c.setDate(c.getDate()+g)}return(document.cookie=[encodeURIComponent(d),"=",b.raw?String(e):encodeURIComponent(String(e)),b.expires?"; expires="+b.expires.toUTCString():"",b.path?"; path="+b.path:"",b.domain?"; domain="+b.domain:"",b.secure?"; secure":""].join(""))}b=e||{};var a,f=b.raw?function(h){return h}:decodeURIComponent;return(a=new RegExp("(?:^|; )"+encodeURIComponent(d)+"=([^;]*)").exec(document.cookie))?f(a[1]):null};


/*
 * jQuery Nivo Slider v2.7.1
 * http://nivo.dev7studios.com
 *
 * Copyright 2011, Gilbert Pellegrom
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * March 2010
 */

(function(a){var b=function(b,c){var d=a.extend({},a.fn.nivoSlider.defaults,c);var e={currentSlide:0,currentImage:"",totalSlides:0,running:false,paused:false,stop:false};var f=a(b);f.data("nivo:vars",e);f.css("position","relative");f.addClass("nivoSlider");var g=f.children();g.each(function(){var b=a(this);var c="";if(!b.is("img")){if(b.is("a")){b.addClass("nivo-imageLink");c=b}b=b.find("img:first")}var d=b.width();if(d==0)d=b.attr("width");var g=b.height();if(g==0)g=b.attr("height");if(d>f.width()){f.width(d)}if(g>f.height()){f.height(g)}if(c!=""){c.css("display","none")}b.css("display","none");e.totalSlides++});if(d.randomStart){d.startSlide=Math.floor(Math.random()*e.totalSlides)}if(d.startSlide>0){if(d.startSlide>=e.totalSlides)d.startSlide=e.totalSlides-1;e.currentSlide=d.startSlide}if(a(g[e.currentSlide]).is("img")){e.currentImage=a(g[e.currentSlide])}else{e.currentImage=a(g[e.currentSlide]).find("img:first")}if(a(g[e.currentSlide]).is("a")){a(g[e.currentSlide]).css("display","block")}f.css("background",'url("'+e.currentImage.attr("src")+'") no-repeat');f.append(a('<div class="nivo-caption"><p></p></div>').css({display:"none",opacity:d.captionOpacity}));a(".nivo-caption",f).css("opacity",0);var h=function(b){var c=a(".nivo-caption",f);if(e.currentImage.attr("title")!=""&&e.currentImage.attr("title")!=undefined){var d=e.currentImage.attr("title");if(d.substr(0,1)=="#")d=a(d).html();if(c.css("opacity")!=0){c.find("p").stop().fadeTo(b.animSpeed,0,function(){a(this).html(d);a(this).stop().fadeTo(b.animSpeed,1)})}else{c.find("p").html(d)}c.stop().fadeTo(b.animSpeed,b.captionOpacity)}else{c.stop().fadeTo(b.animSpeed,0)}};h(d);var i=0;if(!d.manualAdvance&&g.length>1){i=setInterval(function(){o(f,g,d,false)},d.pauseTime)}if(d.directionNav){f.append('<div class="nivo-directionNav"><a class="nivo-prevNav">'+d.prevText+'</a><a class="nivo-nextNav">'+d.nextText+"</a></div>");if(d.directionNavHide){a(".nivo-directionNav",f).hide();f.hover(function(){a(".nivo-directionNav",f).show()},function(){a(".nivo-directionNav",f).hide()})}a("a.nivo-prevNav",f).live("click",function(){if(e.running)return false;clearInterval(i);i="";e.currentSlide-=2;o(f,g,d,"prev")});a("a.nivo-nextNav",f).live("click",function(){if(e.running)return false;clearInterval(i);i="";o(f,g,d,"next")})}if(d.controlNav){var j=a('<div class="nivo-controlNav"></div>');f.append(j);for(var k=0;k<g.length;k++){if(d.controlNavThumbs){var l=g.eq(k);if(!l.is("img")){l=l.find("img:first")}if(d.controlNavThumbsFromRel){j.append('<a class="nivo-control" rel="'+k+'"><img src="'+l.attr("rel")+'" alt="" /></a>')}else{j.append('<a class="nivo-control" rel="'+k+'"><img src="'+l.attr("src").replace(d.controlNavThumbsSearch,d.controlNavThumbsReplace)+'" alt="" /></a>')}}else{j.append('<a class="nivo-control" rel="'+k+'">'+(k+1)+"</a>")}}a(".nivo-controlNav a:eq("+e.currentSlide+")",f).addClass("active");a(".nivo-controlNav a",f).live("click",function(){if(e.running)return false;if(a(this).hasClass("active"))return false;clearInterval(i);i="";f.css("background",'url("'+e.currentImage.attr("src")+'") no-repeat');e.currentSlide=a(this).attr("rel")-1;o(f,g,d,"control")})}if(d.keyboardNav){a(window).keypress(function(a){if(a.keyCode=="37"){if(e.running)return false;clearInterval(i);i="";e.currentSlide-=2;o(f,g,d,"prev")}if(a.keyCode=="39"){if(e.running)return false;clearInterval(i);i="";o(f,g,d,"next")}})}if(d.pauseOnHover){f.hover(function(){e.paused=true;clearInterval(i);i=""},function(){e.paused=false;if(i==""&&!d.manualAdvance){i=setInterval(function(){o(f,g,d,false)},d.pauseTime)}})}f.bind("nivo:animFinished",function(){e.running=false;a(g).each(function(){if(a(this).is("a")){a(this).css("display","none")}});if(a(g[e.currentSlide]).is("a")){a(g[e.currentSlide]).css("display","block")}if(i==""&&!e.paused&&!d.manualAdvance){i=setInterval(function(){o(f,g,d,false)},d.pauseTime)}d.afterChange.call(this)});var m=function(b,c,d){for(var e=0;e<c.slices;e++){var f=Math.round(b.width()/c.slices);if(e==c.slices-1){b.append(a('<div class="nivo-slice"></div>').css({left:f*e+"px",width:b.width()-f*e+"px",height:"0px",opacity:"0",background:'url("'+d.currentImage.attr("src")+'") no-repeat -'+(f+e*f-f)+"px 0%"}))}else{b.append(a('<div class="nivo-slice"></div>').css({left:f*e+"px",width:f+"px",height:"0px",opacity:"0",background:'url("'+d.currentImage.attr("src")+'") no-repeat -'+(f+e*f-f)+"px 0%"}))}}};var n=function(b,c,d){var e=Math.round(b.width()/c.boxCols);var f=Math.round(b.height()/c.boxRows);for(var g=0;g<c.boxRows;g++){for(var h=0;h<c.boxCols;h++){if(h==c.boxCols-1){b.append(a('<div class="nivo-box"></div>').css({opacity:0,left:e*h+"px",top:f*g+"px",width:b.width()-e*h+"px",height:f+"px",background:'url("'+d.currentImage.attr("src")+'") no-repeat -'+(e+h*e-e)+"px -"+(f+g*f-f)+"px"}))}else{b.append(a('<div class="nivo-box"></div>').css({opacity:0,left:e*h+"px",top:f*g+"px",width:e+"px",height:f+"px",background:'url("'+d.currentImage.attr("src")+'") no-repeat -'+(e+h*e-e)+"px -"+(f+g*f-f)+"px"}))}}}};var o=function(b,c,d,e){var f=b.data("nivo:vars");if(f&&f.currentSlide==f.totalSlides-1){d.lastSlide.call(this)}if((!f||f.stop)&&!e)return false;d.beforeChange.call(this);if(!e){b.css("background",'url("'+f.currentImage.attr("src")+'") no-repeat')}else{if(e=="prev"){b.css("background",'url("'+f.currentImage.attr("src")+'") no-repeat')}if(e=="next"){b.css("background",'url("'+f.currentImage.attr("src")+'") no-repeat')}}f.currentSlide++;if(f.currentSlide==f.totalSlides){f.currentSlide=0;d.slideshowEnd.call(this)}if(f.currentSlide<0)f.currentSlide=f.totalSlides-1;if(a(c[f.currentSlide]).is("img")){f.currentImage=a(c[f.currentSlide])}else{f.currentImage=a(c[f.currentSlide]).find("img:first")}if(d.controlNav){a(".nivo-controlNav a",b).removeClass("active");a(".nivo-controlNav a:eq("+f.currentSlide+")",b).addClass("active")}h(d);a(".nivo-slice",b).remove();a(".nivo-box",b).remove();var g=d.effect;if(d.effect=="random"){var i=new Array("sliceDownRight","sliceDownLeft","sliceUpRight","sliceUpLeft","sliceUpDown","sliceUpDownLeft","fold","fade","boxRandom","boxRain","boxRainReverse","boxRainGrow","boxRainGrowReverse");g=i[Math.floor(Math.random()*(i.length+1))];if(g==undefined)g="fade"}if(d.effect.indexOf(",")!=-1){var i=d.effect.split(",");g=i[Math.floor(Math.random()*i.length)];if(g==undefined)g="fade"}if(f.currentImage.attr("data-transition")){g=f.currentImage.attr("data-transition")}f.running=true;if(g=="sliceDown"||g=="sliceDownRight"||g=="sliceDownLeft"){m(b,d,f);var j=0;var k=0;var l=a(".nivo-slice",b);if(g=="sliceDownLeft")l=a(".nivo-slice",b)._reverse();l.each(function(){var c=a(this);c.css({top:"0px"});if(k==d.slices-1){setTimeout(function(){c.animate({height:"100%",opacity:"1.0"},d.animSpeed,"",function(){b.trigger("nivo:animFinished")})},100+j)}else{setTimeout(function(){c.animate({height:"100%",opacity:"1.0"},d.animSpeed)},100+j)}j+=50;k++})}else if(g=="sliceUp"||g=="sliceUpRight"||g=="sliceUpLeft"){m(b,d,f);var j=0;var k=0;var l=a(".nivo-slice",b);if(g=="sliceUpLeft")l=a(".nivo-slice",b)._reverse();l.each(function(){var c=a(this);c.css({bottom:"0px"});if(k==d.slices-1){setTimeout(function(){c.animate({height:"100%",opacity:"1.0"},d.animSpeed,"",function(){b.trigger("nivo:animFinished")})},100+j)}else{setTimeout(function(){c.animate({height:"100%",opacity:"1.0"},d.animSpeed)},100+j)}j+=50;k++})}else if(g=="sliceUpDown"||g=="sliceUpDownRight"||g=="sliceUpDownLeft"){m(b,d,f);var j=0;var k=0;var o=0;var l=a(".nivo-slice",b);if(g=="sliceUpDownLeft")l=a(".nivo-slice",b)._reverse();l.each(function(){var c=a(this);if(k==0){c.css("top","0px");k++}else{c.css("bottom","0px");k=0}if(o==d.slices-1){setTimeout(function(){c.animate({height:"100%",opacity:"1.0"},d.animSpeed,"",function(){b.trigger("nivo:animFinished")})},100+j)}else{setTimeout(function(){c.animate({height:"100%",opacity:"1.0"},d.animSpeed)},100+j)}j+=50;o++})}else if(g=="fold"){m(b,d,f);var j=0;var k=0;a(".nivo-slice",b).each(function(){var c=a(this);var e=c.width();c.css({top:"0px",height:"100%",width:"0px"});if(k==d.slices-1){setTimeout(function(){c.animate({width:e,opacity:"1.0"},d.animSpeed,"",function(){b.trigger("nivo:animFinished")})},100+j)}else{setTimeout(function(){c.animate({width:e,opacity:"1.0"},d.animSpeed)},100+j)}j+=50;k++})}else if(g=="fade"){m(b,d,f);var q=a(".nivo-slice:first",b);q.css({height:"100%",width:b.width()+"px"});q.animate({opacity:"1.0"},d.animSpeed*2,"",function(){b.trigger("nivo:animFinished")})}else if(g=="slideInRight"){m(b,d,f);var q=a(".nivo-slice:first",b);q.css({height:"100%",width:"0px",opacity:"1"});q.animate({width:b.width()+"px"},d.animSpeed*2,"",function(){b.trigger("nivo:animFinished")})}else if(g=="slideInLeft"){m(b,d,f);var q=a(".nivo-slice:first",b);q.css({height:"100%",width:"0px",opacity:"1",left:"",right:"0px"});q.animate({width:b.width()+"px"},d.animSpeed*2,"",function(){q.css({left:"0px",right:""});b.trigger("nivo:animFinished")})}else if(g=="boxRandom"){n(b,d,f);var r=d.boxCols*d.boxRows;var k=0;var j=0;var s=p(a(".nivo-box",b));s.each(function(){var c=a(this);if(k==r-1){setTimeout(function(){c.animate({opacity:"1"},d.animSpeed,"",function(){b.trigger("nivo:animFinished")})},100+j)}else{setTimeout(function(){c.animate({opacity:"1"},d.animSpeed)},100+j)}j+=20;k++})}else if(g=="boxRain"||g=="boxRainReverse"||g=="boxRainGrow"||g=="boxRainGrowReverse"){n(b,d,f);var r=d.boxCols*d.boxRows;var k=0;var j=0;var t=0;var u=0;var v=new Array;v[t]=new Array;var s=a(".nivo-box",b);if(g=="boxRainReverse"||g=="boxRainGrowReverse"){s=a(".nivo-box",b)._reverse()}s.each(function(){v[t][u]=a(this);u++;if(u==d.boxCols){t++;u=0;v[t]=new Array}});for(var w=0;w<d.boxCols*2;w++){var x=w;for(var y=0;y<d.boxRows;y++){if(x>=0&&x<d.boxCols){(function(c,e,f,h,i){var j=a(v[c][e]);var k=j.width();var l=j.height();if(g=="boxRainGrow"||g=="boxRainGrowReverse"){j.width(0).height(0)}if(h==i-1){setTimeout(function(){j.animate({opacity:"1",width:k,height:l},d.animSpeed/1.3,"",function(){b.trigger("nivo:animFinished")})},100+f)}else{setTimeout(function(){j.animate({opacity:"1",width:k,height:l},d.animSpeed/1.3)},100+f)}})(y,x,j,k,r);k++}x--}j+=100}}};var p=function(a){for(var b,c,d=a.length;d;b=parseInt(Math.random()*d),c=a[--d],a[d]=a[b],a[b]=c);return a};var q=function(a){if(this.console&&typeof console.log!="undefined")console.log(a)};this.stop=function(){if(!a(b).data("nivo:vars").stop){a(b).data("nivo:vars").stop=true;q("Stop Slider")}};this.start=function(){if(a(b).data("nivo:vars").stop){a(b).data("nivo:vars").stop=false;q("Start Slider")}};d.afterLoad.call(this);return this};a.fn.nivoSlider=function(c){return this.each(function(d,e){var f=a(this);if(f.data("nivoslider"))return f.data("nivoslider");var g=new b(this,c);f.data("nivoslider",g)})};a.fn.nivoSlider.defaults={effect:"random",slices:15,boxCols:8,boxRows:4,animSpeed:500,pauseTime:3e3,startSlide:0,directionNav:true,directionNavHide:true,controlNav:true,controlNavThumbs:false,controlNavThumbsFromRel:false,controlNavThumbsSearch:".jpg",controlNavThumbsReplace:"_thumb.jpg",keyboardNav:true,pauseOnHover:true,manualAdvance:false,captionOpacity:.8,prevText:"Prev",nextText:"Next",randomStart:false,beforeChange:function(){},afterChange:function(){},slideshowEnd:function(){},lastSlide:function(){},afterLoad:function(){}};a.fn._reverse=[].reverse})(jQuery);

/*
 * Metadata - jQuery plugin for parsing metadata from elements
 *
 * Copyright (c) 2006 John Resig, Yehuda Katz, J?rn Zaefferer, Paul McLanahan
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id$
 *
 */
(function($){$.extend({metadata:{defaults:{type:"class",name:"metadata",cre:/({.*})/,single:"metadata"},setType:function(type,name){this.defaults.type=type;this.defaults.name=name},get:function(elem,opts){var settings=$.extend({},this.defaults,opts);if(!settings.single.length){settings.single="metadata"}var data=$.data(elem,settings.single);if(data){return data}data="{}";if(settings.type=="class"){var m=settings.cre.exec(elem.className);if(m){data=m[1]}}else{if(settings.type=="elem"){if(!elem.getElementsByTagName){return}var e=elem.getElementsByTagName(settings.name);if(e.length){data=$.trim(e[0].innerHTML)}}else{if(elem.getAttribute!=undefined){var attr=elem.getAttribute(settings.name);if(attr){data=attr}}}}if(data.indexOf("{")<0){data="{"+data+"}"}data=eval("("+data+")");$.data(elem,settings.single,data);return data}}});$.fn.metadata=function(opts){return $.metadata.get(this[0],opts)}})(jQuery);



/*
 ### jQuery Star Rating Plugin v3.14 - 2012-01-26 ###
 * Home: http://www.fyneworks.com/jquery/star-rating/
 * Code: http://code.google.com/p/jquery-star-rating-plugin/
 *
	* Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 ###
*/

/*# AVOID COLLISIONS #*/
;
if (window.jQuery)(function ($) {
    /*# AVOID COLLISIONS #*/
    if ($.browser.msie) {
        try {
            document.execCommand("BackgroundImageCache", false, true)
        } catch (e) {}
    }
    $.fn.rating = function (b) {
        if (this.length == 0) {
            return this
        }
        if (typeof arguments[0] == "string") {
            if (this.length > 1) {
                var a = arguments;
                return this.each(function () {
                    $.fn.rating.apply($(this), a)
                })
            }
            $.fn.rating[arguments[0]].apply(this, $.makeArray(arguments).slice(1) || []);
            return this
        }
        var b = $.extend({}, $.fn.rating.options, b || {});
        $.fn.rating.calls++;
        this.not(".star-rating-applied").addClass("star-rating-applied").each(function () {
            var f, k = $(this);
            var c = (this.name || "unnamed-rating").replace(/\[|\]/g, "_").replace(/^\_+|\_+$/g, "");
            var d = $(this.form || document.body);
            var j = d.data("rating");
            if (!j || j.call != $.fn.rating.calls) {
                j = {
                    count: 0,
                    call: $.fn.rating.calls
                }
            }
            var m = j[c];
            if (m) {
                f = m.data("rating")
            }
            if (m && f) {
                f.count++
            } else {
                f = $.extend({}, b || {}, ($.metadata ? k.metadata() : ($.meta ? k.data() : null)) || {}, {
                    count: 0,
                    stars: [],
                    inputs: []
                });
                f.serial = j.count++;
                m = $('<span class="star-rating-control"/>');
                k.before(m);
                m.addClass("rating-to-be-drawn");
                if (k.attr("disabled") || k.hasClass("disabled")) {
                    f.readOnly = true
                }
                if (k.hasClass("required")) {
                    f.required = true
                }
                m.append(f.cancel = $('<div class="rating-cancel"><a title="' + f.cancel + '">' + f.cancelValue + "</a></div>").mouseover(function () {
                    $(this).rating("drain");
                    $(this).addClass("star-rating-hover")
                }).mouseout(function () {
                    $(this).rating("draw");
                    $(this).removeClass("star-rating-hover")
                }).click(function () {
                    $(this).rating("select")
                }).data("rating", f))
            }
            var i = $('<div class="star-rating rater-' + f.serial + '"><a title="' + (this.title || this.value) + '">' + this.value + "</a></div>");
            m.append(i);
            if (this.id) {
                i.attr("id", this.id)
            }
            if (this.className) {
                i.addClass(this.className)
            }
            if (f.half) {
                f.split = 2
            }
            if (typeof f.split == "number" && f.split > 0) {
                var h = ($.fn.width ? i.width() : 0) || f.starWidth;
                var g = (f.count % f.split),
                    l = Math.floor(h / f.split);
                i.width(l).find("a").css({
                    "margin-left": "-" + (g * l) + "px"
                })
            }
            if (f.readOnly) {
                i.addClass("star-rating-readonly")
            } else {
                i.addClass("star-rating-live").mouseover(function () {
                    $(this).rating("fill");
                    $(this).rating("focus")
                }).mouseout(function () {
                    $(this).rating("draw");
                    $(this).rating("blur")
                }).click(function () {
                    $(this).rating("select")
                })
            } if (this.checked) {
                f.current = i
            }
            if (this.nodeName == "A") {
                if ($(this).hasClass("selected")) {
                    f.current = i
                }
            }
            k.hide();
            k.each(function () {
                $(this).next("label").hide()
            });
            k.change(function () {
                $(this).rating("select")
            });
            i.data("rating.input", k.data("rating.star", i));
            f.stars[f.stars.length] = i[0];
            f.inputs[f.inputs.length] = k[0];
            f.rater = j[c] = m;
            f.context = d;
            k.data("rating", f);
            m.data("rating", f);
            i.data("rating", f);
            d.data("rating", j)
        });
        $(".rating-to-be-drawn").rating("draw").removeClass("rating-to-be-drawn");
        return this
    };
    $.extend($.fn.rating, {
        calls: 0,
        focus: function () {
            var b = this.data("rating");
            if (!b) {
                return this
            }
            if (!b.focus) {
                return this
            }
            var a = $(this).data("rating.input") || $(this.tagName == "INPUT" ? this : null);
            if (b.focus) {
                b.focus.apply(a[0], [a.val(), $("a", a.data("rating.star"))[0]])
            }
        },
        blur: function () {
            var b = this.data("rating");
            if (!b) {
                return this
            }
            if (!b.blur) {
                return this
            }
            var a = $(this).data("rating.input") || $(this.tagName == "INPUT" ? this : null);
            if (b.blur) {
                b.blur.apply(a[0], [a.val(), $("a", a.data("rating.star"))[0]])
            }
        },
        fill: function () {
            var a = this.data("rating");
            if (!a) {
                return this
            }
            if (a.readOnly) {
                return
            }
            this.rating("drain");
            this.prevAll().andSelf().filter(".rater-" + a.serial).addClass("star-rating-hover")
        },
        drain: function () {
            var a = this.data("rating");
            if (!a) {
                return this
            }
            if (a.readOnly) {
                return
            }
            a.rater.children().filter(".rater-" + a.serial).removeClass("star-rating-on").removeClass("star-rating-hover")
        },
        draw: function () {
            var a = this.data("rating");
            if (!a) {
                return this
            }
            this.rating("drain");
            if (a.current) {
                a.current.data("rating.input").attr("checked", "checked");
                a.current.prevAll().andSelf().filter(".rater-" + a.serial).addClass("star-rating-on")
            } else {
                $(a.inputs).removeAttr("checked")
            }
            a.cancel[a.readOnly || a.required ? "hide" : "show"]();
            this.siblings()[a.readOnly ? "addClass" : "removeClass"]("star-rating-readonly")
        },
        select: function (b, d) {
            var c = this.data("rating");
            if (!c) {
                return this
            }
            if (c.readOnly) {
                return
            }
            c.current = null;
            if (typeof b != "undefined") {
                if (typeof b == "number") {
                    return $(c.stars[b]).rating("select", undefined, d)
                }
                if (typeof b == "string") {
                    $.each(c.stars, function () {
                        if ($(this).data("rating.input").val() == b) {
                            $(this).rating("select", undefined, d)
                        }
                    })
                }
            } else {
                c.current = this[0].tagName == "INPUT" ? this.data("rating.star") : (this.is(".rater-" + c.serial) ? this : null)
            }
            this.data("rating", c);
            this.rating("draw");
            var a = $(c.current ? c.current.data("rating.input") : null);
            if ((d || d == undefined) && c.callback) {
                c.callback.apply(a[0], [a.val(), $("a", c.current)[0]])
            }
        },
        readOnly: function (a, b) {
            var c = this.data("rating");
            if (!c) {
                return this
            }
            c.readOnly = a || a == undefined ? true : false;
            if (b) {
                $(c.inputs).attr("disabled", "disabled")
            } else {
                $(c.inputs).removeAttr("disabled")
            }
            this.data("rating", c);
            this.rating("draw")
        },
        disable: function () {
            this.rating("readOnly", true, true)
        },
        enable: function () {
            this.rating("readOnly", false, false)
        }
    });
    $.fn.rating.options = {
        cancel: "Cancel Rating",
        cancelValue: "",
        split: 0,
        starWidth: 16
    };
    $(function () {
        $("input[type=radio].star").rating()
    });
    /*# AVOID COLLISIONS #*/
})(jQuery);
/*# AVOID COLLISIONS #*/




 /*
 * jQuery UI selectmenu dev version
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI
 * https://github.com/fnagel/jquery-ui/wiki/Selectmenu
 */
(function(a){a.widget("ui.selectmenu",{getter:"value",version:"1.9",eventPrefix:"selectmenu",options:{transferClasses:true,typeAhead:1000,style:"dropdown",positionOptions:{my:"left top",at:"left bottom",offset:null},width:null,menuWidth:null,handleWidth:26,maxHeight:null,icons:null,format:null,escapeHtml:false,bgImage:function(){},wrapperElement:"<div />"},_create:function(){var b=this,e=this.options;var d=(this.element.attr("id")||"ui-selectmenu-"+Math.random().toString(16).slice(2,10)).replace(":","\\:");this.ids=[d,d+"-button",d+"-menu"];this._safemouseup=true;this.isOpen=false;this.newelement=a("<a />",{"class":this.widgetBaseClass+" ui-widget ui-state-default ui-corner-all",id:this.ids[1],role:"button",href:"#nogo",tabindex:this.element.attr("disabled")?1:0,"aria-haspopup":true,"aria-owns":this.ids[2]});this.newelementWrap=a(e.wrapperElement).append(this.newelement).insertAfter(this.element);var c=this.element.attr("tabindex");if(c){this.newelement.attr("tabindex",c)}this.newelement.data("selectelement",this.element);this.selectmenuIcon=a('<span class="'+this.widgetBaseClass+'-icon ui-icon"></span>').prependTo(this.newelement);this.newelement.prepend('<span class="'+b.widgetBaseClass+'-status" />');this.element.bind({"click.selectmenu":function(f){b.newelement.focus();f.preventDefault()}});this.newelement.bind("mousedown.selectmenu",function(f){b._toggle(f,true);if(e.style=="popup"){b._safemouseup=false;setTimeout(function(){b._safemouseup=true},300)}return false}).bind("click.selectmenu",function(){return false}).bind("keydown.selectmenu",function(g){var f=false;switch(g.keyCode){case a.ui.keyCode.ENTER:f=true;break;case a.ui.keyCode.SPACE:b._toggle(g);break;case a.ui.keyCode.UP:if(g.altKey){b.open(g)}else{b._moveSelection(-1)}break;case a.ui.keyCode.DOWN:if(g.altKey){b.open(g)}else{b._moveSelection(1)}break;case a.ui.keyCode.LEFT:b._moveSelection(-1);break;case a.ui.keyCode.RIGHT:b._moveSelection(1);break;case a.ui.keyCode.TAB:f=true;break;case a.ui.keyCode.PAGE_UP:case a.ui.keyCode.HOME:b.index(0);break;case a.ui.keyCode.PAGE_DOWN:case a.ui.keyCode.END:b.index(b._optionLis.length);break;default:f=true}return f}).bind("keypress.selectmenu",function(f){if(f.which>0){b._typeAhead(f.which,"mouseup")}return true}).bind("mouseover.selectmenu",function(){if(!e.disabled){a(this).addClass("ui-state-hover")}}).bind("mouseout.selectmenu",function(){if(!e.disabled){a(this).removeClass("ui-state-hover")}}).bind("focus.selectmenu",function(){if(!e.disabled){a(this).addClass("ui-state-focus")}}).bind("blur.selectmenu",function(){if(!e.disabled){a(this).removeClass("ui-state-focus")}});a(document).bind("mousedown.selectmenu-"+this.ids[0],function(f){if(b.isOpen){b.close(f)}});this.element.bind("click.selectmenu",function(){b._refreshValue()}).bind("focus.selectmenu",function(){if(b.newelement){b.newelement[0].focus()}});if(!e.width){e.width=this.element.outerWidth()}this.newelement.width(e.width);this.element.hide();this.list=a("<ul />",{"class":"ui-widget ui-widget-content","aria-hidden":true,role:"listbox","aria-labelledby":this.ids[1],id:this.ids[2]});this.listWrap=a(e.wrapperElement).addClass(b.widgetBaseClass+"-menu").append(this.list).appendTo("body");this.list.bind("keydown.selectmenu",function(g){var f=false;switch(g.keyCode){case a.ui.keyCode.UP:if(g.altKey){b.close(g,true)}else{b._moveFocus(-1)}break;case a.ui.keyCode.DOWN:if(g.altKey){b.close(g,true)}else{b._moveFocus(1)}break;case a.ui.keyCode.LEFT:b._moveFocus(-1);break;case a.ui.keyCode.RIGHT:b._moveFocus(1);break;case a.ui.keyCode.HOME:b._moveFocus(":first");break;case a.ui.keyCode.PAGE_UP:b._scrollPage("up");break;case a.ui.keyCode.PAGE_DOWN:b._scrollPage("down");break;case a.ui.keyCode.END:b._moveFocus(":last");break;case a.ui.keyCode.ENTER:case a.ui.keyCode.SPACE:b.close(g,true);a(g.target).parents("li:eq(0)").trigger("mouseup");break;case a.ui.keyCode.TAB:f=true;b.close(g,true);a(g.target).parents("li:eq(0)").trigger("mouseup");break;case a.ui.keyCode.ESCAPE:b.close(g,true);break;default:f=true}return f}).bind("keypress.selectmenu",function(f){if(f.which>0){b._typeAhead(f.which,"focus")}return true}).bind("mousedown.selectmenu mouseup.selectmenu",function(){return false});a(window).bind("resize.selectmenu-"+this.ids[0],a.proxy(b.close,this))},_init:function(){var s=this,e=this.options;var b=[];this.element.find("option").each(function(){var i=a(this);b.push({value:i.attr("value"),text:s._formatText(i.text()),selected:i.attr("selected"),disabled:i.attr("disabled"),classes:i.attr("class"),typeahead:i.attr("typeahead"),parentOptGroup:i.parent("optgroup"),bgImage:e.bgImage.call(i)})});var m=(s.options.style=="popup")?" ui-state-active":"";this.list.html("");if(b.length){for(var k=0;k<b.length;k++){var f={role:"presentation"};if(b[k].disabled){f["class"]=this.namespace+"-state-disabled"}var u={html:b[k].text,href:"#nogo",tabindex:-1,role:"option","aria-selected":false};if(b[k].disabled){u["aria-disabled"]=b[k].disabled}if(b[k].typeahead){u.typeahead=b[k].typeahead}var r=a("<a/>",u);var d=a("<li/>",f).append(r).data("index",k).addClass(b[k].classes).data("optionClasses",b[k].classes||"").bind("mouseup.selectmenu",function(i){if(s._safemouseup&&!s._disabled(i.currentTarget)&&!s._disabled(a(i.currentTarget).parents("ul>li."+s.widgetBaseClass+"-group "))){var j=a(this).data("index")!=s._selectedIndex();s.index(a(this).data("index"));s.select(i);if(j){s.change(i)}s.close(i,true)}return false}).bind("click.selectmenu",function(){return false}).bind("mouseover.selectmenu focus.selectmenu",function(i){if(!a(i.currentTarget).hasClass(s.namespace+"-state-disabled")&&!a(i.currentTarget).parent("ul").parent("li").hasClass(s.namespace+"-state-disabled")){s._selectedOptionLi().addClass(m);s._focusedOptionLi().removeClass(s.widgetBaseClass+"-item-focus ui-state-hover");a(this).removeClass("ui-state-active").addClass(s.widgetBaseClass+"-item-focus ui-state-hover")}}).bind("mouseout.selectmenu blur.selectmenu",function(){if(a(this).is(s._selectedOptionLi().selector)){a(this).addClass(m)}a(this).removeClass(s.widgetBaseClass+"-item-focus ui-state-hover")});if(b[k].parentOptGroup.length){var l=s.widgetBaseClass+"-group-"+this.element.find("optgroup").index(b[k].parentOptGroup);if(this.list.find("li."+l).length){this.list.find("li."+l+":last ul").append(d)}else{a(' <li role="presentation" class="'+s.widgetBaseClass+"-group "+l+(b[k].parentOptGroup.attr("disabled")?" "+this.namespace+'-state-disabled" aria-disabled="true"':'"')+'><span class="'+s.widgetBaseClass+'-group-label">'+b[k].parentOptGroup.attr("label")+"</span><ul></ul></li> ").appendTo(this.list).find("ul").append(d)}}else{d.appendTo(this.list)}if(e.icons){for(var h in e.icons){if(d.is(e.icons[h].find)){d.data("optionClasses",b[k].classes+" "+s.widgetBaseClass+"-hasIcon").addClass(s.widgetBaseClass+"-hasIcon");var p=e.icons[h].icon||"";d.find("a:eq(0)").prepend('<span class="'+s.widgetBaseClass+"-item-icon ui-icon "+p+'"></span>');if(b[k].bgImage){d.find("span").css("background-image",b[k].bgImage)}}}}}}else{a('<li role="presentation"><a href="#nogo" tabindex="-1" role="option"></a></li>').appendTo(this.list)}var c=(e.style=="dropdown");this.newelement.toggleClass(s.widgetBaseClass+"-dropdown",c).toggleClass(s.widgetBaseClass+"-popup",!c);this.list.toggleClass(s.widgetBaseClass+"-menu-dropdown ui-corner-bottom",c).toggleClass(s.widgetBaseClass+"-menu-popup ui-corner-all",!c).find("li:first").toggleClass("ui-corner-top",!c).end().find("li:last").addClass("ui-corner-bottom");this.selectmenuIcon.toggleClass("ui-icon-triangle-1-s",c).toggleClass("ui-icon-triangle-2-n-s",!c);if(e.transferClasses){var t=this.element.attr("class")||"";this.newelement.add(this.list).addClass(t)}if(e.style=="dropdown"){this.list.width(e.menuWidth?e.menuWidth:e.width)}else{this.list.width(e.menuWidth?e.menuWidth:e.width-e.handleWidth)}this.list.css("height","auto");var n=this.listWrap.height();var g=a(window).height();var q=e.maxHeight?Math.min(e.maxHeight,g):g/3;if(n>q){this.list.height(q)}this._optionLis=this.list.find("li:not(."+s.widgetBaseClass+"-group)");if(this.element.attr("disabled")){this.disable()}else{this.enable()}this.index(this._selectedIndex());this._selectedOptionLi().addClass(this.widgetBaseClass+"-item-focus");window.setTimeout(function(){s._refreshPosition()},200)},destroy:function(){this.element.removeData(this.widgetName).removeClass(this.widgetBaseClass+"-disabled "+this.namespace+"-state-disabled").removeAttr("aria-disabled").unbind(".selectmenu");a(window).unbind(".selectmenu-"+this.ids[0]);a(document).unbind(".selectmenu-"+this.ids[0]);this.newelementWrap.remove();this.listWrap.remove();this.element.unbind(".selectmenu").show();a.Widget.prototype.destroy.apply(this,arguments)},_typeAhead:function(e,f){var l=this,k=String.fromCharCode(e).toLowerCase(),d=null,j=null;if(l._typeAhead_timer){window.clearTimeout(l._typeAhead_timer);l._typeAhead_timer=undefined}l._typeAhead_chars=(l._typeAhead_chars===undefined?"":l._typeAhead_chars).concat(k);if(l._typeAhead_chars.length<2||(l._typeAhead_chars.substr(-2,1)===k&&l._typeAhead_cycling)){l._typeAhead_cycling=true;d=k}else{l._typeAhead_cycling=false;d=l._typeAhead_chars}var g=(f!=="focus"?this._selectedOptionLi().data("index"):this._focusedOptionLi().data("index"))||0;for(var h=0;h<this._optionLis.length;h++){var b=this._optionLis.eq(h).text().substr(0,d.length).toLowerCase();if(b===d){if(l._typeAhead_cycling){if(j===null){j=h}if(h>g){j=h;break}}else{j=h}}}if(j!==null){this._optionLis.eq(j).find("a").trigger(f)}l._typeAhead_timer=window.setTimeout(function(){l._typeAhead_timer=undefined;l._typeAhead_chars=undefined;l._typeAhead_cycling=undefined},l.options.typeAhead)},_uiHash:function(){var b=this.index();return{index:b,option:a("option",this.element).get(b),value:this.element[0].value}},open:function(e){var b=this,f=this.options;if(b.newelement.attr("aria-disabled")!="true"){b._closeOthers(e);b.newelement.addClass("ui-state-active");b.listWrap.appendTo(f.appendTo);b.list.attr("aria-hidden",false);b.listWrap.addClass(b.widgetBaseClass+"-open");var c=this._selectedOptionLi();if(f.style=="dropdown"){b.newelement.removeClass("ui-corner-all").addClass("ui-corner-top")}else{this.list.css("left",-5000).scrollTop(this.list.scrollTop()+c.position().top-this.list.outerHeight()/2+c.outerHeight()/2).css("left","auto")}b._refreshPosition();var d=c.find("a");if(d.length){d[0].focus()}b.isOpen=true;b._trigger("open",e,b._uiHash())}},close:function(c,b){if(this.newelement.is(".ui-state-active")){this.newelement.removeClass("ui-state-active");this.listWrap.removeClass(this.widgetBaseClass+"-open");this.list.attr("aria-hidden",true);if(this.options.style=="dropdown"){this.newelement.removeClass("ui-corner-top").addClass("ui-corner-all")}if(b){this.newelement.focus()}this.isOpen=false;this._trigger("close",c,this._uiHash())}},change:function(b){this.element.trigger("change");this._trigger("change",b,this._uiHash())},select:function(b){if(this._disabled(b.currentTarget)){return false}this._trigger("select",b,this._uiHash())},_closeOthers:function(b){a("."+this.widgetBaseClass+".ui-state-active").not(this.newelement).each(function(){a(this).data("selectelement").selectmenu("close",b)});a("."+this.widgetBaseClass+".ui-state-hover").trigger("mouseout")},_toggle:function(c,b){if(this.isOpen){this.close(c,b)}else{this.open(c)}},_formatText:function(b){if(this.options.format){b=this.options.format(b)}else{if(this.options.escapeHtml){b=a("<div />").text(b).html()}}return b},_selectedIndex:function(){return this.element[0].selectedIndex},_selectedOptionLi:function(){return this._optionLis.eq(this._selectedIndex())},_focusedOptionLi:function(){return this.list.find("."+this.widgetBaseClass+"-item-focus")},_moveSelection:function(e,b){if(!this.options.disabled){var d=parseInt(this._selectedOptionLi().data("index")||0,10);var c=d+e;if(c<0){c=0}if(c>this._optionLis.size()-1){c=this._optionLis.size()-1}if(c===b){return false}if(this._optionLis.eq(c).hasClass(this.namespace+"-state-disabled")){(e>0)?++e:--e;this._moveSelection(e,c)}else{this._optionLis.eq(c).trigger("mouseover").trigger("mouseup")}}},_moveFocus:function(f,b){if(!isNaN(f)){var e=parseInt(this._focusedOptionLi().data("index")||0,10);var d=e+f}else{var d=parseInt(this._optionLis.filter(f).data("index"),10)}if(d<0){d=0}if(d>this._optionLis.size()-1){d=this._optionLis.size()-1}if(d===b){return false}var c=this.widgetBaseClass+"-item-"+Math.round(Math.random()*1000);this._focusedOptionLi().find("a:eq(0)").attr("id","");if(this._optionLis.eq(d).hasClass(this.namespace+"-state-disabled")){(f>0)?++f:--f;this._moveFocus(f,d)}else{this._optionLis.eq(d).find("a:eq(0)").attr("id",c).focus()}this.list.attr("aria-activedescendant",c)},_scrollPage:function(c){var b=Math.floor(this.list.outerHeight()/this._optionLis.first().outerHeight());b=(c=="up"?-b:b);this._moveFocus(b)},_setOption:function(b,c){this.options[b]=c;if(b=="disabled"){if(c){this.close()}this.element.add(this.newelement).add(this.list)[c?"addClass":"removeClass"](this.widgetBaseClass+"-disabled "+this.namespace+"-state-disabled").attr("aria-disabled",c)}},disable:function(b,c){if(typeof(b)=="undefined"){this._setOption("disabled",true)}else{if(c=="optgroup"){this._disableOptgroup(b)}else{this._disableOption(b)}}},enable:function(b,c){if(typeof(b)=="undefined"){this._setOption("disabled",false)}else{if(c=="optgroup"){this._enableOptgroup(b)}else{this._enableOption(b)}}},_disabled:function(b){return a(b).hasClass(this.namespace+"-state-disabled")},_disableOption:function(b){var c=this._optionLis.eq(b);if(c){c.addClass(this.namespace+"-state-disabled").find("a").attr("aria-disabled",true);this.element.find("option").eq(b).attr("disabled","disabled")}},_enableOption:function(b){var c=this._optionLis.eq(b);if(c){c.removeClass(this.namespace+"-state-disabled").find("a").attr("aria-disabled",false);this.element.find("option").eq(b).removeAttr("disabled")}},_disableOptgroup:function(c){var b=this.list.find("li."+this.widgetBaseClass+"-group-"+c);if(b){b.addClass(this.namespace+"-state-disabled").attr("aria-disabled",true);this.element.find("optgroup").eq(c).attr("disabled","disabled")}},_enableOptgroup:function(c){var b=this.list.find("li."+this.widgetBaseClass+"-group-"+c);if(b){b.removeClass(this.namespace+"-state-disabled").attr("aria-disabled",false);this.element.find("optgroup").eq(c).removeAttr("disabled")}},index:function(b){if(arguments.length){if(!this._disabled(a(this._optionLis[b]))){this.element[0].selectedIndex=b;this._refreshValue()}else{return false}}else{return this._selectedIndex()}},value:function(b){if(arguments.length){this.element[0].value=b;this._refreshValue()}else{return this.element[0].value}},_refreshValue:function(){var d=(this.options.style=="popup")?" ui-state-active":"";var c=this.widgetBaseClass+"-item-"+Math.round(Math.random()*1000);this.list.find("."+this.widgetBaseClass+"-item-selected").removeClass(this.widgetBaseClass+"-item-selected"+d).find("a").attr("aria-selected","false").attr("id","");this._selectedOptionLi().addClass(this.widgetBaseClass+"-item-selected"+d).find("a").attr("aria-selected","true").attr("id",c);var b=(this.newelement.data("optionClasses")?this.newelement.data("optionClasses"):"");var e=(this._selectedOptionLi().data("optionClasses")?this._selectedOptionLi().data("optionClasses"):"");this.newelement.removeClass(b).data("optionClasses",e).addClass(e).find("."+this.widgetBaseClass+"-status").html(this._selectedOptionLi().find("a:eq(0)").html());this.list.attr("aria-activedescendant",c)},_refreshPosition:function(){var d=this.options;if(d.style=="popup"&&!d.positionOptions.offset){var c=this._selectedOptionLi();var b="0 "+(this.list.offset().top-c.offset().top-(this.newelement.outerHeight()+c.outerHeight())/2)}this.listWrap.position({of:d.positionOptions.of||this.newelement,my:d.positionOptions.my,at:d.positionOptions.at,offset:d.positionOptions.offset||b,collision:d.positionOptions.collision||d.style=="popup"?"fit":"flip"})}})})(jQuery);

/*!
 * jCarousel - Riding carousels with jQuery
 *   http://sorgalla.com/jcarousel/
 *
 * Copyright (c) 2006 Jan Sorgalla (http://sorgalla.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Built on top of the jQuery library
 *   http://jquery.com
 *
 * Inspired by the "Carousel Component" by Bill Scott
 *   http://billwscott.com/carousel/
 */

(function(g){var q={vertical:!1,rtl:!1,start:1,offset:1,size:null,scroll:3,visible:null,animation:"normal",easing:"swing",auto:0,wrap:null,initCallback:null,setupCallback:null,reloadCallback:null,itemLoadCallback:null,itemFirstInCallback:null,itemFirstOutCallback:null,itemLastInCallback:null,itemLastOutCallback:null,itemVisibleInCallback:null,itemVisibleOutCallback:null,animationStepCallback:null,buttonNextHTML:"<div></div>",buttonPrevHTML:"<div></div>",buttonNextEvent:"click",buttonPrevEvent:"click", buttonNextCallback:null,buttonPrevCallback:null,itemFallbackDimension:null},m=!1;g(window).bind("load.jcarousel",function(){m=!0});g.jcarousel=function(a,c){this.options=g.extend({},q,c||{});this.autoStopped=this.locked=!1;this.buttonPrevState=this.buttonNextState=this.buttonPrev=this.buttonNext=this.list=this.clip=this.container=null;if(!c||c.rtl===void 0)this.options.rtl=(g(a).attr("dir")||g("html").attr("dir")||"").toLowerCase()=="rtl";this.wh=!this.options.vertical?"width":"height";this.lt=!this.options.vertical? this.options.rtl?"right":"left":"top";for(var b="",d=a.className.split(" "),f=0;f<d.length;f++)if(d[f].indexOf("jcarousel-skin")!=-1){g(a).removeClass(d[f]);b=d[f];break}a.nodeName.toUpperCase()=="UL"||a.nodeName.toUpperCase()=="OL"?(this.list=g(a),this.clip=this.list.parents(".jcarousel-clip"),this.container=this.list.parents(".jcarousel-container")):(this.container=g(a),this.list=this.container.find("ul,ol").eq(0),this.clip=this.container.find(".jcarousel-clip"));if(this.clip.size()===0)this.clip= this.list.wrap("<div></div>").parent();if(this.container.size()===0)this.container=this.clip.wrap("<div></div>").parent();b!==""&&this.container.parent()[0].className.indexOf("jcarousel-skin")==-1&&this.container.wrap('<div class=" '+b+'"></div>');this.buttonPrev=g(".jcarousel-prev",this.container);if(this.buttonPrev.size()===0&&this.options.buttonPrevHTML!==null)this.buttonPrev=g(this.options.buttonPrevHTML).appendTo(this.container);this.buttonPrev.addClass(this.className("jcarousel-prev"));this.buttonNext= g(".jcarousel-next",this.container);if(this.buttonNext.size()===0&&this.options.buttonNextHTML!==null)this.buttonNext=g(this.options.buttonNextHTML).appendTo(this.container);this.buttonNext.addClass(this.className("jcarousel-next"));this.clip.addClass(this.className("jcarousel-clip")).css({position:"relative"});this.list.addClass(this.className("jcarousel-list")).css({overflow:"hidden",position:"relative",top:0,margin:0,padding:0}).css(this.options.rtl?"right":"left",0);this.container.addClass(this.className("jcarousel-container")).css({position:"relative"}); !this.options.vertical&&this.options.rtl&&this.container.addClass("jcarousel-direction-rtl").attr("dir","rtl");var j=this.options.visible!==null?Math.ceil(this.clipping()/this.options.visible):null,b=this.list.children("li"),e=this;if(b.size()>0){var h=0,i=this.options.offset;b.each(function(){e.format(this,i++);h+=e.dimension(this,j)});this.list.css(this.wh,h+100+"px");if(!c||c.size===void 0)this.options.size=b.size()}this.container.css("display","block");this.buttonNext.css("display","block");this.buttonPrev.css("display", "block");this.funcNext=function(){e.next()};this.funcPrev=function(){e.prev()};this.funcResize=function(){e.resizeTimer&&clearTimeout(e.resizeTimer);e.resizeTimer=setTimeout(function(){e.reload()},100)};this.options.initCallback!==null&&this.options.initCallback(this,"init");!m&&g.browser.safari?(this.buttons(!1,!1),g(window).bind("load.jcarousel",function(){e.setup()})):this.setup()};var f=g.jcarousel;f.fn=f.prototype={jcarousel:"0.2.8"};f.fn.extend=f.extend=g.extend;f.fn.extend({setup:function(){this.prevLast= this.prevFirst=this.last=this.first=null;this.animating=!1;this.tail=this.resizeTimer=this.timer=null;this.inTail=!1;if(!this.locked){this.list.css(this.lt,this.pos(this.options.offset)+"px");var a=this.pos(this.options.start,!0);this.prevFirst=this.prevLast=null;this.animate(a,!1);g(window).unbind("resize.jcarousel",this.funcResize).bind("resize.jcarousel",this.funcResize);this.options.setupCallback!==null&&this.options.setupCallback(this)}},reset:function(){this.list.empty();this.list.css(this.lt, "0px");this.list.css(this.wh,"10px");this.options.initCallback!==null&&this.options.initCallback(this,"reset");this.setup()},reload:function(){this.tail!==null&&this.inTail&&this.list.css(this.lt,f.intval(this.list.css(this.lt))+this.tail);this.tail=null;this.inTail=!1;this.options.reloadCallback!==null&&this.options.reloadCallback(this);if(this.options.visible!==null){var a=this,c=Math.ceil(this.clipping()/this.options.visible),b=0,d=0;this.list.children("li").each(function(f){b+=a.dimension(this, c);f+1<a.first&&(d=b)});this.list.css(this.wh,b+"px");this.list.css(this.lt,-d+"px")}this.scroll(this.first,!1)},lock:function(){this.locked=!0;this.buttons()},unlock:function(){this.locked=!1;this.buttons()},size:function(a){if(a!==void 0)this.options.size=a,this.locked||this.buttons();return this.options.size},has:function(a,c){if(c===void 0||!c)c=a;if(this.options.size!==null&&c>this.options.size)c=this.options.size;for(var b=a;b<=c;b++){var d=this.get(b);if(!d.length||d.hasClass("jcarousel-item-placeholder"))return!1}return!0}, get:function(a){return g(">.jcarousel-item-"+a,this.list)},add:function(a,c){var b=this.get(a),d=0,p=g(c);if(b.length===0)for(var j,e=f.intval(a),b=this.create(a);;){if(j=this.get(--e),e<=0||j.length){e<=0?this.list.prepend(b):j.after(b);break}}else d=this.dimension(b);p.get(0).nodeName.toUpperCase()=="LI"?(b.replaceWith(p),b=p):b.empty().append(c);this.format(b.removeClass(this.className("jcarousel-item-placeholder")),a);p=this.options.visible!==null?Math.ceil(this.clipping()/this.options.visible): null;d=this.dimension(b,p)-d;a>0&&a<this.first&&this.list.css(this.lt,f.intval(this.list.css(this.lt))-d+"px");this.list.css(this.wh,f.intval(this.list.css(this.wh))+d+"px");return b},remove:function(a){var c=this.get(a);if(c.length&&!(a>=this.first&&a<=this.last)){var b=this.dimension(c);a<this.first&&this.list.css(this.lt,f.intval(this.list.css(this.lt))+b+"px");c.remove();this.list.css(this.wh,f.intval(this.list.css(this.wh))-b+"px")}},next:function(){this.tail!==null&&!this.inTail?this.scrollTail(!1): this.scroll((this.options.wrap=="both"||this.options.wrap=="last")&&this.options.size!==null&&this.last==this.options.size?1:this.first+this.options.scroll)},prev:function(){this.tail!==null&&this.inTail?this.scrollTail(!0):this.scroll((this.options.wrap=="both"||this.options.wrap=="first")&&this.options.size!==null&&this.first==1?this.options.size:this.first-this.options.scroll)},scrollTail:function(a){if(!this.locked&&!this.animating&&this.tail){this.pauseAuto();var c=f.intval(this.list.css(this.lt)), c=!a?c-this.tail:c+this.tail;this.inTail=!a;this.prevFirst=this.first;this.prevLast=this.last;this.animate(c)}},scroll:function(a,c){!this.locked&&!this.animating&&(this.pauseAuto(),this.animate(this.pos(a),c))},pos:function(a,c){var b=f.intval(this.list.css(this.lt));if(this.locked||this.animating)return b;this.options.wrap!="circular"&&(a=a<1?1:this.options.size&&a>this.options.size?this.options.size:a);for(var d=this.first>a,g=this.options.wrap!="circular"&&this.first<=1?1:this.first,j=d?this.get(g): this.get(this.last),e=d?g:g-1,h=null,i=0,k=!1,l=0;d?--e>=a:++e<a;){h=this.get(e);k=!h.length;if(h.length===0&&(h=this.create(e).addClass(this.className("jcarousel-item-placeholder")),j[d?"before":"after"](h),this.first!==null&&this.options.wrap=="circular"&&this.options.size!==null&&(e<=0||e>this.options.size)))j=this.get(this.index(e)),j.length&&(h=this.add(e,j.clone(!0)));j=h;l=this.dimension(h);k&&(i+=l);if(this.first!==null&&(this.options.wrap=="circular"||e>=1&&(this.options.size===null||e<= this.options.size)))b=d?b+l:b-l}for(var g=this.clipping(),m=[],o=0,n=0,j=this.get(a-1),e=a;++o;){h=this.get(e);k=!h.length;if(h.length===0){h=this.create(e).addClass(this.className("jcarousel-item-placeholder"));if(j.length===0)this.list.prepend(h);else j[d?"before":"after"](h);if(this.first!==null&&this.options.wrap=="circular"&&this.options.size!==null&&(e<=0||e>this.options.size))j=this.get(this.index(e)),j.length&&(h=this.add(e,j.clone(!0)))}j=h;l=this.dimension(h);if(l===0)throw Error("jCarousel: No width/height set for items. This will cause an infinite loop. Aborting..."); this.options.wrap!="circular"&&this.options.size!==null&&e>this.options.size?m.push(h):k&&(i+=l);n+=l;if(n>=g)break;e++}for(h=0;h<m.length;h++)m[h].remove();i>0&&(this.list.css(this.wh,this.dimension(this.list)+i+"px"),d&&(b-=i,this.list.css(this.lt,f.intval(this.list.css(this.lt))-i+"px")));i=a+o-1;if(this.options.wrap!="circular"&&this.options.size&&i>this.options.size)i=this.options.size;if(e>i){o=0;e=i;for(n=0;++o;){h=this.get(e--);if(!h.length)break;n+=this.dimension(h);if(n>=g)break}}e=i-o+ 1;this.options.wrap!="circular"&&e<1&&(e=1);if(this.inTail&&d)b+=this.tail,this.inTail=!1;this.tail=null;if(this.options.wrap!="circular"&&i==this.options.size&&i-o+1>=1&&(d=f.intval(this.get(i).css(!this.options.vertical?"marginRight":"marginBottom")),n-d>g))this.tail=n-g-d;if(c&&a===this.options.size&&this.tail)b-=this.tail,this.inTail=!0;for(;a-- >e;)b+=this.dimension(this.get(a));this.prevFirst=this.first;this.prevLast=this.last;this.first=e;this.last=i;return b},animate:function(a,c){if(!this.locked&& !this.animating){this.animating=!0;var b=this,d=function(){b.animating=!1;a===0&&b.list.css(b.lt,0);!b.autoStopped&&(b.options.wrap=="circular"||b.options.wrap=="both"||b.options.wrap=="last"||b.options.size===null||b.last<b.options.size||b.last==b.options.size&&b.tail!==null&&!b.inTail)&&b.startAuto();b.buttons();b.notify("onAfterAnimation");if(b.options.wrap=="circular"&&b.options.size!==null)for(var c=b.prevFirst;c<=b.prevLast;c++)c!==null&&!(c>=b.first&&c<=b.last)&&(c<1||c>b.options.size)&&b.remove(c)}; this.notify("onBeforeAnimation");if(!this.options.animation||c===!1)this.list.css(this.lt,a+"px"),d();else{var f=!this.options.vertical?this.options.rtl?{right:a}:{left:a}:{top:a},d={duration:this.options.animation,easing:this.options.easing,complete:d};if(g.isFunction(this.options.animationStepCallback))d.step=this.options.animationStepCallback;this.list.animate(f,d)}}},startAuto:function(a){if(a!==void 0)this.options.auto=a;if(this.options.auto===0)return this.stopAuto();if(this.timer===null){this.autoStopped= !1;var c=this;this.timer=window.setTimeout(function(){c.next()},this.options.auto*1E3)}},stopAuto:function(){this.pauseAuto();this.autoStopped=!0},pauseAuto:function(){if(this.timer!==null)window.clearTimeout(this.timer),this.timer=null},buttons:function(a,c){if(a==null&&(a=!this.locked&&this.options.size!==0&&(this.options.wrap&&this.options.wrap!="first"||this.options.size===null||this.last<this.options.size),!this.locked&&(!this.options.wrap||this.options.wrap=="first")&&this.options.size!==null&& this.last>=this.options.size))a=this.tail!==null&&!this.inTail;if(c==null&&(c=!this.locked&&this.options.size!==0&&(this.options.wrap&&this.options.wrap!="last"||this.first>1),!this.locked&&(!this.options.wrap||this.options.wrap=="last")&&this.options.size!==null&&this.first==1))c=this.tail!==null&&this.inTail;var b=this;this.buttonNext.size()>0?(this.buttonNext.unbind(this.options.buttonNextEvent+".jcarousel",this.funcNext),a&&this.buttonNext.bind(this.options.buttonNextEvent+".jcarousel",this.funcNext), this.buttonNext[a?"removeClass":"addClass"](this.className("jcarousel-next-disabled")).attr("disabled",a?!1:!0),this.options.buttonNextCallback!==null&&this.buttonNext.data("jcarouselstate")!=a&&this.buttonNext.each(function(){b.options.buttonNextCallback(b,this,a)}).data("jcarouselstate",a)):this.options.buttonNextCallback!==null&&this.buttonNextState!=a&&this.options.buttonNextCallback(b,null,a);this.buttonPrev.size()>0?(this.buttonPrev.unbind(this.options.buttonPrevEvent+".jcarousel",this.funcPrev), c&&this.buttonPrev.bind(this.options.buttonPrevEvent+".jcarousel",this.funcPrev),this.buttonPrev[c?"removeClass":"addClass"](this.className("jcarousel-prev-disabled")).attr("disabled",c?!1:!0),this.options.buttonPrevCallback!==null&&this.buttonPrev.data("jcarouselstate")!=c&&this.buttonPrev.each(function(){b.options.buttonPrevCallback(b,this,c)}).data("jcarouselstate",c)):this.options.buttonPrevCallback!==null&&this.buttonPrevState!=c&&this.options.buttonPrevCallback(b,null,c);this.buttonNextState= a;this.buttonPrevState=c},notify:function(a){var c=this.prevFirst===null?"init":this.prevFirst<this.first?"next":"prev";this.callback("itemLoadCallback",a,c);this.prevFirst!==this.first&&(this.callback("itemFirstInCallback",a,c,this.first),this.callback("itemFirstOutCallback",a,c,this.prevFirst));this.prevLast!==this.last&&(this.callback("itemLastInCallback",a,c,this.last),this.callback("itemLastOutCallback",a,c,this.prevLast));this.callback("itemVisibleInCallback",a,c,this.first,this.last,this.prevFirst, this.prevLast);this.callback("itemVisibleOutCallback",a,c,this.prevFirst,this.prevLast,this.first,this.last)},callback:function(a,c,b,d,f,j,e){if(!(this.options[a]==null||typeof this.options[a]!="object"&&c!="onAfterAnimation")){var h=typeof this.options[a]=="object"?this.options[a][c]:this.options[a];if(g.isFunction(h)){var i=this;if(d===void 0)h(i,b,c);else if(f===void 0)this.get(d).each(function(){h(i,this,d,b,c)});else for(var a=function(a){i.get(a).each(function(){h(i,this,a,b,c)})},k=d;k<=f;k++)k!== null&&!(k>=j&&k<=e)&&a(k)}}},create:function(a){return this.format("<li></li>",a)},format:function(a,c){for(var a=g(a),b=a.get(0).className.split(" "),d=0;d<b.length;d++)b[d].indexOf("jcarousel-")!=-1&&a.removeClass(b[d]);a.addClass(this.className("jcarousel-item")).addClass(this.className("jcarousel-item-"+c)).css({"float":this.options.rtl?"right":"left","list-style":"none"}).attr("jcarouselindex",c);return a},className:function(a){return a+" "+a+(!this.options.vertical?"-horizontal":"-vertical")}, dimension:function(a,c){var b=g(a);if(c==null)return!this.options.vertical?b.outerWidth(!0)||f.intval(this.options.itemFallbackDimension):b.outerHeight(!0)||f.intval(this.options.itemFallbackDimension);else{var d=!this.options.vertical?c-f.intval(b.css("marginLeft"))-f.intval(b.css("marginRight")):c-f.intval(b.css("marginTop"))-f.intval(b.css("marginBottom"));g(b).css(this.wh,d+"px");return this.dimension(b)}},clipping:function(){return!this.options.vertical?this.clip[0].offsetWidth-f.intval(this.clip.css("borderLeftWidth"))- f.intval(this.clip.css("borderRightWidth")):this.clip[0].offsetHeight-f.intval(this.clip.css("borderTopWidth"))-f.intval(this.clip.css("borderBottomWidth"))},index:function(a,c){if(c==null)c=this.options.size;return Math.round(((a-1)/c-Math.floor((a-1)/c))*c)+1}});f.extend({defaults:function(a){return g.extend(q,a||{})},intval:function(a){a=parseInt(a,10);return isNaN(a)?0:a},windowLoaded:function(){m=!0}});g.fn.jcarousel=function(a){if(typeof a=="string"){var c=g(this).data("jcarousel"),b=Array.prototype.slice.call(arguments, 1);return c[a].apply(c,b)}else return this.each(function(){var b=g(this).data("jcarousel");b?(a&&g.extend(b.options,a),b.reload()):g(this).data("jcarousel",new f(this,a))})}})(jQuery);

