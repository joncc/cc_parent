<?php namespace Proto2;

/* =Actions
--------------------------------------------------------------- */

// register scripts and styles
add_action('wp_enqueue_scripts', function(){
	$bootstrap_dir = get_template_directory_uri() . '/bootstrap';
	$js_dir = get_template_directory_uri() . '/js';

	$min = ( ! wpedev_is_in_development() ) ? '.min' : '';
	wp_register_script('bootstrap', $bootstrap_dir . "/js/bootstrap$min.js",
		array('jquery') );
	wp_register_style('bootstrap', $bootstrap_dir . "/css/bootstrap$min.css" );
	wp_register_style('bootstrap_theme',
		$bootstrap_dir . "/css/bootstrap-theme$min.css",
		array('bootstrap') );
});

// register menus
add_action('init', function(){
	register_nav_menus( array(
		'account-menu' => 'Account Menu',
		'main-menu' => 'Main Menu'
	) );
});

/* =Functions
--------------------------------------------------------------- */

if( ! function_exists('wpedev_is_in_development') ):
	function wpedev_is_in_development() {
		return strpos( get_bloginfo('url'), 'wpengine.com' ) !== false;
	}
endif;
?>