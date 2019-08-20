<?php
$options_data = array(
	'general' => array(
		'title'  => __('General', 'g7theme'),
		'fields' => array(
			'update_notifier' => array(
				'type'  => 'checkbox',
				'title' => __('Theme Update Notifier', 'g7theme'),
				'label' => __('Enable', 'g7theme'),
				'std'   => '1',
				'desc'  => __('If enabled, you will get notified when a new theme update is available', 'g7theme'),
			),
			'enable_review' => array(
				'type'  => 'checkbox',
				'label' => __('Enable', 'g7theme'),
				'title' => __('Enable Review', 'g7theme'),
				'std'   => '1',
				'desc'  => __('Check this to enable Review Settings in post meta box', 'g7theme'),
			),
		),
	),
	'social' => array(
		'title'  => __('Social', 'g7theme'),
		'desc'   => __('Setting for social widget', 'g7theme'),
		'fields' => array(
			'social_max' => array(
				'type'       => 'text',
				'title'      => __('Maximum number of items', 'g7theme'),
				'attributes' => 'style="width:40px"',
				'std'        => 6,
			),
		),
	),
	'sidebar' => array(
		'title'  => __('Sidebar Manager', 'g7theme'),
		'desc'   => __('This theme has 1 default sidebar. You can add more sidebars from here.', 'g7theme'),
		'fields' => array(
			'sidebar' => array(
				'type'  => 'texts',
				'title' => __('Custom Sidebars', 'g7theme'),
			),
		),
	),
	'contact' => array(
		'title'  => __('Contact Form', 'g7theme'),
		'desc'   => __('These settings will affect contact form page template and contact form widget', 'g7theme'),
		'fields' => array(
			'contact_email' => array(
				'type'  => 'text',
				'title' => __('Email address', 'g7theme'),
				'desc'  => __('if empty, admin email address will be used', 'g7theme'),
			),
			'contact_subject' => array(
				'type'  => 'text',
				'title' => __('Subject', 'g7theme'),
				'std'   => __('A Message From {name}', 'g7theme'),
			),
			'contact_success' => array(
				'type'  => 'textarea',
				'title' => __('Success message', 'g7theme'),
				'rows'  => '2',
				'std'   => __('Thanks. Your message has been sent.', 'g7theme'),
			),
		),
	),
	'scripts' => array(
		'title'  => __('Scripts', 'g7theme'),
		'fields' => array(
			'footer_scripts' => array(
				'type'  => 'textarea',
				'title' => __('Custom scripts', 'g7theme'),
				'rows'  => '10',
				'desc'  => __('Enter your custom scripts (such as Google Analytics or other tracking code) here. It will be inserted before the closing body tag', 'g7theme'),
			),
		),
	),
);
