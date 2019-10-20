<?php
/* WPBakery PageBuilder support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('organics_vc_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_vc_theme_setup', 1 );
	function organics_vc_theme_setup() {
		if (is_admin()) {
			add_filter( 'organics_filter_required_plugins',					'organics_vc_required_plugins' );
		}
	}
}

// Check if WPBakery PageBuilder installed and activated
if ( !function_exists( 'organics_exists_visual_composer' ) ) {
	function organics_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery PageBuilder in frontend editor mode
if ( !function_exists( 'organics_vc_is_frontend' ) ) {
	function organics_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'organics_vc_required_plugins' ) ) {
	function organics_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', (array)organics_storage_get('required_plugins'))) {
			$path = organics_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
		$list[] = array(
					'name' 		=> esc_html__('WPBakery PageBuilder', 'organics'),
					'slug' 		=> 'js_composer',
                    'version' 	=> '6.0.3',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}
