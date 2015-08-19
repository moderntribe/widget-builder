<?php
/**
 * Widget template. This template can be overriden using the "tribe_widget_builder_metabox_admin.php" filter.
 * See the readme.txt file for more info.
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

$saved_description = get_post_meta( $post_id, '_' . $field_description, true );
$saved_dashboard = get_post_meta( $post_id, '_' . $field_dashboard, true );
$saved_disable_sidebar = get_post_meta( $post_id, '_' . $field_disable_sidebar, true );

?>
<label for="<?php echo $field_description; ?>"><?php _e('Widget Description', 'widget-builder' ); ?></label><br />
<textarea id="<?php echo $field_description; ?>" name="<?php echo $field_description; ?>" style="width:100%;" /><?php echo $saved_description; ?></textarea>
<br /><br />
<label for="<?php echo $field_dashboard; ?>">
	<input type="checkbox" name="<?php echo $field_dashboard; ?>" id="<?php echo $field_dashboard; ?>" value="1" <?php checked($saved_dashboard); ?> />
	<?php _e('Available As Dashboard Widget', 'widget-builder' ); ?>
</label><br />
<label for="<?php echo $field_disable_sidebar; ?>">
	<input type="checkbox" name="<?php echo $field_disable_sidebar; ?>" id="<?php echo $field_disable_sidebar; ?>" value="1" <?php checked($saved_disable_sidebar); ?> />
	<?php _e('Disable Sidebar Widget', 'widget-builder' ); ?>
</label>