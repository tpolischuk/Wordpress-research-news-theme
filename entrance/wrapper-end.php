<?php
global $g7_layout;
switch ($g7_layout) {
	case 1:
		echo '</div>';
		echo '<div id="sidebar" class="rs col-md-4">';
		get_sidebar();
		echo '</div>';
		echo '</div>';
		break;
	case 2:
		echo '</div>';
		echo '<div id="sidebar" class="ls col-md-4 col-md-pull-8">';
		get_sidebar();
		echo '</div>';
		echo '</div>';
		break;
	case 3:
	default:
		echo '';
		break;
}
