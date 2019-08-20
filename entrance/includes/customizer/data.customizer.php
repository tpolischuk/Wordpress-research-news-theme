<?php
$customizer_data = array(
	'general' => array(
		'title'  => __('General', 'g7theme'),
		'fields' => array(
			'boxed' => array(
				'type'    => 'checkbox',
				'label'   => __('Enable boxed layout', ' g7theme'),
				'default' => 0,
			),
			'layout' => array(
				'type'    => 'select',
				'label'   => __('Default sidebar alignment', ' g7theme'),
				'default' => '1',
				'choices' => array(
					'1' => __('Right sidebar', 'g7theme'),
					'2' => __('Left sidebar', 'g7theme'),
					'3' => __('Full width (no sidebar)', 'g7theme'),
				),
			),
			'retina' => array(
				'type'    => 'checkbox',
				'label'   => __('Enable retina display support', ' g7theme'),
				'default' => 1,
			),
			'main_logo' => array(
				'type'  => 'image',
				'label' => __('Main logo', ' g7theme'),
			),
			'logo_height' => array(
				'type'    => 'text',
				'label'   => __('Main logo height (in pixels)', ' g7theme'),
				'default' => 60,
			),
			'login_logo' => array(
				'type'  => 'image',
				'label' => __('Login logo', ' g7theme'),
			),
			'favicon' => array(
				'type'  => 'image',
				'label' => __('Favicon', ' g7theme'),
			),
			'breadcrumb' => array(
				'type'    => 'checkbox',
				'label'   => __('Enable breadcrumb navigation', ' g7theme'),
				'default' => 1,
			),
			'breadcrumb_text' => array(
				'type'    => 'text',
				'label'   => __('Breadcrumb prefix', ' g7theme'),
				'default' => __('You are here:', 'g7theme'),
			),
			'gallery_pp' => array(
				'type'    => 'checkbox',
				'label'   => __('Enable prettyPhoto for gallery', 'g7theme'),
				'default' => 0,
			),
			'disable_zoom' => array(
				'type'    => 'checkbox',
				'label'   => __('Disable zoom effect on image', 'g7theme'),
				'default' => 0,
			),
		),
	),
	'featured' => array(
		'title'       => __('Featured Posts', 'g7theme'),
		'description' => sprintf(
			__('Use a tag to feature your posts. If no posts match the tag, <a href="%2$s">sticky posts</a> will be displayed instead.', 'g7theme'),
			admin_url('/edit.php?tag=featured'),
			admin_url('/edit.php?show_sticky=1')
		),
		'fields'      => array(
			'featured_show' => array(
				'type'    => 'checkbox',
				'label'   => __('Enable featured posts', 'g7theme'),
				'default' => 1,
			),
			'featured_tags' => array(
				'type'    => 'text',
				'label'   => __('Tag(s)', 'g7theme'),
				'default' => 'featured',
			),
			'featured_layout' => array(
				'type'    => 'select',
				'label'   => __('Layout', 'g7theme'),
				'default' => '1',
				'choices' => array(
					'1' => __('Grid', 'g7theme'),
					'2' => __('Slider', 'g7theme'),
				),
			),
		),
	),
	'slide' => array(
		'title'       => __('Slider', 'g7theme'),
		'description' => __('Slider settings, if you choose Slider layout in Featured Posts', 'g7theme'),
		'fields'      => array(
			'slider_limit' => array(
				'type'    => 'text',
				'label'   => __('Number of slides', 'g7theme'),
				'default' => '3',
				'size'    => '5',
			),
			'slider_animation' => array(
				'type'    => 'select',
				'label'   => __('Animation', 'g7theme'),
				'default' => 'fade',
				'choices' => array(
					'slide' => 'slide',
					'fade'  => 'fade',
				),
			),
			'slider_slideshowSpeed' => array(
				'type'    => 'text',
				'label'   => __('Slideshow Speed', 'g7theme'),
				'default' => '7000',
				'size'    => '5',
				'desc'    => __('speed of the slideshow cycling, in milliseconds', 'g7theme'),
			),
			'slider_animationSpeed' => array(
				'type'    => 'text',
				'label'   => __('Animation Speed', 'g7theme'),
				'default' => '600',
				'size'    => '5',
				'desc'    => __('speed of animations, in milliseconds', 'g7theme'),
			),
			'slider_pauseOnHover' => array(
				'type'    => 'checkbox',
				'label'   => __('Pause on Hover', 'g7theme'),
				'default' => 1,
			),
		),
	),
	'blog' => array(
		'title'       => __('Blog', 'g7theme'),
		'description' => __('Settings for default blog, category, tag, search result, author and archive pages. <br>For blog page templates, please use the settings on page edit screen', 'g7theme'),
		'fields'      => array(
			'blog_show_image' => array(
				'type'    => 'checkbox',
				'label'   => __('Show featured image', 'g7theme'),
				'default' => 1,
			),
			'blog_show_category' => array(
				'type'    => 'checkbox',
				'label'   => __('Show category', 'g7theme'),
				'default' => 1,
			),
			'blog_show_rating' => array(
				'type'    => 'checkbox',
				'label'   => __('Show rating (if available)', 'g7theme'),
				'default' => 1,
			),
			'blog_show_date' => array(
				'type'    => 'checkbox',
				'label'   => __('Show date', 'g7theme'),
				'default' => 1,
			),
			'blog_show_comments' => array(
				'type'    => 'checkbox',
				'label'   => __('Show comments number', 'g7theme'),
				'default' => 1,
			),
			'blog_show_author' => array(
				'type'    => 'checkbox',
				'label'   => __('Show author', 'g7theme'),
				'default' => 1,
			),
			'blog_show_readmore' => array(
				'type'    => 'checkbox',
				'label'   => __('Show read more link', 'g7theme'),
				'default' => 0,
			),
			'blog_content' => array(
				'type'    => 'select',
				'label'   => __('Show post content', 'g7theme'),
				'default' => 1,
				'choices' => array(
					'0' => __('No', 'g7theme'),
					'1' => __('Excerpt', 'g7theme'),
					'2' => __('Full Content', 'g7theme'),
				),
			),
			'blog_excerpt' => array(
				'type'    => 'text',
				'label'   => __('Excerpt length', 'g7theme'),
				'default' => '40',
			),
		),
	),
	'single' => array(
		'title'  => __('Single Post', 'g7theme'),
		'fields' => array(
			'single_category' => array(
				'type'    => 'checkbox',
				'label'   => __('Show category', 'g7theme'),
				'default' => 1,
			),
			'single_date' => array(
				'type'    => 'checkbox',
				'label'   => __('Show date', 'g7theme'),
				'default' => 1,
			),
			'single_author' => array(
				'type'    => 'checkbox',
				'label'   => __('Show author name', 'g7theme'),
				'default' => 1,
			),
			'single_featured_image' => array(
				'type'    => 'checkbox',
				'label'   => __('Show featured image', 'g7theme'),
				'default' => 1,
			),
			'single_tags' => array(
				'type'    => 'checkbox',
				'label'   => __('Show tags', 'g7theme'),
				'default' => 1,
			),
			'single_author_info' => array(
				'type'    => 'checkbox',
				'label'   => __('Show author info', 'g7theme'),
				'default' => 1,
			),
			'single_related' => array(
				'type'    => 'checkbox',
				'label'   => __('Show related posts', 'g7theme'),
				'default' => 1,
			),
		),
	),
	'footer' => array(
		'title'  => __('Footer', 'g7theme'),
		'fields' => array(
			'footer_widget' => array(
				'type'    => 'checkbox',
				'label'   => __('Show footer widget area', 'g7theme'),
				'default' => 1,
			),
			'footer_text_1' => array(
				'type'    => 'text',
				'label'   => __('Footer text 1', 'g7theme'),
				'default' => __('Copyright text', 'g7theme'),
			),
			'footer_text_2' => array(
				'type'    => 'text',
				'label'   => __('Footer text 2', 'g7theme'),
				'default' => __('Powered by WordPress', 'g7theme'),
			),
		),
	),
	'main_color' => array(
		'title'  => __('Main Color', 'g7theme'),
		'fields' => array(
			'main_color' => array(
				'type'    => 'color',
				'label'   => __('Main color', 'g7theme'),
				'default' => '#e74c3c',
			),
		),
	),
);

$cst_cat = get_categories(array(
	'orderby'    => 'name',
	'order'      => 'ASC',
	'hide_empty' => 0,
));

$customizer_data['cat_colors'] = array(
	'title'       => __('Category Colors', 'g7theme'),
	'description' => __('Custom color for categories. If disabled, main color will be used for all categories', 'g7theme'),
	'fields'      => array(
		'cat_color' => array(
			'type'    => 'checkbox',
			'label'   => __('Enable category colors', 'g7theme'),
			'default' => 0,
		),
	)
);
foreach ((array)$cst_cat as $category) {
	$customizer_data['cat_colors']['fields']['cat_' . $category->cat_ID . '_color'] = array(
		'type'  => 'color',
		'label' => $category->name,
	);
}
