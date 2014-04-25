<?php

// register scripts and styles
add_action('wp_enqueue_scripts', function(){
	$js_dir = get_template_directory_uri() . '/js';

	wp_register_style(
		'cc_parent',
		get_template_directory_uri() . '/style.css'
	);
	wp_register_script(
		'html5shiv',
		'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
		null,
		false
	);

	/* =Fancybox
	--------------------------------------------------------------- */
	wp_register_style(
		'fancybox',
		'//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css'
	);
	wp_register_script(
		'fancybox',
		'//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js',
		array('jquery'),
		false,
		true 
	);
	// Fancybox w/ Thumbnails
	wp_register_style(
		'fancybox-thumbs',
		'//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.css',
		array('fancybox')
	);
	wp_register_script(
		'fancybox-thumbs',
		'//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.js',
		array('fancybox'),
		false,
		true 
	);

	/* =Cycle2 ( aka jCycle )
	--------------------------------------------------------------- */
	wp_register_script(
		'jcycle',
		'//cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/20130801/jquery.cycle2.min.js',
		array('jquery'),
		false,
		true 
	);

	/* =Backstretch
	--------------------------------------------------------------- */
	wp_register_script(
		'backstretch',
		'//cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js',
		null,
		false,
		true 
	);

	/* =Easyzoom
	 * http://cssglobe.com/jquery-plugin-easy-image-zoom/
	--------------------------------------------------------------- */
	wp_register_script(
		'easyzoom',
		$js_dir . '/easyzoom.js',
		array('jquery'),
		false,
		true 
	);
	/* =Toggler
	--------------------------------------------------------------- */
	wp_register_script(
		'toggler',
		$js_dir . '/toggler.js',
		array('jquery'),
		false,
		true 
	);
}, 20);