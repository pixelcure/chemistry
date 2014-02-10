<?php
/*
Plugin Name: WP Extend
Plugin URI: http://www.dquinn.net/wpx/
Description: A developer-centric framework for creating custom post types, taxonomies, metaboxes, options pages and more.
Version: 1.0
Author: Daniel Quinn
Author URI: http://www.dquinn.net
License: GPL2
*/

/*
Copyright 2012 Daniel Quinn (email: daniel@dquinn.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!class_exists('wpx_core')) {

	class wpx_core {

		// let's set everything up.
		public function __construct() {

			// include the wpx core functions
			require_once('core/utility.php');

			// fire this up on init
			add_action("init",array('wpx',"init"));

			// everything else
			require_once('core/taxonomies.php');
			require_once('core/fields.php');
			require_once('core/options.php');
			require_once('core/types.php');

			// register the static cpts
			add_action('init', array($this,'create_fields_type'));
			add_action('init', array($this,'create_post_types'));
			add_action('init', array($this,'create_taxonomy_type'));
			add_action('init', array($this,'create_option_type'));

			// register all dynamic cpts and taxes
			add_action('init', array($this,'register_custom_taxonomies'));
			add_action('init', array($this,'register_custom_post_types'));
			add_action('init', array($this, 'register_options_types'));

		}

		/*
		|--------------------------------------------------------------------------
		 * Internal WPx CPTs
		|--------------------------------------------------------------------------
		*/

		/*
		|--------------------------------------------------------------------------
		/**
		 * Options Pages
		 *
		 * Here we define a cpt to manage options pages in the Dashboard.
		 *
		 * @since 1.0
		 *
		 */
		public function create_option_type() {

				// define metaboxes 
				$metaboxes = array( 
					'Basics' => array(
						array( 'collapsed' => false, 'order'=>40),
						array( 'id'=>'_wpx_options_menu_label', 'label'=>'Menu Label', 'description'=>'Enter the label to display on the menu tab.', 'field'=>'text', 'required'=>true),
						array( 'id'=>'_wpx_options_menu_parent', 'label'=>'Menu Parent', 'description'=>'This is an existing top level menu that this option page will appear underneath. This applies only to pages that exist in the Dashboard already. For example: edit.php?post_type=books for a custom post type. Other options include: index.php (Dashboard); edit.php (Posts); upload.php (Media); link-manager.php (Links); edit.php?post_type=page (Page); edit-comments.php (Comments); themes.php (Themes); plugins.php (Plugins); users.php (Users); tools.php (Tools); Settings (options-general.php). To make this option page\'s menu item appear underneath a top-level custom options page, just assign this option page\'s post as the child of the top-level options page.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_options_register_metaboxes', 'label'=>'Metaboxes', 'description'=>'Choose meta fields to assign to this options page.', 'field'=>'wpx_select_fields', 'required'=>true),
					),
					'UI Settings' => array(
						array( 'collapsed' => true, 'order'=>30),
						array( 'id'=>'_wpx_options_screen_icon', 'label'=>'Screen Icon', 'description'=>'Indicate which screen icon should be used next to the title of the options page. Some valid values are: dashboard, posts, media, links, pages, comments, themes, plugins, users, management, options-general.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_options_icon_url', 'label'=>'Menu Icon', 'description'=>'Upload an optional icon to use in menu tab.', 'field'=>'image', 'required'=>false),
						array( 'id'=>'_wpx_options_menu_position', 'label'=>'Menu Position', 'description'=>' The position in the menu order this menu should appear. By default, if this parameter is omitted, the menu will appear at the bottom of the menu structure. The higher the number, the lower its position in the menu. WARNING: if two menu items use the same position attribute, one of the items may be overwritten so that only one item displays! Risk of conflict can be reduced by using decimal instead of integer values, e.g. 63.3 instead of 63 (Note: Use quotes in code, IE 63.3).', 'field'=>'number', 'required'=>false)
					),
					'Advanced Configuration' => array(
						array( 'collapsed' => true, 'order'=>20),
						array( 'id'=>'_wpx_options_capability', 'label'=>'Capability', 'description'=>'Enter the capability that the options page requires in order to be viewed/edited. See: <a target="_blank" href="http://codex.wordpress.org/Roles_and_Capabilities">http://codex.wordpress.org/Roles_and_Capabilities</a>.', 'field'=>'text', 'required'=>true)
						//array( 'id'=>'_wpx_options_validation', 'label'=>'Validation Routines', 'description'=>'Optionally, enter the validation routines you would like to run on meta fields attached to this page. Enter these as string pairs, one per line, like so: _wpx_my_meta_field, nameOfFunction. The first string before the comma is the ID of the meta field; the second string is the name of the validation function.', 'field'=>'textarea', 'required'=>false)
					)
				);

				// register options post type
				$options = new wpx_register_type(
					'wpx_options', // slug/id for the post type
					array(
						'label_singular' => 'Options Page',
						'label_plural' => 'Options Pages',
						'hierarchical'=> true,
						'supports' => array('title','page-attributes'),
						'menu_position' => 181,
						'register_metaboxes' => $metaboxes,
						'capabilities' => array(
							'publish_posts' => 'manage_options',
							'edit_posts' => 'manage_options',
							'edit_others_posts' => 'manage_options',
							'delete_posts' => 'manage_options',
							'delete_others_posts' => 'manage_options',
							'read_private_posts' => 'manage_options',
							'edit_post' => 'manage_options',
							'delete_post' => 'manage_options',
							'read_post' => 'manage_options',
						)
					)
				);

				// attach custom columns for admin
				add_filter('manage_edit-wpx_options_columns', array($this,'extend_options_columns'));
				add_action('manage_wpx_options_posts_custom_column', array($this,'extend_options_post_list'), 10, 2);

		}

		/*
		|--------------------------------------------------------------------------
		/**
		 * Fields Type
		 *
		 * Fields are GUIs for custom fields, organized by Groups.
		 * We assign fields to custom post types.
		 *
		 * @since 1.0
		 *
		 */
		public function create_fields_type() {

				// define metaboxes 
				$metaboxes = array( 
					'Configuration' => array(
						array('order'=>1000),
						array( 'id'=>'_wpx_fields_type', 'label'=>'Type', 'description'=>'After selecting your field type and saving, you may be presented with additional configuration options specific to the field type you selected.', 'field'=>'wpx_select_types', 'required'=>true),
						array( 'id'=>'_wpx_fields_label', 'label'=>'Label', 'description'=>'This is the label that appears above the metabox.', 'field'=>'text', 'required'=>true),
						array( 'id'=>'_wpx_fields_description', 'label'=>'Description', 'description'=>'Additional information describing this field and/or instructions on how to enter the content.', 'field'=>'textarea', 'required'=>false),
						//array( 'id'=>'_wpx_fields_required', 'label'=>'Required', 'description'=>'Check this box if the field is required.', 'field'=>'checkbox', 'required'=>false) TK
					),
					'Relationship Options' => array(
						array('order'=>1),
						array( 'id'=>'_wpx_fields_post_multiple', 'label'=>'Multiple Selection?', 'description'=>'Check this box if the user should be able to choose more than 1 post from the relationship.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_fields_post_objects', 'label'=>'Data Source', 'description'=>'Choose the post types the user may choose from.', 'field'=>'wpx_select_object_type', 'required'=>false)
					),
					'User Options' => array(
						array('order'=>1),
						array( 'id'=>'_wpx_fields_user_multiple', 'label'=>'Multiple Selection?', 'description'=>'Check this box if the user should be able to choose more than 1 user from the relationship.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_fields_user_roles', 'label'=>'Data Source', 'description'=>'Choose the user roles the user may choose from.', 'field'=>'wpx_select_user_roles', 'required'=>false)
					),
					'Taxonomy Options' => array(
						array('order'=>1),
						array( 'id'=>'_wpx_fields_term_multiple', 'label'=>'Multiple Selection?', 'description'=>'Check this box if the user should be able to choose more than 1 term from the relationship.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_fields_term_objects', 'label'=>'Data Source', 'description'=>'Choose the taxonomies the user may choose from.', 'field'=>'wpx_select_taxonomies', 'required'=>false)
					),
					'Gallery Options' => array(
						array('order'=>1),
						array( 'id'=>'_wpx_fields_gallery_cpt', 'label'=>'Data Source', 'description'=>'Choose which post type will contain your galleries for this field.', 'field'=>'wpx_select_object_type', 'required'=>false)
					)
				);

				// define groups
				$groups = array(
					'wpx_groups', 
					array(
						'label_singular' => 'Group',
						'label_plural' => 'Groups',
						'object_type' => array('wpx_fields'),
						'register_metaboxes' => array(
							array( 'id'=>'_wpx_groups_collapsed', 'label'=>'Collapse Tab?', 'description'=>'Should the metabox be collapsed by default?', 'field'=>'checkbox', 'required'=>false),
							array( 'id'=>'_wpx_groups_order', 'label'=>'Order', 'description'=>'Enter a number to define the order in which this Group should appear in a post type. Use increments of 5 to account for future Groups, and remember that higher numbers mean closer to the top of the page.', 'field'=>'number', 'required'=>false)
						)
					),
				);

				// register fields post type
				$fields = new wpx_register_type(
					'wpx_fields', // slug/id for the post type
					array(
						'label_singular' => 'Meta Field',
						'label_plural' => 'Meta Fields',
						'hierarchical' => true,
						'supports' => array('title','page-attributes'),
						'menu_position' => 179,
						'register_taxonomies' => array($groups),
						'register_metaboxes' => $metaboxes,
						'capabilities' => array(
							'publish_posts' => 'manage_options',
							'edit_posts' => 'manage_options',
							'edit_others_posts' => 'manage_options',
							'delete_posts' => 'manage_options',
							'delete_others_posts' => 'manage_options',
							'read_private_posts' => 'manage_options',
							'edit_post' => 'manage_options',
							'delete_post' => 'manage_options',
							'read_post' => 'manage_options',
						)
					)
				);

				// attach custom columns for admin
				add_filter('manage_edit-wpx_fields_columns', array($this,'extend_fields_columns'));
				add_action('manage_wpx_fields_posts_custom_column', array($this,'extend_fields_post_list'), 10, 2);
		}

		/*
		|--------------------------------------------------------------------------
		/**
		 * Custom Taxonomies
		 *
		 * The post type we'll use to dynamically register custom taxonomies from the Dashboard.
		 *
		 * @since 1.0
		 *
		 */
		public function create_taxonomy_type() {

				// define metaboxes for taxonomy
				$metaboxes = array( 
					'Name' => array(
						array('order'=>40),
						array( 'id'=>'_wpx_taxonomy_label_plural', 'label'=>'Plural Name', 'description'=>'What is the taxonomy called in the plural case? If you enter a name, all other labels will be filled out for you.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_label_singular', 'label'=>'Singular Name', 'description'=>'What is the taxonomy called in the singular case? If you enter a name, all other labels will be filled out for you.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_description', 'label'=>'Description', 'description'=>' A short descriptive summary of what the taxonomy is.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_object_type', 'label'=>'Object Type', 'description'=>'Choose object type(s) to associate this taxonomy with.', 'field'=>'wpx_select_object_type', 'required'=>true)
					),
					'Metaboxes' => array(
						array( 'collapsed' => true, 'order'=>35),
						array( 'id'=>'_wpx_taxonomy_register_metaboxes', 'label'=>'Fields', 'description'=>'Select the fields that you would like to assign to this taxonomy.', 'field'=>'wpx_select_fields', 'required'=>false)
					),
					'Labels' => array(
						array( 'collapsed' => true, 'order'=>30),
						array( 'id'=>'_wpx_taxonomy_name', 'label'=>'Name', 'description'=>'A plural descriptive name for the taxonomy marked for translation.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_singular_name', 'label'=>'Singular Name', 'description'=>'Name for one object of this taxonomy. Default is _x( \'Post Tag\', \'taxonomy singular name\' ) or _x( \'Category\', \'taxonomy singular name\' ). When internationalizing this string, please use a gettext context matching your post type. Example: _x(\'Writer', 'taxonomy singular name\');', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_menu_name', 'label'=>'Menu Name', 'description'=>'The menu name text. This string is the name to give menu items. Defaults to value of name.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_all_items', 'label'=>'All Items', 'description'=>'The all items text. Default is __( \'All Tags\' ) or __( \'All Categories\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_edit_item', 'label'=>'Edit Item', 'description'=>'The edit item text. Default is __( \'Edit Tag\' ) or __( \'Edit Category\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_view_item', 'label'=>'View Item', 'description'=>'The view item text, Default is __( \'View Tag\' ) or __( \'View Category\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_update_item', 'label'=>'Update Item', 'description'=>'The update item text. Default is __( \'Update Tag\' ) or __( \'Update Category\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_add_new_item', 'label'=>'Add New Item', 'description'=>'The add new item text. Default is __( \'Add New Tag\' ) or __( \'Add New Category\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_new_item_name', 'label'=>'New Item Name', 'description'=>'The new item name text. Default is __( \'New Tag Name\' ) or __( \'New Category Name\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_parent_item', 'label'=>'Parent Item', 'description'=>'The parent item text. This string is not used on non-hierarchical taxonomies such as post tags. Default is null or __( \'Parent Category\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_parent_item_colon', 'label'=>'Parent Item Colon', 'description'=>'The same as parent_item, but with colon : in the end null, __( \'Parent Category:\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_search_items', 'label'=>'Search Items', 'description'=>'The search items text. Default is __( \'Search Tags\' ) or __( \'Search Categories\' ).', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_popular_items', 'label'=>'Popular Items', 'description'=>'The popular items text. Default is __( \'Popular Tags\' ) or null.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_separate_items_with_commas', 'label'=>'Separate Items with Commas', 'description'=>'The separate item with commas text used in the taxonomy meta box. This string isn\'t used on hierarchical taxonomies. Default is __( \'Separate tags with commas\' ), or null.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_add_or_remove_items', 'label'=>'Add or Remove Items', 'description'=>'The add or remove items text and used in the meta box when JavaScript is disabled. This string isn\'t used on hierarchical taxonomies. Default is __( \'Add or remove tags\' ) or null.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_choose_from_most_used', 'label'=>'Choose from Most Used', 'description'=>'The choose from most used text used in the taxonomy meta box. This string isn\'t used on hierarchical taxonomies. Default is __( \'Choose from the most used tags\' ) or null.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_not_found', 'label'=>'Not Found', 'description'=>'The text displayed via clicking \'Choose from the most used tags\' in the taxonomy meta box when no tags are available. This string isn\'t used on hierarchical taxonomies. Default is __( \'No tags found.\' ) or null.', 'field'=>'text', 'required'=>false)
					),
					'UI Settings' => array(
						array( 'collapsed' => true, 'order'=>20),
						array( 'id'=>'_wpx_taxonomy_hierarchical', 'label'=>'Hierarchical', 'description'=>'Is this taxonomy hierarchical (can it have descendant) like categories or not hierarchical like tags?', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_show_ui', 'label'=>'Show UI', 'description'=>'Whether to generate a default UI for managing this taxonomy.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_show_in_nav_menus', 'label'=>'Show in Nav Menus', 'description'=>'True makes this taxonomy available for selection in navigation menus.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_show_tagcloud', 'label'=>'Show Tag Cloud', 'description'=>'Whether to allow the Tag Cloud widget to use this taxonomy.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_show_admin_column', 'label'=>'Show in Admin Column', 'description'=>'Whether to allow automatic creation of taxonomy columns on associated post-types. (Available since 3.5).', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_public', 'label'=>'Public', 'description'=>'Should this taxonomy be exposed in the admin UI.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_update_count_callback', 'label'=>'Update Count Callback Hook', 'description'=>'A function name that will be called when the count of an associated $object_type, such as post, is updated. Works much like a hook.', 'field'=>'text', 'required'=>false)
					),
					'Capabilities' => array(
						array( 'collapsed' => true, 'order'=>10),
						array( 'id'=>'_wpx_taxonomy_capabilities', 'label'=>'Capabilities', 'description'=>'An array of the capabilities for this taxonomy. Enter each capability as a comma-separated string.', 'field'=>'wpx_taxonomy_capabilities', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy_sort', 'label'=>'Sort', 'description'=>'Whether this taxonomy should remember the order in which terms are added to objects.', 'field'=>'checkbox', 'required'=>false)
					),
					'Permalink Settings' => array(
						array( 'collapsed' => true, 'order'=>0),
						array( 'id'=>'_wpx_taxonomy_rewrite', 'label'=>'Rewrite', 'description'=>'Set to false to prevent automatic URL rewriting a.k.a. "pretty permalinks". Pass an $args array to override default URL settings for permalinks.', 'field'=>'wpx_taxonomy_rewrite', 'required'=>false),
						array( 'id'=>'_wpx_taxonomy__builtin', 'label'=>'Built In', 'description'=>'Whether this taxonomy is a native or "built-in" taxonomy. Note: this Codex entry is for documentation - core developers recommend you don\'t use this when registering your own taxonomy', 'field'=>'checkbox', 'required'=>false)
					)
				);

				// register taxonomies cpt
				$taxonomies = new wpx_register_type(
					'wpx_taxonomy', 
					array(
						'label_singular' => 'Taxonomy',
						'label_plural' => 'Taxonomies',
						'supports' => array('title','page-attributes'),
						'menu_position' => 180,
						'register_metaboxes' => $metaboxes,
						'capabilities' => array(
							'publish_posts' => 'manage_options',
							'edit_posts' => 'manage_options',
							'edit_others_posts' => 'manage_options',
							'delete_posts' => 'manage_options',
							'delete_others_posts' => 'manage_options',
							'read_private_posts' => 'manage_options',
							'edit_post' => 'manage_options',
							'delete_post' => 'manage_options',
							'read_post' => 'manage_options',
						)
					)
				);

				// attach custom columns for admin
				add_filter('manage_edit-wpx_taxonomy_columns', array($this,'extend_taxonomies_columns'));
				add_action('manage_wpx_taxonomy_posts_custom_column', array($this,'extend_taxonomies_post_list'), 10, 2);

		}

		/*
		|--------------------------------------------------------------------------
		/**
		 * Custom Post Types
		 *
		 * The post type we'll use to dynamically register custom post types from the Dashboard.
		 *
		 * @since 1.0
		 *
		 */
		public function create_post_types() {

				// define metaboxes
				$metaboxes = array( 
					'Name' => array(
						array('order'=>40),
						array( 'id'=>'_wpx_cpt_label_plural', 'label'=>'Plural Name', 'description'=>'What is the post type called in the plural case?', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_label_singular', 'label'=>'Singular Name', 'description'=>'What is the post type called in the singular case?', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_label_description', 'label'=>'Description', 'description'=>' A short descriptive summary of what the post type is.', 'field'=>'text', 'required'=>false)
					),
					'Metaboxes' => array(
						array( 'collapsed' => true, 'order'=>35),
						array( 'id'=>'_wpx_cpt_metaboxes', 'label'=>'Fields', 'description'=>'Select the fields that you would like to assign to this post type. Each field will appear in a metabox that is defined by the Group to which you have assigned the field.', 'field'=>'wpx_select_fields', 'required'=>false),
						array( 'id'=>'_wpx_cpt_supports', 'label'=>'Supports', 'description'=>'An alias for calling add_post_type_support() directly. As of 3.5, boolean false can be passed as value instead of an array to prevent default (title and editor) behavior.', 'field'=>'wpx_select_supports', 'required'=>false)
					),
					'Labels' => array(
						array( 'collapsed' => true, 'order'=>30),
						array( 'id'=>'_wpx_cpt_name', 'label'=>'Name', 'description'=>'General name for the post type, usually plural.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_singular_name', 'label'=>'Name', 'description'=>'Name for one object of this post type. Defaults to value of name.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_menu', 'label'=>'Menu Name', 'description'=>'The menu name text. This string is the name to give menu items.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_all', 'label'=>'All Items', 'description'=>'The all items text used in the menu.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_add_new', 'label'=>'Add New', 'description'=>'The add new text. The default is Add New for both hierarchical and non-hierarchical types.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_add_new_item', 'label'=>'Add New Item', 'description'=>'The add new item text. Default is Add New Post/Add New Page.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_edit_item', 'label'=>'Edit Item', 'description'=>'The edit item text. Default is Edit Post/Edit Page.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_new_item', 'label'=>'New Item', 'description'=>'The new item text. Default is New Post/New Page.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_view_item', 'label'=>'View Item', 'description'=>'The view item text. Default is View Post/View Page.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_search_items', 'label'=>'Search Items', 'description'=>'The search items text. Default is Search Posts/Search Pages.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_not_found', 'label'=>'Not Found', 'description'=>'The not found text. Default is No posts found/No pages found.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_not_found_in_trash', 'label'=>'Not Found in Trash', 'description'=>'The not found in trash text. Default is No posts found in Trash/No pages found in Trash.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_parent_item', 'label'=>'Parent Item', 'description'=>'the parent text. This string isn\'t used on non-hierarchical types. In hierarchical ones the default is Parent Page.', 'field'=>'text', 'required'=>false)
					),
					'Relationships' => array(
						array( 'collapsed' => true, 'order'=>25),
						array( 'id'=>'_wpx_cpt_hierarchical', 'label'=>'Hierarchical', 'description'=>'Whether the post type is hierarchical (e.g. page). Allows Parent to be specified. The \'supports\' parameter should contain \'page-attributes\' to show the parent select box on the editor page.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt_taxonomies', 'label'=>'Taxonomies', 'description'=>'An array of registered taxonomies like category or post_tag that will be used with this post type. This can be used in lieu of calling register_taxonomy_for_object_type() directly. Custom taxonomies still need to be registered with register_taxonomy().', 'field'=>'wpx_select_taxonomies', 'required'=>false),
						array( 'id'=>'_wpx_cpt_has_archive', 'label'=>'Has Archive', 'description'=>'Enables post type archives. Will use $post_type as archive slug by default.', 'field'=>'checkbox', 'required'=>false)
					),
					'UI Settings' => array(
						array( 'collapsed' => true, 'order'=>20),
						array( 'id'=>'_wpx_cpt_show_ui', 'label'=>'Show UI', 'description'=>'Whether to generate a default UI for managing this post type in the admin.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt_show_in_nav_menus', 'label'=>'Show in Nav Menus', 'description'=>'Whether post_type is available for selection in navigation menus.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt_show_in_menu', 'label'=>'Show in Menu', 'description'=>'Whether post_type is available for selection in navigation menus.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_show_in_admin_bar', 'label'=>'Show in Admin Bar', 'description'=>'Whether to make this post type available in the WordPress admin bar.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt_menu_position', 'label'=>'Menu Position', 'description'=>'The position in the menu order the post type should appear. show_in_menu must be true.', 'field'=>'number', 'required'=>false),
						array( 'id'=>'_wpx_cpt_menu_icon', 'label'=>'Menu Icon', 'description'=>'The url to the icon to be used for this menu.', 'field'=>'image', 'required'=>false)
					),
					'Query Settings' => array(
						array( 'collapsed' => true, 'order'=>15),
						array( 'id'=>'_wpx_cpt_public', 'label'=>'Public', 'description'=>'Whether a post type is intended to be used publicly either via the admin interface or by front-end users.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt_exclude_from_search', 'label'=>'Exclude from Search', 'description'=>'Whether to exclude posts with this post type from front end search results.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt_publicly_queryable', 'label'=>'Publicly Queryable', 'description'=>' Whether queries can be performed on the front end as part of parse_request().', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt_query_var', 'label'=>'Query Var', 'description'=>'Sets the query_var key for this post type.', 'field'=>'text', 'required'=>false)
					),
					'Capabilities' => array(
						array( 'collapsed' => true, 'order'=>10),
						array( 'id'=>'_wpx_cpt_capability_type', 'label'=>'Capability Type', 'description'=>'The string to use to build the read, edit, and delete capabilities. May be passed as an array to allow for alternative plurals when using this argument as a base to construct the capabilities, e.g. array(\'story\', \'stories\'). By default the capability_type is used as a base to construct capabilities. It seems that `map_meta_cap`needs to be set to true, to make this work.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_capabilities', 'label'=>'Capabilities', 'description'=>'An array of the capabilities for this post type.', 'field'=>'wpx_capabilities', 'required'=>false),
						array( 'id'=>'_wpx_cpt_map_meta_cap', 'label'=>'Meta Capability Mapping', 'description'=>'Whether to use the internal default meta capability handling.', 'field'=>'checkbox', 'required'=>false)
					),
					'Permalink Settings' => array(
						array( 'collapsed' => true, 'order'=>00),
						array( 'id'=>'_wpx_cpt_permalink_epmask', 'label'=>'Permalink EP Mask', 'description'=>'The default rewrite endpoint bitmasks. For more info see Trac Ticket 12605 and this Make WordPress Plugins summary of endpoints.', 'field'=>'text', 'required'=>false),
						array( 'id'=>'_wpx_cpt_rewrite', 'label'=>'Rewrite', 'description'=>'Triggers the handling of rewrites for this post type. To prevent rewrites, set to false. Default: true and use $post_type as slug.', 'field'=>'wpx_cpt_rewrite', 'required'=>false),
						array( 'id'=>'_wpx_cpt_can_export', 'label'=>'Can Export', 'description'=>'Can this post_type be exported.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt__builtin', 'label'=>'Built In', 'description'=>'Whether this post type is a native or "built-in" post_type. Note: this Codex entry is for documentation - core developers recommend you don\'t use this when registering your own post type.', 'field'=>'checkbox', 'required'=>false),
						array( 'id'=>'_wpx_cpt__edit_link', 'label'=>'Edit Link', 'description'=>'Link to edit an entry with this post type. Note: this Codex entry is for documentation - core developers recommend you don\'t use this when registering your own post type.', 'field'=>'checkbox', 'required'=>false)
					)
				);

				// register cpt post type
				$fields = new wpx_register_type(
					'wpx_types',
					array(
						'label_singular' => 'Post Type',
						'label_plural' => 'Post Types',
						'supports' => array('title'),
						'menu_position' => 178,
						'register_metaboxes' => $metaboxes,
						'capabilities' => array(
							'publish_posts' => 'manage_options',
							'edit_posts' => 'manage_options',
							'edit_others_posts' => 'manage_options',
							'delete_posts' => 'manage_options',
							'delete_others_posts' => 'manage_options',
							'read_private_posts' => 'manage_options',
							'edit_post' => 'manage_options',
							'delete_post' => 'manage_options',
							'read_post' => 'manage_options',
						)
					)
				);

				// attach custom columns for cpt
				add_filter('manage_edit-wpx_types_columns', array($this,'extend_cpts_columns'));
				add_action('manage_wpx_types_posts_custom_column', array($this,'extend_cpts_post_list'), 10, 2);

		}

		/*
		|--------------------------------------------------------------------------
		 * Processes
		|--------------------------------------------------------------------------
		*/

		/*
		|--------------------------------------------------------------------------
		/**
		 * Register Custom Post Types
		 *
		 * The process we'll use to run through all CPTs created in the Dashboard to register
		 * CPTs in WordPress dynamically.
		 *
		 * @since 1.0
		 *
		 */
		public function register_custom_post_types() {

				// get all cpts in the Dashboard
				$post_types = get_posts(array('posts_per_page'=>-1, 'post_type'=>'wpx_types'));

				foreach($post_types as $post_type) {

					// get all metadata
					$attributes = get_post_custom($post_type->ID);

					// reset the args
					$args = array();
					$rewrites = false;
					$rewrite_values = false;

					// go through each custom field

					foreach($attributes as $i=>$attribute) {

						// kick out custom field default stuff from WP
						if($i == '_edit_lock' || $i == '_edit_last') continue;

						// take everything out of an array format
						$attribute = $attribute[0];

						// we need to handle the strings entered for the rewrite array
						if ( $i == '_wpx_cpt_rewrite' ) {
							if ($attribute) {
								$rewrite_values = explode(',', $attribute);
								if ($rewrite_values[0]) $rewrites['slug'] = $rewrite_values[0];
								if ($rewrite_values[1]) $rewrites['with_front'] = $rewrite_values[1];
								if ($rewrite_values[2]) $rewrites['feeds'] = $rewrite_values[2];
								if ($rewrite_values[3]) $rewrites['pages'] = $rewrite_values[3];
								if ($rewrite_values[4]) $rewrites['ep_mask'] = $rewrite_values[4];
								if ($rewrite_values[5] == 1) {
									$rewrites = false;
								}
							}
						}

						$args['rewrite'] = $rewrites;
						unset($args['_wpx_cpt_rewrite']);

						// remove the wpx prefix so it matches the args in register post type
						$args[str_replace('_wpx_cpt_','',$i)] = $attribute;

						// if we have a comma, it needs to become an array
						if (strpos($attribute,',') !== false) {
							$args[str_replace('_wpx_cpt_','',$i)] = explode(',', $attribute);
						}

						//print_r($args['rewrite']);

						// turn all "false", "true" into proper booleans
						if (filter_var($attribute, FILTER_VALIDATE_BOOLEAN)) $args[str_replace('_wpx_cpt_','',$i)] = filter_var($attribute, FILTER_VALIDATE_BOOLEAN);

						//print_r($args['rewrite']);

						// if a singular & plural name was specified, pass this into the arguments
						if ($args['label_singular'] && $args['label_plural']) {
							// do nothing, this will be handled internally (in the types class)
						} else {
							// otherwise,  reorganize the individual labels under a labels array
							if ($args['name']) { $args['labels']['name'] = $args['name']; }
							if ($args['singular_name']) { $args['labels']['singular_name'] = $args['singular_name']; }
							if ($args['menu_name']) { $args['labels']['menu_name'] = $args['menu_name']; }
							if ($args['all_items']) { $args['labels']['all_items'] = $args['name']; }
							if ($args['add_new']) { $args['labels']['add_new'] = $args['add_new']; }
							if ($args['add_new_item']) { $args['labels']['add_new_item'] = $args['add_new_item']; }
							if ($args['edit_item']) { $args['labels']['edit_item'] = $args['edit_item']; }
							if ($args['new_item']) { $args['labels']['new_item'] = $args['new_item']; }
							if ($args['view_item']) { $args['labels']['view_item'] = $args['view_item']; }
							if ($args['search_items']) { $args['labels']['search_items'] = $args['search_items']; }
							if ($args['not_found']) { $args['labels']['not_found'] = $args['not_found']; }
							if ($args['not_found_in_trash']) { $args['labels']['not_found_in_trash'] = $args['not_found_in_trash']; }
							if ($args['parent_item_colon']) { $args['labels']['parent_item_colon'] = $args['parent_item_colon']; }
						}

						// then kick out the labels
						unset($args['name']);
						unset($args['singular_name']);
						unset($args['menu_name']);
						unset($args['all_items']);
						unset($args['add_new']);
						unset($args['add_new_item']);
						unset($args['edit_item']);
						unset($args['new_item']);
						unset($args['view_item']);
						unset($args['search_items']);
						unset($args['not_found']);
						unset($args['not_found_in_trash']);
						unset($args['parent_item_colon']);

						// the same for capabilities
						if ( is_array($args['capabilities']) ) {
							foreach($args['capabilities'] as $i=>$capability) {
								if ($i == 0 && $capability) $rename_capabilities['edit_post'] = $capability;
								if ($i == 1 && $capability) $rename_capabilities['read_post'] = $capability;
								if ($i == 2 && $capability) $rename_capabilities['delete_post '] = $capability;
								if ($i == 3 && $capability) $rename_capabilities['edit_posts'] = $capability;
								if ($i == 4 && $capability) $rename_capabilities['edit_others_posts'] = $capability;
								if ($i == 5 && $capability) $rename_capabilities['publish_posts'] = $capability;
								if ($i == 6 && $capability) $rename_capabilities['read_private_posts'] = $capability;
								if ($i == 7 && $capability) $rename_capabilities['read'] = $capability;
								if ($i == 8 && $capability) $rename_capabilities['delete_posts'] = $capability;
								if ($i == 9 && $capability) $rename_capabilities['delete_private_posts'] = $capability;
								if ($i == 10 && $capability) $rename_capabilities['delete_published_posts'] = $capability;
								if ($i == 11 && $capability) $rename_capabilities['delete_others_posts'] = $capability;
								if ($i == 12 && $capability) $rename_capabilities['edit_private_posts'] = $capability;
								if ($i == 13 && $capability) $rename_capabilities['edit_published_posts'] = $capability;
							}
							$args['capabilities'] = $rename_capabilities;
						}

					}

					// get all fields attached to this cpt
					$fields_meta = get_post_meta($post_type->ID, '_wpx_cpt_metaboxes', true);
					$fields_ids = explode(',', $fields_meta);
					$fields = get_posts(array('post_type'=>'wpx_fields', 'post__in'=>$fields_ids, 'orderby'=>'menu_order', 'posts_per_page'=>-1, 'order'=>'ASC'));

					// reset this to empty
					$groups = array();

					// get all groups
					// what we're doing here is making it possible later
					// to sort by groups and insert other "settings" for metaboxes
					// in the future
					foreach($fields as $field) {
						$group = wp_get_object_terms($field->ID, 'wpx_groups');
						if ($group) {

							// groups have order and required fields as custom fields
							$order = wpx::get_taxonomy_meta($group[0]->term_id, '_wpx_groups_order', true);
							$required = wpx::get_taxonomy_meta($group[0]->term_id, '_wpx_groups_collapsed', true);

							// reset the settings array
							$settings = array();

							// we insert "required" as a key in the first array of the groups 
							// metabox array, which we'll retrieve later
							if ($required) {
								$settings['collapsed'] = true;
								$groups[$group[0]->name][0] = $settings;
							}

							// same for order
							if ($order) {
								$settings['order'] = $order;
								$groups[$group[0]->name][0] = $settings;
							} else {
								$settings['order'] = 0;
								$groups[$group[0]->name][0] = $settings;
							}
							$groups[$group[0]->name][] = $field;
						} else {
							$groups['Settings'][] = $field;
						}
					}

					// reset this to empty
					$metaboxes = array();

					// build proper metabox arrays
					// sorted by group
					if (is_array($groups)) {
	 					foreach($groups as $i=>$group) {
	 						foreach($group as $x=>$field) {
		 						if ($x == 0 && !$field->ID) {
		 							// then this is the settings array
		 							$metaboxes[$i][0] = $field;
		 						} else {
									$required = get_post_meta($field->ID, '_wpx_fields_required', true);
									$metaboxes[$i][] = array(
										'id'=>'_'.$post_type->post_name.'_'.$field->post_name,
										'label'=>get_post_meta($field->ID, '_wpx_fields_label', true),
										'description'=>get_post_meta($field->ID, '_wpx_fields_description', true),
										'field'=>get_post_meta($field->ID, '_wpx_fields_type', true),
										'required'=>filter_var($required, FILTER_VALIDATE_BOOLEAN)
									);
								}
							}
						}
						if ($metaboxes) {
							// add the metaboxes to the args
							$args['register_metaboxes'] = $metaboxes;
						}
					}

					// for whatever reason we sometimes get empty capabilities
					// WP chokes if an empty capability array is passed
					if ( !is_array($args['capabilities']) ) {
						unset($args['capabilities']);
					}

					if ( !is_array($args['rewrite']) ) {
						unset($args['rewrite']);
					}

					// make taxonomies an array always
					if (!is_array($args['taxonomies'])) {
						$args['taxonomies'] = explode(',', $args['taxonomies']);
					}

					// use WPx to register each post type, with its metaboxes (the fields)
					$cpt_iterate = new wpx_register_type(
						$post_type->post_name,
						$args
					);

				}

		}

		/*
		|--------------------------------------------------------------------------
		/**
		 * Register Custom Taxonomies
		 *
		 * This is the process we use to register each taxonomy created in the Dashboard.
		 * Taxonomies generated this way can be assigned to dynamically generated CPTs.
		 *
		 * @since 1.0
		 *
		 */
		public function register_custom_taxonomies() {

			// get all the dynamic taxes
			$taxonomies = get_posts(array('posts_per_page'=>-1, 'post_type'=>'wpx_taxonomy'));

			// for each one..
			foreach($taxonomies as $taxonomy) {
				
				// get all custom field data (the fields)
				$attributes = get_post_custom($taxonomy->ID);
				
				// reset the args
				$args = array();

				// go through each custom meta field
				foreach($attributes as $i=>$attribute) {
					
					// most of these represent a single string value
					// in the cases that do not, we transform them into arrays

					// first, kick out custom field default stuff from WP
					if($i == '_edit_lock' || $i == '_edit_last') continue;
					
					// take everything out of an array format
					$attribute = $attribute[0];
					
					// remove the wpx prefix so it matches the args in wpx's register post type array
					$args[str_replace('_wpx_taxonomy_','',$i)] = $attribute;
					
					// if we have a comma, it needs to become an array
					if (strpos($attribute,',') !== false) {
						$args[str_replace('_wpx_taxonomy_','',$i)] = explode(',', $attribute);
					}
					
					// turn all "false", "true" strings to proper booleans
					if (filter_var($attribute, FILTER_VALIDATE_BOOLEAN)) $args[str_replace('_wpx_taxonomy_','',$i)] = filter_var($attribute, FILTER_VALIDATE_BOOLEAN);
					
					// we need to handle the strings entered for the "rewrite" array
					if ( is_array($args['rewrite']) ) {
						foreach($args['rewrite'] as $i=>$rewrite) {
							if ($i == 0 && $rewrite) $rename_rewrite['slug'] = $rewrite;
							if ($i == 1 && $rewrite) $rename_rewrite['with_front'] = $rewrite;
							if ($i == 2 && $rewrite) $rename_rewrite['hierarchical'] = $rewrite;
							if ($i == 3 && $rewrite) $rename_rewrite['ep_mask'] = $rewrite;
							if ($i == 4 && $rewrite == 1) $rename_rewrite = 'false';
						}

						// unset this if it's actually empty
						$args['rewrite'] = $rename_rewrite;
						if (!$args['rewrite']) unset($args['rewrite']);
					}
				}

				// if a singular & plural name was specified, we specify defaults so that
				// we can be lazy and not enter in everything by hand
				if ($args['label_singular'] && $args['label_plural']) {
					$args['labels'] = array(
						'name' =>$args['label_singular'],
						'singular_name' => $args['label_singular'],
						'menu_name' => $args['label_plural'],
						'all_items' => 'All '.$args['label_plural'],
						'edit_item' => 'Edit '.$args['label_singular'],
						'update_item' => 'Update '.$args['label_singular'],
						'add_new_item' => 'Add New '.$args['label_singular'],
						'new_item_name' => 'New '.$args['label_singular'],
						'search_items' => 'Search '.$args['label_plural'],
						'popular_items' => 'Popular '.$args['label_plural'],
						'parent_item' => 'Parent '.$args['label_singular'],
						'parent_item_colon' => 'Parent '.$args['label_singular'],
						'separate_items_with_commas' => 'Separate '.$args['label_plural'].' with commas.',
						'add_or_remove_items' => 'Add or remove '.$args['label_plural'],
						'choose_from_most_used' => 'Choose from the most used '.$args['label_plural'].'.',
						'not_found' => 'No '.$args['label_plural'].' found.'
					);
				} else {
					// however if we want to get specific, we leave the plural/singular fields blank
					// and get the manually entered values for each label
					if ($args['name']) { $args['labels']['name'] = $args['name']; }
					if ($args['singular_name']) { $args['labels']['singular_name'] = $args['singular_name']; }
					if ($args['menu_name']) { $args['labels']['menu_name'] = $args['menu_name']; }
					if ($args['all_items']) { $args['labels']['all_items'] = $args['name']; }
					if ($args['edit_item']) { $args['labels']['edit_item'] = $args['edit_item']; }
					if ($args['update_item']) { $args['labels']['update_item'] = $args['update_item']; }
					if ($args['add_new_item']) { $args['labels']['add_new_item'] = $args['add_new_item']; }
					if ($args['new_item_name']) { $args['labels']['new_item_name'] = $args['new_item_name']; }
					if ($args['search_items']) { $args['labels']['search_items'] = $args['search_items']; }
					if ($args['popular_items']) { $args['labels']['popular_items'] = $args['popular_items']; }
					if ($args['parent_item']) { $args['labels']['parent_item'] = $args['parent_item']; }
					if ($args['parent_item_colon']) { $args['labels']['parent_item_colon'] = $args['parent_item_colon']; }
					if ($args['separate_items_with_commas']) { $args['labels']['separate_items_with_commas'] = $args['separate_items_with_commas']; }
					if ($args['add_or_remove_items']) { $args['labels']['add_or_remove_items'] = $args['add_or_remove_items']; }
					if ($args['choose_from_most_used']) { $args['labels']['choose_from_most_used'] = $args['choose_from_most_used']; }
					if ($args['not_found']) { $args['labels']['not_found'] = $args['not_found']; }
				}

				// then kick out the specific labels, since this will be entered
				// into the labels array proper
				unset($args['label_singular']);
				unset($args['label_plural']);
				unset($args['name']);
				unset($args['singular_name']);
				unset($args['menu_name']);
				unset($args['all_items']);
				unset($args['edit_item']);
				unset($args['update_item']);
				unset($args['add_new_item']);
				unset($args['new_item_name']);
				unset($args['search_items']);
				unset($args['popular_items']);
				unset($args['parent_item_colon']);
				unset($args['separate_items_with_commas']);
				unset($args['add_or_remove_items']);
				unset($args['choose_from_most_used']);
				unset($args['not_found']);

				// get all fields that were attached to this cpt
				$fields_meta = get_post_meta($taxonomy->ID, '_wpx_taxonomy_register_metaboxes', true);
				$fields_ids = explode(',', $fields_meta);
				$fields = get_posts(array('post_type'=>'wpx_fields', 'post__in'=>$fields_ids, 'orderby'=>'menu_order', 'posts_per_page'=>-1));

				// reset the metabox array
				$metaboxes = array();

				// for each field, translate its custom meta into the metabox array
				foreach($fields as $field) {
					$required = get_post_meta($field->ID, '_wpx_fields_required', true);
					$metaboxes[] = array(
						'id'=> $field->post_name,
						'label'=>get_post_meta($field->ID, '_wpx_fields_label', true),
						'description'=>get_post_meta($field->ID, '_wpx_fields_description', true),
						'field'=>get_post_meta($field->ID, '_wpx_fields_type', true),
						'required'=>filter_var($required, FILTER_VALIDATE_BOOLEAN)
					);
				}
				
				// add the metaboxes to the args
				$args['register_metaboxes'] = $metaboxes;

				// let's specify some common defaults that I prefer
				// these differ from WP's defaults, but they are ones I always find myself
				// resetting; you can reverse this by just specifying defaults when you create tax
				$arg_defaults = array(
					'name' => $taxonomy->post_name,
					'hierarchical' => true, 
					'query_var' => $taxonomy->post_name,
					'public'=>true,
					'show_ui'=>true,
					'show_in_nav_menus'=>true,
					'show_tagcloud'=>true,
					'show_admin_column'=>true,
					'rewrite'=>array('slug'=>$taxonomy->post_name,'with_front'=>true, 'hierarchical'=>false),
					'sort'=>true
				);

				// standard WP default arguments merge
				$revised_args = wp_parse_args( $args, $arg_defaults );
				extract( $revised_args, EXTR_SKIP );

				// use wpx to register each taxonomy, with its metaboxes (the fields)
				$taxonomy = new wpx_register_taxonomy(
					$taxonomy->post_name,
					$revised_args['object_type'],
					$revised_args
				);

			}

		}

		/*
		|--------------------------------------------------------------------------
		/**
		 * Register Custom Options Pages
		 *
		 * The process we'll use to run through all Options types to register
		 * the pages in the Dashboard.
		 *
		 * @since 1.0
		 *
		 */
		public function register_options_types() {

				// get all cpts in the Dashboard
				$options = get_posts(array('posts_per_page'=>-1, 'post_type'=>'wpx_options'));

				foreach($options as $options_page) {

					// get all metadata
					$attributes = get_post_custom($options_page->ID);

					// reset the args
					$args = array();

					// go through each custom field
					foreach($attributes as $i=>$attribute) {

						// kick out custom field default stuff from WP
						if($i == '_edit_lock' || $i == '_edit_last') continue;

						// deal with validation routines
						if ($i == '_wpx_options_validation') {
							$attribute = $attribute[0];
							$routines = explode("\n", $attribute);
							foreach($routines as $routine) {
								$routine_sets[] = explode(',', $routine);
							}
							$args['validation'] = $routine_sets;
						} else {

							// take everything out of an array format
							$attribute = $attribute[0];

							// remove the wpx prefix so it matches the args in register post type
							$args[str_replace('_wpx_options_','',$i)] = $attribute;

							// if we have a comma, it needs to become an array
							if (strpos($attribute,',') !== false) {
								$args[str_replace('_wpx_options_','',$i)] = explode(',', $attribute);
							}

							// turn all "false", "true" into proper booleans
							if (filter_var($attribute, FILTER_VALIDATE_BOOLEAN)) $args[str_replace('_wpx_options_','',$i)] = filter_var($attribute, FILTER_VALIDATE_BOOLEAN);

						}

					}

					// get all fields attached to this options page
					$fields_meta = get_post_meta($options_page->ID, '_wpx_options_register_metaboxes', true);
					$fields_ids = explode(',', $fields_meta);
					$fields = get_posts(array('post_type'=>'wpx_fields', 'post__in'=>$fields_ids, 'orderby'=>'menu_order', 'posts_per_page'=>-1));

					// reset this to empty
					$groups = array();

					// get all groups
					// what we're doing here is making it possible later
					// to sort by groups and insert other "settings" for metaboxes
					// in the future
					foreach($fields as $field) {
						$group = wp_get_object_terms($field->ID, 'wpx_groups');
						if ($group) {

							// groups have order and required fields as custom fields
							$order = wpx::get_taxonomy_meta($group[0]->term_id, '_wpx_groups_order', true);
							$required = wpx::get_taxonomy_meta($group[0]->term_id, '_wpx_groups_collapsed', true);

							// reset the settings array
							$settings = array();

							// we insert "required" as a key in the first array of the groups 
							// metabox array, which we'll retrieve later
							if ($required) {
								$settings['collapsed'] = true;
								$groups[$group[0]->name][0] = $settings;
							}

							// same for order
							if ($order) {
								$settings['order'] = $order;
								$groups[$group[0]->name][0] = $settings;
							} else {
								$settings['order'] = 0;
								$groups[$group[0]->name][0] = $settings;
							}
							$groups[$group[0]->name][] = $field;
						} else {
							$groups['Settings'][] = $field;
						}
					}

					// reset this to empty
					$metaboxes = array();

					// build proper metabox arrays
					// sorted by group
					if (is_array($groups)) {
	 					foreach($groups as $i=>$group) {
	 						foreach($group as $x=>$field) {
		 						if ($x == 0 && !$field->ID) {
		 							// then this is the settings array
		 							$metaboxes[$i][0] = $field;
		 						} else {
									$required = get_post_meta($field->ID, '_wpx_fields_required', true);
									$metaboxes[$i][] = array(
										'id'=>$field->post_name,
										'label'=>get_post_meta($field->ID, '_wpx_fields_label', true),
										'description'=>get_post_meta($field->ID, '_wpx_fields_description', true),
										'field'=>get_post_meta($field->ID, '_wpx_fields_type', true),
										'required'=>filter_var($required, FILTER_VALIDATE_BOOLEAN)
									);
								}
							}
						}
						// add the metaboxes to the args
						$args['register_metaboxes'] = $metaboxes;
					}

					// check if the option page has a parent
					// in which case, pass the parent as the menu_parent
					// and override anything set manually
					$parent_page = wpx::get_ancestor_id($options_page);
					if ($parent_page !== $options_page->ID) {
						$args['menu_ancestor'] = $parent_page;
					} 

					// add the title
					$args['title'] = get_the_title($options_page->ID);

					$options_iterate = new wpx_options_page(
						$options_page->post_name, // unique ID for this page
						$args
					);

				}

		}

		/*
		|--------------------------------------------------------------------------
		 * Customize Columns for Internal CPTs
		|--------------------------------------------------------------------------
		*/

		// custom columns for fields
		public function extend_fields_columns($page_columns) {
			$new_columns['cb'] = '<input type="checkbox" />';
			$new_columns['title'] = _x('Title', 'column name');
			$new_columns['post_types'] = __('Post Types');
			$new_columns['group'] = __('Group');
			$new_columns['order'] = __('Order');
			$new_columns['id'] = __('Meta Key');
			return $new_columns;
		}

		// switch case for custom columns for fields
		public function extend_fields_post_list($column_name, $id) {
			
			global $post;

			switch ($column_name) {

			case 'id':
				echo $post->post_name;
				break;

			case 'order':
				echo $post->menu_order;
				break;

			case 'post_types':
				$post_types = get_posts(array('post_type'=>'wpx_types', 'posts_per_page'=>-1,'meta_key'=>'_wpx_cpt_metaboxes'));
				if ($post_types) {
					foreach($post_types as $i=>$type) {
						$metaboxes = get_post_meta($type->ID, '_wpx_cpt_metaboxes', true);
						$metaboxes_array = explode(',', $metaboxes);
						if (is_array($metaboxes_array)) {
							if (in_array($post->ID, $metaboxes_array)) {
								$found_types[] = $type;
							}
						}
						
					}
				}
				$comma = ', ';
				$count = count($found_types)-1;
				if ($found_types) {
					foreach($found_types as $i=>$type) {
						if ($i == $count) $comma = '';
						echo '<a href="'.get_bloginfo('url').'/wp-admin/post.php?post='.$type->ID.'&action=edit">'.get_the_title($type->ID).'</a>'.$comma;
					}
				}
				break;

			case 'group':
				$groups = get_the_terms( $post->ID, 'wpx_groups' );
				if ($groups) { 
					$count = 0;
					foreach ($groups as $group) {
						echo '<a href="edit.php?post_type=wpx_fields&groups='.$group->slug.'">'.$group->name.'</a>'; 
					}
				}
			break;

			default:
				break;
			}
		}

		// switch statement for custom columns for cpts
		public function extend_cpts_post_list($column_name, $id) {
			global $post;
			switch ($column_name) {
			case 'id':
				echo $post->post_name;
				break;
			case 'order':
				echo $post->menu_order;
				break;
			case 'fields':
				$fields_meta = get_post_meta($post->ID, '_wpx_cpt_metaboxes', true);
				$fields_meta = explode(',',$fields_meta);
				if (is_array($fields_meta)) {
					$fields = get_posts(array('post_type'=>'wpx_fields', 'posts_per_page'=>-1,'post__in'=>$fields_meta));
					$comma = ', ';
					$count = count($fields)-1;
					foreach($fields as $i=>$field) {
						if ($i == $count) $comma = '';
						echo '<a href="'.get_bloginfo('url').'/wp-admin/post.php?post='.$field->ID.'&action=edit">'.get_the_title($field->ID).'</a>'.$comma;
					}

				}
				break;
			default:
				break;
			}
		}

		// custom columns for cpts
		public function extend_cpts_columns($page_columns) {
			$new_columns['cb'] = '<input type="checkbox" />';
			$new_columns['title'] = _x('Title', 'column name');
			$new_columns['fields'] = __('Fields');
			$new_columns['id'] = __('ID');
			return $new_columns;
		}

		/*
		|--------------------------------------------------------------------------
		 * Customize Columns for Options Pages
		|--------------------------------------------------------------------------
		*/

		// custom columns for fields
		public function extend_options_columns($page_columns) {
			$new_columns['cb'] = '<input type="checkbox" />';
			$new_columns['title'] = _x('Title', 'column name');
			$new_columns['fields'] = __('Fields');
			$new_columns['id'] = __('ID');
			return $new_columns;
		}

		// switch case for custom columns for fields
		public function extend_options_post_list($column_name, $id) {
			
			global $post;

			switch ($column_name) {

			case 'id':
				echo $post->post_name;
				break;

			case 'fields':
				$fields_meta = get_post_meta($post->ID, '_wpx_options_register_metaboxes', true);
				$fields_meta = explode(',',$fields_meta);
				if (is_array($fields_meta)) {
					$fields = get_posts(array('post_type'=>'wpx_fields', 'posts_per_page'=>-1,'post__in'=>$fields_meta));
					$comma = ', ';
					$count = count($fields)-1;
					foreach($fields as $i=>$field) {
						if ($i == $count) $comma = '';
						echo '<a href="'.get_bloginfo('url').'/wp-admin/post.php?post='.$field->ID.'&action=edit">'.get_the_title($field->ID).'</a>'.$comma;
					}

				}
				break;

			default:
				break;
			}
		}

		/*
		|--------------------------------------------------------------------------
		 * Customize Columns for Taxonomies
		|--------------------------------------------------------------------------
		*/

		// custom columns for fields
		public function extend_taxonomies_columns($page_columns) {
			$new_columns['cb'] = '<input type="checkbox" />';
			$new_columns['title'] = _x('Title', 'column name');
			$new_columns['fields'] = __('Fields');
			$new_columns['id'] = __('ID');
			return $new_columns;
		}

		// switch case for custom columns for fields
		public function extend_taxonomies_post_list($column_name, $id) {
			
			global $post;

			switch ($column_name) {

			case 'id':
				echo $post->post_name;
				break;

			case 'fields':
				$fields_meta = get_post_meta($post->ID, '_wpx_taxonomy_register_metaboxes', true);
				$fields_meta = explode(',',$fields_meta);
				if (is_array($fields_meta)) {
					$fields = get_posts(array('post_type'=>'wpx_fields', 'posts_per_page'=>-1,'post__in'=>$fields_meta));
					$comma = ', ';
					$count = count($fields)-1;
					foreach($fields as $i=>$field) {
						if ($i == $count) $comma = '';
						echo '<a href="'.get_bloginfo('url').'/wp-admin/post.php?post='.$field->ID.'&action=edit">'.get_the_title($field->ID).'</a>'.$comma;
					}

				}
				break;

			default:
				break;
			}
		}

		/*
		|--------------------------------------------------------------------------
		 * Activation (TK)
		|--------------------------------------------------------------------------
		*/
		public static function activate() {
			// TK
		}

		/*
		|--------------------------------------------------------------------------
		 * Deactivation (TK)
		|--------------------------------------------------------------------------
		*/

		public static function deactivate() {
			// TK: the option to "uninstall" all CPTs, Taxonomies, Fields, and Groups created by the plugin
			// to wipe them from the database, and all custom meta entered thereby
		}
	}
}

if (class_exists('wpx_core')) {

	// installation and uninstallation hooks
	register_activation_hook(__FILE__, array('wpx_core', 'activate'));
	register_deactivation_hook(__FILE__, array('wpx_core', 'deactivate'));

	// instantiate the plugin class
	$wpx_core = new wpx_core();

}