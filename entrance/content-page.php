<?php
global $g7_layout;
$size = $g7_layout == 3 ? 'single-full' : 'single';
list($image_w, $image_h) = g7_image_sizes($size);

$image_view = get_post_meta(get_the_ID(), '_g7_featured_image', true);
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
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if (has_post_thumbnail()) : ?>
			<?php if ($featured_image) : ?>
				<div class="entry-image">
					<?php echo $featured_image; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php edit_post_link('<i class="fa fa-pencil-square-o"></i> ' . __('Edit', 'g7theme'), '<div class="entry-meta"><span>', '</span></div>'); ?>
	</header>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	<?php wp_link_pages(array(
		'before'         => '<footer class="entry-footer"><p><strong>' . __('Pages', 'g7theme') . ':</strong> ',
		'after'          => '</p></footer>',
		'next_or_number' => 'number'
	)); ?>

</article>