<?php
class G7_Options {

	private $option_name;
	private $page;
	private $data;
	private $sections;
	private $use_tabs;

	function __construct() {
		if (!current_user_can('edit_theme_options')) {
			return;
		}
	}

	function set_option_name($option_name) {
		$this->option_name = $option_name;
	}

	function set_page($page) {
		$this->page = $page;
	}

	function set_data($data) {
		$this->data = $data;
	}

	function set_tabs($tabs) {
		$this->use_tabs = $tabs;
	}

	function generate() {
		add_action('admin_menu', array(&$this, 'add_page'));
		add_action('admin_init', array(&$this, 'init'));

		add_action('admin_print_scripts-appearance_page_' . $this->page, array(&$this, 'scripts'));
		add_action('admin_print_styles-appearance_page_' . $this->page, array(&$this, 'styles'));

		add_action('wp_ajax_update', array(&$this, 'validate'));

		if (!get_option($this->option_name)) {
			update_option(
				$this->option_name,
				$this->get_default_values()
			);
		}
	}

	function scripts() {
		wp_enqueue_script('jquery', false, array(), false, true);
		wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, true);
		wp_enqueue_script('jquery-ui-sortable', false, array('jquery'), false, true);
		wp_enqueue_script('media-upload', false, array(), false, true);
		wp_enqueue_script('thickbox', false, array(), false, true);
		wp_enqueue_script('farbtastic', false, array(), false, true);
		wp_enqueue_script('g7-options', PARENT_URL . '/includes/options/options.js');
	}

	function styles() {
		wp_enqueue_style('thickbox');
		wp_enqueue_style('farbtastic');
		wp_enqueue_style('g7-options', PARENT_URL . '/includes/options/options.css');
	}

	function add_page() {
		add_theme_page(
			__('Theme Options', 'g7theme'),
			__('Theme Options', 'g7theme'),
			'edit_theme_options',
			$this->page,
			array(&$this, 'display')
		);
	}

	function init() {
		foreach ($this->data as $k => $v) {
			add_settings_section($k, $v['title'], array(&$this, 'display_section'), $this->page);
			foreach ((array)$v['fields'] as $k2 => $v2) {
				add_settings_field(
					$k2,
					$v2['title'],
					array(&$this, 'display_fields'),
					$this->page,
					$k,
					array_merge($v2, array('name' => $k2))
				);
			}

			$this->sections[$k] = $v['title'];
		}
		register_setting(
			$this->option_name,
			$this->option_name,
			array(&$this, 'validate')
		);
	}

	function get_default_values() {
		$default = array();
		foreach ((array)$this->data as $k => $v) {
			foreach ((array)$v['fields'] as $k2 => $v2) {
				if ($v2['type'] == 'multicheck') {
					foreach ((array)$v2['options'] as $k3 => $v3) {
						$default[$k3] = isset($v2['std'][$k3]) ? '1' : '0';
					}
				} else {
					$default[$k2] = isset($v2['std']) ? $v2['std'] : '';
				}
			}
		}
		return $default;
	}

	function validate($input) {

		if (isset($_POST['reset'])) {
			add_settings_error(
				$this->option_name,
				'restore_defaults',
				__('Default options restored.', 'g7theme'),
				'updated fade'
			);
			return $this->get_default_values();
		}
		if (isset($_POST['update'])) {

			//for sidebars: if sidebar still has widgets, it cannot be deleted
			if (g7_option('sidebar')) {
				$deleted = array_diff(g7_option('sidebar'), $input['sidebar']);
				foreach ((array)$deleted as $v) {
					if (is_active_sidebar(g7_sidebar_id($v))) {
						$input['sidebar'][] = $v;
					}
				}
			}

			foreach ((array)$this->data as $v) {
				foreach ((array)$v['fields'] as $k2 => $v2) {
					//for checkboxes: if unchecked, set the value to 0
					if ($v2['type'] == 'checkbox') {
						$input[$k2] = isset($input[$k2]) ? '1' : '0';
					}
					if ($v2['type'] == 'multicheck') {
						foreach ((array)$v2['options'] as $k3 => $v3) {
							$input[$k3] = isset($input[$k3]) ? '1' : '0';
						}
					}
				}
			}

			add_settings_error(
				'options-framework',
				'save_options',
				__('Options saved.', 'g7theme'),
				'updated fade'
			);

			return $input;

		}
	}

	function display_section($args) {
		if (!empty($this->data[$args['id']]['desc'])) {
			echo '<p>' . $this->data[$args['id']]['desc'] . '</p>';
		}
	}

	function display_fields($args = array()) {
		$options = get_option($this->option_name);
		$option_name = $this->option_name . '[' . $args['name'] . ']';
		$option_value = '';
		if (isset($options[$args['name']])) {
			$option_value = $options[$args['name']];
		}

		switch ($args['type']) {

			case 'text':
				printf(
					'<input type="text" name="%s" id="%s" value="%s" class="regular-text" %s />',
					$option_name,
					$option_name,
					esc_attr($option_value),
					isset($args['attributes']) ? $args['attributes'] : ''
				);
				break;

			case 'textarea':
				printf(
					'<textarea name="%s" id="%s" class="large-text" rows="%s" %s>%s</textarea>',
					$option_name,
					$args['name'],
					isset($args['rows']) ? $args['rows'] : '3',
					isset($args['attributes']) ? $args['attributes'] : '',
					esc_attr($option_value)
				);
				break;

			case 'select':
				printf(
					'<select name="%s" class="%s">',
					$option_name,
					isset($args['class']) ? $args['class'] : 'regular-select'
				);
				foreach ((array)$args['options'] as $k => $v) {
					printf(
						'<option value="%s" %s>%s</option>',
						$k,
						selected($option_value, $k, false),
						$v
					);
				}
				echo '</select>';
				break;

			case 'checkbox':
				printf(
					'<input type="checkbox" id="%s" name="%s" value="1" %s %s />
					<label for="%s">%s</label>',
					$args['name'],
					$option_name,
					checked($option_value, '1', false),
					isset($args['hidden']) ? 'class="folds" data-fold="'.$args['hidden'].'"' : '',
					$args['name'],
					$args['label']
				);
				break;

			case 'radio':
				foreach ((array)$args['options'] as $k => $v) {
					printf(
						'<label class="radio-option"><input type="radio" name="%s" value="%s" %s>%s</label>',
						$option_name,
						$k,
						checked($option_value, $k, false),
						$v
					);
				}
				break;

			case 'multicheck':
				foreach ((array)$args['options'] as $k => $v) {
					printf(
						'<label><input type="checkbox" name="%s" value="1" %s />
						%s</label><br />',
						$this->option_name . "[$k]",
						isset($options[$k]) ? checked($options[$k], '1', false) : '',
						$v
					);
				}
				break;

			case 'image':
				printf(
					'<input type="text" id="%s" name="%s" class="regular-text upload-field" value="%s" />
					<input type="button" class="button upload-button" value="Upload Image" />',
					$args['name'],
					$option_name,
					$option_value
				);
				break;

			case 'color':
				if (!empty($args['presets'])) {
					echo '<div class="color-presets">';
					foreach ($args['presets'] as $v) {
						printf('<div data-color="%s"></div>', $v);
					}
					echo '</div><div class="clear"></div>';
				}
				printf(
					'<div class="colorpicker">
						<input type="text" id="%s" name="%s" class="color" value="%s" />
						<div class="picker"></div>
					</div>',
					$args['name'],
					$option_name,
					$option_value
				);
				break;

			case 'texts':
				$input = '<div class="g7-item" id="%s_%s"><input type="text" name="%s" value="%s" class="regular-text" %s> <a href="#" class="g7-item-delete">%s</a></div>';
				$input2 = '';
				if (empty($option_value)) {
					$input2 = sprintf(
						$input,
						$args['name'],
						1,
						$option_name . '[]',
						'',
						isset($args['attributes']) ? $args['attributes'] : '',
						__('Delete', 'g7theme')
					);
				} else {
					$i = 1;
					foreach ((array)$option_value as $v) {
						$input2 .= sprintf(
							$input,
							$args['name'],
							$i,
							$option_name . '[]',
							esc_attr($v),
							isset($args['attributes']) ? $args['attributes'] : '',
							__('Delete', 'g7theme')
						);
						$i++;
					}

				}
				printf(
					'<div class="g7-item-container">%s</div><div><a class="g7-item-add" href="#" data-id="%s" data-title="%s">%s</a></div>',
					$input2,
					$args['name'],
					$args['title'],
					__('Add More', 'g7theme')
				);
				break;

		}

		if (isset($args['desc'])) {
			if (in_array($args['type'], array('checkbox', 'textarea'))) {
				echo '<br>';
			}
			printf(
				' <span class="description" for="%s">%s</span>',
				$option_name,
				$args['desc']
			);
		}

		if ($args['type'] == 'image') {
			echo '<div class="upload-preview">';
			if ($option_value) {
				echo '<img src="'.$option_value.'" />';
			}
			echo '</div>';
		}
	}

	function display() {
		settings_errors();
		?>
		<div class="wrap<?php echo $this->use_tabs ? ' use_tabs' : ''; ?>">

			<div id="icon-options-general" class="icon32"></div>
			<h2><?php _e('Theme Options', 'g7theme'); ?></h2>

			<?php if ($this->use_tabs) : ?>
			<h2 class="nav-tab-wrapper">
				<?php foreach ((array)$this->sections as $k => $v) : ?>
				<a href="#section-<?php echo $k; ?>" class="nav-tab"><?php echo $v; ?></a>
				<?php endforeach; ?>
			</h2>
			<?php endif; ?>

			<form method="post" action="options.php" enctype="multipart/form-data" id="g7-options">

				<?php settings_fields($this->option_name); ?>

				<?php if ($this->use_tabs) : ?>

					<?php foreach ((array)$this->sections as $k => $v) : ?>
					<div id="section-<?php echo $k; ?>">
						<h3><?php echo $v; ?></h3>
						<table class="form-table">
							<?php do_settings_fields($this->page, $k); ?>
						</table>
					</div>
					<?php endforeach; ?>

				<?php else : ?>

					<?php do_settings_sections($this->page); ?>

				<?php endif; ?>

				<p class="submit">
					<input type="submit" class="button-primary" id="save-options" name="update" value="<?php _e('Save All Settings', 'g7theme'); ?>" />
					<?php if ($this->use_tabs) : ?>
					<a id="notabs" href="<?php echo admin_url('themes.php?page=theme-options&tabs=0'); ?>" title="use no tabs">no tabs</a>
					<?php endif; ?>
				</p>

			</form>

		</div>
		<?php
	}

}
