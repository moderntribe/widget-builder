<?php

// this is PHP v5.3+ safe

add_action( is_multisite() ? 'wp_network_dashboard_setup' : 'wp_dashboard_setup', function( $widget ) use ( $widget ) { 
	wp_add_dashboard_widget( $widget['token'] . '-' . $widget['ID'], $widget['title'], function( $widget ) use ( $widget ) { 
		// apply filters
		$content = apply_filters( 'the_content', empty( $widget['content'] ) ? '' : $widget['content'] );
		$content = str_replace(']]>', ']]&gt;', $content);

		// get template hierarchy
		include( Tribe_Widget_Builder::get_template_hierarchy( 'widget_dashboard' ) );
	});
});