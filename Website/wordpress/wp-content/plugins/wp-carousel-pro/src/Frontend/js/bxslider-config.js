(function ($) {
	'use strict';
	jQuery('body').find('.wpcp-carousel-section.wpcp-ticker').each(function () {
		var carousel_id = $(this).attr('id');
		var carousel_offset = $(this).offset().left;
		var _this = $(this);
		if ('' !== carousel_id) {
			var ticker_items = $('#' + carousel_id).find('.wpcp-ticker').length;
			var speed = (_this.data('slide-width') * ticker_items * _this.data('speed')) / 1000;
			var carousel_width = ((_this.data('slide-width') * ticker_items) + (_this.data('slide-margin') * ticker_items));
			var width = $(window).width();
			var minSlides = _this.data('min-slides');
			var maxSlides = _this.data('max-slides');

			if (_this.data('mode') == 'vertical') {
				minSlides = maxSlides;
				if (width < 480) {
					minSlides = _this.data('min-slides');
					maxSlides = _this.data('min-slides');
				}
			}
				
			jQuery('#' + carousel_id).bxSlider({
				ticker: true,
				tickerHover: _this.data('hover-pause'),
				speed: speed,
				easing: null,
				slideWidth: _this.data('variable-width') ? 'auto' : _this.data('slide-width'),
				slideMargin: _this.data('slide-margin'),
				minSlides: minSlides,
				adaptiveHeight: false,
				maxSlides: maxSlides,
				moveSlides: _this.data('minslide'),
				shrinkItems: false,
				auto: false,
				autoDirection: _this.data('direction'),
				mode: _this.data('mode'),
				wrapperClass: 'sp-wpcp-wrapper',
			});

			if(_this.data('ticker-drag') && _this.data('mode') !== 'vertical') {
				let left = 0;
				let pageX = 0;
				let isDrag = false;

				jQuery('#' + carousel_id).on('mousedown touchstart', function(e) {
					left = $(this).position().left;
					pageX = e.originalEvent.clientX - carousel_offset;
					isDrag = true;
				})

				jQuery('#' + carousel_id).on('mousemove touchmove', function(e) {
					e.preventDefault();
					if(!isDrag) return;
					let clientX = e.originalEvent.clientX - carousel_offset;
					let left_value = Math.abs(left) - (clientX - pageX);
					left_value = (left_value > carousel_width) ? 0 : left_value;
					$(this).css('transform', `translate3d(-${left_value}px, 0px, 0px)`);

					$(this).addClass('wpcp-pause-ticker-lightbox');
				})

				jQuery('#' + carousel_id).on('mouseup mouseleave touchend touchcancel', function(e) {	
					if(!isDrag) return;
					isDrag = false;
					$(this).css('transform', `translate3d(-${('prev' === _this.data('direction')) && (Math.abs($(this).position().left) < 10) ? carousel_width : $(this).position().left }px, 0px, 0px)`);

					$(this).removeClass('wpcp-pause-ticker-lightbox');
				})
			}
		}
		if (_this.data('mode') == 'vertical') {
			jQuery('#' + carousel_id).parents('.sp-wpcp-wrapper').css("margin", "auto");
		}
		$('#' + carousel_id + '.wpcp_img_protection img').on("contextmenu", function (e) {
			return false;
		});
		jQuery('#' + carousel_id + ' .wpcp-single-item img').removeAttr('loading');
	});
})(jQuery);