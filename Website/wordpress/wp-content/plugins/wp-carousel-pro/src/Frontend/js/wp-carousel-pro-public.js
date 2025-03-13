(function ($) {
    'use strict';
    jQuery(document).ready(function () {
        function SwiperSlide(selector, options) {
            if (parseInt(sp_wpcp_vars.wpcp_swiper_js) === 1) {
                return new WPCPSwiper(selector, options);
            } else {
                return new Swiper(selector, options);
            }
        }
        // Utility function to get arrow class based on type.
        function getArrowClasses(type) {
            let arrowClasses = {
                angle: { left: 'wpcp-icon-angle-left', right: 'wpcp-icon-angle-right' },
                chevron_open_big: { left: 'wpcp-icon-left-open-big', right: 'wpcp-icon-right-open-big' },
                right_open: { left: 'wpcp-icon-left-open', right: 'wpcp-icon-right-open' },
                chevron: { left: 'wpcp-icon-left-open-2', right: 'wpcp-icon-right-open-1' },
                right_open_3: { left: 'wpcp-icon-left-open-4', right: 'wpcp-icon-right-open-3' },
                right_open_outline: { left: 'wpcp-icon-left-open-outline', right: 'wpcp-icon-right-open-outline' },
                arrow: { left: 'wpcp-icon-left', right: 'wpcp-icon-right' },
                triangle: { left: 'wpcp-icon-arrow-triangle-left', right: 'wpcp-icon-arrow-triangle-right' }
            };
            return arrowClasses[type] || arrowClasses.arrow;
        }

        jQuery('body').find('.wpcp-carousel-section.wpcp-standard:not(.swiper-initialized)').each(function () {
            var carousel_id = $(this).attr('id'),
                _this = $(this),
                carousel_wrapper_id = _this.parents('.wpcp-carousel-wrapper').attr('id'),
                arrowClasses = getArrowClasses(_this.data('arrowtype')),
                slider_type = _this.parents('.wpcp-carousel-wrapper').data('slider-type'),
                shaders_type = _this.parents('.wpcp-carousel-wrapper').data('shaders-type');
            // Swiper data attr.
            var wpcpSwiperData = $('#' + carousel_id).data('swiper');
            var carousel_items = $('#' + carousel_id).find('.swiper-slide:not(.swiper-slide-duplicate)').length;
            // Carousel Pagination styles.
            var numberPagination = 'numbers' == wpcpSwiperData.pagination_type,
                paginationFraction = 'fraction' == wpcpSwiperData.pagination_type,
                dynamicBullets = 'dynamic' == wpcpSwiperData.pagination_type ? true : false,
                type = {},
                wpcpSwiper;
            if (paginationFraction) {
                type = {
                    type: 'fraction',
                }
            }
            var carouselWrapper = $('#' + carousel_wrapper_id + ' > .wpcp-carousel-section');
            // Check if it's already wrapped
            if (!carouselWrapper.parent().hasClass('wpcp-swiper-wrapper')) {
                carouselWrapper.wrapAll('<div class="wpcp-swiper-wrapper"></div>');
            }
            // Variable width script.
            if (wpcpSwiperData.variableWidth) {
                wpcpSwiperData.slidesToShow.mobile = wpcpSwiperData.slidesToShow.tablet = wpcpSwiperData.slidesToShow.laptop = wpcpSwiperData.slidesToShow.desktop = wpcpSwiperData.slidesToShow.lg_desktop = 'auto';
                setTimeout(function () {
                    $('#' + carousel_id + ' .wpcp-single-item').each(function () {
                        var img_width = $(this).find('.wpcp-slide-image img').width();
                        img_width = img_width ? img_width : '340';
                        $(this).find('.wpcp-all-captions').css('width', img_width);
                    })
                }, 100)
            }
            if (slider_type == 'fashion') {
                $('#' + carousel_id + ':not(.swiper-initialized) .swiper-slide .wpcp-slide-image').addClass('fashion-slider-scale');
                $('#' + carousel_id + ':not(.swiper-initialized)').wrapAll('<div class="fashion-slider"></div>')
                function createFashionSlider(el) {
                    const swiperEl = el.querySelector('.swiper');
                    let navigationLocked = false;
                    let transitionDisabled = false;
                    let frameId;
                    const disableTransitions = (el) => {
                        el.classList.add('fashion-slider-no-transition');
                        transitionDisabled = true;
                        cancelAnimationFrame(frameId);
                        frameId = requestAnimationFrame(() => {
                            el.classList.remove('fashion-slider-no-transition');
                            transitionDisabled = false;
                            navigationLocked = false;
                        });
                    };

                    let fashionSlider;

                    const onNextClick = () => {
                        if (!navigationLocked) {
                            fashionSlider.slideNext();
                        }
                    };
                    const onPrevClick = () => {
                        if (!navigationLocked) {
                            fashionSlider.slidePrev();
                        }
                    };

                    const initNavigation = (swiper) => {
                        // Use lock to control the button locking time without using the button component that comes with it.
                        swiper.el
                            .querySelector('.fashion-slider-button-next')
                            .addEventListener('click', onNextClick);
                        swiper.el
                            .querySelector('.fashion-slider-button-prev')
                            .addEventListener('click', onPrevClick);
                    };

                    const destroyNavigation = (swiper) => {
                        swiper.el
                            .querySelector('.fashion-slider-button-next')
                            .removeEventListener('click', onNextClick);
                        swiper.el
                            .querySelector('.fashion-slider-button-prev')
                            .removeEventListener('click', onPrevClick);
                    };

                    let loopFixed;

                    fashionSlider = new WPCPSwiper(swiperEl, {
                        autoplay: wpcpSwiperData.autoplay ? ({ delay: wpcpSwiperData.autoplaySpeed, disableOnInteraction: false, }) : false,
                        speed: wpcpSwiperData.speed,
                        allowTouchMove: false, // no touch swiping
                        parallax: true, // text parallax
                        loop: wpcpSwiperData.infinite, // infinite loop.
                        on: {
                            loopFix() {
                                loopFixed = false;
                            },
                            transitionStart(swiper) {
                                const isLoop = swiper.params.loop;
                                if (isLoop) {
                                    if (loopFixed) {
                                        return;
                                    }
                                    if (!loopFixed) {
                                        loopFixed = true;
                                    }
                                }

                                // eslint-disable-next-line
                                const { slides, previousIndex, activeIndex, el } = swiper;
                                if (!transitionDisabled) navigationLocked = true; // lock navigation buttons

                                const activeSlide = slides[activeIndex];
                                const previousSlide = slides[previousIndex];
                                const previousImageScale = previousSlide.querySelector(
                                    '.fashion-slider-scale',
                                ); // image wrapper
                                const previousImage = previousSlide.querySelector('img'); // current image
                                const activeImage = activeSlide.querySelector('img'); // next image
                                const direction = activeIndex - previousIndex;
                                const bgColor = activeSlide.getAttribute('data-slide-bg-color');
                                el.style['background-color'] = bgColor; // background color animation

                                previousImageScale.style.transform = 'scale(0.6)';
                                previousImage.style.transitionDuration = '1000ms';
                                previousImage.style.transform = 'scale(1.2)'; // image scaling parallax
                                // const previousSlideTitle = previousSlide.querySelector(
                                // 	'.fashion-slider-title-text',
                                // );
                                // previousSlideTitle.style.transition = '1000ms';
                                // previousSlideTitle.style.color = 'rgba(255,255,255,0)'; // text transparency animation

                                const onTransitionEnd = (e) => {
                                    if (e.target !== previousImage) return;
                                    previousImage.removeEventListener('transitionend', onTransitionEnd);
                                    activeImage.style.transitionDuration = '1300ms';
                                    activeImage.style.transform = 'translate3d(0, 0, 0) scale(1.2)'; // image shift parallax
                                    previousImage.style.transitionDuration = '1300ms';
                                    previousImage.style.transform = `translate3d(${60 * direction
                                        }%, 0, 0)  scale(1.2)`;
                                };
                                previousImage.addEventListener('transitionend', onTransitionEnd);
                            },
                            transitionEnd(swiper) {
                                const { slides, activeIndex, el } = swiper;
                                const activeSlide = slides[activeIndex];
                                const activeImage = activeSlide.querySelector('img');
                                const isLoop = swiper.params.loop;

                                activeSlide.querySelector('.fashion-slider-scale').style.transform =
                                    'scale(1)';
                                activeImage.style.transitionDuration = '1000ms';
                                activeImage.style.transform = 'scale(1)';

                                // const activeSlideTitle = activeSlide.querySelector(
                                // 	'.fashion-slider-title-text',
                                // );
                                // activeSlideTitle.style.transition = '1000ms';
                                // activeSlideTitle.style.color = 'rgba(255,255,255,1)'; // text transparency animation

                                const onTransitionEnd = (e) => {
                                    if (e.target !== activeImage) return;
                                    activeImage.removeEventListener('transitionend', onTransitionEnd);
                                    navigationLocked = false;
                                };
                                activeImage.addEventListener('transitionend', onTransitionEnd);
                                if (!isLoop) {
                                    // First and last, disable button
                                    if (activeIndex === 0) {
                                        el.querySelector('.fashion-slider-button-prev').classList.add(
                                            'fashion-slider-button-disabled',
                                        );
                                    } else {
                                        el.querySelector('.fashion-slider-button-prev').classList.remove(
                                            'fashion-slider-button-disabled',
                                        );
                                    }

                                    if (activeIndex === slides.length - 1) {
                                        el.querySelector('.fashion-slider-button-next').classList.add(
                                            'fashion-slider-button-disabled',
                                        );
                                    } else {
                                        el.querySelector('.fashion-slider-button-next').classList.remove(
                                            'fashion-slider-button-disabled',
                                        );
                                    }
                                }
                            },
                            beforeInit(swiper) {
                                // eslint-disable-next-line
                                const { el } = swiper;
                                // disable initial transition
                                disableTransitions(el);
                            },
                            init(swiper) {
                                // Set initial slide bg color
                                // eslint-disable-next-line
                                const { slides, activeIndex, el } = swiper;
                                // set current bg color
                                const bgColor = slides[activeIndex].getAttribute('data-slide-bg-color');
                                el.style['background-color'] = bgColor; // background color animation
                                // trigger the transitionEnd event once during initialization
                                swiper.emit('transitionEnd');
                                // init navigation
                                initNavigation(swiper);
                            },
                            resize(swiper) {
                                disableTransitions(swiper.el);
                            },
                            destroy(swiper) {
                                destroyNavigation(swiper);
                            },
                        },
                    });

                    return fashionSlider;
                }
              const sliderEl = document.querySelector('#' + carousel_wrapper_id + ' .fashion-slider');
                createFashionSlider(sliderEl);
            } else if (slider_type == 'spring') {
                $('#' + carousel_id + ':not(.swiper-initialized)').wrapAll('<div class="spring-slider"></div>');

                function createSpringSlider(el, extendParams = {}) {
                    // main swiper el
                    const swiperEl = el.querySelector('.swiper');

                    let previousProgress = 0;
                    let isTouched = false;

                    const resetDelay = (swiper) => {
                        swiper.slides.forEach((slideEl) => {
                            slideEl.style.transitionDelay = '0ms';
                        });
                    };

                    // init main swiper
                    wpcpSwiper = new WPCPSwiper(swiperEl, {
                        effect: 'creative',
                        speed: wpcpSwiperData.speed,
                        loop: wpcpSwiperData.infinite,
                        autoplay: wpcpSwiperData.autoplay ? ({ delay: wpcpSwiperData.autoplaySpeed, disableOnInteraction: false, }) : false,
                        followFinger: false,
                        ...extendParams,

                        creativeEffect: {
                            limitProgress: 100,
                            prev: {
                                translate: ['-100%', 0, 0],
                            },
                            next: {
                                translate: ['100%', 0, 0],
                            },
                        },
                        on: {
                            ...(extendParams.on || {}),
                            touchStart(...args) {
                                isTouched = true;
                                if (extendParams.on && extendParams.on.touchStart) {
                                    extendParams.on.touchStart(...args);
                                }
                            },
                            touchEnd(...args) {
                                isTouched = false;
                                if (extendParams.on && extendParams.on.touchStart) {
                                    extendParams.on.touchEnd(...args);
                                }
                            },
                            // eslint-disable-next-line
                            progress(swiper, progress) {
                                if (isTouched) return;
                                if (extendParams.on && extendParams.on.progress) {
                                    extendParams.on.progress(swiper, progress);
                                }
                                const direction = swiper.progress > previousProgress ? 'next' : 'prev';
                                previousProgress = swiper.progress;
                                const delay = swiper.params.speed / 16;
                                const visibleIndexes = swiper.visibleSlidesIndexes;
                                const firstIndex = visibleIndexes[0];
                                const lastIndex = visibleIndexes[visibleIndexes.length - 1];
                                const setDelay = (slideEl, slideIndex) => {
                                    if (direction === 'next' && slideIndex >= firstIndex) {
                                        slideEl.style.transitionDelay = `${(slideIndex - firstIndex + 1) * delay
                                            }ms`;
                                    } else if (direction === 'prev' && slideIndex <= lastIndex + 1) {
                                        slideEl.style.transitionDelay = `${(lastIndex - slideIndex + 1) * delay
                                            }ms`;
                                    } else {
                                        slideEl.style.transitionDelay = `${0}ms`;
                                    }
                                };
                                swiper.slides.forEach((slideEl, slideIndex) => {
                                    if (swiper.animating) {
                                        slideEl.style.transitionDelay = '0ms';
                                        requestAnimationFrame(() => {
                                            setDelay(slideEl, slideIndex);
                                        });
                                    } else {
                                        setDelay(slideEl, slideIndex);
                                    }
                                });
                            },
                        },
                    });

                    wpcpSwiper.on('transitionEnd resize touchStart', () => {
                        resetDelay(wpcpSwiper);
                    });
                    return wpcpSwiper;
                }
                const sliderEl = document.querySelector('#' + carousel_wrapper_id + ' .spring-slider');
                createSpringSlider(sliderEl, {
                    slidesPerView: wpcpSwiperData.slidesToShow.mobile,
                    spaceBetween: 0,
                    //  centeredSlides: wpcpSwiperData.centerMode,
                    autoplay: wpcpSwiperData.autoplay ? ({ delay: wpcpSwiperData.autoplaySpeed, disableOnInteraction: false, }) : false,
                    speed: wpcpSwiperData.speed,
                    autoHeight: (wpcpSwiperData.rows.mobile === 1) ? wpcpSwiperData.adaptiveHeight : false,
                    simulateTouch: wpcpSwiperData.draggable,
                    freeMode: wpcpSwiperData.freeMode,
                    preloadImages: ('false' !== wpcpSwiperData.lazyLoad) ? true : false,
                    keyboard: {
                        enabled: wpcpSwiperData.carousel_accessibility,
                    },
                    updateOnWindowResize: true,
                    grabCursor: true,
                    observer: true,
                    observeParents: true,
                    mousewheel: wpcpSwiperData.swipeToSlide,
                    // Responsive breakpoints.
                    breakpoints: {
                        // when window width is >= 480px
                        [wpcpSwiperData.responsive.mobile]: {
                            slidesPerView: wpcpSwiperData.slidesToShow.tablet,
                            slidesPerGroup: wpcpSwiperData.slidesToScroll.tablet,
                            grid: {
                                rows: wpcpSwiperData.rows.tablet,
                            },
                            loop: (wpcpSwiperData.rows.tablet === 1) ? wpcpSwiperData.infinite : false,
                            autoHeight: (wpcpSwiperData.rows.tablet === 1) ? wpcpSwiperData.adaptiveHeight : false,
                        },
                        // when window width is >= 736px
                        [wpcpSwiperData.responsive.tablet]: {
                            slidesPerView: wpcpSwiperData.slidesToShow.laptop,
                            slidesPerGroup: wpcpSwiperData.slidesToScroll.laptop,
                            grid: {
                                rows: wpcpSwiperData.rows.laptop,
                            },
                            loop: (wpcpSwiperData.rows.laptop === 1) ? wpcpSwiperData.infinite : false,
                            autoHeight: (wpcpSwiperData.rows.laptop === 1) ? wpcpSwiperData.adaptiveHeight : false,
                        },
                        // when window width is >= 980px
                        [wpcpSwiperData.responsive.laptop]: {
                            slidesPerView: wpcpSwiperData.slidesToShow.desktop,
                            slidesPerGroup: wpcpSwiperData.slidesToScroll.desktop,
                            grid: {
                                rows: wpcpSwiperData.rows.desktop,
                            },
                            loop: (wpcpSwiperData.rows.desktop === 1) ? wpcpSwiperData.infinite : false,
                            autoHeight: (wpcpSwiperData.rows.desktop === 1) ? wpcpSwiperData.adaptiveHeight : false,
                        },
                        [wpcpSwiperData.responsive.desktop]: {
                            slidesPerView: wpcpSwiperData.slidesToShow.lg_desktop,
                            slidesPerGroup: wpcpSwiperData.slidesToScroll.lg_desktop,
                            grid: {
                                rows: wpcpSwiperData.rows.lg_desktop,
                            },
                            loop: (wpcpSwiperData.rows.lg_desktop === 1) ? wpcpSwiperData.infinite : false,
                            autoHeight: (wpcpSwiperData.rows.lg_desktop === 1) ? wpcpSwiperData.adaptiveHeight : false,
                        }
                    },
                    // If we need pagination
                    pagination: {
                        el: '#' + carousel_id + ' .wpcp-swiper-dots',
                        clickable: true,
                        dynamicBullets: dynamicBullets,
                        renderBullet: numberPagination ? function (index, className) {
                            return '<span class="wpcp-number-pagination ' + className + '">' + (index + 1) + "</span>";
                        } : '',
                        ...type,
                        renderFraction: paginationFraction ? function (currentClass, totalClass) {
                            return '<div class="wpcp-swiper-pagination-fraction"><span class="' + currentClass + '"></span>' +
                                ' / ' +
                                '<span class="' + totalClass + '"></span></div>';
                        } : '',
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
                    scrollbar: {
                        el: '#' + carousel_id + ' .wpcp-pagination-scrollbar',
                        draggable: true,
                    }
                });

            } else if (slider_type == 'triple') {
                $('#' + carousel_id + ':not(.swiper-initialized)').wrapAll('<div class="triple-slider"></div>');
                function createTripleSlider(el) {
                    const swiperEl = el.querySelector('.swiper');
                    // create prev duplicate swiper
                    const swiperPrevEl = swiperEl.cloneNode(true);
                    swiperPrevEl.classList.add('triple-slider-prev');
                    el.insertBefore(swiperPrevEl, swiperEl);
                    const swiperPrevSlides = swiperPrevEl.querySelectorAll('.swiper-slide');
                    const swiperPrevLastSlideEl = swiperPrevSlides[swiperPrevSlides.length - 1];
                    swiperPrevEl
                        .querySelector('.swiper-wrapper')
                        .insertBefore(swiperPrevLastSlideEl, swiperPrevSlides[0]);

                    // Create next duplicate swiper
                    const swiperNextEl = swiperEl.cloneNode(true);
                    swiperNextEl.classList.add('triple-slider-next');
                    el.appendChild(swiperNextEl);
                    const swiperNextSlides = swiperNextEl.querySelectorAll('.swiper-slide');
                    const swiperNextFirstSlideEl = swiperNextSlides[0];
                    swiperNextEl
                        .querySelector('.swiper-wrapper')
                        .appendChild(swiperNextFirstSlideEl);

                    // Add "main" class
                    swiperEl.classList.add('triple-slider-main');
                    // Common params for all swiperJS.
                    const commonParams = {
                        speed: wpcpSwiperData.speed,
                        loop: wpcpSwiperData.infinite,
                        parallax: true,
                        // Enable autoplay if specified in the config.
                        autoplay: wpcpSwiperData.autoplay ? ({ delay: wpcpSwiperData.autoplaySpeed, disableOnInteraction: false, }) : false,
                    };
                    let tripleMainSwiper;
                    // Init prev swiper
                    const triplePrevSwiper = new WPCPSwiper(swiperPrevEl, {
                        ...commonParams,
                        allowTouchMove: false,
                        on: {
                            click() {
                                tripleMainSwiper.slidePrev();
                            },
                        },
                    });
                    // Init next swiper
                    const tripleNextSwiper = new WPCPSwiper(swiperNextEl, {
                        ...commonParams,
                        allowTouchMove: false,
                        on: {
                            click() {
                                tripleMainSwiper.slideNext();
                            },
                        },
                    });
                    // Init main swiper
                    tripleMainSwiper = new WPCPSwiper(swiperEl, {
                        ...commonParams,
                        grabCursor: false,
                        controller: {
                            control: [triplePrevSwiper, tripleNextSwiper],
                        },
                        simulateTouch: false,
                        on: {
                            destroy() {
                                // Destroy side Swipers on main swiper destroy.
                                triplePrevSwiper.destroy();
                                tripleNextSwiper.destroy();
                            },
                        },
                    });

                    return tripleMainSwiper;
                }
                const sliderEl = document.querySelector('#' + carousel_wrapper_id + ' .triple-slider');
                createTripleSlider(sliderEl);
                // Disable lightbox.
                $('.triple-slider-next .swiper-slide .wcp-light-box .wpcp_icon_overlay, .triple-slider-prev .swiper-slide .wcp-light-box .wpcp_icon_overlay').remove();
                $('.triple-slider-next .swiper-slide .wcp-light-box, .triple-slider-prev .swiper-slide .wcp-light-box').removeClass('wcp-light-box').removeAttr('data-fancybox').on('click', function (e) {
                    e.preventDefault();
                });
            } else {
                /**
                 * Initialize a Swiper instance with the given configuration.
                 * @param {string} carousel_id - The ID of the carousel element.
                 * @param {string} slider_type - The type of slider to initialize.
                 * @param {Object} wpcpSwiperData - Configuration data for the Swiper instance.
                 * @returns {Swiper} - The initialized Swiper instance.
                 */
                function initSwiper(carousel_id, slider_type, wpcpSwiperData) {
                    // Check if the device is mobile based on the responsive breakpoint
                    const isMobile = wpcpSwiperData.responsive.mobile;
                    // Define the base configuration for all slider types.
                    let baseConfig = {
                        // Enable autoplay if specified in the config.
                        autoplay: wpcpSwiperData.autoplay ? { delay: wpcpSwiperData.autoplaySpeed, disableOnInteraction: false } : false,
                        speed: wpcpSwiperData.speed,
                        loop: wpcpSwiperData.infinite,
                        // Enable adaptive height only for single row mobile layouts.
                        autoHeight: (wpcpSwiperData.rows.mobile === 1) ? wpcpSwiperData.adaptiveHeight : false,
                        simulateTouch: wpcpSwiperData.draggable,
                        freeMode: wpcpSwiperData.freeMode,
                        // Preload images if lazy loading is enabled.
                        preloadImages: wpcpSwiperData.lazyLoad !== 'false',
                        keyboard: { enabled: wpcpSwiperData.carousel_accessibility },
                        grid: {
                            rows: wpcpSwiperData.rows.mobile,
                        },
                        lazy: {
                            loadPrevNext: wpcpSwiperData.lazyLoad !== 'false',
                            loadPrevNextAmount: 1
                        },
                        updateOnWindowResize: true,
                        grabCursor: true,
                        observer: true,
                        observeParents: true,
                        mousewheel: wpcpSwiperData.swipeToSlide,
                        // Generate breakpoints for responsive design
                        breakpoints: generateBreakpoints(wpcpSwiperData),
                        // Configure pagination
                        pagination: generatePagination(carousel_id, wpcpSwiperData),
                        // Configure navigation buttons
                        navigation: {
                            nextEl: `#${carousel_id} .swiper-button-next`,
                            prevEl: `#${carousel_id} .swiper-button-prev`,
                        },
                        // Configure scrollbar
                        scrollbar: {
                            el: `#${carousel_id} .wpcp-pagination-scrollbar`,
                            draggable: true,
                        }
                    };
                    // Initialize additional configuration object.
                    let additionalConfig = {};

                    // Configure additional settings based on slider type
                    if (slider_type == 'panorama') {
                        additionalConfig = {
                            effect: 'panorama',
                            panoramaEffect: { depth: 150, rotate: 45 },
                            modules: [EffectPanorama],
                            // Generate breakpoints for responsive design
                            breakpoints: generatePanoramaBreakpoints(wpcpSwiperData),
                        };
                    } else if (slider_type == '3d-carousel') {
                        additionalConfig = {
                            effect: 'carousel',
                            carouselEffect: {
                                opacityStep: 0.33,
                                scaleStep: 0.1,
                                sideSlides: 2
                            },
                            modules: [EffectCarousel],
                            loop: wpcpSwiperData.infinite,
                            loopAdditionalSlides: 1,
                            slidesPerView: 'auto',
                            grabCursor: true,
                        };
                    } else if (slider_type == 'fade' || slider_type == 'cube' || slider_type == 'flip' || slider_type == 'kenburn') {
                        /**
                         * Apply fade effect to slides
                         * @param {number} slidesToShow - Number of slides to show
                        */
                        function fade_effect(slidesToShow) {
                            var fade_items = $(`#${carousel_id} .swiper-wrapper >.single-item-fade`);
                            // Group slides
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

                            carousel_items = $(`#${carousel_id}`).find('.single-item-fade').length;
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
                        additionalConfig = {
                            effect: wpcpSwiperData.effect,
                            fadeEffect: { crossFade: true },
                            breakpoints: Array(),
                            // Configure pagination
                           // pagination: Array(),

                        };
                    } else if (slider_type == 'shutters') {
                        // Add class for shutters effect
                        $(`#${carousel_id}:not(.swiper-initialized) .swiper-slide`).find('img').addClass('swiper-shutters-image');
                        additionalConfig = {
                            modules: [EffectShutters],
                            effect: 'shutters',
                            shuttersEffect: {
                                split: 5,
                            },
                            breakpoints: Array(),
                        }
                    } else if (slider_type == 'slicer') {
                        // Add class for slicer effect
                        $(`#${carousel_id}:not(.swiper-initialized) .swiper-slide`).find('img').addClass('swiper-slicer-image');
                        additionalConfig = {
                            modules: [EffectSlicer],
                            effect: 'slicer',
                            slicerEffect: {
                                split: 5,
                            },
                            direction: 'vertical',
                            breakpoints: Array(),

                        }
                    } else if (slider_type == 'shaders') {
                        $(`#${carousel_id}:not(.swiper-initialized) .swiper-slide .wpcp-all-captions`).addClass('swiper-slide-content');
                        $(`#${carousel_id}:not(.swiper-initialized) .swiper-slide`).find('img').addClass('swiper-gl-image');
                        additionalConfig = {
                            // Shaders configuration
                            modules: [WPCPSwiperGL],
                            effect: 'gl',
                            loop: wpcpSwiperData.infinite,
                            gl: {
                                shader: shaders_type,
                            },
                            breakpoints: Array(),
                            // Configure pagination.
                           // pagination: Array(),
                        }
                    } else {
                        // Default configuration for other slider types.
                        additionalConfig = {
                            direction: wpcpSwiperData.vertical ? 'vertical' : 'horizontal',
                            slidesPerGroup: wpcpSwiperData.slidesToScroll.mobile,
                            loop: wpcpSwiperData.rows.mobile === 1 ? wpcpSwiperData.infinite : false,
                            effect: wpcpSwiperData.effect,
                            fadeEffect: { crossFade: true },
                            slidesPerView: wpcpSwiperData.slidesToShow.mobile,
                            spaceBetween: wpcpSwiperData.spaceBetween,
                            centeredSlides: wpcpSwiperData.centerMode,
                        };
                    }


                    // Merge base configuration with additional configuration
                    const swiperConfig = { ...baseConfig, ...additionalConfig };
                    // Initialize and return Swiper instance
                    wpcpSwiper = new SwiperSlide(`#${carousel_id}:not(.swiper-initialized)`, swiperConfig);
                    if (slider_type == 'shaders') {
                        var wrapper_height = $(`#${carousel_id}`).height();
                        $(`#${carousel_id} .swiper-slide`).css('height', wrapper_height);
                    }
                }
                /**
                 * Generate panoroma breakpoints configuration for responsive design
                 * @param {Object} data - Swiper configuration data
                 * @returns {Object} - Breakpoints configuration
                 */
                function generatePanoramaBreakpoints(data) {
                    return {
                        [data.responsive.mobile]: {
                            slidesPerView: data.slidesToShow.tablet,
                            slidesPerGroup: data.slidesToScroll.tablet,
                            grid: { rows: data.rows.tablet },
                            loop: data.rows.tablet === 1 ? data.infinite : false,
                            panoramaEffect: {
                                rotate: 35,
                                depth: 150,
                            },
                        },
                        [data.responsive.tablet]: {
                            slidesPerView: data.slidesToShow.laptop,
                            slidesPerGroup: data.slidesToScroll.laptop,
                            grid: { rows: data.rows.laptop },
                            loop: data.rows.laptop === 1 ? data.infinite : false,
                            panoramaEffect: {
                                rotate: 30,
                                depth: 150,
                            },
                        },
                        [data.responsive.laptop]: {
                            slidesPerView: data.slidesToShow.desktop,
                            slidesPerGroup: data.slidesToScroll.desktop,
                            grid: { rows: data.rows.desktop },
                            loop: data.rows.desktop === 1 ? data.infinite : false,
                            panoramaEffect: {
                                rotate: 30,
                                depth: 200,
                            },

                        },
                        [data.responsive.desktop]: {
                            slidesPerView: data.slidesToShow.lg_desktop,
                            slidesPerGroup: data.slidesToScroll.lg_desktop,
                            grid: { rows: data.rows.lg_desktop },
                            loop: data.rows.lg_desktop === 1 ? data.infinite : false,
                            panoramaEffect: {
                                rotate: 20,
                                depth: 100,
                            },
                        }
                    };
                }
                /**
                 * Generate breakpoints configuration for responsive design
                 * @param {Object} data - Swiper configuration data
                 * @returns {Object} - Breakpoints configuration
                 */
                function generateBreakpoints(data) {
                    return {
                        [data.responsive.mobile]: {
                            slidesPerView: data.slidesToShow.tablet,
                            slidesPerGroup: data.slidesToScroll.tablet,
                            grid: { rows: data.rows.tablet },
                            loop: data.rows.tablet === 1 ? data.infinite : false,
                            autoHeight: data.rows.tablet === 1 ? data.adaptiveHeight : false,
                        },
                        [data.responsive.tablet]: {
                            slidesPerView: data.slidesToShow.laptop,
                            slidesPerGroup: data.slidesToScroll.laptop,
                            grid: { rows: data.rows.laptop },
                            loop: data.rows.laptop === 1 ? data.infinite : false,
                            autoHeight: data.rows.laptop === 1 ? data.adaptiveHeight : false,
                        },
                        [data.responsive.laptop]: {
                            slidesPerView: data.slidesToShow.desktop,
                            slidesPerGroup: data.slidesToScroll.desktop,
                            grid: { rows: data.rows.desktop },
                            loop: data.rows.desktop === 1 ? data.infinite : false,
                            autoHeight: data.rows.desktop === 1 ? data.adaptiveHeight : false,
                        },
                        [data.responsive.desktop]: {
                            slidesPerView: data.slidesToShow.lg_desktop,
                            slidesPerGroup: data.slidesToScroll.lg_desktop,
                            grid: { rows: data.rows.lg_desktop },
                            loop: data.rows.lg_desktop === 1 ? data.infinite : false,
                            autoHeight: data.rows.lg_desktop === 1 ? data.adaptiveHeight : false,
                        }
                    };
                }

                /**
                 * Generate pagination configuration
                 * @param {string} carousel_id - The ID of the carousel element
                 * @param {Object} data - Swiper configuration data
                 * @returns {Object} - Pagination configuration
                 */
                function generatePagination(carousel_id, data) {
                    return {
                        el: '#' + carousel_id + ' .wpcp-swiper-dots',
                        clickable: true,
                        dynamicBullets: dynamicBullets,
                        renderBullet: numberPagination ? function (index, className) {
                            return '<span class="wpcp-number-pagination ' + className + '">' + (index + 1) + "</span>";
                        } : '',
                        ...type,
                        renderFraction: paginationFraction ? function (currentClass, totalClass) {
                            return '<div class="wpcp-swiper-pagination-fraction"><span class="' + currentClass + '"></span>' +
                                ' / ' +
                                '<span class="' + totalClass + '"></span></div>';
                        } : '',
                    }
                }
                // Initialize Swiper.
                initSwiper(carousel_id, slider_type, wpcpSwiperData);
            }
            if (slider_type != 'fashion' && slider_type != 'triple') {
                // Centered Slides mode.
                function centerModePadding(padding) {
                    if (wpcpSwiperData.centerMode) {
                        $('#' + carousel_id).css({
                            paddingRight: padding + 'px',
                            paddingLeft: padding + 'px'
                        });
                    }
                }
                // Vertical layout.
                function vertical_layout(slidesToShow) {
                    if (wpcpSwiperData.vertical) {
                        var verticalMargin = (1 != slidesToShow) ? slidesToShow * wpcpSwiperData.spaceBetween : 0;
                        var verticalSlideToShow = slidesToShow;
                        var verticalItemHeight = '';
                        $('#' + carousel_id + ' .wpcp-single-item').each(function () {
                            if ($(this).height() > verticalItemHeight) {
                                verticalItemHeight = $(this).outerHeight();
                            }
                        })
                        verticalItemHeight = (verticalItemHeight * verticalSlideToShow) + verticalMargin;
                        $('#' + carousel_id).css('height', verticalItemHeight + 'px');
                    }
                }
                if (wpcpSwiperData.vertical || wpcpSwiperData.centerMode) {
                    vertical_layout(wpcpSwiperData.slidesToShow.mobile);
                    centerModePadding(wpcpSwiperData.centerPadding.mobile);
                    if ($(window).width() > wpcpSwiperData.responsive.mobile) {
                        vertical_layout(wpcpSwiperData.slidesToShow.tablet);
                        centerModePadding(wpcpSwiperData.centerPadding.tablet);
                    }
                    if ($(window).width() > wpcpSwiperData.responsive.tablet) {
                        vertical_layout(wpcpSwiperData.slidesToShow.laptop);
                        centerModePadding(wpcpSwiperData.centerPadding.laptop);
                    }
                    if ($(window).width() > wpcpSwiperData.responsive.laptop) {
                        vertical_layout(wpcpSwiperData.slidesToShow.desktop);
                        centerModePadding(wpcpSwiperData.centerPadding.desktop);
                    }
                    if ($(window).width() > wpcpSwiperData.responsive.desktop) {
                        vertical_layout(wpcpSwiperData.slidesToShow.lg_desktop);
                        centerModePadding(wpcpSwiperData.centerPadding.lg_desktop);
                    }
                }
                // On hover stop.
                if (wpcpSwiperData.pauseOnHover && wpcpSwiperData.autoplay) {
                    $('#' + carousel_id).on({
                        mouseenter: function () {
                            if (wpcpSwiper.autoplay) {
                                wpcpSwiper.autoplay.stop()
                            }
                        },
                        mouseleave: function () {
                            if (wpcpSwiper.autoplay) {
                                wpcpSwiper.autoplay.start();
                            }
                        }
                    });
                }
                $('#' + carousel_id + ' .swiper-button-prev i').addClass(arrowClasses.left);
                $('#' + carousel_id + ' .swiper-button-next i').addClass(arrowClasses.right);
                $('#' + carousel_id + '.wpcp_img_protection img').on("contextmenu", function (e) {
                    return false;
                });
            }
        });

        // Same height option.
        function same_height_item() {
            jQuery('body').find('.wpcp-carousel-section:not(.wpcp-thumbnail-slider)').each(function () {
                var carousel_id = $(this).attr('id');
                var parent_attr = $('#' + carousel_id + '.wpcp_same_height');
                if (parent_attr.length > 0) {
                    var itemWrapper = $('#' + carousel_id + ' .wpcp-single-item'),
                        ImageWrapper = $('#' + carousel_id + ' .wpcp-single-item .wpcp-slide-image'),
                        DetailsWrapper = $('#' + carousel_id + ' .wpcp-single-item .wpcp-all-captions');
                    setTimeout(function () {
                        setSameHeight(itemWrapper);
                        setSameHeight(ImageWrapper);
                        setSameHeight(DetailsWrapper);
                    }, 100)
                }
            })
        }
        same_height_item();
        // Same height option.
        function setSameHeight(productImage) {
            var maxHeight = 0;
            // Loop all productImage and check height, if the height bigger than max then save it.
            for (var i = 0; i < productImage.length; i++) {
                if (maxHeight < $(productImage[i]).outerHeight()) {
                    maxHeight = $(productImage[i]).outerHeight();
                }
            }
            // Set ALL productImage to this height.
            for (var i = 0; i < productImage.length; i++) {
                $(productImage[i]).outerHeight(maxHeight);
            }
        }

        // Thumbnails slider.
        jQuery('body').find('.wpcp-carousel-section.wpcp-thumbnail-slider').each(function () {
            var carousel_id = $(this).attr('id');
            var _this = $(this),
                arrowClasses = getArrowClasses(_this.data('arrowtype'));
            // Swiper data attr.
            var wpcpSwiperData = $('#' + carousel_id).data('swiper');
            var carouselWrapper = $('#' + carousel_id + ' > .wpcpro-gallery-slider');
            // Check if it's already wrapped
            if (!carouselWrapper.parent().hasClass('wpcp-swiper-wrapper')) {
                carouselWrapper.wrapAll('<div class="wpcp-swiper-wrapper"></div>');
            }

            // Gallery Thumbnails slider init.
            var wpcpThumbs = SwiperSlide('#' + carousel_id + ' .wpcpro-gallery-thumbs', {
                centeredSlides: false,
                centeredSlidesBounds: true,
                watchOverflow: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
                direction: wpcpSwiperData.orientation,
                slidesPerGroup: 1,
                loop: wpcpSwiperData.infinite,
                slidesPerView: wpcpSwiperData.slidesToShow.mobile,
                spaceBetween: wpcpSwiperData.spaceBetween,
                // centeredSlides: true,
                observer: true,
                autoplay: false,
                autoHeight: false,
                simulateTouch: wpcpSwiperData.draggable,
                freeMode: wpcpSwiperData.freeMode,
                lazy: wpcpSwiperData.lazyLoad,
                slideToClickedSlide: true,
                grabCursor: true,
                updateOnWindowResize: true,
                mousewheel: wpcpSwiperData.swipeToSlide,
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
            });//auto, static, dynamic %;
            var wpcpMainSlider = SwiperSlide('#' + carousel_id + ' .wpcpro-gallery-slider', {
                watchOverflow: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
                preventInteractionOnTransition: true,
                direction: wpcpSwiperData.slider_orientation,
                slidesPerView: 1,
                slidesPerGroup: 1,
                loop: wpcpSwiperData.infinite,
                autoplay: wpcpSwiperData.autoplay ? ({ delay: wpcpSwiperData.autoplaySpeed, disableOnInteraction: false, }) : false,
                speed: wpcpSwiperData.speed,
                observer: true,
                effect: wpcpSwiperData.effect,
                fadeEffect: { crossFade: true },
                simulateTouch: wpcpSwiperData.draggable,
                freeMode: wpcpSwiperData.freeMode,
                autoHeight: wpcpSwiperData.adaptiveHeight,
                mousewheel: wpcpSwiperData.swipeToSlide,
                slideToClickedSlide: true,
                keyboard: {
                    enabled: wpcpSwiperData.carousel_accessibility,
                },
                // If we need pagination
                pagination: {
                    el: '#' + carousel_id + ' .swiper-pagination',
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
                thumbs: {
                    swiper: wpcpThumbs,
                },
            });

            // On hover stop.
            if (wpcpSwiperData.pauseOnHover && wpcpSwiperData.autoplay) {
                $('#' + carousel_id).on({
                    mouseenter: function () {
                        wpcpMainSlider.autoplay.stop();
                    },
                    mouseleave: function () {
                        wpcpMainSlider.autoplay.start();
                    }
                });
            }
            $('#' + carousel_id + ' .swiper-button-prev i').addClass(arrowClasses.left);
            $('#' + carousel_id + ' .swiper-button-next i').addClass(arrowClasses.right);
            setTimeout(() => {
                if (wpcpSwiperData.orientation == 'vertical' || wpcpSwiperData.orientation == 'horizontal') {
                    var maxHeight = 0;
                    $('#' + carousel_id + ' .wpcpro-gallery-slider .swiper-slide img').each(function () {
                        if ($(this).height() > maxHeight) {
                            maxHeight = $(this).innerHeight();
                        }
                    });
                    $('#' + carousel_id + ' .wpcpro-gallery-slider').css('maxHeight', maxHeight);
                    $('#' + carousel_id + ' .wpcpro-gallery-thumbs').css('maxHeight', maxHeight);
                }
                var $gallery_height = $('#' + carousel_id + ' .wpcpro-gallery-slider').outerHeight();
                $gallery_height = ($gallery_height / 2) - 25;
                $('#' + carousel_id + '.wpcp-thumbnail-slider.wpcp-carousel-section.nav-vertical-center .wpcp-next-button, #' + carousel_id + '.wpcp-thumbnail-slider.wpcp-carousel-section.nav-vertical-center .wpcp-prev-button').hide();
                $('#' + carousel_id + '.wpcp-thumbnail-slider.wpcp-carousel-section.nav-vertical-center .wpcp-next-button, #' + carousel_id + '.wpcp-thumbnail-slider.wpcp-carousel-section.nav-vertical-center .wpcp-prev-button').css('top', $gallery_height).show();
            }, 300);

            $('#' + carousel_id + '.wpcp_img_protection img').on("contextmenu", function (e) {
                return false;
            });
        });
        // This function added for wpcp-Lazyload.
        function wpcp_lazyload_init() {
            var $is_find = $('.wpcp-slide-image img').hasClass('wpcp-lazyload');
            if ($is_find) {
                $("img.wpcp-lazyload").wpcp_lazyload({ effect: "fadeIn", effectTime: 2000 }).removeClass('wpcp-lazyload').addClass('wpcp-lazyloaded');
            }
        }
        jQuery('body').find('.wpcp-carousel-wrapper.wpcp-gallery').each(function () {
            var carousel_id = $(this).attr('id');
            var _this = $(this);

            function initJustifiedGallery() {
                if (jQuery('#' + carousel_id).hasClass('wpcp-justified')) {
                    var rowheight = jQuery('#' + carousel_id + '.wpcp-justified').find('.wpcp-carousel-section').data('rowheight');
                    var horizontalheight = jQuery('#' + carousel_id + '.wpcp-justified').find('.wpcp-carousel-section').data('horizontalgap');
                    var verticalheight = jQuery('#' + carousel_id + '.wpcp-justified').find('.wpcp-carousel-section').data('verticalgap');
                    verticalheight = parseInt(verticalheight);
                    rowheight = parseInt(rowheight);
                    horizontalheight = parseInt(horizontalheight);
                    jQuery('#' + carousel_id + '.wpcp-justified').find('.wpcpro-row').fjGallery({
                        itemSelector: '.wpcp-item-wrapper',
                        imageSelector: '.wpcp-item-wrapper img',
                        gutter: {
                            horizontal: horizontalheight,
                            vertical: verticalheight,
                        },
                        rowHeight: rowheight,
                    });
                }
            }
            initJustifiedGallery();

            // Masonry init.
            if (jQuery('#' + carousel_id).find('.wpcpro-row').hasClass('wpcp-masonry')) {
                var $grid = jQuery('#' + carousel_id).find('.wpcp-masonry').masonry();
                $grid.imagesLoaded().progress(function () {
                    $grid.masonry('layout');
                });
                setTimeout(() => {
                    $grid.masonry('layout');
                }, 500);
                $grid.masonry('layout');
            }
            function WPCPShowNextResult(data) {
                var $content = jQuery(data);
                jQuery('#' + carousel_id).find('.wpcp-masonry').append($content).masonry('appended', $content);
                setTimeout(function () {
                    jQuery('#' + carousel_id).find('.wpcp-masonry').masonry('reloadItems');
                    jQuery('#' + carousel_id).find('.wpcp-masonry').masonry();
                }, 300);
            }
            $('#' + carousel_id + '.wpcp_img_protection img').on("contextmenu", function (e) {
                return false;
            });
            // Image, content, video, mix-content carousel ajax number pagination.
            $('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a').on('click', function (e) {
                e.preventDefault();
                var $_this = $(this);
                var wpc_page = $(this).data('page');
                if ($_this.hasClass('prev')) {
                    wpc_page = $('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a.wcppage.current').data('page');
                    wpc_page = wpc_page - 1;
                }
                if ($_this.hasClass('next')) {
                    wpc_page = $('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a.wcppage.current').data('page');
                    wpc_page = wpc_page + 1;
                }
                wpc_page = wpc_page - 1; // Page offset.
                var ajax_url = $(this).parents('.content-ajax-pagination').data('url');
                var sid = $(this).parents('.content-ajax-pagination').data('id');
                var wpc_total = $(this).parents('.content-ajax-pagination').data('total');
                var nonce = $(this).parents('.content-ajax-pagination').data('nonce');
                $.ajax({
                    type: 'POST',
                    url: ajax_url,
                    data: {
                        id: sid,
                        action: 'wpcp_ajax_image_load',
                        nonce: nonce,
                        wpc_page: wpc_page,
                    },
                    success: function (response) {
                        var data = $(response);
                        jQuery('#' + carousel_id).find('.wpcpro-row').html(data);
                        if (jQuery('#' + carousel_id).find('.wpcpro-row').hasClass('wpcp-masonry')) {
                            if (jQuery('#' + carousel_id).find('.wpcpro-row').hasClass('wpcp-masonry')) {
                                jQuery('#' + carousel_id).find('.wpcp-masonry').masonry("destroy");
                                var $grid = jQuery('#' + carousel_id).find('.wpcp-masonry').masonry({
                                    //  itemSelector: '',
                                    isInitLayout: false
                                });
                                $grid.masonry('layout');
                                $grid.imagesLoaded().progress(function () {
                                    $grid.masonry('layout');
                                });
                            }
                        } else {
                            jQuery('#' + carousel_id).find('.wpcpro-row').html(data);
                            if (jQuery('#' + carousel_id).hasClass('wpcp-justified')) {
                                jQuery('#' + carousel_id + '.wpcp-justified').find('.wpcpro-row').fjGallery('destroy');
                                initJustifiedGallery();
                            }
                        }
                        wpc_page++;
                        $('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a').removeClass('current');
                        $('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a[data-page=' + wpc_page + ']').addClass('current');
                        if ($('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a.current').data('page') == 1) {
                            $('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a.prev').addClass('current');
                        }
                        if ($('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a.current').data('page') == wpc_total) {
                            $('#' + carousel_id + ' .content-ajax-pagination .wpcpro-post-pagination-number a.next').addClass('current');
                        }
                        same_height_item();

                    }
                })
            });
            // Image, content, video, mix-content carousel load more.
            if (!$('#' + carousel_id).find('.wpcpro-load-more').hasClass('wpcpro-load-more-button-initialized')) {
                $('#' + carousel_id).find('.wpcpro-load-more').addClass('wpcpro-load-more-button-initialized');
                jQuery('#' + carousel_id).find('.wpcpro-load-more button').on('click', function (e) {
                    var nonce = $(this).data('nonce');
                    var sid = $(this).data('id');
                    var ajax_url = $(this).data('url');
                    var wpc_page = $(this).data('page');
                    var wpc_total = $(this).data('total');
                    var end_text = $(this).parents('.wpcpro-load-more').data('text');
                    var post_have = $(this).parents('.wpcpro-load-more').data('items-have');
                    var per_views = $(this).parents('.wpcpro-load-more').data('per-page');
                    jQuery('#' + carousel_id).find('.wpcpro-load-more button').attr('wpcp-processing', 1);
                    jQuery('#' + carousel_id).find('.wpcpro-load-more').eq(0).before(
                        '<div class="wpcpro-infinite-scroll-loader"><svg width="44" height="44" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" stroke="#444"><g fill="none" fill-rule="evenodd" stroke-width="2"><circle cx="22" cy="22" r="1"><animate attributeName="r" begin="0s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite" /><animate attributeName="stroke-opacity" begin="0s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite" /> </circle> <circle cx="22" cy="22" r="1"> <animate attributeName="r" begin="-0.9s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite" /><animate attributeName="stroke-opacity" begin="-0.9s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite"/></circle></g></svg></div>'
                    )
                    $.ajax({
                        type: 'POST',
                        url: ajax_url,
                        data: {
                            id: sid,
                            action: 'wpcp_ajax_image_load',
                            nonce: nonce,
                            wpc_page: wpc_page,
                        },
                        success: function (response) {
                            if (jQuery('#' + carousel_id).find('.wpcpro-row').hasClass('wpcp-masonry')) {
                                WPCPShowNextResult(response);
                            } else {
                                jQuery('#' + carousel_id).find('.wpcpro-row').append(response);
                                if (jQuery('#' + carousel_id).hasClass('wpcp-justified')) {
                                    jQuery('#' + carousel_id + '.wpcp-justified').find('.wpcpro-row').fjGallery('destroy');
                                    initJustifiedGallery();
                                }
                            }
                            wpc_page++;
                            var items = post_have - (per_views * (wpc_page - 1));
                            jQuery('#' + carousel_id).find('.wpcpro-load-more   span.load-more-count').html(' (' + items + ')');
                            var viewed_items = per_views * wpc_page;
                            if ((post_have + per_views) > viewed_items) {
                                $(".notice-load-more-post", '#' + carousel_id).find('.load-more-items-have').html(viewed_items);
                            } else {
                                $(".notice-load-more-post", '#' + carousel_id).css('display', 'none');
                            }
                            jQuery('#' + carousel_id).find('.wpcpro-load-more button').data('page', wpc_page);
                            if (wpc_page >= wpc_total) {
                                jQuery('#' + carousel_id).find('.wpcpro-load-more').html(end_text);
                            }
                            jQuery('#' + carousel_id).find('.wpcpro-load-more button').attr('wpcp-processing', 0);
                            jQuery('#' + carousel_id).find('.wpcpro-infinite-scroll-loader').remove();
                            wpcp_lazyload_init();
                            same_height_item();
                        }
                    })
                });
            }
            // Image, content, video, mix-content carousel infinite scroll.
            if (jQuery('#' + carousel_id).find('.wpcpro-load-more').data('pagi') == 'infinite_scroll') {
                var bufferBefore = Math.abs(20);
                jQuery('#' + carousel_id).find('.wpcpro-load-more').hide();
                $(window).scroll(function () {
                    if (jQuery('#' + carousel_id).find('.wpcpro-row').length) {
                        var TopAndContent = jQuery('#' + carousel_id).find('.wpcpro-row').offset().top + jQuery('#' + carousel_id).find('.wpcpro-row').outerHeight();
                        var areaLeft = TopAndContent - $(window).scrollTop()
                        if (areaLeft - bufferBefore < $(window).height()) {
                            if (jQuery('#' + carousel_id).find('.wpcpro-load-more button').attr('wpcp-processing') == 0) {
                                jQuery('#' + carousel_id).find('.wpcpro-load-more button').trigger('click');
                            }
                        }
                    }
                })
            }
            // Post and product carousel ajax number.
            if (jQuery('#' + carousel_id).find('.wpcpro-post-pagination').data('pagi') == 'ajax_number') {
                jQuery('#' + carousel_id).on('click', '.wpcpro-post-pagination:not(.content-ajax-pagination)  a.page-numbers', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var link = $(this).attr('href');
                    jQuery('#' + carousel_id).load(link + " #" + carousel_id + " > *", function () {
                        jQuery('#' + carousel_id).find('.wpcp-carousel-preloader').animate({ opacity: 0, zIndex: -99 }, 300);
                        if (jQuery('#' + carousel_id).find('.wpcpro-row').hasClass('wpcp-masonry')) {
                            var $grid = jQuery('#' + carousel_id).find('.wpcp-masonry').masonry({
                                //  itemSelector: '',
                                isInitLayout: false
                            });
                            $grid.masonry('layout');
                            $grid.imagesLoaded().progress(function () {
                                $grid.masonry('layout');
                            });
                        }
                        wpcp_lazyload_init();
                        same_height_item();
                    });
                });
            }

            var wpc_page = 1;
            // Post and product carousel ajax load more.
            jQuery('#' + carousel_id).on('click', '.wpcpro-post-pagination .wpcpro-post-load-more button', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var total_page = $(this).data('total');
                //  jQuery('#' + carousel_id).find('.wpcpro-load-more button').attr('wpcp-processing', 1);
                var end_text = $(this).parents('.wpcpro-post-load-more').data('text');
                var post_have = $(this).parents('.wpcpro-post-load-more').data('items-have');
                var per_views = $(this).parents('.wpcpro-post-load-more').data('per-page');
                if (jQuery('#' + carousel_id).find('.wpcpro-post-pagination .wpcpro-post-load-more button').attr('wpcp-processing') == 0) {
                    jQuery('#' + carousel_id).find('.wpcpro-post-pagination .wpcpro-post-load-more button').attr('wpcp-processing', 1);
                    jQuery('#' + carousel_id).find('.wpcpro-post-pagination .wpcpro-post-load-more').eq(0).before(
                        '<div class="wpcpro-infinite-scroll-loader"><svg width="44" height="44" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" stroke="#444"><g fill="none" fill-rule="evenodd" stroke-width="2"><circle cx="22" cy="22" r="1"><animate attributeName="r" begin="0s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite" /> <animate attributeName="stroke-opacity" begin="0s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite" /> </circle> <circle cx="22" cy="22" r="1"> <animate attributeName="r" begin="-0.9s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite" /> <animate attributeName="stroke-opacity" begin="-0.9s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite"/></circle></g></svg></div>'
                    );
                    wpc_page++;
                    var shorcode_id = $(this).data('id');
                    var link = jQuery("#" + carousel_id + " .wpcpro-post-pagination .page-numbers[href*='paged" + shorcode_id + "=" + wpc_page + "']").attr('href');
                    setTimeout(function () {
                        jQuery.get(link, function (data) {
                            var elements = jQuery(data).find("#" + carousel_id + " .wpcpro-row > *");
                            if (jQuery('#' + carousel_id).find('.wpcpro-row').hasClass('wpcp-masonry')) {
                                $grid.masonry().append(elements).masonry('appended', elements);
                            } else {
                                jQuery('#' + carousel_id).find('.wpcpro-row').append(elements);
                                // Re-init justified gallery.
                                if (jQuery('#' + carousel_id).hasClass('wpcp-justified')) {
                                    jQuery('#' + carousel_id + '.wpcp-justified').find('.wpcpro-row').fjGallery('destroy');
                                    initJustifiedGallery();
                                }
                            }
                            var items = post_have - (per_views * (wpc_page - 1));
                            jQuery('#' + carousel_id).find('.wpcpro-post-load-more span.load-more-count').html(' (' + items + ')');
                            var viewed_items = per_views * wpc_page;
                            if ((post_have + per_views) > viewed_items) {
                                $(".notice-load-more-post", '#' + carousel_id).find('.load-more-items-have').html(viewed_items);
                            } else {
                                $(".notice-load-more-post", '#' + carousel_id).css('display', 'none');
                            }
                            wpcp_lazyload_init();
                            same_height_item();
                            if (wpc_page >= total_page) {
                                jQuery('#' + carousel_id).find('.wpcpro-post-pagination .wpcpro-post-load-more').html(end_text);
                                // jQuery('#' + carousel_id).find('.wpcpro-post-pagination .wpcpro-post-load-more').remove();
                            }
                        });
                        jQuery('#' + carousel_id).find('.wpcpro-post-pagination .wpcpro-post-load-more button').attr('wpcp-processing', 0);
                        jQuery('#' + carousel_id).find('.wpcpro-infinite-scroll-loader').remove();

                    }, 1000);

                }
            });
            // Post and product carousel infinite scroll.
            if (jQuery('#' + carousel_id).find('.wpcpro-post-pagination').data('pagi') == 'infinite_scroll') {
                var bufferBefore = Math.abs(20);
                jQuery('#' + carousel_id).find('.wpcpro-post-pagination .wpcpro-post-load-more button').hide();
                $(window).scroll(function () {
                    if (jQuery('#' + carousel_id).find('.wpcpro-row').length) {
                        var TopAndContent = jQuery('#' + carousel_id).find('.wpcpro-row').offset().top + jQuery('#' + carousel_id).find('.wpcpro-row').outerHeight();
                        var areaLeft = TopAndContent - $(window).scrollTop()
                        if (areaLeft - bufferBefore < $(window).height()) {
                            if (jQuery('#' + carousel_id).find('.wpcpro-post-pagination:not(.content-ajax-pagination) .wpcpro-post-load-more button').attr('wpcp-processing') == 0) {
                                jQuery('#' + carousel_id).find('.wpcpro-post-pagination:not(.content-ajax-pagination) .wpcpro-post-load-more button').trigger('click');
                            }
                        }
                    }
                })
            }
        });
        wpcp_lazyload_init();

        jQuery('body').find('.wpcp-carousel-section:not(.wpcp-loaded)').addClass('wpcp-loaded');

        // Movable tooltip text on image
        jQuery('body').find('.wpcp-carousel-wrapper').each(function () {
            var carousel_id = $(this).attr('id'),
                moving_content = $('#' + carousel_id + ' .wpcp-carousel-section').hasClass('caption-on-moving'), // Moving alignment true or false
                carousel_layout = $('#' + carousel_id + ' .wpcp-standard').length < 1,
                layout_justified = $('#' + carousel_id + '.wpcp-justified').length < 1,
                layout_thumbnail_slider = $('#' + carousel_id + '.wpcpro-thumbnail-slider').length < 1;

            if (moving_content && carousel_layout && layout_justified && layout_thumbnail_slider) {
                $('#' + carousel_id).find('.wpcp-single-item').on('mousemove', function (e) {
                    var $toolTip = $(this).find('.wpcp-all-captions');

                    // Calculate the position relative to the current image.
                    let mousePosX = e.clientX, // Mouse pointer's X-coordinate
                        mousePosY = e.clientY; // Mouse pointer's Y-coordinate

                    // Update the tooltip's position
                    $toolTip.css({
                        top: (mousePosY + 20) + 'px',
                        left: (mousePosX + 20) + 'px',
                        zIndex: 999999,
                    });

                    // Show the tooltip for the current image.
                    $toolTip.fadeIn('slow');
                });

                // Hide the tooltip when the mouse leaves the current image
                $('#' + carousel_id).find('.wpcp-single-item').on('mouseleave', function () {
                    var $toolTip = $(this).find('.wpcp-all-captions');
                    $toolTip.fadeOut('slow');
                });
            }
        });
    });
})(jQuery);
