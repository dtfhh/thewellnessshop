<?php
	$all_options = organics_get_global('header_mobile');
	$top_panel_style = organics_get_custom_option('top_panel_style');
	$header_options = $all_options[$top_panel_style];

	$contact_address_1=trim(organics_get_custom_option('contact_address_1'));
	$contact_address_2=trim(organics_get_custom_option('contact_address_2'));
	$contact_phone=trim(organics_get_custom_option('contact_phone'));
	$contact_email=trim(organics_get_custom_option('contact_email'));
?>
	<div class="header_mobile">
		<div class="content_wrap">
			<?php
            include organics_get_file_dir('templates/headers/_parts/logo.php');
            ?>
            <div class="menu_button icon-menu"></div>
            <?php
            if (function_exists('organics_exists_woocommerce') && organics_exists_woocommerce() && (organics_is_woocommerce_page() && organics_get_custom_option('show_cart')=='shop' || organics_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
                ?>
                <div class="menu_main_cart top_panel_icon">
                    <?php include organics_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
                </div>
            <?php
            }
			?>
		</div>

		<div class="side_wrap">
			<div class="close"><?php esc_html_e('Close', 'organics'); ?></div>


			<div class="panel_top">
                <nav class="menu_main_nav_area">
                    <?php
                    $menu_main = organics_get_nav_menu('menu_main');
                    if (empty($menu_main)) $menu_main = organics_get_nav_menu();
                    organics_show_layout($menu_main);
                    ?>
                </nav>
				<?php if(function_exists('organics_sc_search')) organics_show_layout(organics_sc_search(array()));

                if (organics_get_theme_option('show_login')=='yes') {
                    if ( is_user_logged_in() ) {
                        $current_user = wp_get_current_user();
                        ?>
                        <div class="login"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="popup_link"><?php esc_html_e('Logout', 'organics'); ?></a></div>
                        <?php
                    } else {
                        // Load core messages
                        organics_enqueue_messages();
                        // Load Popup engine
                        organics_enqueue_popup();
                        ?><div class="login"><?php do_action('trx_utils_action_login', '', '', array( 'link' )); ?></div><?php
                        // Anyone can register ?
                        if ( (int) get_option('users_can_register') > 0) {
                            ?><div class="login"><?php do_action('trx_utils_action_register', '', '', array( 'link' )); ?></div><?php
                        }
                    }
                }
                ?>
			</div>


			<div class="panel_middle">
                <?php
                if (!empty($contact_address_1) || !empty($contact_address_2)) {
                    ?><div class="contact_field contact_address">
                    <span class="contact_icon icon-home"></span>
                    <span class="contact_label contact_address_1"><?php organics_show_layout($contact_address_1); ?></span>
                    <span class="contact_address_2"><?php organics_show_layout($contact_address_2); ?></span>
                    </div><?php
                }

                if (!empty($contact_phone) || !empty($contact_email)) {
                    ?><div class="contact_field contact_phone">
                    <span class="contact_icon icon-phone"></span>
                    <span class="contact_label contact_phone"><?php organics_show_layout($contact_phone); ?></span>
                    <span class="contact_email"><?php organics_show_layout($contact_email); ?></span>
                    </div><?php
                }

                ?>
			</div>


			<div class="panel_bottom">
                <?php
                if (organics_get_custom_option('show_socials')=='yes') {
                    ?>
                    <div class="contact_socials">
                        <?php if(function_exists('organics_sc_socials')) organics_show_layout(organics_sc_socials(array('size'=>'tiny'))); ?>
                    </div>
                <?php
                }
                ?>
			</div>


		</div>
        <?php
        // Load core messages
        organics_enqueue_messages();
        // Load Popup engine
        organics_enqueue_popup();

		?><div class="login login_popup_only"><?php do_action('trx_utils_action_login', '', '', array( 'popup' )); ?></div><?php
		// Anyone can register ?
		if ( (int) get_option('users_can_register') > 0) {
			?><div class="login login_popup_only"><?php do_action('trx_utils_action_register', '', '', array( 'popup' )); ?></div><?php
		}

        ?>
		<div class="mask"></div>
	</div>