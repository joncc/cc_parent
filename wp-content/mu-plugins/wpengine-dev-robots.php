<?php
/*
Plugin Name: WPEngine DEV Robots Meta
Plugin URI: http://www.computercourage.com
Description: Adds robots noindex/nofollow to the site header if the wpengine.com string is in the blog url.
Version: 1.0
Author: Computer Courage
Author URI: http://www.computercourage.com
License: GPL2
*/


add_action('init', 'wpedev_init', 1);
function wpedev_init() {
	add_action( 'admin_notices', 'wpedev_add_notice', 1);
	add_action( 'admin_head', 'wpedev_css' );
	add_action( 'wp_head', 'wpedev_add_robots_meta' );
}

function wpedev_add_robots_meta() {
	if( wpedev_is_in_development() ) {
?>
	<!-- WPEngine DEV Robots Meta -->
	<meta name='robots' content='noindex,nofollow' />
	<!-- end WPEngine DEV Robots Meta -->
<?php
	}
}

function wpedev_is_in_development() {
	return strpos( get_bloginfo('url'), 'wpengine.com' ) !== false;
}

function wpedev_robots_blocked() {
	return !get_option('blog_public');
}

function wpedev_add_notice() {
	if( wpedev_robots_blocked() ) {
		echo "<div class='robots-notice robots-urgent'>Search Engines are blocked. <a href='options-reading.php'>Privacy Settings</a>.</div>";
	} elseif( wpedev_is_in_development() ) { 
		echo "<div class='robots-notice'>Search Engines are blocked while this site is in development.</div>";
	}
}

function wpedev_css() {
	if( wpedev_robots_blocked() || wpedev_is_in_development() ) {
?>
	<style type="text/css">
		.robots-notice {
			background-color:#ffffe0;
			font-weight:bold;
			line-height:19px;
			padding:5px 0;
			font-size:14px;
			text-align:center;
			margin:-1px 15px 0 5px;
			border-width:1px;
			border-style:solid;
			border-bottom-right-radius:3px;
			border-bottom-left-radius:3px;
			border-color:#000;
		}
		.robots-urgent {
			background-color:#dd3636;
			color:#fff;
		}
		.robots-urgent a {
			color:#e9e9e9;
		}
	</style>
<?php
	}
}