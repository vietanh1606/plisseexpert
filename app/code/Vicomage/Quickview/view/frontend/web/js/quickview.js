/**
 * Vicomage Quick View JS
 */
define(['jquery', 'fancybox'],function ($) {
    (function ($) {
        "use strict";
        $.fn.vicomagequickview = function()
        {
            var vicomage = {
                _QuickView: function () {
                    var config = $('.quickview-config').attr('data');
                    if(config != undefined) {
                        config = JSON.parse(config);
                        var Url = $('.quickview-config').attr('data-url');

                        $('.vicomage-quickview').bind('click', function () {
                            var prodUrl = $(this).attr('data-quickview-url');

                            if (prodUrl.length) {
                                var url = window.vicomage_quickview.baseUrl + Url;
                                var showMiniCart = parseInt(window.vicomage_quickview.showMiniCart);
                                window.vicomage_quickview.showMiniCartFlag = false;

                                var overlay = "null";
                                var width = (config.popupWidth) ? config.popupWidth + 'px' : '600px';
                                var height = (config.popupHeight) ? config.popupHeight + 'px' : '400px';
                                if (config.displayOverlay) {
                                    overlay = "{showEarly:true}";
                                }
                                $.fancybox({
                                    autoSize: config.autoSize,
                                    width: width,
                                    height: height,
                                    title: 'null',
                                    scrolling: 'auto',
                                    type: 'iframe',
                                    href: prodUrl,
                                    openEffect: config.openEffect,
                                    closeEffect: config.closeEffect,
                                    helpers: {
                                        title: null,
                                        overlay: overlay
                                    },
                                    beforeLoad: function () {
                                    },
                                    afterLoad: function () {
                                    },
                                    beforeShow: function () {
                                    },
                                    afterShow: function () {
                                    },
                                    beforeChange: function () {
                                    },
                                    beforeClose: function () {
                                        $('[data-block="minicart"]').trigger('contentLoading');
                                        $.ajax({
                                            url: url,
                                            method: "POST"
                                        });
                                    },
                                    afterClose: function () {
                                        if (window.vicomage_quickview.showMiniCartFlag && showMiniCart) {
                                            $("html, body").animate({scrollTop: 0}, "slow");
                                            setTimeout(function () {
                                                $('.action.showcart').trigger('click');
                                            }, 1000);
                                        }
                                    }
                                });
                            }
                        });
                    }
                },
            };
            vicomage._QuickView();
        }
        $.fn.vicomagequickview();
    })(jQuery);
});