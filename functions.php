<?php
namespace Proto2;

include 'includes/register_scripts_styles.php';

/* =Functions
--------------------------------------------------------------- */
if( !function_exists('wpedev_is_in_development') ) {
	function wpedev_is_in_development() {
		return strpos( get_bloginfo('url'), 'wpengine.com' ) !== false;
	}
}

function cleanup_thumbnails_on_delete($postid) {
	$filename = wp_get_attachment_metadata($postid);
	$parsed = pathinfo($filename['file']);
	remove_thumbnail($parsed);
}

function cleanup_thumbnails_on_replace($new_guid) {
	$parsed = pathinfo($new_guid);
	remove_thumbnail($parsed);
}

function remove_thumbnail($parsed) {
	$matches = glob( ABSPATH . 'wp-content/uploads/cc_resize/*' );
	
	foreach($matches as $file) {
		if( preg_match('/\/'.$parsed["filename"].'-(\d*?)x(\d*?)/', $file) ) {
			unlink($file);
		}
	}
}

function cc_resize($url, $width=false, $height=false, $crop=false, $scale=1) {
	$img_dir = ABSPATH . 'wp-content/uploads/cc_resize/';
	$image = wp_get_image_editor($url);
	$file = pathinfo($url);
	
	if( !is_wp_error($image) ) {
		$orig_size = $image->get_size();
		$orig_name = $file['filename'];
		$extension = strtolower($file['extension']);
		
		if($height && $width) {
			$crop = true;
		}
		if(!$width) {
			$width = $orig_size['width'];
		}
		if(!$height) {
			$height = $orig_size['height'];
		}
		
		$width = $width * $scale;
		$height = $height * $scale;
		$new_path = $img_dir . $orig_name . '-' . $width . 'x' . $height . '.' . $extension;
		$new_url = get_bloginfo('url') . '/wp-content/uploads/cc_resize/' . $orig_name . '-' . $width . 'x' . $height . '.' . $extension;
		
		if( !file_exists($new_path) ) {
			if( $extension==='jpg' || $extension==='jpeg' ) {
				$image->set_quality(75);
			}
			
			$image->resize($width, $height, $crop);
			$image->save($new_path);
		}
		return $new_url;
	} else {
		return false;
	}
}

function prevent_category_checkbox_move($args) {
	$args['checked_ontop'] = false;
	return $args;
}

function register_main_menu() {
	register_nav_menus( array(
		'main-menu' => 'Main Menu'
	) );
}

function add_page_slug_to_body_class( $classes ) {
	global $post;
	if ( isset($post) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}

function archive_title() {
	if ( is_day() ) {
		echo 'Daily Archives: ' . get_the_date();
	} elseif ( is_month() ) {
		echo 'Monthly Archives: ' . get_the_date('F Y');
	} elseif ( is_year() ) {
		echo 'Yearly Archives: ' . get_the_date('Y');
	} else {
		echo 'Archives';
	}
}


/* =Features
--------------------------------------------------------------- */
add_theme_support('post-thumbnails');
add_theme_support('html5', array('search-form', 'comment-form',	'comment-list', 'gallery', 'caption'));


/* =Filters
--------------------------------------------------------------- */
add_filter('wp_terms_checklist_args', 'Proto2\prevent_category_checkbox_move');
add_filter('body_class', 'Proto2\add_page_slug_to_body_class');


/* =Actions
--------------------------------------------------------------- */
add_action('init', 'Proto2\register_main_menu');
add_action('delete_attachment', 'Proto2\cleanup_thumbnails_on_delete');
add_action('enable-media-replace-upload-done', 'Proto2\cleanup_thumbnails_on_replace');


?>