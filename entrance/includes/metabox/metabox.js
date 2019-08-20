jQuery(function($) {

	function show_meta_boxes() {
		var page_template = $('#page_template').val();

		//console.log(g7mb);
		//console.log(g7mb2);

		$.each(g7mb, function(k, v) {
			$('#' + k).hide();
			if ($.inArray(page_template, v) > -1) {
				$('#' + k).show();
			} else if (v.length === 0) {
				$('#' + k).show();
			}
		});

		$.each(g7mb2, function(k, v) {
			if ($.inArray(page_template, v) > -1) {
				$('#' + k).hide();
			}
		});
	}
	show_meta_boxes();
	$('#page_template').change(show_meta_boxes);

	var page_layout = $('#_g7_layout');
	if (page_layout == 3) {
		//
	}

	$('.g7-dragdrop').on('click', '.widget-action', function(e) {
		e.preventDefault();
		$(this).closest('.widget').find('.widget-inside').slideToggle('fast');
	});

	$('.g7-dragdrop').on('click', '.widget-control-remove', function(e) {
		e.preventDefault();
		$(this).closest('.widget')
			.animate({height: 0, opacity: 0}, 'slow', function() {
				$(this).remove();
			});
	});

	function set_block_title(block) {
		var title = block.find('.g7-category-dropdown option:selected').text();
		var blocktitle = block.find('.blocktitle');
		if (blocktitle.val() == '') {
			block.find('.widget-title > h4').html(title);
			blocktitle.val(title);
		}
	}
	$('.g7-dragdrop').on('change', '.g7-category-dropdown', function() {
		var block = $(this).closest('.widget');
		set_block_title(block);
	});

	$('.g7-dragdrop').on('keyup', '.blocktitle', function(e) {
		e.preventDefault();
		var blocktitle = $(this).val();
		$(this).closest('.widget')
			.find('.widget-title > h4')
				.text(blocktitle);
	});

	$('.g7-dragdrop').sortable({
		handle: '.widget-top',
		placeholder: 'widget-placeholder',
		sort: function(event, ui) {
			// ui.helper.css({'top' : ui.position.top + $(window).scrollTop() + 'px'});
		}
	});

	$('.g7-add-item a').click(function(e) {
		e.preventDefault();
		var row_container = $(this).closest('.g7mb-input').find('.g7-dragdrop');
		$(this).closest('.g7mb-input')
			.find('.g7-dragdrop-item > .widget')
				.clone(false)
				.hide()
				.appendTo(row_container)
				.fadeIn()
				.find('.g7-colorpicker').wpColorPicker({
					palettes: ['#125', '#459', '#78b', '#ab0', '#de3', '#f0f']
				});
	});

	$('.g7-dragdrop .g7-colorpicker').wpColorPicker({
		palettes: ['#ef4423', '#fb6239', '#ff764c', '#fe9b00', '#9fc54e', '#3e93d2', '#00adef', '#2790b0', '#e15f4e', '#ff6fbc', '#dd577a', '#ff4c54']
	});

	function rating_slider() {
		$('.g7-slider').each(function() {
			var el = $(this);
			el.slider({
				min: el.data('min'),
				max: el.data('max'),
				step: el.data('step'),
				value: el.parent().next().children('input').val(),
				slide: function(event, ui) {
					el.parent().next().children('input').val(ui.value);
					set_overall_rating();
				}
			});
		});
	}
	rating_slider();

	function get_overall_rating() {
		var total = 0;
		var count = 0;
		var overall_rating = 0;
		$('.g7-rating > tbody > tr').each(function() {
			var row = $(this);
			var rating = parseFloat(row.find('[name^="_g7_rating"]').val());
			if (rating > 0) {
				total += rating;
				count++;
			}
		});
		if (count == 0) {
			return '';
		}
		overall_rating = total / count;
		overall_rating = overall_rating.toFixed(2);
		return overall_rating;
	}

	function set_overall_rating() {
		var overall = get_overall_rating();
		$('#_g7_overall_rating').val(overall);
	}

	$('.g7-rating-add a').click(function(e) {
		e.preventDefault();
		var row_container = $(this).parent().next('.g7-rating').find('tbody');
		row_container
			.children('tr:last')
				.clone(false)
				.appendTo(row_container)
				.find('[name^="_g7_criteria"]')
					.val('')
					.focus()
					.end()
				.find('[name^="_g7_rating"]')
					.val('')
					.end();
		rating_slider();
		set_overall_rating();
	});

	$('.g7-rating').on('click', '.g7-rating-delete', function(e) {
		e.preventDefault();
		var row_count = $(this).closest('tbody').children('tr').length;
		if (row_count > 1) {
			$(this).closest('tr').remove();
		} else {
			$(this).closest('tr').find('.g7-slider').slider('value', 0);
			$(this).closest('tr').find('[name^="_g7_criteria"]').val('');
			$(this).closest('tr').find('[name^="_g7_rating"]').val('');
		}
		set_overall_rating();
	});

	$('.g7-dragdrop').on('click', '.g7-imageselect img', function() {
		var img_val = $(this).data('val');
		$(this)
			.addClass('selected')
			.siblings('img')
				.removeClass('selected')
				.end()
			.siblings('input')
				.val(img_val);
	});

	function toggle_review_options() {
		var review_post_checked = $('#_g7_review_post').is(':checked');
		var review_rating = $('.g7-rating').closest('.g7mb-field');
		var review_summary = $('#_g7_summary').closest('.g7mb-field');
		if (review_post_checked) {
			review_rating.show();
			review_summary.show();
		} else {
			review_rating.hide();
			review_summary.hide();
		}
	}
	toggle_review_options();
	$('#_g7_review_post').click(toggle_review_options);
});
