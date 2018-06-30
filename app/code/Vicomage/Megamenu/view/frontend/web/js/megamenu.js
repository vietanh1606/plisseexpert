define(['jquery'], function ($) {
    (function ($, window, document, undefined) {
        $.fn.Megamenu = function () {

            $(this).find("li.dropdown .subchildmenu > li.parent").mouseover(function () {
                var popup = $(this).children("ul.subchildmenu");
                var w_width = $(window).innerWidth();

                if (popup) {
                    var pos = $(this).offset();
                    var c_width = $(popup).outerWidth();
                    if (w_width <= pos.left + $(this).outerWidth() + c_width) {
                        $(popup).css("left", "auto");
                        $(popup).css("right", "100%");
                    } else {
                        $(popup).css("left", "100%");
                        $(popup).css("right", "auto");
                    }
                }
            });
            $(this).find("li.staticwidth.parent").mouseover(function () {
                var popup = $(this).children(".submenu");
                var w_width = $(window).innerWidth();
				
                if (popup) {
                    var pos = $(this).offset();
                    var c_width = $(popup).outerWidth();
					var postion = c_width/2;
                    if (w_width <= pos.left + $(this).outerWidth() + c_width) {
                        $(popup).css("left", "auto");
                        $(popup).css("right", postion - c_width);
                    } else {
                        $(popup).css("left", "0"); 
                        $(popup).css("right", "auto");
                    }
                }
            });
			
			$(this).find("li.dropdown.parent").mouseover(function () {
                var popup = $(this).children(".submenu");
                var w_width = $(window).innerWidth();

                if (popup) {
                    var pos = $(this).offset();
                    var c_width = $(popup).outerWidth();
                    if (w_width <= pos.left + $(this).outerWidth() + c_width) {
                        $(popup).css("left", "auto");
                        $(popup).css("right", "0");
                    } else {
                        $(popup).css("left", "0");
                        $(popup).css("right", "auto");
                    }
                }
            });
			
            $(".nav-toggle").off('click').on('click', function (e) {
                if (!$("html").hasClass("nav-open")) {
                    $("html").addClass("nav-before-open");
                    setTimeout(function () {
                        $("html").addClass("nav-open");
                    }, 300);
                }
                else {
                    $("html").removeClass("nav-open");
                    setTimeout(function () {
                        $("html").removeClass("nav-before-open");
                    }, 300);
                }
            });
            $("li.ui-menu-item > .open-children-toggle").click(function () {
                $(this).next('.submenu').slideToggle();
                if (!$(this).parent().children(".submenu").hasClass("opened")) {
                    $(this).parent().children(".submenu").addClass("opened");
                    $(this).parent().children("a").addClass("ui-state-active");

                }
                else {
                    $(this).parent().children(".submenu").removeClass("opened");
                    $(this).parent().children("a").removeClass("ui-state-active");
                }
            });
        };
    })(window.Zepto || window.jQuery, window, document);
});