<?php
/* The GDPR Framework support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('organics_gdpr_compliance_theme_setup')) {
    add_action( 'organics_action_before_init_theme', 'organics_gdpr_compliance_theme_setup', 1 );
    function organics_gdpr_compliance_theme_setup() {
        if (is_admin()) {
            add_filter( 'organics_filter_required_plugins', 'organics_gdpr_compliance_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'organics_exists_gdpr_compliance' ) ) {
    function organics_exists_gdpr_compliance() {
        return defined( 'WP_GDPR_C_SLUG' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'organics_gdpr_compliance_required_plugins' ) ) {
    function organics_gdpr_compliance_required_plugins($list=array()) {
        if (in_array('gdpr-compliance', (array)organics_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('WP GDPR Compliance', 'organics'),
                'slug'         => 'wp-gdpr-compliance',
                'required'     => false
            );
        return $list;
    }
}