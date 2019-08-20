<?php
class G7_Video_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base'     => 'g7_video',
			'name'        => 'Video',
			'description' => __('Featured video', 'g7theme'),
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
			'embed_code' => array(
				'type'  => 'textarea',
				'label' => __('Video Embed Code', 'g7theme'),
				'std'   => '',
			),
			'description' => array(
				'type'  => 'textarea',
				'label' => __('Description', 'g7theme'),
				'std'   => '',
			),
		);
		$this->fields = $fields;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}
		?>

		<div class="video">
			<?php echo $instance['embed_code']; ?>
		</div>
		<div class="video-desc">
			<?php echo $instance['description']; ?>
		</div>

		<?php
		echo $after_widget;
	}

}
