<?php
class G7_Customizer {

	private $data;

	function set_data($data) {
		$this->data = $data;
	}

	function generate() {
		add_action('customize_register', array(&$this, 'register'));
		add_action('customize_preview_init', array(&$this, 'preview_js'));
	}

	/**
	 * Binds JavaScript handlers to make Customizer preview reload changes
	 * asynchronously.
	 */
	function preview_js() {
		$js_file = PARENT_URL . '/includes/customizer/customizer.js';
		if (file_exists(CHILD_DIR . '/js/customizer.js')) {
			$js_file = CHILD_URL . '/js/customizer.js';
		}
		wp_enqueue_script('g7-customizer', $js_file, array('customize-preview'), false, true);
	}

	function register($wp_customize) {
		$wp_customize->get_setting('blogname')->transport         = 'postMessage';
		$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
		$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

		$priority = 121;

		foreach ($this->data as $k => $v) {
			/**
			 * Section
			 */
			$section = array(
				'title'    => $v['title'],
				'priority' => $priority++,
			);
			if (isset($v['description'])) {
				$section['description'] = $v['description'];
			}
			$wp_customize->add_section($k, $section);

			$i = 1;
			foreach ((array)$v['fields'] as $k2 => $v2) {

				/**
				 * Setting
				 */
				$setting = array();
				if (isset($v2['default'])) {
					$setting['default'] = $v2['default'];
				}
				if ($v2['type'] == 'color') {
					$setting['sanitize_callback'] = 'sanitize_hex_color';
				}
				$wp_customize->add_setting($k2, $setting);

				/**
				 * Control
				 */
				$control = $v2;
				$control['section'] = $k;
				$control['priority'] = $i++;
				unset($control['default']);

				switch ($control['type']) {

					case 'image':
						unset($control['type']);
						$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $k2, $control));
						break;

					case 'color':
						unset($control['type']);
						$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $k2, $control));
						break;

					default:
						$wp_customize->add_control($k2, $control);
						break;

				}
			}
		}
	}
}