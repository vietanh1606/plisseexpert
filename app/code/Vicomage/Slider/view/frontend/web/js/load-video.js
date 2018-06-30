define([
        'jquery'
    ],
    function ($) {
        console.log('prepare loadVideo');
        return {
            vicomageVideo: function (img) {
                if (typeof img !== 'undefined') {
                    var url = img.attr('data-url');
                    var href = url;
                    var id,
                        type,
                        ampersandPosition,
                        vimeoRegex;

                    if (typeof href !== 'string') {
                        return href;
                    }
                    var a = document.createElement('a');

                    a.href = href;
                    href = a;
                    var self = this;
                    if (href.host.match(/youtube\.com/) && href.search) {
                        id = href.search.split('v=')[1];

                        if (id) {
                            id = self._getYoutubeId(id);
                            type = 'youtube';
                        }
                    } else if (href.host.match(/youtube\.com|youtu\.be/)) {
                        id = href.pathname.replace(/^\/(embed\/|v\/)?/, '').replace(/\/.*/, '');
                        type = 'youtube';

                    } else if (href.host.match(/vimeo\.com/)) {
                        type = 'vimeo';
                        vimeoRegex = new RegExp(['https?:\\/\\/(?:www\\.|player\\.)?vimeo.com\\/(?:channels\\/(?:\\w+\\/)',
                            '?|groups\\/([^\\/]*)\\/videos\\/|album\\/(\\d+)\\/video\\/|video\\/|)(\\d+)(?:$|\\/|\\?)'
                        ].join(''));
                        id = href.href.match(vimeoRegex)[3];
                    }

                    if ((!id || !type)) {
                        id = href.href;
                        type = 'custom';
                    }

                    id ? {
                        id: id, type: type, s: href.search.replace(/^\?/, '')
                    } : false;

                    var src= '';
                    if (id) {
                        if (type == 'youtube') {
                            src = 'https://' + href.host + '/embed/' + id + '?autoplay=1';
                        } else if (type == 'vimeo') {
                            src = 'https://player.vimeo.com/video/' + id + '?autoplay=1';
                        }

                    }
                    return src;

                }
            },

            /**
             * Get youtube ID
             * @param {String} srcid
             * @returns {{}}
             */
            _getYoutubeId : function (srcid) {
                if (srcid) {
                    ampersandPosition = srcid.indexOf('&');

                    if (ampersandPosition === -1) {
                        return srcid;
                    }

                    srcid = srcid.substring(0, ampersandPosition);
                }

                return srcid;
            },

            /**
             * Image click function
             * @param   img
             * @param   content
             * @param   width
             * @param   height
             * @return  string
             */
            imgClick: function () {
                var self = this;
                $('#img_video').on('click', function () {
                    var src = self.vicomageVideo($(this));
                    var width = $(this).attr('data-width');
                    var height = $(this).attr('data-height');
                    $(this).hide();
                    $('.vicomage-video-slider').append('<iframe id="iframe_video" src=' + src + ' width="' + width + '" height="' + height + '"></iframe>');
                });
            },

            /**
             * slider after change
             * @param   img
             * @param   slickSlider
             */
            afterChange: function () {
                $('.slick-slider').on('afterChange', function (event, slick, currentSlide) {
                    var element = $(this).find('div[data-slick-index="' + currentSlide + '"]');
                    var videoUrl = element.children('#img_video').attr('data-url');
                    if (!videoUrl) {
                        $(this).find('#iframe_video').remove();
                        $(this).find('#img_video').show();
                    }
                });
            }

        }
    });