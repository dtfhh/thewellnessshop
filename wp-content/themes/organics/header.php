<?php
/**
 * The Header for our theme.
 */

// Theme init - don't remove next row! Load custom options
organics_core_init_theme();
$theme_skin = sanitize_file_name(organics_get_custom_option('theme_skin'));
$body_scheme = organics_get_custom_option('body_scheme');
if (empty($body_scheme)  || organics_is_inherit_option($body_scheme)) $body_scheme = 'original';
$blog_style = organics_get_custom_option(is_singular() && !organics_get_global('blog_streampage') ? 'single_style' : 'blog_style');
$body_style  = organics_get_custom_option('body_style');
$article_style = organics_get_custom_option('article_style');
$top_panel_style = organics_get_custom_option('top_panel_style');
$top_panel_position = organics_get_custom_option('top_panel_position');
$top_panel_scheme = organics_get_custom_option('top_panel_scheme');
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr('scheme_'.$body_scheme); ?>">
<head>
    <?php
	wp_head();
	?>
</head>


<body <?php body_class();?>>
	
	<?php organics_show_layout(organics_get_custom_option('gtm_code')); ?>

	<?php do_action( 'before' ); ?>

	<?php
	// Add TOC items 'Home' and "To top"
	if (organics_get_custom_option('menu_toc_home')=='yes' && function_exists('organics_sc_anchor'))
        organics_show_layout(organics_sc_anchor(array(
			'id' => "toc_home",
			'title' => esc_html__('Home', 'organics'),
			'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'organics'),
			'icon' => "icon-home",
			'separator' => "yes",
			'url' => home_url())
			)); 
	if (organics_get_custom_option('menu_toc_top')=='yes' && function_exists('organics_sc_anchor'))
        organics_show_layout(organics_sc_anchor(array(
			'id' => "toc_top",
			'title' => esc_html__('To Top', 'organics'),
			'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'organics'),
			'icon' => "icon-double-up",
			'separator' => "yes")
			)); 
	?>

	<?php if ( !organics_param_is_off(organics_get_custom_option('show_sidebar_outer')) ) { ?>
	<div class="outer_wrap">
	<?php } ?>

	<?php
	
		$body_style  = organics_get_custom_option('body_style');
		$class = $style = '';
		if (organics_get_custom_option('bg_custom')=='yes' && ($body_style=='boxed' || organics_get_custom_option('bg_image_load')=='always')) {
				if (($img = organics_get_custom_option('bg_image_custom')) != '')
				$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', organics_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
				else if (($img = organics_get_custom_option('bg_pattern_custom')) != '')
					$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
				else if (($img = organics_get_custom_option('bg_image')) > 0)
					$class = 'bg_image_'.($img);
				else if (($img = organics_get_custom_option('bg_pattern')) > 0)
					$class = 'bg_pattern_'.($img);
				if (($img = organics_get_custom_option('bg_color')) != '')
					$style .= 'background-color: '.($img).';';
			}
	?>

	<div class="body_wrap<?php if (!empty($class)) { echo  ' ' . esc_attr($class); } ?>"<?php if (!empty($style)) { echo ' style="'.esc_attr($style).'"'; }; ?>>

		<?php
		// Video bg
		require_once organics_get_file_dir('templates/headers/_parts/video_bg.php');
		?>

		<div class="page_wrap">

			<?php
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				organics_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}
			// Mobile Menu
			require_once organics_get_file_dir('templates/headers/_parts/header-mobile.php');
			// Slider
			require_once organics_get_file_dir('templates/headers/_parts/slider.php');
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				organics_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}

			// Top of page section: page title and breadcrumbs
			require_once organics_get_file_dir('templates/headers/_parts/breadcrumbs.php');
			?>

			<div class="page_content_wrap page_paddings_<?php echo esc_attr(organics_get_custom_option('body_paddings')); ?>">

				<?php
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') organics_open_wrapper('<div class="content_wrap">');
				
				// Main content wrapper
				organics_open_wrapper('<div class="content">');
				?>