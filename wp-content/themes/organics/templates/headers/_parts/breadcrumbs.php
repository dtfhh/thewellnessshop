<?php
// Top of page section: page title and breadcrumbs
$show_title = organics_get_custom_option('show_page_title')=='yes';
$show_breadcrumbs = organics_get_custom_option('show_breadcrumbs')=='yes';
if ( organics_need_page_title() && ( $show_title || $show_breadcrumbs ) ) {
	?>
	<div class="top_panel_title top_panel_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php organics_show_layout(($show_title ? ' title_present' : '') . ($show_breadcrumbs ? ' breadcrumbs_present' : '')); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?>">
		<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php organics_show_layout(($show_title ? ' title_present_inner' : '') . ($show_breadcrumbs ? ' breadcrumbs_present_inner' : '')); ?>">
			<div class="content_wrap">
				<?php if ($show_title) { ?>
					<h1 class="page_title"><?php echo strip_tags(organics_get_blog_title()); ?></h1>
				<?php } ?>
				<?php if ($show_breadcrumbs) { ?>
					<div class="breadcrumbs">
						<?php if (!is_404()) organics_show_breadcrumbs(); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
} else {
	?><div class="top_panel_no_title"></div><?php

}