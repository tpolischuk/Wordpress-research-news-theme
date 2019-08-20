<?php
global $g7_builder_query, $g7_block_class;
list($image_w, $image_h) = g7_image_sizes('small');
?>
<div class="block block-1<?php echo $g7_block_class; ?>">
	<header>
		<?php get_template_part('block', 'header'); ?>
	</header>
	<ul>
		<?php while ($g7_builder_query->have_posts()) : $g7_builder_query->the_post(); ?>
			<li class="post">
				<?php if (has_post_thumbnail()) : ?>
				<div class="block-side">
					<?php echo g7_image($image_w, $image_h); ?>
				</div>
				<?php endif; ?>
				<div class="block-content">
					<h3 class="block-heading">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>
					<div class="block-meta">
						<?php echo g7_date_meta(); ?>
						<?php echo g7_comments_meta(); ?>
					</div>
					<div class="block-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></div>
				</div>
				<div class="clear"></div>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
