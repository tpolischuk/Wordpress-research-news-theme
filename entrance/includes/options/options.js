jQuery(document).ready(function($) {

	var image_text_field;
	$('.upload-button').click(function() {
		image_text_field = $(this).prev('input[type=text]');
		tb_show('', 'media-upload.php?type=image&post_id=0&TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		image_url = $('img', html).attr('src');
		image_text_field.val(image_url);
		image_text_field.siblings('.upload-preview').html('<img src="' + image_url + '" />');
		tb_remove();
	}

	function check_hidden_options() {
		var cb = $('.folds');
		var next_option = cb.closest('tr').next();
		if (cb.is(':checked')) {
			next_option.fadeIn();
		} else {
			next_option.hide();
		}
	}

	$('.folds').click(check_hidden_options);
	check_hidden_options();

	$('.folds').each(function() {
		var cb = $(this);
		var next_option = cb.closest('tr').next();
	});

	$('.color-presets > div')
		.each(function() {
			var color = $(this).data('color');
			$(this).css('background-color', color);
		})
		.click(function() {
			var color = $(this).data('color');
			var td = $(this).closest('td');
			var picker = td.find('.picker');
			td.find('.color').val(color);
			var f = $.farbtastic(picker);
			f.setColor(color);
		});

	$('.color').each(function() {
		var input = $(this);
		var picker = input.next('.picker');
		picker.farbtastic(input);
		input.click(function() {
			picker.fadeIn();
			$(document).mousedown(function() {
				$(picker).fadeOut();
			});
		});
	});

	$('.g7-dragdrop').on('click', '.handlediv', function() {
		$(this).parent('.g7-item').toggleClass('closed');
	});

	$('.g7-dragdrop').sortable({
		update: function () {
			var sorted = $('.metabox-holder').sortable('serialize');
			$('#xdebug').text(sorted);
		}
	});

	$('.postbox-add').click(function(e) {
		e.preventDefault();
		var field_name = $(this).data('name');
		var field_title = $(this).data('title');
		var field_container = $(this).parent().siblings('.g7-dragdrop');
		var field_count = field_container.children('.postbox').length;
		var field_number = field_count + 1;
		var new_field_name = field_name + '_' + field_number;
		var new_field_title = field_title + ' ' + field_number;
		field_container
			.children('.postbox:last')
			.clone(true)
			.attr('id', new_field_name)
			.find('.hndle > span')
				.text(new_field_title)
				.end()
			.find('.inside input:first')
				.val(new_field_title)
				.end()
			.appendTo(field_container);
	});

	$('.postbox-delete').click(function(e) {
		e.preventDefault();
		$(this).closest('.postbox').remove();
	});

	$('.g7-item-add').click(function(e) {
		e.preventDefault();
		var field_id        = $(this).data('id');
		var field_title     = $(this).data('title');
		var field_container = $(this).parent().siblings('.g7-item-container');
		var field_count     = field_container.children('.g7-item').length;
		var field_number    = field_count + 1;
		var new_field_id    = field_id + '_' + field_number;
		field_container
			.children('.g7-item:last')
				.clone(true)
				.attr('id', new_field_id)
				.hide()
				.appendTo(field_container)
				.slideDown()
				.children('input')
					.val('')
					.focus()
					.end();
	});

	$('.g7-item-delete').click(function(e) {
		e.preventDefault();
		var item = $(this).closest('.g7-item');
		var field_count = $(this).closest('.g7-item-container').children('.g7-item').length;
		if (field_count > 1) {
			item
				.slideUp(341, function() {
					item.remove();
				});
		} else {
			item.children('input').val('').focus();
		}
	});

	//Tabs
	$('.use_tabs .nav-tab-wrapper > a:first')
		.addClass('nav-tab-active');

	$('.use_tabs #g7-options > div:first')
		.show()
		.siblings('div')
			.hide();

	$('.use_tabs .nav-tab-wrapper > a').click(function(e) {
		var contentLocation = $(this).attr('href');

		e.preventDefault();
		$(this)
			.addClass('nav-tab-active')
			.siblings()
				.removeClass('nav-tab-active');

		$(contentLocation)
			.show()
			.siblings('div')
				.hide();
	});

});