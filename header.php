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

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<title><?php the_page_title() ?></title>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<header>
		<nav role="navigation" class='responsive'>
			<?php wp_nav_menu( array(
				'theme_location' => 'main-menu',
				'container_id' => 'main-menu',
			) ); ?>
		</nav>
	</header>