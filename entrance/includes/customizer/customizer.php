<?php
require_once PARENT_DIR . '/includes/customizer/class.customizer.php';
require_once PARENT_DIR . '/includes/customizer/data.customizer.php';
if (file_exists(CHILD_DIR . '/data.customizer.php')) {
	require_once CHILD_DIR . '/data.customizer.php';
}

$customizer = new G7_Customizer;
$customizer->set_data($customizer_data);
$customizer->generate();
