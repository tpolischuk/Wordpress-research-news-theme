<?php
class G7_Social_Widget extends G7_Widget {

	private $max_items;
	private $icons;

	function __construct() {

		$this->max_items = g7_option('social_max', 6);
		$this->icons = g7_social_icons();

		$this->widget = array(
			'id_base'     => 'g7_social',
			'name'        => 'Social',
			'description' => __('Social media icons', 'g7theme'),
			'control_ops' => array('width' => 350),
		);

		parent::__construct();
	}

	function set_fields() {
		$fields['title'] = array(
			'type'  => 'text',
			'label' => __('Title', 'g7theme'),
			'std'   => '',
		);
		$fields['size'] = array(
			'type'    => 'select',
			'label'   => __('Icon size', 'g7theme'),
			'std'     => '1',
			'class'   => '',
			'options' => array(
				'small' => __('Small', 'g7theme'),
				'large' => __('Large', 'g7theme'),
			),
		);
		$fields['frame'] = array(
			'type'    => 'select',
			'label'   => __('Icon frame', 'g7theme'),
			'std'     => '1',
			'class'   => '',
			'options' => array(
				'square'  => __('Square', 'g7theme'),
				'rounded' => __('Rounded Square', 'g7theme'),
				'circle'  => __('Circle', 'g7theme'),
			),
		);
		$fields['style'] = array(
			'type'    => 'select',
			'label'   => __('Style', 'g7theme'),
			'std'     => '1',
			'class'   => '',
			'options' => array(
				'horizontal' => __('Horizontal', 'g7theme'),
				'vertical'   => __('Vertical', 'g7theme'),
			),
		);
		$fields['color_on_hover'] = array(
			'type'  => 'checkbox',
			'label' => __('Color on hover', 'g7theme'),
			'std'   => 0,
		);

		$icons[''] = '';
		foreach ($this->icons as $k => $v) {
			$icons[$k] = $v;
		}

		for ($i = 1; $i <= $this->max_items; $i++) {
			$fields['separator' . $i] = array(
				'type'    => 'custom',
				'content' => '<br>',
			);
			$fields['icon' . $i] = array(
				'type'       => 'select',
				'label'      => 'Icon ' . $i,
				'options'    => $icons,
				'std'        => '',
				'class'      => '',
			);
			$fields['url' . $i] = array(
				'type'       => 'text',
				'label'      => 'URL ' . $i,
				'std'        => '',
				'class'      => '',
				'attributes' => 'style="width:300px"',
			);
			$fields['text' . $i] = array(
				'type'       => 'text',
				'label'      => 'Text ' . $i,
				'std'        => '',
				'class'      => '',
				'attributes' => 'style="width:300px"',
			);
		}

		$this->fields = $fields;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;

		$title = apply_filters('widget_title', $instance['title']);
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}
		$class[] = $instance['style'];
		if ($instance['size'] != 'small') {
			$class[] = $instance['size'];
		}
		// if ($instance['frame'] != 'square') {
			$class[] = $instance['frame'];
		// }
		if (!empty($instance['color_on_hover'])) {
			$class[] = 'coh';
		}
		$ul_class = implode(' ', $class);
		?>

		<ul class="<?php echo $ul_class; ?>">
			<?php for ($i = 1; $i <= $this->max_items; $i++) : ?>
				<?php if (!empty($instance['url'.$i])) : ?>
				<li class="social-<?php echo $this->icons[$instance['icon'.$i]]; ?>">
					<a href="<?php echo $instance['url'.$i]; ?>"<?php if ($instance['style'] == 'horizontal') : ?> title="<?php echo $instance['text'.$i]; ?>"<?php endif; ?>>
						<span class="social-box"><i class="fa fa-<?php echo $instance['icon'.$i]; ?>"></i></span>
						<?php if ($instance['style'] == 'vertical') : ?>
							<span class="social-text"><?php echo $instance['text'.$i]; ?></span>
						<?php endif; ?>
					</a>
				</li>
				<?php endif; ?>
			<?php endfor; ?>
		</ul>
		<div class="clear"></div>

		<?php

		echo $after_widget;
	}

}
