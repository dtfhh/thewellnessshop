<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('organics_instagram_widget_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_instagram_widget_theme_setup', 1 );
	function organics_instagram_widget_theme_setup() {
		if (is_admin()) {
			add_filter( 'organics_filter_required_plugins',					'organics_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'organics_exists_instagram_widget' ) ) {
	function organics_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'organics_instagram_widget_required_plugins' ) ) {
	function organics_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', (array)organics_storage_get('required_plugins')))
		$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'organics'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}
