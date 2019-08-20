<?php
global $g7_builder_query, $g7_block_class;
$block_counter = 1;
list($image_w, $image_h) = g7_image_sizes('grid');
list($image_w2, $image_h2) = g7_image_sizes('thumb');
?>
<div class="row block block-5<?php echo $g7_block_class; ?>">
	<header class="col-lg-12">
		<?php get_template_part('block', 'header'); ?>
	</header>
	<div class="col-sm-6">
		<ul>
			<?php while ($g7_builder_query->have_posts()) : $g7_builder_query->the_post(); ?>
				<li class="post">
					<?php if (has_post_thumbnail()) : ?>
					<div class="block-top">
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
				</li>
			<?php $block_counter++; break; endwhile; ?>
		</ul>
	</div>
	<div class="col-sm-6">
		<ul>
			<?php while ($g7_builder_query->have_posts()) : $g7_builder_query->the_post(); ?>
				<li class="post">
					<div class="block-side">
						<?php echo g7_image($image_w2, $image_h2); ?>
					</div>
					<div class="block-content">
						<h4 class="block-heading">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h4>
						<div class="block-meta">
							<?php echo g7_date_meta(); ?>
						</div>
					</div>
					<div class="clear"></div>
				</li>
			<?php $block_counter++; endwhile; ?>
		</ul>
	</div>
</div>
