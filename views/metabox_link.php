<?php
/**
 * Widget template. This template can be overriden using the "tribe_widget_builder_metabox_link.php" filter.
 * See the readme.txt file for more info.
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

$html = '';

foreach ( $fields as $f => $label ) {
	$saved_value = get_post_meta( $post_id, '_' . $f, true );

	// verify nonce setup
	$html .= ($html != "") ? '<br /><br />' : '<input type="hidden" name="' . $this->token . '_nonce" id="' . $this->token . '_nonce" value="' . $nonce . '" />';

	$html .= '<label for="' . $f . '">' . $label . '</label>';
	$html .= '<input type="text" id="' . $f . '" name="' . $f . '" value="' . $saved_value . '" size="32" />';

}

echo $html;
