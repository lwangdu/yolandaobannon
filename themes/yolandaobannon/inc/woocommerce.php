<?php
/**
 * WooCommerce customizations for the Yolanda O'Bannon child theme.
 */

// Hide the checkout order notes field.
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );

// Add product audio URL field.
add_action( 'woocommerce_product_options_general_product_data', function() {
	echo '<div class="options_group">';

	woocommerce_wp_text_input(
		array(
			'id'          => '_product_audio_url',
			'label'       => 'Product Audio URL',
			'type'        => 'url',
			'description' => 'Paste MP3 URL here',
		)
	);

	echo '</div>';
} );

// Save product audio URL field.
add_action( 'woocommerce_process_product_meta', function( $post_id ) {
	if ( isset( $_POST['_product_audio_url'] ) ) {
		update_post_meta(
			$post_id,
			'_product_audio_url',
			esc_url_raw( wp_unslash( $_POST['_product_audio_url'] ) )
		);
	}
} );

function yo_get_current_product_id() {
	global $product;

	if ( $product && is_object( $product ) && method_exists( $product, 'get_id' ) ) {
		return (int) $product->get_id();
	}

	$product_id = get_the_ID();

	if ( $product_id && 'product' === get_post_type( $product_id ) ) {
		return (int) $product_id;
	}

	return 0;
}

function yo_get_product_audio_player_html( $product_id = 0 ) {
	$product_id = $product_id ? (int) $product_id : yo_get_current_product_id();

	if ( ! $product_id ) {
		return '';
	}

	$audio = get_post_meta( $product_id, '_product_audio_url', true );

	if ( ! $audio ) {
		return '';
	}

	$player  = '<div class="product-audio-player">';
	$player .= 'Listen to a Short Preview:';
	$player .= wp_audio_shortcode(
		array(
			'src'     => esc_url( $audio ),
			'preload' => 'none',
		)
	);
	$player .= '</div>';

	return $player;
}

add_shortcode(
	'product_audio_player',
	function() {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return '';
		}

		return yo_get_product_audio_player_html();
	}
);

// Enable Gutenberg for products.
add_filter(
	'use_block_editor_for_post_type',
	function( $can_edit, $post_type ) {
		if ( 'product' === $post_type ) {
			return true;
		}

		return $can_edit;
	},
	10,
	2
);

// Show shop page content above products.
add_action( 'woocommerce_before_shop_loop', 'show_shop_page_content', 1 );

function show_shop_page_content() {
	if ( is_shop() ) {
		$shop_page = get_post( wc_get_page_id( 'shop' ) );
		if ( $shop_page && ! empty( $shop_page->post_content ) ) {
			echo '<div class="page-description">';
			echo apply_filters( 'the_content', $shop_page->post_content );
			echo '</div>';
		}
	}
}

// Improve empty-cart message semantics for WooCommerce block cart/minicart output.
add_filter( 'render_block', 'yo_fix_empty_cart_message_heading', 20, 2 );

function yo_fix_empty_cart_message_heading( $block_content, $block ) {
	$old_markup = '<p class="has-text-align-center"><strong>Your cart is currently empty!</strong></p>';

	if ( false === strpos( $block_content, $old_markup ) ) {
		return $block_content;
	}

	$block_name = isset( $block['blockName'] ) ? $block['blockName'] : '';
	$supported_blocks = array(
		'woocommerce/cart',
		'woocommerce/empty-cart-block',
		'woocommerce/mini-cart',
		'woocommerce/empty-mini-cart-contents-block',
	);

	if ( $block_name && ! in_array( $block_name, $supported_blocks, true ) ) {
		return $block_content;
	}

	$new_markup = '<h2 class="has-text-align-center">Your cart is currently empty!</h2>';

	return str_replace( $old_markup, $new_markup, $block_content );
}
