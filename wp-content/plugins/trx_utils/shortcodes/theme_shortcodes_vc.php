<?php
if ( !function_exists( 'organics_shortcodes2_vc_theme_setup' ) ) {
	
	if (function_exists('organics_exists_visual_composer') && organics_exists_visual_composer())
			add_action('organics_action_before_init_theme','organics_shortcodes2_vc_theme_setup');
    

    function organics_shortcodes2_vc_theme_setup() {

        if (organics_shortcodes_is_used()) {

            global $ORGANICS_GLOBALS;

            // Image
            //-------------------------------------------------------------------------------------

            vc_map(array(
                "base" => "trx_image",
                "name" => __("Image", 'trx_utils'),
                "description" => __("Insert image", 'trx_utils'),
                "category" => __('Content', 'trx_utils'),
                'icon' => 'icon_trx_image',
                "class" => "trx_sc_single trx_sc_image",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "url",
                        "heading" => __("Select image", 'trx_utils'),
                        "description" => __("Select image from library", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "",
                        "type" => "attach_image"
                    ),
                    array(
                        "param_name" => "align",
                        "heading" => __("Image alignment", 'trx_utils'),
                        "description" => __("Align image to left or right side", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['float']),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "shape",
                        "heading" => __("Image shape", 'trx_utils'),
                        "description" => __("Shape of the image: square or round", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Square', 'trx_utils') => 'square'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "title",
                        "heading" => __("Title", 'trx_utils'),
                        "description" => __("Image's title", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "icon",
                        "heading" => __("Title's icon", 'trx_utils'),
                        "description" => __("Select icon for the title from Fontello icons set", 'trx_utils'),
                        "class" => "",
                        "value" => $ORGANICS_GLOBALS['sc_params']['icons'],
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "link",
                        "heading" => __("Link", 'trx_utils'),
                        "description" => __("The link URL from the image", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),
                    $ORGANICS_GLOBALS['vc_params']['id'],
                    $ORGANICS_GLOBALS['vc_params']['class'],
                    $ORGANICS_GLOBALS['vc_params']['animation'],
                    $ORGANICS_GLOBALS['vc_params']['css'],
                    organics_vc_width(),
                    organics_vc_height(),
                    $ORGANICS_GLOBALS['vc_params']['margin_top'],
                    $ORGANICS_GLOBALS['vc_params']['margin_bottom'],
                    $ORGANICS_GLOBALS['vc_params']['margin_left'],
                    $ORGANICS_GLOBALS['vc_params']['margin_right']
                )
            ));









            // Axiomthemes - Recent Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_recent_products",
                "name" => esc_html__("Axiomthemes Slider Recent Products", 'trx_utils'),
                "description" => esc_html__("WooCommerce shortcode: show recent products", 'trx_utils'),
                "category" => esc_html__('WooCommerce', 'trx_utils'),
                'icon' => 'icon_trx_recent_products',
                "class" => "trx_sc_single trx_sc_recent_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", 'trx_utils'),
                        "description" => esc_html__("How many products showed", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", 'trx_utils'),
                        "description" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "orderby",
                        "heading" => esc_html__("Order by", 'trx_utils'),
                        "description" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Date', 'trx_utils') => 'date',
                            __('Title', 'trx_utils') => 'title'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "order",
                        "heading" => esc_html__("Order", 'trx_utils'),
                        "description" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
                        "type" => "dropdown"
                    )
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Recent_Products extends ORGANICS_VC_ShortCodeSingle {}



            // WooCommerce - Featured Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_featured_products",
                "name" => esc_html__("Axiomthemes Slider Featured Products", 'trx_utils'),
                "description" => esc_html__("WooCommerce shortcode: show featured products", 'trx_utils'),
                "category" => esc_html__('WooCommerce', 'trx_utils'),
                'icon' => 'icon_trx_featured_products',
                "class" => "trx_sc_single trx_sc_featured_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", 'trx_utils'),
                        "description" => esc_html__("How many products showed", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", 'trx_utils'),
                        "description" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "orderby",
                        "heading" => esc_html__("Order by", 'trx_utils'),
                        "description" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Date', 'trx_utils') => 'date',
                            __('Title', 'trx_utils') => 'title'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "order",
                        "heading" => esc_html__("Order", 'trx_utils'),
                        "description" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
                        "type" => "dropdown"
                    )
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Featured_Products extends ORGANICS_VC_ShortCodeSingle {}



            // Axiomthemes - Best Selling Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_best_selling_products",
                "name" => esc_html__("Axiomthemes Slider Best Selling Products", 'trx_utils'),
                "description" => esc_html__("WooCommerce shortcode: show best selling products", 'trx_utils'),
                "category" => esc_html__('WooCommerce', 'trx_utils'),
                'icon' => 'icon_trx_best_selling_products',
                "class" => "trx_sc_single trx_sc_best_selling_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", 'trx_utils'),
                        "description" => esc_html__("How many products showed", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", 'trx_utils'),
                        "description" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Best_Selling_Products extends ORGANICS_VC_ShortCodeSingle {}



            // Axiomthemes - Sale Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_sale_products",
                "name" => esc_html__("Axiomthemes Slider Sale Products", 'trx_utils'),
                "description" => esc_html__("WooCommerce shortcode: show sale products", 'trx_utils'),
                "category" => esc_html__('WooCommerce', 'trx_utils'),
                'icon' => 'icon_trx_sale_products',
                "class" => "trx_sc_single trx_sc_sale_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", 'trx_utils'),
                        "description" => esc_html__("How many products showed", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", 'trx_utils'),
                        "description" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "orderby",
                        "heading" => esc_html__("Order by", 'trx_utils'),
                        "description" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Date', 'trx_utils') => 'date',
                            __('Title', 'trx_utils') => 'title'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "order",
                        "heading" => esc_html__("Order", 'trx_utils'),
                        "description" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
                        "type" => "dropdown"
                    )
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Sale_Products extends ORGANICS_VC_ShortCodeSingle {}



            // Axiomthemes - Top Rated Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_top_rated_products",
                "name" => esc_html__("Axiomthemes Slider Top Rated Products", 'trx_utils'),
                "description" => esc_html__("WooCommerce shortcode: show top rated products", 'trx_utils'),
                "category" => esc_html__('WooCommerce', 'trx_utils'),
                'icon' => 'icon_trx_top_rated_products',
                "class" => "trx_sc_single trx_sc_top_rated_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", 'trx_utils'),
                        "description" => esc_html__("How many products showed", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", 'trx_utils'),
                        "description" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "orderby",
                        "heading" => esc_html__("Order by", 'trx_utils'),
                        "description" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Date', 'trx_utils') => 'date',
                            __('Title', 'trx_utils') => 'title'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "order",
                        "heading" => esc_html__("Order", 'trx_utils'),
                        "description" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
                        "type" => "dropdown"
                    )
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Top_Rated_Products extends ORGANICS_VC_ShortCodeSingle {}



            // Button
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_button",
                "name" => __("Button", 'trx_utils'),
                "description" => __("Button with link", 'trx_utils'),
                "category" => __('Content', 'trx_utils'),
                'icon' => 'icon_trx_button',
                "class" => "trx_sc_single trx_sc_button",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "content",
                        "heading" => __("Caption", 'trx_utils'),
                        "description" => __("Button caption", 'trx_utils'),
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "type",
                        "heading" => __("Button's shape", 'trx_utils'),
                        "description" => __("Select button's shape", 'trx_utils'),
                        "class" => "",
                        "value" => array(
                            __('Round', 'trx_utils') => 'round',
                            __('Square', 'trx_utils') => 'square'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "style",
                        "heading" => __("Button's style", 'trx_utils'),
                        "description" => __("Select button's style", 'trx_utils'),
                        "class" => "",
                        "value" => array(
                            __('Filled', 'trx_utils') => 'filled',
                            __('Border', 'trx_utils') => 'border'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "scheme",
                        "heading" => __("Button's color scheme", 'trx_utils'),
                        "description" => __("Select button's color scheme", 'trx_utils'),
                        "class" => "",
                        "value" => array(
                            __('Original', 'trx_utils') => 'original',
                            __('Dark', 'trx_utils') => 'dark',
                            __('Orange', 'trx_utils') => 'orange',
                            __('Crimson', 'trx_utils') => 'crimson'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "size",
                        "heading" => __("Button's size", 'trx_utils'),
                        "description" => __("Select button's size", 'trx_utils'),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Small', 'trx_utils') => 'small',
                            __('Medium', 'trx_utils') => 'medium',
                            __('Large', 'trx_utils') => 'large'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "icon",
                        "heading" => __("Button's icon", 'trx_utils'),
                        "description" => __("Select icon for the title from Fontello icons set", 'trx_utils'),
                        "class" => "",
                        "value" => $ORGANICS_GLOBALS['sc_params']['icons'],
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "color",
                        "heading" => __("Button's text color", 'trx_utils'),
                        "description" => __("Any color for button's caption", 'trx_utils'),
                        "class" => "",
                        "value" => "",
                        "type" => "colorpicker"
                    ),
                    array(
                        "param_name" => "bg_color",
                        "heading" => __("Button's backcolor", 'trx_utils'),
                        "description" => __("Any color for button's background", 'trx_utils'),
                        "class" => "",
                        "value" => "",
                        "type" => "colorpicker"
                    ),
                    array(
                        "param_name" => "align",
                        "heading" => __("Button's alignment", 'trx_utils'),
                        "description" => __("Align button to left, center or right", 'trx_utils'),
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "link",
                        "heading" => __("Link URL", 'trx_utils'),
                        "description" => __("URL for the link on button click", 'trx_utils'),
                        "class" => "",
                        "group" => __('Link', 'trx_utils'),
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "target",
                        "heading" => __("Link target", 'trx_utils'),
                        "description" => __("Target for the link on button click", 'trx_utils'),
                        "class" => "",
                        "group" => __('Link', 'trx_utils'),
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "popup",
                        "heading" => __("Open link in popup", 'trx_utils'),
                        "description" => __("Open link target in popup window", 'trx_utils'),
                        "class" => "",
                        "group" => __('Link', 'trx_utils'),
                        "value" => array(__('Open in popup', 'trx_utils') => 'yes'),
                        "type" => "checkbox"
                    ),
                    array(
                        "param_name" => "rel",
                        "heading" => __("Rel attribute", 'trx_utils'),
                        "description" => __("Rel attribute for the button's link (if need", 'trx_utils'),
                        "class" => "",
                        "group" => __('Link', 'trx_utils'),
                        "value" => "",
                        "type" => "textfield"
                    ),
                    $ORGANICS_GLOBALS['vc_params']['id'],
                    $ORGANICS_GLOBALS['vc_params']['class'],
                    $ORGANICS_GLOBALS['vc_params']['animation'],
                    $ORGANICS_GLOBALS['vc_params']['css'],
                    organics_vc_width(),
                    organics_vc_height(),
                    $ORGANICS_GLOBALS['vc_params']['margin_top'],
                    $ORGANICS_GLOBALS['vc_params']['margin_bottom'],
                    $ORGANICS_GLOBALS['vc_params']['margin_left'],
                    $ORGANICS_GLOBALS['vc_params']['margin_right']
                ),
                'js_view' => 'VcTrxTextView'
            ) );


        }
    }
}
?>