<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('organics_revslider_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_revslider_theme_setup' );
	function organics_revslider_theme_setup() {
		if (organics_exists_revslider()) {
			add_filter( 'organics_filter_list_sliders',					'organics_revslider_list_sliders' );
		}
		if (is_admin()) {
			add_filter( 'organics_filter_required_plugins',				'organics_revslider_required_plugins' );
		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'organics_exists_revslider' ) ) {
	function organics_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'organics_revslider_required_plugins' ) ) {
	function organics_revslider_required_plugins($list=array()) {
		if (in_array('revslider', (array)organics_storage_get('required_plugins'))) {
			$path = organics_get_file_dir('plugins/install/revslider.zip');
			if (file_exists($path)) {
		$list[] = array(
					'name' 		=> esc_html__('Revolution Slider', 'organics'),
					'slug' 		=> 'revslider',
                    'version' 	=> '6.0.7',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Lists
//------------------------------------------------------------------------

// Add RevSlider in the sliders list, prepended inherit (if need)
if ( !function_exists( 'organics_revslider_list_sliders' ) ) {
	function organics_revslider_list_sliders($list=array()) {
		$list["revo"] = esc_html__("Layer slider (Revolution)", 'organics');
		return $list;
	}
}

// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'organics_get_list_revo_sliders' ) ) {
	function organics_get_list_revo_sliders($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_revo_sliders']))
			$list = $ORGANICS_GLOBALS['list_revo_sliders'];
		else {
			$list = array();
			if (organics_exists_revslider()) {
				global $wpdb;
                // Attention! The use of wpdb->prepare() is not required
                // because the query does not use external data substitution
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$ORGANICS_GLOBALS['list_revo_sliders'] = $list = apply_filters('organics_filter_list_revo_sliders', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}
?>