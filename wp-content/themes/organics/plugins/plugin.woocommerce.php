<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('organics_woocommerce_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_woocommerce_theme_setup', 1 );
	function organics_woocommerce_theme_setup() {

		if (organics_exists_woocommerce()) {

			add_theme_support( 'woocommerce' );
			// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
			add_theme_support( 'wc-product-gallery-zoom' );
			// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
			add_theme_support( 'wc-product-gallery-slider' );
			// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
			add_theme_support( 'wc-product-gallery-lightbox' );

			add_action('organics_action_add_styles', 				'organics_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('organics_filter_get_blog_type',				'organics_woocommerce_get_blog_type', 9, 2);
			add_filter('organics_filter_get_blog_title',			'organics_woocommerce_get_blog_title', 9, 2);
			add_filter('organics_filter_get_current_taxonomy',		'organics_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('organics_filter_is_taxonomy',				'organics_woocommerce_is_taxonomy', 9, 2);
			add_filter('organics_filter_get_stream_page_title',		'organics_woocommerce_get_stream_page_title', 9, 2);
			add_filter('organics_filter_get_stream_page_link',		'organics_woocommerce_get_stream_page_link', 9, 2);
			add_filter('organics_filter_get_stream_page_id',		'organics_woocommerce_get_stream_page_id', 9, 2);
			add_filter('organics_filter_detect_inheritance_key',	'organics_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('organics_filter_detect_template_page_id',	'organics_woocommerce_detect_template_page_id', 9, 2);
			add_filter('organics_filter_orderby_need',				'organics_woocommerce_orderby_need', 9, 2);

			add_filter('organics_filter_list_post_types', 			'organics_woocommerce_list_post_types', 10, 1);
			add_filter('organics_filter_list_post_types', 			'organics_woocommerce_list_post_types');

		}

		if (is_admin()) {
			add_filter( 'organics_filter_required_plugins',					'organics_woocommerce_required_plugins' );
		}
	}
}

if ( !function_exists( 'organics_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_woocommerce_settings_theme_setup2', 3 );
	function organics_woocommerce_settings_theme_setup2() {
		if (organics_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			organics_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => '',
				'single_template' => '',
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'page'
				) )
			);

			// Add WooCommerce specific options in the Theme Options
			global $ORGANICS_GLOBALS;

			organics_array_insert_before($ORGANICS_GLOBALS['options'], 'partition_service', array(

				"partition_woocommerce" => array(
					"title" => esc_html__('WooCommerce', 'organics'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => esc_html__('WooCommerce products list parameters', 'organics'),
					"desc" => esc_html__("Select WooCommerce products list's style and crop parameters", 'organics'),
					"type" => "info"),

				"shop_mode" => array(
					"title" => esc_html__('Shop list style',  'organics'),
					"desc" => esc_html__("WooCommerce products list's style: thumbs or list with description", 'organics'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => esc_html__('Thumbs', 'organics'),
						'list' => esc_html__('List', 'organics')
					),
					"type" => "checklist"),

				"show_mode_buttons" => array(
					"title" => esc_html__('Show style buttons',  'organics'),
					"desc" => esc_html__("Show buttons to allow visitors change list style", 'organics'),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => esc_html__('Shop columns',  'organics'),
					"desc" => esc_html__("How many columns used to show products on shop page", 'organics'),
					"override" => "category,post,page",
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => esc_html__('Show currency selector', 'organics'),
					"desc" => esc_html__('Show currency selector in the user menu', 'organics'),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

				"show_cart" => array(
					"title" => esc_html__('Show cart button', 'organics'),
					"desc" => esc_html__('Show cart button in the user menu', 'organics'),
					"std" => "shop",
					"options" => array(
						'hide'   => esc_html__('Hide', 'organics'),
						'always' => esc_html__('Always', 'organics'),
						'shop'   => esc_html__('Only on shop pages', 'organics')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => esc_html__("Crop product's thumbnail",  'organics'),
					"desc" => esc_html__("Crop product's thumbnails on search results page or scale it", 'organics'),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch")

				)
			);

		}
	}
}



// Add Product categories
if ( !function_exists( 'organics_woocommerce_product_cats' ) ) {
	function organics_woocommerce_product_cats()
	{
		global $post;
		$post_id = $post->ID;
		$post_cats = wp_get_post_terms($post_id, 'product_cat');
		$cats_out = '';
		$i = 0;
		if (!empty($post_cats)) {
			$count_cats = count($post_cats);
			foreach ($post_cats as $term) {
				$i++;
				$term_link = get_term_link($term, 'product_cat');
				$cats_out .= !empty($term_link) ?  '<a href="' . esc_url($term_link) . '">' . esc_html($term->name) . '</a>' : '';
				$cats_out .= $i < $count_cats ? ', ' : '';
				$cats_out .= count($post_cats) > 1 && $i < count($post_cats) ? '' : '';

			}
		}
		echo '<div class="product_cats">';
		echo(!empty($cats_out) ? $cats_out : '');
		echo '</div>';
	}
}
add_action('woocommerce_after_shop_loop_item_title', 'organics_woocommerce_product_cats', 1);



/* Add Quick View Button and Add to cart Button */
if ( !function_exists( 'organics_woocommerce_product_view' ) ) {
	function organics_woocommerce_product_view() {?>
	<div class="woo_thumb_buttons">
		<div class="quick_view_images">

	<?php

		global $post, $woocommerce, $product;

		/* Add Quick View Button  */
		if ( has_post_thumbnail() ) {

			$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
			$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
			$image	   	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	=> $image_title,
				'alt'	=> $image_title
			) );

			$attachment_count = count( $product->get_gallery_image_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			}

			echo apply_filters( 'woocommerce_single_product_image_html',
				sprintf( '<a href="%s" rel="prettyPhoto" itemprop="image" class="woocommerce-main-image zoom sc_button quick_view_button icon-resize-full-1" title="%s">'
					. esc_html(__("Quick View", "organics")).'</a>', $image_link, $image_caption, $image ), $post->ID );

		}
	?>
		</div>
	<?php

		/*  Add to cart Button  */
	?><div class="shortcode_add_to_button"><?php

	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		'<a rel="nofollow" href="' . esc_url($product->add_to_cart_url()) . '" 
			aria-hidden="true" 
			data-quantity="1" 
			data-product_id="' . esc_attr( $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id() ) . '"
			data-product_sku="' . esc_attr( $product->get_sku() ) . '"
			class="shop_cart icon-cart-2 button add_to_cart_button'
					. ' product_type_' . $product->get_type()
					. ($product->supports( 'ajax_add_to_cart' ) ? ' ajax_add_to_cart' : '')
					. '">'
			. esc_html( $product->add_to_cart_text() )
		. '</a>',
		$product );
		?></div>
	</div>

<?php
	}
}
add_action('woocommerce_before_shop_loop_item_title', 'organics_woocommerce_product_view', 10);


/* Remove Add to cart Button */
function remove_loop_button(){
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}
add_action('init','remove_loop_button');


// WooCommerce hooks
if (!function_exists('organics_woocommerce_theme_setup3')) {
	add_action( 'organics_action_after_init_theme', 'organics_woocommerce_theme_setup3' );
	function organics_woocommerce_theme_setup3() {

		if (organics_exists_woocommerce()) {

			add_action(	'woocommerce_before_subcategory_title',		'organics_woocommerce_open_thumb_wrapper', 9 );
			add_action(	'woocommerce_before_shop_loop_item_title',	'organics_woocommerce_open_thumb_wrapper', 9 );

			add_action(	'woocommerce_before_subcategory_title',		'organics_woocommerce_open_item_wrapper', 20 );
			add_action(	'woocommerce_before_shop_loop_item_title',	'organics_woocommerce_open_item_wrapper', 20 );

			add_action(	'woocommerce_after_subcategory',				'organics_woocommerce_close_item_wrapper', 20 );
			add_action(	'woocommerce_after_shop_loop_item',			'organics_woocommerce_close_item_wrapper', 20 );

			add_action(	'woocommerce_after_shop_loop_item_title',	'organics_woocommerce_after_shop_loop_item_title', 7);

			add_action(	'woocommerce_after_subcategory_title',		'organics_woocommerce_after_subcategory_title', 10 );

			add_action(	'the_title',									'organics_woocommerce_the_title');

			// Wrap category title into link
			remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
			add_action(	'woocommerce_shop_loop_subcategory_title',  'organics_woocommerce_shop_loop_subcategory_title', 9, 1);

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);
			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
            // Replace product item title tag from h2 to h3
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			add_action( 'woocommerce_shop_loop_item_title', 'organics_woocommerce_template_loop_product_title', 10 );
		}

		if (organics_is_woocommerce_page()) {

			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar

			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(	'woocommerce_before_main_content',			'organics_woocommerce_wrapper_start', 10);

			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);
			add_action(	'woocommerce_after_main_content',			'organics_woocommerce_wrapper_end', 10);

			add_action(	'woocommerce_show_page_title',				'organics_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);
			add_action(	'woocommerce_single_product_summary',		'organics_woocommerce_show_product_title', 5 );

            remove_action(  'woocommerce_single_product_summary',       'woocommerce_template_single_excerpt', 20);
            add_action(    'woocommerce_single_product_summary',		'organics_template_single_excerpt', 20 );

			add_action(	'woocommerce_before_shop_loop', 				'organics_woocommerce_before_shop_loop', 10 );

			if(get_option( 'woocommerce_shop_page_display' ) != "subcategories") {
				remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
				add_action(	'woocommerce_after_shop_loop',				'organics_woocommerce_pagination', 10 );
			}

			add_action(	'woocommerce_product_meta_end',				'organics_woocommerce_show_product_id', 10);

			if (organics_param_is_on(organics_get_custom_option('show_post_related'))) {
				add_filter('woocommerce_output_related_products_args', 'organics_woocommerce_output_related_products_args');
				add_filter('woocommerce_related_products_args', 'organics_woocommerce_related_products_args');

				// remove the upsells action using the same priority as original add_action()
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
				// add my custom upsells function in the same place
				add_action( 'woocommerce_after_single_product_summary', 'organics_upsell_display', 15 );

			} else {
				remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
			}

			add_filter(	'woocommerce_product_thumbnails_columns',	'organics_woocommerce_product_thumbnails_columns' );

			add_filter(	'get_product_search_form',					'organics_woocommerce_get_product_search_form' );

            add_filter( 'woocommerce_price_filter_widget_step', 'organics_woocommerce_price_filter_widget_step');

			add_filter(	'post_class',								'organics_woocommerce_loop_shop_columns_class' );
			add_filter(	'product_cat_class',							'organics_woocommerce_loop_shop_columns_class', 10, 3 );

			organics_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'organics_exists_woocommerce' ) ) {
	function organics_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'organics_is_woocommerce_page' ) ) {
	function organics_is_woocommerce_page() {
		$rez = false;
		if (organics_exists_woocommerce()) {
			global $ORGANICS_GLOBALS;
			if (!empty($ORGANICS_GLOBALS['pre_query'])) {
				$id = isset($ORGANICS_GLOBALS['pre_query']->queried_object_id) ? $ORGANICS_GLOBALS['pre_query']->queried_object_id : 0;
				$rez = $ORGANICS_GLOBALS['pre_query']->get('post_type')=='product'
						|| $id==wc_get_page_id('shop')
						|| $id==wc_get_page_id('cart')
						|| $id==wc_get_page_id('checkout')
						|| $id==wc_get_page_id('myaccount')
						|| $ORGANICS_GLOBALS['pre_query']->is_tax( 'product_cat' )
						|| $ORGANICS_GLOBALS['pre_query']->is_tax( 'product_tag' )
						|| $ORGANICS_GLOBALS['pre_query']->is_tax( get_object_taxonomies( 'product' ) );

			} else
				$rez = is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'organics_woocommerce_detect_inheritance_key' ) ) {
	function organics_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return organics_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'organics_woocommerce_detect_template_page_id' ) ) {
	function organics_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'organics_woocommerce_get_blog_type' ) ) {
	function organics_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;

		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'organics_woocommerce_get_blog_title' ) ) {
	function organics_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;

		if ( organics_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = esc_html__('Tag:', 'organics') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = esc_html__( 'Your cart', 'organics' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = esc_html__( 'Checkout', 'organics' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = esc_html__( 'Account', 'organics' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = organics_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = organics_get_post_title($page_id);
			} else {
				$title = esc_html__( 'Shop', 'organics' );
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'organics_woocommerce_get_stream_page_title' ) ) {
	function organics_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (organics_strpos($page, 'woocommerce')!==false) {
			if (($page_id = organics_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = organics_get_post_title($page_id);
			else
				$title = esc_html__('Shop', 'organics');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'organics_woocommerce_get_stream_page_id' ) ) {
	function organics_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (organics_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'organics_woocommerce_get_stream_page_link' ) ) {
	function organics_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (organics_strpos($page, 'woocommerce')!==false) {
			$id = organics_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'organics_woocommerce_get_current_taxonomy' ) ) {
	function organics_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( organics_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'organics_woocommerce_is_taxonomy' ) ) {
	function organics_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else
			return $query && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'organics_woocommerce_orderby_need' ) ) {
	function organics_woocommerce_orderby_need($need) {
		if ($need == false || empty(organics_storage_get('pre_query')))
			return $need;
		else {
			return organics_storage_call_obj_method('pre_query', 'get', 'post_type')!='product'
			       && organics_storage_call_obj_method('pre_query', 'get', 'product_cat')==''
			       && organics_storage_call_obj_method('pre_query', 'get', 'product_tag')=='';
		}
	}
}

// Add custom post type into list
if ( !function_exists( 'organics_woocommerce_list_post_types' ) ) {
	function organics_woocommerce_list_post_types($list) {
		$list = is_array($list) ? $list : array();
		$list['product'] = esc_html__('Products', 'organics');
		return $list;
	}
}



// Enqueue WooCommerce custom styles
if ( !function_exists( 'organics_woocommerce_frontend_scripts' ) ) {
	function organics_woocommerce_frontend_scripts() {
			wp_enqueue_style( 'organics-woo-style',  organics_get_file_url('css/woo-style.css'), array(), null );
	}
}

// Before main content
if ( !function_exists( 'organics_woocommerce_wrapper_start' ) ) {
	function organics_woocommerce_wrapper_start() {
		global $ORGANICS_GLOBALS;
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !empty($ORGANICS_GLOBALS['shop_mode']) ? $ORGANICS_GLOBALS['shop_mode'] : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'organics_woocommerce_wrapper_end' ) ) {
	function organics_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'organics_woocommerce_show_page_title' ) ) {
	function organics_woocommerce_show_page_title($defa=true) {
		return organics_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'organics_woocommerce_show_product_title' ) ) {
	function organics_woocommerce_show_product_title() {
		if (organics_get_custom_option('show_post_title')=='yes' || organics_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// New product excerpt with video shortcode
if ( !function_exists( 'organics_template_single_excerpt' ) ) {
	function organics_template_single_excerpt() {
		if ( ! defined( 'ABSPATH' ) ) {
			exit; // Exit if accessed directly
		}
		global $post;
		if ( ! $post->post_excerpt ) {
			return;
		}
		?>
		<div itemprop="description">
			<?php echo organics_substitute_all(apply_filters( 'woocommerce_short_description', $post->post_excerpt )); ?>
		</div>
	<?php
	}
}

// Add list mode buttons
if ( !function_exists( 'organics_woocommerce_before_shop_loop' ) ) {
	function organics_woocommerce_before_shop_loop() {
		global $ORGANICS_GLOBALS;
		if (organics_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url((home_url()) . (add_query_arg(array()))).'" method="post">'
				. '<input type="hidden" name="organics_shop_mode" value="'.esc_attr($ORGANICS_GLOBALS['shop_mode']).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr__('Show products as thumbs', 'organics').'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr__('Show products as list', 'organics').'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'organics_woocommerce_open_thumb_wrapper' ) ) {
	function organics_woocommerce_open_thumb_wrapper($cat='') {
		organics_set_global('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'organics_woocommerce_open_item_wrapper' ) ) {
	function organics_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'organics_woocommerce_close_item_wrapper' ) ) {
	function organics_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		organics_set_global('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'organics_woocommerce_after_shop_loop_item_title' ) ) {
	function organics_woocommerce_after_shop_loop_item_title() {
		global $ORGANICS_GLOBALS;
		if ($ORGANICS_GLOBALS['shop_mode'] == 'list') {
			$excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'organics_woocommerce_after_subcategory_title' ) ) {
	function organics_woocommerce_after_subcategory_title($category) {
		global $ORGANICS_GLOBALS;
		if ($ORGANICS_GLOBALS['shop_mode'] == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'organics_woocommerce_show_product_id' ) ) {
	function organics_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'organics') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'organics_woocommerce_output_related_products_args' ) ) {
	function organics_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (organics_param_is_on(organics_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(organics_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  organics_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (organics_param_is_off(organics_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = organics_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

function organics_upsell_display() {
	$ccc = organics_get_custom_option('post_related_columns');
	isset ($ccc) ? $ccc : 4;
	woocommerce_upsell_display( -1, $ccc, 'rand', 'desc' );
}

// Redefine post_type if number of related products == 0
if ( !function_exists( 'organics_woocommerce_related_products_args' ) ) {
	function organics_woocommerce_related_products_args($args) {
		if ($args['posts_per_page'] == 0)
			$args['post_type'] .= '_';
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'organics_woocommerce_product_thumbnails_columns' ) ) {
	function organics_woocommerce_product_thumbnails_columns($cols) {
		return 5;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'organics_woocommerce_loop_shop_columns_class' ) ) {
	function organics_woocommerce_loop_shop_columns_class($class, $class2='', $cat='') {
		if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
			$cols = function_exists('wc_get_default_products_per_row') ? wc_get_default_products_per_row() : 2;
			$class[] = ' column-1_' . $cols;
		}
		return $class;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'organics_woocommerce_price_filter_widget_step' ) ) {
    function organics_woocommerce_price_filter_widget_step($step) {
        return 1;
    }
}


// Search form
if ( !function_exists( 'organics_woocommerce_get_product_search_form' ) ) {
	function organics_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url( home_url( '/'  ) ) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'organics') . '" value="' . get_search_query() . '" name="s" title="' . esc_attr__('Search for products:', 'organics') . '" /><button class="search_button icon-search" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'organics_woocommerce_the_title' ) ) {
	function organics_woocommerce_the_title($title) {
		if (organics_get_global('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}

// Wrap category title into link
if ( !function_exists( 'organics_woocommerce_shop_loop_subcategory_title' ) ) {
	function organics_woocommerce_shop_loop_subcategory_title($cat) {
		$cat->name = sprintf('<a href="%s">%s</a>', esc_url(get_term_link($cat->slug, 'product_cat')), $cat->name);
		?>
		<h2 class="woocommerce-loop-category__title">
		<?php
        organics_show_layout($cat->name);

		if ( $cat->count > 0 ) {
			echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $cat->count ) . ')</mark>', $cat );
		}
		?>
		</h2><?php
	}
}

// Wrap product title into link
if ( !function_exists( 'organics_woocommerce_template_loop_product_title' ) ) {
	function organics_woocommerce_template_loop_product_title() {
		echo '<h2 class="woocommerce-loop-product__title"><a href="'.get_permalink().'">' . get_the_title() . '</a></h3>';
	}
}

// Show pagination links
if ( !function_exists( 'organics_woocommerce_pagination' ) ) {
	function organics_woocommerce_pagination() {
		$style = organics_get_custom_option('blog_pagination');
		organics_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr($style),
			'style' => $style,
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => $style=='pages' ? 10 : 20
			)
		);
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'organics_woocommerce_required_plugins' ) ) {
	function organics_woocommerce_required_plugins($list=array()) {
		if (in_array('woocommerce', (array)organics_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}

// Show products navigation
if ( !function_exists( 'organics_woocommerce_show_post_navi' ) ) {
	function organics_woocommerce_show_post_navi($show=false) {
		return $show || (organics_get_custom_option('show_page_title')=='yes' && is_single() && organics_is_woocommerce_page());
	}
}


?>