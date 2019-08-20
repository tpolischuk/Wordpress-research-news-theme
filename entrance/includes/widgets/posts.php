<?php
class G7_Posts_Widget extends G7_Widget {

	function __construct() {

		$this->base_posts = new G7_Posts;
		$this->widget = array(
			'id_base'     => 'g7_posts',
			'name'        => 'Posts',
			'description' => __('A list of posts with several options', 'g7theme'),
		);

		parent::__construct();
	}

	function set_fields() {
		$this->fields = $this->base_posts->get_fields();
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}

		$query_args = $this->base_posts->get_query_args($instance);
		$posts = new WP_Query($query_args);

		echo $this->base_posts->loop($posts, $instance);

		echo $after_widget;
		wp_reset_postdata();
	}

}
