<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('organics_gutenberg_theme_setup')) {
    add_action( 'organics_action_before_init_theme', 'organics_gutenberg_theme_setup', 1 );
    function organics_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'organics_filter_required_plugins', 'organics_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'organics_exists_gutenberg' ) ) {
    function organics_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'organics_gutenberg_required_plugins' ) ) {
    function organics_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)organics_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Gutenberg', 'organics'),
                'slug'         => 'gutenberg',
                'required'     => false
            );
        return $list;
    }
}