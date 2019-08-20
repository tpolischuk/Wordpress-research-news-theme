<?php get_header(); ?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if (have_posts()) : ?>

		<div class="posts blog-small">
			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('content'); ?>
			<?php endwhile; ?>
		</div>

		<?php g7_pagination(); ?>

	<?php else: ?>

		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>
