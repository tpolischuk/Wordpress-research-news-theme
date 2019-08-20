<?php
global $g7_layout;
$size = $g7_layout == 3 ? 'single-full' : 'single';
list($image_w, $image_h) = g7_image_sizes($size);
$category = get_the_category();
if ($category) {
	$cat_name = $category[0]->cat_name;
}

$post_id     = get_the_ID();
$review_post = get_post_meta($post_id, '_g7_review_post', true);
$schema      = $review_post ? 'Review' : 'Article';
if ($review_post) {
	$criteria = get_post_meta($post_id, '_g7_criteria', true);
	$rating   = get_post_meta($post_id, '_g7_rating', true);
	$overall  = get_post_meta($post_id, '_g7_overall_rating', true);
	$summary  = get_post_meta($post_id, '_g7_summary', true);
}
$social_links = g7_author_social_links();

$image_view = get_post_meta($post_id, '_g7_featured_image', true);
switch ($image_view) {
	case 1:
	default:
		$featured_image = g7_image($image_w, $image_h, false);
		break;
	case 2:
		$featured_image = g7_image($image_w, null, false);
		break;
	case 3:
		$featured_image = '';
		break;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/<?php echo $schema; ?>">

	<header class="entry-header">
		<?php if (get_theme_mod('single_featured_image', 1) && (has_post_thumbnail())) : ?>
			<?php if ($featured_image) : ?>
				<div class="entry-image">
					<?php echo $featured_image; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php if (get_theme_mod('single_category', 1)) : ?>
				<span class="entry-category">
					<?php the_category(', ') ?>
				</span>
			<?php endif; ?>

			<?php if (get_theme_mod('single_date', 1)) : ?>
				<?php echo g7_date_meta(true); ?>
			<?php endif; ?>

			<?php echo g7_comments_meta(); ?>

			<?php if (get_theme_mod('single_author', 1)) : ?>
				<?php echo g7_author_meta(true); ?>
			<?php endif; ?>
			<?php edit_post_link('<i class="fa fa-pencil-square-o"></i> ' . __('Edit', 'g7theme'), '<span>', '</span>'); ?>
		</div>
	</header>

	<div class="entry-content" itemprop="<?php echo strtolower($schema); ?>Body">
		<?php the_content(); ?>
	</div>

	<footer class="entry-footer">
		<?php if ($review_post) : ?>
			<div class="review">
				<?php foreach ((array)$rating as $k => $v) : ?>
					<div class="row">
						<div class="col-xs-6"><?php echo $criteria[$k]; ?></div>
						<div class="col-xs-6"><?php echo g7_rating($v); ?></div>
					</div>
				<?php endforeach; ?>
				<div class="row overall">
					<div class="col-xs-6"><?php _e('OVERALL', 'g7theme'); ?></div>
					<div class="col-xs-6"><?php echo g7_rating($overall, 'big'); ?></div>
				</div>
				<?php if ($summary) : ?>
					<div class="summary" itemprop="description">
						<?php echo $summary; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if (isset($cat_name)) : ?>
				<meta itemprop="itemReviewed" content="<?php echo esc_attr($cat_name); ?>">
			<?php endif; ?>
			<div itemtype="http://schema.org/Rating" itemscope itemprop="reviewRating">
				<meta content="1" itemprop="worstRating">
				<meta content="<?php echo $overall; ?>" itemprop="ratingValue">
				<meta content="5" itemprop="bestRating">
			</div>
		<?php endif; ?>

		<?php wp_link_pages(array(
			'before'         => '<p><strong>' . __('Pages', 'g7theme') . ':</strong> ',
			'after'          => '</p>',
			'next_or_number' => 'number'
		)); ?>

		<?php if (get_theme_mod('single_tags', 1)) : ?>
			<div class="tags">
				<?php the_tags('<i class="fa fa-tags"></i> ', ', ', ''); ?>
			</div>
		<?php endif; ?>

		<nav class="next-prev clearfix">
			<?php previous_post_link('<div class="nav-previous"><div>' . __('Previous Post', 'g7theme') . '</div>%link</div>'); ?>
			<?php next_post_link('<div class="nav-next"><div>' . __('Next Post', 'g7theme') . '</div>%link</div>'); ?>
		</nav>
	</footer>

</article>

<?php if (get_theme_mod('single_author_info', 1)) : ?>
<div class="author-info">
	<h3><?php printf(__('Author', 'g7theme'), get_the_author()); ?></h3>
	<div class="author-avatar">
		<?php echo get_avatar(get_the_author_meta('email'), 50); ?>
	</div>
	<div class="author-link">
		<h4><?php the_author_link(); ?></h4>
		<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
			<?php printf(__('View all posts by %s', 'g7theme'), get_the_author()); ?>
			<span class="meta-nav">&rarr;</span>
		</a>
	</div>
	<div class="clear"></div>
	<div class="author-description">
		<?php the_author_meta('description'); ?>
	</div>
	<?php if (get_the_author_meta('url')) : ?>
		<div class="author-website">
			<i class="fa fa-external-link"></i>
			<a href="<?php echo get_the_author_meta('url'); ?>" target="_blank">
				<?php echo get_the_author_meta('url'); ?>
			</a>
		</div>
	<?php endif; ?>
	<?php if ($social_links) : ?>
		<div class="widget_g7_social">
			<ul class="horizontal circle">
				<?php foreach ($social_links as $k => $v) : ?>
					<li class="social-<?php echo $k; ?>">
						<a href="<?php echo $v['link']; ?>" title="<?php echo ucfirst($k); ?>" target="_blank">
							<span class="social-box">
								<i class="fa fa-<?php echo $v['icon']; ?>"></i>
							</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php if (get_theme_mod('single_related', 1)) : ?>
<div class="related-posts">
	<h3><?php _e('Related Posts', 'g7theme'); ?></h3>
	<?php g7_related_posts($post->ID); ?>
</div>
<?php endif; ?>
