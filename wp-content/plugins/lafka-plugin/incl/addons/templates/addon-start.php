<div class="<?php if ( 1 == $required ) echo 'required-product-addon '; ?>product-addon product-addon-<?php echo sanitize_title( $name ); ?> <?php if(isset($addon['type'])) echo sanitize_html_class($addon['type']) ?>">

	<?php do_action( 'wc_product_addon_start', $addon ); ?>

	<?php if ( $name ) : ?>
		<h3 class="addon-name"><?php echo wptexturize( $name ); ?> <?php if ( 1 == $required ) echo '<abbr class="required" title="' . esc_html__( 'Required field', 'lafka-plugin' ) . '">*</abbr>'; ?></h3>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<?php echo '<div class="addon-description">' . wpautop( wptexturize( $description ) ) . '</div>'; ?>
	<?php endif; ?>

	<?php do_action( 'wc_product_addon_options', $addon ); ?>
