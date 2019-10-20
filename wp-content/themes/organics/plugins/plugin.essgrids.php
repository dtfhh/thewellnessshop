<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('organics_essgrids_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_essgrids_theme_setup', 1 );
	function organics_essgrids_theme_setup() {
		if (is_admin()) {
			add_filter( 'organics_filter_importer_required_plugins',	'organics_essgrids_importer_required_plugins', 10, 2 );
			add_filter( 'organics_filter_required_plugins',				'organics_essgrids_required_plugins' );
		}
	}
}


// Check if Ess. Grid installed and activated
if ( !function_exists( 'organics_exists_essgrids' ) ) {
	function organics_exists_essgrids() {
		return defined('EG_PLUGIN_PATH');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'organics_essgrids_required_plugins' ) ) {
	function organics_essgrids_required_plugins($list=array()) {
		if (in_array('essgrids', (array)organics_storage_get('required_plugins'))) {
			$path = organics_get_file_dir('plugins/install/essential-grid.zip');
			if (file_exists($path)) {
		$list[] = array(
					'name' 		=> esc_html__('Essential Grid', 'organics'),
					'slug' 		=> 'essential-grid',
                    'version' 	=> '2.3.2',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}
