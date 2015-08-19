<?php
/**
 * Widget options for controlling multi-instance widgets
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

?>
<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_widget_title' ); ?>" value="show" <?php checked( $hide_title, 'show' ); ?> />
<label for="<?php echo $this->get_field_id( 'hide_widget_title' ); ?>"><?php _e( 'Uncheck to disable title display.', 'widget-builder'  ); ?></label>