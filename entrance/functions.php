<?php
/**
 * Theme name and options field name
 */
define('G7_NAME', 'Entrance');
define('G7_FOLDERNAME', 'entrance');
define('G7_OPTIONNAME', 'entrance_options');

/**
 * Theme directory and url
 */
define('PARENT_DIR', get_template_directory());
define('PARENT_URL', get_template_directory_uri());
define('CHILD_DIR', get_stylesheet_directory());
define('CHILD_URL', get_stylesheet_directory_uri());

/**
 * honeypot field name, for contact form spam protection
 */
defined('HONEYPOT_FIELD') || define('HONEYPOT_FIELD', 'contact_body');


/**
 * Sets up the content width
 */
if (!isset($content_width)) {
	$content_width = 680;
}


/**
 * Get options from database
 */
if (!function_exists('g7_option')) {
	$g7_option = get_option(G7_OPTIONNAME);
	function g7_option($v, $default = '') {
		global $g7_option;
		if (!empty($g7_option[$v])) {
			if (is_string($g7_option[$v])) {
				return stripslashes($g7_option[$v]);
			}
			return $g7_option[$v];
		} else {
			return $default;
		}
	}
}


require_once PARENT_DIR . '/includes/ajax_action.php';
require_once PARENT_DIR . '/includes/aq_resizer.php';
require_once PARENT_DIR . '/includes/options/options.php';
require_once PARENT_DIR . '/includes/customizer/customizer.php';
require_once PARENT_DIR . '/includes/metabox/metabox.php';
require_once PARENT_DIR . '/includes/widgets.php';
require_once PARENT_DIR . '/includes/class-tgm-plugin-activation.php';


/**
 * Theme setup
 * register various WordPress features
 */
if (!function_exists('g7_theme_setup')) {
	function g7_theme_setup() {
		// Language localization
		load_theme_textdomain('g7theme', PARENT_DIR . '/lang');
		$locale = get_locale();
		$locale_file = PARENT_DIR . "/lang/$locale.php";
		if (is_readable($locale_file)) {
			require_once $locale_file;
		}

		// Add support for custom backgrounds
		add_theme_support('custom-background');

		// Activate post thumbnails
		add_theme_support('post-thumbnails');

		// automatic feed links
		add_theme_support('automatic-feed-links');

		// Add menu location
		register_nav_menus(array(
			'mainmenu' => __('Main Menu', 'g7theme')
		));

		// Add support for post formats
		add_theme_support('post-formats', array(
			'gallery',
			'image',
			'video',
		));
	}
	add_action('after_setup_theme', 'g7_theme_setup');
}


/**
 * Enqueue all javascript files used in public
 */
if (!function_exists('g7_scripts')) {
	function g7_scripts() {
		wp_enqueue_script('jquery', false, array(), false, true);
		wp_enqueue_script('jquery-masonry', false, array('jquery'), false, true);
		wp_enqueue_script('easing', PARENT_URL . '/js/jquery.easing.1.3.js', array('jquery'), false, true);
		wp_enqueue_script('fitvids', PARENT_URL . '/js/jquery.fitvids.js', array('jquery'), false, true);
		wp_enqueue_script('prettyPhoto', PARENT_URL . '/js/jquery.prettyPhoto.js', array('jquery'), false, true);
		wp_enqueue_script('mobilemenu', PARENT_URL . '/js/jquery.mobilemenu.js', array('jquery'), false, true);
		if (g7_show_slider()) {
			wp_enqueue_script('flex', PARENT_URL . '/js/jquery.flexslider-min.js', array('jquery'), false, true);
		}
		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply', false, array(), false, true);
		}
		if (get_theme_mod('retina', 1)) {
			wp_enqueue_script('retina', PARENT_URL . '/js/retina.min.js', array(), false, true);
		}
		wp_enqueue_script('scripts', PARENT_URL . '/js/scripts.js', array('jquery'), false, true);
		wp_localize_script('scripts', 'g7', array(
			'ajaxurl'               => admin_url('admin-ajax.php'),
			'slider_animation'      => get_theme_mod('slider_animation', 'fade'),
			'slider_slideshowSpeed' => get_theme_mod('slider_slideshowSpeed', '7000'),
			'slider_animationSpeed' => get_theme_mod('slider_animationSpeed', '600'),
			'slider_pauseOnHover'   => get_theme_mod('slider_pauseOnHover', 1) ? true : false,
			'navigate_text'         => __('Navigate to...', 'g7theme'),
			'rtl'                   => is_rtl(),
		));
	}
	add_action('wp_enqueue_scripts', 'g7_scripts');
}


/**
 * Check show featured posts
 */
if (!function_exists('g7_show_featured')) {
	function g7_show_featured() {
		if (is_front_page() && get_theme_mod('featured_show', 1)) {
			return true;
		}
		return false;
	}
}


/**
 * Check show slider
 */
if (!function_exists('g7_show_slider')) {
	function g7_show_slider() {
		if (g7_show_featured() && get_theme_mod('featured_layout', 1) == 2) {
			return true;
		}
		if (!empty($_GET['slider'])) {
			return true;
		}
		return false;
	}
}


/**
 * Use external CSS
 */
if (!function_exists('g7_use_external_css')) {
	function g7_use_external_css() {
		return !is_user_logged_in();
	}
}


/**
 * Enqueue all css files used in public
 */
if (!function_exists('g7_styles')) {
	function g7_styles() {
		wp_enqueue_style('bootstrap', PARENT_URL . '/css/bootstrap.min.css');
		wp_enqueue_style('font-awesome', PARENT_URL . '/css/font-awesome.min.css');
		wp_enqueue_style('font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,300italic,400italic,600italic,700italic&subset=latin,cyrillic');
		if (g7_show_slider()) {
			wp_enqueue_style('flexslider', PARENT_URL . '/css/flexslider.css');
		}
		wp_enqueue_style('main-style', get_stylesheet_uri(), array(), '1.4');
		if (g7_use_external_css()) {
			wp_enqueue_style('color', esc_url(home_url('/')) . '?css=1');
		}
		wp_enqueue_style('prettyPhoto', PARENT_URL . '/css/prettyPhoto.css');
	}
	add_action('wp_enqueue_scripts', 'g7_styles');
}


/**
 * Custom styles
 */
if (!function_exists('g7_custom_styles')) {
	function g7_custom_styles() {
		if (get_theme_mod('main_logo')) {
			$logo_height = get_theme_mod('logo_height', 60) . 'px';
			$css[] = "#logo img { max-height: $logo_height; }";
		}
		if (!get_theme_mod('disable_zoom', 0)) {
			$css[] = '.row.featured .post > a:hover img { transform: scale(1.2); -webkit-transform: scale(1.2); }';
			$css[] = '.block-top > a:hover img, .block-side > a:hover img, .posts .entry-image > a:hover img { transform: scale(1.2); -webkit-transform: scale(1.2); }';
		}
		$kolor = get_theme_mod('main_color', '#e74c3c');
		$css[] = "a { color: $kolor; }";
		$css[] = "#mainmenu > li:hover > a, #mainmenu ul { background-color: $kolor }";
		$css[] = "#mainmenu > li.current-menu-item > a, #mainmenu > li.current_page_item > a, #mainmenu > li.current-menu-ancestor > a, #mainmenu > li.current_page_ancestor > a { background-color: $kolor; }";
		$css[] = ".block-title a, .block-title span { background-color: $kolor; }";
		$css[] = ".block-more > a:hover { color: $kolor; }";
		$css[] = ".entry-image .entry-category a { background-color: $kolor; }";
		$css[] = ".block-heading > a:hover,";
		$css[] = ".posts .entry-title a:hover { color: $kolor; }";
		$css[] = ".block-meta a:hover, .entry-meta a:hover { color: $kolor; }";
		$css[] = ".widget li > a:hover { color: $kolor; }";
		$css[] = ".btn, input[type='reset'], input[type='submit'], #submit { background-color: $kolor; }";
		$css[] = ".btn.readmore:hover { border-color: $kolor; background-color: $kolor; }";
		$css[] = ".next-prev a:hover { color: $kolor; }";
		$css[] = ".author-link a:hover { color: $kolor; }";
		$css[] = ".featured .entry-category a { background-color: $kolor; }";
		$css[] = ".block-top:hover, .block-1 .block-side:hover, .posts .entry-image:hover { border-color: $kolor; }";
		$css[] = ".pagination span.current, .pagination a:hover { background-color: $kolor; }";
		$css[] = ".searchsubmit { background-color: $kolor }";
		$css[] = ".widgettitle span { border-color: $kolor }";
		$css[] = ".flexslider .flex-control-paging li a.flex-active { background-color: $kolor }";
		if (get_theme_mod('cat_color', 0)) {
			$categories = get_categories(array(
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => 0,
			));
			foreach ((array)$categories as $category) {
				$cat_id = $category->cat_ID;
				$color = get_theme_mod("cat_{$cat_id}_color");
				if (!empty($color)) {
					$css[] = ".cat-$cat_id .entry-image .entry-category a { background: $color; }";
					$css[] = ".cat-$cat_id .entry-main .entry-category a { color: $color; }";
					$css[] = "#wrapper .cat-$cat_id a:hover { color: $color; }";
					$css[] = ".cat-$cat_id a.readmore:hover { color: #fff; border-color: $color; background: $color; }";
					$css[] = ".cat-$cat_id .block-title a { background: $color; }";
					$css[] = ".featured .cat-$cat_id .entry-category a { background: $color; }";
					$css[] = ".cat-$cat_id .block-top:hover, .block-1.cat-$cat_id .block-side:hover, .posts .cat-$cat_id .entry-image:hover { border-color: $color; }";
					$css[] = ".cat-$cat_id .block-category a { color: $color; }";
				}
			}
		}
		return implode("\n", $css);
	}
}


/**
 * Custom query var
 */
if (!function_exists('g7_query_vars')) {
	function g7_query_vars($vars) {
		$vars[] = 'css';
		return $vars;
	}
	add_filter('query_vars', 'g7_query_vars');
}


/**
 * Custom CSS (external)
 */
if (!function_exists('g7_external_css')) {
	function g7_external_css() {
		$var = get_query_var('css');
		if ($var == '1') {
			header('Content-Type: text/css');
			header('Pragma: cache');
			header('Cache-Control: max-age=86400, must-revalidate');
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
			echo g7_custom_styles();
			exit;
		}
	}
	add_action('template_redirect', 'g7_external_css');
}


/**
 * Custom CSS (embedded)
 */
if (!function_exists('g7_embedded_css')) {
	function g7_embedded_css() {
		$style = '<style type="text/css">';
		$style .= g7_custom_styles();
		$style .= '</style>';
		echo $style;
	}
	if (!g7_use_external_css()) {
		add_action('wp_head', 'g7_embedded_css');
	}
}


/**
 * Update notifier
 */
if (g7_option('update_notifier')) {
	include_once PARENT_DIR . '/update-notifier.php';
}


/**
 * custom excerpt more
 */
if (!function_exists('g7_excerpt_more')) {
	function g7_excerpt_more($more) {
		return '';
	}
	add_filter('excerpt_more', 'g7_excerpt_more');
}


/**
 * custom excerpt length
 */
if (!function_exists('g7_excerpt_length')) {
	function g7_excerpt_length($length) {
		return 100;
	}
	add_filter('excerpt_length', 'g7_excerpt_length');
}


/**
 * Add custom body class
 */
if (!function_exists('g7_body_class')) {
	function g7_body_class($classes) {
		if (get_theme_mod('boxed', 0) || !empty($_GET['boxed'])) {
			$classes[] = 'boxed';
		} else {
			$classes[] = 'stretched';
		}
		return $classes;
	}
	add_filter('body_class', 'g7_body_class');
}


/**
 * Shows a featured image from a post
 */
if (!function_exists('g7_image')) {
	function g7_image($w, $h, $link = true, $overlay = true) {
		if (!has_post_thumbnail()) {
			return '';
		}
		$thumb   = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url($thumb, 'full');
		$image   = aq_resize($img_url, $w, $h, true, true, true);
		if (empty($image)) {
			return '';
		}

		if ($link) {
			$link_open  = '<a href="'.get_permalink().'">';
			$link_close = '</a>';
			$icon       = 'fa-plus-circle';
			switch (get_post_format()) {
				case 'image':
					$icon       = 'fa-picture-o';
					$link_open  = '<a href="'.$img_url.'" data-rel="prettyPhoto">';
					$link_close = '</a>';
					break;
				case 'video':
					$icon = 'fa-play-circle';
					break;
			}
		} else {
			$link_open  = '';
			$link_close = '';
			$overlay    = false;
		}

		return sprintf(
			'%s<img src="%s" alt="%s" width="%s" height="%s">%s%s',
			$link_open,
			$image,
			esc_attr(get_the_title()),
			$w,
			$h,
			$overlay ? '<div class="overlay"><i class="fa ' . $icon . '"></i></div>' : '',
			$link_close
		);
	}
}


/**
 * Image sizes for featured posts
 */
if (!function_exists('g7_image_sizes')) {
	function g7_image_sizes($type = 'thumb') {
		$sizes = array(
			'thumb'       => array(70, 70),
			'small'       => array(200, 150),
			'large'       => array(720, 320),
			'full'        => array(1040, 440),
			'single'      => array(720, 320),
			'single-full' => array(1040, 440),
			'grid'        => array(480, 285),
			'masonry'     => array(480, null),
			'featured1'   => array(520, 400),
			'featured2'   => array(390, 300),
			'widget'      => array(300, 150),
		);
		return $sizes[$type];
	}
}


/**
 * Custom favicon
 */
if (!function_exists('g7_favicon')) {
	function g7_favicon() {
		if (get_theme_mod('favicon')) {
			echo '<link rel="Shortcut Icon" type="image/x-icon" href="' . get_theme_mod('favicon') . '">';
		}
	}
	add_action('wp_head', 'g7_favicon');
}


/**
 * Custom login logo
 */
if (!function_exists('g7_login_logo')) {
	function g7_login_logo() {
		if (get_theme_mod('login_logo')) {
			echo '<style type="text/css">
			.login h1 a { background-image: url(' . get_theme_mod('login_logo') . ') !important; }
			</style>';
		}
	}
	add_action('login_head', 'g7_login_logo');
}


/**
 * Get page layout
 * right sidebar, left sidebar, or full width
 */
if (!function_exists('g7_page_layout')) {
	function g7_page_layout() {
		$default_layout = get_theme_mod('layout', 1);
		if (is_page() || is_single()) {
			$g7_layout = get_post_meta(get_the_ID(), '_g7_layout', true);
			if (empty($g7_layout)) {
				return $default_layout;
			}
			return $g7_layout;
		}
		return $default_layout;
	}
}


/**
 * Shows related posts
 */
if (!function_exists('g7_related_posts')) {
	function g7_related_posts($post_id, $number = 4) {
		$have_posts = false;
		$t          = array();
		$tags       = get_the_tags();
		if ($tags) {
			foreach($tags as $tag) {
				$t[] = $tag->term_id;
			}
		}
		if (!empty($t)) {
			$related = new WP_Query(array(
				'posts_per_page' => $number,
				'post__not_in'   => array($post_id),
				'tag__in'        => $t,
			));
			if ($related->have_posts()) {
				$have_posts = true;
			}
		}
		if (!$have_posts) {
			$c = array();
			foreach(get_the_category() as $cat) {
				$c[] = $cat->cat_ID;
			}
			if (!empty($c)) {
				$related = new WP_Query(array(
					'posts_per_page' => $number,
					'post__not_in'   => array($post_id),
					'category__in'   => $c,
				));
				if ($related->have_posts()) {
					$have_posts = true;
				}
			}
		}
		list($image_w, $image_h) = g7_image_sizes('small');
		?>
		<?php if ($have_posts) : ?>
			<ul class="row block">
				<?php while ($related->have_posts()) : $related->the_post(); ?>
				<li class="col-xs-3 post">
					<div class="block-top">
						<?php echo g7_image($image_w, $image_h); ?>
					</div>
					<div class="block-content">
						<h4 class="block-heading">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h4>
					</div>
				</li>
				<?php endwhile; ?>
			</ul>
		<?php else: ?>
			<span class="norelated"><?php _e('No related posts found', 'g7theme'); ?>.</span>
		<?php endif; ?>
		<?php
		wp_reset_postdata();
	}
}


/**
 * Pagination function
 *
 * @param string $pages
 * @param int $range
 * @author Kriesi
 * @link http://www.kriesi.at/archives/how-to-build-a-wordpress-post-pagination-without-plugin
 */
if (!function_exists('g7_pagination')) {
	function g7_pagination($pages = '', $range = 3, $numbered = true) {
		if (!$numbered) {
			echo '<div class="pagination">' . get_posts_nav_link() . '</div>';
			return;
		}
		$showitems = ($range * 2) + 1;
		global $paged;
		if (empty($paged)) {
			$paged = 1;
		}
		if ($pages == '') {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if (!$pages) {
				$pages = 1;
			}
		}
		if (1 != $pages) {
			echo '<div class="pagination">';
			if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
				echo '<a href="'.get_pagenum_link(1).'"><span class="arrows">&laquo;</span> ' . __('First', 'g7theme') . '</a>';
			}
			if ($paged > 1 && $showitems < $pages) {
				echo '<a href="'.get_pagenum_link($paged - 1).'"><span class="arrows">&lsaquo;</span> ' . __('Previous', 'g7theme') . '</a>';
			}
			for ($i = 1; $i <= $pages; $i++) {
				if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
					if ($paged == $i) {
						echo '<span class="current">'.$i.'</span>';
					} else {
						echo '<a href="'.get_pagenum_link($i).'" class="inactive">'.$i.'</a>';
					}
				}
			}
			if ($paged < $pages && $showitems < $pages) {
				echo '<a href="'.get_pagenum_link($paged + 1).'">' . __('Next', 'g7theme') . ' <span class="arrows">&rsaquo;</span></a>';
			}
			if ($paged < $pages-1 && $paged + $range - 1 < $pages && $showitems < $pages) {
				echo '<a href="'.get_pagenum_link($pages).'">' . __('Last', 'g7theme') . ' <span class="arrows">&raquo;</span></a>';
			}
			echo "</div>\n";
		}
	}
}


/**
 * Comment List
 * called from comments.php
 */
if (!function_exists('g7_commentlist')) {
	function g7_commentlist($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		switch ($comment->comment_type) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e('Pingback:', 'g7theme'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'g7theme'), '<span class="edit-link">', '</span>'); ?></p>
		<?php
				break;
			default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<a class="comment-avatar" href="<?php comment_author_url(); ?>"><?php echo get_avatar($comment, 45); ?></a>
				<div class="comment-content">
					<footer>
						<span class="fn"><?php comment_author_link(); ?></span>
						<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
							<time pubdate datetime="<?php comment_time('c'); ?>">
								<?php printf(__('%1$s at %2$s', 'g7theme'), get_comment_date(), get_comment_time()); ?>
							</time>
						</a>
						<?php edit_comment_link(__('(Edit)', 'g7theme'), '<span class="edit-link">', '</span>'); ?>
					</footer>
					<?php comment_text(); ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<div class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'g7theme'); ?></div>
					<?php endif; ?>
					<?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'g7theme'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
				</div>
			</article><!-- #comment-## -->
		<?php
				break;
		endswitch;
	}
}


/**
 * Check show breadcrumb
 */
if (!function_exists('g7_show_breadcrumb')) {
	function g7_show_breadcrumb() {
		if (is_home() || is_front_page()) {
			return false;
		}
		return true;
	}
}


/**
 * Breadcrumb navigation
 */
if (!function_exists('g7_breadcrumb')) {
	function g7_breadcrumb() {
		if (!get_theme_mod('breadcrumb', 1)) {
			return;
		}
		if (g7_show_breadcrumb()) {
			$separator     = is_rtl() ? "&lsaquo;" : "&rsaquo;";
			$name          = __('Home', 'g7theme');
			$currentBefore = '<span>';
			$currentAfter  = '</span>';
			echo '<div class="breadcrumb">';
			if (get_theme_mod('breadcrumb_text')) {
				echo '<span class="breadcrumb-lead">';
				echo get_theme_mod('breadcrumb_text');
				echo '</span> ';
			}
			global $post;
			$home = esc_url(home_url('/'));
			echo '<a href="' . $home . '">' . $name . '</a> ' . $separator . ' ';
			if (is_tax()) {
				$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				echo $currentBefore . $term->name . $currentAfter;
			} elseif (is_category()) {
				global $wp_query;
				$cat_obj = $wp_query->get_queried_object();
				$thisCat = $cat_obj->term_id;
				$thisCat = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);
				if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $separator . ' '));
				_e('Category Archives', 'g7theme');
				echo " $separator ";
				echo $currentBefore . '';
				single_cat_title();
				echo '' . $currentAfter;
			} elseif (is_day()) {
				echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $separator . ' ';
				echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $separator . ' ';
				echo $currentBefore . get_the_time('d') . $currentAfter;
			} elseif (is_month()) {
				echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $separator . ' ';
				echo $currentBefore . get_the_time('F') . $currentAfter;
			} elseif (is_year()) {
				echo $currentBefore . get_the_time('Y') . $currentAfter;
			} elseif (is_single()) {
				$postType = get_post_type();
				if ($postType == 'post') {
					$cat = get_the_category(); $cat = $cat[0];
					echo get_category_parents($cat, TRUE, ' ' . $separator . ' ');
				}
				echo $currentBefore;
				the_title();
				echo $currentAfter;
			} elseif (is_page() && !$post->post_parent) {
				echo $currentBefore;
				the_title();
				echo $currentAfter;
			} elseif (is_page() && $post->post_parent) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
					$parent_id  = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach ($breadcrumbs as $crumb) {
					echo $crumb . ' ' . $separator . ' ';
				}
				echo $currentBefore;
				the_title();
				echo $currentAfter;
			} elseif (is_search()) {
				echo $currentBefore . __('Search Results for:', 'g7theme'). ' &quot;' . get_search_query() . '&quot;' . $currentAfter;
			} elseif (is_tag()) {
				echo $currentBefore . __('Post Tagged with:', 'g7theme'). ' &quot;';
				single_tag_title();
				echo '&quot;' . $currentAfter;
			} elseif (is_author()) {
				global $author;
				$userdata = get_userdata($author);
				echo $currentBefore . __('Author Archives', 'g7theme') . $currentAfter;
			} elseif (is_404()) {
				echo $currentBefore . __('Page Not Found', 'g7theme') . $currentAfter;
			}
			echo '</div>';
		}
	}
}


/**
 * Shows menu from a location
 */
if (!function_exists('g7_menu')) {
	function g7_menu($location, $class = '') {
		if (has_nav_menu($location)) {
			wp_nav_menu(array(
				'theme_location' => $location,
				'container'      => '',
				'menu_id'        => $location,
				'menu_class'     => $class,
			));
		} else {
			echo '<ul id="' . $location . '"><li><a href="' . esc_url(home_url('/')) . '">Home</a>';
			wp_list_pages('title_li=');
			echo '</ul>';
		}
	}
}


/**
 * Insert footer scripts
 */
if (!function_exists('g7_footer_scripts')) {
	function g7_footer_scripts() {
		echo g7_option('footer_scripts');
	}
	add_action('wp_footer', 'g7_footer_scripts', 100);
}


/**
 * get post rating
 */
if (!function_exists('g7_post_rating')) {
	function g7_post_rating() {
		$post_id     = get_the_ID();
		$review_post = get_post_meta($post_id, '_g7_review_post', true);
		if (!$review_post) {
			return;
		}
		$overall_rating = get_post_meta($post_id, '_g7_overall_rating', true);
		return g7_rating($overall_rating);
	}
}


/**
 * get star icons of rating
 */
if (!function_exists('g7_rating')) {
	function g7_rating($rating) {
		if (empty($rating)) {
			return;
		}
		$round = round(($rating * 2), 0) / 2; //rounding to nearest half
		$html = sprintf('<div class="rating" title="%s">', $rating);
		for ($i = 1; $i <= 5; $i++) {
			if ($i <= $round) {
				$html .= '<i class="fa fa-star"></i>';
			} elseif ($i - $round == 0.5) {
				$html .= '<i class="fa fa-star-half-o"></i>';
			} else {
				$html .= '<i class="fa fa-star-o"></i>';
			}
		}
		$html .= '</div>';
		return $html;
	}
}


/**
 * get sidebar ID from the sidebar name
 */
if (!function_exists('g7_sidebar_id')) {
	function g7_sidebar_id($sidebar_name) {
		$sidebar_id = str_replace(' ', '', $sidebar_name);
		$sidebar_id = strtolower($sidebar_id);
		return $sidebar_id;
	}
}


/**
 * get the ID of first category
 */
if (!function_exists('g7_first_category_ID')) {
	function g7_first_category_ID() {
		$category = get_the_category();
		return $category[0]->cat_ID;
	}
}

/**
 * get the name of first category
 */
if (!function_exists('g7_first_category')) {
	function g7_first_category() {
		$category = get_the_category();
		$category_name = '';
		if ($category[0]) {
			$category_name = sprintf(
				'<a href="%s">%s</a>',
				get_category_link($category[0]->term_id),
				$category[0]->cat_name
			);
		}
		return $category_name;
	}
}


/**
 * Get metadata
 */
if (!function_exists('g7_meta')) {
	function g7_meta($v, $default = '') {
		global $g7_meta;
		if (isset($g7_meta['_g7_' . $v][0])) {
			return $g7_meta['_g7_' . $v][0];
		} else {
			return $default;
		}
	}
}

/**
 * get post content
 * @param  string $type        excerpt or full content
 * @param  int $excerpt_length number of words for excerpt
 * @return string
 */
if (!function_exists('g7_post_content')) {
	function g7_post_content($type, $excerpt_length) {
		switch ($type) {
			case '1':
				$length = (int)$excerpt_length;
				$length = $length == 0 ? 20 : $length;
				$post_content = wp_trim_words(get_the_excerpt(), $length);
				break;
			case '2':
				$post_content = get_the_content();
				$post_content = apply_filters('the_content', $post_content);
				$post_content = str_replace(']]>', ']]&gt;', $post_content);
				break;
			default:
				$post_content = '';
				break;
		}
		return $post_content;
	}
}


/**
 * get date meta
 */
if (!function_exists('g7_date_meta')) {
	function g7_date_meta($schema = false) {
		return sprintf(
			'<span class="entry-date updated">
				<a href="%s">
					<i class="fa fa-clock-o"></i>
					<time datetime="%s"%s>%s</time>
				</a>
			</span>',
			get_permalink(),
			get_the_time('Y-m-d H:i:s'),
			$schema ? ' itemprop="datePublished"' : '',
			get_the_time(get_option('date_format'))
		);
	}
}


/**
 * get comments meta
 */
if (!function_exists('g7_comments_meta')) {
	function g7_comments_meta() {
		return sprintf(
			'<span class="entry-comments">
				<a href="%s">
					<i class="fa fa-comments-o"></i>
					%s
				</a>
			</span>',
			get_comments_link(),
			get_comments_number()
		);
	}
}


/**
 * get author meta
 */
if (!function_exists('g7_author_meta')) {
	function g7_author_meta($schema = false) {
		return sprintf(
			'<span class="vcard">
				<a class="url fn" href="%s">
					<i class="fa fa-user"></i>
					<span%s>%s</span>
				</a>
			</span>',
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			$schema ? ' itemprop="author"' : '',
			get_the_author()
		);
	}
}


/**
 * Logo / site title & description
 */
if (!function_exists('g7_site_title')) {
	function g7_site_title() {
		if (get_theme_mod('main_logo')) {
			$title = sprintf(
				'<img src="%s" alt="%s">',
				get_theme_mod('main_logo'),
				get_bloginfo('name')
			);
			$id = 'logo';
			$site_desc = '';
		} else {
			$title = get_bloginfo('name');
			$id = 'site-title';
			$site_desc = sprintf(
				'<h2 id="site-description">%s</h2>',
				get_bloginfo('description')
			);
		}

		$link = sprintf(
			'<a href="%s" rel="home">%s</a>',
			esc_url(home_url('/')),
			$title
		);

		if (is_front_page() || is_home()) {
			$site_title = '<h1 id="' . $id . '">' . $link . '</h1>';
		} else {
			$site_title = '<p id="' . $id . '">' . $link . '</p>';
		}

		return $site_title . $site_desc;
	}
}


/**
 * Required plugins
 */
if (!function_exists('g7_register_required_plugins')) {
	function g7_register_required_plugins() {
		$plugins = array(
			array(
				'name'               => 'G7 Shortcodes',
				'slug'               => 'g7-shortcodes',
				'source'             => PARENT_DIR . '/includes/plugins/g7-shortcodes.zip',
				'required'           => false,
				'version'            => '1.2',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
		);
		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);
		tgmpa($plugins, $config);
	}
	add_action('tgmpa_register', 'g7_register_required_plugins');
}


/**
 * Social media types
 * used in widgets and user profile
 */
if (!function_exists('g7_social_icons')) {
	function g7_social_icons() {
		return array(
			// icon name   => type
			'behance'      => 'behance',
			'delicious'    => 'delicious',
			'deviantart'   => 'deviantart',
			'digg'         => 'digg',
			'dribbble'     => 'dribbble',
			'facebook'     => 'facebook',
			'flickr'       => 'flickr',
			'foursquare'   => 'foursquare',
			'github-alt'   => 'github',
			'google-plus'  => 'google',
			'instagram'    => 'instagram',
			'linkedin'     => 'linkedin',
			'envelope-o'   => 'mail',
			'pinterest'    => 'pinterest',
			'rss'          => 'rss',
			'skype'        => 'skype',
			'soundcloud'   => 'soundcloud',
			'spotify'      => 'spotify',
			'stumbleupon'  => 'stumbleupon',
			'tumblr'       => 'tumblr',
			'twitter'      => 'twitter',
			'vimeo-square' => 'vimeo',
			'vk'           => 'vk',
			'wordpress'    => 'wordpress',
			'yahoo'        => 'yahoo',
			'youtube'      => 'youtube',
		);
	}
}


/**
 * Add social media fields in user profile
 */
if (!function_exists('g7_add_user_fields')) {
	function g7_add_user_fields($user_contact) {
		$social_types = g7_social_icons();
		unset($social_types['envelope-o']);
		unset($social_types['rss']);
		foreach ($social_types as $type) {
			$user_contact[$type] = ucfirst($type);
		}
		return $user_contact;
	}
	add_filter('user_contactmethods', 'g7_add_user_fields');
}


/**
 * Get social media fields for user profile
 */
if (!function_exists('g7_author_social_links')) {
	function g7_author_social_links() {
		$social_types = g7_social_icons();
		unset($social_types['envelope-o']);
		unset($social_types['rss']);
		$social = array();
		foreach ($social_types as $icon_name => $type) {
			$link = get_the_author_meta($type);
			if (!empty($link)) {
				$social[$type]['icon'] = $icon_name;
				$social[$type]['link'] = $link;
			}
		}
		return $social;
	}
}


/**
 * force gallery link to media file
 */
if (!function_exists('g7_attachment_link')) {
	function g7_attachment_link($link, $id) {
		if (is_feed() || is_admin()) {
			return $link;
		}
		$post = get_post($id);
		if ('image/' == substr($post->post_mime_type, 0, 6)) {
			return wp_get_attachment_url($id);
		} else {
			return $link;
		}
	}
	if (get_theme_mod('gallery_pp', 0)) {
		add_filter('attachment_link', 'g7_attachment_link', 10, 2);
	}
}


/**
 * add prettyPhoto to gallery
 */
if (!function_exists('g7_get_attachment_link')) {
	function g7_get_attachment_link($link) {
		if (strpos($link, 'a href') !== false) {
			$link = str_replace('a href', 'a data-rel="prettyPhoto[pp_gal]" href', $link);
		}
		return $link;
	}
	if (get_theme_mod('gallery_pp', 0)) {
		add_filter('wp_get_attachment_link', 'g7_get_attachment_link');
	}
}


/**
 * Customize color picker
 * - add custom color palette
 */
if (!function_exists('g7_colorpicker')) {
	function g7_colorpicker() {
		wp_enqueue_script('g7-colorpicker', PARENT_URL . '/js/colorpicker.js');
	}
	add_action('admin_enqueue_scripts', 'g7_colorpicker');
}
