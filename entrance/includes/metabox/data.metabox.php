<?php
$sidebars['sidebar'] = 'Default Sidebar';
foreach ((array)g7_option('sidebar') as $v) {
	if (trim($v) == '') {
		continue;
	}
	$sidebars[$v] = $v;
}

$meta_boxes['layout_metabox'] = array(
	'title'    => __('Layout Options', 'g7theme'),
	'pages'    => array('page', 'post'),
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		'layout' => array(
			'type'    => 'select',
			'name'    => __('Page layout', 'g7theme'),
			'options' => array(
				'0' => __('Default', 'g7theme'),
				'1' => __('Right sidebar', 'g7theme'),
				'2' => __('Left sidebar', 'g7theme'),
				'3' => __('Full width (no sidebar)', 'g7theme'),
			),
		),
		'sidebar' => array(
			'type'    => 'select',
			'name'    => __('Sidebar', 'g7theme'),
			'options' => $sidebars,
		),
	)
);

$meta_boxes['page_metabox'] = array(
	'title'     => __('Page Options', 'g7theme'),
	'pages'     => array('page'),
	'templates' => array('page-blog.php', 'page-builder.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		'page_show_title' => array(
			'type'    => 'checkbox',
			'name'    => __('Show page title', 'g7theme'),
			'default' => 0,
		),
		'page_show_content' => array(
			'type'    => 'checkbox',
			'name'    => __('Show page content', 'g7theme'),
			'default' => 0,
		),
	)
);

$meta_boxes['blog_metabox'] = array(
	'title'     => __('Blog Options', 'g7theme'),
	'pages'     => array('page'),
	'templates' => array('page-blog.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		'blog_style' => array(
			'type'    => 'select',
			'name'    => __('Blog style', 'g7theme'),
			'default' => '1',
			'options' => array(
				'1' => __('Small Thumbnails', 'g7theme'),
				'2' => __('Large Thumbnails', 'g7theme'),
				'3' => __('Grid', 'g7theme'),
				'4' => __('Masonry', 'g7theme'),
			),
		),
		'blog_col' => array(
			'type'    => 'select',
			'name'    => __('Number of columns', 'g7theme'),
			'default' => '2',
			'desc'    => __('For grid/masonry style only', 'g7theme'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
		),
		'blog_category' => array(
			'type' => 'category',
			'name' => __('Filter by category', 'g7theme'),
		),
		'blog_num' => array(
			'type' => 'text',
			'name' => __('Number of posts per page', 'g7theme'),
			'size' => 2,
			'desc' => __('Leave this blank if you want to use default setting', 'g7theme') . ' (<a href="' . admin_url('options-reading.php') . '" target="_blank">Reading Settings</a>)',
		),
		'blog_post_options' => array(
			'type'  => 'title',
			'label' => __('Post Options:', 'g7theme'),
		),
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
			'default' => 0,
		),
		'blog_show_readmore' => array(
			'type'    => 'checkbox',
			'label'   => __('Show read more link', 'g7theme'),
			'default' => 0,
		),
		'blog_content' => array(
			'type'    => 'select',
			'name'    => __('Show post content', 'g7theme'),
			'default' => 1,
			'options' => array(
				'0' => 'No',
				'1' => 'Excerpt',
				'2' => 'Full Content',
			),
		),
		'blog_excerpt' => array(
			'type'    => 'text',
			'name'    => __('Excerpt length', 'g7theme'),
			'size'    => 2,
			'default' => '20',
			'desc'    => __('The number of words for excerpt', 'g7theme'),
		),
	)
);

$meta_boxes['pagebuilder_metabox'] = array(
	'title'     => __('Content Builder', 'g7theme'),
	'pages'     => array('page'),
	'templates' => array('page-builder.php'),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		'cat' => array(
			'type'        => 'builder',
			'default_num' => 4,
			'desc'        => __("<p>Click on the <b>Add Block</b> button to add a new block to your page.</p>
<p>You can move blocks by dragging up/down on each block.</p>
<p>Click the down arrow in the upper right corner of each block to expand the block's interface and customize the settings.</p>
<p>To delete the block, click <b>Delete</b>.</p>", 'g7theme')
		),
	)
);

if (g7_option('enable_review')) {
	$meta_boxes['review_metabox'] = array(
		'title'    => __('Review', 'g7theme'),
		'pages'    => array('post'),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			'review_post' => array(
				'type'  => 'checkbox',
				'label' => __('Enable review on this post', 'g7theme'),
			),
			'criteria' => array(
				'type' => 'rating',
				'id2'  => 'rating',
				'id3'  => 'overall_rating',
				'min'  => '0',
				'max'  => '5',
				'step' => '0.5',
			),
			'summary' => array(
				'type' => 'textarea',
				'name' => __('Summary', 'g7theme'),
				'cols' => 80,
				'rows' => 6,
			),
		)
	);
}

$meta_boxes['single'] = array(
	'title'    => __('Featured Image Options', 'g7theme'),
	'pages'    => array('post', 'page'),
	'context'  => 'normal',
	'priority' => 'high',
	'fields'   => array(
		'featured_image' => array(
			'type'    => 'select',
			'name'    => __('Featured image view', 'g7theme'),
			'default' => '1',
			'options' => array(
				'1' => __('Show image', 'g7theme'),
				'2' => __('Show full height (uncropped) image', 'g7theme'),
				'3' => __('Hide image', 'g7theme'),
			)
		)
	)
);
