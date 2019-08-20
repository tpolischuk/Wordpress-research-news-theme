<?php
if (is_admin()) {
	if (!function_exists('g7_call_meta_box')) {
		function g7_call_meta_box() {
			require_once PARENT_DIR . '/includes/metabox/class.metabox.php';
			require_once PARENT_DIR . '/includes/metabox/data.metabox.php';
			if (file_exists(CHILD_DIR . '/data.metabox.php')) {
				require_once CHILD_DIR . '/data.metabox.php';
			}

			return new G7_Meta_Boxes($meta_boxes);
		}
		add_action('load-post.php', 'g7_call_meta_box');
		add_action('load-post-new.php', 'g7_call_meta_box');
	}
}