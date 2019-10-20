<?php
/**
 * Organics Shortcodes
*/





// ---------------------------------- [trx_accordion] ---------------------------------------

/*
[trx_accordion style="1" counter="off" initial="1"]
	[trx_accordion_item title="Accordion Title 1"]Lorem ipsum dolor sit amet, consectetur adipisicing elit[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 2"]Proin dignissim commodo magna at luctus. Nam molestie justo augue, nec eleifend urna laoreet non.[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 3 with custom icons" icon_closed="icon-check" icon_opened="icon-delete"]Curabitur tristique tempus arcu a placerat.[/trx_accordion_item]
[/trx_accordion]
*/
if (!function_exists('organics_sc_accordion')) {	
	function organics_sc_accordion($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"initial" => "1",
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		$style = max(1, min(2, $style));
		$initial = max(0, (int) $initial);
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_accordion_counter'] = 0;
		$ORGANICS_GLOBALS['sc_accordion_show_counter'] = organics_param_is_on($counter);
		$ORGANICS_GLOBALS['sc_accordion_icon_closed'] = empty($icon_closed) || organics_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed;
		$ORGANICS_GLOBALS['sc_accordion_icon_opened'] = empty($icon_opened) || organics_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened;
		wp_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion sc_accordion_style_'.esc_attr($style)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (organics_param_is_on($counter) ? ' sc_show_counter' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. ' data-active="' . ($initial-1) . '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_accordion', $atts, $content);
	}
	add_shortcode('trx_accordion', 'organics_sc_accordion');
}


if (!function_exists('organics_sc_accordion_item')) {	
	function organics_sc_accordion_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"icon_closed" => "",
			"icon_opened" => "",
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_accordion_counter']++;
		if (empty($icon_closed) || organics_param_is_inherit($icon_closed)) $icon_closed = $ORGANICS_GLOBALS['sc_accordion_icon_closed'] ? $ORGANICS_GLOBALS['sc_accordion_icon_closed'] : "icon-plus";
		if (empty($icon_opened) || organics_param_is_inherit($icon_opened)) $icon_opened = $ORGANICS_GLOBALS['sc_accordion_icon_opened'] ? $ORGANICS_GLOBALS['sc_accordion_icon_opened'] : "icon-minus";
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion_item' 
				. (!empty($class) ? ' '.esc_attr($class) : '')
				. ($ORGANICS_GLOBALS['sc_accordion_counter'] % 2 == 1 ? ' odd' : ' even') 
				. ($ORGANICS_GLOBALS['sc_accordion_counter'] == 1 ? ' first' : '') 
				. '">'
				. '<h5 class="sc_accordion_title">'
				. (!organics_param_is_off($icon_closed) ? '<span class="sc_accordion_icon sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
				. (!organics_param_is_off($icon_opened) ? '<span class="sc_accordion_icon sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
				. ($ORGANICS_GLOBALS['sc_accordion_show_counter'] ? '<span class="sc_items_counter">'.($ORGANICS_GLOBALS['sc_accordion_counter']).'</span>' : '')
				. ($title)
				. '</h5>'
				. '<div class="sc_accordion_content"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. do_shortcode($content) 
				. '</div>'
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
	}
	add_shortcode('trx_accordion_item', 'organics_sc_accordion_item');
}
// ---------------------------------- [/trx_accordion] ---------------------------------------






// ---------------------------------- [trx_anchor] ---------------------------------------

						
/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('organics_sc_anchor')) {	
	function organics_sc_anchor($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(organics_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (organics_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('organics_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	add_shortcode("trx_anchor", "organics_sc_anchor");
}
// ---------------------------------- [/trx_anchor] ---------------------------------------





// ---------------------------------- [trx_audio] ---------------------------------------

/*
[trx_audio url="http://trex2.organics.dnw/wp-content/uploads/2014/12/Dream-Music-Relax.mp3" image="http://trex2.organics.dnw/wp-content/uploads/2014/10/post_audio.jpg" title="Insert Audio Title Here" author="Lily Hunter" controls="show" autoplay="off"]
*/

if (!function_exists('organics_sc_audio')) {	
	function organics_sc_audio($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"author" => "",
			"image" => "",
			"mp3" => '',
			"wav" => '',
			"src" => '',
			"url" => '',
			"align" => '',
			"controls" => "",
			"autoplay" => "",
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		if ($src=='') {
			if ($url) $src = $url;
			else if ($mp3) $src = $mp3;
			else if ($wav) $src = $wav;
		}
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		$data = ($title != ''  ? ' data-title="'.esc_attr($title).'"'   : '')
				. ($author != '' ? ' data-author="'.esc_attr($author).'"' : '')
				. ($image != ''  ? ' data-image="'.esc_url($image).'"'   : '')
				. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '');
		$audio = '<audio'
			. ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_audio' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. ' src="'.esc_url($src).'"'
			. (organics_param_is_on($controls) ? ' controls="controls"' : '')
			. (organics_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '')
			. ' width="'.esc_attr($width).'" height="'.esc_attr($height).'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($data)
			. '></audio>';
		if ( organics_get_custom_option('substitute_audio')=='no') {
			if (organics_param_is_on($frame)) {
				$audio = organics_get_audio_frame($audio, $image, $s);
			}
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$audio = organics_substitute_audio($audio, false);
			}
		}
		if (organics_get_theme_option('use_mediaelement')=='yes')
			wp_enqueue_script('wp-mediaelement');
		return apply_filters('organics_shortcode_output', $audio, 'trx_audio', $atts, $content);
	}
	add_shortcode("trx_audio", "organics_sc_audio");
}
// ---------------------------------- [/trx_audio] ---------------------------------------





// ---------------------------------- [trx_blogger] ---------------------------------------

/*
[trx_blogger id="unique_id" ids="comma_separated_list" cat="id|slug" orderby="date|views|comments" order="asc|desc" count="5" descr="0" dir="horizontal|vertical" style="regular|date|image_large|image_medium|image_small|accordion|list" border="0"]
*/
global $ORGANICS_GLOBALS;
$ORGANICS_GLOBALS['sc_blogger_busy'] = false;

if (!function_exists('organics_sc_blogger')) {
	function organics_sc_blogger($atts, $content=null){
		if (organics_in_shortcode_blogger(true)) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "accordion-1",
			"filters" => "no",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"columns" => "",
			"offset" => "",
			"orderby" => "date",
			"order" => "asc",
			"only" => "no",
			"descr" => "",
			"readmore" => "",
			"loadmore" => "no",
			"location" => "default",
			"dir" => "horizontal",
			"hover" => organics_get_theme_option('hover_style'),
			"hover_dir" => organics_get_theme_option('hover_dir'),
			"scroll" => "no",
			"controls" => "no",
			"rating" => "no",
			"info" => "yes",
			"links" => "yes",
			"date_format" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => __('Learn more', 'trx_utils'),
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$width  = organics_prepare_css_value($width);
		$height = organics_prepare_css_value($height);

		global $post, $ORGANICS_GLOBALS;

		$ORGANICS_GLOBALS['sc_blogger_busy'] = true;
		$ORGANICS_GLOBALS['sc_blogger_counter'] = 0;

		if (empty($id)) $id = "sc_blogger_".str_replace('.', '', mt_rand());

		if ($style=='date' && empty($date_format)) $date_format = 'd.m+Y';

		if (!empty($ids)) {
			$posts = explode(',', str_replace(' ', '', $ids));
			$count = count($posts);
		}

		if ($descr == '') $descr = organics_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : ''));

		if (!organics_param_is_off($scroll)) {
			organics_enqueue_slider();
			if (empty($id)) $id = 'sc_blogger_'.str_replace('.', '', mt_rand());
		}

		$class = apply_filters('organics_filter_blog_class',
					'sc_blogger'
					. ' layout_'.esc_attr($style)
					. ' template_'.esc_attr(organics_get_template_name($style))
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ' ' . esc_attr(organics_get_template_property($style, 'container_classes'))
					. ' sc_blogger_' . ($dir=='vertical' ? 'vertical' : 'horizontal')
					. (organics_param_is_on($scroll) && organics_param_is_on($controls) ? ' sc_scroll_controls sc_scroll_controls_type_top sc_scroll_controls_'.esc_attr($dir) : '')
					. ($descr == 0 ? ' no_description' : ''),
					array('style'=>$style, 'dir'=>$dir, 'descr'=>$descr)
		);

		$container = apply_filters('organics_filter_blog_container', organics_get_template_property($style, 'container'), array('style'=>$style, 'dir'=>$dir));
		$container_start = $container_end = '';
		if (!empty($container)) {
			$container = explode('%s', $container);
			$container_start = !empty($container[0]) ? $container[0] : '';
			$container_end = !empty($container[1]) ? $container[1] : '';
		}
		$container2 = apply_filters('organics_filter_blog_container2', organics_get_template_property($style, 'container2'), array('style'=>$style, 'dir'=>$dir));
		$container2_start = $container2_end = '';
		if (!empty($container2)) {
			$container2 = explode('%s', $container2);
			$container2_start = !empty($container2[0]) ? $container2[0] : '';
			//$container2_start = str_replace(array('%css'), array('style="height:'.esc_attr($height).'"'), $container2_start);
			$container2_end = !empty($container2[1]) ? $container2[1] : '';
		}

		$output = '<div'
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="'.($style=='list' ? 'sc_list sc_list_style_iconed ' : '') . esc_attr($class).'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. '>'
			. ($container_start)
			. (!empty($subtitle) ? '<h6 class="sc_blogger_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_blogger_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_blogger_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
			. ($container2_start)
			. ($style=='list' ? '<ul class="sc_list sc_list_style_iconed">' : '')
			. ($dir=='horizontal' && $columns > 1 && organics_get_template_property($style, 'need_columns') ? '<div class="columns_wrap">' : '')
			. (organics_param_is_on($scroll)
				? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($dir).' sc_slider_noresize swiper-slider-container scroll-container"'
					. ' style="'.($dir=='vertical' ? 'height:'.($height != '' ? $height : "230px").';' : 'width:'.($width != '' ? $width.';' : "100%;")).'"'
					. '>'
					. '<div class="sc_scroll_wrapper swiper-wrapper">'
						. '<div class="sc_scroll_slide swiper-slide">'
				: '')
			;

		if (organics_get_template_property($style, 'need_isotope')) {
			if (!organics_param_is_off($filters))
				$output .= '<div class="isotope_filters"></div>';
			if ($columns<1) $columns = organics_substr($style, -1);
			$output .= '<div class="isotope_wrap" data-columns="'.max(1, min(12, $columns)).'">';
		}

		$args = array(
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => $order=='asc' ? 'asc' : 'desc',
			'orderby' => 'date',
		);

		if ($offset > 0 && empty($ids)) {
			$args['offset'] = $offset;
		}

		$args = organics_query_add_sort_order($args, $orderby, $order);
		if (!organics_param_is_off($only)) $args = organics_query_add_filters($args, $only);
		$args = organics_query_add_posts_and_cats($args, $ids, $post_type, $cat);

		$query = new WP_Query( $args );

		$flt_ids = array();

		while ( $query->have_posts() ) { $query->the_post();

			$ORGANICS_GLOBALS['sc_blogger_counter']++;

			$args = array(
				'layout' => $style,
				'show' => false,
				'number' => $ORGANICS_GLOBALS['sc_blogger_counter'],
				'add_view_more' => false,
				'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
				// Additional options to layout generator
				"location" => $location,
				"descr" => $descr,
				"readmore" => $readmore,
				"loadmore" => $loadmore,
				"reviews" => organics_param_is_on($rating),
				"dir" => $dir,
				"scroll" => organics_param_is_on($scroll),
				"info" => organics_param_is_on($info),
				"links" => organics_param_is_on($links),
				"orderby" => $orderby,
				"columns_count" => $columns,
				"date_format" => $date_format,
				// Get post data
				'strip_teaser' => false,
				'content' => organics_get_template_property($style, 'need_content'),
				'terms_list' => !organics_param_is_off($filters) || organics_get_template_property($style, 'need_terms'),
				'filters' => organics_param_is_off($filters) ? '' : $filters,
				'hover' => $hover,
				'hover_dir' => $hover_dir
			);
			$post_data = organics_get_post_data($args);
			$output .= organics_show_post_layout($args, $post_data);

			if (!organics_param_is_off($filters)) {
				if ($filters == 'tags') {			// Use tags as filter items
					if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
						foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
							$flt_ids[$tag->term_id] = $tag->name;
						}
					}
				}
			}

		}

		wp_reset_postdata();

		// Close isotope wrapper
		if (organics_get_template_property($style, 'need_isotope'))
			$output .= '</div>';

		// Isotope filters list
		if (!organics_param_is_off($filters)) {
			$filters_list = '';
			if ($filters == 'categories') {			// Use categories as filter items
				$taxonomy = organics_get_taxonomy_categories_by_post_type($post_type);
				$portfolio_parent = $cat ? max(0, organics_get_parent_taxonomy_by_property($cat, 'show_filters', 'yes', true, $taxonomy)) : 0;
				$args2 = array(
					'type'			=> $post_type,
					'child_of'		=> $portfolio_parent,
					'orderby'		=> 'name',
					'order'			=> 'ASC',
					'hide_empty'	=> 1,
					'hierarchical'	=> 0,
					'exclude'		=> '',
					'include'		=> '',
					'number'		=> '',
					'taxonomy'		=> $taxonomy,
					'pad_counts'	=> false
				);
				$portfolio_list = get_categories($args2);
				if (is_array($portfolio_list) && count($portfolio_list) > 0) {
					$filters_list .= '<a href="#" data-filter="*" class="theme_button active">'.__('All', 'trx_utils').'</a>';
					foreach ($portfolio_list as $cat) {
						$filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($cat->term_id).'" class="theme_button">'.($cat->name).'</a>';
					}
				}
			} else {								// Use tags as filter items
				if (is_array($flt_ids) && count($flt_ids) > 0) {
					$filters_list .= '<a href="#" data-filter="*" class="theme_button active">'.__('All', 'trx_utils').'</a>';
					foreach ($flt_ids as $flt_id=>$flt_name) {
						$filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($flt_id).'" class="theme_button">'.($flt_name).'</a>';
					}
				}
			}
			if ($filters_list) {
				$output .= '<script type="text/javascript">'
					. 'jQuery(document).ready(function () {'
						. 'jQuery("#'.esc_attr($id).' .isotope_filters").append("'.addslashes($filters_list).'");'
					. '});'
					. '</script>';
			}
		}
		$output	.= (organics_param_is_on($scroll)
				? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
					. (!organics_param_is_off($controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
				: '')
			. ($dir=='horizontal' && $columns > 1 && organics_get_template_property($style, 'need_columns') ? '</div>' :  '')
			. ($style == 'list' ? '</ul>' : '')
			. ($container2_end)
			. (!empty($link)
				? '<div class="sc_blogger_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' 				: '')
			. ($container_end)
			. '</div>';

		// Add template specific scripts and styles
		do_action('organics_action_blog_scripts', $style);

		$ORGANICS_GLOBALS['sc_blogger_busy'] = false;

		return apply_filters('organics_shortcode_output', $output, 'trx_blogger', $atts, $content);
	}
	add_shortcode('trx_blogger', 'organics_sc_blogger');
}
// ---------------------------------- [/trx_blogger] ---------------------------------------





// ---------------------------------- [trx_br] ---------------------------------------
						
/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('organics_sc_br')) {	
	function organics_sc_br($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('organics_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	add_shortcode("trx_br", "organics_sc_br");
}
// ---------------------------------- [/trx_br] ---------------------------------------






// ---------------------------------- [trx_button] ---------------------------------------

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('organics_sc_button')) {	
	function organics_sc_button($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "round",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (organics_param_is_on($popup)) organics_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (organics_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('organics_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	add_shortcode('trx_button', 'organics_sc_button');
}
// ---------------------------------- [/trx_button] ---------------------------------------






// ---------------------------------- [trx_call_to_action] ---------------------------------------

/*
[trx_call_to_action id="unique_id" style="1|2" align="left|center|right"]
	[inner shortcodes]
[/trx_call_to_action]
*/

if (!function_exists('organics_sc_call_to_action')) {	
	function organics_sc_call_to_action($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"align" => "center",
			"custom" => "no",
			"accent" => "no",
			"image" => "",
			"video" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => __('Learn more', 'trx_utils'),
			"link2" => '',
			"link2_caption" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_call_to_action_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if (!empty($image)) {
			$thumb_sizes = organics_get_thumb_sizes(array('layout' => 'excerpt'));
			$image = !empty($video) 
				? organics_get_resized_image_url($image, $thumb_sizes['w'], $thumb_sizes['h']) 
				: organics_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		if (!empty($video)) {
			$video = '<video' . ($id ? ' id="' . esc_attr($id.'_video') . '"' : '') 
				. ' class="sc_video"'
				. ' src="' . esc_url(organics_get_video_player_url($video)) . '"'
				. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
				. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
				. ' data-ratio="16:9"'
				. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
				. ' controls="controls" loop="loop"'
				. '>'
				. '</video>';
			if (organics_get_custom_option('substitute_video')=='no') {
				$video = organics_get_video_frame($video, $image, '', '');
			} else {
				if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
					$video = organics_substitute_video($video, $width, $height, false);
				}
			}
			if (organics_get_theme_option('use_mediaelement')=='yes')
				wp_enqueue_script('wp-mediaelement');
		}
		
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		
		$content = do_shortcode($content);
		
		$featured = ($style==1 && (!empty($content) || !empty($image) || !empty($video))
					? '<div class="sc_call_to_action_featured column-1_2">'
						. (!empty($content) 
							? $content 
							: (!empty($video) 
								? $video 
								: $image)
							)
						. '</div>'
					: '');
	
		$need_columns = ($featured || $style==2) && !in_array($align, array('center', 'none'))
							? ($style==2 ? 4 : 2)
							: 0;
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_call_to_action_buttons sc_item_buttons'.($need_columns && $style==2 ? ' column-1_'.esc_attr($need_columns) : '').'">'
							. (!empty($link) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' 
								: '')
							. (!empty($link2) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'" icon="icon-right"]'.esc_html($link2_caption).'[/trx_button]').'</div>' 
								: '')
							. '</div>'
						: '');
	
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_call_to_action'
					. (organics_param_is_on($accent) ? ' sc_call_to_action_accented' : '')
					. ' sc_call_to_action_style_' . esc_attr($style) 
					. ' sc_call_to_action_align_'.esc_attr($align)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (organics_param_is_on($accent) ? '<div class="content_wrap">' : '')
				. ($need_columns ? '<div class="columns_wrap">' : '')
				. ($align!='right' ? $featured : '')
				. ($style==2 && $align=='right' ? $buttons : '')
				. '<div class="sc_call_to_action_info'.($need_columns ? ' column-'.esc_attr($need_columns-1).'_'.esc_attr($need_columns) : '').'">'
					. (!empty($subtitle) ? '<h6 class="sc_call_to_action_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_call_to_action_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_call_to_action_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
					. ($style==1 ? $buttons : '')
				. '</div>'
				. ($style==2 && $align!='right' ? $buttons : '')
				. ($align=='right' ? $featured : '')
				. ($need_columns ? '</div>' : '')
				. (organics_param_is_on($accent) ? '</div>' : '')
			. '</div>';
	
		return apply_filters('organics_shortcode_output', $output, 'trx_call_to_action', $atts, $content);
	}
	add_shortcode('trx_call_to_action', 'organics_sc_call_to_action');
}
// ---------------------------------- [/trx_call_to_action] ---------------------------------------





// ---------------------------------- [trx_chat] ---------------------------------------

/*
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
...
*/

if (!function_exists('organics_sc_chat')) {	
	function organics_sc_chat($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"photo" => "",
			"title" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$title = $title=='' ? $link : $title;
		if (!empty($photo)) {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = organics_get_resized_image_tag($photo, 75, 75);
		}
		$content = do_shortcode($content);
		if (organics_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_chat' . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($css ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
					. '<div class="sc_chat_inner">'
						. ($photo ? '<div class="sc_chat_avatar">'.($photo).'</div>' : '')
						. ($title == '' ? '' : ('<div class="sc_chat_title">' . ($link!='' ? '<a href="'.esc_url($link).'">' : '') . ($title) . ($link!='' ? '</a>' : '') . '</div>'))
						. '<div class="sc_chat_content">'.($content).'</div>'
					. '</div>'
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_chat', $atts, $content);
	}
	add_shortcode('trx_chat', 'organics_sc_chat');
}
// ---------------------------------- [/trx_chat] ---------------------------------------




// ---------------------------------- [trx_columns] ---------------------------------------

/*
[trx_columns id="unique_id" count="number"]
	[trx_column_item id="unique_id" span="2 - number_columns"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta, odio arcu vut natoque dolor ut, enim etiam vut augue. Ac augue amet quis integer ut dictumst? Elit, augue vut egestas! Tristique phasellus cursus egestas a nec a! Sociis et? Augue velit natoque, amet, augue. Vel eu diam, facilisis arcu.[/trx_column_item]
	[trx_column_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in. Magna eros hac montes, et velit. Odio aliquam phasellus enim platea amet. Turpis dictumst ultrices, rhoncus aenean pulvinar? Mus sed rhoncus et cras egestas, non etiam a? Montes? Ac aliquam in nec nisi amet eros! Facilisis! Scelerisque in.[/trx_column_item]
	[trx_column_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim. Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna.[/trx_column_item]
	[trx_column_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna. Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim.[/trx_column_item]
[/trx_columns]
*/

if (!function_exists('organics_sc_columns')) {	
	function organics_sc_columns($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"count" => "2",
			"fluid" => "no",
			"margins" => "yes",
			"banner" => "yes",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$count = max(1, min(12, (int) $count));
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_columns_counter'] = 1;
		$ORGANICS_GLOBALS['sc_columns_after_span2'] = false;
		$ORGANICS_GLOBALS['sc_columns_after_span3'] = false;
		$ORGANICS_GLOBALS['sc_columns_after_span4'] = false;
		$ORGANICS_GLOBALS['sc_columns_count'] = $count;
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="columns_wrap sc_columns'
					. ' columns_' . (organics_param_is_on($fluid) ? 'fluid' : 'nofluid') 
					. (!empty($margins) && organics_param_is_off($margins) ? ' no_margins' : '') 
					. (!empty($banner) && organics_param_is_off($banner) ? ' banner_grid' : '')
					. ' sc_columns_count_' . esc_attr($count)
					. (!empty($class) ? ' '.esc_attr($class) : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>'
					. do_shortcode($content)
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_columns', $atts, $content);
	}
	add_shortcode('trx_columns', 'organics_sc_columns');
}


if (!function_exists('organics_sc_column_item')) {	
	function organics_sc_column_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"span" => "1",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_tile" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => ""
		), $atts)));
		$css .= ($align !== '' ? 'text-align:' . esc_attr($align) . ';' : '') 
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '');
		$span = max(1, min(11, (int) $span));
		if (!empty($bg_image)) {
			if ($bg_image > 0) {
				$attach = wp_get_attachment_image_src( $bg_image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$bg_image = $attach[0];
			}
		}
		global $ORGANICS_GLOBALS;
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="column-'.($span > 1 ? esc_attr($span) : 1).'_'.esc_attr($ORGANICS_GLOBALS['sc_columns_count']).' sc_column_item sc_column_item_'.esc_attr($ORGANICS_GLOBALS['sc_columns_counter']) 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($ORGANICS_GLOBALS['sc_columns_counter'] % 2 == 1 ? ' odd' : ' even') 
					. ($ORGANICS_GLOBALS['sc_columns_counter'] == 1 ? ' first' : '') 
					. ($span > 1 ? ' span_'.esc_attr($span) : '') 
					. ($ORGANICS_GLOBALS['sc_columns_after_span2'] ? ' after_span_2' : '') 
					. ($ORGANICS_GLOBALS['sc_columns_after_span3'] ? ' after_span_3' : '') 
					. ($ORGANICS_GLOBALS['sc_columns_after_span4'] ? ' after_span_4' : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
					. '>'
					. ($bg_color!=='' || $bg_image !== '' ? '<div class="sc_column_item_inner" style="'
							. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(organics_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
							. '">' : '')
						. do_shortcode($content)
					. ($bg_color!=='' || $bg_image !== '' ? '</div>' : '')
					. '</div>';
		$ORGANICS_GLOBALS['sc_columns_counter'] += $span;
		$ORGANICS_GLOBALS['sc_columns_after_span2'] = $span==2;
		$ORGANICS_GLOBALS['sc_columns_after_span3'] = $span==3;
		$ORGANICS_GLOBALS['sc_columns_after_span4'] = $span==4;
		return apply_filters('organics_shortcode_output', $output, 'trx_column_item', $atts, $content);
	}
	add_shortcode('trx_column_item', 'organics_sc_column_item');
}
// ---------------------------------- [/trx_columns] ---------------------------------------





// ---------------------------------- [trx_form] ---------------------------------------

/*
[trx_form id="unique_id" title="Contact Form" description="Mauris aliquam habitasse magna."]
*/

if (!function_exists('organics_sc_form')) {	
	function organics_sc_form($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "form_custom",
			"action" => "",
			"align" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_form_".str_replace('.', '', mt_rand());
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width);
	
		organics_enqueue_messages();	// Load core messages
	
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_form_id'] = $id;
		$ORGANICS_GLOBALS['sc_form_counter'] = 0;
	
		if ($style == 'form_custom')
			$content = do_shortcode($content);
	
		$output = '<div ' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
					. ' class="sc_form_wrap'
					. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">'
			.'<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_form'
					. ' sc_form_style_'.($style) 
					. (!empty($align) && !organics_param_is_off($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>'
					. (!empty($subtitle) 
						? '<h6 class="sc_form_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' 
						: '')
					. (!empty($title) 
						? '<h2 class="sc_form_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' 
						: '')
					. (!empty($description) 
						? '<div class="sc_form_descr sc_item_descr">' . trim(organics_strmacros($description)) . ($style == 1 ? do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]') : '') . '</div>' 
						: '');
		
		$output .= organics_show_post_layout(array(
												'layout' => $style,
												'id' => $id,
												'action' => $action,
												'content' => $content,
												'show' => false
												), false);

		$output .= '</div>'
				. '</div>';
	
		return apply_filters('organics_shortcode_output', $output, 'trx_form', $atts, $content);
	}
	add_shortcode("trx_form", "organics_sc_form");
}

if (!function_exists('organics_sc_form_item')) {	
	function organics_sc_form_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"type" => "text",
			"name" => "",
			"value" => "",
			"options" => "",
			"align" => "",
			"label" => "",
			"label_position" => "top",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_form_counter']++;
	
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		if (empty($id)) $id = ($ORGANICS_GLOBALS['sc_form_id']).'_'.($ORGANICS_GLOBALS['sc_form_counter']);
	
		$label = $type!='button' && $type!='submit' && $label ? '<label for="' . esc_attr($id) . '">' . esc_attr($label) . '</label>' : $label;
	
		// Open field container
		$output = '<div class="sc_form_item sc_form_item_'.esc_attr($type)
						.' sc_form_'.($type == 'textarea' ? 'message' : ($type == 'button' || $type == 'submit' ? 'button' : 'field'))
						.' label_'.esc_attr($label_position)
						.($class ? ' '.esc_attr($class) : '')
						.($align && $align!='none' ? ' align'.esc_attr($align) : '')
					.'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
					. '>';
		
		// Label top or left
		if ($type!='button' && $type!='submit' && ($label_position=='top' || $label_position=='left'))
			$output .= $label;

		// Field output
		if ($type == 'textarea')

			$output .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">' . esc_attr($value) . '</textarea>';

		else if ($type=='button' || $type=='submit')

			$output .= '<button id="' . esc_attr($id) . '">'.($label ? $label : $value).'</button>';

		else if ($type=='radio' || $type=='checkbox') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$i = 0;
					foreach ($options as $v) {
						$i++;
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<div class="sc_form_element">'
										. '<input type="'.esc_attr($type) . '"'
											. ' id="' . esc_attr($id.($i>1 ? '_'.$i : '')) . '"'
											. ' name="' . esc_attr($name ? $name : $id) . (count($options) > 1 && $type=='checkbox' ? '[]' : '') . '"'
											. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
											. (in_array($parts[0], explode(',', $value)) ? ' checked="checked"' : '') 
										. '>'
										. '<label for="' . esc_attr($id.($i>1 ? '_'.$i : '')) . '">' . trim(chop($parts[1])) . '</label>'
									. '</div>';
					}
				}
			}

		} else if ($type=='select') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$output .= '<div class="sc_form_select_container">'
						. '<select id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">';
					foreach ($options as $v) {
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<option'
										. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
										. (in_array($parts[0], explode(',', $value)) ? ' selected="selected"' : '') 
									. '>'
									. trim(chop($parts[1]))
									. '</option>';
					}
					$output .= '</select>'
							. '</div>';
				}
			}

		} else if ($type=='date') {
			wp_enqueue_script( 'jquery-picker', organics_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			wp_enqueue_script( 'jquery-picker-date', organics_get_file_url('/js/picker/picker.date.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_date_wrap icon-calendar-light">'
						. '<input placeholder="' . __('Date', 'trx_utils') . '" id="' . esc_attr($id) . '" class="js__datepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';

		} else if ($type=='time') {
			wp_enqueue_script( 'jquery-picker', organics_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			wp_enqueue_script( 'jquery-picker-time', organics_get_file_url('/js/picker/picker.time.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_time_wrap icon-clock-empty">'
						. '<input placeholder="' . __('Time', 'trx_utils') . '" id="' . esc_attr($id) . '" class="js__timepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';
	
		} else

			$output .= '<input type="'.esc_attr($type ? $type : 'text').'" id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '" value="' . esc_attr($value) . '">';

		// Label bottom
		if ($type!='button' && $type!='submit' && $label_position=='bottom')
			$output .= $label;
		
		// Close field container
		$output .= '</div>';
	
		return apply_filters('organics_shortcode_output', $output, 'trx_form_item', $atts, $content);
	}
	add_shortcode('trx_form_item', 'organics_sc_form_item');
}

// AJAX Callback: Send contact form data
if ( !function_exists( 'organics_sc_form_send' ) ) {
	function organics_sc_form_send() {
		global $_REQUEST, $ORGANICS_GLOBALS;
	
		if ( !organics_verify_nonce( $_REQUEST['nonce'], $ORGANICS_GLOBALS['ajax_url'] ) )
			die();
	
		$response = array('error'=>'');
		if (!($contact_email = organics_get_theme_option('contact_email')) && !($contact_email = organics_get_theme_option('admin_email'))) 
			$response['error'] = __('Unknown admin email!', 'trx_utils');
		else {
			$type = organics_substr($_REQUEST['type'], 0, 7);
			parse_str($_POST['data'], $post_data);

            if (in_array($type, array('form_1', 'form_2'))) {
				$user_name	= organics_strshort($post_data['username'],	100);
				$user_email	= organics_strshort($post_data['email'],	100);
				$user_subj	= organics_strshort($post_data['subject'],	100);
				$user_msg	= organics_strshort($post_data['message'],	organics_get_theme_option('message_maxlength_contacts'));
		
				$subj = sprintf(__('Site %s - Contact form message from %s', 'trx_utils'), get_bloginfo('site_name'), $user_name);
				$msg = "\n".__('Name:', 'trx_utils')   .' '.esc_html($user_name)
					.  "\n".__('E-mail:', 'trx_utils') .' '.esc_html($user_email)
					.  "\n".__('Subject:', 'trx_utils').' '.esc_html($user_subj)
					.  "\n".__('Message:', 'trx_utils').' '.esc_html($user_msg);

			} else {

				$subj = sprintf(__('Site %s - Custom form data', 'trx_utils'), get_bloginfo('site_name'));
				$msg = '';
				if (is_array($post_data) && count($post_data) > 0) {
					foreach ($post_data as $k=>$v)
						$msg .= "\n{$k}: $v";
				}
			}

			$msg .= "\n\n............. " . get_bloginfo('site_name') . " (" . home_url() . ") ............";

			$mail = organics_get_theme_option('mail_function') == 'mail' ? 'mail' : 'wp_mail';
			if (is_email($contact_email) && !@$mail($contact_email, $subj, apply_filters('organics_filter_form_send_message', $msg))) {
				$response['error'] = __('Error send message!', 'trx_utils');
			}
		
			echo json_encode($response);
			die();
		}
	}
}

// ---------------------------------- [/trx_form] ---------------------------------------




// ---------------------------------- [trx_content] ---------------------------------------

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('organics_sc_content')) {	
	function organics_sc_content($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values('!'.($top), '', '!'.($bottom));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	add_shortcode('trx_content', 'organics_sc_content');
}
// ---------------------------------- [/trx_content] ---------------------------------------





// ---------------------------------- [trx_countdown] ---------------------------------------

//[trx_countdown date="" time=""]

if (!function_exists('organics_sc_countdown')) {	
	function organics_sc_countdown($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"date" => "",
			"time" => "",
			"style" => "1",
			"align" => "center",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if (empty($id)) $id = "sc_countdown_".str_replace('.', '', mt_rand());
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		if (empty($interval)) $interval = 1;
		wp_enqueue_script( 'organics-jquery-plugin-script', organics_get_file_url('js/countdown/jquery.plugin.js'), array('jquery'), null, true );
		wp_enqueue_script( 'organics-countdown-script', organics_get_file_url('js/countdown/jquery.countdown.js'), array('jquery'), null, true );
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_countdown sc_countdown_style_' . esc_attr(max(1, min(2, $style))) . (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') . (!empty($class) ? ' '.esc_attr($class) : '') .'"'
			. ($css ? ' style="'.esc_attr($css).'"' : '')
			. ' data-date="'.esc_attr(empty($date) ? date('Y-m-d') : $date).'"'
			. ' data-time="'.esc_attr(empty($time) ? '00:00:00' : $time).'"'
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. '>'
				. ($align=='center' ? '<div class="sc_countdown_inner">' : '')
				. '<div class="sc_countdown_item sc_countdown_days">'
					. '<span class="sc_countdown_digits"><span></span><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.__('Days', 'trx_utils').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_hours">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.__('Hours', 'trx_utils').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_minutes">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.__('Minutes', 'trx_utils').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_seconds">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.__('Seconds', 'trx_utils').'</span>'
				. '</div>'
				. '<div class="sc_countdown_placeholder hide"></div>'
				. ($align=='center' ? '</div>' : '')
			. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_countdown', $atts, $content);
	}
	add_shortcode("trx_countdown", "organics_sc_countdown");
}
// ---------------------------------- [/trx_countdown] ---------------------------------------



						


// ---------------------------------- [trx_dropcaps] ---------------------------------------

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('organics_sc_dropcaps')) {	
	function organics_sc_dropcaps($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		$style = min(4, max(1, $style));
		$content = do_shortcode($content);
		$output = organics_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(organics_substr($content, 0, 1)) . '</span>' . trim(organics_substr($content, 1))
			. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	add_shortcode('trx_dropcaps', 'organics_sc_dropcaps');
}
// ---------------------------------- [/trx_dropcaps] ---------------------------------------





// ---------------------------------- [trx_emailer] ---------------------------------------

//[trx_emailer group=""]

if (!function_exists('organics_sc_emailer')) {	
	function organics_sc_emailer($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"group" => "",
			"open" => "yes",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		// Load core messages
		organics_enqueue_messages();
        static $cnt = 0;
        $cnt++;
        $privacy = trx_utils_get_privacy_text();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_emailer' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (organics_param_is_on($open) ? ' sc_emailer_opened' : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
					. ($css ? ' style="'.esc_attr($css).'"' : '') 
					. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
					. '>'
				. '<form class="sc_emailer_form">'
				. '<input type="text" class="sc_emailer_input" name="email" value="" placeholder="">'
				. '<button href="#" class="sc_emailer_button icon-mail-1" title="'.esc_attr__('Submit', 'trx_utils').'" data-group="'.esc_attr($group ? $group : esc_html__('E-mailer subscription', 'trx_utils')).'" ' . (!empty($privacy) ? ' disabled="disabled"' : ' ') . '></button>'
            . ((!empty($privacy)) ? '<div class="sc_form_field sc_form_field_checkbox">
                  <input type="checkbox" id="i_agree_privacy_policy_sc_form_' . esc_attr($cnt) . '" name="i_agree_privacy_policy" class="sc_form_privacy_checkbox" value="1">
                  <label for="i_agree_privacy_policy_sc_form_' . esc_attr($cnt) . '">' . $privacy . '</label></div>' : '')
            . '</form>'
			. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_emailer', $atts, $content);
	}
	add_shortcode("trx_emailer", "organics_sc_emailer");
}
// ---------------------------------- [/trx_emailer] ---------------------------------------





// ---------------------------------- [trx_gap] ---------------------------------------
						
//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('organics_sc_gap')) {	
	function organics_sc_gap($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		$output = organics_gap_start() . do_shortcode($content) . organics_gap_end();
		return apply_filters('organics_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	add_shortcode("trx_gap", "organics_sc_gap");
}
// ---------------------------------- [/trx_gap] ---------------------------------------






// ---------------------------------- [trx_googlemap] ---------------------------------------

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('organics_sc_googlemap')) {	
	function organics_sc_googlemap($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = organics_get_custom_option('googlemap_style');
		
		$api_key = organics_get_theme_option('api_google');
        if ( organics_get_theme_option('api_google') != '' ) {
		    wp_enqueue_script( 'googlemap', organics_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		    wp_enqueue_script( 'organics-googlemap-script', organics_get_file_url('js/core.googlemap.js'), array(), null, true );
        }

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_googlemap_markers'] = array();
		$content = do_shortcode($content);
		$output = '';
		if (count($ORGANICS_GLOBALS['sc_googlemap_markers']) == 0) {
			$ORGANICS_GLOBALS['sc_googlemap_markers'][] = array(
				'title' => organics_get_custom_option('googlemap_title'),
				'description' => organics_strmacros(organics_get_custom_option('googlemap_description')),
				'latlng' => organics_get_custom_option('googlemap_latlng'),
				'address' => organics_get_custom_option('googlemap_address'),
				'point' => organics_get_custom_option('googlemap_marker')
			);
		}
		$output .= '<div id="'.esc_attr($id).'"'
			. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. ' data-zoom="'.esc_attr($zoom).'"'
			. ' data-style="'.esc_attr($style).'"'
			. '>';
		$cnt = 0;
		foreach ($ORGANICS_GLOBALS['sc_googlemap_markers'] as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.$cnt;
            if ( organics_get_theme_option('api_google') != '' ) {
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(organics_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
            } else {
                $output .= '<iframe src="https://maps.google.com/maps?t=m&output=embed&iwloc=near&z='.esc_attr($zoom > 0 ? $zoom : 14).'&q='
                    . esc_attr(!empty($marker['address']) ? urlencode($marker['address']) : '')
                    . ( !empty($marker['latlng'])
                        ? ( !empty($marker['address']) ? '@' : '' ) . str_replace(' ', '', $marker['latlng'])
                        : ''
                    )
                    . '" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"'
                    . ' aria-label="' . esc_attr(!empty($marker['title']) ? $marker['title'] : '') . '"></iframe>';
            }
        }
		$output .= '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	add_shortcode("trx_googlemap", "organics_sc_googlemap");
}


if (!function_exists('organics_sc_googlemap_marker')) {	
	function organics_sc_googlemap_marker($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_googlemap_markers'][] = array(
			'id' => $id,
			'title' => $title,
			'description' => do_shortcode($content),
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : organics_get_custom_option('googlemap_marker')
		);
		return '';
	}
	add_shortcode("trx_googlemap_marker", "organics_sc_googlemap_marker");
}
// ---------------------------------- [/trx_googlemap] ---------------------------------------





// ---------------------------------- [trx_hide] ---------------------------------------

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('organics_sc_hide')) {	
	function organics_sc_hide($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('organics_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	add_shortcode('trx_hide', 'organics_sc_hide');
}
// ---------------------------------- [/trx_hide] ---------------------------------------





// ---------------------------------- [trx_highlight] ---------------------------------------

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('organics_sc_highlight')) {	
	function organics_sc_highlight($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(organics_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('organics_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	add_shortcode('trx_highlight', 'organics_sc_highlight');
}
// ---------------------------------- [/trx_highlight] ---------------------------------------





// ---------------------------------- [trx_icon] ---------------------------------------

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('organics_sc_icon')) {	
	function organics_sc_icon($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !organics_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(organics_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || organics_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !organics_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($css ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('organics_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	add_shortcode('trx_icon', 'organics_sc_icon');
}
// ---------------------------------- [/trx_icon] ---------------------------------------





// ---------------------------------- [trx_image] ---------------------------------------

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('organics_sc_image')) {	
	function organics_sc_image($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values('!'.($top), '!'.($right), '!'.($bottom), '!'.($left), $width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = organics_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) organics_enqueue_popup();
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img src="'.esc_url($src).'" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('organics_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	add_shortcode('trx_image', 'organics_sc_image');
}
// ---------------------------------- [/trx_image] ---------------------------------------






// ---------------------------------- [trx_infobox] ---------------------------------------

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('organics_sc_infobox')) {	
	function organics_sc_infobox($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='regular')
				$icon = 'icon-gear40';
			else if ($style=='success')
				$icon = 'icon-like83';
			else if ($style=='error')
				$icon = 'icon-sad70';
			else if ($style=='info')
				$icon = 'icon-warning5';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (organics_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !organics_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	add_shortcode('trx_infobox', 'organics_sc_infobox');
}
// ---------------------------------- [/trx_infobox] ---------------------------------------





// ---------------------------------- [trx_line] ---------------------------------------

/*
[trx_line id="unique_id" style="none|solid|dashed|dotted|double|groove|ridge|inset|outset" top="margin_in_pixels" bottom="margin_in_pixels" width="width_in_pixels_or_percent" height="line_thickness_in_pixels" color="line_color's_name_or_#rrggbb"]
*/

if (!function_exists('organics_sc_line')) {	
	function organics_sc_line($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "solid",
			"color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width)
			.($height !='' ? 'border-top-width:' . esc_attr($height) . 'px;' : '')
			.($style != '' ? 'border-top-style:' . esc_attr($style) . ';' : '')
			.($color != '' ? 'border-top-color:' . esc_attr($color) . ';' : '');
		$output = '<div' . ($id ? ' id="'.esc_attr($id) . '"' : '') 
				. ' class="sc_line' . ($style != '' ? ' sc_line_style_'.esc_attr($style) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '></div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_line', $atts, $content);
	}
	add_shortcode('trx_line', 'organics_sc_line');
}
// ---------------------------------- [/trx_line] ---------------------------------------





// ---------------------------------- [trx_list] ---------------------------------------

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('organics_sc_list')) {	
	function organics_sc_list($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '');
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_list_counter'] = 0;
		$ORGANICS_GLOBALS['sc_list_icon'] = empty($icon) || organics_param_is_inherit($icon) ? "icon-right" : $icon;
		$ORGANICS_GLOBALS['sc_list_icon_color'] = $icon_color;
		$ORGANICS_GLOBALS['sc_list_style'] = $style;
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('organics_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	add_shortcode('trx_list', 'organics_sc_list');
}


if (!function_exists('organics_sc_list_item')) {	
	function organics_sc_list_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_list_counter']++;
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || organics_param_is_inherit($icon)) $icon = $ORGANICS_GLOBALS['sc_list_icon'];
		if (trim($color) == '' || organics_param_is_inherit($icon_color)) $icon_color = $ORGANICS_GLOBALS['sc_list_icon_color'];
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. ($ORGANICS_GLOBALS['sc_list_counter'] % 2 == 1 ? ' odd' : ' even') 
			. ($ORGANICS_GLOBALS['sc_list_counter'] == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. ($ORGANICS_GLOBALS['sc_list_style']=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. do_shortcode($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('organics_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	add_shortcode('trx_list_item', 'organics_sc_list_item');
}
// ---------------------------------- [/trx_list] ---------------------------------------






// ---------------------------------- [trx_number] ---------------------------------------

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('organics_sc_number')) {	
	function organics_sc_number($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < organics_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(organics_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	add_shortcode('trx_number', 'organics_sc_number');
}
// ---------------------------------- [/trx_number] ---------------------------------------





// ---------------------------------- [trx_parallax] ---------------------------------------

/*
[trx_parallax id="unique_id" style="light|dark" dir="up|down" image="" color='']Content for parallax block[/trx_parallax]
*/

if (!function_exists('organics_sc_parallax')) {	
	function organics_sc_parallax($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"gap" => "no",
			"dir" => "up",
			"speed" => 0.3,
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_image_x" => "",
			"bg_image_y" => "",
			"bg_video" => "",
			"bg_video_ratio" => "16:9",
			"bg_overlay" => "",
			"bg_texture" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if ($bg_video!='') {
			$info = pathinfo($bg_video);
			$ext = !empty($info['extension']) ? $info['extension'] : 'mp4';
			$bg_video_ratio = empty($bg_video_ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $bg_video_ratio);
			$ratio = explode(':', $bg_video_ratio);
			$bg_video_width = !empty($width) && organics_substr($width, -1) >= '0' && organics_substr($width, -1) <= '9'  ? $width : 1280;
			$bg_video_height = round($bg_video_width / $ratio[0] * $ratio[1]);
			if (organics_get_theme_option('use_mediaelement')=='yes')
				wp_enqueue_script('wp-mediaelement');
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		$bg_image_x = $bg_image_x!='' ? str_replace('%', '', $bg_image_x).'%' : "50%";
		$bg_image_y = $bg_image_y!='' ? str_replace('%', '', $bg_image_y).'%' : "50%";
		$speed = ($dir=='down' ? -1 : 1) * abs($speed);
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = organics_get_scheme_color('bg');
			$rgb = organics_hex2rgb($bg_color);
		}
		$css .= organics_get_css_position_from_values($top, '!'.($right), $bottom, '!'.($left), $width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			;
		$output = (organics_param_is_on($gap) ? organics_gap_start() : '')
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_parallax' 
					. ($bg_video!='' ? ' sc_parallax_with_video' : '') 
					. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. ' data-parallax-speed="'.esc_attr($speed).'"'
				. ' data-parallax-x-pos="'.esc_attr($bg_image_x).'"'
				. ' data-parallax-y-pos="'.esc_attr($bg_image_y).'"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>'
			. ($bg_video!='' 
				? '<div class="sc_video_bg_wrapper"><video class="sc_video_bg"'
					. ' width="'.esc_attr($bg_video_width).'" height="'.esc_attr($bg_video_height).'" data-width="'.esc_attr($bg_video_width).'" data-height="'.esc_attr($bg_video_height).'" data-ratio="'.esc_attr($bg_video_ratio).'" data-frame="no"'
					. ' preload="metadata" autoplay="autoplay" loop="loop" src="'.esc_attr($bg_video).'"><source src="'.esc_url($bg_video).'" type="video/'.esc_attr($ext).'"></source></video></div>' 
				: '')
			. '<div class="sc_parallax_content" style="' . ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . '); background-position:'.esc_attr($bg_image_x).' '.esc_attr($bg_image_y).';' : '').'">'
			. ($bg_overlay>0 || $bg_texture!=''
				? '<div class="sc_parallax_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
					. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
						. (organics_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
						. '"'
						. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
						. '>' 
				: '')
			. do_shortcode($content)
			. ($bg_overlay > 0 || $bg_texture!='' ? '</div>' : '')
			. '</div>'
			. '</div>'
			. (organics_param_is_on($gap) ? organics_gap_end() : '');
		return apply_filters('organics_shortcode_output', $output, 'trx_parallax', $atts, $content);
	}
	add_shortcode('trx_parallax', 'organics_sc_parallax');
}
// ---------------------------------- [/trx_parallax] ---------------------------------------




// ---------------------------------- [trx_popup] ---------------------------------------

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('organics_sc_popup')) {	
	function organics_sc_popup($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		organics_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	add_shortcode('trx_popup', 'organics_sc_popup');
}
// ---------------------------------- [/trx_popup] ---------------------------------------






// ---------------------------------- [trx_price] ---------------------------------------

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('organics_sc_price')) {	
	function organics_sc_price($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		if (!empty($money)) {
			$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
			$m = explode('.', str_replace(',', '.', $money));
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. '>'
				. '<span class="sc_price_currency">'.($currency).'</span>'
				. '<span class="sc_price_money">'.($m[0]).'</span>'
				. (!empty($m[1]) ? '<span class="sc_price_info">' : '')
				. (!empty($m[1]) ? '<span class="sc_price_penny">'.($m[1]).'</span>' : '')
				. (!empty($period) ? '<span class="sc_price_period">'.($period).'</span>' : (!empty($m[1]) ? '<span class="sc_price_period_empty"></span>' : ''))
				. (!empty($m[1]) ? '</span>' : '')
				. '</div>';
		}
		return apply_filters('organics_shortcode_output', $output, 'trx_price', $atts, $content);
	}
	add_shortcode('trx_price', 'organics_sc_price');
}
// ---------------------------------- [/trx_price] ---------------------------------------





// ---------------------------------- [trx_price_block] ---------------------------------------

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('organics_sc_price_block')) {	
	function organics_sc_price_block($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title">'.($title).'</div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	add_shortcode('trx_price_block', 'organics_sc_price_block');
}
// ---------------------------------- [/trx_price_block] ---------------------------------------




// ---------------------------------- [trx_quote] ---------------------------------------

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('organics_sc_quote')) {	
	function organics_sc_quote($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"cite" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width);
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (organics_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"' 
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('organics_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	add_shortcode('trx_quote', 'organics_sc_quote');
}
// ---------------------------------- [/trx_quote] ---------------------------------------





// ---------------------------------- [trx_reviews] ---------------------------------------
						
/*
[trx_reviews]
*/

if (!function_exists('organics_sc_reviews')) {	
	function organics_sc_reviews($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "right",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		$output = organics_param_is_off(organics_get_custom_option('show_sidebar_main'))
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_reviews'
							. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class ? ' '.esc_attr($class) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
						. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
						. '>'
					. trim(organics_get_reviews_placeholder())
					. '</div>'
			: '';
		return apply_filters('organics_shortcode_output', $output, 'trx_reviews', $atts, $content);
	}
	add_shortcode("trx_reviews", "organics_sc_reviews");
}
// ---------------------------------- [/trx_reviews] ---------------------------------------




// ---------------------------------- [trx_search] ---------------------------------------

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('organics_sc_search')) {	
	function organics_sc_search($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => __('Search', 'trx_utils'),
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		if (empty($ajax)) $ajax = organics_get_theme_option('use_ajax_search');
		// Load core messages
		organics_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (organics_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url( home_url( '/' ) ) . '">
								<button type="submit" class="search_submit icon-search-light" title="' . ($state=='closed' ? __('Open search', 'trx_utils') : __('Start search', 'trx_utils')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	add_shortcode('trx_search', 'organics_sc_search');
}
// ---------------------------------- [/trx_search] ---------------------------------------




// ---------------------------------- [trx_section] and [trx_block] ---------------------------------------

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

global $ORGANICS_GLOBALS;
$ORGANICS_GLOBALS['sc_section_dedicated'] = '';

if (!function_exists('organics_sc_section')) {	
	function organics_sc_section($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "no",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"font_size" => "",
			"font_weight" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = organics_get_scheme_color('bg');
			$rgb = organics_hex2rgb($bg_color);
		}
	
		$css .= organics_get_css_position_from_values('!'.($top), '!'.($right), '!'.($bottom), '!'.($left))
			.($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(organics_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!organics_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(organics_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !organics_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = organics_get_css_position_from_values('', '', '', '', $width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && organics_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = organics_prepare_css_value($width);
		$height = organics_prepare_css_value($height);
	
		if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!organics_param_is_off($scroll)) organics_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '') 
					. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || organics_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (organics_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (organics_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (organics_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
					. do_shortcode($content)
					. (organics_param_is_on($pan) ? '</div>' : '')
					. (organics_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || organics_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (organics_param_is_on($dedicated)) {
			global $ORGANICS_GLOBALS;
			if ($ORGANICS_GLOBALS['sc_section_dedicated']=='') {
				$ORGANICS_GLOBALS['sc_section_dedicated'] = $output;
			}
			$output = '';
		}
		return apply_filters('organics_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	add_shortcode('trx_section', 'organics_sc_section');
	add_shortcode('trx_block', 'organics_sc_section');
}
// ---------------------------------- [/trx_section] ---------------------------------------





// ---------------------------------- [trx_skills] ---------------------------------------

/*
[trx_skills id="unique_id" type="bar|pie|arc|counter" dir="horizontal|vertical" layout="rows|columns" count="" max_value="100" align="left|right"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
[/trx_skills]
*/

if (!function_exists('organics_sc_skills')) {	
	function organics_sc_skills($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "100",
			"type" => "bar",
			"layout" => "",
			"dir" => "",
			"style" => "1",
			"columns" => "",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"arc_caption" => __("Skills", 'trx_utils'),
			"pie_compact" => "on",
			"pie_cutout" => 0,
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => __('Learn more', 'trx_utils'),
			"link" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_skills_counter'] = 0;
		$ORGANICS_GLOBALS['sc_skills_columns'] = 0;
		$ORGANICS_GLOBALS['sc_skills_height']  = 0;
		$ORGANICS_GLOBALS['sc_skills_type']    = $type;
		$ORGANICS_GLOBALS['sc_skills_pie_compact'] = $pie_compact;
		$ORGANICS_GLOBALS['sc_skills_pie_cutout']  = max(0, min(99, $pie_cutout));
		$ORGANICS_GLOBALS['sc_skills_color']   = $color;
		$ORGANICS_GLOBALS['sc_skills_bg_color']= $bg_color;
		$ORGANICS_GLOBALS['sc_skills_border_color']= $border_color;
		$ORGANICS_GLOBALS['sc_skills_legend']  = '';
		$ORGANICS_GLOBALS['sc_skills_data']    = '';
		organics_enqueue_diagram($type);
		if ($type!='arc') {
			if ($layout=='' || ($layout=='columns' && $columns<1)) $layout = 'rows';
			if ($layout=='columns') $ORGANICS_GLOBALS['sc_skills_columns'] = $columns;
			if ($type=='bar') {
				if ($dir == '') $dir = 'horizontal';
				if ($dir == 'vertical' && $height < 1) $height = 300;
			}
		}
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
		if ($max_value < 1) $max_value = 100;
		if ($style) {
			$style = max(1, min(4, $style));
			$ORGANICS_GLOBALS['sc_skills_style'] = $style;
		}
		$ORGANICS_GLOBALS['sc_skills_max'] = $max_value;
		$ORGANICS_GLOBALS['sc_skills_dir'] = $dir;
		$ORGANICS_GLOBALS['sc_skills_height'] = organics_prepare_css_value($height);
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$content = do_shortcode($content);
		$output = '<div id="'.esc_attr($id).'"' 
					. ' class="sc_skills sc_skills_' . esc_attr($type) 
						. ($type=='bar' ? ' sc_skills_'.esc_attr($dir) : '') 
						. ($type=='pie' ? ' sc_skills_compact_'.esc_attr($pie_compact) : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
					. ' data-type="'.esc_attr($type).'"'
					. ' data-caption="'.esc_attr($arc_caption).'"'
					. ($type=='bar' ? ' data-dir="'.esc_attr($dir).'"' : '')
				. '>'
					. (!empty($subtitle) ? '<h6 class="sc_skills_subtitle sc_item_subtitle">' . esc_html($subtitle) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_skills_title sc_item_title">' . esc_html($title) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_skills_descr sc_item_descr">' . trim($description) . '</div>' : '')
					. ($layout == 'columns' ? '<div class="columns_wrap sc_skills_'.esc_attr($layout).' sc_skills_columns_'.esc_attr($columns).'">' : '')
					. ($type=='arc' 
						? ('<div class="sc_skills_legend">'.($ORGANICS_GLOBALS['sc_skills_legend']).'</div>'
							. '<div id="'.esc_attr($id).'_diagram" class="sc_skills_arc_canvas"></div>'
							. '<div class="sc_skills_data" style="display:none;">' . ($ORGANICS_GLOBALS['sc_skills_data']) . '</div>'
						  )
						: '')
					. ($type=='pie' && organics_param_is_on($pie_compact)
						? ('<div class="sc_skills_legend">'.($ORGANICS_GLOBALS['sc_skills_legend']).'</div>'
							. '<div id="'.esc_attr($id).'_pie" class="sc_skills_item">'
								. '<canvas id="'.esc_attr($id).'_pie" class="sc_skills_pie_canvas"></canvas>'
								. '<div class="sc_skills_data" style="display:none;">' . ($ORGANICS_GLOBALS['sc_skills_data']) . '</div>'
							. '</div>'
						  )
						: '')
					. ($content)
					. ($layout == 'columns' ? '</div>' : '')
					. (!empty($link) ? '<div class="sc_skills_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_skills', $atts, $content);
	}
	add_shortcode('trx_skills', 'organics_sc_skills');
}


if (!function_exists('organics_sc_skills_item')) {	
	function organics_sc_skills_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"value" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"style" => "",
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_skills_counter']++;
		$ed = organics_substr($value, -1)=='%' ? '%' : '';
		$value = str_replace('%', '', $value);
		if ($ORGANICS_GLOBALS['sc_skills_max'] < $value) $ORGANICS_GLOBALS['sc_skills_max'] = $value;
		$percent = round($value / $ORGANICS_GLOBALS['sc_skills_max'] * 100);
		$start = 0;
		$stop = $value;
		$steps = 100;
		$step = max(1, round($ORGANICS_GLOBALS['sc_skills_max']/$steps));
		$speed = mt_rand(10,40);
		$animation = round(($stop - $start) / $step * $speed);
		$title_block = '<div class="sc_skills_info"><div class="sc_skills_label">' . ($title) . '</div></div>';
		$old_color = $color;
		if (empty($color)) $color = $ORGANICS_GLOBALS['sc_skills_color'];
		if (empty($color)) $color = organics_get_scheme_color('accent1', $color);
		if (empty($bg_color)) $bg_color = $ORGANICS_GLOBALS['sc_skills_bg_color'];
		if (empty($bg_color)) $bg_color = organics_get_scheme_color('bg_color', $bg_color);
		if (empty($border_color)) $border_color = $ORGANICS_GLOBALS['sc_skills_border_color'];
		if (empty($border_color)) $border_color = organics_get_scheme_color('bd_color', $border_color);;
		if (empty($style)) $style = $ORGANICS_GLOBALS['sc_skills_style'];
		$style = max(1, min(4, $style));
		$output = '';
		if ($ORGANICS_GLOBALS['sc_skills_type'] == 'arc' || ($ORGANICS_GLOBALS['sc_skills_type'] == 'pie' && organics_param_is_on($ORGANICS_GLOBALS['sc_skills_pie_compact']))) {
			if ($ORGANICS_GLOBALS['sc_skills_type'] == 'arc' && empty($old_color)) {
				$rgb = organics_hex2rgb($color);
				$color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.(1 - 0.1*($ORGANICS_GLOBALS['sc_skills_counter']-1)).')';
			}
			$ORGANICS_GLOBALS['sc_skills_legend'] .= '<div class="sc_skills_legend_item"><span class="sc_skills_legend_marker" style="background-color:'.esc_attr($color).'"></span><span class="sc_skills_legend_title">' . ($title) . '</span><span class="sc_skills_legend_value">' . ($value) . '</span></div>';
			$ORGANICS_GLOBALS['sc_skills_data'] .= '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="'.esc_attr($ORGANICS_GLOBALS['sc_skills_type']).'"'
				. ($ORGANICS_GLOBALS['sc_skills_type']=='pie'
					? ( ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr($ORGANICS_GLOBALS['sc_skills_max']).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr($ORGANICS_GLOBALS['sc_skills_pie_cutout']).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'"'
						)
					: '')
				. '><input type="hidden" class="text" value="'.esc_attr($title).'" /><input type="hidden" class="percent" value="'.esc_attr($percent).'" /><input type="hidden" class="color" value="'.esc_attr($color).'" /></div>';
		} else {
			$output .= ($ORGANICS_GLOBALS['sc_skills_columns'] > 0 ? '<div class="sc_skills_column column-1_'.esc_attr($ORGANICS_GLOBALS['sc_skills_columns']).'">' : '')
					. ($ORGANICS_GLOBALS['sc_skills_type']=='bar' && $ORGANICS_GLOBALS['sc_skills_dir']=='horizontal' ? $title_block : '')
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.esc_attr($style) : '') 
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($ORGANICS_GLOBALS['sc_skills_counter'] % 2 == 1 ? ' odd' : ' even') 
							. ($ORGANICS_GLOBALS['sc_skills_counter'] == 1 ? ' first' : '') 
							. '"'
						. ($ORGANICS_GLOBALS['sc_skills_height'] !='' || $css ? ' style="height: '.esc_attr($ORGANICS_GLOBALS['sc_skills_height']).';'.($css).'"' : '')
					. '>'
					. (!empty($icon) ? '<div class="sc_skills_icon '.esc_attr($icon).'"></div>' : '');
			if (in_array($ORGANICS_GLOBALS['sc_skills_type'], array('bar', 'counter'))) {
				$output .= '<div class="sc_skills_count"' . ($ORGANICS_GLOBALS['sc_skills_type']=='bar' && $color ? ' style="background-color:' . esc_attr($color) . '; border-color:' . esc_attr($color) . '"' : '') . '>'
							. '<div class="sc_skills_total"'
								. ' data-start="'.esc_attr($start).'"'
								. ' data-stop="'.esc_attr($stop).'"'
								. ' data-step="'.esc_attr($step).'"'
								. ' data-max="'.esc_attr($ORGANICS_GLOBALS['sc_skills_max']).'"'
								. ' data-speed="'.esc_attr($speed).'"'
								. ' data-duration="'.esc_attr($animation).'"'
								. ' data-ed="'.esc_attr($ed).'">'
								. ($start) . ($ed)
							.'</div>'
						. '</div>';
			} else if ($ORGANICS_GLOBALS['sc_skills_type']=='pie') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<canvas id="'.esc_attr($id).'"></canvas>'
					. '<div class="sc_skills_total"'
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr($ORGANICS_GLOBALS['sc_skills_max']).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr($ORGANICS_GLOBALS['sc_skills_pie_cutout']).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>';
			}
			$output .= 
					  ($ORGANICS_GLOBALS['sc_skills_type']=='counter' ? $title_block : '')
					. '</div>'
					. ($ORGANICS_GLOBALS['sc_skills_type']=='bar' && $ORGANICS_GLOBALS['sc_skills_dir']=='vertical' || $ORGANICS_GLOBALS['sc_skills_type'] == 'pie' ? $title_block : '')
					. ($ORGANICS_GLOBALS['sc_skills_columns'] > 0 ? '</div>' : '');
		}
		return apply_filters('organics_shortcode_output', $output, 'trx_skills_item', $atts, $content);
	}
	add_shortcode('trx_skills_item', 'organics_sc_skills_item');
}
// ---------------------------------- [/trx_skills] ---------------------------------------






// ---------------------------------- [trx_slider] ---------------------------------------

/*
[trx_slider id="unique_id" engine="revo|royal|flex|swiper|chop" alias="revolution_slider_alias|royal_slider_id" titles="no|slide|fixed" cat="id|slug" count="posts_number" ids="comma_separated_id_list" offset="" width="" height="" align="" top="" bottom=""]
[trx_slider_item src="image_url"]
[/trx_slider]
*/

if (!function_exists('organics_sc_slider')) {	
	function organics_sc_slider($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"engine" => 'swiper',
			"custom" => "no",
			"alias" => "",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "0",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"controls" => "no",
			"pagination" => "no",
			"slides_space" => 0,
			"slides_per_view" => 1,
			"titles" => "no",
			"descriptions" => organics_get_custom_option('slider_info_descriptions'),
			"links" => "no",
			"align" => "",
			"interval" => "",
			"date_format" => "",
			"crop" => "yes",
			"autoheight" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($width) && $pagination!='full') $width = "100%";
		if (empty($height) && ($pagination=='full' || $pagination=='over')) $height = 250;
		if (!empty($height) && organics_param_is_on($autoheight)) $autoheight = "off";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		if (empty($custom)) $custom = 'no';
		if (empty($controls)) $controls = 'no';
		if (empty($pagination)) $pagination = 'no';
		if (empty($titles)) $titles = 'no';
		if (empty($links)) $links = 'no';
		if (empty($autoheight)) $autoheight = 'no';
		if (empty($crop)) $crop = 'no';

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_slider_engine'] = $engine;
		$ORGANICS_GLOBALS['sc_slider_width']  = organics_prepare_css_value($width);
		$ORGANICS_GLOBALS['sc_slider_height'] = organics_prepare_css_value($height);
		$ORGANICS_GLOBALS['sc_slider_links']  = organics_param_is_on($links);
		$ORGANICS_GLOBALS['sc_slider_bg_image'] = organics_get_theme_setting('slides_type')=='bg';
		$ORGANICS_GLOBALS['sc_slider_crop_image'] = $crop;
	
		if (empty($id)) $id = "sc_slider_".str_replace('.', '', mt_rand());
		
		$ms = organics_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = organics_get_css_position_from_values('', '', '', '', $width);
		$hs = organics_get_css_position_from_values('', '', '', '', '', $height);
	
		$css .= (!in_array($pagination, array('full', 'over')) ? $ms : '') . ($hs) . ($ws);
		
		if ($engine!='swiper' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';
		
		$output = (in_array($pagination, array('full', 'over')) 
					? '<div class="sc_slider_pagination_area sc_slider_pagination_'.esc_attr($pagination)
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
						. (($ms).($hs) ? ' style="'.esc_attr(($ms).($hs)).'"' : '') 
						.'>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_slider sc_slider_' . esc_attr($engine)
					. ($engine=='swiper' ? ' swiper-slider-container' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (organics_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
					. ($hs ? ' sc_slider_height_fixed' : '')
					. (organics_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
					. (organics_param_is_on($pagination) ? ' sc_slider_pagination' : ' sc_slider_nopagination')
					. ($ORGANICS_GLOBALS['sc_slider_bg_image'] ? ' sc_slider_bg' : ' sc_slider_images')
					. (!in_array($pagination, array('full', 'over')) && $align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. '"'
				. (!in_array($pagination, array('full', 'over')) && !organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($slides_per_view > 1 ? ' data-slides-per_view="' . esc_attr($slides_per_view) . '"' : '')
				. (!empty($width) && organics_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && organics_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>';
	
		organics_enqueue_slider($engine);
	
		if ($engine=='revo') {
			if (organics_exists_revslider() && !empty($alias))
				$output .= do_shortcode('[rev_slider '.esc_attr($alias).']');
			else
				$output = '';
		} else if ($engine=='swiper') {
			
			$caption = '';
	
			$output .= '<div class="slides'
				.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
				.($engine=='swiper' && $ORGANICS_GLOBALS['sc_slider_bg_image'] ? ' style="'.esc_attr($hs).'"' : '')
				.'>';
	
			$content = do_shortcode($content);
			
			if (organics_param_is_on($custom) && $content) {
				$output .= $content;
			} else {
				global $post;
		
				if (!empty($ids)) {
					$posts = explode(',', $ids);
					$count = count($posts);
				}
			
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $count,
					'ignore_sticky_posts' => true,
					'order' => $order=='asc' ? 'asc' : 'desc',
				);
		
				if ($offset > 0 && empty($ids)) {
					$args['offset'] = $offset;
				}
		
				$args = organics_query_add_sort_order($args, $orderby, $order);
				$args = organics_query_add_filters($args, 'thumbs');
				$args = organics_query_add_posts_and_cats($args, $ids, $post_type, $cat);
	
				$query = new WP_Query( $args );
	
				$post_number = 0;
				$pagination_items = '';
				$show_image 	= 1;
				$show_types 	= 0;
				$show_date 		= 1;
				$show_author 	= 0;
				$show_links 	= 0;
				$show_counters	= 'views';	//comments | rating
				
				while ( $query->have_posts() ) { 
					$query->the_post();
					$post_number++;
					$post_id = get_the_ID();
					$post_type = get_post_type();
					$post_title = get_the_title();
					$post_link = get_permalink();
					$post_date = get_the_date(!empty($date_format) ? $date_format : 'd.m.y');
					$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
					if (organics_param_is_on($crop)) {
						$post_attachment = $ORGANICS_GLOBALS['sc_slider_bg_image']
							? organics_get_resized_image_url($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null)
							: organics_get_resized_image_tag($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null);
					} else if (!$ORGANICS_GLOBALS['sc_slider_bg_image']) {
						$post_attachment = '<img src="'.esc_url($post_attachment).'" alt="">';
					}
					$post_accent_color = '';
					$post_category = '';
					$post_category_link = '';
	
					if (in_array($pagination, array('full', 'over'))) {
						$old_output = $output;
						$output = '';
						if (file_exists(organics_get_file_dir('templates/_parts/widgets-posts.php'))) {
							require organics_get_file_dir('templates/_parts/widgets-posts.php');
						}
						$pagination_items .= $output;
						$output = $old_output;
					}
					$output .= '<div' 
						. ' class="'.esc_attr($engine).'-slide"'
						. ' data-style="'.esc_attr(($ws).($hs)).'"'
						. ' style="'
							. ($ORGANICS_GLOBALS['sc_slider_bg_image'] ? 'background-image:url(' . esc_url($post_attachment) . ');' : '') . ($ws) . ($hs)
							. '"'
						. '>' 
						. (organics_param_is_on($links) ? '<a href="'.esc_url($post_link).'" title="'.esc_attr($post_title).'">' : '')
						. (!$ORGANICS_GLOBALS['sc_slider_bg_image'] ? $post_attachment : '')
						;
					$caption = $engine=='swiper' ? '' : $caption;
					if (!organics_param_is_off($titles)) {
						$post_hover_bg  = organics_get_scheme_color('accent1');
						$post_bg = '';
						if ($post_hover_bg!='' && !organics_is_inherit_option($post_hover_bg)) {
							$rgb = organics_hex2rgb($post_hover_bg);
							$post_hover_ie = str_replace('#', '', $post_hover_bg);
							$post_bg = "background-color: rgba({$rgb['r']},{$rgb['g']},{$rgb['b']},0.8);";
						}
						$caption .= '<div class="sc_slider_info' . ($titles=='fixed' ? ' sc_slider_info_fixed' : '') . ($engine=='swiper' ? ' content-slide' : '') . '"'.($post_bg!='' ? ' style="'.esc_attr($post_bg).'"' : '').'>';
						$post_descr = organics_get_post_excerpt();
						if (organics_get_custom_option("slider_info_category")=='yes') { // || empty($cat)) {
							// Get all post's categories
							$post_tax = organics_get_taxonomy_categories_by_post_type($post_type);
							if (!empty($post_tax)) {
								$post_terms = organics_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$post_tax));
								if (!empty($post_terms[$post_tax])) {
									if (!empty($post_terms[$post_tax]->closest_parent)) {
										$post_category = $post_terms[$post_tax]->closest_parent->name;
										$post_category_link = $post_terms[$post_tax]->closest_parent->link;
									}
									if ($post_category!='') {
										$caption .= '<div class="sc_slider_category"'.(organics_substr($post_accent_color, 0, 1)=='#' ? ' style="background-color: '.esc_attr($post_accent_color).'"' : '').'><a href="'.esc_url($post_category_link).'">'.($post_category).'</a></div>';
									}
								}
							}
						}
						$output_reviews = '';
						if (organics_get_custom_option('show_reviews')=='yes' && organics_get_custom_option('slider_info_reviews')=='yes') {
							$avg_author = organics_reviews_marks_to_display(get_post_meta($post_id, 'reviews_avg'.((organics_get_theme_option('reviews_first')=='author' && $orderby != 'users_rating') || $orderby == 'author_rating' ? '' : '2'), true));
							if ($avg_author > 0) {
								$output_reviews .= '<div class="sc_slider_reviews post_rating reviews_summary blog_reviews' . (organics_get_custom_option("slider_info_category")=='yes' ? ' after_category' : '') . '">'
									. '<div class="criteria_summary criteria_row">' . trim(organics_reviews_get_summary_stars($avg_author, false, false, 5)) . '</div>'
									. '</div>';
							}
						}
						if (organics_get_custom_option("slider_info_category")=='yes') $caption .= $output_reviews;
						$caption .= '<h3 class="sc_slider_subtitle"><a href="'.esc_url($post_link).'">'.($post_title).'</a></h3>';
						if (organics_get_custom_option("slider_info_category")!='yes') $caption .= $output_reviews;
						if ($descriptions > 0) {
							$caption .= '<div class="sc_slider_descr">'.trim(organics_strshort($post_descr, $descriptions)).'</div>';
						}
						$caption .= '</div>';
					}
					$output .= ($engine=='swiper' ? $caption : '') . (organics_param_is_on($links) ? '</a>' : '' ) . '</div>';
				}
				wp_reset_postdata();
			}
	
			$output .= '</div>';
			if ($engine=='swiper') {
				if (organics_param_is_on($controls))
					$output .= '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>';
				if (organics_param_is_on($pagination))
					$output .= '<div class="sc_slider_pagination_wrap"></div>';
			}
		
		} else
			$output = '';
		
		if (!empty($output)) {
			$output .= '</div>';
			if (!empty($pagination_items)) {
				$output .= '
					<div class="sc_slider_pagination widget_area"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
						<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
							<div class="sc_scroll_wrapper swiper-wrapper">
								<div class="sc_scroll_slide swiper-slide">
									'.($pagination_items).'
								</div>
							</div>
							<div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical"></div>
						</div>
					</div>';
				$output .= '</div>';
			}
		}
	
		return apply_filters('organics_shortcode_output', $output, 'trx_slider', $atts, $content);
	}
	add_shortcode('trx_slider', 'organics_sc_slider');
}


if (!function_exists('organics_sc_slider_item')) {	
	function organics_sc_slider_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"src" => "",
			"url" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $ORGANICS_GLOBALS;
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
	
		if ($src && organics_param_is_on($ORGANICS_GLOBALS['sc_slider_crop_image'])) {
			$src = $ORGANICS_GLOBALS['sc_slider_bg_image']
				? organics_get_resized_image_url($src, !empty($ORGANICS_GLOBALS['sc_slider_width']) && organics_strpos($ORGANICS_GLOBALS['sc_slider_width'], '%')===false ? $ORGANICS_GLOBALS['sc_slider_width'] : null, !empty($ORGANICS_GLOBALS['sc_slider_height']) && organics_strpos($ORGANICS_GLOBALS['sc_slider_height'], '%')===false ? $ORGANICS_GLOBALS['sc_slider_height'] : null)
				: organics_get_resized_image_tag($src, !empty($ORGANICS_GLOBALS['sc_slider_width']) && organics_strpos($ORGANICS_GLOBALS['sc_slider_width'], '%')===false ? $ORGANICS_GLOBALS['sc_slider_width'] : null, !empty($ORGANICS_GLOBALS['sc_slider_height']) && organics_strpos($ORGANICS_GLOBALS['sc_slider_height'], '%')===false ? $ORGANICS_GLOBALS['sc_slider_height'] : null);
		} else if ($src && !$ORGANICS_GLOBALS['sc_slider_bg_image']) {
			$src = '<img src="'.esc_url($src).'" alt="">';
		}
	
		$css .= ($ORGANICS_GLOBALS['sc_slider_bg_image'] ? 'background-image:url(' . esc_url($src) . ');' : '')
				. (!empty($ORGANICS_GLOBALS['sc_slider_width'])  ? 'width:'  . esc_attr($ORGANICS_GLOBALS['sc_slider_width'])  . ';' : '')
				. (!empty($ORGANICS_GLOBALS['sc_slider_height']) ? 'height:' . esc_attr($ORGANICS_GLOBALS['sc_slider_height']) . ';' : '');
	
		$content = do_shortcode($content);
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '').' class="'.esc_attr($ORGANICS_GLOBALS['sc_slider_engine']).'-slide' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				.'>' 
				. ($src && organics_param_is_on($ORGANICS_GLOBALS['sc_slider_links']) ? '<a href="'.esc_url($src).'">' : '')
				. ($src && !$ORGANICS_GLOBALS['sc_slider_bg_image'] ? $src : $content)
				. ($src && organics_param_is_on($ORGANICS_GLOBALS['sc_slider_links']) ? '</a>' : '')
			. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_slider_item', $atts, $content);
	}
	add_shortcode('trx_slider_item', 'organics_sc_slider_item');
}
// ---------------------------------- [/trx_slider] ---------------------------------------





// ---------------------------------- [trx_socials] ---------------------------------------

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('organics_sc_socials')) {	
	function organics_sc_socials($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => organics_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_social_icons'] = false;
		$ORGANICS_GLOBALS['sc_social_type'] = $type;
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? organics_get_socials_url($s[0]) : 'icon-'.$s[0],
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) $ORGANICS_GLOBALS['sc_social_icons'] = $list;
		} else if (organics_param_is_off($custom))
			$content = do_shortcode($content);
		if ($ORGANICS_GLOBALS['sc_social_icons']===false) $ORGANICS_GLOBALS['sc_social_icons'] = organics_get_custom_option('social_icons');
		$output = organics_prepare_socials($ORGANICS_GLOBALS['sc_social_icons']);
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('organics_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	add_shortcode('trx_socials', 'organics_sc_socials');
}


if (!function_exists('organics_sc_social_item')) {	
	function organics_sc_social_item($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		global $ORGANICS_GLOBALS;
		if (!empty($name) && empty($icon)) {
			$type = $ORGANICS_GLOBALS['sc_social_type'];
			if ($type=='images') {
				if (file_exists(organics_get_socials_dir($name.'.png')))
					$icon = organics_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if ($ORGANICS_GLOBALS['sc_social_icons']===false) $ORGANICS_GLOBALS['sc_social_icons'] = array();
			$ORGANICS_GLOBALS['sc_social_icons'][] = array(
				'icon' => $icon,
				'url' => $url
			);
		}
		return '';
	}
	add_shortcode('trx_social_item', 'organics_sc_social_item');
}
// ---------------------------------- [/trx_socials] ---------------------------------------





// ---------------------------------- [trx_table] ---------------------------------------

/*
[trx_table id="unique_id" style="1"]
Table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/
[/trx_table]
*/

if (!function_exists('organics_sc_table')) {	
	function organics_sc_table($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "100%"
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width);
		$content = str_replace(
					array('<p><table', 'table></p>', '><br />'),
					array('<table', 'table>', '>'),
					html_entity_decode($content, ENT_COMPAT, 'UTF-8'));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_table' 
					. (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				.'>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_table', $atts, $content);
	}
	add_shortcode('trx_table', 'organics_sc_table');
}
// ---------------------------------- [/trx_table] ---------------------------------------






// ---------------------------------- [trx_tabs] ---------------------------------------

/*
[trx_tabs id="unique_id" tab_names="Planning|Development|Support" style="1|2" initial="1 - num_tabs"]
	[trx_tab]Randomised words which don't look even slightly believable. If you are going to use a passage. You need to be sure there isn't anything embarrassing hidden in the middle of text established fact that a reader will be istracted by the readable content of a page when looking at its layout.[/trx_tab]
	[trx_tab]Fact reader will be distracted by the <a href="#" class="main_link">readable content</a> of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have evolved over. There are many variations of passages of Lorem Ipsum available, but the majority.[/trx_tab]
	[trx_tab]Distracted by the  readable content  of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have  evolved over.  There are many variations of passages of Lorem Ipsum available.[/trx_tab]
[/trx_tabs]
*/

if (!function_exists('organics_sc_tabs')) {	
	function organics_sc_tabs($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"scroll" => "no",
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width);
	
		if (!organics_param_is_off($scroll)) organics_enqueue_slider();
		if (empty($id)) $id = 'sc_tabs_'.str_replace('.', '', mt_rand());
	
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_tab_counter'] = 0;
		$ORGANICS_GLOBALS['sc_tab_scroll'] = $scroll;
		$ORGANICS_GLOBALS['sc_tab_height'] = organics_prepare_css_value($height);
		$ORGANICS_GLOBALS['sc_tab_id']     = $id;
		$ORGANICS_GLOBALS['sc_tab_titles'] = array();
	
		$content = do_shortcode($content);
	
		$sc_tab_titles = $ORGANICS_GLOBALS['sc_tab_titles'];
	
		$initial = max(1, min(count($sc_tab_titles), (int) $initial));
	
		$tabs_output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
							. ' class="sc_tabs sc_tabs_style_'.esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
							. ' data-active="' . ($initial-1) . '"'
							. '>'
						.'<ul class="sc_tabs_titles">';
		$titles_output = '';
		for ($i = 0; $i < count($sc_tab_titles); $i++) {
			$classes = array('sc_tabs_title');
			if ($i == 0) $classes[] = 'first';
			else if ($i == count($sc_tab_titles) - 1) $classes[] = 'last';
			$titles_output .= '<li class="'.join(' ', $classes).'">'
								. '<a href="#'.esc_attr($sc_tab_titles[$i]['id']).'" class="theme_button" id="'.esc_attr($sc_tab_titles[$i]['id']).'_tab">' . ($sc_tab_titles[$i]['title']) . '</a>'
								. '</li>';
		}
	
		wp_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
		wp_enqueue_script('jquery-effects-fade', false, array('jquery','jquery-effects-core'), null, true);
	
		$tabs_output .= $titles_output
			. '</ul>' 
			. ($content)
			.'</div>';
		return apply_filters('organics_shortcode_output', $tabs_output, 'trx_tabs', $atts, $content);
	}
	add_shortcode("trx_tabs", "organics_sc_tabs");
}


if (!function_exists('organics_sc_tab')) {	
	function organics_sc_tab($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"tab_id" => "",		// get it from VC
			"title" => "",		// get it from VC
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_tab_counter']++;
		if (empty($id))
			$id = !empty($tab_id) ? $tab_id : ($ORGANICS_GLOBALS['sc_tab_id']).'_'.($ORGANICS_GLOBALS['sc_tab_counter']);
		$sc_tab_titles = $ORGANICS_GLOBALS['sc_tab_titles'];
		if (isset($sc_tab_titles[$ORGANICS_GLOBALS['sc_tab_counter']-1])) {
			$sc_tab_titles[$ORGANICS_GLOBALS['sc_tab_counter']-1]['id'] = $id;
			if (!empty($title))
				$sc_tab_titles[$ORGANICS_GLOBALS['sc_tab_counter']-1]['title'] = $title;
		} else {
			$sc_tab_titles[] = array(
				'id' => $id,
				'title' => $title
			);
		}
		$ORGANICS_GLOBALS['sc_tab_titles'] = $sc_tab_titles;
		$output = '<div id="'.esc_attr($id).'"'
					.' class="sc_tabs_content' 
						. ($ORGANICS_GLOBALS['sc_tab_counter'] % 2 == 1 ? ' odd' : ' even') 
						. ($ORGANICS_GLOBALS['sc_tab_counter'] == 1 ? ' first' : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. '>' 
				. (organics_param_is_on($ORGANICS_GLOBALS['sc_tab_scroll']) 
					? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical" style="height:'.($ORGANICS_GLOBALS['sc_tab_height'] != '' ? $ORGANICS_GLOBALS['sc_tab_height'] : '200px').';"><div class="sc_scroll_wrapper swiper-wrapper"><div class="sc_scroll_slide swiper-slide">' 
					: '')
				. do_shortcode($content) 
				. (organics_param_is_on($ORGANICS_GLOBALS['sc_tab_scroll']) 
					? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical '.esc_attr($id).'_scroll_bar"></div></div>' 
					: '')
			. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_tab', $atts, $content);
	}
	add_shortcode("trx_tab", "organics_sc_tab");
}
// ---------------------------------- [/trx_tabs] ---------------------------------------





// ---------------------------------- [trx_title] ---------------------------------------

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('organics_sc_title')) {	
	function organics_sc_title($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width)
			.($align && $align!='none' && !organics_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !organics_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(organics_strpos($image, 'http:')!==false ? $image : organics_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !organics_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('organics_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	add_shortcode('trx_title', 'organics_sc_title');
}
// ---------------------------------- [/trx_title] ---------------------------------------






// ---------------------------------- [trx_toggles] ---------------------------------------

if (!function_exists('organics_sc_toggles')) {	
	function organics_sc_toggles($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_toggle_counter'] = 0;
		$ORGANICS_GLOBALS['sc_toggle_style']   = max(1, min(2, $style));
		$ORGANICS_GLOBALS['sc_toggle_show_counter'] = organics_param_is_on($counter);
		$ORGANICS_GLOBALS['sc_toggles_icon_closed'] = empty($icon_closed) || organics_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed;
		$ORGANICS_GLOBALS['sc_toggles_icon_opened'] = empty($icon_opened) || organics_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened;
		wp_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_toggles sc_toggles_style_'.esc_attr($style)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (organics_param_is_on($counter) ? ' sc_show_counter' : '') 
					. '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_toggles', $atts, $content);
	}
	add_shortcode('trx_toggles', 'organics_sc_toggles');
}


if (!function_exists('organics_sc_toggles_item')) {	
	function organics_sc_toggles_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"open" => "",
			"icon_closed" => "",
			"icon_opened" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_toggle_counter']++;
		if (empty($icon_closed) || organics_param_is_inherit($icon_closed)) $icon_closed = $ORGANICS_GLOBALS['sc_toggles_icon_closed'] ? $ORGANICS_GLOBALS['sc_toggles_icon_closed'] : "icon-plus";
		if (empty($icon_opened) || organics_param_is_inherit($icon_opened)) $icon_opened = $ORGANICS_GLOBALS['sc_toggles_icon_opened'] ? $ORGANICS_GLOBALS['sc_toggles_icon_opened'] : "icon-minus";
		$css .= organics_param_is_on($open) ? 'display:block;' : '';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_toggles_item'.(organics_param_is_on($open) ? ' sc_active' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($ORGANICS_GLOBALS['sc_toggle_counter'] % 2 == 1 ? ' odd' : ' even') 
					. ($ORGANICS_GLOBALS['sc_toggle_counter'] == 1 ? ' first' : '')
					. '">'
					. '<h5 class="sc_toggles_title'.(organics_param_is_on($open) ? ' ui-state-active' : '').'">'
					. (!organics_param_is_off($icon_closed) ? '<span class="sc_toggles_icon sc_toggles_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
					. (!organics_param_is_off($icon_opened) ? '<span class="sc_toggles_icon sc_toggles_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
					. ($ORGANICS_GLOBALS['sc_toggle_show_counter'] ? '<span class="sc_items_counter">'.($ORGANICS_GLOBALS['sc_toggle_counter']).'</span>' : '')
					. ($title) 
					. '</h5>'
					. '<div class="sc_toggles_content"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						.'>' 
						. do_shortcode($content) 
					. '</div>'
				. '</div>';
		return apply_filters('organics_shortcode_output', $output, 'trx_toggles_item', $atts, $content);
	}
	add_shortcode('trx_toggles_item', 'organics_sc_toggles_item');
}
// ---------------------------------- [/trx_toggles] ---------------------------------------





// ---------------------------------- [trx_tooltip] ---------------------------------------

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('organics_sc_tooltip')) {	
	function organics_sc_tooltip($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('organics_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	add_shortcode('trx_tooltip', 'organics_sc_tooltip');
}
// ---------------------------------- [/trx_tooltip] ---------------------------------------






// ---------------------------------- [trx_twitter] ---------------------------------------

/*
[trx_twitter id="unique_id" user="username" consumer_key="" consumer_secret="" token_key="" token_secret=""]
*/

if (!function_exists('organics_sc_twitter')) {	
	function organics_sc_twitter($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"user" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			"count" => "3",
			"controls" => "yes",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		$twitter_username = $user ? $user : organics_get_theme_option('twitter_username');
		$twitter_consumer_key = $consumer_key ? $consumer_key : organics_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = $consumer_secret ? $consumer_secret : organics_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = $token_key ? $token_key : organics_get_theme_option('twitter_token_key');
		$twitter_token_secret = $token_secret ? $token_secret : organics_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, $count ? $count : intval(organics_get_theme_option('twitter_count')));
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && organics_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = organics_get_scheme_color('bg');
			$rgb = organics_hex2rgb($bg_color);
		}
		
		$ms = organics_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = organics_get_css_position_from_values('', '', '', '', $width);
		$hs = organics_get_css_position_from_values('', '', '', '', '', $height);
	
		$css .= ($ms) . ($hs) . ($ws);
	
		$output = '';
	
		if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret) && !empty($twitter_token_key) && !empty($twitter_token_secret)) {
			$data = organics_get_twitter_data(array(
				'mode'            => 'user_timeline',
				'consumer_key'    => $twitter_consumer_key,
				'consumer_secret' => $twitter_consumer_secret,
				'token'           => $twitter_token_key,
				'secret'          => $twitter_token_secret
				)
			);
			if ($data && isset($data[0]['text'])) {
				organics_enqueue_slider('swiper');
				$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || organics_strlen($bg_texture)>2 || ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme))
						? '<div class="sc_twitter_wrap sc_section'
								. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
								. ($align && $align!='none' && !organics_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							.' style="'
								. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
								. ($bg_image !== '' ? 'background-image:url('.esc_url($bg_image).');' : '')
								. '"'
							. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
							. '>'
							. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
									. ' style="' 
										. ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
										. (organics_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
										. '"'
										. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
										. '>' 
						: '')
						. '<div class="sc_twitter sc_slider_swiper sc_slider_nopagination swiper-slider-container'
								. (organics_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
								. (organics_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
								. ($hs ? ' sc_slider_height_fixed' : '')
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && $align && $align!='none' && !organics_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
							. (!empty($width) && organics_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
							. (!empty($height) && organics_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
							. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
							. '>'
							. '<div class="slides swiper-wrapper">';
				$cnt = 0;
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $tweet) {
						if (organics_substr($tweet['text'], 0, 1)=='@') continue;
							$output .= '<div class="swiper-slide" data-style="'.esc_attr(($ws).($hs)).'" style="'.esc_attr(($ws).($hs)).'">'
										. '<div class="sc_twitter_item">'
											. '<span class="sc_twitter_icon icon-twitter"></span>'
											. '<div class="sc_twitter_content">'
												. '<a href="' . esc_url('https://twitter.com/'.($twitter_username)).'" class="sc_twitter_author" target="_blank">@' . esc_html($tweet['user']['screen_name']) . '</a> '
												. force_balance_tags(organics_prepare_twitter_text($tweet))
											. '</div>'
										. '</div>'
									. '</div>';
						if (++$cnt >= $twitter_count) break;
					}
				}
				$output .= '</div>'
						. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
					. '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || organics_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
			}
		}
		return apply_filters('organics_shortcode_output', $output, 'trx_twitter', $atts, $content);
	}
	add_shortcode('trx_twitter', 'organics_sc_twitter');
}
// ---------------------------------- [/trx_twitter] ---------------------------------------

						


// ---------------------------------- [trx_video] ---------------------------------------

//[trx_video id="unique_id" url="http://player.vimeo.com/video/20245032?title=0&amp;byline=0&amp;portrait=0" width="" height=""]

if (!function_exists('organics_sc_video')) {	
	function organics_sc_video($atts, $content = null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"url" => '',
			"src" => '',
			"image" => '',
			"ratio" => '16:9',
			"autoplay" => 'off',
			"align" => '',
			"bg_image" => '',
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($autoplay)) $autoplay = 'off';
		
		$ratio = empty($ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $ratio);
		$ratio_parts = explode(':', $ratio);
		if (empty($height) && empty($width)) {
			$width='100%';
			if (organics_param_is_off(organics_get_custom_option('substitute_video'))) $height="400";
		}
		$ed = organics_substr($width, -1);
		if (empty($height) && !empty($width) && $ed!='%') {
			$height = round($width / $ratio_parts[0] * $ratio_parts[1]);
		}
		if (!empty($height) && empty($width)) {
			$width = round($height * $ratio_parts[0] / $ratio_parts[1]);
		}
		$css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
		$css_dim = organics_get_css_position_from_values('', '', '', '', $width, $height);
		$css_bg = organics_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
	
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		$url = $src!='' ? $src : $url;
		if ($image!='' && organics_param_is_off($image))
			$image = '';
		else {
			if (organics_param_is_on($autoplay) && is_singular() && !organics_get_global('blog_streampage'))
				$image = '';
			else {
				if ($image > 0) {
					$attach = wp_get_attachment_image_src( $image, 'full' );
					if (isset($attach[0]) && $attach[0]!='')
						$image = $attach[0];
				}
				if ($bg_image) {
					$thumb_sizes = organics_get_thumb_sizes(array(
						'layout' => 'grid_3'
					));
					if (!is_single() || !empty($image)) $image = organics_get_resized_image_url(empty($image) ? get_the_ID() : $image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, false);
				} else
					if (!is_single() || !empty($image)) $image = organics_get_resized_image_url(empty($image) ? get_the_ID() : $image, $ed!='%' ? $width : null, $height);
				if (empty($image) && (!is_singular() || organics_get_global('blog_streampage')))	// || organics_param_is_off($autoplay)))
					$image = organics_get_video_cover_image($url);
			}
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
	
		$url = organics_get_video_player_url($src!='' ? $src : $url);
		
		$video = '<video' . ($id ? ' id="' . esc_attr($id) . '"' : '') 
			. ' class="sc_video'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. ' src="' . esc_url($url) . '"'
			. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
			. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
			. ' data-ratio="'.esc_attr($ratio).'"'
			. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
			. ($bg_image ? ' data-bg-image="'.esc_attr($bg_image).'"' : '') 
			. ($css_bg!='' ? ' data-style="'.esc_attr($css_bg).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (($image && organics_param_is_on(organics_get_custom_option('substitute_video'))) || (organics_param_is_on($autoplay) && is_singular() && !organics_get_global('blog_streampage')) ? ' autoplay="autoplay"' : '') 
			. ' controls="controls" loop="loop"'
			. '>'
			. '</video>';
		if (organics_param_is_off(organics_get_custom_option('substitute_video'))) {
			if (organics_param_is_on($frame)) $video = organics_get_video_frame($video, $image, $css, $css_bg);
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$video = organics_substitute_video($video, $width, $height, false);
			}
		}
		if (organics_get_theme_option('use_mediaelement')=='yes')
			wp_enqueue_script('wp-mediaelement');
		return apply_filters('organics_shortcode_output', $video, 'trx_video', $atts, $content);
	}
	add_shortcode("trx_video", "organics_sc_video");
}
// ---------------------------------- [/trx_video] ---------------------------------------






// ---------------------------------- [trx_zoom] ---------------------------------------

/*
[trx_zoom id="unique_id" border="none|light|dark"]
*/

if (!function_exists('organics_sc_zoom')) {	
	function organics_sc_zoom($atts, $content=null){	
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"effect" => "zoom",
			"src" => "",
			"url" => "",
			"over" => "",
			"align" => "",
			"bg_image" => "",
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		wp_enqueue_script( 'organics-elevate-zoom-script', organics_get_file_url('js/jquery.elevateZoom-3.0.4.js'), array(), null, true );
	
		$css .= organics_get_css_position_from_values('!'.($top), '!'.($right), '!'.($bottom), '!'.($left));
		$css_dim = organics_get_css_position_from_values('', '', '', '', $width, $height);
		$css_bg = organics_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
		$width  = organics_prepare_css_value($width);
		$height = organics_prepare_css_value($height);
		if (empty($id)) $id = 'sc_zoom_'.str_replace('.', '', mt_rand());
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if ($over > 0) {
			$attach = wp_get_attachment_image_src( $over, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$over = $attach[0];
		}
		if ($effect=='lens' && ((int) $width > 0 && organics_substr($width, -2, 2)=='px') || ((int) $height > 0 && organics_substr($height, -2, 2)=='px')) {
			if ($src)
				$src = organics_get_resized_image_url($src, (int) $width > 0 && organics_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && organics_substr($height, -2, 2)=='px' ? (int) $height : null);
			if ($over)
				$over = organics_get_resized_image_url($over, (int) $width > 0 && organics_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && organics_substr($height, -2, 2)=='px' ? (int) $height : null);
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
		$output = empty($src) 
				? '' 
				: (
					(!empty($bg_image) 
						? '<div class="sc_zoom_wrap'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
								. '"'
							. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
							. ($css_bg!='' ? ' style="'.esc_attr($css_bg).'"' : '') 
							. '>' 
						: '')
					.'<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_zoom' 
								. (empty($bg_image) && !empty($class) ? ' '.esc_attr($class) : '') 
								. (empty($bg_image) && $align && $align!='none' ? ' align'.esc_attr($align) : '')
								. '"'
							. (empty($bg_image) && !organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. '>'
							. '<img src="'.esc_url($src).'"' . ($css_dim!='' ? ' style="'.esc_attr($css_dim).'"' : '') . ' data-zoom-image="'.esc_url($over).'" alt="" />'
					. '</div>'
					. (!empty($bg_image) 
						? '</div>' 
						: '')
				);
		return apply_filters('organics_shortcode_output', $output, 'trx_zoom', $atts, $content);
	}
	add_shortcode('trx_zoom', 'organics_sc_zoom');
}


// ---------------------------------- [/trx_zoom] ---------------------------------------



// ---------------------------------- [trx_axiomthemes_recent_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_recent_products')) {
    function organics_sc_axiomthemes_recent_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "width" => "",
            "height" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_recent_products'
            . ($class ? ' ' . esc_attr($class) : '')
            . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
            . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
            . '"'
            .'>'
            . (organics_param_is_on($scroll)
                ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                . '>'
                . '<div class="sc_scroll_wrapper swiper-wrapper">'
                . '<div class="sc_scroll_slide swiper-slide">'
                : '')

            . do_shortcode('[recent_products per_page=" '. $per_page .' " columns="' . $columns .'" orderby="' . $orderby .'" order="' . $order .'"]')

            . (organics_param_is_on($scroll)
                ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                : '')
            . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_recent_products', $atts, $content);
    }

    add_shortcode('trx_axiomthemes_recent_products', 'organics_sc_axiomthemes_recent_products');

}
// ---------------------------------- [/trx_axiomthemes_recent_products] ---------------------------------------




// ---------------------------------- [trx_axiomthemes_featured_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_featured_products')) {
    function organics_sc_axiomthemes_featured_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "width" => "",
            "height" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_featured_products'
            . ($class ? ' ' . esc_attr($class) : '')
            . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
            . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
            . '"'
            .'>'
            . (organics_param_is_on($scroll)
                ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                . '>'
                . '<div class="sc_scroll_wrapper swiper-wrapper">'
                . '<div class="sc_scroll_slide swiper-slide">'
                : '')

            . do_shortcode('[featured_products per_page=" '. $per_page .' " columns="' . $columns .'" orderby="' . $orderby .'" order="' . $order .'"]')

            . (organics_param_is_on($scroll)
                ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                : '')
            . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_featured_products', $atts, $content);
    }

    add_shortcode('trx_axiomthemes_featured_products', 'organics_sc_axiomthemes_featured_products');

}
// ---------------------------------- [/trx_axiomthemes_featured_products] ---------------------------------------




// ---------------------------------- [trx_axiomthemes_best_selling_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_best_selling_products')) {
    function organics_sc_axiomthemes_best_selling_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "only" => "no",
            "orderby" => "date",
            "order" => "desc",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "width" => "",
            "height" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_best_selling_products'
            . ($class ? ' ' . esc_attr($class) : '')
            . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
            . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
            . '"'
            .'>'
            . (organics_param_is_on($scroll)
                ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                . '>'
                . '<div class="sc_scroll_wrapper swiper-wrapper">'
                . '<div class="sc_scroll_slide swiper-slide">'
                : '')

            . do_shortcode('[best_selling_products per_page=" '. $per_page .' " columns="' . $columns .'" orderby="' . $orderby .'" order="' . $order .'"]')

            . (organics_param_is_on($scroll)
                ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                : '')
            . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_best_selling_products', $atts, $content);
    }

    add_shortcode('trx_axiomthemes_best_selling_products', 'organics_sc_axiomthemes_best_selling_products');

}
// ---------------------------------- [/trx_axiomthemes_best_selling_products] ---------------------------------------





// ---------------------------------- [trx_axiomthemes_sale_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_sale_products')) {
    function organics_sc_axiomthemes_sale_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_sale_products'
            . ($class ? ' ' . esc_attr($class) : '')
            . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
            . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
            . '"'
            .'>'
            . (organics_param_is_on($scroll)
                ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                . '>'
                . '<div class="sc_scroll_wrapper swiper-wrapper">'
                . '<div class="sc_scroll_slide swiper-slide">'
                : '')

            . do_shortcode('[sale_products per_page=" '. $per_page .' " columns="' . $columns .'"]')

            . (organics_param_is_on($scroll)
                ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                : '')
            . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_sale_products', $atts, $content);
    }

    add_shortcode('trx_axiomthemes_sale_products', 'organics_sc_axiomthemes_sale_products');

}
// ---------------------------------- [/trx_axiomthemes_sale_products] ---------------------------------------





// ---------------------------------- [trx_axiomthemes_top_rated_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_top_rated_products')) {
    function organics_sc_axiomthemes_top_rated_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_top_rated_products'
            . ($class ? ' ' . esc_attr($class) : '')
            . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
            . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
            . '"'
            .'>'
            . (organics_param_is_on($scroll)
                ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                . '>'
                . '<div class="sc_scroll_wrapper swiper-wrapper">'
                . '<div class="sc_scroll_slide swiper-slide">'
                : '')

            . do_shortcode('[top_rated_products per_page=" '. $per_page .' " columns="' . $columns .'" orderby="' . $orderby .'" order="' . $order .'"]')

            . (organics_param_is_on($scroll)
                ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                : '')
            . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_top_rated_products', $atts, $content);
    }

    add_shortcode('trx_axiomthemes_top_rated_products', 'organics_sc_axiomthemes_top_rated_products');

}
// ---------------------------------- [/trx_axiomthemes_top_rated_products] ---------------------------------------


// THEME SHORTCODES =======================





// ---------------------------------- [trx_clients] ---------------------------------------

/*
[trx_clients id="unique_id" columns="3" style="clients-1|clients-2|..."]
	[trx_clients_item name="client name" position="director" image="url"]Description text[/trx_clients_item]
	...
[/trx_clients]
*/
if ( !function_exists( 'organics_sc_clients' ) ) {
	function organics_sc_clients($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "clients-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'trx_utils'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_clients_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && organics_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$ms = organics_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = organics_get_css_position_from_values('', '', '', '', $width);
		$hs = organics_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		if (organics_param_is_on($slider)) organics_enqueue_slider('swiper');

		$columns = max(1, min(12, $columns));
		$count = max(1, (int) $count);
		if (organics_param_is_off($custom) && $count < $columns) $columns = $count;
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_clients_id'] = $id;
		$ORGANICS_GLOBALS['sc_clients_style'] = $style;
		$ORGANICS_GLOBALS['sc_clients_counter'] = 0;
		$ORGANICS_GLOBALS['sc_clients_columns'] = $columns;
		$ORGANICS_GLOBALS['sc_clients_slider'] = $slider;
		$ORGANICS_GLOBALS['sc_clients_css_wh'] = $ws . $hs;

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
			. ' class="sc_clients_wrap'
			. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
			.'">'
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_clients sc_clients_style_'.esc_attr($style)
			. ' ' . esc_attr(organics_get_template_property($style, 'container_classes'))
			. ' ' . esc_attr(organics_get_slider_controls_classes($controls))
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (organics_param_is_on($slider)
				? ' sc_slider_swiper swiper-slider-container'
				. (organics_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
				. ($hs ? ' sc_slider_height_fixed' : '')
				: '')
			.'"'
			. (!empty($width) && organics_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
			. (!empty($height) && organics_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
			. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
			. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
			. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
			. ($style!='clients-3' ? ' data-slides-min-width="150"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_clients_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_clients_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_clients_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
			. (organics_param_is_on($slider)
				? '<div class="slides swiper-wrapper">'
				: ($columns > 1
					? '<div class="sc_columns columns_wrap">'
					: '')
			);

		$content = do_shortcode($content);

		if (organics_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;

			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}

			$args = array(
				'post_type' => 'clients',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);

			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}

			$args = organics_query_add_sort_order($args, $orderby, $order);
			$args = organics_query_add_posts_and_cats($args, $ids, 'clients', $cat, 'clients_group');

			$query = new WP_Query( $args );

			$post_number = 0;

			while ( $query->have_posts() ) {
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => organics_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = organics_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], 'post_custom_options', true);
				$thumb_sizes = organics_get_thumb_sizes(array('layout' => $style));
				$args['client_name'] = $post_meta['client_name'];
				$args['client_position'] = $post_meta['client_position'];
				$args['client_image'] = $post_data['post_thumb'];
				$args['client_link'] = organics_param_is_on('client_show_link')
					? (!empty($post_meta['client_link']) ? $post_meta['client_link'] : $post_data['post_link'])
					: '';
				$output .= organics_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}

		if (organics_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_clients_button sc_item_button">'.organics_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
			. '</div><!-- /.sc_clients -->'
			. '</div><!-- /.sc_clients_wrap -->';

		// Add template specific scripts and styles
		do_action('organics_action_blog_scripts', $style);

		return apply_filters('organics_shortcode_output', $output, 'trx_clients', $atts, $content);
	}
	add_shortcode('trx_clients', 'organics_sc_clients');
}


if ( !function_exists( 'organics_sc_clients_item' ) ) {
	function organics_sc_clients_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"name" => "",
			"position" => "",
			"image" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_clients_counter']++;

		$id = $id ? $id : ($ORGANICS_GLOBALS['sc_clients_id'] ? $ORGANICS_GLOBALS['sc_clients_id'] . '_' . $ORGANICS_GLOBALS['sc_clients_counter'] : '');

		$descr = trim(chop(do_shortcode($content)));

		$thumb_sizes = organics_get_thumb_sizes(array('layout' => $ORGANICS_GLOBALS['sc_clients_style']));

		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$image = organics_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);

		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => $ORGANICS_GLOBALS['sc_clients_style'],
			'number' => $ORGANICS_GLOBALS['sc_clients_counter'],
			'columns_count' => $ORGANICS_GLOBALS['sc_clients_columns'],
			'slider' => $ORGANICS_GLOBALS['sc_clients_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $ORGANICS_GLOBALS['sc_clients_css_wh'],
			'client_position' => $position,
			'client_link' => $link,
			'client_image' => $image
		);
		$output = organics_show_post_layout($args, $post_data);
		return apply_filters('organics_shortcode_output', $output, 'trx_clients_item', $atts, $content);
	}
	add_shortcode('trx_clients_item', 'organics_sc_clients_item');
}
// ---------------------------------- [/trx_clients] ---------------------------------------



// Add [trx_clients] and [trx_clients_item] in the shortcodes list
if (!function_exists('organics_clients_reg_shortcodes')) {
	//Handler of add_filter('organics_action_shortcodes_list',	'organics_clients_reg_shortcodes');
	function organics_clients_reg_shortcodes() {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['shortcodes'])) {

			$users = organics_get_list_users();
			$members = organics_get_list_posts(false, array(
					'post_type'=>'clients',
					'orderby'=>'title',
					'order'=>'asc',
					'return'=>'title'
				)
			);
			$clients_groups = organics_get_list_terms(false, 'clients_group');
			$clients_styles = organics_get_list_templates('clients');
			$controls 		= organics_get_list_slider_controls();

			organics_array_insert_after($ORGANICS_GLOBALS['shortcodes'], 'trx_chat', array(

				// Clients
				"trx_clients" => array(
					"title" => esc_html__("Clients", 'trx_utils'),
					"desc" => wp_kses( __("Insert clients list in your page (post)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'trx_utils'),
							"desc" => wp_kses( __("Title for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'trx_utils'),
							"desc" => wp_kses( __("Subtitle for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'trx_utils'),
							"desc" => wp_kses( __("Short description for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Clients style", 'trx_utils'),
							"desc" => wp_kses( __("Select style to display clients list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "clients-1",
							"type" => "select",
							"options" => $clients_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_utils'),
							"desc" => wp_kses( __("How many columns use to show clients", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'trx_utils'),
							"desc" => wp_kses( __("Select color scheme for this block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'trx_utils'),
							"desc" => wp_kses( __("Use slider to show clients", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'trx_utils'),
							"desc" => wp_kses( __("Slider controls style and position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'trx_utils'),
							"desc" => wp_kses( __("Size of space (in px) between slides", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'trx_utils'),
							"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'trx_utils'),
							"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'trx_utils'),
							"desc" => wp_kses( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'trx_utils'),
							"desc" => wp_kses( __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => organics_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $clients_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'trx_utils'),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'trx_utils'),
							"desc" => wp_kses( __("Skip posts before select next part.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'trx_utils'),
							"desc" => wp_kses( __("Select desired posts sorting method", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_utils'),
							"desc" => wp_kses( __("Select desired posts order", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_utils'),
							"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'trx_utils'),
							"desc" => wp_kses( __("Link URL for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'trx_utils'),
							"desc" => wp_kses( __("Caption for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
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
					),
					"children" => array(
						"name" => "trx_clients_item",
						"title" => esc_html__("Client", 'trx_utils'),
						"desc" => wp_kses( __("Single client (custom parameters)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"name" => array(
								"title" => esc_html__("Name", 'trx_utils'),
								"desc" => wp_kses( __("Client's name", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", 'trx_utils'),
								"desc" => wp_kses( __("Client's position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'trx_utils'),
								"desc" => wp_kses( __("Link on client's personal page", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"image" => array(
								"title" => esc_html__("Image", 'trx_utils'),
								"desc" => wp_kses( __("Client's image", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'trx_utils'),
								"desc" => wp_kses( __("Client's short description", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_clients] and [trx_clients_item] in the VC shortcodes list
if (!function_exists('organics_clients_reg_shortcodes_vc')) {
	//Handler of add_filter('organics_action_shortcodes_list_vc',	'organics_clients_reg_shortcodes_vc');
	function organics_clients_reg_shortcodes_vc() {
		global $ORGANICS_GLOBALS;

		$clients_groups = organics_get_list_terms(false, 'clients_group');
		$clients_styles = organics_get_list_templates('clients');
		$controls		= organics_get_list_slider_controls();

		// Clients
		vc_map( array(
			"base" => "trx_clients",
			"name" => esc_html__("Clients", 'trx_utils'),
			"description" => wp_kses( __("Insert clients list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'trx_utils'),
			'icon' => 'icon_trx_clients',
			"class" => "trx_sc_columns trx_sc_clients",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_clients_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Clients style", 'trx_utils'),
					"description" => wp_kses( __("Select style to display clients list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip($clients_styles),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'trx_utils'),
					"description" => wp_kses( __("Select color scheme for this block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "slider",
					"heading" => esc_html__("Slider", 'trx_utils'),
					"description" => wp_kses( __("Use slider to show testimonials", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					"class" => "",
					"std" => "no",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['yes_no']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'trx_utils'),
					"description" => wp_kses( __("Slider controls style and position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"std" => "no",
					"value" => array_flip($controls),
					"type" => "dropdown"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Space between slides", 'trx_utils'),
					"description" => wp_kses( __("Size of space (in px) between slides", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Slides change interval", 'trx_utils'),
					"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", 'trx_utils'),
					"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom", 'trx_utils'),
					"description" => wp_kses( __("Allow get clients from inner shortcodes (custom) or get it from specified group (cat)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Custom clients" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'trx_utils'),
					"description" => wp_kses( __("Title for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'trx_utils'),
					"description" => wp_kses( __("Subtitle for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'trx_utils'),
					"description" => wp_kses( __("Description for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories", 'trx_utils'),
					"description" => wp_kses( __("Select category to show clients. If empty - select clients from any category (group) or from IDs list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip(organics_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $clients_groups)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns", 'trx_utils'),
					"description" => wp_kses( __("How many columns use to show clients", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					"admin_label" => true,
					"class" => "",
					"value" => "4",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Number of posts", 'trx_utils'),
					"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "4",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Offset before select posts", 'trx_utils'),
					"description" => wp_kses( __("Skip posts before select next part.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Post sorting", 'trx_utils'),
					"description" => wp_kses( __("Select desired posts sorting method", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sorting']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Post order", 'trx_utils'),
					"description" => wp_kses( __("Select desired posts order", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("client's IDs list", 'trx_utils'),
					"description" => wp_kses( __("Comma separated list of client's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'trx_utils'),
					"description" => wp_kses( __("Link URL for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'trx_utils'),
					"description" => wp_kses( __("Caption for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				$ORGANICS_GLOBALS['vc_params']['margin_top'],
				$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
				$ORGANICS_GLOBALS['vc_params']['margin_left'],
				$ORGANICS_GLOBALS['vc_params']['margin_right'],
				$ORGANICS_GLOBALS['vc_params']['id'],
				$ORGANICS_GLOBALS['vc_params']['class'],
				$ORGANICS_GLOBALS['vc_params']['animation'],
				$ORGANICS_GLOBALS['vc_params']['css']
			),
			'js_view' => 'VcTrxColumnsView'
		) );


		vc_map( array(
			"base" => "trx_clients_item",
			"name" => esc_html__("Client", 'trx_utils'),
			"description" => wp_kses( __("Client - all data pull out from it account on your site", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item trx_sc_clients_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_clients_item',
			"as_child" => array('only' => 'trx_clients'),
			"as_parent" => array('except' => 'trx_clients'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Name", 'trx_utils'),
					"description" => wp_kses( __("Client's name", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Position", 'trx_utils'),
					"description" => wp_kses( __("Client's position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'trx_utils'),
					"description" => wp_kses( __("Link on client's personal page", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Client's image", 'trx_utils'),
					"description" => wp_kses( __("Clients's image", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				$ORGANICS_GLOBALS['vc_params']['id'],
				$ORGANICS_GLOBALS['vc_params']['class'],
				$ORGANICS_GLOBALS['vc_params']['animation'],
				$ORGANICS_GLOBALS['vc_params']['css']
			),
			'js_view' => 'VcTrxColumnItemView'
		) );

		class WPBakeryShortCode_Trx_Clients extends ORGANICS_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Clients_Item extends ORGANICS_VC_ShortCodeCollection {}

	}
}





// ---------------------------------- [trx_team] ---------------------------------------

/*
[trx_team id="unique_id" columns="3" style="team-1|team-2|..."]
	[trx_team_item user="user_login"]
	[trx_team_item member="member_id"]
	[trx_team_item name="team member name" photo="url" email="address" position="director"]
[/trx_team]
*/
if ( !function_exists( 'organics_sc_team' ) ) {
	function organics_sc_team($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "team-1",
			"slider" => "no",
			"controls" => "no",
			"slides_space" => 0,
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 3,
			"columns" => 3,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'trx_utils'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_team_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && organics_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$ms = organics_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = organics_get_css_position_from_values('', '', '', '', $width);
		$hs = organics_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (organics_param_is_off($custom) && $count < $columns) $columns = $count;

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_team_id'] = $id;
		$ORGANICS_GLOBALS['sc_team_style'] = $style;
		$ORGANICS_GLOBALS['sc_team_columns'] = $columns;
		$ORGANICS_GLOBALS['sc_team_counter'] = 0;
		$ORGANICS_GLOBALS['sc_team_slider'] = $slider;
		$ORGANICS_GLOBALS['sc_team_css_wh'] = $ws . $hs;

		if (organics_param_is_on($slider)) organics_enqueue_slider('swiper');

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
			. ' class="sc_team_wrap'
			. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
			.'">'
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_team sc_team_style_'.esc_attr($style)
			. ' ' . esc_attr(organics_get_template_property($style, 'container_classes'))
			. ' ' . esc_attr(organics_get_slider_controls_classes($controls))
			. (organics_param_is_on($slider)
				? ' sc_slider_swiper swiper-slider-container'
				. (organics_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
				. ($hs ? ' sc_slider_height_fixed' : '')
				: '')
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
			.'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. (!empty($width) && organics_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
			. (!empty($height) && organics_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
			. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
			. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
			. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
			. ' data-slides-min-width="250"'
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_team_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_team_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_team_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
			. (organics_param_is_on($slider)
				? '<div class="slides swiper-wrapper">'
				: ($columns > 1 // && organics_get_template_property($style, 'need_columns')
					? '<div class="sc_columns columns_wrap">'
					: '')
			);

		$content = do_shortcode($content);

		if (organics_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;

			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}

			$args = array(
				'post_type' => 'team',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);

			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}

			$args = organics_query_add_sort_order($args, $orderby, $order);
			$args = organics_query_add_posts_and_cats($args, $ids, 'team', $cat, 'team_group');
			$query = new WP_Query( $args );

			$post_number = 0;

			while ( $query->have_posts() ) {
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => organics_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					"columns_count" => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = organics_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], 'team_data', true);
				$thumb_sizes = organics_get_thumb_sizes(array('layout' => $style));
				$args['position'] = $post_meta['team_member_position'];
				$args['link'] = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : $post_data['post_link'];
				$args['email'] = $post_meta['team_member_email'];
				$args['photo'] = $post_data['post_thumb'];
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, organics_get_theme_option("retina_ready"))));
				$args['socials'] = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$args['socials'] = organics_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}

				$output .= organics_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}

		if (organics_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {// && organics_get_template_property($style, 'need_columns')) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_team_button sc_item_button">'.organics_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
			. '</div><!-- /.sc_team -->'
			. '</div><!-- /.sc_team_wrap -->';

		// Add template specific scripts and styles
		do_action('organics_action_blog_scripts', $style);

		return apply_filters('organics_shortcode_output', $output, 'trx_team', $atts, $content);
	}
	add_shortcode('trx_team', 'organics_sc_team');
}


if ( !function_exists( 'organics_sc_team_item' ) ) {
	function organics_sc_team_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"user" => "",
			"member" => "",
			"name" => "",
			"position" => "",
			"photo" => "",
			"email" => "",
			"link" => "",
			"socials" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_team_counter']++;

		$id = $id ? $id : ($ORGANICS_GLOBALS['sc_team_id'] ? $ORGANICS_GLOBALS['sc_team_id'] . '_' . $ORGANICS_GLOBALS['sc_team_counter'] : '');

		$descr = trim(chop(do_shortcode($content)));

		$thumb_sizes = organics_get_thumb_sizes(array('layout' => $ORGANICS_GLOBALS['sc_team_style']));

		if (!empty($socials)) $socials = organics_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($socials).'"][/trx_socials]');

		if (!empty($user) && $user!='none' && ($user_obj = get_user_by('login', $user)) != false) {
			$meta = get_user_meta($user_obj->ID);
			if (empty($email))		$email = $user_obj->data->user_email;
			if (empty($name))		$name = $user_obj->data->display_name;
			if (empty($position))	$position = isset($meta['user_position'][0]) ? $meta['user_position'][0] : '';
			if (empty($descr))		$descr = isset($meta['description'][0]) ? $meta['description'][0] : '';
			if (empty($socials))	$socials = organics_show_user_socials(array('author_id'=>$user_obj->ID, 'echo'=>false));
		}

		if (!empty($member) && $member!='none' && ($member_obj = (intval($member) > 0 ? get_post($member, OBJECT) : get_page_by_title($member, OBJECT, 'team'))) != null) {
			if (empty($name))		$name = $member_obj->post_title;
			if (empty($descr))		$descr = $member_obj->post_excerpt;
			$post_meta = get_post_meta($member_obj->ID, 'team_data', true);
			if (empty($position))	$position = $post_meta['team_member_position'];
			if (empty($link))		$link = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : get_permalink($member_obj->ID);
			if (empty($email))		$email = $post_meta['team_member_email'];
			if (empty($photo)) 		$photo = wp_get_attachment_url(get_post_thumbnail_id($member_obj->ID));
			if (empty($socials)) {
				$socials = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$socials = organics_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
			}
		}
		if (empty($photo)) {
			if (!empty($email)) $photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, organics_get_theme_option("retina_ready"))));
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = organics_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}
		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => $ORGANICS_GLOBALS['sc_team_style'],
			'number' => $ORGANICS_GLOBALS['sc_team_counter'],
			'columns_count' => $ORGANICS_GLOBALS['sc_team_columns'],
			'slider' => $ORGANICS_GLOBALS['sc_team_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $ORGANICS_GLOBALS['sc_team_css_wh'],
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo,
			'socials' => $socials
		);
		$output = organics_show_post_layout($args, $post_data);

		return apply_filters('organics_shortcode_output', $output, 'trx_team_item', $atts, $content);
	}
	add_shortcode('trx_team_item', 'organics_sc_team_item');
}
// ---------------------------------- [/trx_team] ---------------------------------------



// Add [trx_team] and [trx_team_item] in the shortcodes list
if (!function_exists('organics_team_reg_shortcodes')) {
	//Handler of add_filter('organics_action_shortcodes_list',	'organics_team_reg_shortcodes');
	function organics_team_reg_shortcodes() {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['shortcodes'])) {

			$users = organics_get_list_users();
			$members = organics_get_list_posts(false, array(
					'post_type'=>'team',
					'orderby'=>'title',
					'order'=>'asc',
					'return'=>'title'
				)
			);
			$team_groups = organics_get_list_terms(false, 'team_group');
			$team_styles = organics_get_list_templates('team');
			$controls	 = organics_get_list_slider_controls();

			organics_array_insert_after($ORGANICS_GLOBALS['shortcodes'], 'trx_tabs', array(

				// Team
				"trx_team" => array(
					"title" => esc_html__("Team", 'trx_utils'),
					"desc" => wp_kses( __("Insert team in your page (post)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'trx_utils'),
							"desc" => wp_kses( __("Title for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'trx_utils'),
							"desc" => wp_kses( __("Subtitle for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'trx_utils'),
							"desc" => wp_kses( __("Short description for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Team style", 'trx_utils'),
							"desc" => wp_kses( __("Select style to display team members", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "1",
							"type" => "select",
							"options" => $team_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_utils'),
							"desc" => wp_kses( __("How many columns use to show team members", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => 3,
							"min" => 2,
							"max" => 5,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'trx_utils'),
							"desc" => wp_kses( __("Select color scheme for this block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'trx_utils'),
							"desc" => wp_kses( __("Use slider to show team members", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'trx_utils'),
							"desc" => wp_kses( __("Slider controls style and position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'trx_utils'),
							"desc" => wp_kses( __("Size of space (in px) between slides", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'trx_utils'),
							"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'trx_utils'),
							"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_utils'),
							"desc" => wp_kses( __("Alignment of the team block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'trx_utils'),
							"desc" => wp_kses( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'trx_utils'),
							"desc" => wp_kses( __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => organics_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $team_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'trx_utils'),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'trx_utils'),
							"desc" => wp_kses( __("Skip posts before select next part.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'trx_utils'),
							"desc" => wp_kses( __("Select desired posts sorting method", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_utils'),
							"desc" => wp_kses( __("Select desired posts order", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_utils'),
							"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'trx_utils'),
							"desc" => wp_kses( __("Link URL for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'trx_utils'),
							"desc" => wp_kses( __("Caption for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
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
					),
					"children" => array(
						"name" => "trx_team_item",
						"title" => esc_html__("Member", 'trx_utils'),
						"desc" => wp_kses( __("Team member", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => esc_html__("Registerd user", 'trx_utils'),
								"desc" => wp_kses( __("Select one of registered users (if present) or put name, position, etc. in fields below", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "select",
								"options" => $users
							),
							"member" => array(
								"title" => esc_html__("Team member", 'trx_utils'),
								"desc" => wp_kses( __("Select one of team members (if present) or put name, position, etc. in fields below", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "select",
								"options" => $members
							),
							"link" => array(
								"title" => esc_html__("Link", 'trx_utils'),
								"desc" => wp_kses( __("Link on team member's personal page", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => esc_html__("Name", 'trx_utils'),
								"desc" => wp_kses( __("Team member's name", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", 'trx_utils'),
								"desc" => wp_kses( __("Team member's position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'trx_utils'),
								"desc" => wp_kses( __("Team member's e-mail", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'trx_utils'),
								"desc" => wp_kses( __("Team member's photo (avatar)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => esc_html__("Socials", 'trx_utils'),
								"desc" => wp_kses( __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'trx_utils'),
								"desc" => wp_kses( __("Team member's short description", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_team] and [trx_team_item] in the VC shortcodes list
if (!function_exists('organics_team_reg_shortcodes_vc')) {
	//Handler of add_filter('organics_action_shortcodes_list_vc',	'organics_team_reg_shortcodes_vc');
	function organics_team_reg_shortcodes_vc() {
		global $ORGANICS_GLOBALS;

		$users = organics_get_list_users();
		$members = organics_get_list_posts(false, array(
				'post_type'=>'team',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
			)
		);
		$team_groups = organics_get_list_terms(false, 'team_group');
		$team_styles = organics_get_list_templates('team');
		$controls	 = organics_get_list_slider_controls();

		// Team
		vc_map( array(
			"base" => "trx_team",
			"name" => esc_html__("Team", 'trx_utils'),
			"description" => wp_kses( __("Insert team members", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'trx_utils'),
			'icon' => 'icon_trx_team',
			"class" => "trx_sc_columns trx_sc_team",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_team_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Team style", 'trx_utils'),
					"description" => wp_kses( __("Select style to display team members", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip($team_styles),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'trx_utils'),
					"description" => wp_kses( __("Select color scheme for this block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "slider",
					"heading" => esc_html__("Slider", 'trx_utils'),
					"description" => wp_kses( __("Use slider to show team members", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					"class" => "",
					"std" => "no",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['yes_no']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'trx_utils'),
					"description" => wp_kses( __("Slider controls style and position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"std" => "no",
					"value" => array_flip($controls),
					"type" => "dropdown"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Space between slides", 'trx_utils'),
					"description" => wp_kses( __("Size of space (in px) between slides", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Slides change interval", 'trx_utils'),
					"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", 'trx_utils'),
					"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'trx_utils'),
					"description" => wp_kses( __("Alignment of the team block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom", 'trx_utils'),
					"description" => wp_kses( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Custom members" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'trx_utils'),
					"description" => wp_kses( __("Title for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'trx_utils'),
					"description" => wp_kses( __("Subtitle for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'trx_utils'),
					"description" => wp_kses( __("Description for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories", 'trx_utils'),
					"description" => wp_kses( __("Select category to show team members. If empty - select team members from any category (group) or from IDs list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip(organics_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $team_groups)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns", 'trx_utils'),
					"description" => wp_kses( __("How many columns use to show team members", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					"admin_label" => true,
					"class" => "",
					"value" => "3",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Number of posts", 'trx_utils'),
					"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "3",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Offset before select posts", 'trx_utils'),
					"description" => wp_kses( __("Skip posts before select next part.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Post sorting", 'trx_utils'),
					"description" => wp_kses( __("Select desired posts sorting method", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sorting']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Post order", 'trx_utils'),
					"description" => wp_kses( __("Select desired posts order", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Team member's IDs list", 'trx_utils'),
					"description" => wp_kses( __("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'trx_utils'),
					"description" => wp_kses( __("Link URL for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'trx_utils'),
					"description" => wp_kses( __("Caption for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				organics_vc_width(),
				organics_vc_height(),
				$ORGANICS_GLOBALS['vc_params']['margin_top'],
				$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
				$ORGANICS_GLOBALS['vc_params']['margin_left'],
				$ORGANICS_GLOBALS['vc_params']['margin_right'],
				$ORGANICS_GLOBALS['vc_params']['id'],
				$ORGANICS_GLOBALS['vc_params']['class'],
				$ORGANICS_GLOBALS['vc_params']['animation'],
				$ORGANICS_GLOBALS['vc_params']['css']
			),
			'default_content' => '
					[trx_team_item user="' . esc_html__( 'Member 1', 'trx_utils' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 2', 'trx_utils' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 4', 'trx_utils' ) . '"][/trx_team_item]
				',
			'js_view' => 'VcTrxColumnsView'
		) );


		vc_map( array(
			"base" => "trx_team_item",
			"name" => esc_html__("Team member", 'trx_utils'),
			"description" => wp_kses( __("Team member - all data pull out from it account on your site", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item trx_sc_team_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_team_item',
			"as_child" => array('only' => 'trx_team'),
			"as_parent" => array('except' => 'trx_team'),
			"params" => array(
				array(
					"param_name" => "user",
					"heading" => esc_html__("Registered user", 'trx_utils'),
					"description" => wp_kses( __("Select one of registered users (if present) or put name, position, etc. in fields below", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($users),
					"type" => "dropdown"
				),
				array(
					"param_name" => "member",
					"heading" => esc_html__("Team member", 'trx_utils'),
					"description" => wp_kses( __("Select one of team members (if present) or put name, position, etc. in fields below", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($members),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'trx_utils'),
					"description" => wp_kses( __("Link on team member's personal page", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "name",
					"heading" => esc_html__("Name", 'trx_utils'),
					"description" => wp_kses( __("Team member's name", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Position", 'trx_utils'),
					"description" => wp_kses( __("Team member's position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "email",
					"heading" => esc_html__("E-mail", 'trx_utils'),
					"description" => wp_kses( __("Team member's e-mail", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "photo",
					"heading" => esc_html__("Member's Photo", 'trx_utils'),
					"description" => wp_kses( __("Team member's photo (avatar)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Socials", 'trx_utils'),
					"description" => wp_kses( __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				$ORGANICS_GLOBALS['vc_params']['id'],
				$ORGANICS_GLOBALS['vc_params']['class'],
				$ORGANICS_GLOBALS['vc_params']['animation'],
				$ORGANICS_GLOBALS['vc_params']['css']
			),
			'js_view' => 'VcTrxColumnItemView'
		) );

		class WPBakeryShortCode_Trx_Team extends ORGANICS_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Team_Item extends ORGANICS_VC_ShortCodeCollection {}

	}
}




// ---------------------------------- [trx_services] ---------------------------------------

/*
[trx_services id="unique_id" columns="4" count="4" style="services-1|services-2|..." title="Block title" subtitle="xxx" description="xxxxxx"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
[/trx_services]
*/
if ( !function_exists( 'organics_sc_services' ) ) {
	function organics_sc_services($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => esc_html__('Learn more', 'trx_utils'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'trx_utils'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && organics_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$ms = organics_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = organics_get_css_position_from_values('', '', '', '', $width);
		$hs = organics_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (organics_param_is_off($custom) && $count < $columns) $columns = $count;

		if (organics_param_is_on($slider)) organics_enqueue_slider('swiper');

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_services_id'] = $id;
		$ORGANICS_GLOBALS['sc_services_style'] = $style;
		$ORGANICS_GLOBALS['sc_services_columns'] = $columns;
		$ORGANICS_GLOBALS['sc_services_counter'] = 0;
		$ORGANICS_GLOBALS['sc_services_slider'] = $slider;
		$ORGANICS_GLOBALS['sc_services_css_wh'] = $ws . $hs;
		$ORGANICS_GLOBALS['sc_services_readmore'] = $readmore;

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
			. ' class="sc_services_wrap'
			. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
			.'">'
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_services'
			. ' sc_services_style_'.esc_attr($style)
			. ' sc_services_type_'.esc_attr($type)
			. ' ' . esc_attr(organics_get_template_property($style, 'container_classes'))
			. ' ' . esc_attr(organics_get_slider_controls_classes($controls))
			. (organics_param_is_on($slider)
				? ' sc_slider_swiper swiper-slider-container'
				. (organics_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
				. ($hs ? ' sc_slider_height_fixed' : '')
				: '')
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
			. '"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. (!empty($width) && organics_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
			. (!empty($height) && organics_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
			. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
			. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
			. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
			. ' data-slides-min-width="250"'
			. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_services_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
			. (organics_param_is_on($slider)
				? '<div class="slides swiper-wrapper">'
				: ($columns > 1
					? '<div class="sc_columns columns_wrap">'
					: '')
			);

		$content = do_shortcode($content);

		if (organics_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;

			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}

			$args = array(
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);

			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}

			$args = organics_query_add_sort_order($args, $orderby, $order);
			$args = organics_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
			$query = new WP_Query( $args );

			$post_number = 0;

			while ( $query->have_posts() ) {
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => organics_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= organics_show_post_layout($args);
			}
			wp_reset_postdata();
		}

		if (organics_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.organics_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
			. '</div><!-- /.sc_services -->'
			. '</div><!-- /.sc_services_wrap -->';

		// Add template specific scripts and styles
		do_action('organics_action_blog_scripts', $style);

		return apply_filters('organics_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	add_shortcode('trx_services', 'organics_sc_services');
}


if ( !function_exists( 'organics_sc_services_item' ) ) {
	function organics_sc_services_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_services_counter']++;

		$id = $id ? $id : ($ORGANICS_GLOBALS['sc_services_id'] ? $ORGANICS_GLOBALS['sc_services_id'] . '_' . $ORGANICS_GLOBALS['sc_services_counter'] : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? $ORGANICS_GLOBALS['sc_services_readmore'] : $readmore;

		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = organics_get_thumb_sizes(array('layout' => $ORGANICS_GLOBALS['sc_services_style']));
			$image = organics_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => $ORGANICS_GLOBALS['sc_services_style'],
			'number' => $ORGANICS_GLOBALS['sc_services_counter'],
			'columns_count' => $ORGANICS_GLOBALS['sc_services_columns'],
			'slider' => $ORGANICS_GLOBALS['sc_services_slider'],
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $ORGANICS_GLOBALS['sc_services_css_wh']
		);
		$output = organics_show_post_layout($args, $post_data);
		return apply_filters('organics_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	add_shortcode('trx_services_item', 'organics_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('organics_services_reg_shortcodes')) {
	//Handler of add_filter('organics_action_shortcodes_list',	'organics_services_reg_shortcodes');
	function organics_services_reg_shortcodes() {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['shortcodes'])) {

			$services_groups = organics_get_list_terms(false, 'services_group');
			$services_styles = organics_get_list_templates('services');
			$controls 		 = organics_get_list_slider_controls();

			organics_array_insert_after($ORGANICS_GLOBALS['shortcodes'], 'trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", 'trx_utils'),
					"desc" => wp_kses( __("Insert services list in your page (post)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'trx_utils'),
							"desc" => wp_kses( __("Title for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'trx_utils'),
							"desc" => wp_kses( __("Subtitle for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'trx_utils'),
							"desc" => wp_kses( __("Short description for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Services style", 'trx_utils'),
							"desc" => wp_kses( __("Select style to display services list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"type" => array(
							"title" => esc_html__("Icon's type", 'trx_utils'),
							"desc" => wp_kses( __("Select type of icons: font icon or image", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'trx_utils'),
								'images' => esc_html__('Images', 'trx_utils')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_utils'),
							"desc" => wp_kses( __("How many columns use to show services list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'trx_utils'),
							"desc" => wp_kses( __("Select color scheme for this block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'trx_utils'),
							"desc" => wp_kses( __("Use slider to show services", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'trx_utils'),
							"desc" => wp_kses( __("Slider controls style and position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'trx_utils'),
							"desc" => wp_kses( __("Size of space (in px) between slides", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'trx_utils'),
							"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'trx_utils'),
							"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_utils'),
							"desc" => wp_kses( __("Alignment of the services block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'trx_utils'),
							"desc" => wp_kses( __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'trx_utils'),
							"desc" => wp_kses( __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => organics_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'trx_utils'),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'trx_utils'),
							"desc" => wp_kses( __("Skip posts before select next part.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'trx_utils'),
							"desc" => wp_kses( __("Select desired posts sorting method", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_utils'),
							"desc" => wp_kses( __("Select desired posts order", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_utils'),
							"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"readmore" => array(
							"title" => esc_html__("Read more", 'trx_utils'),
							"desc" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'trx_utils'),
							"desc" => wp_kses( __("Link URL for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'trx_utils'),
							"desc" => wp_kses( __("Caption for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
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
					),
					"children" => array(
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", 'trx_utils'),
						"desc" => wp_kses( __("Service item", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", 'trx_utils'),
								"desc" => wp_kses( __("Item's title", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon", 'trx_utils'),
								"desc" => wp_kses( __('Select icon for the item from Fontello icons set', 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "icons",
								"options" => $ORGANICS_GLOBALS['sc_params']['icons']
							),
							"image" => array(
								"title" => esc_html__("Item's image", 'trx_utils'),
								"desc" => wp_kses( __("Item's image (if icon not selected)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", 'trx_utils'),
								"desc" => wp_kses( __("Link on service's item page", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", 'trx_utils'),
								"desc" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'trx_utils'),
								"desc" => wp_kses( __("Item's short description", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('organics_services_reg_shortcodes_vc')) {
	function organics_services_reg_shortcodes_vc() {
		global $ORGANICS_GLOBALS;

		$services_groups = organics_get_list_terms(false, 'services_group');
		$services_styles = organics_get_list_templates('services');
		$controls		 = organics_get_list_slider_controls();

		// Services
		vc_map( array(
			"base" => "trx_services",
			"name" => esc_html__("Services", 'trx_utils'),
			"description" => wp_kses( __("Insert services list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'trx_utils'),
			"icon" => 'icon_trx_services',
			"class" => "trx_sc_columns trx_sc_services",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_services_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Services style", 'trx_utils'),
					"description" => wp_kses( __("Select style to display services list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip($services_styles),
					"type" => "dropdown"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'trx_utils'),
					"description" => wp_kses( __("Select type of icons: font icon or image", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array(
						esc_html__('Icons', 'trx_utils') => 'icons',
						esc_html__('Images', 'trx_utils') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'trx_utils'),
					"description" => wp_kses( __("Select color scheme for this block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "slider",
					"heading" => esc_html__("Slider", 'trx_utils'),
					"description" => wp_kses( __("Use slider to show services", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					"class" => "",
					"std" => "no",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['yes_no']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'trx_utils'),
					"description" => wp_kses( __("Slider controls style and position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"std" => "no",
					"value" => array_flip($controls),
					"type" => "dropdown"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Space between slides", 'trx_utils'),
					"description" => wp_kses( __("Size of space (in px) between slides", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Slides change interval", 'trx_utils'),
					"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", 'trx_utils'),
					"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'trx_utils'),
					"description" => wp_kses( __("Alignment of the services block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom", 'trx_utils'),
					"description" => wp_kses( __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Custom services" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'trx_utils'),
					"description" => wp_kses( __("Title for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'trx_utils'),
					"description" => wp_kses( __("Subtitle for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'trx_utils'),
					"description" => wp_kses( __("Description for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories", 'trx_utils'),
					"description" => wp_kses( __("Select category to show services. If empty - select services from any category (group) or from IDs list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip(organics_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $services_groups)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns", 'trx_utils'),
					"description" => wp_kses( __("How many columns use to show services list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					"admin_label" => true,
					"class" => "",
					"value" => "4",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Number of posts", 'trx_utils'),
					"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "4",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Offset before select posts", 'trx_utils'),
					"description" => wp_kses( __("Skip posts before select next part.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Post sorting", 'trx_utils'),
					"description" => wp_kses( __("Select desired posts sorting method", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sorting']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Post order", 'trx_utils'),
					"description" => wp_kses( __("Select desired posts order", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Service's IDs list", 'trx_utils'),
					"description" => wp_kses( __("Comma separated list of service's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "readmore",
					"heading" => esc_html__("Read more", 'trx_utils'),
					"description" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'trx_utils'),
					"description" => wp_kses( __("Link URL for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'trx_utils'),
					"description" => wp_kses( __("Caption for the button at the bottom of the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				organics_vc_width(),
				organics_vc_height(),
				$ORGANICS_GLOBALS['vc_params']['margin_top'],
				$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
				$ORGANICS_GLOBALS['vc_params']['margin_left'],
				$ORGANICS_GLOBALS['vc_params']['margin_right'],
				$ORGANICS_GLOBALS['vc_params']['id'],
				$ORGANICS_GLOBALS['vc_params']['class'],
				$ORGANICS_GLOBALS['vc_params']['animation'],
				$ORGANICS_GLOBALS['vc_params']['css']
			),
			'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'trx_utils' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'trx_utils' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'trx_utils' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'trx_utils' ) . '"][/trx_services_item]
				',
			'js_view' => 'VcTrxColumnsView'
		) );


		vc_map( array(
			"base" => "trx_services_item",
			"name" => esc_html__("Services item", 'trx_utils'),
			"description" => wp_kses( __("Custom services item - all data pull out from shortcode parameters", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_services_item',
			"as_child" => array('only' => 'trx_services'),
			"as_parent" => array('except' => 'trx_services'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'trx_utils'),
					"description" => wp_kses( __("Item's title", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'trx_utils'),
					"description" => wp_kses( __("Select icon for the item from Fontello icons set", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image", 'trx_utils'),
					"description" => wp_kses( __("Item's image (if icon is empty)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'trx_utils'),
					"description" => wp_kses( __("Link on item's page", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "readmore",
					"heading" => esc_html__("Read more", 'trx_utils'),
					"description" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				$ORGANICS_GLOBALS['vc_params']['id'],
				$ORGANICS_GLOBALS['vc_params']['class'],
				$ORGANICS_GLOBALS['vc_params']['animation'],
				$ORGANICS_GLOBALS['vc_params']['css']
			),
			'js_view' => 'VcTrxColumnItemView'
		) );

		class WPBakeryShortCode_Trx_Services extends ORGANICS_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends ORGANICS_VC_ShortCodeCollection {}

	}
}





// ---------------------------------- [trx_testimonials] ---------------------------------------

/*
[trx_testimonials id="unique_id" style="1|2|3"]
	[trx_testimonials_item user="user_login"]Testimonials text[/trx_testimonials_item]
	[trx_testimonials_item email="" name="" position="" photo="photo_url"]Testimonials text[/trx_testimonials]
[/trx_testimonials]
*/

if (!function_exists('organics_sc_testimonials')) {
	function organics_sc_testimonials($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "testimonials-4",
			"columns" => 3,
			"slider" => "yes",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && organics_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}

		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = organics_get_scheme_color('bg');
			$rgb = organics_hex2rgb($bg_color);
		}

		$ms = organics_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = organics_get_css_position_from_values('', '', '', '', $width);
		$hs = organics_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (organics_param_is_off($custom) && $count < $columns) $columns = $count;

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_testimonials_id'] = $id;
		$ORGANICS_GLOBALS['sc_testimonials_style'] = $style;
		$ORGANICS_GLOBALS['sc_testimonials_columns'] = $columns;
		$ORGANICS_GLOBALS['sc_testimonials_counter'] = 0;
		$ORGANICS_GLOBALS['sc_testimonials_slider'] = $slider;
		$ORGANICS_GLOBALS['sc_testimonials_css_wh'] = $ws . $hs;

		if (organics_param_is_on($slider)) organics_enqueue_slider('swiper');

		$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || organics_strlen($bg_texture)>2 || ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme))
				? '<div class="sc_testimonials_wrap sc_section'
				. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
				. '"'
				.' style="'
				. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
				. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
				. '"'
				. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. '>'
				. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
				. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
				. (organics_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
				. '"'
				. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
				. '>'
				: '')
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
			. ' ' . esc_attr(organics_get_template_property($style, 'container_classes'))
			. (organics_param_is_on($slider)
				? ' sc_slider_swiper swiper-slider-container'
				. ' ' . esc_attr(organics_get_slider_controls_classes($controls))
				//. ' sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom'
				. (organics_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
				. ($hs ? ' sc_slider_height_fixed' : '')
				: '')
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
			. '"'
			. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
			. (!empty($width) && organics_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
			. (!empty($height) && organics_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
			. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
			. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
			. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
			. ' data-slides-min-width="250"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
			. (organics_param_is_on($slider)
				? '<div class="slides swiper-wrapper">'
				: ($columns > 1
					? '<div class="sc_columns columns_wrap">'
					: '')
			);

		$content = do_shortcode($content);

		if (organics_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;

			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}

			$args = array(
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);

			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}

			$args = organics_query_add_sort_order($args, $orderby, $order);
			$args = organics_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');

			$query = new WP_Query( $args );

			$post_number = 0;

			while ( $query->have_posts() ) {
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => organics_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = organics_get_post_data($args);
				$post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
				$post_meta = get_post_meta($post_data['post_id'], 'testimonial_data', true);
				$thumb_sizes = organics_get_thumb_sizes(array('layout' => $style));
				$args['author'] = $post_meta['testimonial_author'];
				$args['position'] = $post_meta['testimonial_position'];
				$args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';	//$post_data['post_link'];
				$args['email'] = $post_meta['testimonial_email'];
				$args['photo'] = $post_data['post_thumb'];
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, organics_get_theme_option("retina_ready"))));
				$output .= organics_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}

		if (organics_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= '</div>'
			. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || organics_strlen($bg_texture)>2
				?  '</div></div>'
				: '');

		// Add template specific scripts and styles
		do_action('organics_action_blog_scripts', $style);

		return apply_filters('organics_shortcode_output', $output, 'trx_testimonials', $atts, $content);
	}
	add_shortcode('trx_testimonials', 'organics_sc_testimonials');
}


if (!function_exists('organics_sc_testimonials_item')) {
	function organics_sc_testimonials_item($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"author" => "",
			"position" => "",
			"link" => "",
			"photo" => "",
			"email" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
		), $atts)));

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_testimonials_counter']++;

		$id = $id ? $id : ($ORGANICS_GLOBALS['sc_testimonials_id'] ? $ORGANICS_GLOBALS['sc_testimonials_id'] . '_' . $ORGANICS_GLOBALS['sc_testimonials_counter'] : '');

		$thumb_sizes = organics_get_thumb_sizes(array('layout' => $ORGANICS_GLOBALS['sc_testimonials_style']));

		if (empty($photo)) {
			if (!empty($email))
				$photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, organics_get_theme_option("retina_ready"))));
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = organics_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_content' => do_shortcode($content)
		);
		$args = array(
			'layout' => $ORGANICS_GLOBALS['sc_testimonials_style'],
			'number' => $ORGANICS_GLOBALS['sc_testimonials_counter'],
			'columns_count' => $ORGANICS_GLOBALS['sc_testimonials_columns'],
			'slider' => $ORGANICS_GLOBALS['sc_testimonials_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => '',
			'tag_css' => $css,
			'tag_css_wh' => $ORGANICS_GLOBALS['sc_testimonials_css_wh'],
			'author' => $author,
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo
		);
		$output = organics_show_post_layout($args, $post_data);

		return apply_filters('organics_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
	}
	add_shortcode('trx_testimonials_item', 'organics_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------



// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('organics_testimonials_reg_shortcodes')) {
	//Handler of add_filter('organics_action_shortcodes_list',	'organics_testimonials_reg_shortcodes');
	function organics_testimonials_reg_shortcodes() {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['shortcodes'])) {

			$testimonials_groups = organics_get_list_terms(false, 'testimonial_group');
			$testimonials_styles = organics_get_list_templates('testimonials');
			$controls = organics_get_list_slider_controls();

			organics_array_insert_before($ORGANICS_GLOBALS['shortcodes'], 'trx_title', array(

				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", 'trx_utils'),
					"desc" => wp_kses( __("Insert testimonials into post (page)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'trx_utils'),
							"desc" => wp_kses( __("Title for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'trx_utils'),
							"desc" => wp_kses( __("Subtitle for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'trx_utils'),
							"desc" => wp_kses( __("Short description for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Testimonials style", 'trx_utils'),
							"desc" => wp_kses( __("Select style to display testimonials", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "testimonials-1",
							"type" => "select",
							"options" => $testimonials_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_utils'),
							"desc" => wp_kses( __("How many columns use to show testimonials", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'trx_utils'),
							"desc" => wp_kses( __("Use slider to show testimonials", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'trx_utils'),
							"desc" => wp_kses( __("Slider controls style and position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'trx_utils'),
							"desc" => wp_kses( __("Size of space (in px) between slides", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'trx_utils'),
							"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'trx_utils'),
							"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_utils'),
							"desc" => wp_kses( __("Alignment of the testimonials block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'trx_utils'),
							"desc" => wp_kses( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'trx_utils'),
							"desc" => wp_kses( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => organics_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $testimonials_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'trx_utils'),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'trx_utils'),
							"desc" => wp_kses( __("Skip posts before select next part.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'trx_utils'),
							"desc" => wp_kses( __("Select desired posts sorting method", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_utils'),
							"desc" => wp_kses( __("Select desired posts order", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_utils'),
							"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'trx_utils'),
							"desc" => wp_kses( __("Select color scheme for this block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_utils'),
							"desc" => wp_kses( __("Any background color for this section", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", 'trx_utils'),
							"desc" => wp_kses( __("Select or upload image or write URL from other site for the background", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'trx_utils'),
							"desc" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'trx_utils'),
							"desc" => wp_kses( __("Predefined texture style from 1 to 11. 0 - without texture.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
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
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", 'trx_utils'),
						"desc" => wp_kses( __("Testimonials item (custom parameters)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", 'trx_utils'),
								"desc" => wp_kses( __("Name of the testimonmials author", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'trx_utils'),
								"desc" => wp_kses( __("Link URL to the testimonmials author page", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'trx_utils'),
								"desc" => wp_kses( __("E-mail of the testimonmials author (to get gravatar)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'trx_utils'),
								"desc" => wp_kses( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", 'trx_utils'),
								"desc" => wp_kses( __("Current testimonials text", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('organics_testimonials_reg_shortcodes_vc')) {
	//Handler of add_filter('organics_action_shortcodes_list_vc',	'organics_testimonials_reg_shortcodes_vc');
	function organics_testimonials_reg_shortcodes_vc() {
		global $ORGANICS_GLOBALS;

		$testimonials_groups = organics_get_list_terms(false, 'testimonial_group');
		$testimonials_styles = organics_get_list_templates('testimonials');
		$controls			 = organics_get_list_slider_controls();

		// Testimonials			
		vc_map( array(
			"base" => "trx_testimonials",
			"name" => esc_html__("Testimonials", 'trx_utils'),
			"description" => wp_kses( __("Insert testimonials slider", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'trx_utils'),
			'icon' => 'icon_trx_testimonials',
			"class" => "trx_sc_collection trx_sc_testimonials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_testimonials_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Testimonials style", 'trx_utils'),
					"description" => wp_kses( __("Select style to display testimonials", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip($testimonials_styles),
					"type" => "dropdown"
				),
				array(
					"param_name" => "slider",
					"heading" => esc_html__("Slider", 'trx_utils'),
					"description" => wp_kses( __("Use slider to show testimonials", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					"class" => "",
					"std" => "yes",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['yes_no']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'trx_utils'),
					"description" => wp_kses( __("Slider controls style and position", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"std" => "no",
					"value" => array_flip($controls),
					"type" => "dropdown"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Space between slides", 'trx_utils'),
					"description" => wp_kses( __("Size of space (in px) between slides", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Slides change interval", 'trx_utils'),
					"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", 'trx_utils'),
					"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Slider', 'trx_utils'),
					'dependency' => array(
						'element' => 'slider',
						'value' => 'yes'
					),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'trx_utils'),
					"description" => wp_kses( __("Alignment of the testimonials block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom", 'trx_utils'),
					"description" => wp_kses( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Custom slides" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'trx_utils'),
					"description" => wp_kses( __("Title for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'trx_utils'),
					"description" => wp_kses( __("Subtitle for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'trx_utils'),
					"description" => wp_kses( __("Description for the block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories", 'trx_utils'),
					"description" => wp_kses( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip(organics_array_merge(array(0 => esc_html__('- Select category -', 'trx_utils')), $testimonials_groups)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns", 'trx_utils'),
					"description" => wp_kses( __("How many columns use to show testimonials", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					"admin_label" => true,
					"class" => "",
					"value" => "1",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Number of posts", 'trx_utils'),
					"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "3",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Offset before select posts", 'trx_utils'),
					"description" => wp_kses( __("Skip posts before select next part.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Post sorting", 'trx_utils'),
					"description" => wp_kses( __("Select desired posts sorting method", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sorting']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Post order", 'trx_utils'),
					"description" => wp_kses( __("Select desired posts order", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Post IDs list", 'trx_utils'),
					"description" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Query', 'trx_utils'),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'trx_utils'),
					"description" => wp_kses( __("Select color scheme for this block", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'trx_utils'),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'trx_utils'),
					"description" => wp_kses( __("Any background color for this section", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'trx_utils'),
					"description" => wp_kses( __("Select background image from library for this section", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'trx_utils'),
					"description" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'trx_utils'),
					"description" => wp_kses( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'trx_utils'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				organics_vc_width(),
				organics_vc_height(),
				$ORGANICS_GLOBALS['vc_params']['margin_top'],
				$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
				$ORGANICS_GLOBALS['vc_params']['margin_left'],
				$ORGANICS_GLOBALS['vc_params']['margin_right'],
				$ORGANICS_GLOBALS['vc_params']['id'],
				$ORGANICS_GLOBALS['vc_params']['class'],
				$ORGANICS_GLOBALS['vc_params']['animation'],
				$ORGANICS_GLOBALS['vc_params']['css']
			)
		) );


		vc_map( array(
			"base" => "trx_testimonials_item",
			"name" => esc_html__("Testimonial", 'trx_utils'),
			"description" => wp_kses( __("Single testimonials item", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item trx_sc_testimonials_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_testimonials_item',
			"as_child" => array('only' => 'trx_testimonials'),
			"as_parent" => array('except' => 'trx_testimonials'),
			"params" => array(
				array(
					"param_name" => "author",
					"heading" => esc_html__("Author", 'trx_utils'),
					"description" => wp_kses( __("Name of the testimonmials author", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'trx_utils'),
					"description" => wp_kses( __("Link URL to the testimonmials author page", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "email",
					"heading" => esc_html__("E-mail", 'trx_utils'),
					"description" => wp_kses( __("E-mail of the testimonmials author", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "photo",
					"heading" => esc_html__("Photo", 'trx_utils'),
					"description" => wp_kses( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Testimonials text", 'trx_utils'),
					"description" => wp_kses( __("Current testimonials text", 'trx_utils'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				$ORGANICS_GLOBALS['vc_params']['id'],
				$ORGANICS_GLOBALS['vc_params']['class'],
				$ORGANICS_GLOBALS['vc_params']['css']
			),
			'js_view' => 'VcTrxColumnItemView'
		) );

		class WPBakeryShortCode_Trx_Testimonials extends ORGANICS_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Testimonials_Item extends ORGANICS_VC_ShortCodeCollection {}

	}
}
?>