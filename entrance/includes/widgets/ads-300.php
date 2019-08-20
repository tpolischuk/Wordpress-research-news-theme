<?php
class G7_Ads300_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base'     => 'g7_ads300',
			'name'        => 'Ads 300px',
			'description' => __('Banner (300 pixels width)', 'g7theme'),
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
			'banner_url' => array(
				'type'  => 'text',
				'label' => __('Banner image url', 'g7theme'),
				'std'   => '',
			),
			'banner_link' => array(
				'type'  => 'text',
				'label' => __('Banner link', 'g7theme'),
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

		<?php if ($instance['banner_url']) : ?>
		<a href="<?php echo $instance['banner_link']; ?>">
			<img class="banner" src="<?php echo $instance['banner_url']; ?>" alt="banner">
		</a>
		<?php endif; ?>

		<?php
		echo $after_widget;
	}

}
