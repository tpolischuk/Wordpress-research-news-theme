<?php
global $g7_builder_query, $g7_block_class;
?>
<div class="block block-6<?php echo $g7_block_class; ?>">
	<header>
		<?php get_template_part('block', 'header'); ?>
	</header>
	<ul>
		<?php while ($g7_builder_query->have_posts()) : $g7_builder_query->the_post(); ?>
			<li class="post">
				<h4 class="block-heading">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h4>
				<div class="block-meta">
					<?php echo g7_date_meta(); ?>
				</div>
				<div class="clear"></div>
			</li>
		<?php endwhile; ?>
	</ul>
</div>
