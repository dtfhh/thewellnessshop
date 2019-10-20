<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_404_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_404_theme_setup', 1 );
	function organics_template_404_theme_setup() {
		organics_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'organics_template_404_output' ) ) {
	function organics_template_404_output() {
        global $ORGANICS_GLOBALS;
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
                <div class="columns_wrap sc_columns columns_nofluid sc_columns_count_12"><div class="column-6_12 sc_column_item sc_column_item_1 odd first span_6">
                        <div class="sc_column_item_inner">
                            <h1 class="page_title"><?php esc_html_e('404', 'organics' ); ?></h1>
                        </div>
                    </div><div class="column-6_12 sc_column_item sc_column_item_7 odd span_6">
                        <div class="sc_column_item_inner">
                        <h2 class="page_subtitle"><?php echo wp_kses( sprintf( __('Sorry! <br/>Page not found', 'organics')), $ORGANICS_GLOBALS['allowed_tags'] ); ?></h2>
                        <p class="page_description"><?php esc_html_e('Please report any broken links to our team.', 'organics'); ?></p>
                        <div class="page_button"><a href="<?php echo esc_url(home_url()); ?>" class="sc_button sc_button_square sc_button_style_border sc_button_scheme_white sc_button_size_small"><?php esc_html_e('GO BACK HOME', 'organics' ) ?></a></div>
                    </div>
                    </div>
                </div>
            <div class="block_with_no_bottom_margin"></div>
            </div>
		</article>
		<?php
	}
}
?>