<?php get_header(); ?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php while (have_posts()) : the_post(); ?>

		<?php get_template_part('content', 'single'); ?>

		<?php comments_template(); ?>

	<?php endwhile; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>