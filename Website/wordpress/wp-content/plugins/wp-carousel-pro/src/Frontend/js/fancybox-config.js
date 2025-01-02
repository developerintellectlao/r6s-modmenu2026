jQuery(document).ready(function ($) {
	$('.wpcp-carousel-section').each(function () {
		var carousel_id = jQuery(this).attr('id');
		swiperData = $('#' + carousel_id).data('swiper'),
			carousel_type = jQuery(this).data('carousel_typ'),
			infobar = jQuery(this).data('infobar'),
			thumb = jQuery(this).data('thumbs'),
			protect_image = jQuery(this).data('protect_image'),
			autoplay = jQuery(this).data('autoplay'),
			loop = jQuery(this).data('loop'),
			speed = jQuery(this).data('speed'),
			sliding_effect = jQuery(this).data('sliding_effect'),
			open_close = jQuery(this).data('open_close'),
			outside  = jQuery(this).data('outside'),
			keyboard = jQuery(this).data('keyboard');

		infiniteLoop = '';
		if (swiperData && swiperData.infinite !== false) {
			infiniteLoop = swiperData.infinite;
		}
		// var slick = jQuery(this).data('slick');
		if (open_close == 'zoom') {
			open_close = 'zoom-in-out';
		}

		var clickOutside = (outside == true) ? 'close' : false;
		if (carousel_type == 'image-carousel') {
			var class_carousel = '.wpcp-image-carousel';
		}
		if (carousel_type == 'video-carousel') {
			var class_carousel = '.wpcp-video-carousel';
		}
		if (carousel_type == 'mix-content') {
			var class_carousel = '.wpcp-mix-carousel';
		}

		if ($('#' + carousel_id + '.wpcp-standard').length || $('#' + carousel_id + '.wpcpro-thumbnail-slider').length || infiniteLoop) {
			var selector = '#' + carousel_id + '.wpcp-carousel-section .swiper-slide:not(.swiper-slide-duplicate) [data-fancybox="wpcp_view"]';
		} else {
			var selector = '#' + carousel_id + '.wpcp-carousel-section div:not(.bx-clone) .wpcp-single-item [data-fancybox="wpcp_view"]';
		}
		$().fancybox({
			selector: selector,
			backFocus: false,
			margin: [44, 0],
			baseClass: carousel_id + ' wpcp-fancybox-wrapper',
			slideShow: {
				autoStart: autoplay,
				speed: speed
			},
			loop: loop,
			animationEffect: open_close,
			animationDuration: 366,
			transitionEffect: sliding_effect,
			transitionDuration: 366,
			thumbs: {
				autoStart: thumb,
				axis: 'x'
			},
			infobar: infobar,
			protect: protect_image,
			keyboard: keyboard,
			clickSlide: clickOutside,
			// Clicked on the background (backdrop) element.
			clickOutside: clickOutside,
			caption: function (instance, item) {
				var caption     = $(this).data('caption') || '';
				var description = $(this).data('desc') || '';
				return (caption.length || description.length ? '<div class="wpcp_image_details"><div class="wpcp_img_caption">' + $('<div>').text(caption).html() + '</div> <div class="wpcp_desc">' + $('<div>').text(description).html() + '</div> </div><br />' : '');
			},
			hash: false,
			beforeShow: function (instance, slide) {
				$(".wpcp-fancybox-wrapper .fancybox-caption").addClass('none');
				$(".wpcp-fancybox-wrapper + div#elementor-lightbox-slideshow-single-img").css('display', 'none');
				// var itemSlug = slide.opts.$orig.attr("data-item-slug");
				// if (itemSlug && sp_wpcp_vars.wpcp_slug_in_address_bar) {
				// 	window.location.hash = itemSlug;
				// }
			},
			afterClose: function () {
				history.replaceState(null, null, ' ');
			},
			beforeClose: function () {
				history.replaceState(null, null, ' ');
			},
			afterShow: function () {
				$('.wpcp-fancybox-wrapper .fancybox-button--play').attr('title', sp_wpcp_vars.fancybox_tooltip_i18n.start_slideshow);
				$('.wpcp-fancybox-wrapper .fancybox-button--zoom').attr('title', sp_wpcp_vars.fancybox_tooltip_i18n.zoom);
				$('.wpcp-fancybox-wrapper .fancybox-button--fsenter').attr('title', sp_wpcp_vars.fancybox_tooltip_i18n.full_screen);
				$('.wpcp-fancybox-wrapper .fancybox-button--share').attr('title', sp_wpcp_vars.fancybox_tooltip_i18n.share);
				$('.wpcp-fancybox-wrapper .fancybox-button--download').attr('title', sp_wpcp_vars.fancybox_tooltip_i18n.download);
				$('.wpcp-fancybox-wrapper .fancybox-button--thumbs').attr('title', sp_wpcp_vars.fancybox_tooltip_i18n.thumbnails);
				$('.wpcp-fancybox-wrapper .fancybox-button--close').attr('title', sp_wpcp_vars.fancybox_tooltip_i18n.close);

				$(".wpcp-fancybox-wrapper ~ div#elementor-lightbox-slideshow-single-img").css('display', 'none');
				setTimeout(function () {
					$('.wpcp-fancybox-wrapper .fancybox-caption').removeClass('none');
					$('body').find(selector).each(function (i) {
						var _this = $(this),
							imageUrl = _this.data('wpc_url');
						if (imageUrl) {
							$('.' + carousel_id + '.wpcp-fancybox-wrapper .fancybox-thumbs .fancybox-thumbs__list a:nth(' + i + ')').css("background-image", "url(" + imageUrl + ")");
						}
					});

					var captionLength = $(".wpcp-fancybox-wrapper .fancybox-caption__body").text().length;
					if (150 < captionLength) {
						$(".wpcp-fancybox-wrapper .fancybox-caption").css('width', '80%')
					}

				}, 100);
				// Download lightbox popup images.
				$('.wpcp-fancybox-wrapper').on('click', '[data-fancybox-download]', function () {
					var a = $("<a class='remove-downloaded-element'>")
						.attr("href", this.href)
						.attr("download", "")
						.appendTo("body");
					a[0].click();
					$("a.remove-downloaded-element").remove();
				});
			},

			btnTpl: {
				arrowLeft:
					'<button data-fancybox-prev class="fancybox-button fancybox-button--arrow_left" title="{{PREV}}">' +
					'<div class="wpcp-fancybox-nav-arrow"><i class="fa fa-chevron-left"></i></div>' +
					"</button>",
				arrowRight:
					'<button data-fancybox-next class="fancybox-button fancybox-button--arrow_right" title="{{NEXT}}">' +
					'<div class="wpcp-fancybox-nav-arrow"><i class="fa fa-chevron-right"></i></div>' +
					"</button>",
			},

			// Image and Video url share with social link .
			share: {
				url: function (instance, item) {
					if (item.type === 'inline' && item.contentType === 'video') {
						return item.$content.find('source:first').attr('src');
					}
					return item.src;
				}
			},
			// Images look sharper on retina displays.
			afterLoad: function (instance, current) {
				var sharpe_img = jQuery('#' + carousel_id + '.wpcp-carousel-section').data('l_box_img_sharpe');
				if (sharpe_img) {
					var pixelRatio = window.devicePixelRatio || 1;
					if (pixelRatio > 1.5) {
						current.width = current.width / pixelRatio;
						current.height = current.height / pixelRatio;
					}
				}
				$('.fancybox-button, .fancybox-thumbs .fancybox-thumbs__list a').click(function (e) {
					e.preventDefault();
					setTimeout(() => {
						$('.fancybox-slide.fancybox-slide--video').not('.fancybox-slide--current').trigger("onReset");
					}, 300);
				});
			}
		})

		$('#' + carousel_id + '.wpcp-carousel-section:not(.wpcp-thumbnail-slider)').on('click', '.swiper-slide-duplicate,.bx-clone', function (e) {
			$(selector)
				.eq(($(e.currentTarget).attr("data-swiper-slide-index") || 0) % $(selector).length)
				.trigger("click", {
					$trigger: $(this)
				});
			return false;
		});
		// Fancybox doesn't support Dailymotion by default. This code helps to embed Dailymotion video in Fancybox popup.
		if ($(document).find('#' + carousel_id + ' .wpcp-slide-image').children('.wcp-video').length >= 1) {
			$.fancybox.defaults.media.dailymotion = {
				matcher: /dailymotion.com\/video\/(.*)\/?(.*)/,
				params: {
					autoplay: 1,
				},
				type: 'iframe',
				url: '//www.dailymotion.com/embed/video/$1'
			};
		}
		$(document).on('click', '#' + carousel_id + ' .wcp-light-box-caption', function (e) {
			e.preventDefault();
			var current_product = $(this).parents(".wpcp-single-item");
			$(current_product).find('[data-fancybox="wpcp_view"]').trigger('click');
		})
	})
	function wpcp_openBoxfromUrl() {
		var currentHash = window.location.hash;
		currentHash = currentHash.replace("#", "");
		if (currentHash !== "") {
			$("[data-item-slug='" + currentHash + "']").eq(0).trigger("click");
		}
	}
	setTimeout(() => {
		wpcp_openBoxfromUrl()
	}, 300);
});
