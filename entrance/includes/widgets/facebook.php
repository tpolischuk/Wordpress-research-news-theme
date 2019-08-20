<?php
class G7_Facebook_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base'     => 'g7_facebook',
			'name'        => 'Facebook',
			'description' => __('Facebook like box', 'g7theme'),
		);

		parent::__construct();
	}

	function set_fields() {
		$fields = array(
			'title' => array(
				'type'  => 'text',
				'label' => __('Title', 'g7theme'),
				'std'   => '',
			),
			'href' => array(
				'type'  => 'text',
				'label' => __('Facebook Page URL', 'g7theme'),
				'std'   => '',
			),
			'height' => array(
				'type'  => 'text',
				'label' => __('Height', 'g7theme'),
				'std'   => '250',
			),
			'colorscheme' => array(
				'type'    => 'select',
				'label'   => __('Color Scheme', 'g7theme'),
				'std'     => 'light',
				'options' => array(
					'light' => 'light',
					'dark'  => 'dark',
				),
			),
			'show_faces' => array(
				'type'    => 'select',
				'label'   => __('Show Faces', 'g7theme'),
				'std'     => 'true',
				'options' => array(
					'true'  => 'Yes',
					'false' => 'No',
				),
			),
			'stream' => array(
				'type'    => 'select',
				'label'   => __('Show stream', 'g7theme'),
				'std'     => 'false',
				'options' => array(
					'true'  => 'Yes',
					'false' => 'No',
				),
			),
			'header' => array(
				'type'    => 'select',
				'label'   => __('Show header', 'g7theme'),
				'std'     => 'true',
				'options' => array(
					'true'  => 'Yes',
					'false' => 'No',
				),
			),
		);
		$this->fields = $fields;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		if ($title) {
			echo $before_title . $title . $after_title;
		}

		$href = urlencode($instance['href']);

		$setting[] = "href={$href}";
		$setting[] = "width=300";
		$setting[] = "height={$instance['height']}";
		$setting[] = "show_faces={$instance['show_faces']}";
		$setting[] = "colorscheme={$instance['colorscheme']}";
		$setting[] = "stream={$instance['stream']}";
		$setting[] = "header={$instance['header']}";

		$settings = implode('&amp;', $setting);
		?>

<iframe src="//www.facebook.com/plugins/likebox.php?<?php echo $settings; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:<?php echo $instance['height']; ?>px;" allowTransparency="true"></iframe>

		<?php
		echo $after_widget;
	}

}
