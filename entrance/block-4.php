<?php
global $g7_builder_query, $g7_block_class;
$block_counter = 1;
list($image_w, $image_h) = g7_image_sizes('grid');
list($image_w2, $image_h2) = g7_image_sizes('thumb');
?>
<div class="col-sm-6<?php echo $g7_block_class; ?>">
	<header>
		<?php get_template_part('block', 'header'); ?>
	</header>
	<ul>
		<?php while ($g7_builder_query->have_posts()) : $g7_builder_query->the_post(); ?>
			<li class="post">
				<?php if ($block_counter == 1) : ?>
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
					</div>
				<?php else : ?>
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
				<?php endif; ?>
			</li>
		<?php $block_counter++; endwhile; ?>
	</ul>
</div>
