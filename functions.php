<?php
namespace CC_Parent;

include 'includes/register_scripts_styles.php';


/* =Features
--------------------------------------------------------------- */
add_theme_support('post-thumbnails');
add_theme_support('html5', array('search-form', 'comment-form',	'comment-list', 'gallery', 'caption'));


/* =Filters
--------------------------------------------------------------- */
add_filter('wp_terms_checklist_args', 'CC_Parent\prevent_category_checkbox_move');
add_filter('body_class', 'CC_Parent\add_page_slug_to_body_class');


/* =Actions
--------------------------------------------------------------- */
add_action('init', 'CC_Parent\register_main_menu');
add_action('delete_attachment', 'CC_Parent\cleanup_thumbnails_on_delete');
add_action('enable-media-replace-upload-done', 'CC_Parent\cleanup_thumbnails_on_replace');


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

function resize( $url, $args = array() ) {
	$o = new Resize($url, $args);
	return $o->get_resized_image_url();
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

/* =Classes
--------------------------------------------------------------- */
define( 'CC_Resize_Image_Directory', ABSPATH . 'wp-content/uploads/cc_resize' );
class Resize{
	// Constants
	const IMAGE_DIR = CC_Resize_Image_Directory;
	// Properties
	private
		$source,
		$target,
		$resized,
		$crop,
		$scale,
		$image;

	// Methods
	public function __construct( $url, $args = array() ){
		$this->setup_properties( $url, $args );
		if( ! $this->resized_image_exists() ) :
			$this->create_resized_image();
		endif;
	}
	private function setup_properties( $url, $args = array() ){
		$defaults = array(
			'width'   => false,
			'height'  => false,
			'crop'    => true,
			'scale'   => 1,
			'match_ratio' => true
		);
		$args = wp_parse_args( $args, $defaults );

		$this->crop   = $args['crop'  ];
		$this->scale  = $args['scale' ];
		$this->match_ratio = $args['match_ratio'];

		$this->source = array( 'url' => $url );
		$this->image =  wp_get_image_editor( $this->get_source_path() );
		$this->source = array_merge( $this->source, $this->image->get_size() );
		
		$this->target = array(
			'width'  => $args['width'] * $args['scale'],
			'height' => $args['height'] * $args['scale']
		);

		$this->resized = array(
			'width' => false,
			'height' => false,
		);
	}
	private function resized_image_exists(){
		if( file_exists( $this->get_resized_file_path() ) ) :
			return true;
		else :
			return false;
		endif;
	}
	private function create_resized_image(){
		$image = $this->image;
		if( is_wp_error( $image ) ) :
			trigger_error( 'could not get image editor' );
			return false;
		else :
			$extension = pathinfo( $this->source['url'] );
			if( $extension==='jpg' || $extension==='jpeg' ) {
				$image->set_quality(75);
			}
			$image->resize(
				$this->get_resized_width(),
				$this->get_resized_height(),
				$this->crop
			);
			$image->save( $this->get_resized_file_path() );
		endif;
	}
	private function get_resized_file_path(){
		return self::IMAGE_DIR . '/' . $this->get_resized_file_name();
	}
	private function get_resized_file_name(){
		$source_path_info = pathinfo( $this->source['url'] );
		$file_name = $source_path_info['filename'];
		$file_name .= '-' . $this->get_resized_width();
		$file_name .= 'x' . $this->get_resized_height();
		$file_name .= '.' . $source_path_info['extension'];
		return $file_name;
	}
	private function get_resized_dimensions(){
		$w_ratio = $this->target['width' ] / $this->source['width'];
		$h_ratio = $this->target['height'] / $this->source['height'];
		
		$greater_ratio = ( $w_ratio > $h_ratio ) ? $w_ratio : $h_ratio;
		$lesser_ratio  = ( $h_ratio == $greater_ratio ) ? $w_ratio : $h_ratio;

		if( $this->crop ):
			if( $this->match_ratio && $this->source['width'] < $this->target['width'] ){
				$this->adjust_target( 'width' );
			}
			if( $this->match_ratio && $this->source['height'] < $this->target['height'] ){
				$this->adjust_target( 'height' );
			}
			$d = $this->target;
		else :
			$d = array(
				'width' => $this->source['width'] * $lesser_ratio,
				'height' => $this->source['height'] * $lesser_ratio
			);
		endif;

		$d['width'] = round( $d['width'] );
		$d['height'] = round( $d['height'] );
		$this->resized = array_merge( $this->resized, $d );
		return $d;
	}
	private function adjust_target( $side ){
		$target_start = $this->target[$side];
		$target_final = $this->source[$side];

		$multiplier = $target_final / $target_start;

		$this->target['width'] = $this->target['width'] * $multiplier;
		$this->target['height'] = $this->target['height'] * $multiplier;
	}
	private function get_resized_width(){
		if( ! $this->resized['width'] ):
			$this->get_resized_dimensions();
		endif;
		return $this->resized['width'];
	}
	private function get_resized_height(){
		if( ! $this->resized['height'] ):
			$this->get_resized_dimensions();
		endif;
		return $this->resized['height'];
	}
	private function get_source_path(){
		// if the image is internal, then use the absolute path rather than the url
		if( strpos($this->source['url'], get_bloginfo('url')) === 0 ) {
			return ABSPATH . str_replace(get_bloginfo('url'), '', $this->source['url']);
		}else{
			return $this->source['url'];
		}
	}
	public function get_resized_image_url(){
		$upload_dir = wp_upload_dir();
		return $upload_dir['baseurl'] . '/cc_resize/' . $this->get_resized_file_name();
	}
}








