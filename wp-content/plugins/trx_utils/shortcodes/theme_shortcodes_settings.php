<?php
/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_shortcodes2_settings_theme_setup' ) ) {
//	if ( organics_vc_is_frontend() )
    if ((isset($_GET['vc_editable']) && $_GET['vc_editable'] == 'true') || (isset($_GET['vc_action']) && $_GET['vc_action'] == 'vc_inline'))
        add_action('organics_action_before_init_theme', 'organics_shortcodes2_settings_theme_setup', 21);
    else
        add_action('organics_action_after_init_theme', 'organics_shortcodes2_settings_theme_setup', 11);
    function organics_shortcodes2_settings_theme_setup()
    {
        if (organics_shortcodes_is_used()) {
            global $ORGANICS_GLOBALS;

            // Shortcodes list
            //------------------------------------------------------------------
            $ORGANICS_GLOBALS['shortcodes']['trx_image'] = array(
                    "title" => __("Image", 'trx_utils'),
                    "desc" => __("Insert image into your post (page)", 'trx_utils'),
                    "decorate" => false,
                    "container" => false,
                    "params" => array(
                        "url" => array(
                            "title" => __("URL for image file", 'trx_utils'),
                            "desc" => __("Select or upload image or write URL from other site", 'trx_utils'),
                            "readonly" => false,
                            "value" => "",
                            "type" => "media",
                            "before" => array(
                                'sizes' => true        // If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
                            )
                        ),
                        "title" => array(
                            "title" => __("Title", 'trx_utils'),
                            "desc" => __("Image title (if need)", 'trx_utils'),
                            "value" => "",
                            "type" => "text"
                        ),
                        "icon" => array(
                            "title" => __("Icon before title", 'trx_utils'),
                            "desc" => __('Select icon for the title from Fontello icons set', 'trx_utils'),
                            "value" => "",
                            "type" => "icons",
                            "options" => $ORGANICS_GLOBALS['sc_params']['icons']
                        ),
                        "align" => array(
                            "title" => __("Float image", 'trx_utils'),
                            "desc" => __("Float image to left or right side", 'trx_utils'),
                            "value" => "",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => $ORGANICS_GLOBALS['sc_params']['float']
                        ),
                        "shape" => array(
                            "title" => __("Image Shape", 'trx_utils'),
                            "desc" => __("Shape of the image: square (rectangle) or round", 'trx_utils'),
                            "value" => "square",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => array(
                                "square" => __('Square', 'trx_utils')
                            )
                        ),
                        "link" => array(
                            "title" => __("Link", 'trx_utils'),
                            "desc" => __("The link URL from the image", 'trx_utils'),
                            "value" => "",
                            "type" => "text"
                        ),
                        "width" => organics_shortcodes_width(),
                        "height" => organics_shortcodes_height(),
                        "top" => $ORGANICS_GLOBALS['sc_params']['top'],
                        "bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
                        "left" => $ORGANICS_GLOBALS['sc_params']['left'],
                        "right" => $ORGANICS_GLOBALS['sc_params']['right'],
                        "id" => $ORGANICS_GLOBALS['sc_params']['id'],
                        "class" => $ORGANICS_GLOBALS['sc_params']['class'],
                        "animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
                        "css" => $ORGANICS_GLOBALS['sc_params']['css']
                    )
            );


            // Button
            $ORGANICS_GLOBALS['shortcodes']['trx_button'] = array(
                "title" => __("Button", 'trx_utils'),
                "desc" => __("Button with link", 'trx_utils'),
                "decorate" => false,
                "container" => true,
                "params" => array(
                    "_content_" => array(
                        "title" => __("Caption", 'trx_utils'),
                        "desc" => __("Button caption", 'trx_utils'),
                        "value" => "",
                        "type" => "text"
                    ),
                    "type" => array(
                        "title" => __("Button's shape", 'trx_utils'),
                        "desc" => __("Select button's shape", 'trx_utils'),
                        "value" => "round",
                        "size" => "medium",
                        "options" => array(
                            'square' => __('Square', 'trx_utils'),
                            'round' => __('Round', 'trx_utils')
                        ),
                        "type" => "switch"
                    ),
                    "style" => array(
                        "title" => __("Button's style", 'trx_utils'),
                        "desc" => __("Select button's style", 'trx_utils'),
                        "value" => "default",
                        "dir" => "horizontal",
                        "options" => array(
                            'filled' => __('Filled', 'trx_utils'),
                            'border' => __('Border', 'trx_utils')
                        ),
                        "type" => "checklist"
                    ),
                    "scheme" => array(
                        "title" => __("Button's color scheme", 'trx_utils'),
                        "desc" => __("Select button's color scheme", 'trx_utils'),
                        "value" => "original",
                        "dir" => "horizontal",
                        "options" => array(
                            'original' => __('Original', 'trx_utils'),
                            'dark' => __('Dark', 'trx_utils'),
                            'orange' => __('Orange', 'trx_utils'),
                            'crimson' => __('Crimson', 'trx_utils')
                        ),
                        "type" => "checklist"
                    ),
                    "size" => array(
                        "title" => __("Button's size", 'trx_utils'),
                        "desc" => __("Select button's size", 'trx_utils'),
                        "value" => "small",
                        "dir" => "horizontal",
                        "options" => array(
                            'small' => __('Small', 'trx_utils'),
                            'medium' => __('Medium', 'trx_utils'),
                            'large' => __('Large', 'trx_utils')
                        ),
                        "type" => "checklist"
                    ),
                    "icon" => array(
                        "title" => __("Button's icon", 'trx_utils'),
                        "desc" => __('Select icon for the title from Fontello icons set', 'trx_utils'),
                        "value" => "",
                        "type" => "icons",
                        "options" => $ORGANICS_GLOBALS['sc_params']['icons']
                    ),
                    "color" => array(
                        "title" => __("Button's text color", 'trx_utils'),
                        "desc" => __("Any color for button's caption", 'trx_utils'),
                        "std" => "",
                        "value" => "",
                        "type" => "color"
                    ),
                    "bg_color" => array(
                        "title" => __("Button's backcolor", 'trx_utils'),
                        "desc" => __("Any color for button's background", 'trx_utils'),
                        "value" => "",
                        "type" => "color"
                    ),
                    "align" => array(
                        "title" => __("Button's alignment", 'trx_utils'),
                        "desc" => __("Align button to left, center or right", 'trx_utils'),
                        "value" => "none",
                        "type" => "checklist",
                        "dir" => "horizontal",
                        "options" => $ORGANICS_GLOBALS['sc_params']['align']
                    ),
                    "link" => array(
                        "title" => __("Link URL", 'trx_utils'),
                        "desc" => __("URL for link on button click", 'trx_utils'),
                        "divider" => true,
                        "value" => "",
                        "type" => "text"
                    ),
                    "target" => array(
                        "title" => __("Link target", 'trx_utils'),
                        "desc" => __("Target for link on button click", 'trx_utils'),
                        "dependency" => array(
                            'link' => array('not_empty')
                        ),
                        "value" => "",
                        "type" => "text"
                    ),
                    "popup" => array(
                        "title" => __("Open link in popup", 'trx_utils'),
                        "desc" => __("Open link target in popup window", 'trx_utils'),
                        "dependency" => array(
                            'link' => array('not_empty')
                        ),
                        "value" => "no",
                        "type" => "switch",
                        "options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
                    ),
                    "rel" => array(
                        "title" => __("Rel attribute", 'trx_utils'),
                        "desc" => __("Rel attribute for button's link (if need)", 'trx_utils'),
                        "dependency" => array(
                            'link' => array('not_empty')
                        ),
                        "value" => "",
                        "type" => "text"
                    ),
                    "width" => organics_shortcodes_width(),
                    "height" => organics_shortcodes_height(),
                    "top" => $ORGANICS_GLOBALS['sc_params']['top'],
                    "bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
                    "left" => $ORGANICS_GLOBALS['sc_params']['left'],
                    "right" => $ORGANICS_GLOBALS['sc_params']['right'],
                    "id" => $ORGANICS_GLOBALS['sc_params']['id'],
                    "class" => $ORGANICS_GLOBALS['sc_params']['class'],
                    "animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
                    "css" => $ORGANICS_GLOBALS['sc_params']['css']
                )
            );


            // Axiomthemes - Recent Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_recent_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Recent Products", 'trx_utils'),
                "desc" => esc_html__("WooCommerce shortcode: show recent products", 'trx_utils'),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", 'trx_utils'),
                        "desc" => esc_html__("How many products showed", 'trx_utils'),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", 'trx_utils'),
                        "desc" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                    "orderby" => array(
                        "title" => esc_html__("Order by", 'trx_utils'),
                        "desc" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "value" => "date",
                        "type" => "select",
                        "options" => array(
                            "date" => __('Date', 'trx_utils'),
                            "title" => esc_html__('Title', 'trx_utils')
                        )
                    ),
                    "order" => array(
                        "title" => esc_html__("Order", 'trx_utils'),
                        "desc" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "value" => "desc",
                        "type" => "switch",
                        "size" => "big",
                        "options" => $ORGANICS_GLOBALS['sc_params']['ordering']
                    )
                )
            );


            // Axiomthemes - Featured Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_featured_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Featured Products", 'trx_utils'),
                "desc" => esc_html__("WooCommerce shortcode: show featured products", 'trx_utils'),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", 'trx_utils'),
                        "desc" => esc_html__("How many products showed", 'trx_utils'),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", 'trx_utils'),
                        "desc" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                    "orderby" => array(
                        "title" => esc_html__("Order by", 'trx_utils'),
                        "desc" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "value" => "date",
                        "type" => "select",
                        "options" => array(
                            "date" => __('Date', 'trx_utils'),
                            "title" => esc_html__('Title', 'trx_utils')
                        )
                    ),
                    "order" => array(
                        "title" => esc_html__("Order", 'trx_utils'),
                        "desc" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "value" => "desc",
                        "type" => "switch",
                        "size" => "big",
                        "options" => $ORGANICS_GLOBALS['sc_params']['ordering']
                    )
                )
            );

            // Axiomthemes - Best Selling Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_best_selling_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Best Selling Products", 'trx_utils'),
                "desc" => esc_html__("WooCommerce shortcode: show best selling products", 'trx_utils'),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", 'trx_utils'),
                        "desc" => esc_html__("How many products showed", 'trx_utils'),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", 'trx_utils'),
                        "desc" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                )
            );

            // Axiomthemes - Sale Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_sale_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Sale Products", 'trx_utils'),
                "desc" => esc_html__("WooCommerce shortcode: show sale products", 'trx_utils'),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", 'trx_utils'),
                        "desc" => esc_html__("How many products showed", 'trx_utils'),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", 'trx_utils'),
                        "desc" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                    "orderby" => array(
                        "title" => esc_html__("Order by", 'trx_utils'),
                        "desc" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "value" => "date",
                        "type" => "select",
                        "options" => array(
                            "date" => __('Date', 'trx_utils'),
                            "title" => esc_html__('Title', 'trx_utils')
                        )
                    ),
                    "order" => array(
                        "title" => esc_html__("Order", 'trx_utils'),
                        "desc" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "value" => "desc",
                        "type" => "switch",
                        "size" => "big",
                        "options" => $ORGANICS_GLOBALS['sc_params']['ordering']
                    )
                )
            );

            // Axiomthemes - Top Rated Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_top_rated_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Top Rated Products", 'trx_utils'),
                "desc" => esc_html__("WooCommerce shortcode: show top rated products", 'trx_utils'),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", 'trx_utils'),
                        "desc" => esc_html__("How many products showed", 'trx_utils'),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", 'trx_utils'),
                        "desc" => esc_html__("How many columns per row use for products output", 'trx_utils'),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                    "orderby" => array(
                        "title" => esc_html__("Order by", 'trx_utils'),
                        "desc" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "value" => "date",
                        "type" => "select",
                        "options" => array(
                            "date" => __('Date', 'trx_utils'),
                            "title" => esc_html__('Title', 'trx_utils')
                        )
                    ),
                    "order" => array(
                        "title" => esc_html__("Order", 'trx_utils'),
                        "desc" => esc_html__("Sorting order for products output", 'trx_utils'),
                        "value" => "desc",
                        "type" => "switch",
                        "size" => "big",
                        "options" => $ORGANICS_GLOBALS['sc_params']['ordering']
                    )
                )
            );
        }
    }
}
?>