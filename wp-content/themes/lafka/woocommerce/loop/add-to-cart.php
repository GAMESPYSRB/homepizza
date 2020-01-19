<?php

/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
	exit;
}

global $product;
echo '<div class="links">';
echo apply_filters('woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
					sprintf('<a href="%s" data-quantity="%s" class="%s" title="%s" %s>%s</a>',
						esc_url($product->add_to_cart_url()),
						esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
						esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
						esc_attr($product->add_to_cart_text()),
						isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
						esc_html($product->add_to_cart_text())
					), $product, $args);

// Do not show quickview link for composite products as it is too complex for the user
if ( lafka_get_option( 'use_quickview' ) && $product->get_type() != 'composite' ) {
	$classes = array('lafka-quick-view-link');

	if ( lafka_is_product_eligible_for_variation_in_listings( $product ) ) {
		$lafka_quickview_link_label = __( 'More Options', 'lafka' );
		$classes[] = 'lafka-more-options';
	} else {
		$lafka_quickview_link_label = __( 'Order Now', 'lafka' );
	}

	echo '<a href="#" class="'.esc_attr(implode(' ', $classes)).'" data-id="' . esc_attr( $product->get_id() ) . '" title="' . esc_attr( $lafka_quickview_link_label ) . '">' . esc_html( $lafka_quickview_link_label ) . '</a>';
}
// show compare link
if (defined('YITH_WOOCOMPARE')) {
	lafka_add_compare_link();
}

echo '</div>';
