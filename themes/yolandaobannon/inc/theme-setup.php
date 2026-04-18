<?php
/**
 * General child theme setup and utility functions.
 */

// Register custom block styles.
function yowangdu_register_block_styles() {
	$block_styles = array(
		'core/columns'      => array(
			'columns-reverse' => __( 'Reverse', 'yowangdu' ),
		),
		'core/group'        => array(
			'shadow-light' => __( 'Shadow', 'yowangdu' ),
			'shadow-solid' => __( 'Solid', 'yowangdu' ),
		),
		'core/image'        => array(
			'shadow-light' => __( 'Shadow', 'yowangdu' ),
			'shadow-solid' => __( 'Solid', 'yowangdu' ),
		),
		'core/list'         => array(
			'no-disc' => __( 'No Disc', 'yowangdu' ),
		),
		'core/quote'        => array(
			'shadow-light' => __( 'Shadow', 'yowangdu' ),
			'shadow-solid' => __( 'Solid', 'yowangdu' ),
		),
		'core/social-links' => array(
			'outline' => __( 'Outline', 'yowangdu' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_action( 'init', 'yowangdu_register_block_styles' );

// Add custom login logo.
function yowangdu_login_logo() {
	$logo_path = '/assets/images/YoWangdu-Logo-180.png';

	if ( ! file_exists( get_stylesheet_directory() . $logo_path ) ) {
		return;
	}

	$logo = get_stylesheet_directory_uri() . $logo_path;
	?>
	<style type="text/css">
		.login h1 a {
			background-image: url(<?php echo esc_url( $logo ); ?>);
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center center;
			display: block;
			overflow: hidden;
			text-indent: -9999em;
			width: 100px;
			height: 100px;
		}
	</style>
	<?php
}
add_action( 'login_head', 'yowangdu_login_logo' );

// Set login logo URL.
function yowangdu_login_header_url( $url ) {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'yowangdu_login_header_url' );

// Unregister selected parent theme patterns.
function yowangdu_remove_core_patterns() {
	$core_block_patterns = array(
		'banner-hero',
		'banner-project-description',
		'cta-content-image-on-right',
		'cta-pricing',
		'cta-rsvp',
		'cta-services-image-left',
		'cta-subscribe-centered',
		'footer-centered-logo-nav',
		'footer-colophon-3-col',
		'footer',
		'gallery-full-screen-image',
		'gallery-offset-images-grid-2-col',
		'gallery-offset-images-grid-3-col',
		'gallery-offset-images-grid-4-col',
		'gallery-project-layout',
	);

	foreach ( $core_block_patterns as $core_block_pattern ) {
		unregister_block_pattern( 'core/' . $core_block_pattern );
	}
}
add_action( 'init', 'yowangdu_remove_core_patterns' );

// Enable excerpts for pages.
add_post_type_support( 'page', 'excerpt' );

// Copyright shortcode.
function yowangdu_copyright_line() {
	return 'Copyright &copy; 2009 - ' . date( 'Y' );
}
add_shortcode( 'copyright', 'yowangdu_copyright_line' );
