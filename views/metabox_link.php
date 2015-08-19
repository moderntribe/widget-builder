<?php
/**
 * Widget template. This template can be overriden using the "tribe_widget_builder_metabox_link.php" filter.
 * See the readme.txt file for more info.
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

$html = '<input type="hidden" name="' . self::TOKEN . '_nonce" id="' . self::TOKEN . '_noonce" value="' . $nonce . '" />';

$html .= '<p>';
$html .= sprintf('<label for="%s">%s</label><br />', self::TOKEN.'_link_text', __( 'Link Text', 'widget-builder' ));
$html .= sprintf('<input type="text" id="%s" name="%s" value="%s" size="32" />', self::TOKEN.'_link_text', self::TOKEN.'_link_text', esc_attr(get_post_meta($post_id, '_'.self::TOKEN.'_link_text', TRUE)));
$html .= '</p>';

$html .= '<p>';
$html .= sprintf('<label for="%s">%s</label><br />', self::TOKEN.'_link_url', __( 'Link URL', 'widget-builder' ));
$html .= sprintf('<input type="text" id="%s" name="%s" value="%s" size="32" />', self::TOKEN.'_link_url', self::TOKEN.'_link_url', esc_attr(get_post_meta($post_id, '_'.self::TOKEN.'_link_url', TRUE)));
$html .= '</p>';

$html .= '<p>';
$html .= sprintf('<label for="%s">%s</label><br />', self::TOKEN.'_link_target', __( 'Link Target', 'widget-builder' ));
$html .= sprintf('<select name="%s" id="%s">', self::TOKEN.'_link_target', self::TOKEN.'_link_target');
$target = get_post_meta($post_id, '_'.self::TOKEN.'_link_target', TRUE);
$html .= sprintf('<option value="">%s</option>', __('Open in current window', 'widget-builder'));
$html .= sprintf('<option value="_blank" %s>%s</option>', selected($target, '_blank', FALSE), __('Open in new window', 'widget-builder'));
$html .= '</select>';
$html .= '</p>';

echo $html;
