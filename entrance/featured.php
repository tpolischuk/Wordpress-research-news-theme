<?php
if (!g7_show_featured()) {
	return;
}
$flayout       = get_theme_mod('featured_layout', 1);
$limit         = $flayout == 2 ? get_theme_mod('slider_limit', 3) : 5;
$featured_tags = explode(',', get_theme_mod('featured_tags', 'featured'));
$tags          = array();
$have_posts    = false;

if (!empty($_GET['slider'])) {
	$flayout = 2;
}

foreach ((array)$featured_tags as $tag) {
	$tags[] = trim($tag);
}

if (!empty($tags)) {
	$featured = new WP_Query(array(
		'posts_per_page'      => $limit,
		'tag_slug__in'        => $tags,
		'ignore_sticky_posts' => 1,
	));
	if ($featured->have_posts()) {
		$have_posts = true;
	}
}

if (!$have_posts) {
	$featured = new WP_Query(array(
		'posts_per_page'      => $limit,
		'post__in'            => get_option('sticky_posts'),
		'ignore_sticky_posts' => 1,
	));
	if ($featured->have_posts()) {
		$have_posts = true;
	}
}

list($image_w, $image_h)   = g7_image_sizes('full');
list($image_w1, $image_h1) = g7_image_sizes('featured1');
list($image_w2, $image_h2) = g7_image_sizes('featured2');

$i = 1;
?>

<?php if ($have_posts) : ?>

	<?php if ($flayout == 2) : ?>

		<div class="flexslider featured">
			<ul class="slides">
				<?php while ($featured->have_posts()) : $featured->the_post(); ?>
					<li class="post cat-<?php echo g7_first_category_ID(); ?>">
						<a href="<?php the_permalink(); ?>">
							<?php echo g7_image($image_w, $image_h, false); ?>
						</a>
						<header>
							<span class="entry-category">
								<?php echo g7_first_category(); ?>
							</span>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</header>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>

	<?php else : ?>

		<div class="row featured">
			<?php while ($featured->have_posts()) : $featured->the_post(); ?>
				<?php if ($i == 1) : ?>
					<div class="post col-sm-6 cat-<?php echo g7_first_category_ID(); ?>">
						<a href="<?php the_permalink(); ?>">
							<?php echo g7_image($image_w1, $image_h1, false); ?>
						</a>
						<header>
							<span class="entry-category">
								<?php echo g7_first_category(); ?>
							</span>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</header>
					</div>
				<?php else : ?>
					<div class="post col-sm-3 col-xs-6 cat-<?php echo g7_first_category_ID(); ?>">
						<a href="<?php the_permalink(); ?>">
							<?php echo g7_image($image_w2, $image_h2, false); ?>
						</a>
						<header>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</header>
					</div>
				<?php endif; ?>
			<?php $i++; endwhile; ?>
		</div>

	<?php endif; ?>

<?php wp_reset_postdata(); endif; ?>
