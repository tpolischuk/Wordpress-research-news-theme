<?php
global $g7_layout;
if (empty($g7_layout)) {
	$g7_layout = g7_page_layout();
}
switch ($g7_layout) {
	case 1:
		echo '<div class="row"><div id="content" class="col-md-8">';
		break;
	case 2:
		echo '<div class="row"><div id="content" class="col-md-8 col-md-push-4">';
		break;
	case 3:
	default:
		echo '';
		break;
}
