<?php
/**
 * AxiomThemes Framework: shortcodes manipulations
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('organics_sc_theme_setup')) {
	add_action( 'organics_action_init_theme', 'organics_sc_theme_setup', 1 );
	function organics_sc_theme_setup() {
		// Add sc stylesheets
		add_action('organics_action_add_styles', 'organics_sc_add_styles', 1);
	}
}

if (!function_exists('organics_sc_theme_setup2')) {
	add_action( 'organics_action_before_init_theme', 'organics_sc_theme_setup2' );
	function organics_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'organics_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('organics_sc_prepare_content')) organics_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('organics_shortcode_output', 'organics_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'organics_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'organics_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'organics_sc_selector_add_in_toolbar', 11);

	}
}


// Add shortcodes styles
if ( !function_exists( 'organics_sc_add_styles' ) ) {
	//add_action('organics_action_add_styles', 'organics_sc_add_styles', 1);
	function organics_sc_add_styles() {
		// Shortcodes
		wp_enqueue_style( 'organics-shortcodes-style',	trx_utils_get_file_url('shortcodes/shortcodes.css'), array(), null );
	}
}


// Add shortcodes init scripts
if ( !function_exists( 'organics_sc_add_scripts' ) ) {
	//add_filter('organics_shortcode_output', 'organics_sc_add_scripts', 10, 4);
	function organics_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		global $ORGANICS_GLOBALS;
		
		if (empty($ORGANICS_GLOBALS['shortcodes_scripts_added'])) {
			$ORGANICS_GLOBALS['shortcodes_scripts_added'] = true;
			//wp_enqueue_style( 'organics-shortcodes-style', trx_utils_get_file_url('shortcodes/shortcodes.css'), array(), null );
			wp_enqueue_script( 'organics-shortcodes-script', trx_utils_get_file_url('shortcodes/shortcodes.js'), array('jquery'), null, true );
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('organics_sc_prepare_content')) {
	function organics_sc_prepare_content() {
		if (function_exists('organics_sc_clear_around')) {
			$filters = array(
				array('axiomthemes', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
            if (function_exists('organics_exists_woocommerce') && organics_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'organics_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('organics_sc_excerpt_shortcodes')) {
	function organics_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('organics_sc_clear_around')) {
	function organics_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// Organics shortcodes load scripts
if (!function_exists('organics_sc_load_scripts')) {
	function organics_sc_load_scripts() {
		wp_enqueue_script( 'organics-shortcodes-script', trx_utils_get_file_url('shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		wp_enqueue_script( 'organics-selection-script',  organics_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
	}
}

// Organics shortcodes prepare scripts
if (!function_exists('organics_sc_prepare_scripts')) {
	function organics_sc_prepare_scripts() {
		global $ORGANICS_GLOBALS;
		if (!isset($ORGANICS_GLOBALS['shortcodes_prepared'])) {
			$ORGANICS_GLOBALS['shortcodes_prepared'] = true;
			$json_parse_func = 'eval';	// 'JSON.parse'
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					try {
						ORGANICS_GLOBALS['shortcodes'] = <?php organics_show_layout($json_parse_func); ?>(<?php echo json_encode( organics_array_prepare_to_json($ORGANICS_GLOBALS['shortcodes']) ); ?>);
					} catch (e) {}
					ORGANICS_GLOBALS['shortcodes_cp'] = '<?php echo is_admin() ? (!empty($ORGANICS_GLOBALS['to_colorpicker']) ? $ORGANICS_GLOBALS['to_colorpicker'] : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('organics_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','organics_sc_selector_add_in_toolbar', 11);
	function organics_sc_selector_add_in_toolbar(){

		if ( !organics_options_is_used() ) return;

		organics_sc_load_scripts();
		organics_sc_prepare_scripts();

		global $ORGANICS_GLOBALS;

		$shortcodes = $ORGANICS_GLOBALS['shortcodes'];
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'trx_utils').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo ($shortcodes_list);
	}
}

// Organics shortcodes builder settings
require_once trx_utils_get_file_dir('shortcodes/shortcodes_settings.php');

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once trx_utils_get_file_dir('shortcodes/shortcodes_vc.php');
}

// Organics shortcodes implementation
require_once trx_utils_get_file_dir('shortcodes/shortcodes.php');



/**
 * Additional Shortcodes
 */

// Theme init
if (!function_exists('organics_init_more_shortcodes')) {
	add_action( 'organics_action_before_init_theme', 'organics_init_more_shortcodes' );
	function organics_init_more_shortcodes() {

		// Register shortcodes [trx_team] and [trx_team_item]
		add_action('organics_action_shortcodes_list', 'organics_team_reg_shortcodes');
		if (function_exists('organics_exists_visual_composer') && organics_exists_visual_composer())
			add_action('organics_action_shortcodes_list_vc', 'organics_team_reg_shortcodes_vc');

		// Register shortcodes [trx_testimonials] and [trx_testimonials_item]
		add_action('organics_action_shortcodes_list',		'organics_testimonials_reg_shortcodes');
		if (function_exists('organics_exists_visual_composer') && organics_exists_visual_composer())
			add_action('organics_action_shortcodes_list_vc','organics_testimonials_reg_shortcodes_vc');

		// Register shortcodes [trx_services] and [trx_services_item]
		add_action('organics_action_shortcodes_list',		'organics_services_reg_shortcodes');
		if (function_exists('organics_exists_visual_composer') && organics_exists_visual_composer()) {
			add_action('organics_action_shortcodes_list_vc','organics_services_reg_shortcodes_vc');
		}

		// Add shortcodes [trx_clients] and [trx_clients_item] in the shortcodes list
		add_action('organics_action_shortcodes_list',		'organics_clients_reg_shortcodes');
		if (function_exists('organics_exists_visual_composer') && organics_exists_visual_composer())
			add_action('organics_action_shortcodes_list_vc',	'organics_clients_reg_shortcodes_vc');

		add_action('organics_action_shortcodes_list', 			'organics_woocommerce_reg_shortcodes', 20);
		if (function_exists('organics_exists_visual_composer') && organics_exists_visual_composer())
			add_action('organics_action_shortcodes_list_vc',	'organics_woocommerce_reg_shortcodes_vc', 20);

	}
}




// Register shortcodes to the internal builder
//------------------------------------------------------------------------
if ( !function_exists( 'organics_woocommerce_reg_shortcodes' ) ) {
	function organics_woocommerce_reg_shortcodes() {

		// WooCommerce - Cart
		organics_sc_map("woocommerce_cart", array(
				"title" => esc_html__("Woocommerce: Cart", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show Cart page", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array()
			)
		);

		// WooCommerce - Checkout
		organics_sc_map("woocommerce_checkout", array(
				"title" => esc_html__("Woocommerce: Checkout", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show Checkout page", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array()
			)
		);

		// WooCommerce - My Account
		organics_sc_map("woocommerce_my_account", array(
				"title" => esc_html__("Woocommerce: My Account", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show My Account page", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array()
			)
		);

		// WooCommerce - Order Tracking
		organics_sc_map("woocommerce_order_tracking", array(
				"title" => esc_html__("Woocommerce: Order Tracking", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show Order Tracking page", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array()
			)
		);

		// WooCommerce - Shop Messages
		organics_sc_map("shop_messages", array(
				"title" => esc_html__("Woocommerce: Shop Messages", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show shop messages", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array()
			)
		);

		// WooCommerce - Product Page
		organics_sc_map("product_page", array(
				"title" => esc_html__("Woocommerce: Product Page", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: display single product page", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"sku" => array(
						"title" => esc_html__("SKU", "organics"),
						"desc" => wp_kses_data( __("SKU code of displayed product", "organics") ),
						"value" => "",
						"type" => "text"
					),
					"id" => array(
						"title" => esc_html__("ID", "organics"),
						"desc" => wp_kses_data( __("ID of displayed product", "organics") ),
						"value" => "",
						"type" => "text"
					),
					"posts_per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => "1",
						"min" => 1,
						"type" => "spinner"
					),
					"post_type" => array(
						"title" => esc_html__("Post type", "organics"),
						"desc" => wp_kses_data( __("Post type for the WP query (leave 'product')", "organics") ),
						"value" => "product",
						"type" => "text"
					),
					"post_status" => array(
						"title" => esc_html__("Post status", "organics"),
						"desc" => wp_kses_data( __("Display posts only with this status", "organics") ),
						"value" => "publish",
						"type" => "select",
						"options" => array(
							"publish" => esc_html__('Publish', 'organics'),
							"protected" => esc_html__('Protected', 'organics'),
							"private" => esc_html__('Private', 'organics'),
							"pending" => esc_html__('Pending', 'organics'),
							"draft" => esc_html__('Draft', 'organics')
						)
					)
				)
			)
		);

		// WooCommerce - Product
		organics_sc_map("product", array(
				"title" => esc_html__("Woocommerce: Product", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: display one product", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"sku" => array(
						"title" => esc_html__("SKU", "organics"),
						"desc" => wp_kses_data( __("SKU code of displayed product", "organics") ),
						"value" => "",
						"type" => "text"
					),
					"id" => array(
						"title" => esc_html__("ID", "organics"),
						"desc" => wp_kses_data( __("ID of displayed product", "organics") ),
						"value" => "",
						"type" => "text"
					)
				)
			)
		);

		// WooCommerce - Best Selling Products
		organics_sc_map("best_selling_products", array(
				"title" => esc_html__("Woocommerce: Best Selling Products", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show best selling products", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					)
				)
			)
		);

		// WooCommerce - Recent Products
		organics_sc_map("recent_products", array(
				"title" => esc_html__("Woocommerce: Recent Products", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show recent products", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					),
					"order" => array(
						"title" => esc_html__("Order", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "desc",
						"type" => "switch",
						"size" => "big",
						"options" => organics_get_sc_param('ordering')
					)
				)
			)
		);

		// WooCommerce - Related Products
		organics_sc_map("related_products", array(
				"title" => esc_html__("Woocommerce: Related Products", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show related products", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"posts_per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					)
				)
			)
		);

		// WooCommerce - Featured Products
		organics_sc_map("featured_products", array(
				"title" => esc_html__("Woocommerce: Featured Products", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show featured products", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					),
					"order" => array(
						"title" => esc_html__("Order", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "desc",
						"type" => "switch",
						"size" => "big",
						"options" => organics_get_sc_param('ordering')
					)
				)
			)
		);

		// WooCommerce - Top Rated Products
		organics_sc_map("featured_products", array(
				"title" => esc_html__("Woocommerce: Top Rated Products", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show top rated products", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					),
					"order" => array(
						"title" => esc_html__("Order", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "desc",
						"type" => "switch",
						"size" => "big",
						"options" => organics_get_sc_param('ordering')
					)
				)
			)
		);

		// WooCommerce - Sale Products
		organics_sc_map("featured_products", array(
				"title" => esc_html__("Woocommerce: Sale Products", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: list products on sale", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					),
					"order" => array(
						"title" => esc_html__("Order", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "desc",
						"type" => "switch",
						"size" => "big",
						"options" => organics_get_sc_param('ordering')
					)
				)
			)
		);

		// WooCommerce - Product Category
		organics_sc_map("product_category", array(
				"title" => esc_html__("Woocommerce: Products from category", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					),
					"order" => array(
						"title" => esc_html__("Order", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "desc",
						"type" => "switch",
						"size" => "big",
						"options" => organics_get_sc_param('ordering')
					),
					"category" => array(
						"title" => esc_html__("Categories", "organics"),
						"desc" => wp_kses_data( __("Comma separated category slugs", "organics") ),
						"value" => '',
						"type" => "text"
					),
					"operator" => array(
						"title" => esc_html__("Operator", "organics"),
						"desc" => wp_kses_data( __("Categories operator", "organics") ),
						"value" => "IN",
						"type" => "checklist",
						"size" => "medium",
						"options" => array(
							"IN" => esc_html__('IN', 'organics'),
							"NOT IN" => esc_html__('NOT IN', 'organics'),
							"AND" => esc_html__('AND', 'organics')
						)
					)
				)
			)
		);

		// WooCommerce - Products
		organics_sc_map("products", array(
				"title" => esc_html__("Woocommerce: Products", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: list all products", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"skus" => array(
						"title" => esc_html__("SKUs", "organics"),
						"desc" => wp_kses_data( __("Comma separated SKU codes of products", "organics") ),
						"value" => "",
						"type" => "text"
					),
					"ids" => array(
						"title" => esc_html__("IDs", "organics"),
						"desc" => wp_kses_data( __("Comma separated ID of products", "organics") ),
						"value" => "",
						"type" => "text"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					),
					"order" => array(
						"title" => esc_html__("Order", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "desc",
						"type" => "switch",
						"size" => "big",
						"options" => organics_get_sc_param('ordering')
					)
				)
			)
		);

		// WooCommerce - Product attribute
		organics_sc_map("product_attribute", array(
				"title" => esc_html__("Woocommerce: Products by Attribute", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"per_page" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many products showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					),
					"order" => array(
						"title" => esc_html__("Order", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "desc",
						"type" => "switch",
						"size" => "big",
						"options" => organics_get_sc_param('ordering')
					),
					"attribute" => array(
						"title" => esc_html__("Attribute", "organics"),
						"desc" => wp_kses_data( __("Attribute name", "organics") ),
						"value" => "",
						"type" => "text"
					),
					"filter" => array(
						"title" => esc_html__("Filter", "organics"),
						"desc" => wp_kses_data( __("Attribute value", "organics") ),
						"value" => "",
						"type" => "text"
					)
				)
			)
		);

		// WooCommerce - Products Categories
		organics_sc_map("product_categories", array(
				"title" => esc_html__("Woocommerce: Product Categories", "organics"),
				"desc" => wp_kses_data( __("WooCommerce shortcode: show categories with products", "organics") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"number" => array(
						"title" => esc_html__("Number", "organics"),
						"desc" => wp_kses_data( __("How many categories showed", "organics") ),
						"value" => 4,
						"min" => 1,
						"type" => "spinner"
					),
					"columns" => array(
						"title" => esc_html__("Columns", "organics"),
						"desc" => wp_kses_data( __("How many columns per row use for categories output", "organics") ),
						"value" => 4,
						"min" => 2,
						"max" => 4,
						"type" => "spinner"
					),
					"orderby" => array(
						"title" => esc_html__("Order by", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "date",
						"type" => "select",
						"options" => array(
							"date" => esc_html__('Date', 'organics'),
							"title" => esc_html__('Title', 'organics')
						)
					),
					"order" => array(
						"title" => esc_html__("Order", "organics"),
						"desc" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"value" => "desc",
						"type" => "switch",
						"size" => "big",
						"options" => organics_get_sc_param('ordering')
					),
					"parent" => array(
						"title" => esc_html__("Parent", "organics"),
						"desc" => wp_kses_data( __("Parent category slug", "organics") ),
						"value" => "",
						"type" => "text"
					),
					"ids" => array(
						"title" => esc_html__("IDs", "organics"),
						"desc" => wp_kses_data( __("Comma separated ID of products", "organics") ),
						"value" => "",
						"type" => "text"
					),
					"hide_empty" => array(
						"title" => esc_html__("Hide empty", "organics"),
						"desc" => wp_kses_data( __("Hide empty categories", "organics") ),
						"value" => "yes",
						"type" => "switch",
						"options" => organics_get_sc_param('yes_no')
					)
				)
			)
		);
	}
}



// Register shortcodes to the VC builder
//------------------------------------------------------------------------
if ( !function_exists( 'organics_woocommerce_reg_shortcodes_vc' ) ) {
	function organics_woocommerce_reg_shortcodes_vc() {

		if (false && function_exists('organics_exists_woocommerce') && organics_exists_woocommerce()) {

			// WooCommerce - Cart
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "woocommerce_cart",
				"name" => esc_html__("Cart", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show cart page", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_wooc_cart',
				"class" => "trx_sc_alone trx_sc_woocommerce_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "organics"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "organics") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Woocommerce_Cart extends ORGANICS_VC_ShortCodeAlone {}


			// WooCommerce - Checkout
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "woocommerce_checkout",
				"name" => esc_html__("Checkout", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show checkout page", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_wooc_checkout',
				"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "organics"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "organics") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Woocommerce_Checkout extends ORGANICS_VC_ShortCodeAlone {}


			// WooCommerce - My Account
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "woocommerce_my_account",
				"name" => esc_html__("My Account", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show my account page", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_wooc_my_account',
				"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "organics"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "organics") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Woocommerce_My_Account extends ORGANICS_VC_ShortCodeAlone {}


			// WooCommerce - Order Tracking
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "woocommerce_order_tracking",
				"name" => esc_html__("Order Tracking", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show order tracking page", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_wooc_order_tracking',
				"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "organics"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "organics") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Woocommerce_Order_Tracking extends ORGANICS_VC_ShortCodeAlone {}


			// WooCommerce - Shop Messages
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "shop_messages",
				"name" => esc_html__("Shop Messages", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show shop messages", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_wooc_shop_messages',
				"class" => "trx_sc_alone trx_sc_shop_messages",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "organics"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "organics") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Shop_Messages extends ORGANICS_VC_ShortCodeAlone {}


			// WooCommerce - Product Page
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product_page",
				"name" => esc_html__("Product Page", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: display single product page", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_product_page',
				"class" => "trx_sc_single trx_sc_product_page",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", "organics"),
						"description" => wp_kses_data( __("SKU code of displayed product", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", "organics"),
						"description" => wp_kses_data( __("ID of displayed product", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", "organics"),
						"description" => wp_kses_data( __("Post type for the WP query (leave 'product')", "organics") ),
						"class" => "",
						"value" => "product",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_status",
						"heading" => esc_html__("Post status", "organics"),
						"description" => wp_kses_data( __("Display posts only with this status", "organics") ),
						"class" => "",
						"value" => array(
							esc_html__('Publish', 'organics') => 'publish',
							esc_html__('Protected', 'organics') => 'protected',
							esc_html__('Private', 'organics') => 'private',
							esc_html__('Pending', 'organics') => 'pending',
							esc_html__('Draft', 'organics') => 'draft'
						),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Product_Page extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Product
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product",
				"name" => esc_html__("Product", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: display one product", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_product',
				"class" => "trx_sc_single trx_sc_product",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", "organics"),
						"description" => wp_kses_data( __("Product's SKU code", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", "organics"),
						"description" => wp_kses_data( __("Product's ID", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Product extends ORGANICS_VC_ShortCodeSingle {}


			// WooCommerce - Best Selling Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "best_selling_products",
				"name" => esc_html__("Best Selling Products", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show best selling products", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_best_selling_products',
				"class" => "trx_sc_single trx_sc_best_selling_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Best_Selling_Products extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Recent Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "recent_products",
				"name" => esc_html__("Recent Products", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show recent products", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_recent_products',
				"class" => "trx_sc_single trx_sc_recent_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"

					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Recent_Products extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Related Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "related_products",
				"name" => esc_html__("Related Products", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show related products", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_related_products',
				"class" => "trx_sc_single trx_sc_related_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Related_Products extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Featured Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "featured_products",
				"name" => esc_html__("Featured Products", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show featured products", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_featured_products',
				"class" => "trx_sc_single trx_sc_featured_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Featured_Products extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Top Rated Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "top_rated_products",
				"name" => esc_html__("Top Rated Products", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show top rated products", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_top_rated_products',
				"class" => "trx_sc_single trx_sc_top_rated_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Top_Rated_Products extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Sale Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "sale_products",
				"name" => esc_html__("Sale Products", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products on sale", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_sale_products',
				"class" => "trx_sc_single trx_sc_sale_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Sale_Products extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Product Category
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product_category",
				"name" => esc_html__("Products from category", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_product_category',
				"class" => "trx_sc_single trx_sc_product_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "category",
						"heading" => esc_html__("Categories", "organics"),
						"description" => wp_kses_data( __("Comma separated category slugs", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "operator",
						"heading" => esc_html__("Operator", "organics"),
						"description" => wp_kses_data( __("Categories operator", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('IN', 'organics') => 'IN',
							esc_html__('NOT IN', 'organics') => 'NOT IN',
							esc_html__('AND', 'organics') => 'AND'
						),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Product_Category extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Products
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "products",
				"name" => esc_html__("Products", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: list all products", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_products',
				"class" => "trx_sc_single trx_sc_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "skus",
						"heading" => esc_html__("SKUs", "organics"),
						"description" => wp_kses_data( __("Comma separated SKU codes of products", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", "organics"),
						"description" => wp_kses_data( __("Comma separated ID of products", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );

			class WPBakeryShortCode_Products extends ORGANICS_VC_ShortCodeSingle {}




			// WooCommerce - Product Attribute
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product_attribute",
				"name" => esc_html__("Products by Attribute", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_product_attribute',
				"class" => "trx_sc_single trx_sc_product_attribute",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many products showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "attribute",
						"heading" => esc_html__("Attribute", "organics"),
						"description" => wp_kses_data( __("Attribute name", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "filter",
						"heading" => esc_html__("Filter", "organics"),
						"description" => wp_kses_data( __("Attribute value", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );

			class WPBakeryShortCode_Product_Attribute extends ORGANICS_VC_ShortCodeSingle {}



			// WooCommerce - Products Categories
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "product_categories",
				"name" => esc_html__("Product Categories", "organics"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show categories with products", "organics") ),
				"category" => esc_html__('WooCommerce', 'organics'),
				'icon' => 'icon_trx_product_categories',
				"class" => "trx_sc_single trx_sc_product_categories",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number", "organics"),
						"description" => wp_kses_data( __("How many categories showed", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses_data( __("How many columns per row use for categories output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'organics') => 'date',
							esc_html__('Title', 'organics') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "organics"),
						"description" => wp_kses_data( __("Sorting order for products output", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "parent",
						"heading" => esc_html__("Parent", "organics"),
						"description" => wp_kses_data( __("Parent category slug", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "date",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", "organics"),
						"description" => wp_kses_data( __("Comma separated ID of products", "organics") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "hide_empty",
						"heading" => esc_html__("Hide empty", "organics"),
						"description" => wp_kses_data( __("Hide empty categories", "organics") ),
						"class" => "",
						"value" => array("Hide empty" => "1" ),
						"type" => "checkbox"
					)
				)
			) );

			class WPBakeryShortCode_Products_Categories extends ORGANICS_VC_ShortCodeSingle {}

		}
	}
}