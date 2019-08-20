<?php
class G7_Meta_Box {

	var $id;
	var $meta_box;
	var $nonce_action;
	var $nonce_name;
	var $prefix = '_g7_';

	function __construct($id, $meta_box) {
		$this->id = $id;
		$this->meta_box = $meta_box;
		add_action('add_meta_boxes', array(&$this, 'add'));
		add_action('save_post', array(&$this, 'save'));

		$this->nonce_action = 'g7mb-save-' . $this->id;
		$this->nonce_name = 'nonce_' . $this->id;
	}

	function add() {
		foreach ($this->meta_box['pages'] as $page) {
			add_meta_box(
				$this->id,
				$this->meta_box['title'],
				array($this, 'display'),
				$page,
				$this->meta_box['context'],
				$this->meta_box['priority']
			);
		}
	}

	function display($post) {
		wp_nonce_field($this->nonce_action, $this->nonce_name);

		foreach ((array)$this->meta_box['fields'] as $k => $v) {
			echo '<div class="g7mb-field">';
			$this->display_fields($this->prefix . $k, $v, $post->ID);
			echo '</div>';
		}
	}

	function display_fields($field_id, $field, $post_id) {
		if (isset($field['name'])) {
			printf(
				'<div class="g7mb-label"><label for="%s">%s</label></div>',
				$field_id,
				$field['name']
			);
		}
		echo '<div class="g7mb-input">';

		$value = get_post_meta($post_id, $field_id, true);

		if ($value == '' && isset($field['default'])) {
			if (!isset($_GET['post'])) {
				$value = $field['default'];
			}
		}

		switch ($field['type']) {

			case 'title':
				echo '<div class="g7-title">' . $field['label'] . '</div>';
				break;

			case 'text':
				printf(
					'<input type="text" name="%s" id="%s" size="%s" value="%s" />',
					$field_id,
					$field_id,
					isset($field['size']) ? $field['size'] : 40,
					$value
				);
				break;

			case 'textarea':
				printf(
					'<textarea name="%s" id="%s" cols="%s" rows="%s">%s</textarea>',
					$field_id,
					$field_id,
					isset($field['cols']) ? $field['cols'] : 40,
					isset($field['rows']) ? $field['rows'] : 2,
					$value
				);
				break;

			case 'select':
				printf(
					'<select name="%s" id="%s">',
					$field_id,
					$field_id
				);
				foreach ((array)$field['options'] as $k => $v) {
					printf(
						'<option value="%s" %s>%s</option>',
						$k,
						selected($value, $k, false),
						$v
					);
				}
				echo '</select>';
				break;

			case 'checkbox':
				printf(
					'<input type="checkbox" name="%s" id="%s" value="1" %s />%s',
					$field_id,
					$field_id,
					checked($value, '1', false),
					isset($field['label']) ? ' <label for="' . $field_id . '">' . $field['label'] . '</label>' : ''
				);
				break;

			case 'category':
				$option_all = isset($field['option_all']) ? $field['option_all'] : __('All Categories', 'g7theme');
				wp_dropdown_categories(
					array(
						'hide_empty'      => 0,
						'name'            => $field_id,
						'id'              => $field_id,
						'class'           => '',
						'hierarchical'    => 1,
						'show_option_all' => $option_all,
						'selected'        => $value,
						'show_count'      => 0,
					)
				);
				break;

			case 'slider':
				printf(
					'<div class="g7-slider" data-min="%s" data-max="%s" data-step="%s"></div>
					<span class="g7-slider-value">%s</span>
					<input type="hidden" name="%s" value="%s">',
					$field['min'],
					$field['max'],
					$field['step'],
					$value,
					$field_id,
					$value
				);
				break;

			case 'rating':
                $this->rating($field_id, $field, $post_id);
				break;

			case 'builder':
				$this->builder($field_id, $field, $post_id);
				break;
		}

		if (isset($field['desc'])) {
			if ($field['type'] == 'checkbox' || $field['type'] == 'textarea') {
				echo '<br>';
			}
			printf(
				' <span class="description">%s</span>',
				$field['desc']
			);
		}

		echo '</div>';
	}

	function rating($field_id, $field, $post_id) {
		$criteria_value = get_post_meta($post_id, $field_id, true);
		$rating_value   = get_post_meta($post_id, $this->prefix . $field['id2'], true);
		$overall_rating = get_post_meta($post_id, $this->prefix . $field['id3'], true);
		$row = sprintf(
			'<tr>
				<td>
					<input type="text" name="%s[]" value="%s" size="25">
				</td>
				<td>
					<div class="g7-slider" data-min="%s" data-max="%s" data-step="%s"></div>
				</td>
				<td>
					<input type="text" readonly="readonly" name="%s[]" value="%s" size="3">
				</td>
				<td>
					<a class="g7-rating-delete" href="#">%s</a>
				</td>
			</tr>',
			$field_id,
			'%s',
			$field['min'],
			$field['max'],
			$field['step'],
			$this->prefix . $field['id2'],
			'%s',
			__('Delete', 'g7theme')
		);
		$row2 = '';
		if (empty($criteria_value)) {
			$row2 .= sprintf(
				$row,
				'',
				''
			);
		} else {
			$i = 1;
			$count = count($criteria_value);
			for ($i = 1; $i <= $count; $i++) {
				$row2 .= sprintf(
					$row,
					$criteria_value[$i - 1],
					$rating_value[$i - 1]
				);
			}
		}
		printf(
			'<div class="g7-rating-add"><a href="#">%s</a></div>
			<table class="g7-rating">
				<thead>
					<tr>
						<th>%s</th>
						<th colspan="3">%s</th>
					</tr>
				</thead>
				<tbody>
					%s
				</tbody>
				<tfoot>
					<tr>
						<td>%s</td>
						<td></td>
						<td><input type="text" name="%s" id="%s" value="%s" size="3" readonly="readonly"></td>
						<td></td>
					</tr>
				</tfoot>
			</table>',
			__('Add Row', 'g7theme'),
			__('Criteria', 'g7theme'),
			__('Rating', 'g7theme'),
			$row2,
			__('Overall rating', 'g7theme'),
			$this->prefix . $field['id3'],
			$this->prefix . $field['id3'],
			$overall_rating
		);
	}

	function builder($field_id, $field, $post_id) {
		$value = get_post_meta($post_id, $field_id, true);
		//print_r($value);

		$row = '
			<div class="widget">
				<div class="widget-top">
					<div class="widget-title-action">
						<a href="#" class="widget-action"></a>
					</div>
					<div class="widget-title"><h4>%1$s</h4></div>
				</div>
				<div class="widget-inside">
					<div class="widget-content">
						<p>
							<label>' . __('Title', 'g7theme') . ':</label>
							<input type="text" size="30" value="%6$s" name="%2$s[title][]" class="blocktitle g7-input">
						</p>
						<p>
							<label>' . __('Filter by category', 'g7theme') . ':</label>
							%3$s
						</p>
						<p>
							<label>' . __('Style', 'g7theme') . ':</label>
							%4$s
						</p>
						<p>
							<label>' . __('Number of posts to show', 'g7theme') . ':</label>
							<input type="text" size="3" value="%5$s" name="%2$s[num][]">
						</p>
					</div>
					<div class="widget-control-actions">
						<div class="alignleft">
							<a href="#remove" class="widget-control-remove">' . __('Delete', 'g7theme') . '</a>
						</div>
						<br class="clear">
					</div>
				</div>
			</div>
		';

		$rows = '';

		if ($value) {
			$count = count($value['cat']);
			for ($i = 0; $i < $count - 1; $i++) {
				$rows .= sprintf(
					$row,
					$value['title'][$i],
					$field_id,
					$this->category_dropdown($field_id . '[cat][]', $value['cat'][$i]),
					$this->style_select($field_id . '[style][]', $value['style'][$i]),
					$value['num'][$i],
					$value['title'][$i]
				);
			}
		}

		$row2 = sprintf(
			$row,
			__('Block Title', 'g7theme'),
			$field_id,
			$this->category_dropdown($field_id . '[cat][]'),
			$this->style_select($field_id . '[style][]'),
			$field['default_num'],
			'',
			''
		);

		printf(
			'<div class="g7-content-builder">
				<div class="g7-add-item">
					<a href="#" class="button button-primary">%s</a>
				</div>
				<div class="g7-dragdrop">
					%s
				</div>
				<div class="g7-dragdrop-item">
					%s
				</div>
			</div>',
			__('Add Block', 'g7theme'),
			$rows,
			$row2
		);
	}

	function style_select($name, $value = 1) {
		$styles = '<span class="g7-imageselect">';
		$styles .= sprintf('<input type="hidden" name="%s" value="%s">', $name, $value);
		for ($i = 1; $i <= 6; $i++) {
			$selected = $value == $i ? 'selected' : '';
			$styles .= sprintf(
				'<img class="%s" src="%s/includes/metabox/style%s.png" data-val="%s" alt="Style %s">',
				$selected,
				PARENT_URL,
				$i,
				$i,
				$i
			);
		}
		$styles .= '</span>';
		return $styles;
	}

	function dropdown($name, $options, $value = 0) {
		$dropdown = sprintf('<select name="%s" class="g7-select">', $name);
		foreach ((array)$options as $k => $v) {
			$dropdown .= sprintf(
				'<option value="%s" %s>%s</option>',
				$k,
				selected($value, $k, false),
				$v
			);
		}
		$dropdown .= '</select>';
		return $dropdown;
	}

	function category_dropdown($name, $value = 0) {
		$dropdown = wp_dropdown_categories(array(
			'echo'            => '0',
			'hide_empty'      => 0,
			'name'            => $name,
			'id'              => 'tmp',
			'class'           => 'g7-category-dropdown',
			'hierarchical'    => 1,
			'show_option_all' => __('All Categories', 'g7theme'),
			'selected'        => $value,
			'show_count'      => 0,
		));
		$dropdown = str_replace(" id='tmp'", '', $dropdown);
		return $dropdown;
	}

	function save($post_id) {
		global $post_type;
		$post_type_object = get_post_type_object($post_type);

		// Check whether:
		// - the post is autosaved
		// - the post is a revision
		// - current post type is supported
		// - user has proper capability
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			|| (!isset($_POST['post_ID']) || $post_id != $_POST['post_ID'])
			|| (!in_array($post_type, $this->meta_box['pages']))
			|| (!current_user_can($post_type_object->cap->edit_post, $post_id))
			) {
			return $post_id;
		}

		// verify nonce
		if (!isset($_POST[$this->nonce_name]) || !wp_verify_nonce($_POST[$this->nonce_name], $this->nonce_action)) {
			return $post_id;
		}

		// check if template included
		if (!empty($this->meta_box['templates'])) {
			if (isset($_POST['page_template']) && !in_array($_POST['page_template'], $this->meta_box['templates'])) {
				return $post_id;
			}
		}

		// check if template not excluded
		if (!empty($this->meta_box['templates_ex'])) {
			if (isset($_POST['page_template']) && in_array($_POST['page_template'], $this->meta_box['templates_ex'])) {
				return $post_id;
			}
		}

		foreach ((array)$this->meta_box['fields'] as $field_id => $field) {
			$field_id = $this->prefix . $field_id;
			switch ($field['type']) {
				case 'checkbox':
					$cb_val = empty($_POST[$field_id]) ? 0 : 1;
					update_post_meta($post_id, $field_id, $cb_val);
					break;
				case 'rating':
					if (isset($_POST[$field_id])) {
						$field_id2 = $this->prefix . $field['id2'];
						$field_id3 = $this->prefix . $field['id3'];
						update_post_meta($post_id, $field_id, $_POST[$field_id]);
						update_post_meta($post_id, $field_id2, $_POST[$field_id2]);
						update_post_meta($post_id, $field_id3, $_POST[$field_id3]);
					}
					break;
				default:
					if (isset($_POST[$field_id])) {
						$new = $_POST[$field_id];
						$old = get_post_meta($post_id, $field_id, true);
						if ($new == '') {
							delete_post_meta($post_id, $field_id);
						} elseif ($old != $new) {
							update_post_meta($post_id, $field_id, $_POST[$field_id]);
						}
					}
					break;
			}
		}
	}
}

class G7_Meta_Boxes {

	var $meta_boxes;

	function __construct($meta_boxes) {
		$this->meta_boxes = $meta_boxes;
		foreach ((array)$this->meta_boxes as $id => $meta_box) {
			new G7_Meta_Box($id, $meta_box);
		}
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue'));
	}

	function enqueue() {
		$screen = get_current_screen();
		if ($screen->base != 'post') {
			return;
		}

		foreach ($this->meta_boxes as $id => $meta_box) {
			if (!empty($meta_box['templates'])) {
				$g7mb[$id] = $meta_box['templates'];
			} else {
				$g7mb[$id] = array();
			}
		}
		foreach ($this->meta_boxes as $meta_box) {
			if (!empty($meta_box['templates_ex'])) {
				$g7mb2[$id] = $meta_box['templates_ex'];
			} else {
				$g7mb2[$id] = array();
			}
		}

		wp_enqueue_style('g7-metabox', PARENT_URL . '/includes/metabox/metabox.css');
		wp_enqueue_style('jquery-ui-custom', PARENT_URL .'/includes/metabox/jquery-ui-custom.css');
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('jquery', false, array(), false, true);
		wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, true);
		wp_enqueue_script('jquery-ui-slider', false, array('jquery'), false, true);
		wp_enqueue_script('g7-metabox', PARENT_URL . '/includes/metabox/metabox.js', array('jquery', 'wp-color-picker'), false, true);
		wp_localize_script('g7-metabox', 'g7mb', $g7mb);
		wp_localize_script('g7-metabox', 'g7mb2', $g7mb2);
	}
}