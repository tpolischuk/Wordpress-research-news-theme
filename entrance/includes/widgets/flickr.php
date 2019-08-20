<?php
class G7_Flickr_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base'     => 'g7_flickr',
			'name'        => 'Flickr',
			'description' => __('Photos from a Flickr account', 'g7theme'),
		);

		parent::__construct();
	}

	function set_fields() {
		$idgettr_url = 'http://idgettr.com/';
		$fields = array(
			'title' => array(
				'type'  => 'text',
				'label' => __('Title', 'g7theme'),
				'std'   => __('Photos on Flickr', 'g7theme'),
			),
			'user' => array(
				'type'  => 'text',
				'label' => 'Flickr ID (<a href="' . $idgettr_url . '">Find your ID</a>)',
				'std'   => '',
			),
			'number' => array(
				'type'       => 'text',
				'label'      => __('Number of photos to show', 'g7theme'),
				'std'        => 6,
				'attributes' => 'size="3"',
			),
			'display' => array(
				'type'    => 'select',
				'label'   => __('Display', 'g7theme'),
				'std'     => 'latest',
				'options' => array(
					'latest' => 'latest',
					'random' => 'random',
				),
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

		$user    = $instance['user'];
		$number  = $instance['number'];
		$display = $instance['display'];

		?>
		<div>
			<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=<?php echo $display; ?>&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $user; ?>"></script>

			<div class="clear"></div>
		</div>
		<?php

		echo $after_widget;
	}

}
