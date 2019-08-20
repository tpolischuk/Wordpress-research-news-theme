<?php
class G7_Subpages_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base'     => 'g7_subpages',
			'name'        => 'Subpages',
			'description' => __('A list of sub pages', 'g7theme'),
		);

		parent::__construct();
	}

	function set_fields() {
		$fields = array(
			'title' => array(
				'type'  => 'text',
				'label' => __('Title', 'g7theme'),
				'std'   => '',
				'desc'  => __('If empty, parent page title will be used', 'g7theme'),
			),
			'parent' => array(
				'type'  => 'text',
				'label' => __('Parent Page', 'g7theme'),
				'std'   => '',
				'desc'  => __('Enter a Page ID, or leave empty for current page', 'g7theme'),
			),
			'sortby' => array(
				'type'    => 'select',
				'label'   => __('Sort by', 'g7theme'),
				'std'     => 'menu_order',
				'options' => array(
					'post_title'             => __('Page title', 'g7theme'),
					'menu_order, post_title' => __('Page order', 'g7theme'),
					'ID'                     => __('Page ID', 'g7theme'),
				),
			),
			'exclude' => array(
				'type'  => 'text',
				'label' => 'Exclude',
				'std'   => '',
				'desc'  => __('Page IDs, separated by commas.', 'g7theme'),
			),
		);
		$this->fields = $fields;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;

		$title = apply_filters('widget_title', $instance['title']);
		if (empty($title)) {
			$title = get_the_title();
		}
		echo $before_title . $title . $after_title;

		$parent = $instance['parent'];
		if (empty($parent)) {
			$parent = get_the_ID();
		}

		$li = wp_list_pages(array(
			'echo'        => 0,
			'title_li'    => '',
			'child_of'    => $parent,
			'exclude'     => $instance['exclude'],
			'sort_column' => $instance['sortby'],
		));

		echo "<ul>$li</ul>";

		echo $after_widget;
	}

}
