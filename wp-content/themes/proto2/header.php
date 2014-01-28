<?php
namespace Proto2\Header;

function the_page_title(){
		/*
		 * Print the <title> tag based on what is being viewed.
		 */
		global $page, $paged;
		wp_title( '|', true, 'right' );

		// Add a page number if necessary:
		// this should probably be done with a filter instead
		// see http://codex.wordpress.org/Function_Reference/wp_title
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'toolbox' ), max( $paged, $page ) );
}

add_action('wp_enqueue_scripts', function(){
	wp_enqueue_script('bootstrap');
	wp_enqueue_style('bootstrap');
})

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<title><?php the_page_title() ?></title>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php get_search_form(true); ?>
	<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
	<?php do_action( 'before' ); ?>

	<header>
		<nav id="access" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #access -->
	</header>