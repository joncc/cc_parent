<?php namespace Proto2;

/* =Actions
--------------------------------------------------------------- */

// register scripts and styles
add_action('wp_enqueue_scripts', function(){
	$js_dir = get_template_directory_uri() . '/js';

	$min = ( ! wpedev_is_in_development() ) ? '.min' : '';
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