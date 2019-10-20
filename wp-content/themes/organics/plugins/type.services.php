<?php
/**
 * AxiomThemes Framework: Services post type settings
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Theme init
if (!function_exists('organics_services_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_services_theme_setup' );
	function organics_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('organics_filter_get_blog_type',			'organics_services_get_blog_type', 9, 2);
		add_filter('organics_filter_get_blog_title',		'organics_services_get_blog_title', 9, 2);
		add_filter('organics_filter_get_current_taxonomy',	'organics_services_get_current_taxonomy', 9, 2);
		add_filter('organics_filter_is_taxonomy',			'organics_services_is_taxonomy', 9, 2);
		add_filter('organics_filter_get_stream_page_title',	'organics_services_get_stream_page_title', 9, 2);
		add_filter('organics_filter_get_stream_page_link',	'organics_services_get_stream_page_link', 9, 2);
		add_filter('organics_filter_get_stream_page_id',	'organics_services_get_stream_page_id', 9, 2);
		add_filter('organics_filter_query_add_filters',		'organics_services_query_add_filters', 9, 2);
		add_filter('organics_filter_detect_inheritance_key','organics_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (organics_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'organics_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'organics_post_fill_options_column', 9, 2);
		}


        // Add supported data types
        organics_theme_support_pt('services');
        organics_theme_support_tx('services_group');
	}
}

if ( !function_exists( 'organics_services_settings_theme_setup2' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_services_settings_theme_setup2', 3 );
	function organics_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		organics_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-service',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'page'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'organics_is_services_page' ) ) {
	function organics_is_services_page() {
		global $ORGANICS_GLOBALS;
		$is = in_array($ORGANICS_GLOBALS['page_template'], array('blog-services', 'single-service'));
		if (!$is) {
			if (!empty($ORGANICS_GLOBALS['pre_query']))
				$is = $ORGANICS_GLOBALS['pre_query']->get('post_type')=='services' 
						|| $ORGANICS_GLOBALS['pre_query']->is_tax('services_group') 
						|| ($ORGANICS_GLOBALS['pre_query']->is_page() 
								&& ($id=organics_get_template_page_id('blog-services')) > 0 
								&& $id==(isset($ORGANICS_GLOBALS['pre_query']->queried_object_id) 
											? $ORGANICS_GLOBALS['pre_query']->queried_object_id 
											: 0)
						);
			else
				$is = get_query_var('post_type')=='services' 
						|| is_tax('services_group') 
						|| (is_page() && ($id=organics_get_template_page_id('blog-services')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'organics_services_detect_inheritance_key' ) ) {
	function organics_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return organics_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'organics_services_get_blog_type' ) ) {
	function organics_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'organics_services_get_blog_title' ) ) {
	function organics_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( organics_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = organics_get_post_title();
			} else {
				$title = esc_html__('All services', 'organics');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'organics_services_get_stream_page_title' ) ) {
	function organics_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (organics_strpos($page, 'services')!==false) {
			if (($page_id = organics_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = organics_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'organics');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'organics_services_get_stream_page_id' ) ) {
	function organics_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (organics_strpos($page, 'services')!==false) $id = organics_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'organics_services_get_stream_page_link' ) ) {
	function organics_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (organics_strpos($page, 'services')!==false) {
			$id = organics_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'organics_services_get_current_taxonomy' ) ) {
	function organics_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( organics_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'organics_services_is_taxonomy' ) ) {
	function organics_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'organics_services_query_add_filters' ) ) {
	function organics_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}


