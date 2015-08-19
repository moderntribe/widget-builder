<?php
/*
Plugin Name:	Widget Builder
Description:	This plugin creates predefined content widgets that can be used in multiple sidebars while being centrally configured.
Author:			Timothy Wood, Modern Tribe, Inc.
Version:		1.0
Author URI:		http://tri.be
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die();

// Load the widget builder
require_once( 'classes/widget-builder.php' );
add_action('plugins_loaded', array('Tribe_Widget_Builder', 'init'));

// Load widget display
require_once( 'classes/custom-widget-display.php' );
if ( ! function_exists('tribe_load_custom_widget_display') ) {
	function tribe_load_custom_widget_display() {
		
		$token = 'tribe_widget_builder';

		// setup CPT query args
		$args = array(
			'numberposts'  => -1,
			'post_type'    => $token,
			'post_status'  => 'publish' );
		
		// filter 'tribe_widget_builder_get_posts_args' to modify the cpt query arguments 
 		$args = apply_filters( $token . '_get_posts_args', $args );

		$available_custom_widgets = get_posts($args);

		// filter 'tribe_widget_builder_get_posts' to override the cpt query
		$available_custom_widgets = apply_filters( $token . '_get_posts', $available_custom_widgets );

		if(count($available_custom_widgets) > 0 ) {
			foreach($available_custom_widgets as $widget) {
				$widget_params = array( 
					'ID' => $widget->ID, 
					'title' => $widget->post_title, 
					'content' => $widget->post_content,
					'image' => ( has_post_thumbnail( $widget->ID ) ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $widget->ID ), 'single-post-thumbnail' ) : null,
					'link_url' => get_post_meta($widget->ID, '_' . $token . '_link_url', true),
					'link_text' => get_post_meta($widget->ID, '_' . $token . '_link_text', true),
					'widget_description' => get_post_meta($widget->ID, '_' . $token . '_widget_description', true),
					'token' => $token
				);
				$tribe_widget_factory = new Tribe_WP_Widget_Factory();
				$tribe_widget_factory->register('Tribe_Widget_Builder_Display', $widget_params);
			}
		}
	}
}
add_action('widgets_init', 'tribe_load_custom_widget_display');

