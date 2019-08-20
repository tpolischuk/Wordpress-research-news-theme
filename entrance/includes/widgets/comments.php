<?php
class G7_Comments_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base'     => 'g7_comments',
			'name'        => 'Comments',
			'description' => __('A list of latest comments', 'g7theme'),
		);

		parent::__construct();
	}

	function set_fields() {
		$fields = array(
			'title' => array(
				'type'  => 'text',
				'label' => __('Title', 'g7theme'),
				'std'   => __('Recent Comments', 'g7theme'),
			),
			'number' => array(
				'type'       => 'text',
				'label'      => __('Number of comments to show', 'g7theme'),
				'std'        => 5,
				'class'      => '',
				'attributes' => 'size="3"',
			),
			'show_avatar' => array(
				'type'  => 'checkbox',
				'label' => __('Show avatar', 'g7theme'),
				'std'   => 1,
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
		$number = $instance['number'];

		$comments = get_comments(array(
			'number'      => $number,
			'status'      => 'approve',
			'post_status' => 'publish'
		));
		?>

		<ul>
			<?php foreach ($comments as $comment) : ?>
			<li>
				<?php if ($instance['show_avatar']) : ?>
					<?php echo get_avatar($comment->comment_author_email, 45); ?>
				<?php endif; ?>
				<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
					<?php //echo get_comment_author_link($comment->comment_ID); ?>
					<?php echo get_comment_author($comment->comment_ID); ?>
				</a>:
				<?php echo wp_trim_words($comment->comment_content, 10, '...'); ?>
				<div class="clear"></div>
			</li>
			<?php endforeach; ?>
		</ul>

		<?php
		echo $after_widget;
	}

}
