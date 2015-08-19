<?php
/**
 * Widget template. This template can be overriden using the "tribe_widget_builder_widget_dashboard.php" filter.
 * See the readme.txt file for more info.
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

// build html
$widget_html = '';
if ( !empty( $widget['image'] ) ) {
	$widget_html .= ( !empty( $widget['link_url'] ) ) ? '<a href="' . $widget['link_url'] . '" target="_blank"><img src="' . $widget['image'][0] . '" /></a>' : '<img src="' . $widget['image'][0] . '" />'; 
}
$widget_html .= $content;
$widget_html .= ( !empty( $widget['link_url'] ) ) ? '<a href="' . $widget['link_url'] . '" target="_blank">' . $widget['link_text'] . '</a>' : '';

// to screen
echo $widget_html;
