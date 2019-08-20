(function ($) {

	"use strict";

	$(function() {

		/**
		 * submenu indicator & fade animation
		 */
		var icon_dir = 'right';
		if (g7.rtl) {
			icon_dir = 'left';
		}
		$('#mainmenu > li:has(ul) > a').addClass('parent').append('<i class="fa fa-angle-down"></i>');
		$('#mainmenu ul li:has(ul) > a').addClass('parent').append('<i class="fa fa-angle-' + icon_dir + '"></i>');
		$('#mainmenu li').hover(function() {
			$(this).children('ul').stop().fadeIn(100);
		}, function() {
			$(this).children('ul').stop().fadeOut('fast');
		});

		/**
		 * add select menu for mainmenu
		 */
		if ($.fn.mobileMenu) {
			$('#mainmenu').mobileMenu({
				defaultText: g7.navigate_text,
				className: 'mainmenu'
			});
		}

		/**
		 * add masonry layout
		 */
		var mcontainer = $('.masonry-container');
		var masonry_rtl = false;
		if (g7.rtl) {
			masonry_rtl = true;
		}
		mcontainer.each(function() {
			var mc = $(this);
			mc.imagesLoaded(function() {
				mc.masonry({
					itemSelector: 'article',
					isAnimated: true,
					isOriginLeft: !masonry_rtl
				});
			})
		});

		/**
		 * add prettyPhoto call if plugin included
		 */
		if ($.fn.prettyPhoto) {
			$("a[data-rel^='prettyPhoto']").prettyPhoto({
				theme: 'pp_default',
				social_tools: false,
				hook: 'data-rel'
			});
		}

		/**
		 * contact widget validation and submit action
		 */
		$('[name="contact_name"], [name="contact_email"], [name="contact_message"]').keyup(function() {
			if ($(this).val() != '') {
				$(this).removeClass('err');
			}
		});
		$('.widget_g7_contact form').submit(function(e) {
			e.preventDefault();
			var f = $(this);
			var loading = f.find('.loading');
			var contact_msg = f.prev('.contact-msg');
			var contact_name = f.find('[name="contact_name"]');
			var contact_email = f.find('[name="contact_email"]');
			var contact_message = f.find('[name="contact_message"]');
			loading.show();
			contact_msg.html('');
			$.ajax({
				type: 'POST',
				url: g7.ajaxurl,
				data: $(this).serialize(),
				datatype: 'json',
				timeout: 30000,
				error: function() {
					loading.hide();
				},
				success: function (response) {
					loading.hide();
					switch (response.status) {
						case 1:
							contact_msg.html(response.msg);
							f.hide();
							break;
						case 2:
							contact_msg.html(response.msg);
							break;
						case 3:
							if (typeof response.error.name != 'undefined') {
								contact_name.addClass('err');
							}
							if (typeof response.error.email != 'undefined') {
								contact_email.addClass('err');
							}
							if (typeof response.error.message != 'undefined') {
								contact_message.addClass('err');
							}
							if (typeof response.error.body != 'undefined') {
								contact_msg.html(response.error.body);
								f.hide();
							}
							break;
					}
				}
			});
			return false;
		});

		/**
		 * add fitvids for video inside content and widgets
		 */
		$('.entry-content, .widget').fitVids();

	});

	/**
	 * add flex slider call if plugin included
	 */
	if ($.fn.flexslider) {
		$(window).load(function() {
			$('.flexslider').flexslider({
				animation: g7.slider_animation,
				slideshowSpeed: parseInt(g7.slider_slideshowSpeed),
				animationSpeed: parseInt(g7.slider_animationSpeed),
				pauseOnHover: g7.slider_pauseOnHover,
				directionNav: true
			});
		});
	}

})(jQuery);
