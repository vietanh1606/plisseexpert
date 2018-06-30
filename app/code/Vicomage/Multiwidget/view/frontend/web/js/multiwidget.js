/**
 * Vicomage Product Filter JS
 */
define(['jquery','slick','quickview','vicomagecore'],function($){
    (function ($) {
        "use strict";

        $.fn.vicomagewidget = function(element,sliderConfig,ajaxConfig) {
            var methods = {
                productfilterLoadAjax: function (element,sliderConfig,ajaxConfig) {
                    element.find('li.product-tab').each(function () {
                        var tab = $(this);
                        var group = tab.parents('.vigoproduct');
                        var productContent = '.content-products';
                        var type = tab.attr('data-type');
                        var classType = '.product_type_' + type;
                        var ajax_url = tab.parents('.group-product-tabs').attr('data-ajax-url');
                        var config = JSON.parse(sliderConfig);

                        if (tab.hasClass('active')) {
                            var tabActive = group.find(classType).addClass('active').find('.product-items');
                            $.fn.vicomageSlider(tabActive,sliderConfig);
                        }

                        tab.on('click', function () {
                            var $loading = group.find('.ajax_loading');


                            //check if this tab has active
                            if (tab.hasClass('active')) {
                                return false;
                            }
                            tab.addClass('active');
                            tab.siblings().removeClass('active');


                            if (tab.hasClass('loaded')) {
                                //if product loaded will display that tab
                                var tabActive = group.find(classType).addClass('active');
                                tabActive.siblings().removeClass('active');
                                var tabActive2 = tabActive.find('.product-items');
                                $.fn.vicomageSlider(tabActive2,sliderConfig);
                            } else {
                                //if load have not load will send ajax and get content
                                $loading.show();
                                $.ajax({
                                    type: 'POST',
                                    data: {type: type, info: ajaxConfig},
                                    url: ajax_url,
                                    success: function (data) {
                                        $loading.hide();
                                        group.find(productContent).append(data);
                                        var tabActive = group.find(classType).addClass('active');
                                        tabActive.siblings().removeClass('active');
                                        $.fn.vicomageSlider(tabActive.find('.product-items'),sliderConfig);
                                        if($.fn.timer !== undefined){
                                            var countdown = tabActive.find('.vicommage-count-down');
                                            if(countdown.lenght){
                                                countdown.timer({
                                                    classes	: '.countdown',
                                                    layout	: vicommage_timer_layout,
                                                    timeout : vicommage_timer_timeout
                                                });
                                            }
                                        }
                                        if (tab.attr('data-type') == type) {
                                            tab.addClass('loaded');
                                        }

                                        //check quick view
                                        if (config.quickview) {
                                            $.fn.vicomagequickview();
                                        }
                                        methods.productAddToCart();
                                    }
                                });
                            }
                        });
                    })
                },

                productAddToCart: function () {
                    if ($.fn.mage !== undefined) {
                        $('.action.tocart').unbind("click").click(function () { // Callback Ajax Add to Cart
                            var form = $(this).closest('form');
                            var widget = form.catalogAddToCart({bindSubmit: false});
                            widget.catalogAddToCart('submitForm', form);
                            return false;
                        });
                    }
                }
            }
            methods.productfilterLoadAjax(element,sliderConfig,ajaxConfig);
        }

        $.fn.timer = function (options) {
            var defaults = {
                classes  	 : '.countdown',
                layout	 	 : '<span class="day">%%D%%</span><span class class="colon">:</span><span class="hour">%%H%%</span><span class="colon">:</span><span class="min">%%M%%</span><span class="colon">:</span><span class="sec">%%S%%</span>',
                leadingZero	 : true,
                countStepper : -1, // s: -1 // min: -60 // hour: -3600
                timeout	 	 : '<span class="timeout">Time out!</span>',
            };

            var settings = $.extend(defaults, options);
            var layout			 = settings.layout;
            var leadingZero 	 = settings.leadingZero;
            var countStepper 	 = settings.countStepper;
            var setTimeOutPeriod = (Math.abs(countStepper)-1)*1000 + 990;
            var timeout 		 = settings.timeout;

            var methods = {
                init : function() {
                    return this.each(function() {
                        var $countdown 	= $(settings.classes, $(this));
                        if( $countdown.length )methods.timerLoad($countdown);
                    });
                },

                timerLoad: function(el){
                    var gsecs = el.data('timer');
                    if(gsecs > 0 ){
                        methods.CountBack(el, gsecs);
                    }
                },

                calcage: function (secs, num1, num2) {
                    var s = ((Math.floor(secs/num1)%num2)).toString();
                    if (leadingZero && s.length < 2) s = "0" + s;
                    return "<b>" + s + "</b>";
                },

                CountBack: function (el, secs) {
                    if (secs < 0) {
                        el.html(timeout);
                        return;
                    }
                    var timerStr = layout.replace(/%%D%%/g, methods.calcage(secs,86400,100000));
                    timerStr = timerStr.replace(/%%H%%/g, methods.calcage(secs,3600,24));
                    timerStr = timerStr.replace(/%%M%%/g, methods.calcage(secs,60,60));
                    timerStr = timerStr.replace(/%%S%%/g, methods.calcage(secs,1,60));
                    el.html(timerStr);
                    setTimeout(function(){ methods.CountBack(el, (secs+countStepper))}, setTimeOutPeriod);
                },

            };

            if (methods[options]) {
                return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
            } else if (typeof options === 'object' || !options) {
                return methods.init.apply(this);
            } else {
                $.error('Method "' + method + '" does not exist in timer plugin!');
            }
        }

        if (typeof vicommage_timer_layout != 'undefined'){
            $('.vicommage-count-down').timer({
                classes	: '.countdown',
                layout	: vicommage_timer_layout,
                timeout : vicommage_timer_timeout
            });
        }
    })(jQuery);
});
