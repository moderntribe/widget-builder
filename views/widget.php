<?php
/**
 * Widget template. This template can be overriden using the "tribe_widget_builder_widget.php" filter.
 * See the readme.txt file for more info.
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

// build html
$target = $link_target?'target="'.$link_target.'"':'';
$widget = $before_widget;
$widget .= ( !empty( $title ) && (!isset($instance['hide_widget_title']) || $instance['hide_widget_title']) == 'show' ) ? $before_title . $title . $after_title : '';
if ( !empty( $image ) ) {
	$widget .= ( !empty( $link_url ) ) ? '<a href="' . $link_url . '" ' . $target . '><img src="' . $image[0] . '" /></a>' : '<img src="' . $image[0] . '" />';
}
$widget .= $content;
$widget .= ( !empty( $link_url ) ) ? '<a href="' . $link_url . '" ' . $target . '>' . $link_text . '</a>' : '';
$widget .= $after_widget;

// to screen
echo $widget;
