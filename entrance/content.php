<?php
$post_id        = get_the_ID();
$review_post    = get_post_meta($post_id, '_g7_review_post', true);
$overall_rating = get_post_meta($post_id, '_g7_overall_rating', true);
$content_type   = get_theme_mod('blog_content', 1);
$excerpt_length = get_theme_mod('blog_excerpt', 40);
$post_content   = g7_post_content($content_type, $excerpt_length);
$show_category  = 0;

$post_class = '';
list($image_w, $image_h) = g7_image_sizes('small');
if (get_post_type() == 'post') {
	$post_class = ' cat-' . g7_first_category_ID();
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>

	<?php if (get_theme_mod('blog_show_image', 1) && has_post_thumbnail()) : ?>
	<div class="entry-image">
		<?php if (get_theme_mod('blog_show_category', 1) && $post_class) : $show_category = 1; ?>
		<div class="entry-category">
			<?php echo g7_first_category(); ?>
		</div>
		<?php endif; ?>
		<?php echo g7_image($image_w, $image_h); ?>
	</div>
	<?php endif; ?>

	<div class="entry-main">
		<?php if (get_theme_mod('blog_show_category', 1) && $show_category == 0 && $post_class) : ?>
		<div class="entry-category">
			<?php echo g7_first_category(); ?>
		</div>
		<?php endif; ?>

		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<?php if ($post_class) : ?>
		<div class="entry-meta">
			<?php if (g7_option('enable_review') && get_theme_mod('blog_show_rating', 1) && $review_post && $overall_rating) : ?>
			<div class="rating"><?php echo g7_rating($overall_rating); ?></div>
			<?php endif; ?>

			<?php if (get_theme_mod('blog_show_date', 1)) : ?>
				<?php echo g7_date_meta(); ?>
			<?php endif; ?>

			<?php if (get_theme_mod('blog_show_comments', 1)) : ?>
				<?php echo g7_comments_meta(); ?>
			<?php endif; ?>

			<?php if (get_theme_mod('blog_show_author', 1)) : ?>
				<?php echo g7_author_meta(); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ($post_content) : ?>
		<div class="entry-content"><?php echo $post_content; ?></div>
		<?php endif; ?>

		<?php if (get_theme_mod('blog_show_readmore', 1)) : ?>
			<a class="btn readmore" href="<?php the_permalink(); ?>"><?php _e('Read more', 'g7theme'); ?></a>
		<?php endif; ?>
	</div>
	<div class="clear"></div>

</article>