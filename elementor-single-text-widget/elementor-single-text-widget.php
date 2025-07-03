<?php
/**
 * Plugin Name: Elementor Single Text Widget
 * Description: A simple Elementor widget with a single text input.
 * Version: 1.0
 * Author: Your Name* Requires Plugins: elementor
 * Elementor tested up to: 3.30.0
 * Elementor Pro tested up to: 3.30.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Load the widget file after Elementor is loaded
add_action( 'elementor/widgets/register', function( $widgets_manager ) {

	require_once('widget-single-text.php' );
	$widgets_manager->register( new \Elementor_Single_Text_Widget() );

} );
