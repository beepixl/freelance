<?php

/**
 * This class contains all the fields used in the widget "Progress Map" 
 *
 * @version 1.0 
 */
 
if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

if( !class_exists( 'CspmMetabox' ) ){
	
	class CspmMetabox{
		
		private $plugin_path;
		private $plugin_url;
		
		private static $_this;	
		
		public $plugin_settings = array();
		
		protected $metafield_prefix;
		
		/**
		 * The name of the post to which we'll add the metaboxes
		 * @since 1.0 */
		 
		public $object_type;
		
		/**
		 * The ID of the current map */
		
		public $object_id;
		
		public $registred_cpts;
		
		public $selected_cpt;
				
		function __construct($atts = array()){
			
			extract( wp_parse_args( $atts, array(
				'plugin_path' => '', 
				'plugin_url' => '',
				'object_type' => '',
				'plugin_settings' => array(), 
				'metafield_prefix' => '',
			)));
             
			self::$_this = $this;       
				           
			$this->plugin_path = $plugin_path;
			
			$this->plugin_url = $plugin_url;
			
			$this->plugin_settings = $plugin_settings;
			
			$this->metafield_prefix = $metafield_prefix;
			
			$this->object_type = $object_type;
			
			$this->registred_cpts = $this->cspm_get_registred_cpts();
			
			/**
			 * Get selected post type based on the post ID */
			 
			$post_id = 0;
			
			if(isset($_REQUEST['post'])){
				
				$post_id = $_REQUEST['post'];
			
			}elseif(isset($_REQUEST['post_ID'])){
				
				$post_id = $_REQUEST['post_ID'];
				
			}
			
			$this->selected_cpt = get_post_meta( $post_id, $this->metafield_prefix . '_post_type', true );
			
			$this->object_id = $post_id;

		}
	

		static function this() {
			
			return self::$_this;
		
		}
			
		
		/**
		 * "Progress Map" Metabox.
		 * This metabox will contain all the settings needed for "Progress Map"
		 *
		 * @since 1.0
		 */
		function cspm_progress_map_metabox(){
			
			/**
			 * 1. Post type metabox options */
			 
			$cspm_cpt_metabox_options = array(
				'id'            => $this->metafield_prefix . '_pm_cpt_metabox',
				'title'         => __( '(Custom) Post Type', 'cspm' ),
				'object_types'  => array( $this->object_type ), // Post type
				'priority'   => 'high',
				//'context'    => 'side',
				'show_names' => true, // Show field names on the left				
			);
			
				/**
				 * Create post type Metabox */
				 
				$cspm_cpt_metabox = new_cmb2_box( $cspm_cpt_metabox_options );
				
				/**
				 * Post type metabox field(s) */
				 
				$cspm_cpt_metabox->add_field( array(
					'id' => $this->metafield_prefix . '_post_type',
					'name' => 'Main post type',
					'desc' => 'Select the post type to use with this map.<br /><br />
					<span style="color:red;">1. Select a post type, then, click on the button <strong>"Publish"</strong> to save the map. New metabox(es) will be available for you to build your map!</span><br /><br />
					<span style="color:red;">2. If in the future, you want to change the post type, select you new post type and click on the button <strong>"Update"</strong> to adapt/synchronize the Metabox(es) below to the new update!</span><br /><br />
					<span style="color:red;">3. If you don\'t find your post type in the list, click on the menu <strong>"Progress Map"</strong> and make sure to select your post type in the field <strong>"Plugin settings => Post types"</strong>!</span>',
					'type' => 'select',
					'show_option_none' => false,
					'default' => '',
					'options' => $this->registred_cpts,
					'before_row' => '<div class="cspml_single_field">',
					'after_row' => '</div>',				
				));
			
			/**
			 * 2. Shortcode metabox options */
			 
			$cspm_shortcode_metabox_options = array(
				'id'            => $this->metafield_prefix . '_pm_shortcode_widget',
				'title'         => __( 'Map Shortcode', 'cspm' ),
				'object_types'  => array( $this->object_type ), // Post type
				'show_on_cb'   => function(){
					global $pagenow;
					return ($pagenow == 'post-new.php') ? false : true;
				},				
				'priority'   => 'low',
				'context'    => 'side',
				'show_names' => true, // Show field names on the left
			);
			
				/**
				 * Create Shortcode Metabox */
				 
				$cspm_cpt_metabox = new_cmb2_box( $cspm_shortcode_metabox_options );
				
				$cspm_cpt_metabox->add_field( array(
					'id' => $this->metafield_prefix . '_shortcode_text',
					'name' => 'To display this map, use the shortcode:',
					'desc' => '<pre>[cspm_main_map id="'.$this->object_id.'"]</pre>',
					'type' => 'title',
					'attributes' => array(
						'style' => 'font-size:13px; font-weight:600; font-style:normal;'
					),					
				));
									
			
			/**
			 * 3. Progress Map settings metabox options */
			 
			$cspm_metabox_options = array(
				'id'            => $this->metafield_prefix . '_pm_metabox',
				'title'         => '<img src="'.$this->plugin_url.'/img/progress-map.png" style="width:19px; margin:0 10px -3px 0;" />'.__( 'Progress Map Settings', 'cspm' ),
				'object_types'  => array( $this->object_type ), // Post type
				'show_on_cb'   => function(){
					global $pagenow;
					return ($pagenow == 'post-new.php') ? false : true;
				},
				// 'context'    => 'normal',
				'priority'   => 'high',
				'show_names' => true, // Show field names on the left
				// 'cmb_styles' => false, // false to disable the CMB stylesheet
				// 'closed'     => true, // true to keep the metabox closed by default
				// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
				// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
			);
			
				/**
				 * Create Progress Map settings Metabox */
					 
				$cspm_metabox = new_cmb2_box( $cspm_metabox_options );
			
				/**
				 * Display Progress Map settings fields */
			
				$this->cspm_progress_map_settings_tabs($cspm_metabox, $cspm_metabox_options);
			
		}
		
		
		/**
		 * Buill all the tabs that contains "Progress Map" settings
		 *
		 * @since 1.0
		 */
		function cspm_progress_map_settings_tabs($metabox_object, $metabox_options){
			
			/**
			 * Setting tabs */
			 
			$tabs_setting = array(
				'args' => $metabox_options,
				'tabs' => array()
			);
				
				/**
				 * Tabs array */
				 
				$cspm_tabs = array(
					
					/**
				 	 * Query Settings */
					 
					array(
						'id' => 'query_settings', 
						'title' => 'Query settings', 
						'callback' => 'cspm_query_fields'
					),
					
					/**
				 	 * Layout Settings */
					 					
					array(
						'id' => 'layout_settings', 
						'title' => 'Layout settings', 
						'callback' => 'cspm_layout_fields'
					),
					
					/**
				 	 * Map Settings */
					 					
					array(
						'id' => 'map_settings', 
						'title' => 'Map settings', 
						'callback' => 'cspm_map_fields'
					),
					
					/**
				 	 * Map Style Settings */
					 					
					array(
						'id' => 'map_style_settings', 
						'title' => 'Map style settings', 
						'callback' => 'cspm_map_style_fields'
					),
					
					/**
				 	 * Infobox Settings */
					 					
					array(
						'id' => 'infobox_settings', 
						'title' => 'Infobox settings', 
						'callback' => 'cspm_infobox_fields'
					),
					
					/**
				 	 * Marker Categories Settings */
					 					
					array(
						'id' => 'marker_categories_settings', 
						'title' => 'Markers categories settings', 
						'callback' => 'cspm_markers_categories_fields'
					),
					
					/**
				 	 * KML Settings */
					 					
					array(
						'id' => 'kml_settings', 
						'title' => 'KML layers settings', 
						'callback' => 'cspm_kml_fields'
					),
					
					/**
				 	 * Overlays Settings */
					 					
					array(
						'id' => 'overlays_settings', 
						'title' => 'Overlays settings', 
						'callback' => 'cspm_overlays_fields'
					),
					
					/**
				 	 * Carousel Settings */
					 					
					array(
						'id' => 'carousel_settings', 
						'title' => 'Carousel settings', 
						'callback' => 'cspm_carousel_fields'
					),
					
					/**
				 	 * Carousel Settings */
					 					
					array(
						'id' => 'carousel_style', 
						'title' => 'Carousel style', 
						'callback' => 'cspm_carousel_style_fields'
					),
					
					/**
				 	 * Carousel Items Settings */
					 					
					array(
						'id' => 'carousel_items_settings', 
						'title' => 'Carousel items settings', 
						'callback' => 'cspm_carousel_items_fields'
					),
					
					/**
				 	 * Posts Count Settings */
					 					
					array(
						'id' => 'posts_count_settings', 
						'title' => 'Posts count settings', 
						'callback' => 'cspm_posts_count_fields'
					),
					
					/**
				 	 * Faceted Search Settings */
					 					
					array(
						'id' => 'faceted_search_settings', 
						'title' => 'Faceted search settings', 
						'callback' => 'cspm_faceted_search_fields'
					),
					
					/**
				 	 * Search Form Settings */
					 					
					array(
						'id' => 'search_form_settings', 
						'title' => 'Search form settings', 
						'callback' => 'cspm_search_form_fields'
					),
					
					/**
				 	 * Zoom to Country Settings */
					 					
					array(
						'id' => 'zoom_to_country_settings', 
						'title' => 'Zoom to country settings', 
						'callback' => 'cspm_zoom_to_country_fields'
					),
					
					/**
				 	 * Nearby points of interest Settings
					 * @since 3.2 */
					 					
					array(
						'id' => 'nearby_places_settings', 
						'title' => 'Nearby points of interest settings', 
						'callback' => 'cspm_nearby_places_fields'
					),

					/**
				 	 * Custom CSS */
					 										
					array(
						'id' => 'customize', 
						'title' => 'Customize', 
						'callback' => 'cspm_customize_fields'
					),
				);
				
				foreach($cspm_tabs as $tab_data){
				 
					$tabs_setting['tabs'][] = array(
						'id'     => 'cspm_' . $tab_data['id'],
						'title'  => '<span class="cspm_tabs_menu_image"><img src="'.$this->plugin_url.'/img/admin-icons/'.str_replace('_', '-', $tab_data['id']).'.png" style="width:20px;" /></span> <span class="cspm_tabs_menu_item">'.__( $tab_data['title'], 'cspm' ).'</span>',						
						'fields' => call_user_func(array(&$this, $tab_data['callback'])), //, $metabox_object),
					);
		
				}
			
			/*
			 * Set tabs */
			 
			$metabox_object->add_field( array(
				'id'   => 'cspm_pm_settings_tabs',
				'type' => 'tabs',
				'tabs' => $tabs_setting
			) );
			
			return $metabox_object;
			
		}
		
		
		/**
		 * Query Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_query_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Query Settings',
				'desc' => 'Filter your posts by controlling the parameters below to your needs. You can always get the information you want without actually dealing with any parameter.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_query_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_number_of_items',
				'name' => 'Number of posts', 
				'desc' => 'Enter the number of posts to show on the map. Leave this field empty to get all posts.',
				'type' => 'text',
				'default' => '',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '0',
				),
			);

			$fields[] = array(
				'id' => $this->metafield_prefix . '_taxonomies_section',
				'name' => 'Taxonomy Parameters',
				'desc' => 'This will allow you to show only posts associated with the certain taxonomies.',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
									
				/**
				 * [@post_type_taxonomy_options] : Takes the list of all taxonomies related to the post type selected in "Query settings" */
			 
				$post_type_taxonomy_options	= $this->cspm_get_post_type_taxonomies($this->selected_cpt);		
					unset($post_type_taxonomy_options['post_format']);
					
				reset($post_type_taxonomy_options); // Set the cursor to 0
						
				/**
				 * Loop through all taxonomies (except for 'post_format' and display each one of them */
				
				foreach($post_type_taxonomy_options as $taxonomy_name => $taxonomy_title){
				
					$tax_name = $taxonomy_name;
					$tax_label = $taxonomy_title;	
					
					$fields[] = array(
						'id' => $this->metafield_prefix . '_taxonomie_'.$tax_name,
						'name' => $tax_label.' ('.$tax_name.')',
						'desc' => 'Show only posts associated with the selected terms.',
						'type' => 'pw_multiselect',
						'options' => $this->cspm_get_term_options($tax_name),
						'attributes' => array(
							'placeholder' => 'Select term(s)'
						),
					);
					
					$fields[] = array(
						'id' => $this->metafield_prefix . '_'.$tax_name.'_operator_param',
						'name' => '"Operator" parameter', 
						'desc' => 'Operator to test "'.$tax_label.'". Defaults to "IN".  <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters" target="_blank">More</a>',
						'type' => 'radio_inline',
						'default' => 'IN',
						'options' => array(
							'AND' => 'AND',
							'IN' => 'IN',
							'NOT IN' => 'NOT IN',
						),
					);
					
				}
							
				$fields[] = array(
					'id' => $this->metafield_prefix . '_taxonomy_relation_param',
					'name' => '"Relation" parameter', 
					'desc' => 'Select the relationship between each taxonomy (when there is more than one). Defaults to "AND". <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters" target="_blank">More</a>.',
					'type' => 'radio',
					'default' => 'AND',
					'options' => array(
						'AND' => 'AND',
						'OR' => 'OR',
					),
				);
						
			$fields[] = array(
				'id' => $this->metafield_prefix . '_status_section',
				'name' => 'Status Parameters',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_items_status',
					'name' => 'Status', 
					'desc' => 'Show posts associated with certain status. Defaults to "publish". <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Status_Parameters" target="_blank">More</a>',
					'type' => 'multicheck',
					'default' => array('publish'),
					'options' => get_post_stati(),
				);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_custom_fields_section',
				'name' => 'Custom Fields Parameters',
				'desc' => 'Show posts associated with certain custom field(s). <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters" target="_blank">More</a>',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_custom_fields',
					'name' => '', 
					'desc' => '',
					'type' => 'group',
					'repeatable'  => true,
					'options'     => array(
						'group_title'   => __( 'Custom Field {#}', 'cspm' ),
						'add_button'    => __( 'Add New Custom Field', 'cspm' ),
						'remove_button' => __( 'Remove Custom Fields', 'cspm' ),
						'sortable'      => true,
						'closed'     => true,
					),
					'fields' => array(	
						array(
							'id' => 'custom_field_name',
							'name' => 'Custom field key/name', 
							'desc' => '',
							'type' => 'text',
							'default' => '',
							'attributes'  => array(
								'data-group-title' => 'text'
							)
						),		
						array(
							'id' => 'custom_field_values',
							'name' => 'Custom field value(s)', 
							'desc' => 'Custom field value(s). Seperate multiple values by comma. (Note: Mulitple values support is limited to a compare value of "IN", "NOT IN", "BETWEEN", or "NOT BETWEEN")',
							'type' => 'text',
							'default' => '',
						),
						array(
							'id' => 'custom_field_type',
							'name' => 'Custom field type', 
							'desc' => '',
							'type' => 'select',
							'default' => 'CHAR',
							'options' => array(
								'NUMERIC' => 'NUMERIC',
								'BINARY' => 'BINARY',
								'CHAR' => 'CHAR',
								'DATE' => 'DATE',
								'DATETIME' => 'DATETIME',
								'DECIMAL' => 'DECIMAL',						
								'SIGNED' => 'SIGNED',
								'TIME' => 'TIME',												
								'UNSIGNED' => 'UNSIGNED',						
							)
						),															
						array(
							'id' => 'custom_field_compare_param',
							'name' => '"Compare" parameter', 
							'desc' => 'Operator to test the custom field value(s).',
							'type' => 'select',
							'default' => '=',
							'options' => array(
								esc_attr('=') => '=',
								esc_attr('!=') => '!=',
								esc_attr('>') => '>',
								esc_attr('>=') => '>=',
								esc_attr('<') => '<',
								esc_attr('<=') => '<=',
								'LIKE' => 'LIKE',						
								'NOT LIKE' => 'NOT LIKE',
								'IN' => 'IN',												
								'NOT IN' => 'NOT IN',						
								'BETWEEN' => 'BETWEEN',
								'NOT BETWEEN' => 'NOT BETWEEN',
								'EXISTS' => 'EXISTS',
								'NOT EXISTS' => 'NOT EXISTS',						
							)
						),				
					)
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_custom_field_relation_param',
					'name' => '"Relation" parameter', 
					'desc' => 'Select the relationship between each custom field (when there is more than one). Defaults to "AND". <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters" target="_blank">More</a>',
					'type' => 'radio',
					'default' => 'AND',
					'options' => array(
						'AND' => 'AND',
						'OR' => 'OR'
					)
				);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_post_section',
				'name' => 'Post Parameters',
				'desc' => 'This will allow you to select specific posts to display or to remove from the map.',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_post_in',
					'name' => 'Posts to retrieve', 
					'desc' => 'Select the posts to retrieve (to display). Type a space to list all available posts!. <span style="color:red;">If you use this field, <strong>"Post not to retrieve"</strong> will be ignored!',
					'type' => 'post_search_ajax',					
					'limit' => PHP_INT_MAX, 
					'sortable' => false,
					'query_args' => array(
						'post_type' => array( $this->selected_cpt ),
						'posts_per_page' => -1
					),
				);			
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_post_not_in',
					'name' => 'Posts not to retreive', 
					'desc' => 'Select the posts not to retrieve (to remove). Type a space to list all available posts!',
					'type' => 'post_search_ajax',										
					'limit' => PHP_INT_MAX,
					'default' => '', 
					'sortable' => false,
					'query_args' => array(
						'post_type' => array( $this->selected_cpt ),
						'posts_per_page' => -1
					),
				);	
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_caching_section',
				'name' => 'Caching parameters',
				'desc' => 'Stop the data retrieved from being added to the cache.',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_cache_results',
					'name' => 'Post information cache', 
					'desc' => 'Show Posts without adding post information to the cache. <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Caching_Parameters" target="_blank">More</a>',
					'type' => 'radio',
					'default' => 'true',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_update_post_meta_cache',
					'name' => 'Post meta information cache', 
					'desc' => 'Show Posts without adding post meta information to the cache. <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Caching_Parameters" target="_blank">More</a>',
					'type' => 'radio',
					'default' => 'true',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_update_post_term_cache',
					'name' => 'Post term information cache', 
					'desc' => 'Show Posts without adding post term information to the cache. <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Caching_Parameters" target="_blank">More</a>',
					'type' => 'radio',
					'default' => 'true',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_author_section',
				'name' => 'Author Parameters',
				'desc' => 'Show posts associated with certain author.',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_authors_prefixing',
					'name' => 'Authors condition', 
					'desc' => 'Select "Yes" if you want to display all posts except those from selected authors.<br />
							   Select "No" if you want to display all posts of selected authors. <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Author_Parameters" target="_blank">More</a>',
					'type' => 'radio',
					'default' => 'false',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					),
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_authors',
					'name' => 'Authors', 
					'desc' => 'Show/Hide posts associated with certain authors. <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Author_Parameters" target="_blank">More</a>',
					'type' => 'pw_multiselect',
					'default' => '',
					'options' => $this->cspm_get_all_users(),
					'attributes' => array(
						'placeholder' => 'Select Author(s)'
					),
				);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_order_section',
				'name' => 'Order & Orderby Parameters',
				'desc' => 'Sort retrieved posts.',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_orderby_param',
					'name' => 'Orderby parameter', 
					'desc' => 'Sort retrieved posts by parameter. Defaults to "date". <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">More</a>',
					'type' => 'select',
					'default' => function(){ return 'date'; }, // Fix an issue on CMB2 that returns error when the default value is the same as a PHP function!
					'options' => array(
						'none' => 'No order',
						'ID' => 'Order by post id',
						'author' => 'Order by author',
						'title' => 'Order by title',
						'name' => 'Order by post name (post slug)',
						'date' => 'Order by date',
						'modified' => 'Order by last modified date',
						'parent' => 'Order by post/page parent id',
						'rand' => 'Random order',
						'comment_count' => 'Order by number of comments',
						'menu_order' => 'Order by Page Order',
						'meta_value' => 'Order by string meta value',
						'meta_value_num' => 'Order by numeric meta value ',
						'post__in' => 'Preserve post ID order given in the post__in array',
					)
				);							
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_orderby_meta_key',
					'name' => 'Custom field name', 
					'desc' => 'This field is used only for "Order by string meta value" & "Order by numeric meta value" in "Orderby parameters".',
					'type' => 'text',
					'default' => '',
					'attributes' => array(
						'data-conditional-id' => $this->metafield_prefix . '_orderby_param',
						'data-conditional-value' => wp_json_encode(array('meta_value', 'meta_value_num')),								
					),					
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_order_meta_type',
					'name' => 'Custom field type', 
					'desc' => 'Select the custom field type. This field is used only for "Order by string meta value" in "Orderby parameters".',
					'type' => 'select',
					'default' => 'CHAR',
					'options' => array(
						'CHAR' => 'CHAR',
						'NUMERIC' => 'NUMERIC',
						'BINARY' => 'BINARY',							
						'DATE' => 'DATE',
						'DATETIME' => 'DATETIME',
						'DECIMAL' => 'DECIMAL',
						'SIGNED' => 'SIGNED',
						'TIME' => 'TIME',
						'UNSIGNED' => 'UNSIGNED',
					),
					'attributes' => array(
						'data-conditional-id' => $this->metafield_prefix . '_orderby_param',
						'data-conditional-value' => wp_json_encode(array('meta_value')),								
					),
				);				
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_order_param',
					'name' => 'Order parameter', 
					'desc' => 'Designates the ascending or descending order of the "orderby" parameter. Defaults to "DESC". <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">More</a>',
					'type' => 'radio',
					'default' => 'DESC',
					'options' => array(
						'ASC' => 'Ascending order from lowest to highest values (1,2,3 | A,B,C)',
						'DESC' => 'Descending order from highest to lowest values (3,2,1 | C,B,A)'
					)
				);							

			return $fields;
			
		}
		
		
		/**
		 * Layout Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_layout_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Layout Settings',
				'desc' => '',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_layout_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_main_layout',
				'name' => 'Main layout',
				'desc' => 'Select main layout alignment.',
				'type' => 'radio',
				'default' => 'mu-cd',
				'options' => array(
					'mu-cd' => 'Map-Up, Carousel-Down',
					'md-cu' => 'Map-Down, Carousel-Up',
					'mr-cl' => 'Map-Right, Carousel-Left',
					'ml-cr' => 'Map-Left, Carousel-Right',
					'fit-in-map' => 'Fit in the box (Map only)',
					'fullscreen-map' => 'Full screen Map (Map only)',
					'm-con' => 'Map with carousel on top',
					'fit-in-map-top-carousel' => 'Fit in the box with carousel on top',
					'fullscreen-map-top-carousel' => 'Full screen Map with carousel on top',
					'map-tglc-top' => 'Map, toggle carousel from top',
					'map-tglc-bottom' => 'Map, toggle carousel from bottom',
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_layout_type',
				'name' => 'Layout type',
				'desc' => 'Select main layout type.',
				'type' => 'radio',
				'default' => 'full_width',
				'options' => array(
					'fixed' => 'Fixed width &amp; Fixed height',
					'full_width' => 'Full width &amp; Fixed height',
					'responsive' => 'Responsive layout <sup>(Hide the carousel on mobile browsers)</sup>'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_layout_fixed_width',
				'name' => 'Layout width',
				'desc' => 'Select the width (in pixels) of the layout. (Works only for the fixed layout)',
				'type' => 'text',
				'default' => '700',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '10'
				),		
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_layout_fixed_height',
				'name' => 'Layout height',
				'desc' => 'Select the height (in pixels) of the layout.',
				'type' => 'text',
				'default' => '600',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '10'
				),				
			);	

			return $fields;
			
		}
		
		
		/**
		 * Map Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_map_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Map Settings',
				'desc' => '',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_map_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);

			$fields[] = array(
				'id' => $this->metafield_prefix . '_map_center',
				'name' => 'Map center',
				'desc' => 'Provide the center point of the map. (Latitude then Longitude separated by comma). Refer to <a href="https://maps.google.com/" target="_blank">https://maps.google.com/</a> to get you center point.',
				'type' => 'text',
				'default' => $this->cspm_get_field_default('map_center', '51.53096,-0.121064'),		
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_initial_map_style',
				'name' => 'Initial style',
				'desc' => 'Select the initial map style. <span style="color:red;">If you\'ve choosed the option <strong>"Custom style"</strong>, then, you must choose one of the available styles in the section <strong>"Map style settings"</strong>.</span>',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('initial_map_style', 'ROADMAP'),
				'options' => array(
					'ROADMAP' => 'Map',
					'SATELLITE' => 'Satellite',
					'TERRAIN' => 'Terrain',
					'HYBRID' => 'Hybrid',
					'custom_style' => 'Custom style'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_map_zoom',
				'name' => 'Map zoom',
				'desc' => 'Select the map zoom. <span style="color:red;">The map zoom will be ignored if you activate the option (below) <strong>"Autofit"</strong>!</span>',
				'type' => 'select',
				'default' => $this->cspm_get_field_default('map_zoom', '12'),
				'options' => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'11' => '11',
					'12' => '12',
					'13' => '13',
					'14' => '14',
					'15' => '15',
					'16' => '16',
					'17' => '17',
					'18' => '18',
					'19' => '19'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_max_zoom',
				'name' => 'Max. zoom',
				'desc' => 'Select the maximum zoom of the map.',
				'type' => 'select',
				'default' => $this->cspm_get_field_default('max_zoom', '19'),
				'options' => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'11' => '11',
					'12' => '12',
					'13' => '13',
					'14' => '14',
					'15' => '15',
					'16' => '16',
					'17' => '17',
					'18' => '18',
					'19' => '19'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_min_zoom',
				'name' => 'Min. zoom',
				'desc' => 'Select the minimum zoom of the map. <span style="color:red;">The Min. zoom should be lower than the Max. zoom!</span>',
				'type' => 'select',
				'default' => $this->cspm_get_field_default('min_zoom', '0'),
				'options' => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'11' => '11',
					'12' => '12',
					'13' => '13',
					'14' => '14',
					'15' => '15',
					'16' => '16',
					'17' => '17',
					'18' => '18',
					'19' => '19'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_zoom_on_doubleclick',
				'name' => 'Zoom on double click',
				'desc' => 'Enable/Disable zooming and recentering the map on double click. Defaults to "Disable".',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('zoom_on_doubleclick', 'true'),
				'options' => array(
					'false' => 'Enable',
					'true' => 'Disable'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_map_draggable',
				'name' => 'Draggable',
				'desc' => 'If Yes, prevents the map from being dragged. Dragging is enabled by default.',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('map_draggable', 'true'),
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_useClustring',
				'name' => 'Clustering',
				'desc' => 'Clustering simplifies your data visualization by consolidating data that are nearby each other on the map in an aggregate form. <span style="color:red;">Activating this option will significantly increase the loading speed of the map!</span>',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('useClustring', 'true'),
				'options' => array(
					'true' => 'Yes <span style="color:red;"><sup>(Recommended)</sup></span>',
					'false' => 'No'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_gridSize',
				'name' => 'Grid size',
				'desc' => 'Grid size or Grid-based clustering works by dividing the map into squares of a certain size (the size changes at each zoom) and then grouping the markers into each grid square.',
				'type' => 'text',
				'default' => $this->cspm_get_field_default('gridSize', '60'),
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '0'
				),						
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_autofit',
				'name' => 'Autofit',
				'desc' => 'This option extends map bounds to contain all markers & clusters. <span style="color:red;">By activating this option, the map zoom will be ignored!</span>',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('autofit', 'false'),
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_traffic_layer',
				'name' => 'Traffic Layer',
				'desc' => 'Display current road traffic.',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('traffic_layer', 'false'),
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_transit_layer',
				'name' => 'Transit Layer',
				'desc' => 'Display local Transit route information.',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('transit_layer', 'false'),
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
								
			$fields[] = array(
				'id' => $this->metafield_prefix . '_recenter_map',
				'name' => 'Recenter Map',
				'desc' => 'Show a button on the map to allow recentring the map. Defaults to "Yes".',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('recenter_map', 'true'),
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_geotarget_section',
				'name' => 'Geotarget parameters',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_geoIpControl',
					'name' => 'Geo targeting',
					'desc' => 'The Geo targeting is the method of determining the geolocation of a website visitor.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('geoIpControl', 'false'),
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_show_user',
					'name' => 'Show user location?',
					'desc' => 'Show a marker indicating the location of the user when choosing to share their location.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('show_user', 'false'),
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_user_marker_icon',
					'name' => 'User Marker image',
					'desc' => 'Upload a marker image to display as the user location. When empty, the map will display the default marker of Google Map.',
					'type' => 'file',
					'default' => $this->cspm_get_field_default('user_marker_icon', ''),
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_user_map_zoom',
					'name' => 'Geotarget Zoom',
					'desc' => 'Select the zoom of the map when indicating the location of the user.',
					'type' => 'select',
					'default' => $this->cspm_get_field_default('user_map_zoom', '12'),
					'options' => array(
						'0' => '0',
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
						'9' => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
						'13' => '13',
						'14' => '14',
						'15' => '15',
						'16' => '16',
						'17' => '17',
						'18' => '18',
						'19' => '19'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_user_circle',
					'name' => 'Draw a Circle around the user\'s location',
					'desc' => 'Draw a circle within a certain distance of the user\'s location. Set to 0 to ignore this option.',
					'type' => 'text',
					'default' => $this->cspm_get_field_default('user_circle', '0'),
					'attributes' => array(
						'type' => 'number',
						'pattern' => '\d*',
						'min' => '0'
					),				
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_user_circle_fillColor',
					'name' => 'Fill color',
					'desc' => 'The fill color of the circle.',
					'type' => 'colorpicker',
					'default' => $this->cspm_get_field_default('user_circle_fillColor', '#189AC9'),															
				);

				$fields[] = array(
					'id' => $this->metafield_prefix . '_user_circle_fillOpacity',
					'name' => 'Fill opacity',
					'desc' => 'The fill opacity of the circle between 0.0 and 1.0.',
					'type' => 'select',
					'default' => $this->cspm_get_field_default('user_circle_fillOpacity', '0.1'),
					'options' => array(
						'0,0' => '0.0',
						'0,1' => '0.1',
						'0,2' => '0.2',
						'0,3' => '0.3',
						'0,4' => '0.4',
						'0,5' => '0.5',
						'0,6' => '0.6',
						'0,7' => '0.7',
						'0,8' => '0.8',
						'0,9' => '0.9',
						'1' => '1',
					)			
				);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_user_circle_strokeColor',
					'name' => 'Stroke color',
					'desc' => 'The stroke color of the circle.',
					'type' => 'colorpicker',
					'default' => $this->cspm_get_field_default('user_circle_strokeColor', '#189AC9'),
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_user_circle_strokeOpacity',
					'name' => 'Stroke opacity',
					'desc' => 'The stroke opacity of the circle between 0.0 and 1.',
					'type' => 'select',
					'default' => $this->cspm_get_field_default('user_circle_strokeOpacity', '1'),
					'options' => array(
						'0,0' => '0.0',
						'0,1' => '0.1',
						'0,2' => '0.2',
						'0,3' => '0.3',
						'0,4' => '0.4',
						'0,5' => '0.5',
						'0,6' => '0.6',
						'0,7' => '0.7',
						'0,8' => '0.8',
						'0,9' => '0.9',
						'1' => '1',
					)			
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_user_circle_strokeWeight',
					'name' => 'Stroke weight',
					'desc' => 'The stroke width of the circle in pixels.',
					'type' => 'text',
					'default' => $this->cspm_get_field_default('user_circle_strokeWeight', '1'),
					'attributes' => array(
						'type' => 'number',
						'pattern' => '\d*',
						'min' => '0'
					),				
				);	

			$fields[] = array(
				'id' => $this->metafield_prefix . '_ui_elements_section',
				'name' => 'UI elements',
				'desc' => 'The maps displayed through the Google Maps API contain UI elements to allow user interaction with the map. These elements are known as controls and you can include and/or customize variations of these controls in your map.',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_mapTypeControl',
					'name' => 'Show map type control',
					'desc' => 'The MapType control lets the user toggle between map types (such as ROADMAP and SATELLITE). This control appears by default in the top right corner of the map.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('mapTypeControl', 'true'),
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_streetViewControl',
					'name' => 'Show street view control',
					'desc' => 'The Street View control contains a Pegman icon which can be dragged onto the map to enable Street View. This control appears by default in the right top corner of the map.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('streetViewControl', 'false'),
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_scrollwheel',
					'name' => 'Scroll wheel',
					'desc' => 'Allow/Disallow the zoom-in and zoom-out of the map using the scroll wheel.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('scrollwheel', 'false'),
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_zoomControl',
					'name' => 'Show zoom control',
					'desc' => 'The Zoom control displays a small "+/-" buttons to control the zoom level of the map. This control appears by default in the top left corner of the map on non-touch devices or in the bottom left corner on touch devices.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('zoomControl', 'true'),
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_zoomControlType',
					'name' => 'Zoom control Type',
					'desc' => 'Select the zoom control type.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('zoomControlType', 'customize'),
					'options' => array(
						'customize' => 'Customized type',
						'default' => 'Default type'
					)
				);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_markers_customizations_section',
				'name' => 'Markers Customizations',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_retinaSupport',
					'name' => 'Retina support',
					'desc' => 'Enable retina support for custom markers & Clusters images. When enabled, make sure the uploaded image is twice the size you want it to be displayed in the map. 
							   For example, if you want the marker/cluster image in the map to be displayed at 20x30 pixels, upload an image with 40x60 pixels.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('retinaSupport', 'false'),
					'options' => array(
						'true' => 'Enable',
						'false' => 'Disable'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_defaultMarker',
					'name' => 'Marker type',
					'desc' => 'Select the marker type.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('defaultMarker', 'customize'),
					'options' => array(
						'customize' => 'Customized type',
						'default' => 'Default type'
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_markerAnimation',
					'name' => 'Marker animation',
					'desc' => 'You can animate a marker so that it exhibit a dynamic movement when it\'s been fired. To specify the way a marker is animated, select
							   one of the supported animations above.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('markerAnimation', 'pulsating_circle'),
					'options' => array(
						'pulsating_circle' => 'Pulsating circle',
						'bouncing_marker' => 'Bouncing marker',
						'flushing_infobox' => 'Flushing infobox <sup>Use only when <strong>Show infobox</strong> is set to <strong>Yes</strong></sup>'				
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_marker_icon',
					'name' => 'Marker image',
					'desc' => 'Upload a new marker image. You can always find the original marker in the plugin\'s images directory.',
					'type' => 'file',
					'default' => $this->cspm_get_field_default('marker_icon', ''),
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_marker_anchor_point_option',
					'name' => 'Set the anchor point',
					'desc' => 'Depending of the shape of the marker, you may not want the middle of the bottom edge to be used as the anchor point. 
							   In this situation, you need to specify the anchor point of the image. A point is defined with an X and Y value (in pixels). 
							   So if X is set to 10, that means the anchor point is 10 pixels to the right of the top left corner of the image. Setting Y to 10 means 
							   that the anchor is 10 pixels down from the top right corner of the image.',
					'type' => 'radio',
					'default' => $this->cspm_get_field_default('marker_anchor_point_option', 'disable'),
					'options' => array(
						'auto' => 'Auto detect <sup>*Detects the center of the image.</sup>',
						'manual' => 'Manualy <sup>*Enter the anchor point in the next two fields.</sup>',
						'disable' => 'Disable'				
					)
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_marker_anchor_point',
					'name' => 'Marker anchor point',
					'desc' => 'Enter the anchor point of the Marker image. Seperate X and Y by comma. (e.g. 10,15)',
					'type' => 'text',
					'default' => $this->cspm_get_field_default('marker_anchor_point', ''),
				);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_clusters_customizations_section',
				'name' => 'Clusters Customizations',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_big_cluster_icon',
					'name' => 'Large cluster image',
					'desc' => 'Upload a new large cluster image. You can always find the original marker in the plugin\'s images directory.',
					'type' => 'file',
					'default' => $this->cspm_get_field_default('big_cluster_icon', ''),
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_medium_cluster_icon',
					'name' => 'Medium cluster image',
					'desc' => 'Upload a new medium cluster image. You can always find the original marker in the plugin\'s images directory.',
					'type' => 'file',
					'default' => $this->cspm_get_field_default('medium_cluster_icon', ''),
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_small_cluster_icon',
					'name' => 'Small cluster image',
					'desc' => 'Upload a new small cluster image. You can always find the original marker in the plugin\'s images directory.',
					'type' => 'file',
					'default' => $this->cspm_get_field_default('small_cluster_icon', ''),
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_cluster_text_color',
					'name' => 'Clusters text color',
					'desc' => 'Change the text color of all your clusters.',
					'type' => 'colorpicker',
					'default' => $this->cspm_get_field_default('cluster_text_color', ''),
				);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_zoom_customizations_section',
				'name' => 'Zoom Buttons Customizations',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_zoom_in_icon',
					'name' => 'Zoom-in image',
					'desc' => 'Upload a new zoom-in button image. You can always find the original marker in the plugin\'s images directory.',
					'type' => 'file',
					'default' => $this->cspm_get_field_default('zoom_in_icon', ''),
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_zoom_in_css',
					'name' => 'Zoom-in CSS',
					'desc' => 'Enter your custom CSS to customize the zoom-in button.<br /><strong>e.g.</strong> border:1px solid; ...',
					'type' => 'textarea',
					'default' => $this->cspm_get_field_default('zoom_in_css', ''),
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_zoom_out_icon',
					'name' => 'Zoom-out image',
					'desc' => 'Upload a new zoom-out button image. You can always find the original marker in the plugin\'s images directory.',
					'type' => 'file',
					'default' => $this->cspm_get_field_default('zoom_out_icon', ''),
				);
					
				$fields[] = array(
					'id' => $this->metafield_prefix . '_zoom_out_css',
					'name' => 'Zoom-out CSS',
					'desc' => 'Enter your custom CSS to customize the zoom-out button.<br /><strong>e.g.</strong> border:1px solid; ...',
					'type' => 'textarea',
					'default' => $this->cspm_get_field_default('zoom_out_css', ''),
				);			

			return $fields;
			
		}
		
		
		/**
		 * Map Style Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_map_style_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Map Style Settings',
				'desc' => 'Styled maps allow you to customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas. The lovely styles below are provided by <a href="http://snazzymaps.com" target="_blank">Snazzy Maps</a>',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_map_style_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_map_style_alert_msg',
				'name' => '<strong>IMPORTANT!</strong><br /> The Custom Map Style cannot be operated without activating the option <strong>"Custom style"</strong> in <strong>"Map Settings => Initial style"</strong>',
				'desc' => '',				
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:16px; color:#fff; background:#000; text-align:center; padding:15px; font-weight:200;'
				),
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_style_option',
				'name' => 'Style option', 
				'desc' => 'Select the style option of the map. <span style="color:red;">If you select the option <strong>Progress map styles</strong>, choose one of the available styles below.
						   If you select the option <strong>My custom style</strong>, enter your custom style code in the field <strong>Javascript Style Array</strong>.</span>',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('style_option', 'progress-map'),
				'options' => array(
					'progress-map' => 'Progress Map styles',
					'custom-style' => 'My custom style'
				)
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_map_style',
				'name' => 'Map style',
				'desc' => 'Select your map style.',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('map_style', 'google-map'),
				'options' => $this->cspm_get_all_map_styles(),
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_custom_style_name',
				'name' => 'Custom style name',
				'desc' => 'Enter your custom style name. Defaults to "Custom style". <span style="color:red;">Only available if your style option is <strong>"My custom style"</strong>.</span>',
				'type' => 'text',
				'default' => $this->cspm_get_field_default('custom_style_name', 'Custom style'),
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_js_style_array',
				'name' => 'Javascript Style Array',
				'desc' => 'If you don\'t like any of the styles above, fell free to add your own style. Please include just the array definition. No extra variables or code.<br />
						  Make use of the following services to create your style:<br />
						  . <a href="http://www.evoluted.net/thinktank/web-design/custom-google-maps-style-tool" target="_blank">Custom Google Maps Style Tool by Evoluted</a><br />
						  . <a href="http://software.stadtwerk.org/google_maps_colorizr/" target="_blank">Google Maps Colorizr by stadt werk</a><br />			  					  
						  You may also like to <a href="http://snazzymaps.com/submit" target="_blank">submit</a> your style for the world to see :)',
				'type' => 'textarea',
				'default' => $this->cspm_get_field_default('js_style_array', ''),
			);	

			return $fields;
			
		}
		
		
		/**
		 * Infobox Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_infobox_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Infobox Settings',
				'desc' => 'The infobox, also called infowindow is an overlay that looks like a bubble and is often connected to a marker.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_infobox_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_show_infobox',
				'name' => 'Show Infobox',
				'desc' => 'Show/Hide the Infobox.',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('show_infobox', 'true'),
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_infobox_type',
				'name' => 'Infobox type',
				'desc' => 'Select the Infobox type.',
				'type' => 'radio_image',
				'default' => $this->cspm_get_field_default('infobox_type', 'rounded_bubble'),
				'options' => array(				
					'square_bubble' => 'Square bubble',
					'rounded_bubble' => 'Rounded bubble',
					'cspm_type3' => 'Infobox 3',
					'cspm_type4' => 'Infobox 4',
					'cspm_type2' => 'Infobox 2',				
					'cspm_type5' => 'Large Infobox','cspm_type1' => 'Infobox 1',										
																	
				),
				'images_path'      => $this->plugin_url,
				'images'           => array(
					'square_bubble' => 'img/admin-icons/radio-imgs/square_bubble.jpg',
					'rounded_bubble' => 'img/admin-icons/radio-imgs/rounded_bubble.jpg',				
					'cspm_type1' => 'img/admin-icons/radio-imgs/infobox_1.jpg',
					'cspm_type2' => 'img/admin-icons/radio-imgs/infobox_2.jpg',
					'cspm_type3' => 'img/admin-icons/radio-imgs/infobox_3.jpg',
					'cspm_type4' => 'img/admin-icons/radio-imgs/infobox_4.jpg',
					'cspm_type5' => 'img/admin-icons/radio-imgs/infobox_5.jpg',												
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_infobox_display_event',
				'name' => 'Display event',
				'desc' => 'Select from the options above when to display infoboxes on the map.',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('infobox_display_event', 'onload'),
				'options' => array(
					'onload' => 'On map load <sup>(Loads all infoboxes)</sup>',
					'onclick' => 'On marker click',
					'onhover' => 'On marker hover <span style="color:red;"><sup>(Doesn\'t work on touch devices)</sup></span>'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_remove_infobox_on_mouseout',
				'name' => 'Remove Infobox on mouseout?',
				'desc' => 'Choose whether you want to remove the infobox when the mouse leaves the marker or not. <span style="color:red">This option is operational only when the <strong>Display event</strong> 
						  equals to <strong>On marker click</strong> or <strong>On marker hover</strong>. This option doesn\'t work on touch devices</span>',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('remove_infobox_on_mouseout', 'false'),
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_infobox_external_link',
				'name' => 'Post URL',
				'desc' => 'Choose an option to open the single post page. You can also disable links in the infoboxes by selecting the option "Disable"',
				'type' => 'radio',
				'default' => $this->cspm_get_field_default('infobox_external_link', 'same_window'),
				'options' => array(
					'new_window' => 'Open in a new window',
					'same_window' => 'Open in the same window',
					'disable' => 'Disable'
				)
			);
			
			return $fields;
			
		}
		
		
		/**
		 * Markers Categories Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_markers_categories_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Markers Categories Settings',
				'desc' => 'In this section, you will be able to upload custom icons for your markers. To do that, choose from the available taxonomies the one that represents the category of your posts/locations, set the option "Marker Categories Option" to "Yes", then, upload a custom icon for each category of markers.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_marker_categories_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_marker_cats_settings',
				'name' => 'Markers categories option',
				'desc' => 'Select "Yes" to enable this option for this map. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
					
			/**
			 * [@post_type_taxonomy_options] : Takes the list of all taxonomies related to the post type selected in "Query settings" */
			 
			$post_type_taxonomy_options	= $this->cspm_get_post_type_taxonomies($this->selected_cpt);		
				unset($post_type_taxonomy_options['post_format']);
				
			reset($post_type_taxonomy_options); // Set the cursor to 0
	
			$fields[] = array(
				'id' => $this->metafield_prefix . '_marker_categories_taxonomy',
				'name' => 'Taxonomies',
				'desc' => 'Choose the taxonomy that represents the category of your posts.',
				'type' => 'radio',
				'default' => key($post_type_taxonomy_options), // Get the first option (term) in the taxonomies list
				'options' => $post_type_taxonomy_options,
			);

			$marker_categories_fields_array = array();
			
			foreach($post_type_taxonomy_options as $cpt_taxonomy_slug => $cpt_taxonomy_title){
	
				$tax_name = $cpt_taxonomy_slug;
				$tax_label = $cpt_taxonomy_title;
						
				$marker_categories_fields_array[] = array(
					'id' => 'marker_img_category_'.$tax_name,
					'name' => $tax_label, 
					'desc' => 'Select the marker category to which you want to add a custom image.',
					'type' => 'select',
					'options' => array('0'=>'')+$this->cspm_get_term_options($tax_name),
					'attributes'  => array(
						'data-conditional-id' => $this->metafield_prefix . '_marker_categories_taxonomy',
						'data-conditional-value' => wp_json_encode(array($this->metafield_prefix . '_marker_categories_taxonomy', $tax_name)),								
						'data-group-title' => 'select',
					)
				);
														
				$marker_categories_fields_array[] = array(
					'id' => 'marker_img_path_'.$tax_name,
					'name' => 'Marker image', 
					'desc' => 'Upload the marker category image.',
					'type' => 'file',
					'default' => '',
					'attributes'  => array(
						'data-conditional-id' => $this->metafield_prefix . '_marker_categories_taxonomy',
						'data-conditional-value' => wp_json_encode(array($this->metafield_prefix . '_marker_categories_taxonomy', $tax_name)),								
					),
				);
				
			}
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_marker_categories_images', //$this->metafield_prefix . '_marker_category_'.$tax_name,
				'name' => 'Markers categories images', 
				'desc' => 'Upload a custom marker image for each category (taxonomy term).<br />
							<span style="color:red;">
							1. If one of the categories doesn\'t have a marker image  
							or that you don\'t want to use the custom markers feature at all, the default marker will be used instead.<br />
							2. If a post is assigned to multiple categories/terms, the plugin will call 
							the marker image of the first category/term in the list.</span>',
				'type' => 'group',
				'repeatable'  => true,
				'options'     => array(
					'group_title'   => __( 'Marker Image {#}', 'cspm' ),
					'add_button'    => __( 'Add New Marker Image', 'cspm' ),
					'remove_button' => __( 'Remove Marker Image', 'cspm' ),
					'sortable'      => true,
					'closed'     => true,
				),
				'fields' => $marker_categories_fields_array,
				/*'fields' => array(
					array(
						'id' => 'marker_img_category',
						'name' => $tax_label, 
						'desc' => 'Select the marker category to which you want to add a custom image.',
						'type' => 'select',
						'options' => array('0'=>'')+$this->cspm_get_term_options('category'),
						'attributes'  => array(
							//'data-conditional-id' => $this->metafield_prefix . '_marker_categories_taxonomy',
							//'data-conditional-value' => $tax_name,								
							'data-group-title' => 'select'
						)
					),
					array(
						'id' => 'marker_img_path',
						'name' => 'Marker image', 
						'desc' => 'Upload the marker category image.',
						'type' => 'file',
						'default' => '',
						'attributes'  => array(
							//'data-conditional-id' => $this->metafield_prefix . '_marker_categories_taxonomy',
							//'data-conditional-value' => $tax_name,								
						)
					)
				)*/
			);
				
			
			return $fields;
			
		}
		
		
		/**
		 * KML Layers Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_kml_fields(){
			
			$fields = array();

			$fields[] = array(
				'name' => 'KML Layers Settings',
				'desc' => 'Layers are objects on the map that consist of one or more separate items, but are manipulated as a single unit. Layers generally reflect collections of objects that you add on top of the map to designate a common association. The Google Maps API manages the presentation of objects within layers by rendering their constituent items into one object (typically a tile overlay) and displaying them as the map\'s viewport changes. Layers may also alter the presentation layer of the map itself, slightly altering the base tiles in a fashion consistent with the layer.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_kml_layers_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_use_kml',
				'name' => 'KML Layers option',
				'desc' => 'Select "Yes" to enable the KML Layers option for this map. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_kml_layers',
				'name' => 'KML/KMZ Layers',
				'desc' => 'Click on the button "Add New KML/KMZ" to add a new KML/KMZ file. You can add Multiple KML/KMZ layers!
						   <br /><span style="color:red">Note: If your have multiple KML Layers and you want to automatically center and zoom the map to the bounding box of the contents of your layers, activate the option <strong>"Map settings => Autofit"</strong>!</span>',
				'type' => 'group',
				'repeatable'  => true,
				'options'     => array(
					'group_title'   => __( 'KML/KMZ {#}', 'cspm' ),
					'add_button'    => __( 'Add New KML/KMZ', 'cspm' ),
					'remove_button' => __( 'Remove KML/KMZ', 'cspm' ),
					'sortable'      => true,
					'closed'     => true,
				),
				'fields' => array(	
					array(
						'id' => 'kml_label',
						'name' => 'KML/KMZ Label', 
						'desc' => 'Give a label to this KML/KMZ. The Label will help to distinct a KML/KMZ between multiple KML/KMZ layers. (Example: "Lodon Air Quality")',
						'type' => 'text',
						'default' => '',
						'attributes'  => array(
							'data-group-title' => 'text'
						)
					),
					array(
						'id' => 'kml_url',
						'name' => 'KML/KMZ File URL',
						'desc' => 'Supply a link to a KML file or KMZ file that\'s already <span style="color:red">hosted on the Internet.</span>
								   <br /><span style="color:red">Note: You can use the Media Library to upload your file, then, paste its URL in this field.</span>',
						'type' => 'text_url',
						'default' => ''
					),
					array(
						'id' => 'kml_suppressInfoWindows',
						'name' => 'Suppress Infowindows',
						'desc' => 'Suppress the rendering of info windows when layer features are clicked. Defaults to "No".',
						'type' => 'radio',
						'default' => 'false',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					),
					array(
						'id' => 'kml_preserveViewport',
						'name' => 'Preserve Viewport',
						'desc' => 'Select whether you want to center and zoom the map to the bounding box of the contents of the layer. If this option is set to "Yes", the viewport is left unchanged. Defaults to "No".<br />
								   <span style="color:red;">Note: If this is the only KML Layer you\'ve created, this option will be ignored if you activate the option <strong>"Map settings => Autofit"</strong>!</span>',
						'type' => 'radio',
						'default' => 'false',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					),
					array(
						'id' => 'kml_screenOverlays',
						'name' => 'Screen Overlays',
						'desc' => 'Select whether to render the screen overlays included in this KML/KMZ layer. Defaults to "no".',
						'type' => 'radio',
						'default' => 'false',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					),
					array(
						'id' => 'kml_zIndex',
						'name' => 'zIndex',
						'desc' => 'The zIndex compared to other KML/KMZ layers.',
						'type' => 'text',
						'default' => '1',
						'attributes' => array(
							'type' => 'number',
							'pattern' => '\d*',
						),				
					),
					array(
						'id' => 'kml_visibility',
						'name' => 'Visibility',
						'desc' => 'Whether this KML/KMZ is visible on the map. Defaults to "Yes".',
						'type' => 'radio',
						'default' => 'true',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					)					
				)
			);

			return $fields;
			
		}
		
		/**
		 * Overlays Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_overlays_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Overlays Settings',
				'desc' => 'You can add objects to the map to designate points, lines, areas, or collections of objects. The Google Maps JavaScript API calls these objects overlays. Overlays are tied to latitude/longitude coordinates, so they move when you drag or zoom the map.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_overlays_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			/**
			 * Polyline */
			 
			$fields[] = array(
				'id' => $this->metafield_prefix . '_polyline_section',
				'name' => 'Polylines',
				'desc' => 'To draw a line on your map, use a polyline. The Polyline class defines a linear overlay of connected line segments on the map. A Polyline object consists of an array of LatLng locations, and creates a series of line segments that connect those locations in an ordered sequence.',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_draw_polyline',
				'name' => 'Draw Polyline option',
				'desc' => 'Select "Yes" to enable this option in this map. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_polylines',
				'name' => 'Polylines',
				'desc' => 'Click on the button "Add New Polyline" to add a new polyline. You can add Multiple polylines!',
				'type' => 'group',
				'repeatable'  => true,
				'options'     => array(
					'group_title'   => __( 'Polyline {#}', 'cspm' ),
					'add_button'    => __( 'Add New Polyline', 'cspm' ),
					'remove_button' => __( 'Remove Polyline', 'cspm' ),
					'sortable'      => true,
					'closed'     => true,
				),
				'fields' => array(	
					array(
						'id' => 'polyline_label',
						'name' => 'Polyline Label', 
						'desc' => 'Give a label to this Polyline. The Label will help to distinct a polyline between multiple Polylines. (Example: "Lodon Polyline")',
						'type' => 'text',
						'default' => '',
						'attributes'  => array(
							'data-group-title' => 'text'
						)
					),
					array(
						'id' => 'polyline_name',
						'name' => 'Polyline ID/Name', 
						'desc' => 'Give a unique ID/Name to this Polyline. <span style="color:red">If two polylines has the same IDs/Names, the last added polyline will override the old polyline.</span> (Example: "london_polyline")',
						'type' => 'text',
						'default' => '',
					),																					
					array(
						'id' => 'polyline_path',
						'name' => 'Polyline Path', 
						'desc' => 'The ordered sequence of coordinates of the Polyline. Enter the LatLng coordinates of the locations that will be connected as a polyline. Put each line segment (LatLng) as <strong>[Lat,Lng]</strong> seperated by comma (see example 1).
								   <br /><span style="color:red"><strong>Example 1:</strong> [45.5215,-1.5245],[41.2587,1.2479],[40.1649,1.9879]</span>
								   <br /><strong style="color:red"><strong><u>Note:</u></strong> You can also use your post IDs as line segments. Each post ID will be replaced by the post\'s LatLng coordinates (see example 2). Post IDs seperated by comma!</strong>
								   <br /><span style="color:red"><strong>Example 2:</strong> 154,254,120,100</span>
								   <br /><span style="color:red"><u>Note:</u> The polyline order is defined by the order of the <u>line segments</u>/<u>post IDs</u></span>.',
						'type' => 'textarea',
						'default' => '',
					),
					array(
						'id' => 'polyline_clickable',
						'name' => 'clickable',
						'desc' => 'Indicates whether this Polyline handles mouse events. Defaults to "No".',
						'type' => 'radio',
						'default' => 'false',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					),
					array(
						'id' => 'polyline_url',
						'name' => 'Polyline redirect URL',
						'desc' => 'If provided, the URL will be executed when the Polyline is clicked. <span style="color:red;">Works only when "Clickable" is set to "Yes"!</span>',
						'type' => 'text_url',
						'default' => ''
					),
					array(
						'id' => 'polyline_url_target',
						'name' => 'URL target',
						'desc' => 'Choose an option to open the Polyline redirect URL. Defaults to "Open in a new window".',
						'type' => 'radio',
						'default' => 'new_window',
						'options' => array(
							'new_window' => 'Open in a new window',
							'same_window' => 'Open in the same window',
						)
					),			
					array(
						'id' => 'polyline_description',
						'name' => 'Polyline description',
						'desc' => 'Enter the message text or the description to display inside an infowindow when the Polyline is hovered over. The infowindow will be removed once the mouse leaves the Polyline!
								    <span style="color:red;">Works only when "Clickable" is set to "Yes"!</span>
									<br /><span style="color:red;">Note: HTML not allowed. Only valid text!</span>',
						'type' => 'textarea',
						'default' => ''
					),
					array(
						'id' => 'polyline_infowindow_maxwidth',
						'name' => 'Infowindow Maximum width',
						'desc' => 'Maximum width (in pixels) of the infowindow, regardless of the Polyline description\'s width. Defaults to "200px".',
						'type' => 'text',
						'default' => '250',
						'attributes' => array(
							'type' => 'number',
							'pattern' => '\d*',
							'min' => '0'
						),				
					),										
					array(
						'id' => 'polyline_geodesic',
						'name' => 'Geodesic',
						'desc' => 'When "Yes", edges of the polyline are interpreted as geodesic and will follow the curvature of the Earth. When "No", edges of the polyline are rendered as straight lines in screen space. Defaults to "No".',
						'type' => 'radio',
						'default' => 'false',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					),
					array(
						'id' => 'polyline_strokeColor',
						'name' => 'Stroke color',
						'desc' => 'The stroke color. Defaults to "#189AC9".',
						'type' => 'colorpicker',
						'default' => '#189AC9',
					),		
					array(
						'id' => 'polyline_strokeOpacity',
						'name' => 'Stroke opacity',
						'desc' => 'The stroke opacity between 0.0 and 1. Defaults to "1".',
						'type' => 'select',
						'default' => '1',
						'options' => array(
							'0,0' => '0.0',
							'0,1' => '0.1',
							'0,2' => '0.2',
							'0,3' => '0.3',
							'0,4' => '0.4',
							'0,5' => '0.5',
							'0,6' => '0.6',
							'0,7' => '0.7',
							'0,8' => '0.8',
							'0,9' => '0.9',
							'1' => '1',
						)			
					),	
					array(
						'id' => 'polyline_strokeWeight',
						'name' => 'Stroke weight',
						'desc' => 'The stroke width in pixels. Defaults to "2".',
						'type' => 'text',
						'default' => '2',
						'attributes' => array(
							'type' => 'number',
							'pattern' => '\d*',
							'min' => '0'
						),				
					),
					array(
						'id' => 'polyline_zIndex',
						'name' => 'zIndex',
						'desc' => 'The zIndex compared to other polylines.',
						'type' => 'text',
						'default' => '',
						'attributes' => array(
							'type' => 'number',
							'pattern' => '\d*',
						),				
					),
					array(
						'id' => 'polyline_visibility',
						'name' => 'Visibility',
						'desc' => 'Whether this polyline is visible on the map. Defaults to "Yes".',
						'type' => 'radio',
						'default' => 'true',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					)
				)
			);
			
			/**
			 * Polygon */
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_polygon_section',
				'name' => 'Polygons',
				'desc' => 'A polygon represents an area enclosed by a closed path (or loop), which is defined by a series of coordinates. Polygon objects are similar to Polyline objects in that they consist of a series of coordinates in an ordered sequence. Polygons are drawn with a stroke and a fill. You can define custom colors, weights, and opacities for the edge of the polygon (the stroke) and custom colors and opacities for the enclosed area (the fill).',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_draw_polygon',
				'name' => 'Draw Polygon option',
				'desc' => 'Select "Yes" to enable this option in this map. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
					
			$fields[] = array(
				'id' => $this->metafield_prefix . '_polygons',
				'name' => 'Polygons',
				'desc' => 'Click on the button "Add New Polygon" to add a new polygon. You can add Multiple polygons!',
				'type' => 'group',
				'repeatable'  => true,
				'options'     => array(
					'group_title'   => __( 'Polygon {#}', 'cspm' ),
					'add_button'    => __( 'Add New Polygon', 'cspm' ),
					'remove_button' => __( 'Remove Polygon', 'cspm' ),
					'sortable'      => true,
					'closed'     => true,
				),
				'fields' => array(	
					array(
						'id' => 'polygon_label',
						'name' => 'Polygon Label', 
						'desc' => 'Give a label to this Polygon. The Label will help to distinct a polygon between multiple Polygons. (Example: "Lodon Polygon")',
						'type' => 'text',
						'default' => '',
						'attributes'  => array(
							//'required'    => 'required',
							//'data-validation' => 'required',
							'data-group-title' => 'text'
						)
					),
					array(
						'id' => 'polygon_name',
						'name' => 'Polygon ID/Name', 
						'desc' => 'Give a unique ID/Name to this Polygon. <span style="color:red">If two polygons has the same IDs/Names, the last added polygon will override the old polygon.</span> (Example: "london_polygon")',
						'type' => 'text',
						'default' => '',
					),																					
					array(
						'id' => 'polygon_path',
						'name' => 'Polygon Path', 
						'desc' => 'The ordered sequence of coordinates of the Polygon. Enter the LatLng coordinates of the locations that will be connected as a polygon. Put each line segment (LatLng) as <strong>[Lat,Lng]</strong> seperated by comma (see example 1).
								   <br /><span style="color:red">Example 1: [45.5215,-1.5245],[41.2587,1.2479],[40.1649,1.9879]</span>
								   <br /><strong style="color:red"><strong><u>Note:</u></strong> You can also use your post IDs as line segments. Each post ID will be replaced by the post\'s LatLng coordinates (see example 2). Post IDs seperated by comma!</strong>
								   <br /><span style="color:red">Example 2: 154,254,120,100</span>
								   <br /><span style="color:red"><u>Note:</u> The polygon order is defined by the order of the <u>line segments</u>/<u>post IDs</u></span>.',
						'type' => 'textarea',
						'default' => '',
					),
					array(
						'id' => 'polygon_clickable',
						'name' => 'clickable',
						'desc' => 'Indicates whether this Polygon handles mouse events. Defaults to "No".',
						'type' => 'radio',
						'default' => 'false',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					),
					array(
						'id' => 'polygon_url',
						'name' => 'Polygon redirect URL',
						'desc' => 'If provided, the URL will be executed when the Polygon is clicked. <span style="color:red;">Works only when "Clickable" is set to "Yes"!</span>',
						'type' => 'text_url',
						'default' => ''
					),
					array(
						'id' => 'polygon_url_target',
						'name' => 'URL target',
						'desc' => 'Choose an option to open the Polygon redirect URL. Defaults to "Open in a new window".',
						'type' => 'radio',
						'default' => 'new_window',
						'options' => array(
							'new_window' => 'Open in a new window',
							'same_window' => 'Open in the same window',
						)
					),
					array(
						'id' => 'polygon_description',
						'name' => 'Polygon description',
						'desc' => 'Enter the message text or the description to display inside an infowindow when the Polygon is hovered over. The infowindow will be removed once the mouse leaves the Polygon!
								   <span style="color:red;">Works only when "Clickable" is set to "Yes"!</span>
								   <br /><span style="color:red;">Note: HTML not allowed. Only valid text!</span>',
						'type' => 'textarea',
						'default' => '',
					),
					array(
						'id' => 'polygon_infowindow_maxwidth',
						'name' => 'Infowindow Maximum width',
						'desc' => 'Maximum width (in pixels) of the infowindow, regardless of the Polygon description\'s width. Defaults to "200px".',
						'type' => 'text',
						'default' => '250',
						'attributes' => array(
							'type' => 'number',
							'pattern' => '\d*',
							'min' => '0'
						),				
					),					
					array(
						'id' => 'polygon_fillColor',
						'name' => 'Fill color',
						'desc' => 'The fill color. Defaults to "#189AC9".',
						'type' => 'colorpicker',
						'default' => '#189AC9',
					),		
					array(
						'id' => 'polygon_fillOpacity',
						'name' => 'Fill opacity',
						'desc' => 'The fill opacity between 0.0 and 1. Defaults to "1".',
						'type' => 'select',
						'default' => '1',
						'options' => array(
							'0,0' => '0.0',
							'0,1' => '0.1',
							'0,2' => '0.2',
							'0,3' => '0.3',
							'0,4' => '0.4',
							'0,5' => '0.5',
							'0,6' => '0.6',
							'0,7' => '0.7',
							'0,8' => '0.8',
							'0,9' => '0.9',
							'1' => '1',
						)			
					),				
					array(
						'id' => 'polygon_geodesic',
						'name' => 'Geodesic',
						'desc' => 'When "Yes", edges of the polygon are interpreted as geodesic and will follow the curvature of the Earth. When "No", edges of the polygon are rendered as straight lines in screen space. Defaults to "No".',
						'type' => 'radio',
						'default' => 'false',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					),
					array(
						'id' => 'polygon_strokeColor',
						'name' => 'Stroke color',
						'desc' => 'The stroke color. Defaults to "#189AC9".',
						'type' => 'colorpicker',
						'default' => '#189AC9',
					),		
					array(
						'id' => 'polygon_strokeOpacity',
						'name' => 'Stroke opacity',
						'desc' => 'The stroke opacity between 0.0 and 1. Defaults to "1".',
						'type' => 'select',
						'default' => '1',
						'options' => array(
							'0,0' => '0.0',
							'0,1' => '0.1',
							'0,2' => '0.2',
							'0,3' => '0.3',
							'0,4' => '0.4',
							'0,5' => '0.5',
							'0,6' => '0.6',
							'0,7' => '0.7',
							'0,8' => '0.8',
							'0,9' => '0.9',
							'1' => '1',
						)			
					),	
					array(
						'id' => 'polygon_strokeWeight',
						'name' => 'Stroke weight',
						'desc' => 'The stroke width in pixels. Defaults to "2".',
						'type' => 'text',
						'default' => '2',
						'attributes' => array(
							'type' => 'number',
							'pattern' => '\d*',
							'min' => '0'
						),				
					),
					array(
						'id' => 'polygon_strokePosition',
						'name' => 'Stroke Position',
						'desc' => 'The stroke position. Defaults to "CENTER".<br />
								  <strong>1. Center:</strong> The stroke is centered on the polygon\'s path, with half the stroke inside the polygon and half the stroke outside the polygon.<br />
								  <strong>2. Inside:</strong> The stroke lies inside the polygon.<br />
								  <strong>3. Outside:</strong> The stroke lies outside the polygon.',
						'type' => 'radio',
						'default' => 'CENTER',
						'options' => array(
							'CENTER' => 'Center',
							'INSIDE' => 'Inside',
							'OUTSIDE' => 'Outside',
						)
					),
					array(
						'id' => 'polygon_zIndex',
						'name' => 'zIndex',
						'desc' => 'The zIndex compared to other polygons.',
						'type' => 'text',
						'default' => '',
						'attributes' => array(
							'type' => 'number',
							'pattern' => '\d*',
						),				
					),
					array(
						'id' => 'polygon_visibility',
						'name' => 'Visibility',
						'desc' => 'Whether this polyline is visible on the map. Defaults to "Yes".',
						'type' => 'radio',
						'default' => 'true',
						'options' => array(
							'true' => 'Yes',
							'false' => 'No'
						)
					)
				)
			);
			
			return $fields;
			
		}
		
		
		/**
		 * Carousel Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_carousel_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Carousel Settings',
				'desc' => 'Control carousel mode, movement & animation.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_carousel_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_show_carousel',
				'name' => 'Show carousel',
				'desc' => 'Show/Hide the map\'s carousel.',
				'type' => 'radio',
				'default' => 'true',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_carousel_mode',
				'name' => 'Mode',
				'desc' => 'select whether the carousel appears in RTL mode or LTR mode. Defaults to "Left-to-right"',
				'type' => 'select',
				'default' => 'false',
				'options' => array(
					'true' => 'Right-to-left',
					'false' => 'Left-to-right'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_carousel_scroll',
				'name' => 'Scroll',
				'desc' => 'The number of items to scroll by. Defaults to "1"',
				'type' => 'text',
				'default' => '1',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '1'
				),				
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_carousel_easing',
				'name' => 'Easing',
				'desc' => 'The easing effect when scrolling carousel items. Defaults to "linear". <a href="http://easings.net/" target="_blank">(Easing Examples)</a>',
				'type' => 'select',
				'default' => 'linear',
				'options' => array(
					'linear' => 'linear',
					'swing' => 'swing',
					'easeInQuad' => 'easeInQuad',
					'easeOutQuad' => 'easeOutQuad',
					'easeInOutQuad' => 'easeInOutQuad',
					'easeInCubic' => 'easeInCubic',
					'easeOutCubic' => 'easeOutCubic',
					'easeInOutCubic' => 'easeInOutCubic',
					'easeInQuart' => 'easeInQuart',
					'easeOutQuart' => 'easeOutQuart',
					'easeInOutQuart' => 'easeInOutQuart',
					'easeInQuint' => 'easeInQuint',
					'easeOutQuint' => 'easeOutQuint',
					'easeInOutQuint' => 'easeInOutQuint',
					'easeInExpo' => 'easeInExpo',
					'easeOutExpo' => 'easeOutExpo',
					'easeInOutExpo' => 'easeInOutExpo',
					'easeInSine' => 'easeInSine',
					'easeOutSine' => 'easeOutSine',
					'easeInOutSine' => 'easeInOutSine',
					'easeInCirc' => 'easeInCirc',
					'easeOutCirc' => 'easeOutCirc',
					'easeInOutCirc' => 'easeInOutCirc',
					'easeInElastic' => 'easeInElastic',
					'easeOutElastic' => 'easeOutElastic',
					'easeInOutElastic' => 'easeInOutElastic',
					'easeInBack' => 'easeInBack',
					'easeOutBack' => 'easeOutBack',
					'easeInOutBack' => 'easeInOutBack',
					'easeInBounce' => 'easeInBounce',
					'easeOutBounce' => 'easeOutBounce',
					'easeInOutBounce' => 'easeInOutBounce',
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_carousel_animation',
				'name' => 'Animation',
				'desc' => 'The speed of the scroll animation. Defaults to "fast".',
				'type' => 'select',
				'default' => 'fast',
				'options' => array(
					'slow' => 'slow',
					'fast' => 'Fast'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_carousel_auto',
				'name' => 'Auto',
				'desc' => 'Specify how many seconds to periodically autoscroll the content. If set to 0 (default) then autoscrolling is turned off.',
				'type' => 'text',
				'default' => '0',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '0'
				),				
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_carousel_wrap',
				'name' => 'Wrap',
				'desc' => 'Specify whether to wrap at the first/last item (or both) and jump back to the start/end. If set to null, wrapping is turned off. Defaults to "Circular".',
				'type' => 'select',
				'default' => 'circular',
				'options' => array(
					'first' => 'First',
					'last' => 'Last',
					'both' => 'Both',
					'circular' => 'Circular',
					'null' => 'Null'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_scrollwheel_carousel',
				'name' => 'Scroll wheel',
				'desc' => 'Move the carousel with scroll wheel. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_touchswipe_carousel',
				'name' => 'Touch swipe',
				'desc' => 'Move the carousel with touch swipe. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_move_carousel_on',
				'name' => 'Scroll carousel ...',
				'desc' => 'Select from the following options when to scroll the carousel.',
				'type' => 'multicheck',
				'default' => array('marker_click', 'marker_hover', 'infobox_hover'),
				'options' => array(
					'marker_click' => 'On marker click',
					'marker_hover' => 'On marker hover',
					'infobox_hover' => 'On infobox Hover'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_carousel_map_zoom',
				'name' => 'Map zoom',
				'desc' => 'Select the map zoom when an item in the carousel is selected. Defaults to "12".',
				'type' => 'select',
				'default' => '12',
				'options' => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'11' => '11',
					'12' => '12',
					'13' => '13',
					'14' => '14',
					'15' => '15',
					'16' => '16',
					'17' => '17',
					'18' => '18',
					'19' => '19'
				)
			);
					
			return $fields;
			
		}
		
		
		/**
		 * Carousel Style Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_carousel_style_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Carousel Style Settings',
				'desc' => 'Customize the carousel.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_carousel_style_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_carousel_css',
				'name' => 'Carousel CSS',
				'desc' => 'Add your custom CSS to customize the carousel style.<br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
				'type' => 'textarea',
				'default' => ''
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_arrows_background',
				'name' => 'Arrows background color',
				'desc' => 'Change the default background color of the arrows.',
				'type' => 'colorpicker',
				'default' => '#fff'
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_horizontal_left_arrow_icon',
				'name' => 'Horizontal left arrow image',
				'desc' => 'Upload a new left arrow image. You can always find the original arrow in the plugin\'s images directory.',
				'type' => 'file',
				'default' => ''
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_horizontal_right_arrow_icon',
				'name' => 'Horizontal right arrow image',
				'desc' => 'Upload a new right arrow image. You can always find the original arrow in the plugin\'s images directory.',
				'type' => 'file',
				'default' => ''
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_vertical_top_arrow_icon',
				'name' => 'Vertical top arrow image',
				'desc' => 'Upload a new top arrow image. You can always find the original arrow in the plugin\'s images directory.',
				'type' => 'file',
				'default' => ''
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_vertical_bottom_arrow_icon',
				'name' => 'Vertical bottom arrow image',
				'desc' => 'Upload a new bottom arrow image. You can always find the original arrow in the plugin\'s images directory.',
				'type' => 'file',
				'default' => ''
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_items_background',
				'name' => 'Carousel items background color',
				'desc' => 'Change the default background color of the carousel items.',
				'type' => 'colorpicker',
				'default' => '#fff'
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_items_hover_background',
				'name' => 'Active carousel items background color',
				'desc' => 'Change the default background color of the carousel items when one of them is selected.',
				'type' => 'colorpicker',
				'default' => '#fbfbfb'
			);
					
			return $fields;
			
		}
		
		
		/**
		 * Carousel Items Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_carousel_items_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Carousel Items Settings',
				'desc' => 'Customize the carousel items style & content.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_carousel_items_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_items_view',
				'name' => 'Items view',
				'desc' => 'Select the view of the carousel items. Defaults to "Horizontal".',
				'type' => 'radio_image',
				'default' => 'listview',
				'options' => array(
					'listview' => 'Horizontal',
					'gridview' => 'Vertical',
				),
				'images_path'      => $this->plugin_url,
				'images'           => array(
					'listview' => 'img/admin-icons/radio-imgs/horizontal.jpg',
					'gridview' => 'img/admin-icons/radio-imgs/vertical.jpg',				
				)	
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_horizontal_item_section',
				'name' => 'Horizontal view',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_horizontal_item_size',
					'name' => 'Items size <sup>(Horizontal view)</sup>',
					'desc' => 'Enter the size (in pixels) of the carousel items. This field is related to the items of the horizontal view. (Width then height separated by comma. Default: 454,150)',
					'type' => 'text',
					'default' => '454,150',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_horizontal_item_css',
					'name' => 'Items CSS <sup>(Horizontal view)</sup>',
					'desc' => 'Enter your custom CSS of the carousel items. This field is related to the items of the horizontal view.<br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
					'type' => 'textarea',
					'default' => '',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_horizontal_image_size',
					'name' => 'Image size <sup>(Horizontal view)</sup>',
					'desc' => 'Enter the image size (in pixels) of the carousel items. This field is related to the items of the horizontal view. (Width then height separated by comma. Default: 204,150)<br />
					<strong style="color:red;">Please note that after you change the size of the image, you\'ll have to regenerate your image attachments in order to create new images that matches the new size! Use <a href="https://wordpress.org/plugins-wp/regenerate-thumbnails/" target="_blank">this plugin</a> to regenerate your images. </strong>',
					'type' => 'text',
					'default' => '204,150',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_horizontal_details_size',
					'name' => 'Description area size <sup>(Horizontal view)</sup>',
					'desc' => 'Enter the size (in pixels) of the items description area. This field is related to the items of the horizontal view. (Width then height separated by comma. Default: 250,150)',
					'type' => 'text',
					'default' => '250,150',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_horizontal_title_css',
					'name' => 'Title CSS <sup>(Horizontal view)</sup>',
					'desc' => 'Customize the items title area and text by entring your CSS. This field is related to the items of the horizontal view.
							   <br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
					'type' => 'textarea',
					'default' => '',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_horizontal_details_css',
					'name' => 'Description CSS <sup>(Horizontal view)</sup>',
					'desc' => 'Customize the items description area and text by entring your CSS. This field is related to the items of the horizontal view.
							   <br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
					'type' => 'textarea',
					'default' => '',
				);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_vertical_item_section',
				'name' => 'Vertical view',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_vertical_item_size',
					'name' => 'Items size <sup>(Vertical view)</sup>',
					'desc' => 'Enter the size (in pixels) of the carousel items. This field is related to the items of the vertical view. (Width then height separated by comma. Default: 204,290)',
					'type' => 'text',
					'default' => '204,290',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_vertical_item_css',
					'name' => 'Items CSS <sup>(Vertical view)</sup>',
					'desc' => 'Enter your custom CSS of the carousel items. This field is related to the items of the vertical view.
							   <br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
					'type' => 'textarea',
					'default' => '',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_vertical_image_size',
					'name' => 'Image size <sup>(Vertical view)</sup>',
					'desc' => 'Enter the image size (in pixels) of the carousel items. This field is related to the items of the vertical view. (Width then height separated by comma. Default: 204,120)<br />
					<strong style="color:red;">Please note that after you change the size of the image, you\'ll have to regenerate your image attachments in order to create new images that matches the new size! Use <a href="https://wordpress.org/plugins-wp/regenerate-thumbnails/" target="_blank">this plugin</a> to regenerate your images. </strong>',					
					'type' => 'text',
					'default' => '204,120',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_vertical_details_size',
					'name' => 'Description area size <sup>(Vertical view)</sup>',
					'desc' => 'Enter the size (in pixels) of the items description area. This field is related to the items of the vertical view. (Width then height separated by comma. Default: 204,170)',
					'type' => 'text',
					'default' => '204,170',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_vertical_title_css',
					'name' => 'Title CSS <sup>(Vertical view)</sup>',
					'desc' => 'Customize the items title area and text by entring your CSS. This field is related to the items of the vertical view.
							   <br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
					'type' => 'textarea',
					'default' => '',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_vertical_details_css',
					'name' => 'Description CSS <sup>(Vertical view)</sup>',
					'desc' => 'Customize the items description area and text by entring your CSS. This field is related to the items of the vertical view.
							   <br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
					'type' => 'textarea',
					'default' => '',
				);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_more_item_section',
				'name' => 'Content settings',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_show_details_btn',
					'name' => '"More" button',
					'desc' => 'Show/Hide "More" button',
					'type' => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => 'Show',
						'no' => 'Hide',
					)
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_details_btn_text',
					'name' => '"More" Button text',
					'desc' => 'Enter your customize text to show on the "More" Button.',
					'type' => 'text',
					'default' => 'More',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_details_btn_css',
					'name' => '"More" Button CSS',
					'desc' => 'Enter your CSS to customize the "More" Button\'s look.<br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
					'type' => 'textarea',
					'default' => '',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_items_title',
					'name' => 'Items title',
					'desc' => 'Create your custom items title by entering the name of your custom fields. You can use as many you want. Leave this field empty to use the default title.
							<br /><strong>Syntax:</strong> [meta_key<sup>1</sup>][separator<sup>1</sup>][meta_key<sup>2</sup>][separator<sup>2</sup>][meta_key<sup>n</sup>]...[title length].
							<br /><strong>Example of use:</strong> [post_category][s=,][post_address][l=50]
							<br /><strong>*</strong> To insert empty an space enter [-]
							<br /><strong>* Make sure there\'s no empty spaces between ][</strong>',
					'type' => 'textarea',
					'default' => '',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_click_on_title',
					'name' => 'Title as link',
					'desc' => 'Select "Yes" to use the title as a link to the post page.',
					'type' => 'radio',
					'default' => 'no',
					'options' => array(
						'yes' => 'Yes',
						'no' => 'No',
					)
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_external_link',
					'name' => 'Post URL',
					'desc' => 'Choose an option to open the post URL. Defaults to "Open in the same window".',
					'type' => 'radio',
					'default' => 'same_window',
					'options' => array(
						'new_window' => 'Open in a new window',
						'same_window' => 'Open in the same window'
					)
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_items_details',
					'name' => 'Items description',
					'desc' => 'Create your custom description content. You can combine the content with your custom fields & taxonomies. Leave this field empty to use the default description.
							<br /><strong>Syntax:</strong> [content;content_length][separator][t=label:][meta_key][separator][t=Category:][tax=taxonomy_slug][separator]...[description length]
							<br /><strong>Example of use:</strong> [content;80][s=br][t=Category:][-][tax=category][s=br][t=Address:][-][post_address]
							<br /><strong>*</strong> To specify a description length, use <strong>[l=LENGTH]</strong>. Change LENGTH to a number (e.g. 100).
							<br /><strong>*</strong> To add a label, use <strong>[t=YOUR_LABEL]</strong>
							<br /><strong>*</strong> To add a custom field, use <strong>[CUSTOM_FIELD_NAME]	</strong>				
							<br /><strong>*</strong> To insert a taxonomy, use <strong>[tax=TAXONOMY_SLUG]</strong>
							<br /><strong>*</strong> To insert new line enter <strong>[s=br]</strong>
							<br /><strong>*</strong> To insert an empty space enter <strong>[-]</strong>
							<br /><strong>*</strong> To insert the content/excerpt, use <strong>[content;LENGTH]</strong>. Change LENGTH to a number (e.g. 100).
							<br /><strong>* Make sure there\'s no empty spaces between ][<br />
							<span style="color:red;">Note: The same content will be dislayed in the infoboxes!</span></strong>',
					'type' => 'textarea',
					'default' => '[l=100]',
				);	
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_ellipses',
					'name' => 'Show ellipses',
					'desc' => 'Show ellipses (&hellip;) at the end of the content. Defaults to "Yes".',
					'type' => 'radio',
					'default' => 'yes',
					'options' => array(
						'yes' => 'Yes',
						'no' => 'No',
					)
				);
				
			return $fields;
			
		}
		
		
		/**
		 * Posts Count Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_posts_count_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Posts Count Settings',
				'desc' => 'Show the number of posts on the map. Use the settings below to change the default label & style.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_posts_count_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_show_posts_count',
				'name' => 'Show posts count',
				'desc' => 'Show/Hide the posts count clause',
				'type' => 'radio',
				'default' => 'no',
				'options' => array(
					'yes' => 'Show',
					'no' => 'Hide',
				)
			);	
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_posts_count_clause',
				'name' => 'Posts count label',
				'desc' => 'Write your custom label.<br /><strong>Syntaxe:</strong> LABEL [posts_count] LABEL',
				'type' => 'text',
				'default' => '[posts_count] Posts',
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_posts_count_color',
				'name' => 'Label color',
				'desc' => 'Choose the color of the label.',
				'type' => 'colorpicker',
				'default' => '#333333',
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_posts_count_style',
				'name' => 'Label style',
				'desc' => 'Add your CSS code to customize the label style.<br /><strong>e.g.</strong> background-color:#ededed; border:1px solid; ...',
				'type' => 'textarea',
				'default' => '',
			);
								
			return $fields;
			
		}
		
		
		/**
		 * Faceted Search Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_faceted_search_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Faceted Search Settings',
				'desc' => 'Faceted search, also called faceted navigation or faceted browsing, is a technique for accessing information organized according to a faceted classification system, allowing users to explore a collection of information by applying multiple filters. A faceted classification system classifies each information element along multiple explicit dimensions, enabling the classifications to be accessed and ordered in multiple ways rather than in a single, pre-determined, taxonomic order.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_faceted_search_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_alert_msg',
				'name' => '<strong>IMPORTANT</strong>!<br />The faceted search cannot be operated without activating the <strong>"Marker categories option"</strong> in <strong>"Marker categories settings"</strong>',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:16px; color:#fff; background:#000; text-align:center; padding:15px; font-weight:200;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_option',
				'name' => 'Faceted search option',
				'desc' => 'Select "Yes" to enable this option in this map. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_display_status',
				'name' => 'Faceted search display status',
				'desc' => 'Choose whether to open the faceted search on map load or to close it. Defaults to "Close".',
				'type' => 'radio',
				'default' => 'close',
				'options' => array(
					'open' => 'Open',
					'close' => 'Close'
				)
			);
			
			/**
			 * [@post_type_taxonomy_options] : Takes the list of all taxonomies related to the post type selected in "Query settings" */
			 
			$post_type_taxonomy_options	= $this->cspm_get_post_type_taxonomies($this->selected_cpt);		
				unset($post_type_taxonomy_options['post_format']);
				
			reset($post_type_taxonomy_options); // Set the cursor to 0
			
			foreach($post_type_taxonomy_options as $cpt_taxonomy_slug => $cpt_taxonomy_title){
	
				$tax_name = $cpt_taxonomy_slug;
				$tax_label = $cpt_taxonomy_title;
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_faceted_search_taxonomy_'.$tax_name,				
					'name' => $tax_label,
					'desc' => 'Select the terms to use in the faceted search.',
					'type' => 'pw_multiselect',
					'options' => $this->cspm_get_term_options($tax_name),				
					'attributes' => array(
						'placeholder' => 'Select Term(s)',
						'data-conditional-id' => $this->metafield_prefix . '_marker_categories_taxonomy',
						'data-conditional-value' => wp_json_encode(array($this->metafield_prefix . '_marker_categories_taxonomy', $tax_name)),
					),
				);
				
			}
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_autocheck',
				'name' => 'Check/Select term(s) on map load',
				'desc' => 'This will allow you to check/select one our multiple terms by default on map load. Select "Yes" to enable this option in this map. Defaults to "No".
						   <span style="color:red;">When set to <strong>"Yes"</strong>, the option <strong>"Faceted search display status"</strong> will be ignored and the filter will be opened by default!</span>',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			foreach($post_type_taxonomy_options as $cpt_taxonomy_slug => $cpt_taxonomy_title){
	
				$tax_name = $cpt_taxonomy_slug;
				$tax_label = $cpt_taxonomy_title;
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_faceted_search_autocheck_taxonomy_'.$tax_name,				
					'name' => 'Select the term(s) to check/select on map load',
					'desc' => 'Select the term(s) to check/select on map load. <span style="color:red;">When you select multiple terms and the option <strong>"Multiple terms option"</strong> is set to <strong>"No"</strong>, only the last term in this field will be checked/selected by default!</span>',
					'type' => 'pw_multiselect',
					'options' => $this->cspm_get_term_options($tax_name),				
					'attributes' => array(
						'placeholder' => 'Select Term(s)',
						'data-conditional-id' => $this->metafield_prefix . '_marker_categories_taxonomy',
						'data-conditional-value' => wp_json_encode(array($this->metafield_prefix . '_marker_categories_taxonomy', $tax_name)),
					),
				);
				
			}
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_multi_taxonomy_option',
				'name' => 'Multiple terms option', 
				'desc' => 'Select "Yes" if you want to filter the posts/locations by selecting multiple terms in the faceted search form.',
				'type' => 'radio',
				'default' => 'true',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No',
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_drag_map',
				'name' => 'Drag the map', 
				'desc' => 'Choose whether you want to drag the map to the nearest zone containing the markers or to simply autofit the map (After a filter action). Defaults to "Autofit".',
				'type' => 'radio',
				'default' => 'autofit',
				'options' => array(
					'drag' => 'Drag the map to the position of the first post/location in the results list',
					'autofit' => 'Autofit the map to contain all posts/locations in the results list',
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_customizing_section',
				'name' => 'Customization',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_input_skin',
				'name' => 'Checkbox/Radio skin', 
				'desc' => 'Select the skin of the checkbox/radio input. <a target="_blank" href="http://icheck.fronteed.com/">See all skins</a>',
				'type' => 'radio_image',
				'default' => 'polaris',
				'options' => array(
					'minimal' => 'Minimal skin',
					'square' => 'Square skin',
					'flat' => 'Flat skin',
					'line' => 'Line skin',
					'polaris' => 'Polaris skin',
					'futurico' => 'Futurico skin',
				),
				'images_path'      => $this->plugin_url,
				'images'           => array(
					'minimal' => 'img/admin-icons/radio-imgs/minimal.jpg',
					'square' => 'img/admin-icons/radio-imgs/square.jpg',				
					'flat' => 'img/admin-icons/radio-imgs/flat.jpg',
					'line' => 'img/admin-icons/radio-imgs/line.jpg',
					'polaris' => 'img/admin-icons/radio-imgs/polaris.jpg',
					'futurico' => 'img/admin-icons/radio-imgs/futurico.jpg',
				)				
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_input_color',
				'name' => 'Checkbox/Radio skin color', 
				'desc' => 'Select the skin color of the checkbox/radio input. (Polaris & Futurico skins doesn\'t use colors). <a target="_blank" href="http://icheck.fronteed.com/">See all colors</a>',
				'type' => 'radio',
				'default' => 'blue',
				'options' => array(
					'black' => 'Black',
					'red' => 'Red',
					'green' => 'Green',
					'blue' => 'Blue',
					'aero' => 'Aero',
					'grey' => 'Grey',
					'orange' => 'Orange',
					'yellow' => 'Yellow',
					'pink' => 'Pink',
					'purple' => 'Purple',
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_icon',
				'name' => 'Faceted search button image',
				'desc' => 'Upload a new image for the faceted search button. You can always find the original image in the plugin\'s images directory.',
				'type' => 'file',
				'default' => ''
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_faceted_search_css',
				'name' => 'Category list background color',
				'desc' => 'Change the background color of the faceted search form container.',
				'type' => 'colorpicker',
				'default' => '#ffffff',
			);						
			
			return $fields;
			
		}
		
		
		/**
		 * Search Form Settings Fields 
		 *
		 * @since 1.0 
		 */
		function cspm_search_form_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Search Form Settings',
				'desc' => 'The search form is a technique that lets a user enter their address and see markers on a map for the locations nearest to them within a chosen distance restriction. Use this interface to control the search form settings.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_search_form_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_search_form_option',
				'name' => 'Search form option',
				'desc' => 'Select "Yes" to enable this option in this map. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_sf_display_status',
				'name' => 'Search form display status',
				'desc' => 'Choose whether to open the search form on map load or to close it. Defaults to "Close".',
				'type' => 'radio',
				'default' => 'close',
				'options' => array(
					'open' => 'Open',
					'close' => 'Close'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_sf_min_search_distances',
				'name' => 'Min distances of search',
				'desc' => 'Enter the minimum distance to use as a distance search between the origin address and the destinations in the map.',
				'type' => 'text',
				'default' => '3',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '1',
				),								
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_sf_max_search_distances',
				'name' => 'Max distances of search',
				'desc' => 'Enter the maximum distance to use as a distance search between the origin address and the destinations in the map.',
				'type' => 'text',
				'default' => '50',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '1',
				),				
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_sf_distance_unit',
				'name' => 'Distance unit',
				'desc' => 'Select the distance unit.',
				'type' => 'radio',
				'default' => 'metric',
				'options' => array(
					'metric' => 'Km',
					'imperial' => 'Miles'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_form_customization_section',
				'name' => 'Search form customization',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_address_placeholder',
					'name' => 'Address field placeholder',
					'desc' => 'Update the text to show as a placeholder for the address field',
					'type' => 'text',
					'default' => 'Enter City & Province, or Postal code',
				);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_slider_label',
					'name' => 'Slider label',
					'desc' => 'Update the text to show as a label for the slider',
					'type' => 'text',
					'default' => 'Expand the search area up to',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_submit_text',
					'name' => 'Submit button text',
					'desc' => 'Update the text to show in the submit button.',
					'type' => 'text',
					'default' => 'Search',
				);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_search_form_icon',
					'name' => 'Search form button image',
					'desc' => 'Upload a new image for the search form button. You can always find the original image in the plugin\'s images directory.',
					'type' => 'file',
					'default' => ''
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_search_form_bg_color',
					'name' => 'Background color',
					'desc' => 'Change the background color of the search form container.',
					'type' => 'colorpicker',
					'default' => '#ffffff',
				);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_warning_msg_section',
				'name' => 'Warning messages',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_no_location_msg',
					'name' => 'No locations message',
					'desc' => 'Update the text to show when the search form has no locations to display.',
					'type' => 'text',
					'default' => 'We could not find any location',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_bad_address_msg',
					'name' => 'Bad address message',
					'desc' => 'Update the text to show when the search form did not uderstand the provided address.',
					'type' => 'text',
					'default' => 'We could not understand the location',
				);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_bad_address_sug_1',
					'name' => '"Bad address" first suggestion',
					'desc' => 'Update the text to show as a first suggestion for the bad address\'s message.',
					'type' => 'text',
					'default' => '- Make sure all street and city names are spelled correctly.',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_bad_address_sug_2',
					'name' => '"Bad address" Second suggestion',
					'desc' => 'Update the text to show as a second suggestion for the bad address\'s message.',
					'type' => 'text',
					'default' => '- Make sure your address includes a city and state.',
				);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_bad_address_sug_3',
					'name' => '"Bad address" Third suggestion',
					'desc' => 'Update the text to show as a third suggestion for the bad address\'s message.',
					'type' => 'text',
					'default' => '- Try entering a zip code.',
				);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_circle_customization_section',
				'name' => 'Circle customization',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_circle_option',
					'name' => 'Circle option',
					'desc' => 'The circle option is a technique of drawing a circle of a given radius of the search address. Select "Yes" to enable this option. Defaults to "Yes".',
					'type' => 'radio',
					'default' => 'true',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_edit_circle',
					'name' => 'Resize the circle',
					'desc' => 'Resizing the circle will allow you to increase and/or decrease the search distance in order to get more or less results. Defaults to "Yes".',
					'type' => 'radio',
					'default' => 'true',
					'options' => array(
						'true' => 'Yes',
						'false' => 'No'
					)
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_fillColor',
					'name' => 'Fill color',
					'desc' => 'The fill color.',
					'type' => 'colorpicker',
					'default' => '#189AC9',
				);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_fillOpacity',
					'name' => 'Fill opacity',
					'desc' => 'The fill opacity between 0.0 and 1.0.',
					'type' => 'select',
					'default' => '0.1',
					'options' => array(
						'0,0' => '0.0',
						'0,1' => '0.1',
						'0,2' => '0.2',
						'0,3' => '0.3',
						'0,4' => '0.4',
						'0,5' => '0.5',
						'0,6' => '0.6',
						'0,7' => '0.7',
						'0,8' => '0.8',
						'0,9' => '0.9',
						'1' => '1',
					)			
				);
			
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_strokeColor',
					'name' => 'Stroke color',
					'desc' => 'The stroke color.',
					'type' => 'colorpicker',
					'default' => '#189AC9',
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_strokeOpacity',
					'name' => 'Stroke opacity',
					'desc' => 'The stroke opacity between 0.0 and 1.',
					'type' => 'select',
					'default' => '1',
					'options' => array(
						'0,0' => '0.0',
						'0,1' => '0.1',
						'0,2' => '0.2',
						'0,3' => '0.3',
						'0,4' => '0.4',
						'0,5' => '0.5',
						'0,6' => '0.6',
						'0,7' => '0.7',
						'0,8' => '0.8',
						'0,9' => '0.9',
						'1' => '1',
					)			
				);
				
				$fields[] = array(
					'id' => $this->metafield_prefix . '_sf_strokeWeight',
					'name' => 'Stroke weight',
					'desc' => 'The stroke width in pixels.',
					'type' => 'text',
					'default' => '1',
					'attributes' => array(
						'type' => 'number',
						'pattern' => '\d*',
						'min' => '0'
					),				
				);	

			return $fields;
			
		}
		
		
		/**
		 * Zoom to country Settings Fields 
		 *
		 * @since 3.0 
		 */
		function cspm_zoom_to_country_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Zoom to country',
				'desc' => 'This feature will add a dropdown list that will give the possibility to zoom to a country. 
						   If you use the map to show locations from several countries, this feature will help users to quickly navigate through the map.',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_custom_css_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_zoom_country_option',
				'name' => 'Zoom to country option',
				'desc' => 'Select "Yes" to enable this option in this map. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_zoom_country_display_status',
				'name' => 'Countries list display status',
				'desc' => 'Choose whether to open the countries list on map load or to close it. Defaults to "Close".
						  <span style="color:red;">This option will be ignored when you set the option <strong>"Faceted search settings => Check/Select term(s) on map load"</strong> to <strong>"Yes"</strong></span>',
				'type' => 'radio',
				'default' => 'close',
				'options' => array(
					'open' => 'Open',
					'close' => 'Close'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_country_zoom_or_autofit',
				'name' => 'Country bounds',
				'desc' => 'Select whether to zoom the map to the country center point or to show the whole country in the center of the map. Defaults to "Show whole country".',
				'type' => 'radio',
				'default' => 'autofit',
				'options' => array(
					'autofit' => 'Show whole country',
					'zoom' => 'Zoom to country center'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_country_zoom_level',
				'name' => 'Map zoom',
				'desc' => 'Select the map zoom from the country center point. Defaults to "12". <span style="color:red;">This option works only with <strong>"Country bounds => Zoom to country center"</strong></span>',
				'type' => 'select',
				'default' => '12',
				'options' => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
					'11' => '11',
					'12' => '12',
					'13' => '13',
					'14' => '14',
					'15' => '15',
					'16' => '16',
					'17' => '17',
					'18' => '18',
					'19' => '19'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_countries_btn_icon',
				'name' => 'Countries button image',
				'desc' => 'Upload a new image for the countries button. You can always find the original image in the plugin\'s images directory.',
				'type' => 'file',
				'default' => ''
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_countries_list_section',
				'name' => 'Dropdown List Parameters',
				'desc' => 'Customize the countries dropdown list.',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
			$fields[] = array(
				'name' => 'Display language',
				'desc' => 'In the dropdown list that will be displayed on the map, select in which language to display countries names.<br />
						  <span style="color:red;">Not all languages are available! If a language is not available, English will be used instead!</span>',
				'type' => 'select',
				'id'   => $this->metafield_prefix . '_country_display_language',				
				'default' => 'en',
				'options_cb' => array(&$this, 'cspm_get_world_languages'),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_show_country_flag',
				'name' => 'Country flag',
				'desc' => 'Show country flag. Defaults to "Yes".',
				'type' => 'radio',
				'default' => 'true',
				'options' => array(
					'true' => 'Yes',
					'only' => 'Yes & Hide country name',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'name' => 'Countries',
				'desc' => 'Select the countries to display.<br /><br />
						  <span style="color:red;"><strong>You want to sort the countries?</strong> Change this field from "Multicheck" to "Multiselect & Sort" by adding the below PHP code in your theme\'s "functions.php" file.<br /><br />
						  <strong>Notes:</strong><br />
						  1. The "Multiselect & Sort" field is not user friendly when you want to select all countries because it doesn\'t offer an option 
						  to select them all by a simple click!<br />
						  2. The "Multiselect & Sort" field has been tested with all countries selected and it turned out that it slows down the page while sorting countries!<br /><br />
						  <strong>PHP Code:</strong><br />
						  </span>
<pre style="font-size:11px !important;">
/**
 * Progress Map Wordpress Plugin.
 * Change the countries field type to "Multiselect & Sort".
 *
 * @since 3.0
 */
add_filter("cspm_countries_field_type", function(){ return true; });
</pre>',
				'type' => $this->cspm_countries_field_type(),
				'id'   => $this->metafield_prefix . '_countries',
				'options_cb' => array(&$this, 'cspm_get_countries'),
				'attributes' => array(
					'placeholder' => 'Select country(ies)'
				),
			);
			
			return $fields;
			
		}
		
		
		function cspm_nearby_places_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Nearby points of interest settings',
				'desc' => '',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_nearby_places_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_nearby_places_option',
				'name' => 'Nearby points of interest option',
				'desc' => 'Select "Yes" to enable this option in this map. Defaults to "No".',
				'type' => 'radio',
				'default' => 'false',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_np_proximities_display_status',
				'name' => 'Proximities list display status',
				'desc' => 'Choose whether to open the proximities list on map load or to close it. Defaults to "Close".</span>',
				'type' => 'radio',
				'default' => 'close',
				'options' => array(
					'open' => 'Open',
					'close' => 'Close'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_np_distance_unit',				
				'name' => __('Unit System', 'cspm'),
				'type' => 'radio',
				'desc' => __('Choose the unit system to use when displaying distance. Defaults to "Metric (Km)".', 'cspm'),
				'options' => array(
					'METRIC' => 'Metric (Km)', 
					'IMPERIAL' => 'Imperial (Miles)', 
				 ), 
				'default' => 'METRIC'
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_np_radius',				
				'name' => __('Maximum Radius', 'cspm'),
				'type' => 'text', 
				'desc' => __('Choose the maximum distance from the given location within which to search for Places, in meters. The maximum allowed value is 50000. Defaults to 50000', 'cspm'),
				'default' => '50000',
				'attributes' => array(
					'type' => 'number',
					'pattern' => '\d*',
					'min' => '50',
					'max' => '50000',
				),
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_np_circle_option',
				'name' => 'Circle option',
				'desc' => 'Draw a circle around the search area. Defaults to "Yes".',
				'type' => 'radio',
				'default' => 'true',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_np_edit_circle',
				'name' => 'Resize the circle',
				'desc' => 'Resizing the circle will allow you to increase and/or decrease the search distance in order to get more or less results. Defaults to "Yes".',
				'type' => 'radio',
				'default' => 'true',
				'options' => array(
					'true' => 'Yes',
					'false' => 'No'
				)
			);
			
			$fields[] = array(
				'id' => $this->metafield_prefix . '_np_marker_type',
				'name' => 'Markers icon',
				'desc' => 'Choose the markers icons to use for the points of interest. Defaults to "Custome icons".',
				'type' => 'radio',
				'default' => 'custom',
				'options' => array(
					'default' => 'Default icon',
					'custom' => 'Custome icons'
				)
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_show_proximity_icon',
				'name' => 'Proximity icon',
				'desc' => 'Show proximity icon in the list. Defaults to "Yes".',
				'type' => 'radio',
				'default' => 'true',
				'options' => array(
					'true' => 'Yes',
					'only' => 'Yes & Hide proximity name',
					'false' => 'No'
				)
			);
						
			$fields[] = array(
				'id' => $this->metafield_prefix . '_np_proximities',				
				'name' => __('Place Types', 'cspm'),
				'type' => 'pw_multiselect',
				'desc' => __('Select the place types to use & change their display order.', 'cspm'),
				'options' => array(
					'accounting' => 'Accounting',
					'airport' => 'Airport',
					'amusement_park' => 'Amusement park',
					'aquarium' => 'Aquarium',
					'art_gallery' => 'Art gallery',
					'atm' => 'ATM',
					'bakery' => 'Bakery',
					'bank' => 'Bank',
					'bar' => 'Bar',
					'beauty_salon' => 'Beauty salon',
					'bicycle_store' => 'Bicycle store',
					'book_store' => 'Book store',
					'bowling_alley' => 'Bowling alley',
					'bus_station' => 'Bus station',
					'cafe' => 'Cafe',
					'campground' => 'Campground',
					'car_dealer' => 'Car dealer',
					'car_rental' => 'Car rental',
					'car_repair' => 'Car repair',
					'car_wash' => 'Car wash',
					'casino' => 'Casino',
					'cemetery' => 'Cemetery',
					'church' => 'Church',
					'city_hall' => 'City hall',
					'clothing_store' => 'Clothing store',
					'convenience_store' => 'Convenience store',
					'courthouse' => 'Courthouse',
					'dentist' => 'Dentist',
					'department_store' => 'Department store',
					'doctor' => 'Doctor',
					'electrician' => 'Electrician',
					'electronics_store' => 'Electronics store',
					'embassy' => 'Embassy',
					'establishment' => 'Establishment',
					'finance' => 'Finance',
					'fire_station' => 'Fire station',
					'florist' => 'Florist',
					'food' => 'Food',
					'funeral_home' => 'Funeral home',
					'furniture_store' => 'Furniture store',
					'gas_station' => 'Gas station',
					'general_contractor' => 'General contractor',
					'grocery_or_supermarket' => 'Grocery or supermarket',
					'gym' => 'GYM',
					'hair_care' => 'Hair care',
					'hardware_store' => 'Hardware store',
					'health' => 'Health',
					'hindu_temple' => 'Hindu temple',
					'home_goods_store' => 'Home goods store',
					'hospital' => 'Hospital',
					'insurance_agency' => 'Insurance agency',
					'jewelry_store' => 'Jewelry store',
					'laundry' => 'Laundry',
					'lawyer' => 'Lawyer',
					'library' => 'Library',
					'liquor_store' => 'Liquor store',
					'local_government_office' => 'Local government office',
					'locksmith' => 'Locksmith',
					'lodging' => 'Lodging',
					'meal_delivery' => 'Meal delivery',
					'meal_takeaway' => 'Meal takeaway',
					'mosque' => 'Mosque',
					'movie_rental' => 'Movie rental',
					'movie_theater' => 'Movie theater',
					'moving_company' => 'Moving company',
					'museum' => 'Museum',
					'night_club' => 'Night club',
					'painter' => 'Painter',
					'park' => 'Park',
					'parking' => 'Parking',
					'pet_store' => 'Pet store',
					'pharmacy' => 'Pharmacy',
					'physiotherapist' => 'Physiotherapist',
					'place_of_worship' => 'Place of worship',
					'plumber' => 'Plumber',
					'police' => 'Police',
					'post_office' => 'Post office',
					'real_estate_agency' => 'Real estate agency',
					'restaurant' => 'Restaurant',
					'roofing_contractor' => 'Roofing contractor',
					'rv_park' => 'RV park',
					'school' => 'School',
					'shoe_store' => 'Shoe store',
					'shopping_mall' => 'Shopping mall',
					'spa' => 'Spa',
					'stadium' => 'Stadium',
					'storage' => 'Storage',
					'store' => 'Store',
					'subway_station' => 'Subway station',
					'synagogue' => 'Synagogue',
					'taxi_stand' => 'Taxi stand',
					'train_station' => 'Train station',
					'travel_agency' => 'Travel agency',
					'university' => 'University',
					'veterinary_care' => 'Veterinary care',
					'zoo' => 'Zoo',
				),
				'default' => array(
					'accounting',
					'airport',
					'amusement_park',
					'aquarium',
					'art_gallery',
					'atm',
					'bakery',
					'bank',
					'bar',
					'beauty_salon',
					'bicycle_store',
					'book_store',
					'bowling_alley',
					'bus_station',
					'cafe',
					'campground',
					'car_dealer',
					'car_rental',
					'car_repair',
					'car_wash',
					'casino',
					'cemetery',
					'church',
					'city_hall',
					'clothing_store',
					'convenience_store',
					'courthouse',
					'dentist',
					'department_store',
					'doctor',
					'electrician',
					'electronics_store',
					'embassy',
					'establishment',
					'finance',
					'fire_station',
					'florist',
					'food',
					'funeral_home',
					'furniture_store',
					'gas_station',
					'general_contractor',
					'grocery_or_supermarket',
					'gym',
					'hair_care',
					'hardware_store',
					'health',
					'hindu_temple',
					'home_goods_store',
					'hospital',
					'insurance_agency',
					'jewelry_store',
					'laundry',
					'lawyer',
					'library',
					'liquor_store',
					'local_government_office',
					'locksmith',
					'lodging',
					'meal_delivery',
					'meal_takeaway',
					'mosque',
					'movie_rental',
					'movie_theater',
					'moving_company',
					'museum',
					'night_club',
					'painter',
					'park',
					'parking',
					'pet_store',
					'pharmacy',
					'physiotherapist',
					'place_of_worship',
					'plumber',
					'police',
					'post_office',
					'real_estate_agency',
					'restaurant',
					'roofing_contractor',
					'rv_park',
					'school',
					'shoe_store',
					'shopping_mall',
					'spa',
					'stadium',
					'storage',
					'store',
					'subway_station',
					'synagogue',
					'taxi_stand',
					'train_station',
					'travel_agency',
					'university',
					'veterinary_care',
					'zoo',
				),				
				'attributes' => array(
					'placeholder' => 'Select the points of interest',
				),
				'select_all_button' => true
			);

			return $fields;
				
		}
		
		function cspm_customize_fields(){
			
			$fields = array();
			
			$fields[] = array(
				'name' => 'Customize',
				'desc' => '',
				'type' => 'title',
				'id'   => $this->metafield_prefix . '_custom_css_settings',
				'attributes' => array(
					'style' => 'font-size:20px; color:#008fed; font-weight:400;'
				),
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_map_elements_section',
				'name' => 'Map elements',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
			$countries_btn_icon = apply_filters('cspm_countries_btn_icon', $this->plugin_url.'img/continents.png', $this->object_id);
			$search_form_icon = apply_filters('cspm_search_form_icon', $this->plugin_url.'img/loup.png', $this->object_id);
			$faceted_search_icon = apply_filters('cspm_faceted_search_icon', $this->plugin_url.'img/filter.png', $this->object_id);
			$proximities_icon = apply_filters('cspm_proximities_icon', $this->plugin_url.'img/proximities.png', $this->object_id);
											
			$fields[] = array(
				'id' => $this->metafield_prefix . '_map_horizontal_elements_order',
				'name' => __( 'Map horizontal elements display order', 'cspm' ),
				'desc' => __( 'Change the display order of the elements displayed on the map', 'cspm' ),				
				'type' => 'order',
				'inline' => true,
				'options' => array(
					'zoom_country' => '<img src="'.$countries_btn_icon.'" style="height:20px;" alt="'.__('Zoom to country', 'cspm').'" title="'.__('Zoom to country', 'cspm').'" />',
					'search_form' => '<img src="'.$search_form_icon.'" style="height:20px;" alt="'.__('Map search form', 'cspm').'" title="'.__('Map search form', 'cspm').'" />',
					'faceted_search' => '<img src="'.$faceted_search_icon.'" style="height:20px;" alt="'.__('Faceted search form', 'cspm').'" title="'.__('Faceted search form', 'cspm').'" />',
					'proximities' => '<img src="'.$proximities_icon.'" style="height:20px;" alt="'.__('Nearby points of interest', 'cspm').'" title="'.__('Nearby points of interest', 'cspm').'" />',
				),
			);
							
			$recenter_btn_img = apply_filters('cspm_recenter_map_btn_img', $this->plugin_url.'img/recenter.png', $this->object_id);
			$geo_btn_img = apply_filters('cspm_geo_btn_img', $this->plugin_url.'img/geoloc.png', $this->object_id);
							
			$fields[] = array(
				'id' => $this->metafield_prefix . '_map_vertical_elements_order',
				'name' => __( 'Map vertical elements display order', 'cspm' ),
				'desc' => __( 'Change the display order of the elements displayed on the map', 'cspm' ),				
				'type' => 'order',
				'inline' => false,
				'options' => array(
					'recenter_map' => '<img src="'.$recenter_btn_img.'" style="height:20px;" alt="'.__('Recenter map', 'cspm').'" title="'.__('Recenter map', 'cspm').'" />',
					'geo' => '<img src="'.$geo_btn_img.'" style="height:20px;" alt="'.__('Geo targeting', 'cspm').'" title="'.__('Geo targeting', 'cspm').'" />',
				),
			);
				
			$fields[] = array(
				'id' => $this->metafield_prefix . '_additional_css_section',
				'name' => 'Additional CSS',
				'desc' => '',
				'type' => 'title',
				'attributes' => array(
					'style' => 'font-size:15px; color:#ff6600; font-weight:600;'
				),
			);
				
						
			$fields[] = array(
				'id' => $this->metafield_prefix . '_custom_css',
				'name' => 'Custom CSS code',
				'desc' => 'Add your own CSS here',
				'type' => 'textarea',
				'default' => '',
			);
			
			return $fields;
			
		}
		
		
		/**
		 * Get all registred post types & remove "Progress Map's" CPT from the list
		 *
		 * @since 1.0
		 */
		function cspm_get_registred_cpts(){
				
			$wp_post_types_array = array(
				'post' => __('Posts').' (post)', 
				'page' => __('Pages').' (page)'
			);

			$all_custom_post_types = get_post_types(array('_builtin' => false), 'objects');
			
			$return_post_types_array = array();
			
			/**
			 * First we'll get the post types selected in the plugin settings.
			 * If not found, then, we'll get all registred post types */
			
			$default_post_types = $this->cspm_get_field_default('post_types', array());
			
			if(is_array($default_post_types) && count($default_post_types) > 0){
				
				/** 
				 * Loop through default WP post types */
				 
				foreach($wp_post_types_array as $wp_post_type_name => $wp_post_type_label){
					
					if(in_array($wp_post_type_name, $default_post_types)){						
					
						$return_post_types_array[$wp_post_type_name] = $wp_post_type_label;
					
					}
					
				}
				
				/** 
				 * Loop through all custom post types */
				 
				foreach($all_custom_post_types as $post_type){
					
					if(in_array($post_type->name, $default_post_types)){						
					
						if($post_type->name != $this->object_type)
							$return_post_types_array[$post_type->name] = $post_type->labels->name.' ('.$post_type->name.')';
					
					}
					
				}
				
			}else{
				
				$post_types_array = $wp_post_types_array;
				
				foreach($all_custom_post_types as $post_type){
					
					if($post_type->name != $this->object_type)
						$return_post_types_array[$post_type->name] = $post_type->labels->name.' ('.$post_type->name.')';
					
				}
				
			}
			
			return $return_post_types_array;
			
		}
		
		
		/**
		 * Get the list of all Authors/Users registred in the site
		 *
		 * @since 1.0
		 */
		function cspm_get_all_users(){

			$blog_users = get_users(array('fields' => 'all'));
			
			$authors_array = array();
			
			foreach($blog_users as $user)
				$authors_array[$user->ID] = $user->user_nicename.' ('.$user->user_email.')';
				
			return $authors_array;
			
		}
		
		
		/**		 
		 * Get all Taxonomies related to a given post type
		 * 
		 * Since 1.0
		 */
		function cspm_get_post_type_taxonomies($post_type){
			
			$taxonomies_fields = $taxonomy_options = array();
			
			$post_type_taxonomies = (array) get_object_taxonomies($post_type, 'objects');
			
			foreach($post_type_taxonomies as $single_taxonomy){
				
				$tax_name = $single_taxonomy->name;
				$tax_label = $single_taxonomy->labels->name;	

				$taxonomy_options[$tax_name] = $tax_label;
				
			}
			
			return $taxonomy_options;
				
		}
		

		/**
		 * Get the list of all Map Styles from the file "inc/cspm-map-styles.php"
		 *
		 * @since 1.0
		 */
		function cspm_get_all_map_styles(){

			$map_styles_array = array();
			
			if(file_exists($this->plugin_path . 'inc/cspm-map-styles.php')){

				$map_styles = include($this->plugin_path . 'inc/cspm-map-styles.php');
				
				array_multisort($map_styles);
				
				foreach($map_styles as $key => $value){
					
					$value_output  = '';
					$value_output .= empty($value['new']) ? '' : ' <sup class="cspm_new_tag" style="margin:0 5px 0 -2px;">NEW</sup>';		
					$value_output .= $value['title'];				
					$value_output .= empty($value['demo']) ? '' : ' <sup class="cspm_demo_tag"><a href="'.$value['demo'].'" target="_blank"><small>Demo</small></a></sup>';
					
					$map_styles_array[$key] = $value_output;
				
				}
				
			}
			
			return $map_styles_array;

		}
		
		
		/**
		 * This will return a list of all world languages
		 *
		 * @since 3.0
		 */
		function cspm_get_world_languages(){
			
			if(file_exists($this->plugin_path . 'inc/cspm-world-languages.php')){

				$world_languages = include_once($this->plugin_path . 'inc/cspm-world-languages.php');
				
				return $world_languages;
				
			}else return;
			
		}
		
				
		/**
		 * This will return a list of all countries in English Language
		 *
		 * @since 3.0
		 */
		function cspm_get_countries(){
			
			if(file_exists($this->plugin_path . 'inc/countries/en/country.php')){

				$countries = include_once($this->plugin_path . 'inc/countries/en/country.php');
				
				return $countries;
				
			}else return;
			
		}

		
		/**
		 * Gets a number of terms and displays them as options
		 *
		 * @since 1.0
		 */
		function cspm_get_term_options($tax_name){

			$terms = get_terms($tax_name, "hide_empty=0");
			
			$term_options = array();
						
			if(count($terms) > 0){	
				
				foreach($terms as $term){			   											
					$term_options[$term->term_id] = $term->name;
				}
				
			}
						
			return $term_options;
			
		}
		
		
		/**
		 * This will get the default value of field based on the one selected in the plugin settings.
		 * If a field has a default value in the plugin settings, we'll use, otherwise, we'll use a given default value instead.
		 *
		 * @since 1.0
		 */
		function cspm_get_field_default($option_id, $default_value = ''){
			
			/**
			 * We'll check if the default settings can be found in the array containing the "(shared) plugin settings".
			 * If found, we'll use it. If not found, we'll use the one in [@default_value] instead. */
			 
			$default = $this->cspm_setting_exists($option_id, $this->plugin_settings, $default_value);
			
			return $default;
			
		}
		
		
		/**
		 * Check if array_key_exists and if empty() doesn't return false
		 * Replace the empty value with the default value if available 
		 * @empty() return false when the value is (null, 0, "0", "", 0.0, false, array())
		 *
		 * @since 1.0
		 */
		function cspm_setting_exists($key, $array, $default = ''){
			
			$array_value = isset($array[$key]) ? $array[$key] : $default;
			
			$setting_value = empty($array_value) ? $default : $array_value;
			
			return $setting_value;
			
		}
		
		
		/**
		 * Change the countries field type from "multicheck" to "multiselect"
		 *
		 * @since 1.0
		 */
		function cspm_countries_field_type(){
			
			$field_type = apply_filters('cspm_countries_field_type', false);
			
			return $field_type == true ? 'pw_multiselect' : 'multicheck';
			
		}

	}
	
}	
		
