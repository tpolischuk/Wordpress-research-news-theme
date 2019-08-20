<?php
global $g7_block_title, $g7_cat;
$link1 = '<span>';
$link2 = '</span>';
if (!empty($g7_cat)) {
	$category_name = esc_attr(get_the_category_by_ID($g7_cat));
	$category_link = esc_url(get_category_link($g7_cat));
	$link1 = '<a href="' . $category_link . '">';
	$link2 = '</a>';
}
?>
<h2 class="block-title">
	<?php echo $link1; ?>
		<?php echo $g7_block_title; ?>
		<?php if (is_rtl()) : ?>
			<i class="fa fa-angle-left"></i>
		<?php else : ?>
			<i class="fa fa-angle-right"></i>
		<?php endif; ?>
	<?php echo $link2; ?>
</h2>
<?php if (!empty($g7_cat)) : ?>
	<div class="block-more">
		<a href="<?php echo $category_link; ?>" title="<?php _e('View all posts in', 'g7theme'); ?> <?php echo $category_name; ?>">
			<i class="fa fa-list-ul"></i>
		</a>
		<a href="<?php echo get_category_feed_link($g7_cat); ?>" title="<?php _e('Subscribe to', 'g7theme'); ?> <?php echo $category_name; ?>">
			<i class="fa fa-rss"></i>
		</a>
	</div>
<?php endif; ?>