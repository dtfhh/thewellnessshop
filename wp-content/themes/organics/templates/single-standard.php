<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_single_standard_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_single_standard_theme_setup', 1 );
	function organics_template_single_standard_theme_setup() {
		organics_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single standard', 'organics'),
			'thumb_title'  => esc_html__('Fullwidth image', 'organics'),
			'w'		 => 1170,
			'h'		 => 660
		));
	}
}

// Template output
if ( !function_exists( 'organics_template_single_standard_output' ) ) {
	function organics_template_single_standard_output($post_options, $post_data) {
		// To prevent double calculation, remove increment $post_data['post_views']++

		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && organics_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = organics_get_custom_option('show_post_title')=='yes' && (organics_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));
		$title_tag = organics_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		organics_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.(!empty($avg_author) || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		if ($show_title && $post_options['location'] == 'center' && organics_get_custom_option('show_page_title')=='no') {
			?>
            <<?php echo esc_html($title_tag); ?> itemprop="<?php organics_show_layout(!empty($avg_author) || $avg_users > 0 ? 'itemReviewed' : 'headline'); ?>" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php organics_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
		<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(organics_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				organics_show_layout($post_options['dedicated']);
			} else {
				organics_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php organics_show_layout($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
			
		
		if ($show_title && $post_options['location'] != 'center' && organics_get_custom_option('show_page_title')=='no') {
			?>
            <<?php echo esc_html($title_tag); ?> itemprop="<?php organics_show_layout(!empty($avg_author) || $avg_users > 0 ? 'itemReviewed' : 'headline'); ?>" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php organics_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && organics_get_custom_option('show_post_info')=='yes') {
			$info_parts = array('snippets'=>true);
			require organics_get_file_dir('templates/_parts/post-info.php');
		}
		
		require organics_get_file_dir('templates/_parts/reviews-block.php');
			
		organics_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="'.(!empty($avg_author) || !empty($avg_users) ? 'reviewBody' : 'articleBody').'">');
			
		// Post content
		if ($post_data['post_protected']) { 
			organics_show_layout($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			global $ORGANICS_GLOBALS;
			if (organics_strpos($post_data['post_content'], organics_get_reviews_placeholder())===false && function_exists('organics_sc_reviews')) $post_data['post_content'] = organics_sc_reviews(array()) . ($post_data['post_content']);
			organics_show_layout(organics_gap_wrapper(organics_reviews_wrapper($post_data['post_content'])));
			require organics_get_file_dir('templates/_parts/single-pagination.php');
			if ( organics_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags"><?php esc_html_e('Tags:', 'organics'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php 
			}
		} 
		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			require organics_get_file_dir('templates/_parts/editor-area.php');
		}
			
		organics_close_wrapper();
			
		if (!$post_data['post_protected']) {
			require organics_get_file_dir('templates/_parts/author-info.php');
			require organics_get_file_dir('templates/_parts/share.php');
		}

		$sidebar_present = !organics_param_is_off(organics_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) organics_close_wrapper();
		require organics_get_file_dir('templates/_parts/related-posts.php');
		if ($sidebar_present) organics_close_wrapper();

		if (!$post_data['post_protected']) {
			require organics_get_file_dir('templates/_parts/comments.php');
		}

	}
}
?>