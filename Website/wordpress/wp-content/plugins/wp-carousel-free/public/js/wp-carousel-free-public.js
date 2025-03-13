(function ($) {
    'use strict';

    jQuery('body').find('.wpcp-carousel-section.wpcp-standard').each(function () {

        var carousel_id = $(this).attr('id');
        var _this = $(this);
        var wpcpSwiperData = $('#' + carousel_id).data('swiper');

        if (wpcpSwiperData.effect == 'flip') {
            /**
            * Apply fade effect to slides
            * @param {number} slidesToShow - Number of slides to show
            */
            function fade_effect(slidesToShow) {
                var fade_items = $(`#${carousel_id} .swiper-wrapper >.single-item-fade`);
                // Group slides.
                for (var i = 0; i < fade_items.length; i += slidesToShow) {
                    fade_items.slice(i, i + slidesToShow).wrapAll('<div class="swiper-slide"><div class="swiper-slide-kenburn"></div></div>');
                }
                // Fix fade last item small issue if row or column does not fill
                $(`#${carousel_id} .swiper-slide-kenburn`).each(function () {
                    var empty_items = slidesToShow - $(this).find('.single-item-fade').length
                    if (empty_items > 0) {
                        for (let i = 0; i < empty_items; i++) {
                            $(this).append(`<div class="single-item-fade" style="width:${100 / slidesToShow}%;"></div>`);
                        }
                    }
                });
                $(fade_items).css('width', `${100 / slidesToShow}%`);

                // $carousel_items = $(`#${carousel_id}`).find('.single-item-fade').length;
            }
            // Apply fade effect based on screen size.
            if ($(window).width() > wpcpSwiperData.responsive.desktop) {
                fade_effect(wpcpSwiperData.slidesToShow.lg_desktop);
            } else if ($(window).width() > wpcpSwiperData.responsive.laptop) {
                fade_effect(wpcpSwiperData.slidesToShow.desktop);
            } else if ($(window).width() > wpcpSwiperData.responsive.tablet) {
                fade_effect(wpcpSwiperData.slidesToShow.laptop);
            } else if ($(window).width() > wpcpSwiperData.responsive.mobile) {
                fade_effect(wpcpSwiperData.slidesToShow.tablet);
            } else if ($(window).width() > 0) {
                fade_effect(wpcpSwiperData.slidesToShow.mobile);
            }
            wpcpSwiperData.slidesToShow.mobile = 1;
            wpcpSwiperData.slidesToShow.tablet = 1;
            wpcpSwiperData.slidesToShow.desktop = 1;
            wpcpSwiperData.slidesToShow.laptop = 1;
            wpcpSwiperData.slidesToShow.lg_desktop = 1;
        }
        var wpcpSwiper = new Swiper('#' + carousel_id + ':not(.swiper-initialized, .swiper-container-initialized)', {
            // Optional parameters
            autoplay: wpcpSwiperData.autoplay ? ({
                delay: wpcpSwiperData.autoplaySpeed, disableOnInteraction: false,
            }) : false,
            speed: wpcpSwiperData.speed,
            effect: wpcpSwiperData.effect,
            centeredSlides: wpcpSwiperData.centerMode,
            // slidesPerGroup: 1,
            slidesPerView: wpcpSwiperData.slidesToShow.mobile,
            simulateTouch: wpcpSwiperData.draggable,
            loop: wpcpSwiperData.infinite,
            allowTouchMove: wpcpSwiperData.swipe,
            spaceBetween: wpcpSwiperData.spaceBetween,
            freeMode: wpcpSwiperData.freeMode,
            grabCursor: true,
            preloadImages: ('false' !== wpcpSwiperData.lazyLoad) ? true : false,
            lazy: {
                loadPrevNext: ('false' !== wpcpSwiperData.lazyLoad) ? true : false,
                loadPrevNextAmount: 1
            },

            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 480px
                [wpcpSwiperData.responsive.mobile]: {
                    slidesPerView: wpcpSwiperData.slidesToShow.tablet,
                },
                // when window width is >= 736px
                [wpcpSwiperData.responsive.tablet]: {
                    slidesPerView: wpcpSwiperData.slidesToShow.laptop,
                },
                // when window width is >= 980px
                [wpcpSwiperData.responsive.laptop]: {
                    slidesPerView: wpcpSwiperData.slidesToShow.desktop,
                },
                [wpcpSwiperData.responsive.desktop]: {
                    slidesPerView: wpcpSwiperData.slidesToShow.lg_desktop,
                }
            },

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            a11y: wpcpSwiperData.accessibility ? ({
                prevSlideMessage: 'Previous slide',
                nextSlideMessage: 'Next slide',
            }) : false,

            // Navigation arrows
            navigation: {
                nextEl: '#' + carousel_id + ' .swiper-button-next',
                prevEl: '#' + carousel_id + ' .swiper-button-prev',
            },
        });

        // On hover stop.
        if (wpcpSwiperData.pauseOnHover && wpcpSwiperData.autoplay) {
            $('#' + carousel_id).on({
                mouseenter: function () {
                    wpcpSwiper.autoplay.stop();
                },
                mouseleave: function () {
                    wpcpSwiper.autoplay.start();
                }
            });
        }
    });

    jQuery(document).find('.wpcp-carousel-wrapper').addClass('wpcp-loaded');
})(jQuery);