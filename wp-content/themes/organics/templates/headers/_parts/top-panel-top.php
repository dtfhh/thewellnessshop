 <?php if (organics_get_custom_option('show_top_panel_top')=='yes') {

if (in_array('contact_info', $top_panel_top_components) && ($contact_info=trim(organics_get_custom_option('contact_info')))!='') {
    ?>
    <div class="top_panel_top_contact_area">
        <?php organics_show_layout($contact_info); ?>
    </div>
<?php
}

if (in_array('open_hours', $top_panel_top_components) && ($open_hours=trim(organics_get_custom_option('contact_open_hours')))!='') {
    ?>
    <div class="top_panel_top_open_hours icon-clock"><?php organics_show_layout($open_hours); ?></div>
<?php
}
?>

<div class="top_panel_top_user_area">
    <?php

    if (in_array('search', $top_panel_top_components) && organics_get_custom_option('show_search')=='yes') {
        ?>
        <div class="top_panel_top_search"><?php if(function_exists('organics_sc_search')) echo organics_sc_search(array('state'=>'closed')); ?></div>
    <?php
    }
	
		$menu_user = organics_get_nav_menu('menu_user');
		if (empty($menu_user)) {
			?>
			<ul id="menu_user" class="menu_user_nav">
			<?php
		} else {
			$menu = organics_substr($menu_user, 0, organics_strlen($menu_user)-5);
			$pos = organics_strpos($menu, '<ul');
			if ($pos!==false) $menu = organics_substr($menu, 0, $pos+3) . ' class="menu_user_nav"' . organics_substr($menu, $pos+3);
			echo str_replace('class=""', '', $menu);
		}

        if (in_array('currency', $top_panel_top_components) && function_exists('organics_is_woocommerce_page') && organics_is_woocommerce_page() && organics_get_custom_option('show_currency')=='yes') {
            ?>
            <li class="menu_user_currency">
                <a href="#">$</a>
                <ul>
				<li><a href="#"><b>&#36;</b> <?php esc_html_e('Dollar', 'organics'); ?></a></li>
				<li><a href="#"><b>&euro;</b> <?php esc_html_e('Euro', 'organics'); ?></a></li>
				<li><a href="#"><b>&pound;</b> <?php esc_html_e('Pounds', 'organics'); ?></a></li>
                </ul>
            </li>
        <?php
        }

        if (in_array('language', $top_panel_top_components) && organics_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
            $languages = icl_get_languages('skip_missing=1');
            if (!empty($languages) && is_array($languages)) {
                $lang_list = '';
                $lang_active = '';
                foreach ($languages as $lang) {
                    $lang_title = esc_attr($lang['translated_name']);
                    if ($lang['active']) {
                        $lang_active = $lang_title;
                    }
                    $lang_list .= "\n"
                        .'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
                        .'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
                        . ($lang_title)
                        .'</a></li>';
                }
                ?>
                <li class="menu_user_language">
				<a href="#"><span><?php organics_show_layout($lang_active); ?></span></a>
				<ul><?php organics_show_layout($lang_list); ?></ul>
                </li>
            <?php
            }
        }

        if (in_array('bookmarks', $top_panel_top_components) && organics_get_custom_option('show_bookmarks')=='yes') {
            // Load core messages
            organics_enqueue_messages();
            ?>
		<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php esc_attr_e('Show bookmarks', 'organics'); ?>"><?php esc_html_e('Bookmarks', 'organics'); ?></a>
                <?php
                $list = organics_get_value_gpc('organics_bookmarks', '');
                if (!empty($list)) $list = json_decode($list, true);
                ?>
                <ul class="bookmarks_list">
				<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php esc_attr_e('Add the current page into bookmarks', 'organics'); ?>"><?php esc_html_e('Add bookmark', 'organics'); ?></a></li>
                    <?php
                    if (!empty($list) && is_array($list)) {
                        foreach ($list as $bm) {
						echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.esc_attr__('Delete this bookmark', 'organics').'"></span></a></li>';
                        }
                    }
                    ?>
                </ul>
            </li>
        <?php
        }

        if (in_array('login', $top_panel_top_components) && organics_get_custom_option('show_login')=='yes') {
            if ( !is_user_logged_in() ) {
                // Load core messages
                organics_enqueue_messages();
                // Load Popup engine
                organics_enqueue_popup();
                // Anyone can register ?
                if ( (int) get_option('users_can_register') > 0) {
                    ?><li class="menu_user_register"><?php do_action('trx_utils_action_register'); ?></li><?php
                }
                ?><li class="menu_user_login"><?php do_action('trx_utils_action_login'); ?></li><?php
            } else {
                $current_user = wp_get_current_user();
                ?>
                <li class="menu_user_controls">
                    <a href="#"><?php
                        $user_avatar = '';
                        if ($current_user->user_email) $user_avatar = get_avatar($current_user->user_email, 16*min(2, max(1, organics_get_theme_option("retina_ready"))));
                        if ($user_avatar) {
						?><span class="user_avatar"><?php organics_show_layout($user_avatar); ?></span><?php
					}?><span class="user_name"><?php organics_show_layout($current_user->display_name); ?></span></a>
                    <ul>
                        <?php if (current_user_can('publish_posts')) { ?>
					<li><a href="<?php echo esc_url(home_url('/')); ?>/wp-admin/post-new.php?post_type=post" class="icon icon-doc"><?php esc_html_e('New post', 'organics'); ?></a></li>
                        <?php } ?>
					<li><a href="<?php echo esc_url(get_edit_user_link()); ?>" class="icon icon-cog"><?php esc_html_e('Settings', 'organics'); ?></a></li>
                    </ul>
                </li>
			<li class="menu_user_logout"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="icon icon-logout"><?php esc_html_e('Logout', 'organics'); ?></a></li>
            <?php
            }
        }

        if (in_array('socials', $top_panel_top_components) && organics_get_custom_option('show_socials')=='yes') {
            ?>
            <div class="top_panel_top_socials">
                <?php echo organics_do_shortcode('[trx_socials size="tiny"][/trx_socials]'); ?>
            </div>
        <?php
        }

        if (in_array('cart', $top_panel_top_components) && function_exists('organics_exists_woocommerce') && organics_exists_woocommerce() && (organics_is_woocommerce_page() && organics_get_custom_option('show_cart')=='shop' || organics_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
            ?>
            <li class="menu_user_cart">
                <?php require organics_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
            </li>
        <?php
        }
        ?>

    </ul>

</div>
	
<?php } ?>