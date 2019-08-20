<?php
class G7_Contact_Widget extends G7_Widget {

	function __construct() {

		$this->widget = array(
			'id_base'     => 'g7_contact',
			'name'        => 'Contact Form',
			'description' => __('Contact form with ajax validation', 'g7theme'),
		);

		parent::__construct();
	}

	function set_fields() {
		$fields = array(
			'title' => array(
				'type'  => 'text',
				'label' => __('Title', 'g7theme'),
				'std'   => __('Get In Touch', 'g7theme'),
			)
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
		<div class="contact-us">
			<div class="contact-msg"></div>
			<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
				<input type="hidden" name="action" value="g7_contact_form">
				<input type="hidden" name="security" value="<?php echo wp_create_nonce('contact_nonce'); ?>">
				<p>
					<span>
						<input type="text" placeholder="<?php _e('Name', 'g7theme'); ?>" class="text-input" name="contact_name" title="<?php _e('Name', 'g7theme'); ?>">
					</span>
				</p>
				<p>
					<span>
						<input type="text" placeholder="<?php _e('Email', 'g7theme'); ?>" class="text-input" name="contact_email" title="<?php _e('Email', 'g7theme'); ?>">
					</span>
				</p>
				<p>
					<span>
						<textarea placeholder="<?php _e('Message', 'g7theme'); ?>" name="contact_message" title="<?php _e('Message', 'g7theme'); ?>"></textarea>
					</span>
				</p>
				<p class="contact_h">
					<?php _e('Please leave this field blank', 'g7theme'); ?>:
					<span>
						<input type="text" class="text-input" name="<?php echo HONEYPOT_FIELD; ?>">
					</span>
				</p>
				<p>
					<span>
						<button type="submit" class="btn"><?php _e('Send', 'g7theme'); ?></button>
						<img class="loading" src="<?php echo PARENT_URL; ?>/images/loading.png" alt="loading...">
					</span>
				</p>
			</form>
		</div>
		<?php
		echo $after_widget;
	}

}
