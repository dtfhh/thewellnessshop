<?php
/**
 * AxiomThemes Framework: Testimonial post type settings
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Theme init
if (!function_exists('organics_testimonial_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_testimonial_theme_setup' );
	function organics_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_filter('trx_utils_filter_override_options',			'organics_testimonial_add_override_options');

		// Save data from override options
		add_action('save_post',				'organics_testimonial_save_data');

		// Override options fields
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['testimonial_override_options'] = array(
			'id' => 'testimonial-override-options',
			'title' => esc_html__('Testimonial Details', 'organics'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'organics'),
					"desc" => wp_kses( __("Name of the testimonial's author", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'organics'),
					"desc" => wp_kses( __("Position of the testimonial's author", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'organics'),
					"desc" => wp_kses( __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'organics'),
					"desc" => wp_kses( __("URL of the testimonial source or author profile page", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
			)
		);

        // Add supported data types
        organics_theme_support_pt('testimonial');
        organics_theme_support_tx('testimonial_group');
	}
}


// Add override options
if (!function_exists('organics_testimonial_add_override_options')) {
	function organics_testimonial_add_override_options($boxes = array()) {
        $boxes[] = array_merge(organics_get_global('testimonial_override_options'), array('callback' => 'organics_testimonial_show_override_options'));
        return $boxes;
	}
}

// Callback function to show fields in override options
if (!function_exists('organics_testimonial_show_override_options')) {
	function organics_testimonial_show_override_options() {
		global $post, $ORGANICS_GLOBALS;

		// Use nonce for verification
		echo '<input type="hidden" name="override_options_testimonial_nonce" value="', esc_attr($ORGANICS_GLOBALS['admin_nonce']), '" />';
		
		$data = get_post_meta($post->ID, 'testimonial_data', true);
	
		$fields = $ORGANICS_GLOBALS['testimonial_override_options']['fields'];
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from override options
if (!function_exists('organics_testimonial_save_data')) {
	function organics_testimonial_save_data($post_id) {
		global $ORGANICS_GLOBALS;
		// verify nonce
		if (!isset($_POST['override_options_testimonial_nonce']) || !wp_verify_nonce($_POST['override_options_testimonial_nonce'], $ORGANICS_GLOBALS['admin_url'])) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = $ORGANICS_GLOBALS['testimonial_override_options']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, 'testimonial_data', $data);
	}
}


