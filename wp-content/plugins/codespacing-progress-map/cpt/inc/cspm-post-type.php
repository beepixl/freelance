<?php
 
if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

if(!class_exists('CspmPostType')){
	
	class CspmPostType{
		
		private static $_this;	
		
		private $object_type;
		
		function __construct($atts = array()){
			
			extract( wp_parse_args( $atts, array(
				'object_type' => '', 
			)));
			
			$this->object_type = esc_attr($object_type);

			add_action('init', array(&$this, 'cspm_register_post_type'));
			
			add_filter('manage_edit-'.$this->object_type.'_columns', array(&$this, 'cspm_edit_list_columns'));
			
			add_action('manage_'.$this->object_type.'_posts_custom_column', array(&$this, 'cspm_display_list_columns'), 10, 2);
			
			add_filter('manage_edit-'.$this->object_type.'_sortable_columns', array(&$this, 'cspm_sort_list_columns'));
			
			add_filter('post_updated_messages', array(&$this, 'cspm_updated_messages' ));
			
			add_filter('bulk_post_updated_messages', array(&$this, 'cspm_bulk_post_updated_messages_filter'), 10, 2);
			
			add_action('admin_head-post.php', array(&$this, 'cspm_publishing_actions'));
			add_action('admin_head-post-new.php', array(&$this, 'cspm_publishing_actions'));
		
		}


		static function this(){
			
			return self::$_this;
			
		}
		
				
		/**
		 * Registers the post type needed by the plugin.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		function cspm_register_post_type() {
		
			/* Set up the arguments for the post type. */
			$args = array(
		
				/** 
				 * Whether the post type should be used publicly via the admin or by front-end users.  This 
				 * argument is sort of a catchall for many of the following arguments.  I would focus more 
				 * on adjusting them to your liking than this argument.
				 */
				'public'              => false, // bool (default is FALSE)
		
				/**
				 * Whether queries can be performed on the front end as part of parse_request(). 
				 */
				'publicly_queryable'  => false, // bool (defaults to 'public').
		
				/**
				 * Whether to exclude posts with this post type from front end search results.
				 */
				'exclude_from_search' => true, // bool (defaults to 'public')
		
				/**
				 * Whether individual post type items are available for selection in navigation menus. 
				 */
				'show_in_nav_menus'   => true, // bool (defaults to 'public')
		
				/**
				 * Whether to generate a default UI for managing this post type in the admin. You'll have 
				 * more control over what's shown in the admin with the other arguments.  To build your 
				 * own UI, set this to FALSE.
				 */
				'show_ui'             => true, // bool (defaults to 'public')
		
				/**
				 * Whether to show post type in the admin menu. 'show_ui' must be true for this to work. 
				 */
				'show_in_menu'        => false, // bool (defaults to 'show_ui')
		
				/**
				 * Whether to make this post type available in the WordPress admin bar. The admin bar adds 
				 * a link to add a new post type item.
				 */
				'show_in_admin_bar'   => true, // bool (defaults to 'show_in_menu')
		
				/**
				 * The position in the menu order the post type should appear. 'show_in_menu' must be true 
				 * for this to work.
				 */
				'menu_position'       => 27, // int (defaults to 25 - below comments)
		
				/**
				 * The URI to the icon to use for the admin menu item. There is no header icon argument, so 
				 * you'll need to use CSS to add one.
				 */
				'menu_icon'           => '', // string (defaults to use the post icon)
		
				/**
				 * Whether the posts of this post type can be exported via the WordPress import/export plugin 
				 * or a similar plugin. 
				 */
				'can_export'          => true, // bool (defaults to TRUE)
		
				/**
				 * Whether to delete posts of this type when deleting a user who has written posts. 
				 */
				'delete_with_user'    => false, // bool (defaults to TRUE if the post type supports 'author')
		
				/**
				 * Whether this post type should allow hierarchical (parent/child/grandchild/etc.) posts. 
				 */
				'hierarchical'        => false, // bool (defaults to FALSE)
		
				/** 
				 * Whether the post type has an index/archive/root page like the "page for posts" for regular 
				 * posts. If set to TRUE, the post type name will be used for the archive slug.  You can also 
				 * set this to a string to control the exact name of the archive slug.
				 */
				'has_archive'         => false, // bool|string (defaults to FALSE)
		
				/**
				 * Sets the query_var key for this post type. If set to TRUE, the post type name will be used. 
				 * You can also set this to a custom string to control the exact key.
				 */
				'query_var'           => true, // bool|string (defaults to TRUE - post type name)
		
				/**
				 * A string used to build the edit, delete, and read capabilities for posts of this type. You 
				 * can use a string or an array (for singular and plural forms).  The array is useful if the 
				 * plural form can't be made by simply adding an 's' to the end of the word.  For example, 
				 * array( 'box', 'boxes' ).
				 */
				'capability_type'     => 'post', // string|array (defaults to 'post')
		
				/**
				 * Whether WordPress should map the meta capabilities (edit_post, read_post, delete_post) for 
				 * you.  If set to FALSE, you'll need to roll your own handling of this by filtering the 
				 * 'map_meta_cap' hook.
				 */
				'map_meta_cap'        => true, // bool (defaults to FALSE)
		
				/** 
				 * How the URL structure should be handled with this post type.  You can set this to an 
				 * array of specific arguments or true|false.  If set to FALSE, it will prevent rewrite 
				 * rules from being created.
				 */
				'rewrite' => array(
		
					/* The slug to use for individual posts of this type. */
					'slug'       => $this->object_type, // string (defaults to the post type name)
		
					/* Whether to show the $wp_rewrite->front slug in the permalink. */
					'with_front' => true, // bool (defaults to TRUE)
		
					/* Whether to allow single post pagination via the <!--nextpage--> quicktag. */
					'pages'      => false, // bool (defaults to TRUE)
		
					/* Whether to create feeds for this post type. */
					'feeds'      => false, // bool (defaults to the 'has_archive' argument)
		
					/* Assign an endpoint mask to this permalink. */
					'ep_mask'    => EP_PERMALINK, // const (defaults to EP_PERMALINK)
				),
		
				/**
				 * What WordPress features the post type supports.  Many arguments are strictly useful on 
				 * the edit post screen in the admin.  However, this will help other themes and plugins 
				 * decide what to do in certain situations.  You can pass an array of specific features or 
				 * set it to FALSE to prevent any features from being added.  You can use 
				 * add_post_type_support() to add features or remove_post_type_support() to remove features 
				 * later.  The default features are 'title' and 'editor'.
				 */
				'supports' => array(
		
					/* Post titles ($post->post_title). */
					'title',
		
					/* Post content ($post->post_content). */
					//'editor',
		
					/* Post excerpt ($post->post_excerpt). */
					//'excerpt',
		
					/* Post author ($post->post_author). */
					'author',
		
					/* Featured images (the user's theme must support 'post-thumbnails'). */
					//'thumbnail',
		
					/* Displays comments meta box.  If set, comments (any type) are allowed for the post. */
					//'comments',
		
					/* Displays meta box to send trackbacks from the edit post screen. */
					//'trackbacks',
		
					/* Displays the Custom Fields meta box. Post meta is supported regardless. */
					//'custom-fields',
		
					/* Displays the Revisions meta box. If set, stores post revisions in the database. */
					//'revisions',
		
					/* Displays the Attributes meta box with a parent selector and menu_order input box. */
					//'page-attributes',
		
					/* Displays the Format meta box and allows post formats to be used with the posts. */
					//'post-formats',
				),
				
				'taxonomies' => array(
				),
		
				/**
				 * Labels used when displaying the posts in the admin and sometimes on the front end.  These 
				 * labels do not cover post updated, error, and related messages.  You'll need to filter the 
				 * 'post_updated_messages' hook to customize those.
				 */
				'labels' => array(
						
					'name'               => __('PM. Maps', 'cspm'),
					'singular_name'      => __('PM. Maps', 'cspm'),
										
					'menu_name'          => __('PM. Maps', 'cspm'),
						
					'name_admin_bar'     => __('PM. Maps', 'cspm'),
					'add_new'            => __('Add New Map', 'cspm'),
					'add_new_item'       => __('Add New Map', 'cspm'),	
					'edit_item'          => __('Edit Map', 'cspm'),	
					'new_item'           => __('New Map', 'cspm'),
					'view_item'          => __('View Map', 'cspm'),
					'search_items'       => __('Search Maps', 'cspm'),
					'not_found'          => __('No Maps'.' found', 'cspm'),	
					'not_found_in_trash' => __('No Maps'.' found in trash', 'cspm'),	
					'all_items'          => __('All Maps', 'cspm'),
		
					/* Labels for hierarchical post types only. */
					'parent_item'        => __('Parent Map', 'cspm'),
					'parent_item_colon'  => __('Parent Map:', 'cspm'),
		
					/* Custom archive label.  Must filter 'post_type_archive_title' to use. */
					'archive_title'      => __('Maps', 'cspm'),
				)
			);
		
			register_post_type($this->object_type, $args);
			
		}
	
		
		/**
		 * Edit columns in the manage plugin's custom post type list
		 * 
		 * @since 1.0
		 */
		function cspm_edit_list_columns($columns) {
		
			$columns = array(
				 'cb' => '<input type="checkbox" />', 
				 'title' => __('Title', 'cspm'),
				 'map_id' => __('Map ID', 'cspm'), 
				 'shortcode' => __('Shortcode', 'cspm'),			 
			);	 
				
			return $columns ;
			
		}
		
		
		/**
		 * Edit the Maps list
		 * 
		 * @since 1.0
		 */
		function cspm_display_list_columns($column, $post_id) {

			switch ($column) { 
	
				/**
				 * Get the map ID */
				 
				case 'map_id': 
				echo $post_id; 
				break; 
				
				/**
				 * Get the map shortcode */
				 
				case 'shortcode': 
				echo '[cspm_main_map id="'.$post_id.'"]';
				break; 
				
				/**
				 * Just break out of the switch statement for everything else. */
				 
				default :
				break;				
			
			} 
			
		}
	
		
		/**
		 * Sort the list columns
		 *
		 * @since 1.0
		 */
		function cspm_sort_list_columns($columns){
			
			$columns = array(
				'map_id' => 'map_id',
				'title' => 'title',
			);
		 
			return $columns;
			
		}
	
	
		/**
		 * Update messages.
		 *
		 * @since 1.0	 
		 */
		function cspm_updated_messages( $messages ) {
		
			$messages[$this->object_type] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => __( 'Map updated.', 'cspm' ),
				2  => __( 'Map updated.', 'cspm' ),
				3  => __( 'Map deleted.', 'cspm' ),
				4  => __( 'Map updated.', 'cspm' ),
				5  => __( 'Map updated.', 'cspm' ),
				6  => __( 'Map saved.', 'cspm' ),
				7  => __( 'Map saved.', 'cspm' ),
				8  => __( 'Map saved.', 'cspm' ),
				9  => __( 'Map updated.', 'cspm' ),
				10 => __( 'Map updated.', 'cspm' )
			);
	
			return $messages;
			
		}
		
		function cspm_bulk_post_updated_messages_filter( $bulk_messages, $bulk_counts ) {
	
			$bulk_messages[$this->object_type] = array(
				'updated'   => _n( '%s Map updated.', '%s Maps updated.', $bulk_counts['updated'] ),
				'locked'    => _n( '%s Map not updated, somebody is editing it.', '%s Maps not updated, somebody is editing them.', $bulk_counts['locked'] ),
				'deleted'   => _n( '%s Map permanently deleted.', '%s Maps permanently deleted.', $bulk_counts['deleted'] ),
				'trashed'   => _n( '%s Map moved to the Trash.', '%s Maps moved to the Trash.', $bulk_counts['trashed'] ),
				'untrashed' => _n( '%s Map restored from the Trash.', '%s Maps restored from the Trash.', $bulk_counts['untrashed'] ),
			);
		
			return $bulk_messages;
		
		}
		
		
		/**
		 * Hide some of the options in the "Publish" Metabox
		 *
		 * @since 1.0
		 */
		function cspm_publishing_actions(){
			
			global $post;
			
			if($post->post_type == $this->object_type){
				
				echo '<style type="text/css">
				#misc-publishing-actions .misc-pub-post-status,
				#misc-publishing-actions .misc-pub-visibility{
				display:none;
				}
				</style>';
				
			}
			
		}

	}

}
