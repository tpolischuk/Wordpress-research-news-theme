<?php
/* Template Name: Blog */

if (get_query_var('paged')) {
	$paged = get_query_var('paged');
} elseif (get_query_var('page')) {
	$paged = get_query_var('page');
} else {
	$paged = 1;
}

$g7_meta = get_post_custom();

$cat   = g7_meta('blog_category');
$num   = g7_meta('blog_num');
if (empty($num)) {
	$num = get_option('posts_per_page');
}
$blog_style    = g7_meta('blog_style');
$g7_blog_style = 'small';
$row1          = '';
$row2          = '';
switch ($blog_style) {
	case '1': $g7_blog_style = 'small'; break;
	case '2': $g7_blog_style = 'large'; break;
	case '3': $g7_blog_style = 'grid'; $row1 = '<div class="row">'; $row2 = '</div>'; break;
	case '4': $g7_blog_style = 'masonry'; $row1 = '<div class="row masonry-container">'; $row2 = '</div>'; break;
}

$blog = new WP_Query(array(
	'posts_per_page' => $num,
	'cat'            => $cat,
	'paged'          => $paged
));
$g7_layout = g7_page_layout();

get_header();
?>

<?php if (g7_meta('page_show_title', 0) || g7_meta('page_show_content', 0)) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if (g7_meta('page_show_title', 0)) : ?>
				<h1 class="page-title"><?php the_title(); ?></h1>
			<?php endif; ?>
			<?php if (g7_meta('page_show_content', 0)) : ?>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
<?php endif; ?>

<?php get_template_part('wrapper', 'start'); ?>

	<?php if ($blog->have_posts()) : ?>

		<div class="posts blog-<?php echo $g7_blog_style; ?>">
			<?php echo $row1; ?>
			<?php while ($blog->have_posts()) : $blog->the_post(); ?>
				<?php get_template_part('content', $g7_blog_style); ?>
			<?php endwhile; ?>
			<?php echo $row2; ?>
			<?php wp_reset_postdata(); ?>
		</div>

		<?php g7_pagination($blog->max_num_pages); ?>

	<?php else: ?>

		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>