<?php
/**
 * Child theme bootstrap for Yolanda O'Bannon.
 */

require_once get_stylesheet_directory() . '/inc/theme-setup.php';
require_once get_stylesheet_directory() . '/inc/woocommerce.php';

// Enqueue parent and child theme styles with cache busting for the child stylesheet.
function twentytwentyfour_enqueue_styles() {
	$child_style_path = get_stylesheet_directory() . '/style.css';
	$child_style_ver  = file_exists( $child_style_path ) ? filemtime( $child_style_path ) : wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'parent-theme',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);

	wp_enqueue_style(
		'child-theme',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'parent-theme' ),
		$child_style_ver
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyfour_enqueue_styles' );
