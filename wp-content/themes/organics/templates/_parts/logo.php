<?php
if ( !function_exists( 'organics_show_logos' ) ) {
	function organics_show_logos() {
		global $ORGANICS_GLOBALS;
		?>
		<div class="logo">
			<a href="<?php echo esc_url(home_url()); ?>"><?php
				echo !empty($ORGANICS_GLOBALS['logo']) 
					? '<img src="'.esc_url($ORGANICS_GLOBALS['logo']).'" class="logo_main" alt="'.esc_attr__('img', 'organics').'">' 
					: ''; 
				organics_show_layout($ORGANICS_GLOBALS['logo_text']
					? '<div class="logo_text">'.($ORGANICS_GLOBALS['logo_text']).'</div>' 
					: '');
				organics_show_layout($ORGANICS_GLOBALS['logo_slogan']
					? '<br><div class="logo_slogan">' . esc_html($ORGANICS_GLOBALS['logo_slogan']) . '</div>' 
					: '');
			?></a>
		</div>
	<?php
	}
}
organics_show_logos();
?>