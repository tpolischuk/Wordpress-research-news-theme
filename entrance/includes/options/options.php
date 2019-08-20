<?php
require_once PARENT_DIR . '/includes/options/class.options.php';
require_once PARENT_DIR . '/includes/options/data.options.php';
if (file_exists(CHILD_DIR . '/data.options.php')) {
	require_once CHILD_DIR . '/data.options.php';
}

$theme_options = new G7_Options;
$theme_options->set_option_name(G7_OPTIONNAME);
$theme_options->set_page('theme-options');
$theme_options->set_data($options_data);
$theme_options->set_tabs(false);
$theme_options->generate();
