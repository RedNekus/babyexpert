(function (t, y) {
    var x = y.documentElement;
    var O = {};

    function p(ad) {
        if (!(ad in O)) {
            O[ad] = new RegExp("(^|\\s+)" + ad + "(\\s+|$)", "")
        }
        return O[ad]
    }

    function q(ae, ad) {
        return p(ad).test(ae.className)
    }

    function V(ae, ad) {
        if (!q(ae, ad)) {
            ae.className += " " + ad
        }
    }

    function L(ae, ad) {
        ae.className = ae.className.replace(new RegExp("((?:^|\\s+)" + ad + "|" + ad + "(?:\\s+|$))", "g"), "")
    }

    function w(ae, ad) {
        if (q(ae, ad)) {
            L(ae, ad)
        } else {
            V(ae, ad)
        }
    }
    var u, n, v = u = function (ad, ae) {
            var ag = ad[a]("*"),
                af = [],
                ai = 0,
                ah = ag.length;
            for (; ai < ah; ai++) {
                if (q(ag[ai], ae)) {
                    af.push(ag[ai])
                }
            }
            return af
        };
    if (y.querySelectorAll) {
        u = function (ad, ae) {
            return ad.querySelectorAll("." + ae)
        }
    } else {
        if (y.getElementsByClassName) {
            u = function (ad, ae) {
                if (ad.getElementsByClassName) {
                    return ad.getElementsByClassName(ae)
                }
                return v(ad, ae)
            }
        }
    }

    function Q(af, ae) {
        var ad = af;
        do {
            if (q(ad, ae)) {
                return ad
            }
        } while (ad = ad.parentNode);
        return null
    }
    if (t.innerHeight) {
        n = function () {
            return {
                width: t.innerWidth,
                height: t.innerHeight
            }
        }
    } else {
        if (x && x.clientHeight) {
            n = function () {
                return {
                    width: x.clientWidth,
                    height: x.clientHeight
                }
            }
        } else {
            n = function () {
                var ad = y.body;
                return {
                    width: ad.clientWidth,
                    height: ad.clientHeight
                }
            }
        }
    }
    var B = y.addEventListener ? function (af, ad, ae) {
            af.addEventListener(ad, ae, false)
        } : function (af, ad, ae) {
            af.attachEvent("on" + ad, ae)
        }, Y = y.removeEventListener ? function (af, ad, ae) {
            af.removeEventListener(ad, ae, false)
        } : function (af, ad, ae) {
            af.detachEvent("on" + ad, ae)
        };
    var J, o;
    if ("onmouseenter" in x) {
        J = function (ad, ae) {
            B(ad, "mouseenter", ae)
        };
        o = function (ad, ae) {
            B(ad, "mouseleave", ae)
        }
    } else {
        J = function (ad, ae) {
            B(ad, "mouseover", function (af) {
                if (s(af, this)) {
                    ae(af)
                }
            })
        };
        o = function (ad, ae) {
            B(ad, "mouseout", function (af) {
                if (s(af, this)) {
                    ae(af)
                }
            })
        }
    }

    function s(ad, af) {
        var ag = ad.relatedTarget;
        try {
            while (ag && ag !== af) {
                ag = ag.parentNode
            }
            if (ag !== af) {
                return true
            }
        } catch (ae) {}
    }

    function h(ae) {
        try {
            ae.preventDefault()
        } catch (ad) {
            ae.returnValue = false
        }
    }

    function k(ae) {
        try {
            ae.stopPropagation()
        } catch (ad) {
            ae.cancelBubble = true
        }
    }
    var K = (function (ak, am) {
        var ao = {
            boxModel: null
        }, ae = am.defaultView && am.defaultView.getComputedStyle,
            an = /([A-Z])/g,
            af = /-([a-z])/ig,
            ag = function (aq, ar) {
                return ar.toUpperCase()
            }, ai = /^-?\d+(?:px)?$/i,
            al = /^-?\d/,
            ap = function () {};
        if ("getBoundingClientRect" in x) {
            return function (ay) {
                if (!ay || !ay.ownerDocument) {
                    return null
                }
                ad();
                var ax = ay.getBoundingClientRect(),
                    at = ay.ownerDocument,
                    aw = at.body,
                    av = (x.clientTop || aw.clientTop || 0) + (parseInt(aw.style.marginTop, 10) || 0),
                    au = (x.clientLeft || aw.clientLeft || 0) + (parseInt(aw.style.marginLeft, 10) || 0),
                    ar = ax.top + (ak.pageYOffset || ao.boxModel && x.scrollTop || aw.scrollTop) - av,
                    aq = ax.left + (ak.pageXOffset || ao.boxModel && x.scrollLeft || aw.scrollLeft) - au;
                return {
                    top: ar,
                    left: aq
                }
            }
        } else {
            return function (at) {
                if (!at || !at.ownerDocument) {
                    return null
                }
                ah();
                var aq = at.offsetParent,
                    aA = at,
                    ay = at.ownerDocument,
                    aw, au = ay.body,
                    av = ay.defaultView,
                    az = av ? av.getComputedStyle(at, null) : at.currentStyle,
                    ax = at.offsetTop,
                    ar = at.offsetLeft;
                while ((at = at.parentNode) && at !== au && at !== x) {
                    if (ao.supportsFixedPosition && az.position === "fixed") {
                        break
                    }
                    aw = av ? av.getComputedStyle(at, null) : at.currentStyle;
                    ax -= at.scrollTop;
                    ar -= at.scrollLeft;
                    if (at === aq) {
                        ax += at.offsetTop;
                        ar += at.offsetLeft;
                        if (ao.doesNotAddBorder && !(ao.doesAddBorderForTableAndCells && /^t(able|d|h)$/i.test(at.nodeName))) {
                            ax += parseFloat(aw.borderTopWidth, 10) || 0;
                            ar += parseFloat(aw.borderLeftWidth, 10) || 0
                        }
                        aA = aq, aq = at.offsetParent
                    }
                    if (ao.subtractsBorderForOverflowNotVisible && aw.overflow !== "visible") {
                        ax += parseFloat(aw.borderTopWidth, 10) || 0;
                        ar += parseFloat(aw.borderLeftWidth, 10) || 0
                    }
                    az = aw
                }
                if (az.position === "relative" || az.position === "static") {
                    ax += au.offsetTop;
                    ar += au.offsetLeft
                }
                if (ao.supportsFixedPosition && az.position === "fixed") {
                    ax += Math.max(x.scrollTop, au.scrollTop);
                    ar += Math.max(x.scrollLeft, au.scrollLeft)
                }
                return {
                    top: ax,
                    left: ar
                }
            }
        }

        function aj(at, aA, aq) {
            var ax, az = at.style;
            if (!aq && az && az[aA]) {
                ax = az[aA]
            } else {
                if (ae) {
                    aA = aA.replace(an, "-$1").toLowerCase();
                    var aw = at.ownerDocument.defaultView;
                    if (!aw) {
                        return null
                    }
                    var ay = aw.getComputedStyle(at, null);
                    if (ay) {
                        ax = ay.getPropertyValue(aA)
                    }
                } else {
                    if (at.currentStyle) {
                        var au = aA.replace(af, ag);
                        ax = at.currentStyle[aA] || at.currentStyle[au];
                        if (!ai.test(ax) && al.test(ax)) {
                            var ar = az.left,
                                av = at.runtimeStyle.left;
                            at.runtimeStyle.left = at.currentStyle.left;
                            az.left = au === "fontSize" ? "1em" : (ax || 0);
                            ax = az.pixelLeft + "px";
                            az.left = ar;
                            at.runtimeStyle.left = av
                        }
                    }
                }
            }
            return ax
        }

        function ad() {
            var aq = am.createElement("div");
            aq.style.width = aq.style.paddingLeft = "1px";
            am.body.appendChild(aq);
            ao.boxModel = aq.offsetWidth === 2;
            am.body.removeChild(aq).style.display = "none";
            aq = null;
            ad = ap
        }

        function ah() {
            var aw = am.body,
                ax = am.createElement("div"),
                ar, au, at, av, ay = parseFloat(aj(aw, "marginTop", true), 10) || 0,
                aq = "<div style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;'><div></div></div><table style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;' cellpadding='0' cellspacing='0'><tr><td></td></tr></table>";
            ax.style.cssText = "position:absolute;top:0;lefto:0;margin:0;border:0;width:1px;height:1px;visibility:hidden;";
            ax.innerHTML = aq;
            aw.insertBefore(ax, aw.firstChild);
            ar = ax.firstChild;
            au = ar.firstChild;
            av = ar.nextSibling.firstChild.firstChild;
            ao.doesNotAddBorder = (au.offsetTop !== 5);
            ao.doesAddBorderForTableAndCells = (av.offsetTop === 5);
            au.style.position = "fixed", au.style.top = "20px";
            ao.supportsFixedPosition = (au.offsetTop === 20 || au.offsetTop === 15);
            au.style.position = au.style.top = "";
            ar.style.overflow = "hidden", ar.style.position = "relative";
            ao.subtractsBorderForOverflowNotVisible = (au.offsetTop === -5);
            ao.doesNotIncludeMarginInBodyOffset = (aw.offsetTop !== ay);
            aw.removeChild(ax);
            aw = ax = ar = au = at = av = null;
            ad();
            ah = ap
        }
    })(t, y);
    var ac = (function (af, ak) {
        var ah = false,
            ag = false,
            al = [],
            ai;

        function aj() {
            if (!ah) {
                if (!ak.body) {
                    return setTimeout(aj, 13)
                }
                ah = true;
                if (al) {
                    var an, am = 0;
                    while ((an = al[am++])) {
                        an.call(null)
                    }
                    al = null
                }
            }
        }

        function ae() {
            if (ag) {
                return
            }
            ag = true;
            if (ak.readyState === "complete") {
                return aj()
            }
            if (ak.addEventListener) {
                ak.addEventListener("DOMContentLoaded", ai, false);
                af.addEventListener("load", aj, false)
            } else {
                if (ak.attachEvent) {
                    ak.attachEvent("onreadystatechange", ai);
                    af.attachEvent("onload", aj);
                    var am = false;
                    try {
                        am = af.frameElement == null
                    } catch (an) {}
                    if (x.doScroll && am) {
                        ad()
                    }
                }
            }
        }
        if (ak.addEventListener) {
            ai = function () {
                ak.removeEventListener("DOMContentLoaded", ai, false);
                aj()
            }
        } else {
            if (ak.attachEvent) {
                ai = function () {
                    if (ak.readyState === "complete") {
                        ak.detachEvent("onreadystatechange", ai);
                        aj()
                    }
                }
            }
        }

        function ad() {
            if (ah) {
                return
            }
            try {
                x.doScroll("left")
            } catch (am) {
                setTimeout(ad, 1);
                return
            }
            aj()
        }
        return function (am) {
            ae();
            if (ah) {
                am.call(null)
            } else {
                al.push(am)
            }
        }
    })(t, y);

    function M() {
        var ad = (function (ai) {
            ai = ai.toLowerCase();
            var ah = /(webkit)[ \/]([\w.]+)/.exec(ai) || /(opera)(?:.*version)?[ \/]([\w.]+)/.exec(ai) || /(msie) ([\w.]+)/.exec(ai) || !/compatible/.test(ai) && /(mozilla)(?:.*? rv:([\w.]+))?/.exec(ai) || [];
            return {
                browser: ah[1] || "",
                version: ah[2] || "0"
            }
        })(navigator.userAgent);
        var af = ".b-share-popup-wrap{z-index:1073741823;position:absolute;width:500px}.b-share-popup{position:absolute;z-index:1073741823;border:1px solid #888;background:#FFF;color:#000}.b-share-popup-wrap .b-share-popup_down{top:0}.b-share-popup-wrap .b-share-popup_up{bottom:0}.b-share-popup-wrap_state_hidden{position:absolute!important;top:-9999px!important;right:auto!important;bottom:auto!important;left:-9999px!important;visibility:hidden!important}.b-share-popup,x:nth-child(1){border:0;padding:1px!important}@media all and (resolution = 0dpi){.b-share-popup,x:nth-child(1),x:-o-prefocus{padding:0!important;border:1px solid #888}}.b-share-popup__i{display:-moz-inline-box;display:inline-block;padding:5px 0!important;overflow:hidden;vertical-align:top;white-space:nowrap;visibility:visible;background:#FFF;-webkit-box-shadow:0 2px 9px rgba(0,0,0,0.6);-moz-box-shadow:0 2px 9px rgba(0,0,0,0.6);box-shadow:0 2px 9px rgba(0,0,0,0.6)}.b-share-popup__item{font:1em/1.25em Arial,sans-serif;display:block;padding:5px 15px!important;white-space:nowrap;background:#FFF}.b-share-popup__item,a.b-share-popup__item:link,a.b-share-popup__item:visited{text-decoration:none!important;border:0!important}a.b-share-popup__item{cursor:pointer}a.b-share-popup__item .b-share-popup__item__text{display:inline;text-decoration:underline;color:#1a3dc1}a.b-share-popup__item:hover{word-spacing:0}a.b-share-popup__item:hover .b-share-popup__item__text{color:#F00;cursor:pointer}.b-share-popup__icon{display:-moz-inline-box;display:inline-block;margin:-3px 0 0 0;padding:0 5px 0 0!important;vertical-align:middle}.b-share-popup__icon_input{width:21px;height:16px;margin-top:-6px;padding:0!important}.b-share-popup__icon__input{margin-right:0;margin-left:2px;vertical-align:top}.b-share-popup__spacer{display:block;padding-top:10px!important}.b-share-popup__header{font:86%/1em Verdana,sans-serif;display:block;padding:10px 15px 5px 15px!important;color:#999}.b-share-popup__header_first{padding-top:5px!important}.b-share-popup__input{font:86%/1em Verdana,sans-serif;display:block;padding:5px 15px!important;color:#999;text-align:left}.b-share-popup__input__input{font:1em/1em Verdana,sans-serif;display:block;width:10px;margin:5px 0 0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;resize:none;text-align:left;direction:ltr}.b-share-popup_down .b-share-popup_with-link .b-share-popup__input_link{position:absolute;top:5px;right:0;left:0}.b-share-popup_up .b-share-popup_with-link .b-share-popup__input_link{position:absolute;right:0;bottom:5px;left:0}.b-share-popup_down .b-share-popup_with-link{padding-top:55px!important}.b-share-popup_up .b-share-popup_with-link{padding-bottom:55px!important}.b-share-popup_down .b-share-popup_expandable .b-share-popup__main{padding-bottom:25px!important}.b-share-popup_up .b-share-popup_expandable .b-share-popup__main{padding-top:25px!important}.b-share-popup_down .b-share-popup_yandexed{padding-bottom:10px!important}.b-share-popup_up .b-share-popup_yandexed{padding-top:10px!important}.b-share-popup__yandex{position:absolute;right:4px;bottom:2px;font:78.125%/1em Verdana,sans-serif;padding:3px!important;background:transparent}a.b-share-popup__yandex:link,a.b-share-popup__yandex:visited{color:#c6c5c5;text-decoration:none}a.b-share-popup__yandex:link:hover,a.b-share-popup__yandex:visited:hover{color:#F00;text-decoration:underline}.b-share-popup_up .b-share-popup__yandex{top:2px;bottom:auto}.b-share-popup_expandable .b-share-popup__yandex{right:auto;left:4px}.b-share-popup_to-right .b-share-popup_expandable .b-share-popup__yandex{right:4px;left:auto}.b-share-popup__expander .b-share-popup__item{position:absolute;bottom:5px;font:86%/1em Verdana,sans-serif;margin:10px 0 0;padding:5px 10px!important;cursor:pointer;color:#999;background:transparent}.b-share-popup_to-right,.b-share-popup_to-right .b-share-popup__expander{direction:rtl}.b-share-popup_to-right .b-share-popup__expander .b-share-popup__icon{padding:0 0 0 5px!important}.b-share-popup_up .b-share-popup__expander .b-share-popup__item{top:-5px;bottom:auto}.b-share-popup__expander .b-share-popup__item:hover .b-share-popup__item__text{text-decoration:underline}.b-share-popup__expander .b-ico_action_rarr,.b-share-popup_to-right .b-share-popup__expander .b-ico_action_larr,.b-share-popup_full .b-share-popup__expander .b-ico_action_larr,.b-share-popup_to-right .b-share-popup_full .b-share-popup__expander .b-ico_action_rarr,.b-share-popup__expander .b-share-popup__item__text_collapse,.b-share-popup_full .b-share-popup__item__text_expand{display:none}.b-share-popup_to-right .b-share-popup__expander .b-ico_action_rarr,.b-share-popup_full .b-share-popup__item__text_collapse,.b-share-popup_full .b-share-popup__expander .b-ico_action_rarr,.b-share-popup_to-right .b-share-popup_full .b-share-popup__expander .b-ico_action_larr{display:inline}.b-ico_action_rarr,.b-ico_action_larr{width:8px;height:7px;border:0}.b-share-popup__main,.b-share-popup__extra{direction:ltr;vertical-align:bottom;text-align:left}.b-share-popup_down .b-share-popup__main,.b-share-popup_down .b-share-popup__extra{vertical-align:top}.b-share-popup__main{display:-moz-inline-stack;display:inline-block}.b-share-popup__extra{display:none;margin:0 -10px 0 0}.b-share-popup_full .b-share-popup__extra{display:-moz-inline-stack;display:inline-block}.b-share-popup_to-right .b-share-popup__extra{margin:0 0 0 -10px}.b-share-popup__tail{position:absolute;width:21px;height:10px;margin:0 0 0 -11px}.b-share-popup_down .b-share-popup__tail{top:-10px;background:url(http://yandex.st/share/static/b-share-popup_down__tail.gif) 0 0 no-repeat}.b-share-popup_up .b-share-popup__tail{bottom:-10px;background:url(http://yandex.st/share/static/b-share-popup_up__tail.gif) 0 0 no-repeat}.b-share-popup_down .b-share-popup__tail,x:nth-child(1){top:-9px;background-image:url(http://yandex.st/share/static/b-share-popup_down__tail.png)}.b-share-popup_up .b-share-popup__tail,x:nth-child(1){bottom:-9px;background-image:url(http://yandex.st/share/static/b-share-popup_up__tail.png)}@media all and (resolution = 0dpi){.b-share-popup_down .b-share-popup__tail,x:nth-child(1),x:-o-prefocus{top:-10px;background-image:url(http://yandex.st/share/static/b-share-popup_down__tail.gif)}.b-share-popup_up .b-share-popup__tail,x:nth-child(1),x:-o-prefocus{bottom:-10px;background-image:url(http://yandex.st/share/static/b-share-popup_up__tail.gif)}}.b-share-popup .b-share-popup_show_form_mail,.b-share-popup .b-share-popup_show_form_html{padding:0!important}.b-share-popup .b-share-popup_show_form_mail .b-share-popup__main,.b-share-popup .b-share-popup_show_form_html .b-share-popup__main,.b-share-popup .b-share-popup_show_form .b-share-popup__main,.b-share-popup .b-share-popup_show_form_mail .b-share-popup__extra,.b-share-popup .b-share-popup_show_form_html .b-share-popup__extra,.b-share-popup .b-share-popup_show_form .b-share-popup__extra{height:15px;padding:0!important;overflow:hidden;visibility:hidden}.b-share-popup_show_form_mail .b-share-popup__expander,.b-share-popup_show_form_html .b-share-popup__expander,.b-share-popup_show_form .b-share-popup__expander,.b-share-popup_show_form_mail .b-share-popup__input_link,.b-share-popup_show_form_html .b-share-popup__input_link,.b-share-popup_show_form .b-share-popup__input_link{display:none}.b-share-popup__form{position:relative;display:none;overflow:hidden;padding:5px 0 0!important;margin:0 0 -15px;white-space:normal}.b-share-popup_show_form_mail .b-share-popup__form_mail,.b-share-popup_show_form_html .b-share-popup__form_html,.b-share-popup_show_form .b-share-popup__form{display:block}.b-share-popup__form__link{font:86%/1.4545em Verdana,sans-serif;float:left;display:inline;padding:5px!important;margin:0 0 5px 10px;text-decoration:underline;cursor:pointer;color:#1a3dc1}.b-share-popup__form__button{font:86%/1.4545em Verdana,sans-serif;float:left;display:inline;margin:5px 0 0 15px}.b-share-popup__form__close{font:86%/1.4545em Verdana,sans-serif;float:right;display:inline;padding:5px!important;margin:0 10px 5px 0;cursor:pointer;color:#999}a.b-share-popup__form__link:hover,a.b-share-popup__form__close:hover{text-decoration:underline;color:#F00}.b-share-popup_font_fixed .b-share-popup__item{font-size:12.8px}.b-share-popup_font_fixed .b-share-popup__header,.b-share-popup_font_fixed .b-share-popup__input,.b-share-popup_font_fixed .b-share-popup__expander .b-share-popup__item,.b-share-popup_font_fixed .b-share-popup__form__link,.b-share-popup_font_fixed .b-share-popup__form__button,.b-share-popup_font_fixed .b-share-popup__form__close{font-size:11px}.b-share-popup_font_fixed .b-share-popup__yandex{font-size:10px}.b-share-form-button{font:86%/17px Verdana,Arial,sans-serif;display:-moz-inline-box;display:inline-block;position:relative;height:19px;margin:0 3px;padding:0 4px;cursor:default;white-space:nowrap;text-decoration:none!important;color:#000!important;border:none;outline:none;background:url(http://yandex.st/share/static/b-share-form-button.png) 0 -20px repeat-x}.b-share-form-button:link:hover,.b-share-form-button:visited:hover{color:#000!important}.b-share-form-button__before,.b-share-form-button__after{position:absolute;width:3px;height:19px;background:url(http://yandex.st/share/static/b-share-form-button.png)}.b-share-form-button__before{margin-left:-7px}.b-share-form-button__after{margin-left:4px;background-position:-3px 0}.b-share-form-button::-moz-focus-inner{border:none}button.b-share-form-button .b-share-form-button__before,button.b-share-form-button .b-share-form-button__after{margin-top:-1px}@-moz-document url-prefix(){button.b-share-form-button .b-share-form-button__after{margin-top:-2px;margin-left:6px}button.b-share-form-button .b-share-form-button__before{margin-top:-2px;margin-left:-9px}}SPAN.b-share-form-button:hover,.b-share-form-button_state_hover{background-position:0 -60px}SPAN.b-share-form-button:hover .b-share-form-button__before,.b-share-form-button_state_hover .b-share-form-button__before{background-position:0 -40px}SPAN.b-share-form-button:hover .b-share-form-button__after,.b-share-form-button_state_hover .b-share-form-button__after{background-position:-3px -40px}.b-share-form-button_state_pressed,.b-share-form-button_state_pressed .b-share-form-button_share{background-position:0 -100px!important}.b-share-form-button_state_pressed .b-share-form-button__before{background-position:0 -80px!important}.b-share-form-button_state_pressed .b-share-form-button__after{background-position:-3px -80px!important}button.b-share-form-button_state_pressed{overflow:visible}.b-share-form-button_icons{position:relative;padding:0;background-position:0 -20px!important}.b-share-form-button_icons .b-share-form-button__before{left:0;margin-left:-3px;background-position:0 0!important}.b-share-form-button_icons .b-share-form-button__after{z-index:-1;margin-left:0;background-position:-3px 0!important}.b-share-form-button_icons .b-share__handle{padding:2px!important}.b-share-form-button_icons .b-share__handle_more{position:relative;padding-right:6px!important;margin-right:-4px}.b-share-form-button_icons .b-share-icon{opacity:.5;background-image:url(http://yandex.st/share/static/b-share-icon_size_14.png)}.b-share-form-button_icons A.b-share__handle:hover .b-share-icon{opacity:1}.b-share{font:86%/1.4545em Arial,sans-serif;display:-moz-inline-box;display:inline-block;padding:1px 3px 1px 4px!important;vertical-align:middle}.b-share .b-share-form-button{font-size:1em}.b-share__text .b-share-icon{margin:0 5px 0 0;border:none}.b-share__text{margin-right:5px}.b-share__handle{float:left;cursor:pointer;text-align:left;text-decoration:none!important;height:16px;padding:5px 3px 5px 2px!important;cursor:pointer;text-align:left;text-decoration:none!important}.b-share__handle_cursor_default{cursor:default}.b-share__handle .b-share-form-button{margin-top:-2px}.b-share__hr{display:none;float:left;width:1px;height:26px;margin:0 3px 0 2px}a.b-share__handle:hover .b-share__text{text-decoration:underline;color:#F00}.b-share_bordered{padding:0 2px 0 3px!important;border:1px solid #e4e4e4;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px}.b-share_bordered .b-share__hr{display:inline;background:#e4e4e4}.b-share_link{margin:-8px 0}a.b-share_link{margin:0}.b-share_link .b-share__text{text-decoration:underline;color:#1a3dc1}.b-share-form-button_share{padding-left:26px!important;vertical-align:top}.b-share-form-button_share .b-share-form-button__before{margin-left:-29px}.b-share-form-button_share .b-share-form-button__icon{position:absolute;width:20px;height:17px;margin:1px 0 0 -23px;background:url(http://yandex.st/share/static/b-share-form-button_share__icon.png) 0 0 no-repeat}.b-share-pseudo-link{border-bottom:1px dotted;cursor:pointer;text-decoration:none!important}.b-share_font_fixed{font-size:11px}.b-share__handle_more{font-size:9px;margin-top:-1px;color:#7b7b7b}A.b-share__handle_more:hover{color:#000}.b-share__group{float:left}.b-share-icon{float:left;display:inline;overflow:hidden;width:16px;height:16px;padding:0!important;vertical-align:top;border:0;background:url(http://yandex.st/share/static/b-share-icon.png) 0 99px no-repeat}.b-share-icon_vkontakte,.b-share-icon_custom{background-position:0 0}.b-share-icon_yaru,.b-share-icon_yaru_photo,.b-share-icon_yaru_wishlist{background-position:0 -17px}.b-share-icon_lj{background-position:0 -34px}.b-share-icon_twitter{background-position:0 -51px}.b-share-icon_facebook{background-position:0 -68px}.b-share-icon_moimir{background-position:0 -85px}.b-share-icon_friendfeed{background-position:0 -102px}.b-share-icon_mail{background-position:0 -119px}.b-share-icon_html{background-position:0 -136px}.b-share-icon_postcard{background-position:0 -153px}.b-share-icon_odnoklassniki{background-position:0 -170px}.b-share-icon_blogger{background-position:0 -187px}.b-share-icon_greader{background-position:0 -204px}.b-share-icon_delicious{background-position:0 -221px}.b-share-icon_gbuzz{background-position:0 -238px}.b-share-icon_linkedin{background-position:0 -255px}.b-share-icon_myspace{background-position:0 -272px}.b-share-icon_evernote{background-position:0 -289px}.b-share-icon_digg{background-position:0 -306px}.b-share-icon_juick{background-position:0 -324px}.b-share-icon_moikrug{background-position:0 -341px}.b-share-icon_yazakladki{background-position:0 -358px}.b-share-icon_liveinternet{background-position:0 -375px}.b-share-icon_tutby{background-position:0 -392px}.b-share-icon_diary{background-position:0 -409px}.b-share-icon_gplus{background-position:0 -426px}",
            ae = ".b-share-popup__i,.b-share-popup__icon,.b-share-popup__main,.b-share-popup_full .b-share-popup__extra{zoom:1;display:inline}.b-share-popup_to-left{left:0}.b-share-popup_to-right .b-share-popup__expander{direction:ltr}.b-share-popup_to-right .b-share-popup__expander .b-share-popup__item{direction:rtl}.b-share-popup__icon{margin-top:-1px}.b-share-popup__icon_input{margin-top:-4px}.b-share-popup__icon__input{width:14px}.b-share-popup__spacer{overflow:hidden;font:1px/1 Arial}.b-share-popup__input__input{width:100px;direction:ltr}.b-share-popup_full .b-share-popup__input_link .b-share-popup__input__input,.b-share-popup_full .b-share-popup__form .b-share-popup__input__input{width:200px}.b-share-popup-wrap{margin-bottom:25px}.b-share-popup__form{direction:ltr}.b-share-popup__form__button,.b-share-popup__form__close,.b-share-popup__form__link{float:none;display:inline-block}.b-share-popup_full .b-share-popup__form__close{margin-left:70px}* HTML .b-share-popup_up .b-share-popup__tail{overflow:hidden;bottom:-10px}* html .b-share-form-button{text-decoration:none!important}.b-share-form-button{position:relative;overflow:visible}.b-share-form-button__before,.b-share-form-button__after{top:0}button.b-share-form-button .b-share-form-button__before,button.b-share-form-button .b-share-form-button__after{margin-top:auto}.b-share-form-button__icon{top:0}.b-share{zoom:1;display:inline}* HTML .b-share-form-button_share .b-share-form-button__icon{margin-top:-1px;background-image:url(http://yandex.st/share/static/b-share-form-button_share__icon.gif)}";
        var ag = document.createElement("style");
        ag.type = "text/css";
        ag.id = "ya_share_style";
        if (ag.styleSheet) {
            ag.styleSheet.cssText = ad.browser === "msie" && (ad.version < 8 || y.documentMode < 8) ? af + ae : af
        } else {
            ag.appendChild(y.createTextNode(af))
        }
        af = ae = "";
        I.appendChild(ag);
        M = function () {}
    }
    var C = function () {}, e = null,
        a = "getElementsByTagName",
        H = encodeURIComponent,
        I = y[a]("head")[0] || y.body,
        z = "//yandex.st/share",
        D = "http://share.yandex.ru",
        G = {
            blogger: "Blogger",
            diary: "Diary",
            digg: "Digg",
            evernote: "Evernote",
            delicious: "delicious",
            facebook: "Facebook",
            friendfeed: "FriendFeed",
            gbuzz: "Google Buzz",
            gplus: "Google Plus",
            greader: "Google Reader",
            juick: "Juick",
            linkedin: "LinkedIn",
            liveinternet: "LiveInternet",
            lj: "LiveJournal",
            moikrug: "\u041C\u043E\u0439 \u041A\u0440\u0443\u0433",
            moimir: "\u041C\u043E\u0439 \u041C\u0438\u0440",
            myspace: "MySpace",
            odnoklassniki: "\u041E\u0434\u043D\u043E\u043A\u043B\u0430\u0441\u0441\u043D\u0438\u043A\u0438",
            tutby: "Я ТУТ!",
            twitter: "Twitter",
            vkontakte: "\u0412\u041A\u043E\u043D\u0442\u0430\u043A\u0442\u0435",
            yaru: "\u042F.\u0440\u0443",
            yazakladki: "\u042F\u043D\u0434\u0435\u043A\u0441.\u0417\u0430\u043A\u043B\u0430\u0434\u043A\u0438"
        }, Z = {
            be: {
                close: "\u0437\u0430\u043A\u0440\u044B\u0446\u044C",
                "code blog": "\u041A\u043E\u0434 \u0434\u043B\u044F \u0431\u043B\u043E\u0433\u0430",
                link: "C\u043F\u0430\u0441\u044B\u043B\u043A\u0430",
                share: "\u041F\u0430\u0434\u0437\u044F\u043B\u0456\u0446\u0446\u0430",
                "share with friends": "\u041F\u0430\u0434\u0437\u044F\u043B\u0456\u0446\u0446\u0430 \u0437 \u0441\u044F\u0431\u0440\u0430\u043Ci"
            },
            en: {
                close: "close",
                "code blog": "Blog code",
                link: "Link",
                share: "Share",
                "share with friends": "Share with friends"
            },
            kk: {
                close: "\u0436\u0430\u0431\u0443",
                "code blog": "\u0411\u043B\u043E\u0433 \u04AF\u0448\u0456\u043D \u043A\u043E\u0434",
                link: "\u0421\u0456\u043B\u0442\u0435\u043C\u0435",
                share: "\u0411\u04E9\u043B\u0456\u0441\u0443",
                "share with friends": "\u0414\u043e\u0441\u0442\u0430\u0440\u044b\u04a3\u044b\u0437\u0431\u0435\u043d \u0431\u04e9\u043b\u0456\u0441\u0456\u04a3\u0456\u0437"
            },
            ru: {
                close: "\u0437\u0430\u043A\u0440\u044B\u0442\u044C",
                "code blog": "\u041A\u043E\u0434 \u0434\u043B\u044F \u0431\u043B\u043E\u0433\u0430",
                link: "\u0421\u0441\u044B\u043B\u043A\u0430",
                share: "\u041F\u043E\u0434\u0435\u043B\u0438\u0442\u044C\u0441\u044F",
                "share with friends": "\u041F\u043E\u0434\u0435\u043B\u0438\u0442\u0435\u0441\u044C \u0441 \u0434\u0440\u0443\u0437\u044C\u044F\u043C\u0438"
            },
            tr: {
                close: "kapat",
                "code blog": "Blog i\u00E7in eklenti kodu",
                link: "Ba\u011flant\u0131",
                share: "Payla\u015F",
                "share with friends": "Arkada\u015flarla payla\u015f"
            },
            tt: {
                close: "\u044F\u0431\u0443",
                "code blog": "\u0411\u043B\u043E\u0433 \u04E9\u0447\u0435\u043D \u043A\u043E\u0434",
                link: "\u0421\u044b\u043b\u0442\u0430\u043c\u0430",
                share: "\u0411\u04AF\u043B\u0435\u0448\u04AF",
                "share with friends": "\u0414\u0443\u0441\u043B\u0430\u0440\u0433\u044B\u0437 \u0431\u0435\u043B\u04D9\u043D \u0431\u04AF\u043B\u0435\u0448\u0435\u0433\u0435\u0437"
            },
            uk: {
                close: "\u0437\u0430\u043A\u0440\u0438\u0442\u0438",
                "code blog": "\u041A\u043E\u0434 \u0434\u043B\u044F \u0431\u043B\u043E\u0433\u0443",
                link: "\u041F\u043E\u0441\u0438\u043B\u0430\u043D\u043D\u044F",
                share: "\u041F\u043E\u0434\u0456\u043B\u0438\u0442\u0438\u0441\u044F",
                "share with friends": "\u041F\u043E\u0434\u0456\u043B\u0456\u0442\u044C\u0441\u044F \u0437 \u0434\u0440\u0443\u0437\u044F\u043C\u0438"
            }
        };

    function b(aF, aH) {
        M();
        if (!aF || (!aF.element && !aF.elementID)) {
            throw new Error("Invalid parameters")
        }
        var ap = aF.element || aF.elementID;
        if (typeof ap === "string") {
            ap = y.getElementById(ap)
        } else {
            if (!ap.nodeType === 1) {
                ap = null
            }
        } if (!ap || ap.yashareInited) {
            return
        }
        ap.yashareInited = true;
        var al = {};
        if (aF.style) {
            al.type = aF.style.button === false ? "link" : "button";
            al.linkUnderline = aF.style.link;
            al.border = aF.style.border;
            al.linkIcon = aF.style.icon
        }
        var ai, aJ = aF.l10n,
            ae = (aF.link || t.location) + "",
            ax = aF.elementStyle || al || {
                type: "button"
            }, am = ax.quickServices || aF.services || ["|", "yaru", "vkontakte", "odnoklassniki", "twitter", "facebook", "moimir", "lj"],
            aL = aF.title || y.title,
            an = aF.serviceSpecific || aF.override || {}, aE = aF.popupStyle || aF.popup || {}, ak = aE.codeForBlog,
            aB = aE.blocks || ["yaru", "vkontakte", "odnoklassniki", "twitter", "facebook", "moimir", "lj"],
            aD = aE.copyPasteField || aE.input,
            aw = "ya-share-" + Math.random() + "-" + +(new Date()),
            aj = !/(?:moikrug\.ru|ya\.ru|yandex\.by|yandex\.com|yandex\.com\.tr|yandex\.kz|yandex\.net|yandex\.ru|yandex\.ua|yandex-team\.ru)$/.test(location.hostname),
            az, ar = aF.servicesDeclaration;
        if (!aj && ar) {
            for (az in ar) {
                if (ar.hasOwnProperty(az) && !(az in G)) {
                    var aK = ar[az];
                    if (aK && aK.url && aK.title && (aK.icon_16 || aK.icon_from)) {
                        G[az] = ar[az]
                    } else {
                        throw new Error("Invalid new service declaration")
                    }
                }
            }
        }
        if (!ax.type || ("block button link icon none".indexOf(ax.type) === -1)) {
            ax.type = "button"
        }
        var af = ax.afterIconsText;
        if (af) {
            ax.type = "text"
        }
        if (!aJ || !(aJ in Z)) {
            aJ = location.hostname.split(".").splice(-1, 1)[0];
            switch (aJ) {
            case "by":
                aJ = "be";
                break;
            case "kz":
                aJ = "kk";
                break;
            case "ua":
                aJ = "uk";
                break;
            default:
                aJ = "ru"
            }
        }
        aJ = Z[aJ];
        ai = ax.text || aF.text || (aJ.share + (ax.type == "button" ? "\u2026" : ""));
        ai = m(ai);
        if (Object.prototype.toString.call(aB) === "[object Array]") {
            var aG = aB;
            aB = {};
            aB[aJ["share with friends"]] = aG;
            aG = null
        }
        switch (typeof ak) {
        case "string":
            var av = ak;
            ak = {};
            ak[aJ["code blog"]] = av;
            break;
        case "object":
            for (var aI in ak) {
                break
            }
            if (!aI) {
                ak = null
            }
            break;
        default:
            ak = null
        }
        var ao = ' id="' + aw + '" data-hdirection="' + (aE.hDirection || "") + '" data-vdirection="' + (aE.vDirection || "") + '"';
        var aq, ay, aK, au = ['<span class="b-share' + (ax.type == "block" ? " b-share-form-button b-share-form-button_icons" : "") + (ax.border ? " b-share_bordered" : "") + (ax.linkUnderline ? " b-share_link" : "") + '"' + (ax.background ? ' style="background:' + ax.background + '"' : "") + ">" + ((ax.type !== "none" && ax.type !== "text") ? '<a class="b-share__handle"' + ao + ">" : "")];
        if (ax.type == "block") {
            au = ['<div class="b-share"><span class="b-share-form-button b-share-form-button_icons"><i class="b-share-form-button__before"></i>']
        } else {
            if (ax.type == "button") {
                au.push('<span class="b-share-form-button b-share-form-button_share"><i class="b-share-form-button__before"></i><i class="b-share-form-button__icon"></i>' + ai + '<i class="b-share-form-button__after"></i></span>')
            } else {
                if (ax.type === "link" || ax.type === "text") {
                    au.push('<span class="b-share__text' + (ax.type === "text" ? " b-share__handle b-share__handle_cursor_default" : "") + (ax.linkUnderline === "dotted" ? " b-share-pseudo-link" : "") + '">')
                }
                if (((ax.type === "link" || ax.type === "text") && ax.linkIcon) || ax.type === "icon") {
                    au.push('<img alt="" class="b-share-icon" src="' + z + '/static/b-share.png"/>')
                }
                if (ax.type === "link" || ax.type === "text") {
                    au.push(ai + "</span>")
                }
            }
        } if (ax.type !== "none" && ax.type !== "text") {
            au.push("</a>")
        }
        if (ax.group) {
            au.push('<span class="b-share__group">')
        }
        for (aq = 0, ay = am.length; aq < ay; aq++) {
            aK = am[aq];
            au.push(aa(aK, c(aK, "link", ae, an), c(aK, "title", aL, an), c(aK, "description", aF.description || "", an), c(aK, "image", aF.image || "", an)))
        }
        if (ax.group) {
            au.push("</span>")
        }
        if (ax.type == "block") {
            af = "▼";
            au.push('<a class="b-share__handle b-share__handle_more" title="Ещё" ' + ao + '><span class="__b-share__handle_more">' + af + "</span></a>");
            au.push('<i class="b-share-form-button__after"></i>')
        } else {
            if (af) {
                au.push('<a class="b-share__handle b-share_link"' + ao + '><span class="b-share__text b-share-pseudo-link">' + af + "</span></a>")
            }
        }
        au.push("</span>");
        if (ax.type == "block") {
            au.push("</div>")
        }
        ap.innerHTML = au.join("");
        R(ap, aH, af, ax.type === "none");
        if (ax.type !== "none") {
            var aC = ['<div class="b-share-popup-wrap b-share-popup-wrap_state_hidden" id="' + aw + '-popup"><div class="b-share-popup b-share-popup_down b-share-popup_to-right"><div class="b-share-popup__i' + (aD ? " b-share-popup_with-link" : "") + '">'];
            if (ak) {
                aC.push('<div class="b-share-popup__form b-share-popup__form_html">');
                for (var ah in ak) {
                    if (ak.hasOwnProperty(ah)) {
                        aC.push('<label class="b-share-popup__input">' + ah + '<textarea class="b-share-popup__input__input" cols="10" rows="5">' + ak[ah] + "</textarea></label>")
                    }
                }
                aC.push('<a class="b-share-popup__form__close">' + aJ.close + "</a></div>")
            }
            aC.push('<div class="b-share-popup__main ' + (aj ? " b-share-popup_yandexed" : "") + '">');
            if (aD) {
                aC.push('<label class="b-share-popup__input b-share-popup__input_link">' + aJ.link + '<input class="b-share-popup__input__input" readonly="readonly" type="text" value="' + m(ae) + '" /></label>')
            }
            for (var aA in aB) {
                if (aB.hasOwnProperty(aA)) {
                    var at = aB[aA];
                    ay = at.length;
                    if (ay) {
                        if (aA) {
                            aC.push('<div class="b-share-popup__header b-share-popup__header_first">' + aA + "</div>")
                        }
                        for (aq = 0; aq < ay; aq++) {
                            aK = at[aq];
                            aC.push(d(aK, c(aK, "link", ae, an), c(aK, "title", aL, an), c(aK, "description", aF.description, an), c(aK, "image", aF.image || "", an)))
                        }
                    }
                }
            }
            if (ak) {
                aC.push('<div class="b-share-popup__spacer"></div><a class="b-share-popup__item b-share-popup__item_type_code"><span class="b-share-popup__icon"><span class="b-share-icon b-share-icon_html"></span></span><span class="b-share-popup__item__text">' + aJ["code blog"] + "</span></a>")
            }
            if (aj) {
                aC.push('<a href="http://api.yandex.ru/share/" class="b-share-popup__yandex">\u042F\u043D\u0434\u0435\u043A\u0441</a>')
            }
            aC.push("</div>");
            aC.push('</div><div class="b-share-popup__tail"></div></div></div>');
            var ad = y.createElement("div"),
                ag;
            ad.innerHTML = aC.join("");
            ag = ad.firstChild;
            y.body.appendChild(ag);
            ad = null;
            f(ag, aH)
        }
        return [ap, ag]
    }

    function S(ae) {
        var ad = y.createElement("script");
        ad.setAttribute("src", location.protocol + "//clck.yandex.ru/jclck/dtype=stred/pid=52/cid=70685/path=" + ae + "/rnd=" + Math.round(Math.random() * 100000) + "/*" + encodeURIComponent(location.href));
        I.appendChild(ad)
    }

    function c(af, ae, ag, ah) {
        var ad = ag,
            ai = ah[af];
        if (ai && ae in ai) {
            ad = ai[ae]
        }
        return (ae == "description" || ae == "image") ? ad : H(ad)
    }

    function F(ad) {
        return Boolean(G[ad]["title"])
    }

    function ab(ad) {
        var ag = G[ad];
        var af = "";
        var ae = "";
        if (F(ad)) {
            if (ag.icon_from) {
                af += ag.icon_from
            } else {
                af += "custom";
                ae = ' style="background-image:url(' + ag.icon_16 + ");" + (ag.icon_16_css ? ag.icon_16_css : "") + '"'
            }
        } else {
            af += ad
        }
        return '<span class="b-share-icon b-share-icon_' + af + '"' + ae + "></span>"
    }

    function l(ae) {
        var ad = G[ae];
        return F(ae) ? ad.title : ad
    }

    function A(ah, ad, ag, ak, af) {
        af = af ? H(af) : "";
        ak = ak ? H(ak) : "";
        var ai = D + "/go.xml?service=" + ah;
        if (F(ah)) {
            var aj = G[ah];
            var ae = aj.url.replace("{link}", ad).replace("{title}", ag).replace("{description}", ak).replace("{image}", af);
            if (aj.directLink) {
                return ae
            } else {
                return ai + "&amp;goto=" + H(ae)
            }
        } else {
            return ai + "&amp;url=" + ad + "&amp;title=" + ag + (ak ? "&amp;description=" + ak : "") + (af ? "&amp;image=" + af : "")
        }
    }

    function aa(af, ah, ae, ag, ad) {
        if (af in G) {
            return '<a rel="nofollow" target="_blank" title="' + l(af) + '" class="b-share__handle b-share__link" href="' + A(af, ah, ae, ag, ad) + '" data-service="' + af + '">' + ab(af) + "</a>"
        } else {
            if (af == "|") {
                return '<span class="b-share__hr"></span>'
            }
        }
    }

    function d(af, ah, ae, ag, ad) {
        if (af in G) {
            return '<a rel="nofollow" target="_blank" href="' + A(af, ah, ae, ag, ad) + '" class="b-share-popup__item" data-service="' + af + '"><span class="b-share-popup__icon">' + ab(af) + '</span><span class="b-share-popup__item__text">' + l(af) + "</span></a>"
        } else {
            if (af == "|") {
                return '<div class="b-share-popup__spacer"></div>'
            }
        }
    }
    var E;

    function g() {
        t.clearTimeout(E)
    }

    function N(ad) {
        E = t.setTimeout(ad.onDocumentClick, 2000)
    }

    function f(ag, aj) {
        var ae, ak, ad = u(ag, "b-share-popup__expander")[0],
            af = u(ag, "b-share-popup__item");
        if (ad) {
            B(ad.firstChild, "click", i)
        }
        for (ae = 0, ak = af.length; ae < ak; ae++) {
            B(af[ae], "click", aj.onshare)
        }
        j(ag[a]("input"));
        j(ag[a]("textarea"));
        var ah = u(ag, "b-share-popup__item_type_code")[0];
        if (ah) {
            var ai = u(ag, "b-share-popup__i")[0];
            B(ah, "click", function (al) {
                V(ai, "b-share-popup_show_form_html");
                h(al)
            });
            B(u(ag, "b-share-popup__form__close")[0], "click", function (al) {
                L(ai, "b-share-popup_show_form_html");
                h(al)
            })
        }
        J(ag, g);
        o(ag, aj.setPopupCloseTimeout)
    }

    function j(af) {
        var ae = 0,
            ad = af.length,
            ag;
        for (; ae < ad; ae++) {
            ag = af[ae];
            B(ag, "click", (function (ah) {
                return function () {
                    ah.select()
                }
            })(ag))
        }
    }

    function R(af, ah, ae, ak) {
        var aj = 1,
            ai, ag = u(af, "b-share__handle");
        var ad = ag.length;
        var am = ad;
        if (ak) {
            aj = 0
        } else {
            var al = ag[0];
            if (ae) {
                al = ag[ad - 1];
                am--
            }
            B(al, "click", ah.toggleOpenMode);
            J(al, g);
            o(al, ah.setPopupCloseTimeout)
        }
        for (aj, ai = am; aj < ai; aj++) {
            B(ag[aj], "click", ah.onshare)
        }
    }

    function r(ae) {
        var ag, af, ad;
        if (!(ag = ae.currentTarget)) {
            ad = ae.target || ae.srcElement;
            if (!(ag = Q(ad, "b-share__handle"))) {
                ag = Q(ad, "b-share-popup__item")
            }
        }
        if (ag && (af = ag.getAttribute("data-service"))) {
            S(af);
            switch (af) {
            case "facebook":
                T(ae, ag, 800, 500);
                break;
            case "moimir":
                T(ae, ag, 560, 400);
                break;
            case "twitter":
                T(ae, ag, 650, 520);
                break;
            case "vkontakte":
                T(ae, ag, 550, 420);
                break;
            case "odnoklassniki":
                T(ae, ag, 560, 370);
                break
            }
        }
        return af
    }

    function T(ae, ag, ad, af) {
        h(ae);
        window.open(ag.href, "yashare_popup", "scrollbars=1,resizable=1,menubar=0,toolbar=0,status=0,left=" + ((screen.width - ad) / 2) + ",top=" + ((screen.height - af) / 2) + ",width=" + ad + ",height=" + af).focus()
    }

    function i() {
        var ad = Q(this, "b-share-popup__i");
        w(ad, "b-share-popup_full")
    }

    function X(ae, af) {
        if (ae && typeof ae !== "number") {
            var ad = ae.which ? ae.which : 1;
            if (ad !== 1 || Q(ae.target || ae.srcElement, "b-share-popup-wrap")) {
                return
            }
        }
        if (e) {
            af.closePopup(e.id)
        }
    }

    function W(af, aj) {
        af = af.replace("-popup", "");
        var ai = y.getElementById(af),
            ah = y.getElementById(af + "-popup"),
            ag = u(ah, "b-share-popup__input__input");
        L(ai, "b-share-popup_opened");
        L(ai, "b-share-form-button_state_pressed");
        ah.className = "b-share-popup-wrap b-share-popup-wrap_state_hidden";
        ah.style.cssText = "";
        if (ag) {
            for (var ae = 0, ad = ag.length; ae < ad; ae++) {
                ag[ae].style.cssText = ""
            }
        }
        ah.firstChild.className = "b-share-popup";
        Y(y, "click", aj.onDocumentClick);
        e = null;
        aj.onclose(aj._this)
    }

    function U(ae, aj, av) {
        av = av || {};
        var ai = av.hDirection || ae.getAttribute("data-hdirection"),
            aq = av.vDirection || ae.getAttribute("data-vdirection"),
            au = y.getElementById(ae.id + "-popup"),
            ah = au.firstChild,
            ag = u(au, "b-share-popup__input__input"),
            af = n(),
            ar, at, am = K(ae);
        if (ai === "left" || ai === "right") {
            ar = ai
        } else {
            ar = (am.left - Math.max(x.scrollLeft, y.body.scrollLeft)) >= af.width / 2 ? "left" : "right"
        } if (aq === "up" || aq === "down") {
            at = aq
        } else {
            at = (am.top - Math.max(x.scrollTop, y.body.scrollTop)) >= af.height / 2 ? "up" : "down"
        }
        aj.onDocumentClick();
        var ak = u(au, "b-share-popup__tail")[0],
            ao = Math.round(ae.offsetWidth / 2),
            al = av.width || ah.offsetWidth,
            ad = Math.round(al / 2);
        if (am.left - (ad - ao) > 5) {
            am.left -= Math.round(ad - ao);
            ao = ad - 10
        }
        am.top += (at === "up" ? -9 : 9 + ae.offsetHeight);
        au.style.cssText = "top:" + (av.top || am.top) + "px;left:" + (av.left || am.left) + "px;width:" + al + "px";
        ak.style.cssText = "left: " + ao + "px";
        if (ag) {
            for (var ap = 0, an = ag.length; ap < an; ap++) {
                ag[ap].style.width = (al - 30) + "px"
            }
        }
        ah.className = "b-share-popup b-share-popup_" + at + " b-share-popup_to-" + ar;
        au.className = "b-share-popup-wrap b-share-popup-wrap_state_visible";
        V(ae, "b-share-popup_opened");
        V(ae, "b-share-form-button_state_pressed");
        ah.firstChild.style.zoom = 1;
        t.setTimeout(function () {
            B(y, "click", aj.onDocumentClick)
        }, 50);
        S("share");
        e = au;
        aj.onopen(aj._this)
    }

    function P(ae, ad) {
        var af = ae.currentTarget || Q(ae.target || ae.srcElement, "b-share__handle");
        if (q(af, "b-share-popup_opened")) {
            ad.closePopup(af.id, ad)
        } else {
            if (ad.onbeforeopen(ad._this) !== false) {
                ad.openPopup(af, ad)
            }
        }
        h(ae);
        k(ae)
    }
    if (!("Ya" in t)) {
        t.Ya = {}
    }
    Ya.share = function (ad) {
        if (!(this instanceof Ya.share)) {
            return new Ya.share(ad)
        }
        if (ad) {
            z = ad.STATIC_HOST || z;
            D = ad.SHARE_HOST || D
        }
        this._loaded = false;
        var ae = this,
            ag = ad.onshare || C,
            af = {
                onready: ad.onready || ad.onload || C,
                onbeforeclose: ad.onbeforeclose || C,
                onclose: ad.onclose || C,
                onbeforeopen: ad.onbeforeopen || C,
                onopen: ad.onopen || C,
                onshare: function (ai) {
                    var ah = r(ai);
                    if (ah) {
                        ag(ah, ae)
                    }
                },
                _this: ae
            };
        af.toggleOpenMode = function (ah) {
            P(ah, af)
        };
        af.closePopup = function (ah) {
            g();
            if (af.onbeforeclose(ae) !== false) {
                W(ah, af)
            }
        };
        af.openPopup = function (ah, ai) {
            U(ah, ai)
        };
        af.onDocumentClick = function (ah) {
            X(ah, af)
        };
        af.setPopupCloseTimeout = function () {
            N(af)
        };
        this.closePopup = function () {
            W(this._popup.id, af)
        };
        this.openPopup = function (ah) {
            U(u(this._block, "b-share__handle")[0], af, ah)
        };
        ac(function () {
            var ah = b(ad, af);
            ad = null;
            if (!ah) {
                return
            }
            ae._block = ah[0];
            ae._popup = ah[1];
            ae._loaded = true;
            af.onready(ae)
        })
    };
    Ya.share.prototype = {
        updateShareLink: function (ai, aj, al) {
            if (!this._loaded) {
                return this
            }
            var af, ae, am, ag, ak = "",
                ad = "";
            if (arguments.length == 1 && typeof arguments[0] == "object") {
                var ah = arguments[0];
                ai = ah.link || t.location.toString();
                aj = ah.title || y.title;
                ak = ah.description || "";
                ad = ah.image || "";
                al = ah.serviceSpecific || {}
            } else {
                ai = ai || t.location.toString();
                aj = aj || y.title;
                al = al || {}
            }
            am = u(this._block, "b-share__link");
            for (af = 0, ae = am.length; af < ae; af++) {
                ag = am[af].getAttribute("data-service");
                am[af].href = A(ag, c(ag, "link", ai, al), c(ag, "title", aj, al), c(ag, "description", ak, al), c(ag, "image", ad, al))
            }
            if (this._popup) {
                am = u(this._popup, "b-share-popup__item");
                for (af = 0, ae = am.length; af < ae; af++) {
                    ag = am[af].getAttribute("data-service");
                    am[af].href = A(ag, c(ag, "link", ai, al), c(ag, "title", aj, al), c(ag, "description", ak, al), c(ag, "image", ad, al))
                }
                am = u(this._popup, "b-share-popup__input__input")[0];
                if (am) {
                    am.value = ai
                }
            }
            return this
        },
        updateCustomService: function (ad, ae) {
            if (G[ad] && G[ad].url) {
                G[ad].url = ae;
                this.updateShareLink()
            }
        }
    };
    ac(function () {
        var af = u(y.body, "yashare-auto-init"),
            aj = 0,
            ah = af.length,
            ad, ai, ag, ae;
        for (; aj < ah; aj++) {
            ad = af[aj];
            ai = ad.getAttribute("data-yashareQuickServices");
            ag = ad.getAttribute("data-yasharePopupServices");
            if (typeof ai === "string") {
                ai = ai.split(",")
            } else {
                ai = null
            }
            ae = {
                element: ad,
                l10n: ad.getAttribute("data-yashareL10n"),
                link: ad.getAttribute("data-yashareLink"),
                title: ad.getAttribute("data-yashareTitle"),
                elementStyle: {
                    type: ad.getAttribute("data-yashareType"),
                    quickServices: ai
                }
            };
            if (ag && typeof ag === "string") {
                ag = ag.split(",");
                ae.popupStyle = {
                    blocks: ag
                }
            }
            new Ya.share(ae)
        }
    });

    function m(ad) {
        return (ad || "").replace(/</g, "&lt;").replace(/"/g, "&quot;")
    }
})(window, document);

function showMsgBox(f, b, e, url) {
    var a = $("#msg-box");
    e = e || "left";
    if (a.length === 0) {
        a = $("<div />").attr("id", "msg-box").addClass("b-msg-box " + (b || ""));
        $("<p />").html(f).css("text-align", e).appendTo(a);
        $("<span />").addClass("close").click(function () {
            a.hide();
			if (url) document.location.href = url;
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

function removeCartItem() {
    var a = $(this);
    $.getJSON(a.attr("href"), null, function (f, b) {
        if (b === "success" && f.success) {
            if (f.count == 0) {
                $("#cp-content").html(f.cphtml)
            } else {
                var e = $("#cp-content"),
                    c = e.find("tr[data-item-id=" + f.id + "]");
                if (c.length) {
                    c.next().remove();
                    c.remove();
                    e.find("tr:even").each(function (g) {
                        $(this).find(".num").text(g + ".")
                    });
                    $("#cp-total-amount").html(f.totalAmount)
                }
            }
            var d = $("#cm-content");
            d.html(f.cmhtml);
            if (f.count != 0) {
                d.find(".plist a, .btn-reset").click(removeCartItem)
            }
        }
    });
    return false
}
$(function () {

    $("#search-fld").focus(function () {
        if ($(this).val() == "Поиск...") {
            $(this).val("")
        }
    }).blur(function () {
        if ($(this).val() == "") {
            $(this).val("Поиск...")
        }
    });
	
	
    $("#search-fld-code").focus(function () {
        if ($(this).val() == "Поиск по коду") {
            $(this).val("")
        }
    }).blur(function () {
        if ($(this).val() == "") {
            $(this).val("Поиск по коду")
        }
    });
	
    $("#show-login").click(function () {
        $("#search-form").hide();
        $("#login-form").show()
    });
    $("#back-to-search").click(function () {
        $("#login-form").hide();
        $("#search-form").show()
    });   	
    $("#slide-panel .fbo").each(function () {
        var e = $(this);
        e.find(".inner").css("display", "block");
        e.css({
            display: "inline-block"
        });
        if ($.cookie(e.attr("id") + "-opened") !== "1") {
            e.css({
                marginRight: "-" + e.width() + "px"
            })
        } else {
            e.find(".opener").addClass("opened")
        }
    });
    $("#slide-panel .opener").click(function () {
        var f = $(this),
            e = f.parent(".fbo"),
			wm = $(this).prev();
        if (f.hasClass("opened")) {
            e.animate({
                marginRight: "-" + e.width() + "px"			
            }, "slow", function () {
                f.removeClass("opened")
            });
            $.cookie(e.attr("id") + "-opened", "0", {
                path: "/"
            })
        } else {
            e.animate({
                marginRight: 0
            }, "slow", function () {
                f.addClass("opened")
            });
            $.cookie(e.attr("id") + "-opened", "1", {
                path: "/"
            })
        }
        return false
    });
    $("#fld-input-code").focus(function () {
        if ($(this).val() == $(this).prop("defaultValue")) {
            $(this).val("")
        }
    }).blur(function () {
        if ($(this).val() == "") {
            $(this).val($(this).prop("defaultValue"))
        }
    });
    $(window).scroll(function () {
        var e = $("#slide-panel"),
            f = $(document).scrollTop() + 6;
        e.animate({
            top: f
        }, {
            duration: 0,
            queue: false
        })
    });
    $("#sidebar-filter").css({
        display: "none",
        visibility: "visible"
    });
    $("#btn-show-filter").click(function () {
        $("#sidebar-filter").slideToggle("fast")
    });
    $(".f-pricefrom").focus(function () {
        if ($(this).val() == "от") {
            $(this).val("")
        }
    }).blur(function () {
        if ($(this).val() == "") {
            $(this).val("от")
        }
    });
    $(".f-priceto").focus(function () {
        if ($(this).val() == "до") {
            $(this).val("")
        }
    }).blur(function () {
        if ($(this).val() == "") {
            $(this).val("до")
        }
    });
    $("#sort-select").change(function () {
        location = $(this).val()
    });

    $("#home-tabs").tabs({
        cookie: {
            expires: 1
        }
    });    
	$("#category-tabs").tabs();
    $(".goto-adv-search").click(function () {
        $("#slider").addClass("hidden");
        $("#catalog-filter").removeClass("hidden");
        $.cookie("dfilter", "1", {
            path: "/"
        })
    });
    $("#login-form").submit(function () {
        var e = $(this);
		$.ajax({
			type: "POST",
			url: e.attr("action"),
			dataType: "json",
			data: e.serialize(),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
	                $("#btns-account").remove();
					$("#btn-logout").removeClass("hidden");
					$("#btn-cabinet").removeClass("hidden");
					$("#search-form").show();
					e.remove();
					//showMsgBox(res['message'], "white", "center");
				} else {
					showMsgBox(res['message'], "white", "center");
				}
			}					
		});
        return false
    });
    $("#form-callback, #form-want-product, #form-checkout, #form-review, #form-faq, #form-registration, #form-discount, #form-active-user").submit(function () {
        var e = $(this);
		var valid = true;
		e.find('.required').each(function () {
			if ($(this).val() == '' || $(this).val() == '*') {
				return valid = false;
			}			
		});
		if(valid) {
			$.ajax({
				type: "POST",
				url: e.attr("action"),
				dataType: "json",
				data: e.serialize(),
				success:function (res, f) {//возвращаемый результат от сервера
					if (f === "success" && res['succes']) {
						e.find(":text, textarea").each(function () {
							if ($(this).hasClass("required")) {
								$(this).val("*").addClass("asterisk")
							} else {
								$(this).val("")
							}
						});	
						if (res['redirect']==1) {
							if (res['url']) document.location.href = res['url'];
						} else {
							showMsgBox(res['message'], "white", "center", res['url']);
						}
						
					} else {
						showMsgBox(res['message'], "white", "center", res['url']);
					}
				}					
			}); 
		} else {
			showMsgBox('Заполните пожалуйста поля со звездочкой!', "white", "center");
		}
        return false
    });
    $("#add-child").click(function () {
        var f = $("#children-info .child-info:last"),
            g = f.clone(),
            e = f.find(":text:first").attr("name").match(/\d/)[0];
        g.find(":input").val("");
        g.find(".w-select > div").remove();
        g.find("select, :text").each(function () {
            $(this).attr("name", $(this).attr("name").replace(/\d/, parseInt(e) + 1))
        });
        $("#children-info").append(g);
        g.find(".w-select select").selectmenu({
            maxHeight: 342
        });
        $("#children-info .rm-child").show().unbind("click").click(function () {
            var h = $("#children-info .child-info").length;
            if (h > 1) {
                $(this).parents(".child-info").remove();
                if ((h - 1) === 1) {
                    $("#children-info .rm-child").hide()
                }
            }
        })
    });
    $("#add-phone").click(function () {
        var f = $("#phone-info .phone-info:last"),
            g = f.clone(),
            e = f.find(":text:first").attr("name").match(/\d/)[0];
        g.find(":input").val("");
        g.find(".w-select > div").remove();
        g.find("select, :text").each(function () {
            $(this).attr("name", $(this).attr("name").replace(/\d/, parseInt(e) + 1))
        });
        $("#phone-info").append(g);
        g.find(".w-select select").selectmenu({
            maxHeight: 342
        });
        $("#phone-info .rm-phone").show().unbind("click").click(function () {
            var h = $("#phone-info .phone-info").length;
            if (h > 1) {
                $(this).parents(".phone-info").remove();
                if ((h - 1) === 1) {
                    $("#phone-info .rm-phone").hide()
                }
            }
        })
    });	
    $("input.required").focus(function () {
        if ($(this).val() == "*") {
            $(this).removeClass("asterisk").val("")
        }
    }).blur(function () {
        if ($(this).val() == "") {
            $(this).addClass("asterisk").val("*")
        }
    });
    $("#form-discount .show-discount").mouseover(function () {
        $(this).prev().find(".stiker").show()
    }).mouseout(function () {
        $(this).prev().find(".stiker").hide()
    });    
	/*$("#form-discount .show-discount").mouseover(function () {
        $(this).next().show()
    }).mouseout(function () {
        $(this).next().hide()
    });*/
	
	$(".date-picker").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	
    $("#cart-page .item-details").hide();
    $("#cart-page .btn-show-details").click(function () {
        var e = $(this).parents("tr").next(".item-details");
        if (e.is(":hidden")) {
            e.show();
            $(this).addClass("collapse")
        } else {
            e.hide();
            $(this).removeClass("collapse")
        }
    });
    $("#cart-page .name span").click(function () {
        var f = $(this).parents("tr"),
            e = f.next(".item-details");
        if (e.is(":hidden")) {
            e.show();
            f.find(".btn-show-details").addClass("collapse")
        } else {
            e.hide();
            f.find(".btn-show-details").removeClass("collapse")
        }
        return false
    });
	$("body").delegate("#form-product-buy, .form-product-buy",'submit',function(){

		var e = $(this),
			cm = $("#cm-content");
			$.ajax({
				type: "POST",
				url: e.attr("action"),
				dataType: "json",
				data: e.serialize(),
				success:function (res, f) {//возвращаемый результат от сервера
					if (f === "success" && res['succes']) {
						cm.empty();
						cm.html(res['html']);
						var g=cm.next();
						if (!g.hasClass("opened")) g.click();
						setTimeout(function(){
							if (g.hasClass("opened")) g.click();
						},2500);
					} else {
						showMsgBox(res['message'], "white", "center")
					}
				}					
			});	
		return false
	});
	$("body").delegate("#form-product-wishlist, .form-product-wishlist, #codetocompare",'submit',function(){	
        var e = $(this),
			ww = $("#wm-wrap"),
			wm = $("#wm-content");
			$.ajax({
				type: "POST",
				url: e.attr("action"),
				dataType: "json",
				data: e.serialize(),
				success:function (res, f) {//возвращаемый результат от сервера
					if (f === "success" && res['succes']) {
						ww.empty();
						ww.html(res['html']);
						var g=wm.next();
						if (!g.hasClass("opened")) g.click();
						setTimeout(function(){
							if (g.hasClass("opened")) g.click();
						},2500);
						if (res['message']) showMsgBox(res['message'], "white", "center");
					} else {
						showMsgBox(res['message'], "white", "center")
					}
				}					
			});	
        return false
    });   
	$("#pred_result").delegate("#btn-wish-delegate", "click", function(){
        var e = $(this),
			ww = $("#wm-wrap"),
			wm = $("#wm-content"),
			pid = $(this).attr('pid');
			$.ajax({
				type: "POST",
				url: e.attr("href"),
				dataType: "json",
				data: "pid="+pid,
				success:function (res, f) {//возвращаемый результат от сервера
					if (f === "success" && res['succes']) {
						ww.empty();
						ww.html(res['html']);
						var g=wm.next();
						if (!g.hasClass("opened")) g.click();
						setTimeout(function(){
							if (g.hasClass("opened")) g.click();
						},2500);
						if (res['message']) showMsgBox(res['message'], "white", "center");
					} else {
						showMsgBox(res['message'], "white", "center")
					}
				}					
			});	
        return false
    });	
	$("#pred_result").delegate("#show_all_result_search", "click", function(){
        $('.btn-search').click();
        return false
    });
	$("#compare_click").click(function() {
		$("#form-product-wishlist").submit();
		return false;
	});	
	$("#oformit_zakaz").click(function() {
		var g = $("#cart-page td.qty input")
			fl = true;
		g.each(function (i) {
			if ($(this).val() < 1) {
				showMsgBox("Количество должно быть больше нуля", "white", "center");
				fl = false;
			}
		});
		if (!fl) return false;
	});
    $("#cart-page td.qty input").keyup(function (g) {
        var f = $(this),
            i = parseInt(f.val());
        if (i > 0) {
            var h = f.attr("data-item-id");
			$.ajax({
				type: "POST",
				url: "/cart/updatecart/",
				dataType: "json",
				data: {id: h, qty: i},
				success:function (res, f) {//возвращаемый результат от сервера
					if (f === "success") {
						$(".ci-total-" + h).html(res['total']);
						$("#ci-qty-" + h).html(i);
						$("#ci-price-" + h).html(res['price']);
						$("#cp-total-amount").html(res['totalAmount']);			
					} else {
						showMsgBox(res['message'], "white", "center")
					}
				}					
			});				
        } 
    });    

    $("#goto-detail-desc").click(function () {
        $("body,html").animate({
            scrollTop: $($(this).attr("href")).offset().top
        }, 800, function () {
            $("#product-info-tabs").tabs("select", 5)
        });
        return false
    });    
	$("#goto-detail-char").click(function () {
        $("body,html").animate({
            scrollTop: $($(this).attr("href")).offset().top
        }, 800, function () {
            $("#product-info-tabs").tabs("select", 0)
        });
        return false
    });
	$("#goto-detail-photo").click(function () {
        $("body,html").animate({
            scrollTop: $($(this).attr("href")).offset().top
        }, 800, function () {
            $("#product-info-tabs").tabs("select", 2)
        });
        return false
    });	
    $("#back-top").click(function () {
        $("body,html").animate({
            scrollTop: 0
        }, 800);
        return false
    });
    $(".btn-choose-color").click(function () {
        var l = $(this),
            m = l.attr("data-model-id"),
            g = $("#pmt-tab-1 a[data-model-id=" + m + "]"),
            h = g.parent("li").attr("jcarouselindex"),
            f = l.prev().find("img");
		$("#product-media-tabs").tabs("select", 0);
		$("#pmodels-carousel").jcarousel("scroll", h - 1, true);
		g.click();
		$("#refresh-price > span").empty();
        $("#refresh-price > span").html(l.attr("data-model-price"));		
        $("body,html").animate({
            scrollTop: 440
        }, 800);
    });
    var b = $(".b-related-products .view-switcher");
    b.find("li:first-child").click(function () {
        $(this).parent(".view-switcher").next().attr("class", "grid");
        $(this).addClass("active").next().removeClass("active")
    });
    b.find("li + li").click(function () {
        $(this).parent(".view-switcher").next().attr("class", "list");
        $(this).addClass("active").prev().removeClass("active")
    });
    var a = $("#cmp-carousel .item");
    var c = a.length;
    var d = 0;
    $("#cmp-carousel .next").click(function () {
        if (d + 4 < c) {
            a.eq(d).addClass("hidden");
            a.eq(d + 4).removeClass("hidden");
            var f = $("#cmp-features .rb td:nth-child(" + (d + 2) + ")"),
                e = $("#cmp-features .rb td:nth-child(" + (d + 6) + ")");
            f.attr("class", "hidden");
            f.next().attr("class", "c2").next().attr("class", "c3").next().attr("class", "c4");
            e.attr("class", "c5");
            d++
        }
        return false
    });
    $("#cmp-carousel .prev").click(function () {
        if (d > 0) {
            d--;
            a.eq(d).removeClass("hidden");
            a.eq(d + 4).addClass("hidden");
            var f = $("#cmp-features .rb td:nth-child(" + (d + 6) + ")"),
                e = $("#cmp-features .rb td:nth-child(" + (d + 2) + ")");
            f.attr("class", "hidden");
            e.attr("class", "c2").next().attr("class", "c3").next().attr("class", "c4").next().attr("class", "c5")
        }
        return false
    });
    $("#cmp-features .hide-row").click(function () {
        $(this).parents("tr").remove()
    });    
	$(".form-btn").toggle(function () {
        $(this).html("скрыть");
		$('.arr').removeClass('bot');
		$('.arr').addClass('top');
		$('.dop-form').show();
		return false;
    },function (){
        $(this).html("показать");
		$('.arr').removeClass('top');
		$('.arr').addClass('bot');		
		$('.dop-form').hide();
		return false;
    });

	function number_format( str ){
	   return str.replace(/(\s)+/g, '').replace(/(\d{1,3})(?=(?:\d{3})+$)/g, '$1 ');
	}	

	$('.onlydigit').bind("change keyup input click", function() {
		if (this.value.match(/[^0-9]/g)) {
			this.value = this.value.replace(/[^0-9]/g, '');
		}
	});	

	/*$('.onlycena').bind("change keyup input click", function() {
		if (this.value.match(/[^0-9]/g)) {
			this.value = this.value.replace(/[^0-9]/g, '');
		}
		this.value = number_format(this.value);
	});	*/
	
	$("#wm-content").delegate("#linkstofriend", "click", function(){		
		var e = $(this);
				
		$.ajax({
			type: "POST",
			url: e.attr("href"),
			dataType: "json",
			data: e.serialize(),
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					e.find(":text, textarea").each(function () {
						if ($(this).hasClass("required")) {
							$(this).val("*").addClass("asterisk")
						} else {
							$(this).val("")
						}
					});	
					showMsgBox(res['message'], "white", "left");
				} else {
					showMsgBox(res['message'], "white", "center");
				}
			}					
		}); 
		
	return false;
	
	});
	
	$(".check-header").click(function () {

		if ($(this).next('div').hasClass("active")) {
			$(this).css('border-bottom','1px solid #9F9F9F');
			$(this).next('div').removeClass('active');			
		} else {
			$(this).css('border-bottom','none');
			$(this).next('div').addClass('active');		
		}
	
	});	
	
	/*$('.check-body span').each(function() {
	
		if ($(this).attr("class") == "checked") {
			
			var elem = $(this).parent().parent().parent().parent().prev();
			elem.css('border-bottom','none');
			elem.next('div').addClass('active');		
		}
	
	});	*/
	
	$(".filtr_catalog").each(function () {
		var e = $(this);
		e.find(".check-header").click();
	});	
		
	
	$('#uniform-dumayut span, #uniform-samovivoz_ofice span, #uniform-samovivoz span').click(function() {
	
		if ($(this).attr("class") == "checked") {
			
			$('#street').removeClass('required').val('');	
			$('#house').removeClass('required').val('');	
			$('#apartment').removeClass('required').val('');	
		
		} else {
		
			$('#street').addClass('required').val('*');
			$('#house').addClass('required').val('*');
			$('#apartment').addClass('required').val('*');
		
		}
	
	});

	
	function pred_result(elem) {
		var string = elem,
			q = string.val();

			if (q.length > 0) {
				$.ajax({
					type: "GET",
					url: "/category/pred_search",
					dataType: "json",
					data: "q="+q,
					success:function (res, f) {//возвращаемый результат от сервера
						if (f === "success" && res['succes']) {
							$("#pred_result").show();
							$("#pred_result").html(res['html']);		
						} else {
							$("#pred_result").empty();
							$("#pred_result").hide();
						}
					}					
				});	
			} else {
				$("#pred_result").hide();
			}	
	}
	
	$("#search-fld").keyup(function (g) {
		pred_result($(this));
    });	
	
	$("#search-fld").focus(function (g) {
		pred_result($(this));
    });		
	
	$("body").click(function (event) {
		if ($(event.target).closest("#pred_result").length === 0) {
			$("#pred_result").hide();
		}
	});

	$(".refresh-curs").click(function() {
	
		$(".d-currency").find('.active').removeClass('active');
		$(this).parent().addClass('active');
		var cur = $(this).attr('rel'),
			id_catalog = $(this).attr('data-id-catalog')
		$.ajax({
			type: "GET",
			url: $(this).attr('href'),
			dataType: "json",
			data: "currency="+cur+"&id_catalog="+id_catalog,
			success:function (res, f) {
				if (f === "success" && res['succes']) {
					$("#refresh-price").empty();
					$("#refresh-price").html(res['html']);		
				} 
			}					
		});		
	
		return false;
	});

	$(".skidka").keyup(function () {
		var e = $(this);

		$.ajax({
			type: "GET",
			url: "/cart/updatetable/",
			dataType: "json",
			data: "key="+e.attr('id')+"&val="+e.val(),
			success:function (res, f) {
				if (f === "success" && res['succes']) {
					$("#zakaz_product_table").empty();
					$("#zakaz_product_table").html(res['html']);		
				} 
			}					
		});			
	});	
	
	$("#filtr-form input, #filtr-form select").change(function() {
		var e = $("#filtr-form");
		$(".b-products").css({"opacity" : "0.5"});
		data_url = e.serialize()+'&filtr_submit=1&path='+e.attr('rel')+"&path2="+e.attr('data-path2');
		$.ajax({
			type: "GET",
			url: e.attr("action"),
			dataType: "json",
			data: data_url,
			success:function (res, f) {//возвращаемый результат от сервера
				if (f === "success" && res['succes']) {
					//if (res['redirect']) document.location.href = res['redirect'];
					$(".b-products").empty();
					$(".b-products").css({"opacity" : "1"});
					$(".b-products").html(res['html']);
					window.history.replaceState(null, null, "/category/"+e.attr('rel')+"/?"+data_url);
				} 
			}					
		});		
	});
	
	$(".importer-izgotovitel").click(function() {
		var msgBlock = $("#ii-display").html();
		showMsgBox(msgBlock, "white", "left");
	});
			
});