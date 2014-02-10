<?php
/**
* Core: Public Functions
*
* These are public static functions that you can call anywhere.
*
* @package WordPress
* @subpackage wpx 
* @since 1.0
*/

class wpx {

	public static function init() {

		// make it possible to style the login screen
		add_action('login_head',  array('wpx','extend_login_styles'));

		// make it possible to style the dashboard
		add_action( 'admin_head', array('wpx','extend_dashboard_styles') );

		// add js necessary for wpx
		add_action( 'admin_enqueue_scripts', array('wpx','extend_dashboard_script') );

		// attempt to bring in sidebars
		add_action( 'widgets_init', array('wpx', 'get_sidebars'));

		// use HTML5 markup
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

		// stuff we're globally disabling/re-enabling
		add_action( 'widgets_init',  array('wpx', 'disable_recent_comments_styles'));
		add_filter( 'default_hidden_meta_boxes', array('wpx', 'enable_excerpt_metabox'), 20, 1 );
		add_filter( 'login_headerurl', array('wpx','change_login_logo_url') );
		add_action( 'right_now_content_table_end' , array('wpx','extend_right_now_widget') );

		// allow galleries and image field types to delete images from the media library
		add_action( 'save_post', array('wpx', 'manage_image_field_delete') );

		// forces all keys in WPX cpts to use underscores
		add_filter('sanitize_title',  array('wpx', 'normalize_keys'));

		// extends the internal field types to modify screen behavior
		add_action('wpx_post_type_edit_screen', array('wpx','extend_internal_field_types') );

	}

	/*
	|--------------------------------------------------------------------------
	 * Internal to WPx
	|--------------------------------------------------------------------------
	/*
	* These are functions used by the WPx plugin.
	*/

	/*
	|--------------------------------------------------------------------------
	/**
	 * Normalize Keys
	 *
	 * Forces hyphens to become underscores when dealing with "core" WPx content types
	 * and prevents invalid characters from making it thru from the Title field.
	 *
	 * @since 1.0
	 *
	 */
	function normalize_keys($title) {
		global $post;
		$post_type = get_post_type($post);
		// only for internal cpts
		if ($post_type == 'wpx_fields' || $post_type == 'wpx_options' || $post_type == 'wpx_types' || $post_type == 'wpx_taxonomy') {
			// only underscores, hyphens, and lowercase alphanumerics allowed
			$title = sanitize_key( $title );
			// actually, only underscores allowed
			return str_replace('-', '_', $title);
		} else {
			return $title;
		}
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Extract Field Key
	 *
	 * Gets the unique ID of a field when on an edit post screen.
	 *
	 * @since 1.0
	 */
	public function extract_field_key($key, $post_type) {

		$search = '_'.$post_type.'_';
		$field_key = str_replace($search, '', $key);
		$field_key = get_page_by_path($field_key, OBJECT, 'wpx_fields');
		return $field_key;

	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Extend Internal Field Types
	 *
	 * Allows us to show extra field options when specific field types are chosen.
	 *
	 * @since 1.0
	 */
	public function extend_internal_field_types() {

		global $post;

		$post_type = get_post_type($post->ID);

		// remove wp seo for the wpx types
		remove_meta_box( 'wpseo_meta', 'wpx_fields', 'normal' );
		remove_meta_box( 'wpseo_meta', 'wpx_options', 'normal' );
		remove_meta_box( 'wpseo_meta', 'wpx_taxonomy', 'normal' );
		remove_meta_box( 'wpseo_meta', 'wpx_types', 'normal' );

		// only do this for core WPx fields
		if ($post_type == 'wpx_fields') {

			// inquire about the field type
			$field_type = get_post_meta($post->ID, '_wpx_fields_type', true);

			if ($field_type !== 'post') {
				remove_meta_box('wpx_fields_relationshipoptions','wpx_fields','normal');
			}

			if ($field_type !== 'user') {
				remove_meta_box('wpx_fields_useroptions','wpx_fields','normal');
			}

			if ($field_type !== 'term') {
				remove_meta_box('wpx_fields_taxonomyoptions','wpx_fields','normal');
			}

			if ($field_type !== 'gallery') {
				remove_meta_box('wpx_fields_galleryoptions','wpx_fields','normal');
			}

			// if there is no field type, it means the field hasn't been saved yet
			// so hide all the additional panels
			if (!$field_type) {
				remove_meta_box('wpx_fields_galleryoptions','wpx_fields','normal');
				remove_meta_box('wpx_fields_relationshipoptions','wpx_fields','normal');
				remove_meta_box('wpx_fields_taxonomyoptions','wpx_fields','normal');
			}

		}

	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Adds JS for wpx
	 *
	 * Enqueue to the Dashboard the JS we need for WPx.
	 *
	 * @since 1.0
	 */
	public function extend_dashboard_script() {

		wp_enqueue_media();
		wp_register_script( 'wpx.common', plugins_url('/assets/js/scripts/common.js', dirname(__FILE__)), array('jquery'), null, true);
		wp_register_script( 'wpx.fields', plugins_url('/assets/js/scripts/fields.js', dirname(__FILE__)), array('jquery'), null, true);
		wp_enqueue_script( 'wpx.common' );
		wp_enqueue_script( 'wpx.fields' );

	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Manage Image Field Delete
	 *
	 * Used in conjunction with the image and gallery field types.
	 *
	 * @since 1.0
	 */
	public function manage_image_field_delete() {
		// skip autosaves
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		// delete all images in this array
		if (isset($_POST['wpx_prep_gallery_delete_image'])) {
			wp_delete_attachment($_POST['wpx_prep_gallery_delete_image']);
		}
		// delete a specific set of images
		if (isset($_POST['wpx_prep_gallery_delete_set'])) {
			$set = explode(',',$_POST['wpx_prep_gallery_delete_set']);
			$set = array_filter($set);
			foreach ($set as $image) {
				wp_delete_attachment($image);
			}
		}
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Remove Recent Comments Styles
	 *
	 * WP inserts this inline style: <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
	 * directly into the head of the document for the Recent Comments widget. This function removes the inline style. 
	 *
	 * @since 1.0
	 *
	 */
	public function disable_recent_comments_styles() {
		global $wp_widget_factory;
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Enable Excerpt Metabox
	 *
	 * WP by default hides the Excerpt metabox in the Dashboard, this unhides it. 
	 *
	 * @since 1.0
	 *
	 */
	public function enable_excerpt_metabox( $hidden ) {
		foreach ( $hidden as $i => $metabox ) {
			if ( 'postexcerpt' == $metabox ) {
				unset ( $hidden[$i] );
			}
		}
		return $hidden;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Change Login Logo URL
	 *
	 * Makes the login logo link to the site's homepage, and not WordPress.org. 
	 *
	 * @since 1.0
	 */
	public function change_login_logo_url($url) {
		return get_bloginfo('url');
	}


	/*
	|--------------------------------------------------------------------------
	/**
	 * Extend "Right Now" Dashboard Widget 
	 *
	 * Adds custom taxonomies and custom post types to the Right Now widget in the Dashboard. 
	 *
	 * @since 1.0
	 */
	public function extend_right_now_widget() {
		$args = array(
			'public' => true ,
			'_builtin' => false
		);
		$output = 'object';
		$operator = 'and';
		$post_types = get_post_types( $args , $output , $operator );
		foreach( $post_types as $post_type ) {
			$num_posts = wp_count_posts( $post_type->name );
			$num = number_format_i18n( $num_posts->publish );
			$text = _n( $post_type->labels->singular_name, $post_type->labels->name , intval( $num_posts->publish ) );
			if ( current_user_can( 'edit_posts' ) ) {
				$num = "<a href='edit.php?post_type=$post_type->name'>$num</a>";
				$text = "<a href='edit.php?post_type=$post_type->name'>$text</a>";
			}
			echo '<tr><td class="first b b-' . $post_type->name . '">' . $num . '</td>';
			echo '<td class="t ' . $post_type->name . '">' . $text . '</td></tr>';
		}
		$taxonomies = get_taxonomies( $args , $output , $operator );
		foreach( $taxonomies as $taxonomy ) {
			$num_terms  = wp_count_terms( $taxonomy->name );
			$num = number_format_i18n( $num_terms );
			$text = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name , intval( $num_terms ) );
			if ( current_user_can( 'manage_categories' ) ) {
				$num = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num</a>";
				$text = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$text</a>";
			}
			echo '<tr><td class="first b b-' . $taxonomy->name . '">' . $num . '</td>';
			echo '<td class="t ' . $taxonomy->name . '">' . $text . '</td></tr>';
		}
	}

	/*
	|--------------------------------------------------------------------------
	 * Extend (New Stuff)
	|--------------------------------------------------------------------------
	/*
	* These are all new functions you can call in your theme like so:
	* wpx::name_of_function();
	*/

	/*
	|--------------------------------------------------------------------------
	/**
	 * Get Attachment ID by URL
	 *
	 * Gets an attachments by its URL.
	 *
	 * @since 1.0
	 *
	 */
	function get_attachment_id_by_url($url) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";
		$id = $wpdb->get_var($query);
		return $id;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Get Attachment by ID
	 *
	 * Returns attachment metdata by ID.
	 *
	 * @since 1.0
	 *
	 */
	function get_attachment($attachment_id) {
		$attachment = get_post($attachment_id);
		return $attachment;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Get Options Meta
	 *
	 * Like get_post_meta(), this function returns the custom meta attached to an Options page.
	 *
	 * @since 1.0
	 *
	 */
	public function get_option_meta($options_page_id, $meta_key) {
		$meta_array =  get_option( $options_page_id );
		return $meta_array[$meta_key];
	}


	/*
	|--------------------------------------------------------------------------
	/**
	 * Get Taxonomy Term Meta
	 *
	 * Like get_post_meta(), this function returns the custom meta attached to a taxonomy.
	 *
	 * @since 1.0
	 *
	 */
	public function get_taxonomy_meta($term_id, $meta_key) {
		$string = 'taxonomy_term_'.$term_id;
		$meta_array =  get_option( $string );
		return $meta_array[$meta_key];
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Add Custom CSS to the Login Form
	 *
	 * Includes a CSS file into the Login screens. It will look for a login.css in the theme directory.
	 *
	 * @since 1.0
	 */
	public function extend_login_styles() {
		$css = TEMPLATEPATH . '/login.css';
		if(is_file($css)){
			echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_url') . '/login.css" />'."\n";
		}
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Add Custom CSS to the Dashboard
	 *
	 * Includes a CSS file into the Dashboard for wpx and for the theme. 
	 * The one for your theme will look for a dashboard.css in the root of your theme.
	 *
	 * @since 1.0
	 */
	public function extend_dashboard_styles() {
		if (is_admin()) {
			// these are the styles for wpx
			wp_register_style('wpx.dashboard', plugins_url('/assets/styles/dashboard.css', dirname(__FILE__)), false, null, 'screen');
			wp_enqueue_style('wpx.dashboard');
			// these are your styles, from the theme
			$css = TEMPLATEPATH . '/dashboard.css';
			if (is_file($css)) {
				wp_register_style('wpx.dashboard.custom', get_bloginfo('template_url').'/dashboard.css', false, null, 'screen');
				wp_enqueue_style('wpx.dashboard.custom');
			}
		}

	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Dynamically Render Sidebars in Dashboard
	 *
	 * Search across the site for all unique instances of what we deem are sidebar
	 * custom meta values and generate a sidebar for each one.
	 *
	 * @param array $fields An array of custom field names we should search across
	 * @param array $args Any arguments to pass to the register_sidebar function
	 * @since 1.0
	 */
	public static function get_sidebars(array $fields, $args = '') {
		// for each custom meta field to search across
		foreach($fields as $field) {
			// get the values
			foreach(get_sidebar_meta($field) as $i=>$sidebar){
				// register a sidebar
				register_sidebar(array(
					'name'=>$sidebar[meta_value],
					'id' => sanitize_key(strtolower(str_replace(" ", "", $sidebar[meta_value]))),
					'description' => $args['description'],
					'class' => $args['class'],
					'before_widget' => $args['before_widget'],
					'after_widget' => $args['after_widget'],
					'before_title' => $args['before_title'],
					'after_title' => $args['after_title'],
				));
			}
		}
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Get All Sidebar Meta
	 *
	 * Returns all values for a given custom field.
	 * Each Page with a custom field value for _sidebar is defining a custom Sidebar, and we use this function
	 * later to register a sidebar for each found value. 
	 *
	 * @since 3.0
	 * @param string $field The name of the custom field to retrieve.
	 * @return object
	 *
	 */
	private function get_sidebar_meta($field){
		global $wpdb;
		$query = "SELECT * FROM $wpdb->postmeta WHERE meta_key = '".$field."' GROUP BY meta_value ORDER BY post_id DESC";
		$sidebars = $wpdb->get_results($query, ARRAY_A);
		return $sidebars;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Get Ancestor ID
	 *
	 * Retrieve the ID of the ancestor of the given object.
	 *
	 * @since 1.0
	 * @param object $object The post we're checking ancestors for.
	 * @return string
	 *
	 */
	public static function get_ancestor_id($object = null) {
		if (!$object) {
			global $post;
			$object = $post;
		}
		$ancestor = get_post_ancestors( $object );
		if (empty($ancestor)) {
			$ancestor = array($object->ID);
		}
		$ancestor = end($ancestor);
		return $ancestor;
	}


	/*
	|--------------------------------------------------------------------------
	/**
	 * Get Ancestor Meta
	 *
	 * Retrieve custom meta from an ancestor of the given post.
	 *
	 * @since 1.0
	 * @param object $post The post we're getting ancestor meta for.
	 * @param string $meta_key The custom field name.
	 * @param string $defaunt_meta The content to display if we find nothing.
	 * @return string
	 *
	 */
	public static function get_ancestor_meta($post=null, $meta_key, $default_value) {
		if (!$post) {
			global $post;
		}
		$ancestor_meta = get_post_meta(wpx::get_ancestor_id($post), $meta_key, true);
		$meta = get_post_meta($post->ID, $meta_key, true);
		// does this page have the meta?
		if ($meta) {
			$value = $meta;
		// okay, does the ancestor have the meta?
		} else if ($ancestor_meta) {
			$value = $ancestor_meta;
		// just use the default
		} else {
			$value = $default_value;
		}
		return $value;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Truncate
	 *
	 * A very simple function that limits a given block of text to the specified length.
	 * Does *not* account for HTML.
	 *
	 * @since 1.0
	 * @param string $text
	 * @param string $limit
	 * @param string $break The character to break on (typically a space)
	 * @return string
	 *
	 */
	public static function truncate($text, $limit, $break) {
		$size = strlen($text);
		if ($size > $limit) {
			$text = $text." ";
			$text = substr($text,0,$limit);
			$text = substr($text,0,strrpos($text,' '));
			$text = $text.$break;
		}
		return $text;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Determine Sidebar
	 *
	 * When we have hierarchical Pages, we often have the need to make a sidebar and its widgets
	 * be automatically inherited based on the Page hierarchy. This function will recursively check the 
	 * ancestor pages of the current Page (must be used in the Loop) and display the sidebar assigned
	 * to the highest ancestor, but only after checking if the Page has no custom sidebar defined.
	 * You must also pass the default registered sidebar to use if the ancestor has no custom sidebar
	 * defined or the current page has no custom sidebar to use.
	 *
	 * You would use this function like so: dynamic_sidebar(get_custom_sidebar('id-of-default-sidebar')); 
	 *
	 * @since 1.0
	 * @param string $default_sidebar
	 *
	 */
	public static function get_custom_sidebar($default_sidebar) {
		global $post;
		if ($post->ancestors) {
			// get the parent of this page
			$ancestor = end($post->ancestors);
		} else {
			$ancestor = 0;
		}
		// if this page is the toppmost parent 
		if ($ancestor == 0) {
			// we'll use its ID
			$ancestor = $post->ID;
			// get this page's sidebar value
			if (get_post_meta($post->ID, '_sidebar', true)) {
				$sidebar = get_post_meta($post->ID, '_sidebar', true);
			} else {
				// let's use the default sidebar for this template
				$sidebar = $default_sidebar;
			}
		// okay, it's some subpage
		} else {
			// if this child has its own sidebar assigned, use that
			if (get_post_meta($post->ID, '_sidebar', true)) {
				$sidebar = get_post_meta($post->ID, '_sidebar', true);
			} else { 
				// otherwise we need to first see if this page's parent has a sidebar
				$post_parent = get_post($post->post_parent, 'OBJECT'); 
				$post_ancestor = get_post($ancestor, 'OBJECT'); 
				if (get_post_meta($post_parent->ID,'_sidebar', true)) {
					$sidebar = get_post_meta($post_parent->ID,'_sidebar', true);
					// okay, well does the ancestor have a custom sidebar?
				} elseif (get_post_meta($post_ancestor->ID,'_sidebar', true)) {
					$sidebar = get_post_meta($post_ancestor->ID,'_sidebar', true);
				} else {
					// let's use the one assigned to the ancestor
					$sidebar = $default_sidebar;
				}
			}
		}
		return $sidebar;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Get Excerpt by ID
	 *
	 * Sometimes the functions get_excerpt() and the_excerpt() are not helpful, because they both only work in the Loop
	 * and return different markup (get_excerpt() strips HTML, while the_excerpt() returns content
	 * wrapped in p tags). This function will let you generate an excerpt by passing a post object:
	 *
	 * If there is a manually entered post_excerpt, it will return the content of the post_excerpt raw. Any markup
	 * entered into the Excerpt meta box will be returned as well, and you can use apply_filters('the_content', $output);
	 * on the output to render the content as you would the_excerpt().
	 *
	 * If there is no manual excerpt, the function will get the post_content, apply the_content filter, 
	 * escape and filter out all HTML, then truncate the excerpt to a specified length. 
	 *
	 * @since 1.0
	 * @param object $object
	 * @param int $length
	 *
	 */
	public static function get_excerpt_by_id($object, $length = 55) {
		if ($object->post_excerpt) {
			return $object->post_excerpt;
		} else {
			$output = $object->post_content;
			$output = apply_filters('the_content', $output);
			$output = str_replace('\]\]\>', ']]&gt;', $output);
			$output = strip_tags($output);
			$excerpt_length = 55;
			$words = explode(' ', $output, $length + 1);
			if (count($words)> $length) {
				array_pop($words);
				array_push($words, '');
				$output = implode(' ', $words);
			}
			return $output;
		}
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Check if Page is a Child of another Page
	 *
	 * Recursively looks at ancestors to determine if the given page is a child
	 * of a given page. If no ID is passed, it will check the current post in the Loop. 
	 *
	 * @since 1.0
	 * @param int $target_id
	 * @param int $post_id
	 *
	 */
	public static function is_child_of($target_id, $post_id = null){
		global $post;
		
		if ($post_id == null) {
			$post_id = $post->ID; # no id set so get the post object's id.
		}
		$current = get_page($post_id);
		if ($current->post_parent != 0) {
			# so there is a parent
			if ($current->post_parent != $target_id) {
				return is_child_of($target_id, $current->post_parent); # not that page, run again
			} else {
				return true; # are so it is	
			}
		} else {
			return false; # no parent page so return false
		}
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Time Since
	 *
	 * Transforms a date into a human-friendly relative time. Borrowed from TwentyTen. 
	 *
	 * @since 1.0
	 * @param mixed $older_date
	 * @param mixed $newer_date
	 *
	 */
	public static function get_time_since($older_date, $newer_date = false) {
		// array of time period chunks
		$chunks = array(
			array(60 * 60 * 24 * 365 , 'year'),
			array(60 * 60 * 24 * 30 , 'month'),
			array(60 * 60 * 24 * 7, 'week'),
			array(60 * 60 * 24 , 'day'),
			array(60 * 60 , 'hour'),
			array(60 , 'minute'),
		);
		
		// $newer_date will equal false if we want to know the time elapsed between a date and the current time
		// $newer_date will have a value if we want to work out time elapsed between two known dates
		$newer_date = ($newer_date == false) ? (time()+(60*60*get_settings("gmt_offset"))) : $newer_date;
		
		// difference in seconds
		$since = $newer_date - $older_date;
		
		// we only want to output two chunks of time here, eg:
		// x years, xx months
		// x days, xx hours
		// so there's only two bits of calculation below:

		// step one: the first chunk
		for ($i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			$name = $chunks[$i][1];

			// finding the biggest chunk (if the chunk fits, break)
			if (($count = floor($since / $seconds)) != 0)
				{
				break;
				}
			}

		// set output var
		$output = ($count == 1) ? '1 '.$name : "$count {$name}s";

		// step two: the second chunk
		if ($i + 1 < $j)
			{
			$seconds2 = $chunks[$i + 1][0];
			$name2 = $chunks[$i + 1][1];
			
			if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0)
				{
				// add to output var
				$output .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
				}
			}
		
		return $output;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Get Attachments
	 *
	 * A function to retrieve the attachments of a post. 
	 *
	 * @since 1.0
	 * @param int $post_id The ID of the post you want to get attachments of.
	 * @param string $order Reverse or ascending order, etc
	 * @param string $orderby What field to sort on
	 * @param bool $exclude_featured_thumbnail Should we exclude the featured thumbnail?
	 * @param string $mime Restrict by mime type
	 * @param string $post_status Don't change this
	 * @return object
	 *
	 */
	public static function get_attachments($post_id, $order = 'ASC', $orderby = 'menu_order ID', $exclude_thumbnail = true, $mime = '', $post_status = 'inherit') {
		if ($exclude_thumbnail == true) {
			$attachments = get_children( array('post_parent' => $post_id, 'post_status' => $post_status, 'post_type'=> 'attachment', 'post_mime_type' => $mime, 'order' => $order, 'orderby' => $orderby, 'exclude' => get_post_thumbnail_id($post_id) ));
		} else {
			$attachments = get_children( array('post_parent' => $post_id, 'post_status' => $post_status, 'post_type'=> 'attachment', 'post_mime_type' => $mime, 'order' => $order, 'orderby' => $orderby ) );
		}
		return $attachments;
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Resize Image
	 *
	 * Wraps the WP Image Editor.
	 * All crops generated by this function go into the /wpx/ folder.
	 * Useful for passing crops to responsive load methods.
	 * 
	 * @since 1.0
	 */
	public static function resize( $path, $max_width = null, $max_height = null, $crop = true, $args = '') {

		// determine base path for uploads
		$upload_dir = wp_upload_dir();

		// let's specify some common defaults
		$defaults = array(
			'quality' => 100,
			'suffix' => null,
			'uploads_url' => $upload_dir['baseurl'],
			'uploads_directory' => $upload_dir['basedir'],
			'crops_directory' => '/wpx/',
			'filetype' => null
		);

		// standard WP default arguments merge
		$parameters = wp_parse_args( $args, $defaults );
		extract( $parameters, EXTR_SKIP );

		// get the path
		$system_root = $_SERVER[ 'DOCUMENT_ROOT' ];
		$file_path = parse_url($path);
		$image = wp_get_image_editor($system_root.$file_path['path']);

		// only do stuff on success
		if ( ! is_wp_error( $image ) ) {
			// resize the image
			if ($max_width || $max_height) { $image->resize( $max_width, $max_height, $crop ); }
			// set the quality
			if ($parameters['quality']) $image->set_quality( $parameters['quality'] );
			// rotate the image
			if ($parameters['rotate']) $image->rotate( $parameters['rotate'] );
			// flip the image
			if ($parameters['flip']) $image->flip( true, false );
			// crop the image
			if ($parameters['crop']) $image->crop( $parameters['crop'] );
			// generate the filename
			$filename = $image->generate_filename($parameters['suffix'], $parameters['uploads_directory'].$parameters['crops_directory'], $parameters['filetype'] );
			// save the image
			$image_meta = $image->save($filename);
			// insert the URL path to the crop
			$image_meta['url'] = $parameters['uploads_url'].$parameters['crops_directory'].$image_meta['file'];
			// return the data
			return $image_meta;
		}
	}

	/*
	|--------------------------------------------------------------------------
	/**
	 * Gravatar Exists
	 *
	 * Utility function to check if a gravatar exists for a given email or id
	 * @param int|string|object $id_or_email A user ID,  email address, or comment object
	 * @return bool if the gravatar exists or not
	 */
	public static function validate_gravatar($id_or_email) {
		// id or email code borrowed from wp-includes/pluggable.php
		$email = '';
		if ( is_numeric($id_or_email) ) {
			$id = (int) $id_or_email;
			$user = get_userdata($id);
			if ( $user )
				$email = $user->user_email;
		} elseif ( is_object($id_or_email) ) {
			// No avatar for pingbacks or trackbacks
			$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
			if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
				return false;
	 
			if ( !empty($id_or_email->user_id) ) {
				$id = (int) $id_or_email->user_id;
				$user = get_userdata($id);
				if ( $user)
					$email = $user->user_email;
			} elseif ( !empty($id_or_email->comment_author_email) ) {
				$email = $id_or_email->comment_author_email;
			}
		} else {
			$email = $id_or_email;
		}
	 
		$hashkey = md5(strtolower(trim($email)));
		$uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';
	 
		$data = wp_cache_get($hashkey);
		if (false === $data) {
			$response = wp_remote_head($uri);
			if( is_wp_error($response) ) {
				$data = 'not200';
			} else {
				$data = $response['response']['code'];
			}
		    wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);
	 
		}		
		if ($data == '200'){
			return true;
		} else {
			return false;
		}
	}

}