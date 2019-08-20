<?php
class G7_Widget extends WP_Widget {

	protected $widget;
	protected $fields;

	function __construct() {
		$this->set_fields();
		parent::WP_Widget(
			$this->widget['id_base'],
			G7_NAME . ' - ' . $this->widget['name'],
			array(
				'description' => $this->widget['description']
			),
			isset($this->widget['control_ops']) ? $this->widget['control_ops'] : array()
		);
	}

    function update($new_instance, $old_instance) {
		$instance = wp_parse_args($new_instance, $old_instance);

		foreach ((array)$this->fields as $k => $v) {
			if ($v['type'] == 'custom') {
				continue;
			}
			//for checkboxes: if unchecked, set the value to 0
			if ($v['type'] == 'checkbox') {
				$instance[$k] = isset($new_instance[$k]) ? 1 : 0;
			}
		}

		return $instance;
    }

    function set_fields() {
    	$this->fields = array();
    }

    function form($instance) {

		foreach ($this->fields as $k => $v) {

			echo '<p>';

			if (in_array($v['type'], array('text', 'textarea', 'select', 'category'))) {
				echo '<label for="' . $this->get_field_id($k) .'">' . $v['label'] . ':</label>' . "\n";
			}

			if ($v['type'] != 'custom') {
				$value = $instance ? $instance[$k] : $v['std'];
			}

			switch ($v['type']) {

				case 'text':
					printf(
						'<input type="text" name="%s" id="%s" value="%s" class="%s" %s />',
						$this->get_field_name($k),
						$this->get_field_id($k),
						$value,
						isset($v['class']) ? $v['class'] : 'widefat',
						isset($v['attributes']) ? $v['attributes'] : ''
					);
					break;

				case 'textarea':
					printf(
						'<textarea name="%s" id="%s" class="%s" %s>%s</textarea>',
						$this->get_field_name($k),
						$this->get_field_id($k),
						isset($v['class']) ? $v['class'] : 'widefat',
						isset($v['attributes']) ? $v['attributes'] : '',
						$value
					);
					break;

				case 'select':
					printf(
						'<select name="%s" id="%s" class="%s" %s>',
						$this->get_field_name($k),
						$this->get_field_id($k),
						isset($v['class']) ? $v['class'] : 'widefat',
						isset($v['attributes']) ? $v['attributes'] : ''
					);
					foreach ((array)$v['options'] as $k2 => $v2) {
						printf(
							'<option value="%s"%s>%s</option>',
							$k2,
							selected($value, $k2, false),
							$v2
						);
					}
					echo '</select>';
					break;

				case 'category':
					wp_dropdown_categories(array(
						'hide_empty'      => 0,
						'name'            => $this->get_field_name($k),
						'id'              => $this->get_field_id($k),
						'class'           => 'widefat',
						'hierarchical'    => 1,
						'show_option_all' => __('All Categories', 'g7theme'),
						'selected'        => $value,
					));
					break;

				case 'checkbox':
					printf(
						'<input id="%s" class="checkbox" type="checkbox" value="1" name="%s" %s>
						<label for="%s">%s</label>',
						$this->get_field_id($k),
						$this->get_field_name($k),
						checked($value, true, false),
						$this->get_field_id($k),
						$v['label']
					);
					break;

				case 'custom':
					echo $v['content'];
					break;

			}

			if (isset($v['desc'])) {
				echo '<br><small>' . $v['desc'] . '</small>';
			}
			echo '</p>';
		}
    }

}

/**
 * Base class for all post related widgets
 */
class G7_Posts {

	public $orderby        = true;
	public $cat            = true;
	public $limit          = true;
	public $show_thumbnail = true;
	public $style          = true;
	public $show_category  = true;
	public $show_rating    = true;
	public $show_date      = true;
	public $show_comment   = true;
	public $show_author    = true;
	public $show_excerpt   = true;

	function get_fields() {
		$fields['title'] = array(
			'type'  => 'text',
			'label' => __('Title', 'g7theme'),
			'std'   => '',
		);
		if ($this->orderby) {
			$fields['orderby'] = array(
				'type'    => 'select',
				'label'   => __('Post order', 'g7theme'),
				'class'   => '',
				'std'     => 1,
				'options' => array(
					1 => __('Recent', 'g7theme'),
					2 => __('Popular', 'g7theme'),
					3 => __('Random', 'g7theme'),
				),
			);
		}
		if ($this->cat) {
			$fields['cat'] = array(
				'type'  => 'category',
				'label' => __('Filter by Category', 'g7theme'),
				'std'   => 0,
			);
		}
		if ($this->limit) {
			$fields['limit'] = array(
				'type'       => 'text',
				'label'      => __('Number of posts to show', 'g7theme'),
				'std'        => 5,
				'class'      => '',
				'attributes' => 'size="3"',
			);
		}
		if ($this->show_thumbnail) {
			$fields['show_thumbnail'] = array(
				'type'  => 'checkbox',
				'label' => __('Show thumbnail', 'g7theme'),
				'std'   => 1,
			);
		}
		if ($this->style) {
			$fields['style'] = array(
				'type'    => 'select',
				'label'   => __('Thumbnail size', 'g7theme'),
				'class'   => '',
				'std'     => 1,
				'options' => array(
					1 => __('Small', 'g7theme'),
					2 => __('Large', 'g7theme'),
					3 => __('Mixed', 'g7theme'),
				),
			);
		}
		if ($this->show_category) {
			$fields['show_category'] = array(
				'type'  => 'checkbox',
				'label' => __('Show category', 'g7theme'),
				'std'   => 0,
			);
		}
		if ($this->show_rating) {
			$fields['show_rating'] = array(
				'type'  => 'checkbox',
				'label' => __('Show rating (if available)', 'g7theme'),
				'std'   => 1,
			);
		}
		if ($this->show_date) {
			$fields['show_date'] = array(
				'type'  => 'checkbox',
				'label' => __('Show date', 'g7theme'),
				'std'   => 1,
			);
		}
		if ($this->show_comment) {
			$fields['show_comment'] = array(
				'type'  => 'checkbox',
				'label' => __('Show comments number', 'g7theme'),
				'std'   => 1,
			);
		}
		if ($this->show_author) {
			$fields['show_author'] = array(
				'type'  => 'checkbox',
				'label' => __('Show author', 'g7theme'),
				'std'   => 0,
			);
		}
		if ($this->show_excerpt) {
			$fields['show_excerpt'] = array(
				'type'    => 'select',
				'label'   => __('Show excerpt', 'g7theme'),
				'class'   => '',
				'std'     => 0,
				'options' => array(
					0 => __('No', 'g7theme'),
					1 => __('Show on first item', 'g7theme'),
					2 => __('Show on all items', 'g7theme')
				),
			);
		}

		return $fields;
	}

	function get_query_args($instance) {
		$orderby = 'date';
		if ($this->orderby) {
			switch ($instance['orderby']) {
				case 2:
					$orderby = 'comment_count';
					break;
				case 3:
					$orderby = 'rand';
					break;
				case 1:
				default:
					$orderby = 'date';
			}
		}
		$order = 'DESC';

		if ($this->cat) {
			$query_args['cat']             = $instance['cat'];
		}
		$query_args['posts_per_page']      = $instance['limit'];
		$query_args['orderby']             = $orderby;
		$query_args['order']               = $order;
		$query_args['ignore_sticky_posts'] = 1;

		return $query_args;
	}

	function loop($posts, $instance) {
		$loop = '';

		if ($posts->have_posts()) {
			$loop .= '<ul>';

			$counter = 1;
			while ($posts->have_posts()) {
				$posts->the_post();

				$cat_class = '';
				if (get_theme_mod('cat_color', 0)) {
					$cat_class .= 'cat-' . g7_first_category_ID();
				}

				$post_class = '';
				if (!empty($instance['show_excerpt'])) {
					if (($instance['show_excerpt'] == 1 && $counter == 1) || $instance['show_excerpt'] == 2) {
						$post_class = ' post1';
					}
				}

				$thumbnail = '';
				if (!empty($instance['show_thumbnail'])) {
					$style = $instance['style'];
					if ($style == 1 || ($style == 3 && $counter > 1)) {
						list($image_w, $image_h) = g7_image_sizes('thumb');
						$thumbnail = '<div class="block-side">' . g7_image($image_w, $image_h) . '</div>';
					} elseif ($style == 2 || ($style == 3 && $counter == 1)) {
						list($image_w, $image_h) = g7_image_sizes('widget');
						$thumbnail = '<div class="block-top">' . g7_image($image_w, $image_h) . '</div>';
						$post_class = ' post2';
					}
				}

				$class = $cat_class . $post_class;

				$li_attr = empty($class) ? '' : ' class="' . $class . '"';

				$loop .= "<li$li_attr>";

				$loop .= $thumbnail;

				$loop .= '<div class="block-content">';

				if (!empty($instance['show_category'])) {
					$loop .= '<div class="block-category">';
					$loop .= g7_first_category();
					$loop .= '</div>';
				}

				$loop .= sprintf(
					'<h4 class="block-heading"><a href="%s">%s</a></h4>',
					get_permalink(),
					get_the_title()
				);

				$loop .= '<div class="block-meta">';

				if (!empty($instance['show_rating'])) {
					$loop .= g7_post_rating();
				}

				if (!empty($instance['show_date'])) {
					$loop .= g7_date_meta();
				}

				if (!empty($instance['show_comment'])) {
					$loop .= g7_comments_meta();
				}

				if (!empty($instance['show_author'])) {
					$loop .= g7_author_meta();
				}

				$loop .= '</div>';
				$loop .= '<div class="clear"></div>';

				if (!empty($instance['show_excerpt'])) {
					if (($instance['show_excerpt'] == 1 && $counter == 1) || $instance['show_excerpt'] == 2) {
						$loop .= '<div class="block-excerpt">' . g7_post_content(1, 20) . '</div>';
					}
				}

				$loop .= '</div>';
				$loop .= '</li>';
				$counter++;
			}

			$loop .= '</ul>';
		}

		return $loop;
	}

}

require_once PARENT_DIR . '/includes/widgets/comments.php';
require_once PARENT_DIR . '/includes/widgets/flickr.php';
require_once PARENT_DIR . '/includes/widgets/posts.php';
require_once PARENT_DIR . '/includes/widgets/social.php';
require_once PARENT_DIR . '/includes/widgets/ads-125.php';
require_once PARENT_DIR . '/includes/widgets/ads-300.php';
require_once PARENT_DIR . '/includes/widgets/video.php';
require_once PARENT_DIR . '/includes/widgets/facebook.php';
require_once PARENT_DIR . '/includes/widgets/reviews.php';
require_once PARENT_DIR . '/includes/widgets/subpages.php';
require_once PARENT_DIR . '/includes/widgets/contact.php';


/**
 * Add widgetized area:
 * - Default Sidebar
 * - Custom Sidebar
 * - Footer 1
 * - Footer 2
 * - Footer 3
 * - Footer 4
 *
 * Add custom widgets
 *
 */
if (!function_exists('g7_widgets_init')) {
	function g7_widgets_init() {
		register_sidebar(array(
			'name'          => __('Default Sidebar', 'g7theme'),
			'id'            => 'sidebar',
			'description'   => __('This is the main sidebar, located beside the main content.', 'g7theme'),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widgettitle"><span>',
			'after_title'   => '</span></h2>'
		));
		$custom_sidebar = g7_option('sidebar');
		$i = 1;
		if (!empty($custom_sidebar)) {
			foreach ($custom_sidebar as $v) {
				if (trim($v) == '') {
					continue;
				}
				register_sidebar(array(
					'name'          => $v,
					'id'            => g7_sidebar_id($v),
					'description'   => __('This is a custom sidebar, located beside the main content.', 'g7theme'),
					'before_widget' => '<li id="%1$s" class="widget %2$s">',
					'after_widget'  => '</li>',
					'before_title'  => '<h2 class="widgettitle"><span>',
					'after_title'   => '</span></h2>'
				));
				$i++;
			}
		}
		register_sidebar(array(
			'name'          => __('Footer 1', 'g7theme'),
			'id'            => 'footer1',
			'description'   => __('This widget area is located at the left side of footer area.', 'g7theme'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle"><span>',
			'after_title'   => '</span></h2>'
		));
		register_sidebar(array(
			'name'          => __('Footer 2', 'g7theme'),
			'id'            => 'footer2',
			'description'   => __('This widget area is located at the center of footer area.', 'g7theme'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle"><span>',
			'after_title'   => '</span></h2>'
		));
		register_sidebar(array(
			'name'          => __('Footer 3', 'g7theme'),
			'id'            => 'footer3',
			'description'   => __('This widget area is located at the right side of footer area.', 'g7theme'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle"><span>',
			'after_title'   => '</span></h2>'
		));

		register_widget('G7_Posts_Widget');
		register_widget('G7_Flickr_Widget');
		register_widget('G7_Comments_Widget');
		register_widget('G7_Social_Widget');
		register_widget('G7_Ads125_Widget');
		register_widget('G7_Ads300_Widget');
		register_widget('G7_Video_Widget');
		register_widget('G7_Facebook_Widget');
		register_widget('G7_Reviews_Widget');
		register_widget('G7_Contact_Widget');
		register_widget('G7_Subpages_Widget');
	}
	add_action('widgets_init', 'g7_widgets_init');
}
