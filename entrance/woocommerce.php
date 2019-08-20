<?php get_header(); ?>

	<?php $g7_layout = 3; get_template_part('wrapper', 'start'); ?>

		<?php woocommerce_content(); ?>

	<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>