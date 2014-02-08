<?php namespace Proto2;

/* =Actions
--------------------------------------------------------------- */

// register scripts and styles
add_action('wp_enqueue_scripts', function(){
	$js_dir = get_template_directory_uri() . '/js';
	$min = ( ! wpedev_is_in_development() ) ? '.min' : '';

	wp_register_style('proto2', get_template_directory_uri() . '/style.css' );

	wp_register_script('backstretch',
		'//cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js' );
});

// register menus
add_action('init', function(){
	register_nav_menus( array(
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


function timthumb( $src, $w=false, $h=false, $options=false){
	//var_dump($src);
	$rs = get_template_directory_uri() . '/timthumb.php';
	$rs .= '?src=' . $src;
	if( $w ){ $rs.= '&w=' . $w; }
	if( $h ){ $rs.= '&h=' . $h; }
	if( $options ){ $rs.= '&' . $options; }
	return $rs;
}
?>