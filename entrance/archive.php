<?php get_header(); ?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if (have_posts()) : ?>

		<header class="archive-header">
			<h1 class="page-title">
				<?php if (is_day()) : ?>
					<?php printf(__('Daily Archives: %s', 'g7theme'), '<span>' . get_the_date() . '</span>'); ?>
				<?php elseif (is_month()) : ?>
					<?php printf(__('Monthly Archives: %s', 'g7theme'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'g7theme')) . '</span>'); ?>
				<?php elseif (is_year()) : ?>
					<?php printf(__('Yearly Archives: %s', 'g7theme'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'g7theme')) . '</span>'); ?>
				<?php else : ?>
					<?php _e('Blog Archives', 'g7theme'); ?>
				<?php endif; ?>
			</h1>
		</header>

		<div class="posts blog-small">
			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('content'); ?>
			<?php endwhile; ?>
		</div>

		<?php g7_pagination(); ?>

	<?php else : ?>

		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>