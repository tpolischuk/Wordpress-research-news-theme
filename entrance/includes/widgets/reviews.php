<?php
class G7_Reviews_Widget extends G7_Widget {

	function __construct() {

		$this->base_posts = new G7_Posts;
		$this->base_posts->orderby = false;
		$this->base_posts->cat = false;
		$this->base_posts->show_excerpt = false;

		$this->widget = array(
			'id_base'     => 'g7_reviews',
			'name'        => 'Reviews',
			'description' => __('A list of reviews', 'g7theme'),
		);

		parent::__construct();
	}

	function set_fields() {
		$this->fields = $this->base_posts->get_fields();
		$this->fields['orderby'] = array(
			'type'    => 'select',
			'label'   => __('Post order', 'g7theme'),
			'class'   => '',
			'std'     => 4,
			'options' => array(
				1 => __('Recent', 'g7theme'),
				2 => __('Popular', 'g7theme'),
				3 => __('Random', 'g7theme'),
				4 => __('Highest Rated', 'g7theme'),
			),
		);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}

		$query_args = $this->base_posts->get_query_args($instance);
		$query_args['meta_query'] = array(
			array(
				'key'     => '_g7_review_post',
				'value'   => '1',
				'compare' => '=',
			),
		);
		if (isset($instance['orderby']))
			switch ($instance['orderby']) {
				case 2:
					$query_args['orderby'] = 'comment_count';
					break;
				case 3:
					$query_args['orderby'] = 'rand';
					break;
				case 4:
					$query_args['orderby']  = 'meta_value_num';
					$query_args['meta_key'] = '_g7_overall_rating';
					break;
				case 1:
				default:
					$query_args['orderby'] = 'date';
					break;
		}
		$posts = new WP_Query($query_args);

		echo $this->base_posts->loop($posts, $instance);

		echo $after_widget;
		wp_reset_postdata();
	}

}
