<?php namespace Proto2;

/* =Actions
--------------------------------------------------------------- */
include 'includes/register_scripts_styles.php';

// register menus
add_action('init', function(){
	register_nav_menus( array(
		'main-menu' => 'Main Menu'
	) );
});


// Slug Body Class
// from http://www.wpbeginner.com/wp-themes/how-to-add-page-slug-in-body-class-of-your-wordpress-themes/
add_filter(
	'body_class',
	function ( $classes ) {
		global $post;
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}
		return $classes;
	}
);


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