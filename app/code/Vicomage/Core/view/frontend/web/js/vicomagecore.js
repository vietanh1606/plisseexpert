define(['jquery','slick'],function($){
    (function ($) {
        "use strict";

        $.fn.vicomageSlider = function(element,config) {
            var methods = {
                configSlider: function (element, config) {
                    config = JSON.parse(config);
                    if (config.slider) {
                        if (!element.hasClass('slick-initialized')) {
                            var responsive = config.responsive;
                            element.slick({
                                slidesToShow: config.slidesToShow,
                                autoplay: config.autoplay,
                                dots: config.dots,
                                arrows: config.arrows,
                                rows: config.rows,
                                infinite: config.infinite,
                                speed: config.speed,
                                vertical: config.vertical,
                                autoplaySpeed: config.autoplaySpeed,
                                slidesToScroll: 1,
                                responsive:
                                    [
                                        {'breakpoint': 1201, 'settings': {'slidesToShow': responsive[1201]}},
                                        {'breakpoint': 1200, 'settings': {'slidesToShow': responsive[1200]}},
                                        {'breakpoint': 992, 'settings': {'slidesToShow': responsive[992]}},
                                        {'breakpoint': 769, 'settings': {'slidesToShow': responsive[769]}},
                                        {'breakpoint': 641, 'settings': {'slidesToShow': responsive[641]}},
                                        {'breakpoint': 481, 'settings': {'slidesToShow': responsive[481]}},
                                        {'breakpoint': 361, 'settings': {'slidesToShow': responsive[361]}},
                                        {'breakpoint': 1, 'settings': {'slidesToShow': responsive[1]}}
                                    ]
                            });
                        }
                    }
                }
            }
            methods.configSlider(element,config);
        }
    })(jQuery);
});
