<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'parent-style' )
	);

	if ( is_rtl() ) {
		wp_enqueue_style( 'parent-rtl', get_template_directory_uri() . '/rtl.css' );
		wp_enqueue_style( 'child-rtl',
			get_stylesheet_directory_uri() . '/rtl.css',
			array( 'parent-rtl' )
		);
	}

	wp_enqueue_script( 'child-lafka-front',
		get_stylesheet_directory_uri() . '/js/lafka-front.js',
		array( 'lafka-front' ),
		false,
		true
	);
}