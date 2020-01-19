<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
?>
<div class="lafka_product_addon wc-metabox closed">
	<h3>
		<button type="button" class="remove_addon button"><?php esc_html_e( 'Remove', 'lafka-plugin' ); ?></button>
		<div class="handlediv" title="<?php esc_html_e( 'Click to toggle', 'lafka-plugin' ); ?>"></div>
		<strong><?php esc_html_e( 'Group', 'lafka-plugin' ); ?> <span class="group_name"><?php if ( $addon['name'] ) echo '"' . esc_attr( $addon['name'] ) . '"'; ?></span> &mdash; </strong>
        <select name="product_addon_type[<?php echo $loop; ?>]" class="product_addon_type">
            <option <?php selected('checkbox', $addon['type']); ?> value="checkbox"><?php _e('Checkboxes', 'lafka-plugin'); ?></option>
            <option <?php selected('radiobutton', $addon['type']); ?> value="radiobutton"><?php _e('Radio buttons', 'lafka-plugin'); ?></option>
            <option <?php selected('textarea', $addon['type']); ?> value="textarea"><?php _e('Textarea', 'lafka-plugin'); ?></option>
        </select>
        <input type="hidden" name="product_addon_position[<?php echo $loop; ?>]" class="product_addon_position" value="<?php echo $loop; ?>" />
	</h3>
	<table cellpadding="0" cellspacing="0" class="wc-metabox-content">
		<tbody>
			<tr>
				<td class="addon_name" width="50%">
					<label for="addon_name_<?php echo $loop; ?>">
						<?php
						esc_html_e( 'Name', 'lafka-plugin' );
						?>
					</label>
					<input type="text" id="addon_name_<?php echo $loop; ?>" name="product_addon_name[<?php echo $loop; ?>]" value="<?php echo esc_attr( $addon['name'] ) ?>" />
				</td>
				<td class="addon_required" width="50%">
					<label for="addon_required_<?php echo $loop; ?>"><?php esc_html_e( 'Required fields?', 'lafka-plugin' ); ?></label>
					<input type="checkbox" id="addon_required_<?php echo $loop; ?>" name="product_addon_required[<?php echo $loop; ?>]" <?php checked( $addon['required'], 1 ) ?> />
				</td>
			</tr>
			<tr>
				<td class="addon_description" colspan="2">
					<label for="addon_description_<?php echo $loop; ?>">
						<?php
						esc_html_e( 'Description', 'lafka-plugin' );
						?>
					</label>
					<textarea cols="20" id="addon_description_<?php echo $loop; ?>" rows="3" name="product_addon_description[<?php echo $loop; ?>]"><?php echo esc_textarea( $addon['description'] ) ?></textarea>
				</td>
			</tr>
			<?php do_action( 'lafka_product_addons_panel_before_options', $post, $addon, $loop ); ?>
			<tr>
				<td class="data" colspan="3">
					<table cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th><?php esc_html_e('Label', 'lafka-plugin'); ?></th>
								<th class="price_column"><?php esc_html_e('Price', 'lafka-plugin'); ?></th>
                                <th class="minmax_column"><span class="column-title"><?php esc_html_e('Min / Max', 'lafka-plugin'); ?></span></th>
                                <th width="10%" class="lafka-is-default-column"><?php esc_html_e('Default Value', 'lafka-plugin'); ?></th>
								<?php do_action( 'lafka_product_addons_panel_option_heading', $post, $addon, $loop ); ?>
								<th width="1%"></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="5"><button type="button" class="add_addon_option button"><?php esc_html_e('New&nbsp;Option', 'lafka-plugin'); ?></button></td>
							</tr>
						</tfoot>
						<tbody>
							<?php
							foreach ( $addon['options'] as $option )
								include( dirname( __FILE__ ) . '/html-addon-option.php' );
							?>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>