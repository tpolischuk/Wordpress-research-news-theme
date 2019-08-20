<?php
function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-size: 270px auto;
            width:300px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function custom_reviewers_scripts() {
	wp_register_script('reviewer_scripts', get_stylesheet_directory_uri() . '/scripts.js', array('jquery'), '1.0', true);
	wp_enqueue_script('reviewer_scripts');
}
add_action ('wp_enqueue_scripts', 'custom_reviewers_scripts');

function register_exact_target_widget() {
  register_widget( 'Exact_Target_Widget' );
}

include dirname(__FILE__).'/exact-target-widget.php';
