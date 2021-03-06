<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>
	<?php
	/** @var WC_Product $product */
	global $product;
	$lafka_chosen_category = lafka_get_chosen_category_for_related( $product );
    ?>

    <section class="related products">

        <h2>
		    <?php esc_html_e( 'Drugo', 'lafka' ); ?>
		    <?php echo ' '; ?>
		    <?php if ( $lafka_chosen_category !== null && $lafka_chosen_category->slug !== 'uncategorized' ): ?>
                <a class="lafka-related-browse"
                   href="<?php echo esc_url( get_term_link( $lafka_chosen_category ) ); ?>"
                   title="<?php echo sprintf( esc_attr__( 'Browse more "%s"', 'lafka' ), $lafka_chosen_category->name ) ?>">
				    <?php echo esc_html( $lafka_chosen_category->name ) . ' '; ?>
                </a>
		    <?php else: ?>
			    <?php esc_html_e( 'Proizvodi', 'lafka' ); ?>
		    <?php endif; ?>

		    <?php esc_html_e( 'you\'ll love', 'lafka' ); ?>
        </h2>

		<?php woocommerce_product_loop_start(); ?>
        <!-- Container for flex carousel on related products  -->
        <div>

			<?php foreach ( $related_products as $related_product ) : ?>

				<?php
				/** @var WC_Product $related_product */
				$post_object = get_post( $related_product->get_id() );

				// Althemist edit - removed pass by ref as it is unnecessary
				setup_postdata( $GLOBALS['post'] = $post_object );

				wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>
            <!-- END Container for flex carousel on related products  -->
        </div>
		<?php woocommerce_product_loop_end(); ?>

    </section>

<?php endif;

wp_reset_postdata();