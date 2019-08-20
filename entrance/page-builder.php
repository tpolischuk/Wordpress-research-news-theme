<?php
/* Template Name: Content Builder */

$g7_meta = get_post_custom();

$g7_layout = g7_page_layout();
$post_id   = get_the_ID();
$cat_meta  = get_post_meta($post_id, '_g7_cat', true);
$count     = count($cat_meta['cat']);
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

	<?php if ($count) : ?>
		<?php for ($i = 0; $i < $count - 1; $i++) : ?>
			<?php
			$num    = $cat_meta['num'][$i];
			$style  = $cat_meta['style'][$i];
			$g7_cat = $cat_meta['cat'][$i];
			$g7_builder_query = new WP_Query(array(
				'posts_per_page'      => $num,
				'cat'                 => $g7_cat,
				'style'               => $style,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'ignore_sticky_posts' => 1,
			));
			$g7_block_title = $cat_meta['title'][$i];
			$g7_block_class = $g7_cat ? " cat-{$g7_cat}" : '';
			$style_prev     = $i == 0 ? 0 : $cat_meta['style'][$i - 1];
			$style_next     = $cat_meta['style'][$i + 1];

			$j = 1;
			?>

			<?php if ($style == 4 && $style_prev != 4) : ?>
				<div class="row block block-4">
			<?php endif; ?>

			<?php get_template_part('block', $style); ?>

			<?php if (($style == 4 && $i == $count - 2) || ($style == 4 && $style_next != 4)) : ?>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		<?php endfor; ?>
	<?php endif; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>