<?php
$category = get_category(get_query_var('cat'));
$category_rss = '';
if (!empty($category)) {
	$rss_link = get_category_feed_link($category->cat_ID);
	$rss_title = __('Subscribe to this category', 'g7theme');
	$category_rss = sprintf(
		'<div class="category-feed"><a href="%s" rel="nofollow"><i class="fa fa-rss"></i> %s</a></div>',
		$rss_link,
		$rss_title
	);
}
$category_desc = '';
$category_description = category_description();
if (!empty($category_description)) {
	$category_desc = apply_filters('category_archive_meta', '<div class="archive-meta">' . $category_description . '</div>');
}

get_header();
?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if (have_posts()) : ?>

		<header class="archive-header">
			<h1 class="page-title"><?php echo single_cat_title('', false); ?></h1>
			<?php echo $category_rss; ?>
			<?php echo $category_desc; ?>
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