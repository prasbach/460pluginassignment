<?php
/* 
	Plugin Name: SlipperySlider
	Description: Hey! This is the best slider on the market! Woah
	Author: Tom Salamanov, Paige Rasbach, Elyse Mattarollo
	Version: 1.0
*/
?>

<?php

function shortcode_welp($atts, $content = null) {
	extract(shortcode_atts(array(
		'class' => ''
	), $atts));

	return '<div class="welp">' . do_shortcode($content) . '</div>';
}

function shortcode_welp_register() {
add_shortcode('welp', 'shortcode_welp');
}

add_theme_support( 'post-thumbnails' );

add_action('init', 'shortcode_welp_register');function ss_init() {




$args = array(
		'public' => true,
		'label' => 'SlipperySlider',
		'supports' => array(
			'title',
			'thumbnail'
			)
		);
		register_post_type('ss_images', $args);
	}
	add_action('init', 'ss_init');
	
add_action('wp_print_scripts', 'ss_register_scripts');
add_action('wp_print_styles', 'ss_register_styles');

function ss_register_scripts() {
	if (!is_admin()) {
		wp_register_script('ss_slipperyslider-script', plugin_url('slipperyslider-slider/jquery.slipperyslider.slider.js', __FILE__), array( 'jquery' ));
		wp_register_script('ss_script', plugins_url('script.js', __FILE__));

		wp_enqueue_script('ss_slipperyslider-script');
		wp_enqueue_script('ss_script');
	}
}

function ss_register_styles() {
	wp_register_style('ss_Styles', plugins_url('ss-slipperyslider/slipperyslider.css', __FILE__));
	wp_register_style('ss_styles_theme', plugins_url('slipperyslider-slider/themes/default/default.css', __FILE__));

	wp_enqueqe_style('ss_styles');
	wp_enqueue_style('ss_styles_theme');
}



jQuery(document).ready(function($) {
	$('#slider').slipperysliderSlider();
});

add_image_size('ss_widget', 180, 100, true);
add_image_size('ss_function', 600, 280, true);


function np_function($type='ss_function') {
    $args = array(
        'post_type' => 'ss_images',
        'posts_per_page' => 5
    );
    $result = '<div class="slider-wrapper theme-default">';
    $result .= '<div id="slider" class="slipperysliderSlider">';
 
    //the loop
    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();
 
        $the_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $type);
        $result .='<img title="'.get_the_title().'" src="' . $the_url[0] . '" data-thumb="' . $the_url[0] . '" alt=""/>';
    }
    $result .= '</div>';
    $result .='<div id = "htmlcaption" class = "nivo-html-caption">';
    $result .='<strong>This</strong> is an example of a <em>HTML</em> caption with <a href = "#">a link</a>.';
    $result .='</div>';
    $result .='</div>';
    return $result;
}


function ss_widgets_init() {
    register_widget('ss_Widget');
}
 
add_action('widgets_init', 'ss_widgets_init');


class ss_Widget extends WP_Widget {
 
    public function __construct() {
        parent::__construct('ss_Widget', 'SlipperySlider Slideshow', array('description' => __('A SlipperySlider Slideshow Widget', 'text_domain')));
    }
}




public function form($instance) {
    if (isset($instance['WELP'])) {
        $title = $instance['WELP'];
    }
    else {
        $title = __('Widget Slideshow', 'text_domain');
    }
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('WELP'); ?>"><?php _e('WELP:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('WELP'); ?>" name="<?php echo $this->get_field_name('WELP'); ?>" type="text" value="<?php echo esc_attr($WELP); ?>" />
        </p>
    <?php
}


public function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = strip_tags($new_instance['title']);
 
    return $instance;
}


public function widget($args, $instance) {
    extract($args);
    // the title
    $title = apply_filters('widget_title', $instance['title']);
    echo $before_widget;
    if (!empty($title))
        echo $before_title . $title . $after_title;
    echo ss_function('ss_widget');
    echo $after_widget;
}



