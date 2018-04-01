<?php
/**
 * Plugin Name: Progress Map by CodeSpacing
 * Plugin URI: http://codecanyon.net/item/progress-map-wordpress-plugin/5581719?ref=codespacing
 * Description: <strong>Progress Map</strong> is a Wordpress plugin for location listings. With this plugin, your locations will be listed on both Google map (as markers) and a carousel (as locations details), this one will be related to the map, which means that the selected item in the carousel will target its location in the map and vice versa.
 * Version: 3.2
 * Author: Hicham Radi (CodeSpacing)
 * Author URI: http://www.codespacing.com/
 * Text Domain: cspm
 * Domain Path: /languages
 */
 
if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

if(!class_exists('CSProgressMap')){
	
	class CSProgressMap{
		
		private static $_this;
		
		public $plugin_version = '3.2';
			
		private $plugin_path;
		private $plugin_url;
			
		public $cspm_plugin_path;
		public $cspm_plugin_url;
			
		public $prefix;
		public $metafield_prefix;
		
		private $plugin_get_var = 'cs_progress_map_plugin';
		
		private $cspm_wpsf;
	
		public $plugin_settings = array();
		public $shared_plugin_settings = array();
		
		/**
		 * The name of the post to which we'll add the metaboxes
		 * @since 1.0 */
		 
		public $object_type;
				
		/**
		 * Plugin settings */
		 
		public $post_types = array('post');
		public $outer_links_field_name = ''; //@since 2.5				
		public $custom_list_columns = 'no'; //@since 2.6.3
		public $use_with_wpml = 'no'; //@since 2.6.3
		public $latitude_field_name = 'codespacing_progress_map_lat'; // @since 2.5		
		public $longitude_field_name = 'codespacing_progress_map_lng'; // @since 2.5

		/**
		 * Default Map settings */
		
		public $api_key = ''; //@since 2.8
		public $map_language = 'en';
		public $center = '51.53096,-0.121064';			
		public $wrong_center_point = false;
		public $initial_map_style = 'ROADMAP';
		public $zoom = '12';
		public $useClustring = 'true';
		public $gridSize = '60';
		public $mapTypeControl = 'true';
		public $streetViewControl = 'false';
		public $scrollwheel = 'false';
		public $zoomControl = 'true';
		public $zoomControlType = 'customize';
		public $marker_icon = '';			
		public $big_cluster_icon = '';
		public $medium_cluster_icon = '';
		public $small_cluster_icon = ''; 
		public $cluster_text_color = '#ffffff';			
		public $zoom_in_icon = '';	
		public $zoom_in_css = '';
		public $zoom_out_icon = '';
		public $zoom_out_css = '';
		public $defaultMarker = '';
		public $retinaSupport = 'false';
		public $geoIpControl = 'false';	
		public $pulsating_circle = 'pulsating_circle'; // @since 2.5		
		public $marker_anchor_point_option = 'disable'; //@since 2.6.1
		public $marker_anchor_point = ''; //@since 2.6.1
		public $map_draggable = 'true'; //@since 2.6.3
		public $max_zoom = 19; //@since 2.6.3
		public $min_zoom = 0; //@since 2.6.3		
		public $zoom_on_doubleclick = 'false'; //@since 2.6.4
		public $autofit = 'false'; //@since 2.7
		public $traffic_layer = 'false'; //@since 2.7
		public $transit_layer = 'false'; //@since 2.7.4
		public $show_user = 'false'; //@since 2.7.4
		public $user_marker_icon = ''; //@since 2.7.4
		public $user_map_zoom = 12; //@since 2.7.4
		public $user_circle = 0; //@since 2.7.4
		public $user_circle_fillColor = '#189AC9'; // @since 3.0
		public $user_circle_fillOpacity = '0.1'; // @since 3.0
		public $user_circle_strokeColor = '#189AC9'; // @since 3.0				
		public $user_circle_strokeOpacity = '1'; // @since 3.0
		public $user_circle_strokeWeight = '1'; // @since 3.0
		public $recenter_map = 'true'; //@since 3.0						
		
		/**
		 * Default Map style settings */
		
		public $style_option = 'progress-map';
		public $map_style = 'google-map';	
		public $js_style_array = '';
		public $custom_style_name = 'Custom style'; //@since 2.6.1
		 		 		
		/**
		 * Default infobox settings */
		 
		public $show_infobox = 'true'; // @since 2.5		
		public $infobox_type = 'rounded_bubble'; // @since 2.5		
		public $infobox_display_event = 'onload'; // @since 2.5		
		public $infobox_external_link = 'same_window'; // @since 2.5	
		public $remove_infobox_on_mouseout = 'false'; //@since 2.7.4	
		
		/**
		 * Troubleshooting & configs */
		 
		public $combine_files = 'combine'; // @since 2.5		
		public $loading_scripts = 'entire_site'; // @since 2.5
		public $include_or_remove_option = 'include'; //@since 2.6.1		
		public $load_on_page_ids = ''; // @since 2.5		
		public $load_on_post_ids = ''; // @since 2.5
		public $load_on_page_templates = ''; // @since 2.6.1
		public $custom_css = ''; //@since 2.6.1
		public $remove_bootstrap = 'enable'; //@since 2.8.2
		public $remove_gmaps_api = array(); //@since 2.8.2 //updated 2.8.5	
		public $remove_google_fonts = 'enable'; //@since 2.8.2		
								
		function __construct(){				
			
			self::$_this = $this;  
			     
			$this->plugin_path = $this->cspm_plugin_path = plugin_dir_path( __FILE__ );
			$this->plugin_url = $this->cspm_plugin_url =  plugin_dir_url( __FILE__ );
			
			/**
			 * Our metabox custom fields prefix */
			 
			$this->metafield_prefix = '_cspm';
			
			/**
			 * Our custom post type for adding new maps */
			 
			$this->object_type = 'cspm_post_type';
			
			/**
			 * Include and create a new WordPressSettingsFramework */
			 
			require_once( $this->plugin_path .'wp-settings-framework.php' );
			$this->cspm_wpsf = new CsPm_WordPressSettingsFramework( $this->plugin_path .'settings/cspm.php' );

			/**
			 * Call the plugin settings */
			 
			$this->plugin_settings = cspm_wpsf_get_settings( $this->plugin_path .'settings/cspm.php' );
			
			/** 
			 * Plugin settings */
			
			$this->post_types = $this->cspm_get_setting('pluginsettings', 'post_types', array());
			
			$this->latitude_field_name = str_replace(' ', '', $this->cspm_get_setting('pluginsettings', 'latitude_field_name', 'codespacing_progress_map_lat'));
			$this->longitude_field_name = str_replace(' ', '', $this->cspm_get_setting('pluginsettings', 'longitude_field_name', 'codespacing_progress_map_lng'));
		
			if (!defined('CSPM_ADDRESS_FIELD')) define('CSPM_ADDRESS_FIELD', 'codespacing_progress_map_address');
			if (!defined('CSPM_LATITUDE_FIELD')) define('CSPM_LATITUDE_FIELD', $this->latitude_field_name);
			if (!defined('CSPM_LONGITUDE_FIELD')) define('CSPM_LONGITUDE_FIELD', $this->longitude_field_name);	
			if (!defined('CSPM_SECONDARY_LAT_LNG_FIELD')) define('CSPM_SECONDARY_LAT_LNG_FIELD', 'codespacing_progress_map_secondary_lat_lng');												
			if (!defined('CSPM_MARKER_ICON_FIELD')) define('CSPM_MARKER_ICON_FIELD', 'cspm_primary_marker_image'); /* @since 2.8 */
			if (!defined('CSPM_SINGLE_POST_IMG_ONLY_FIELD')) define('CSPM_SINGLE_POST_IMG_ONLY_FIELD', 'cspm_single_post_marker_img_only'); /* @since 3.0 */
			
			$this->use_with_wpml = $this->cspm_get_setting('pluginsettings', 'use_with_wpml', 'no');
			$this->outer_links_field_name = str_replace(' ', '', $this->cspm_get_setting('pluginsettings', 'outer_links_field_name', ''));
			$this->custom_list_columns = $this->cspm_get_setting('pluginsettings', 'custom_list_columns', 'no');
			 	
			/**
			 * Default map settings */
			 
			$this->api_key = $this->cspm_get_setting('mapsettings', 'api_key', '');
			$this->map_language = str_replace(' ', '', $this->cspm_get_setting('mapsettings', 'map_language', 'en'));
			$this->center = $this->cspm_get_setting('mapsettings', 'map_center', '51.53096,-0.121064');			
			$this->initial_map_style = $this->cspm_get_setting('mapsettings', 'initial_map_style', 'ROADMAP');
			$this->zoom = $this->cspm_get_setting('mapsettings', 'map_zoom', '12');
			$this->useClustring = $this->cspm_get_setting('mapsettings', 'useClustring', 'true');
			$this->gridSize = $this->cspm_get_setting('mapsettings', 'gridSize', '60');
			$this->mapTypeControl = $this->cspm_get_setting('mapsettings', 'mapTypeControl', 'true');
			$this->streetViewControl = $this->cspm_get_setting('mapsettings', 'streetViewControl', 'false');
			$this->scrollwheel = $this->cspm_get_setting('mapsettings', 'scrollwheel', 'false');
			$this->zoomControl = $this->cspm_get_setting('mapsettings', 'zoomControl', 'true');
			$this->zoomControlType = $this->cspm_get_setting('mapsettings', 'zoomControlType', 'customize');
			$this->marker_icon = $this->cspm_get_setting('mapsettings', 'marker_icon', $this->plugin_url.'img/pin-blue.png');			
			$this->big_cluster_icon = $this->cspm_get_setting('mapsettings', 'big_cluster_icon', $this->plugin_url.'img/big-cluster.png');
			$this->medium_cluster_icon = $this->cspm_get_setting('mapsettings', 'medium_cluster_icon', $this->plugin_url.'img/medium-cluster.png');
			$this->small_cluster_icon = $this->cspm_get_setting('mapsettings', 'small_cluster_icon', $this->plugin_url.'img/small-cluster.png'); 
			$this->cluster_text_color = $this->cspm_get_setting('mapsettings', 'cluster_text_color', '#ffffff');			
			$this->zoom_in_icon = $this->cspm_get_setting('mapsettings', 'zoom_in_icon', $this->plugin_url.'img/addition-sign.png');	
			$this->zoom_in_css = $this->cspm_get_setting('mapsettings', 'zoom_in_css');	
			$this->zoom_out_icon = $this->cspm_get_setting('mapsettings', 'zoom_out_icon', $this->plugin_url.'img/minus-sign.png');	
			$this->zoom_out_css = $this->cspm_get_setting('mapsettings', 'zoom_out_css');
			$this->defaultMarker = $this->cspm_get_setting('mapsettings', 'defaultMarker');
			$this->retinaSupport = $this->cspm_get_setting('mapsettings', 'retinaSupport', 'false');
			$this->geoIpControl = $this->cspm_get_setting('mapsettings', 'geoIpControl', 'false');			
			$this->markerAnimation = $this->cspm_get_setting('mapsettings', 'markerAnimation', 'pulsating_circle'); // @since 2.5
			$this->marker_anchor_point_option = $this->cspm_get_setting('mapsettings', 'marker_anchor_point_option', 'disable'); // @since 2.6.1
			$this->marker_anchor_point = $this->cspm_get_setting('mapsettings', 'marker_anchor_point', ''); // @since 2.6.1				
			$this->map_draggable = $this->cspm_get_setting('mapsettings', 'map_draggable', 'true'); // @since 2.6.3				
			$this->max_zoom = $this->cspm_get_setting('mapsettings', 'max_zoom', 19); // @since 2.6.3
			$this->min_zoom = $this->cspm_get_setting('mapsettings', 'min_zoom', 0); // @since 2.6.3
			$this->zoom_on_doubleclick = $this->cspm_get_setting('mapsettings', 'zoom_on_doubleclick', 'false'); // @since 2.6.3												
			$this->autofit = $this->cspm_get_setting('mapsettings', 'autofit', 'false'); // @since 2.7												
			$this->traffic_layer = $this->cspm_get_setting('mapsettings', 'traffic_layer', 'false'); // @since 2.7
			$this->transit_layer = $this->cspm_get_setting('mapsettings', 'transit_layer', 'false'); // @since 2.7.4
			$this->show_user = $this->cspm_get_setting('mapsettings', 'show_user', 'false'); // @since 2.7.4
			$this->user_marker_icon = $this->cspm_get_setting('mapsettings', 'user_marker_icon', ''); // @since 2.7.4
			$this->user_map_zoom = $this->cspm_get_setting('mapsettings', 'user_map_zoom', '12'); // @since 2.7.4
			$this->user_circle = $this->cspm_get_setting('mapsettings', 'user_circle', '0'); // @since 2.7.4
			$this->user_circle_fillColor = $this->cspm_get_setting('mapsettings', 'user_circle_fillColor', '#189AC9'); // @since 3.0
			$this->user_circle_fillOpacity = $this->cspm_get_setting('mapsettings', 'user_circle_fillOpacity', '0.1'); // @since 3.0
			$this->user_circle_strokeColor = $this->cspm_get_setting('mapsettings', 'user_circle_strokeColor', '#189AC9'); // @since 3.0				
			$this->user_circle_strokeOpacity = $this->cspm_get_setting('mapsettings', 'user_circle_strokeOpacity', '1'); // @since 3.0
			$this->user_circle_strokeWeight = $this->cspm_get_setting('mapsettings', 'user_circle_strokeWeight', '1'); // @since 3.0						
			$this->recenter_map = $this->cspm_get_setting('mapsettings', 'recenter_map', 'true'); // @since 3.0						
					
			/**
			 * Default map styles section */
			 
			$this->style_option = $this->cspm_get_setting('mapstylesettings', 'style_option', 'progress-map');
			$this->map_style = $this->cspm_get_setting('mapstylesettings', 'map_style', 'google-map');
			$this->js_style_array = $this->cspm_get_setting('mapstylesettings', 'js_style_array', '');
			$this->custom_style_name = $this->cspm_get_setting('mapstylesettings', 'custom_style_name', 'Custom style'); //@since 2.6.1
		
			/**
			 * Default Infobox settings
			 * @since 2.5 */
			
			$this->show_infobox = $this->cspm_get_setting('infoboxsettings', 'show_infobox', 'true');
			$this->infobox_type = $this->cspm_get_setting('infoboxsettings', 'infobox_type', 'rounded_bubble');
			$this->infobox_display_event = $this->cspm_get_setting('infoboxsettings', 'infobox_display_event', 'onload');
			$this->infobox_external_link = $this->cspm_get_setting('infoboxsettings', 'infobox_external_link', 'same_window');
			$this->remove_infobox_on_mouseout = $this->cspm_get_setting('infoboxsettings', 'remove_infobox_on_mouseout', 'false'); //@since 2.7.4
					
			/**
			 * Troubleshooting & Configs
			 * @since 2.5
			 */
			 
			$this->combine_files = $this->cspm_get_setting('troubleshooting', 'combine_files', 'combine');
			$this->custom_css = $this->cspm_get_setting('troubleshooting', 'custom_css', ''); //@since 2.6.1
			$this->remove_bootstrap = $this->cspm_get_setting('troubleshooting', 'remove_bootstrap', 'enable'); //@since 2.8.2
			$this->remove_google_fonts = $this->cspm_get_setting('troubleshooting', 'remove_google_fonts', 'enable'); //@since 2.8.2	
				
			$remove_frontend_gmaps_api = isset($this->plugin_settings['cspm_troubleshooting_remove_gmaps_api_disable_frontend'])
												? $this->plugin_settings['cspm_troubleshooting_remove_gmaps_api_disable_frontend'] : ''; //@since 2.8.5
			$remove_backend_gmaps_api = isset($this->plugin_settings['cspm_troubleshooting_remove_gmaps_api_disable_backend'])
												? $this->plugin_settings['cspm_troubleshooting_remove_gmaps_api_disable_backend'] : ''; //@since 2.8.5
					
			$this->remove_gmaps_api = array(
				$remove_frontend_gmaps_api,
				$remove_backend_gmaps_api,
			); //@since 2.8.5
			
			/**
			 * [@shared_plugin_settings] | Build an array containing the plugin settings that we can share with other classes.
			 * The idea is to use the shared settings as default settings in other classes. */
			 
			$this->shared_plugin_settings = apply_filters('cspm_shared_plugin_settings', array(
			 	
				'post_types' => $this->post_types,
				
				/**
				 * Default map settings */
			 			
				'map_center' => $this->center,			
				'initial_map_style' => $this->initial_map_style,
				'map_zoom' => $this->zoom,
				'useClustring' => $this->useClustring,
				'gridSize' => $this->gridSize,
				'mapTypeControl' => $this->mapTypeControl,
				'streetViewControl' => $this->streetViewControl,
				'scrollwheel' => $this->scrollwheel,
				'zoomControl' => $this->zoomControl,
				'zoomControlType' => $this->zoomControlType,
				'marker_icon' => $this->marker_icon,		
				'big_cluster_icon' => $this->big_cluster_icon,
				'medium_cluster_icon' => $this->medium_cluster_icon,
				'small_cluster_icon' => $this->small_cluster_icon,
				'cluster_text_color' => $this->cluster_text_color,			
				'zoom_in_icon' => $this->zoom_in_icon,	
				'zoom_in_css' => $this->zoom_in_css,	
				'zoom_out_icon' => $this->zoom_out_icon,
				'zoom_out_css' => $this->zoom_out_css,
				'defaultMarker' => $this->defaultMarker,
				'retinaSupport' => $this->retinaSupport,
				'geoIpControl' => $this->geoIpControl,		
				'markerAnimation' => $this->markerAnimation,
				'marker_anchor_point_option' => $this->marker_anchor_point_option,
				'marker_anchor_point' => $this->marker_anchor_point,				
				'map_draggable' => $this->map_draggable,			
				'max_zoom' => $this->max_zoom,
				'min_zoom' => $this->min_zoom,
				'zoom_on_doubleclick' => $this->zoom_on_doubleclick,											
				'autofit' => $this->autofit,											
				'traffic_layer' => $this->traffic_layer,
				'transit_layer' => $this->transit_layer,
				'show_user' => $this->show_user,
				'user_marker_icon' => $this->user_marker_icon,
				'user_map_zoom' => $this->user_map_zoom,
				'user_circle' => $this->user_circle,
				'user_circle_fillColor' => $this->user_circle_fillColor, //@since 3.0
				'user_circle_fillOpacity' => $this->user_circle_fillOpacity, //@since 3.0
				'user_circle_strokeColor' => $this->user_circle_strokeColor, //@since 3.0
				'user_circle_strokeOpacity' => $this->user_circle_strokeOpacity, //@since 3.0
				'user_circle_strokeWeight' => $this->user_circle_strokeWeight, //@since 3.0
				'recenter_map' => $this->recenter_map, //@since 3.0				
						
				/**
				 * Default map styles section */
				 
				'style_option' => $this->style_option,
				'map_style' => $this->map_style,
				'js_style_array' => $this->js_style_array,
				'custom_style_name' => $this->custom_style_name,
			
				/**
				 * Default Infobox settings
				 * @since 2.5 */
				
				'show_infobox' => $this->show_infobox,
				'infobox_type' => $this->infobox_type,
				'infobox_display_event' => $this->infobox_display_event,
				'infobox_external_link' => $this->infobox_external_link,
				'remove_infobox_on_mouseout' => $this->remove_infobox_on_mouseout,
						
				/**
				 * Troubleshooting & Configs
				 * @since 2.5
				 */
				 
				'outer_links_field_name' => $this->outer_links_field_name,
				'use_with_wpml' => $this->use_with_wpml,
				'combine_files' => $this->combine_files,
				'remove_bootstrap' => $this->remove_bootstrap,
				'remove_google_fonts' => $this->remove_google_fonts,
				'remove_gmaps_api' => $this->remove_gmaps_api,
				'custom_css' => $this->custom_css,
			)); 

		}

  
		static function this(){
			
			return self::$_this;
			
		}


		function cspm_hooks(){
			
			/**
			 * Load plugin textdomain.
			 * @since 2.8 */
			 
			add_action('init', array(&$this, 'cspm_load_plugin_textdomain')); 
			
			if(is_admin()){
							
				/**
				 * Include and setup our custom post type */
				
				if(file_exists($this->plugin_path . 'cpt/inc/cspm-post-type.php')){
					
					require_once( $this->plugin_path . 'cpt/inc/cspm-post-type.php' );

					if(class_exists('CspmPostType')){
						
						$CspmPostType = new CspmPostType(array(
							'object_type' => $this->object_type,
						));
						
					}

				}
				
				/**
				 * Make sure to load our metaboxes only when the current post type ...
				 * ... matches out custom post type. This will prevent JS conflicts with other plugins.
				 * @since 3.1 */
				 
				$current_post_type = $this->cspm_get_current_post_type();		
				
				if($current_post_type == $this->object_type){
					
					/**
					 * Include and setup custom metaboxes and fields */
							
					if(file_exists($this->plugin_path . 'cpt/inc/cspm-metaboxes.php')){
						
						require_once( $this->plugin_path . 'cpt/inc/cspm-metaboxes.php' );
					
						if(class_exists('CspmMetaboxes')){
							
							new CspmMetaboxes(array(
								'plugin_path' => $this->plugin_path, 
								'plugin_url' => $this->plugin_url,
								'plugin_settings' => $this->shared_plugin_settings,
								'metafield_prefix' => $this->metafield_prefix,
								'object_type' => $this->object_type,
							));				
							
						}
						
					}
					
				}
				
				/**
				 * Ajax functions */
			
				add_action('wp_ajax_cspm_regenerate_markers', array(&$this, 'cspm_regenerate_markers'));
				 
				/**
				 * Add plugin menu */
				 
				add_action('admin_menu', array(&$this, 'cspm_admin_menu'));

				/**
				 * Add "Location" meta box to "Add" custom post type area */
				 
				add_action('admin_init', array(&$this, 'cspm_meta_box'));
				add_action('save_post', array(&$this, 'cspm_insert_meta_box_fields'), 10, 2);
				
				/**
				 * Remove all metaboxes from our CPT except for the ones we built */
				 
				add_action('current_screen', array(&$this, 'cspm_remove_all_metaboxes'));
				
				/**
				 * Register new carousel image sizes */
				 			
				add_action('admin_init', array(&$this, 'cspm_add_carousel_image_sizes'));
					
				/**
				 * Add Marker/Infobox Images Size */
				 
				if(function_exists('add_image_size')){
					add_image_size('cspm-marker-thumbnail', 100, 100, true);
					add_image_size('cspm-horizontal-thumbnail-map', 204, 150, true);
					add_image_size('cspm-vertical-thumbnail-map', 204, 120, true);
					add_image_size('cspacing-horizontal-thumbnail', 204, 150, true ); //@deprecated since 3.0
					add_image_size('cspacing-vertical-thumbnail', 204, 120, true );	//@deprecated since 3.0				
				}
					
				/**
				 * Add custom links to plugin instalation area */
				 
				add_filter('plugin_row_meta', array(&$this, 'cspm_plugin_meta_links'), 10, 2);
				add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), array(&$this, 'cspm_add_plugin_action_links'));
						
				/**
				 * Alter the list of acceptable file extensions WordPress checks during media uploads
				 * @since 2.7 */
				 
				add_filter('upload_mimes', array(&$this, 'cspm_custom_upload_mimes'));

				/**
				 * Display a message in the admin to promote for Progress Map extensions
				 *
				 * @since 2.8
				 */
				 
				add_action( 'admin_notices', array(&$this, 'cspm_about_extensions') );	
				
				/**
				 * Get out if the loaded page is not our plguin settings page */
				 
				if (isset($_GET['page']) && $_GET['page'] == $this->plugin_get_var ){
		
					/**
					 * Call custom functions */
					 
					add_action('wpsf_before_settings', array(&$this, 'cspm_before_settings'));
					add_action('wpsf_after_settings', array(&$this, 'cspm_after_settings'));
			
					/**
					 * Add an optional settings validation filter (recommended) */
					 
					add_filter( $this->cspm_wpsf->cspm_get_option_group() .'_settings_validate', array(&$this, 'cspm_validate_settings') );		
			
				}
			
				/**
				 * Add a custom column on all posts list showing the coordinates of each post
				 * @since 2.6.3 */

				if($this->custom_list_columns == 'yes'){
					foreach($this->post_types as $post_type){
						if($post_type == 'page'){
							add_filter('manage_pages_columns', array(&$this, 'cspm_manage_posts_columns')); // @since 2.6.3
							add_action('manage_pages_custom_column', array(&$this, 'cspm_manage_posts_custom_column'), 10, 2); // @since 2.6.3	
						}else{
							add_filter('manage_'.$post_type.'_posts_columns', array(&$this, 'cspm_manage_posts_columns')); // @since 2.6.3
							add_action('manage_'.$post_type.'_posts_custom_column', array(&$this, 'cspm_manage_posts_custom_column'), 10, 2); // @since 2.6.3
						}
					}
				}

				/**
				 * Executed when activating the plugin in order to run any sync code needed for the latest version of the plugin 
				 * @since 2.4 */
				 
				register_activation_hook(__FILE__, array(&$this, 'cspm_sync_settings_for_latest_version'));
				
				/**
				 * Duplicate map "link", "action" & "link style"
				 * @since 3.2 */
				 				
				add_filter('post_row_actions', array(&$this, 'cspm_duplicate_map_link'), 10, 2);		
				add_action('admin_action_cspm_duplicate_map', array(&$this, 'cspm_duplicate_map'));
				add_action('admin_head', array(&$this, 'cspm_duplicate_map_link_style'));
					
			}else{
				
				/**
				 * Call .js and .css files */
				 
				add_action('wp_enqueue_scripts', array(&$this, 'cspm_register_styles'));
				add_action('wp_enqueue_scripts', array(&$this, 'cspm_register_scripts'));

			}
			
			/**
			 * Include and load the main map shortcode class */
			
			if(file_exists($this->plugin_path . 'shortcodes/cspm-main-map.php')){
				
				require_once( $this->plugin_path . 'shortcodes/cspm-main-map.php' );
				
				if(class_exists('CspmMainMap')){
					
					$CspmMainMap = new CspmMainMap(array(
						'init' => true, 
						'plugin_settings' => $this->shared_plugin_settings,
						'metafield_prefix' => $this->metafield_prefix,
						'object_type' => $this->object_type,
					));
				
					$CspmMainMap->cspm_hooks();
					
				}
					
			}
			
			/**
			 * Include and load all shortcodes/maps classes */
			
			$cspm_shortcodes_classes = array(
				'cspm_light_map' => 'shortcodes/cspm-light-map.php',
				'cspm_static_map' => 'shortcodes/cspm-static-map.php',
				'cspm_streetview_map' => 'shortcodes/cspm-streetview-map.php',
				'cspm_contact_map' => 'shortcodes/cspm-contact-map.php',
				'cspm_frontend_form' => 'shortcodes/cspm-frontend-form.php',
			);
			
				foreach($cspm_shortcodes_classes as $class_key => $class_path){
					if(file_exists($this->plugin_path . $class_path))
						require_once($this->plugin_path . $class_path);
				}

		}

		
		/**
		 * Register & Enqueue CSS files 
		 *
		 * @updated 3.0
		 */
		function cspm_register_styles(){
			
			do_action('cspm_before_register_style');
							
			$min_path = ($this->combine_files == 'seperate_minify' || $this->combine_files == "combine") ? 'min/' : '';
			$min_prefix = ($this->combine_files == 'seperate_minify' || $this->combine_files == "combine") ? '.min' : '';
			
			/**
			 * Font Style */
			
			if($this->remove_google_fonts == 'enable'){  	
				wp_register_style('cspm_font', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic&subset=latin,vietnamese,latin-ext');				
			}

			/**
			 * icheck skins */
							
			$icheck_skins = array('flat', 'futurico', 'polaris', 'line', 'minimal', 'square');
			$icheck_skin_colors = array('black', 'red', 'green', 'blue', 'aero', 'grey', 'orange', 'yellow', 'pink', 'purple');
			
			foreach($icheck_skins as $skin){

				foreach($icheck_skin_colors as $color){
				
					$color_file_name = ($color == 'black') ? $skin : $color;
					
					if(file_exists($this->plugin_path .'css/icheck/'.$skin.'/'.$color_file_name.$min_prefix.'.css'))
						wp_register_style('cspm_icheck_'.$skin.'_'.$color_file_name.'_css', $this->plugin_url .'css/icheck/'.$skin.'/'.$color_file_name.$min_prefix.'.css', array(), $this->plugin_version);
					
				}
				
			}
			
			if($this->combine_files == "combine"){
				
				$script_handle = 'cspm_combined_styles';
					
				wp_register_style($script_handle, $this->plugin_url .'css/min/cspm_combined_styles.min.css', array(), $this->plugin_version);
				
			}else{
				
				/**
				 * Bootstrap */
				
				if($this->remove_bootstrap == 'enable')	
					wp_register_style('cspm_bootstrap_css', $this->plugin_url .'css/'.$min_path.'bootstrap'.$min_prefix.'.css', array(), $this->plugin_version);
				
				/**
				 * jCarousel */
				 
				wp_register_style('cspm_carousel_css', $this->plugin_url .'css/'.$min_path.'jcarousel'.$min_prefix.'.css', array(), $this->plugin_version);
				
				/**
				 * Infobox & Carousel loaders */
				 
				wp_register_style('cspm_loading_css', $this->plugin_url .'css/'.$min_path.'loading'.$min_prefix.'.css', array(), $this->plugin_version);
				
				/**
				 * Custom Scroll bar */
				 
				wp_register_style('cspm_mCustomScrollbar_css', $this->plugin_url .'css/'.$min_path.'jquery.mCustomScrollbar'.$min_prefix.'.css', array(), $this->plugin_version);

				/**
				 * Range Slider */
				 				
				wp_register_style('cspm_rangeSlider_css', $this->plugin_url .'css/'.$min_path.'ion.rangeSlider'.$min_prefix.'.css', array(), $this->plugin_version);
				wp_register_style('cspm_rangeSlider_skin_css', $this->plugin_url .'css/'.$min_path.'ion.rangeSlider.skinFlat'.$min_prefix.'.css', array(), $this->plugin_version);
				
				/** 
				 * Progress Map styles */
				
				wp_register_style('cspm_nprogress_css', $this->plugin_url .'css/'.$min_path.'nprogress'.$min_prefix.'.css', array(), $this->plugin_version);
				
				wp_register_style('cspm_animate_css', $this->plugin_url .'css/'.$min_path.'animate'.$min_prefix.'.css', array(), $this->plugin_version);
				
				$script_handle = 'cspm_map_css';
					
				wp_register_style($script_handle, $this->plugin_url .'css/'.$min_path.'style'.$min_prefix.'.css', array(), $this->plugin_version);
								
			}
			
			do_action('cspm_after_register_style');
		
			/**
			 * Add custom header script */
			
			wp_add_inline_style($script_handle, $this->custom_css);			
			
			/**
			 * Add custom header script */
			 
			add_filter('wp_head', array(&$this, 'cspm_header_script'));
			
		}	
		
		
		/**
		 * Register & Enqueue JS files 
		 *
		 * @updated 3.0
		 */
		function cspm_register_scripts(){		
						
			if( class_exists( 'CspmMainMap' ) )
				$CspmMainMap = CspmMainMap::this();

			/**
			 * Localize the script with new data */
			 
			$wp_localize_script_args = array(
				
				'ajax_url' => admin_url('admin-ajax.php'),//get_bloginfo('url') . '/wp-admin/admin-ajax.php',
				'plugin_url' => $this->plugin_url,
			
				/**
				 * [@map_script_args] | Default settings to use for maps like "Light map", "Nearby map ext", etc...
				 * Note: we'll use the key "initial" to identify the default map settings! */
					
				'map_script_args' => array('initial' => array(
					
					/**
					 * Even empty, we need them inside AJAX functions */
					 
					'map_object_id' => '',
					'map_settings' => '',
								
					/**
					 * Map settings */
					 
					'center' => $this->center,
					'zoom' => $this->zoom,
					'scrollwheel' => $this->scrollwheel,
					'mapTypeControl' => $this->mapTypeControl,
					'streetViewControl' => $this->streetViewControl,
					'zoomControl' => $this->zoomControl,
					'zoomControlType' => $this->zoomControlType,
					'defaultMarker' => $this->defaultMarker,
					'marker_icon' => $this->marker_icon,
					'big_cluster_icon' => $this->big_cluster_icon,
					'big_cluster_size' => $CspmMainMap->cspm_get_image_size($CspmMainMap->cspm_get_image_path_from_url($this->big_cluster_icon), $this->retinaSupport),
					'medium_cluster_icon' => $this->medium_cluster_icon,
					'medium_cluster_size' => $CspmMainMap->cspm_get_image_size($CspmMainMap->cspm_get_image_path_from_url($this->medium_cluster_icon), $this->retinaSupport),
					'small_cluster_icon' => $this->small_cluster_icon,
					'small_cluster_size' => $CspmMainMap->cspm_get_image_size($CspmMainMap->cspm_get_image_path_from_url($this->small_cluster_icon), $this->retinaSupport),
					'cluster_text_color' => $this->cluster_text_color,
					'grid_size' => $this->gridSize,
					'retinaSupport' => $this->retinaSupport,
					'initial_map_style' => $this->initial_map_style,
					'markerAnimation' => $this->markerAnimation, // @since 2.5
					'marker_anchor_point_option' => $this->marker_anchor_point_option, //@since 2.6.1
					'marker_anchor_point' => $this->marker_anchor_point, //@since 2.6.1
					'map_draggable' => $this->map_draggable, //@since 2.6.3
					'min_zoom' => $this->min_zoom, //@since 2.6.3
					
					/**
					 * @max_zoom, since 2.6.3
					 * @updated 2.8 (Fix issue when min zoom is bigger than max zoom) */
					'max_zoom' => ($this->min_zoom > $this->max_zoom) ? 19 : $this->max_zoom,
					
					'zoom_on_doubleclick' => $this->zoom_on_doubleclick, //@since 2.6.3
	
					/**
					 * Geotarget
					 * @since 2.8 */
					 
					'geo' => $this->geoIpControl,
					'show_user' => $this->show_user,
					'user_marker_icon' => $this->user_marker_icon,
					'user_map_zoom' => $this->user_map_zoom,
					'user_circle' => $this->user_circle,
				
					/**
					 * Search form settings */
					 
					'before_search_address' => '', //@since 2.8, Add text before the search field value
					'after_search_address' => '', //@since 2.8, Add text after the search field value
				
				)),
				
				/**
				 * Geotarget
				 * @since 2.8 */
				 
				'geoErrorTitle' => esc_attr__('Give Maps permission to use your location!', 'cspm'),				
				'geoErrorMsg' => esc_attr__('If you can\'t center the map on your location, a couple of things might be going on. It\'s possible you denied Google Maps access to your location in the past, or your browser might have an error.', 'cspm'),				
				'geoDeprecateMsg' => esc_attr__('Browsers no longer supports obtaining the user\'s location using the HTML5 Geolocation API from pages delivered by non-secure connections. This means that the page that\'s making the Geolocation API call must be served from a secure context such as HTTPS.', 'cspm'), //@since 2.8.3
				
				/**
				 * Cluster text
				 * @since 2.8 */
				 
				'cluster_text' => esc_attr__('Click to view all markers in this area', 'cspm'),
				
				/**
				 * Map Messages
				 * @since 3.2 */
				 
				'new_marker_selected_msg' => esc_attr__('A new location has been selected and can be used to display nearby points of interest!', 'cspm'),	
				'no_marker_selected_msg' => esc_attr__('First, select a marker/location from the map!', 'cspm'),
				'circle_reached_max_msg' => esc_attr__('The circle has reached the maximum distance!', 'cspm'),
				'circle_reached_min_msg' => esc_attr__('The circle has reached the minimum distance!', 'cspm'),
				'no_route_found_msg' => esc_attr__('No {travel_mode} route could be found to your destination!', 'cspm'),
				'max_search_radius_msg' => esc_attr__('You have reached the maximum radius of search!', 'cspm'),
				'min_search_radius_msg' => esc_attr__('You have reached the minimum radius of search!', 'cspm'),
				'no_nearby_point_found' => esc_attr__('No nearby points of interest has been found!', 'cspm'),
				
			);
			
			do_action('cspm_before_register_script');
						
			/**
			 * GMaps API */
			
			if(!in_array('disable_frontend', $this->remove_gmaps_api)){ 
			
				$gmaps_api_key = (!empty($this->api_key)) ? '&key='.$this->api_key : '';
				 
				wp_register_script('cspm_google_maps_api', '//maps.google.com/maps/api/js?v=3.exp'.$gmaps_api_key.'&language='.$this->map_language.'&libraries=geometry,places', array( 'jquery' ), false, true);

			}
			
			if($this->combine_files == "combine"){
			
				wp_register_script('cspm_combined_scripts', $this->plugin_url .'js/min/cspm_combined_scripts.min.js', array( 'jquery' ), $this->plugin_version, true);
				
				$localize_script_handle = 'cspm_combined_scripts';
	
			}else{			
								
				$min_path = $this->combine_files == 'seperate_minify' ? 'min/' : '';
				$min_prefix = $this->combine_files == 'seperate_minify' ? '.min' : '';
				
				/**
				 * ScrollTo jQuery Plugin
				 * Used for the extensions, "List & Filter" & "Nearby Places"
				 * @since 2.8.6 */
				
				if(class_exists('CspmListFilter') || class_exists('CspmNearbyMap'))
					wp_register_script('cspm_scrollTo_js', $this->plugin_url .'js/'.$min_path.'jquery.scrollTo'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);
				
				/**
				 * GMap3 jQuery Plugin */
				 
				wp_register_script('cspm_gmap3_js', $this->plugin_url .'js/'.$min_path.'gmap3'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);		
				
				/**
				 * Live Query */
				 
				wp_register_script('cspm_livequery_js', $this->plugin_url .'js/'.$min_path.'jquery.livequery'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);
				
				/**
				 * Marker Clusterer */
				 
				wp_register_script('cspm_markerclusterer_js', $this->plugin_url .'js/'.$min_path.'MarkerClustererPlus'.$min_prefix.'.js', array(), $this->plugin_version, true);
				
				/**
				 * Touche Swipe */
				 
				wp_register_script('cspm_touchSwipe_js', $this->plugin_url .'js/'.$min_path.'jquery.touchSwipe'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);		
				
				/**
				 * jCarousel & jQuery Easing */
				 
				wp_register_script('cspm_jcarousel_js', $this->plugin_url .'js/'.$min_path.'jquery.jcarousel'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);
				wp_register_script('cspm_easing', $this->plugin_url .'js/'.$min_path.'jquery.easing.1.3'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);
				
				/**
				 * Custom Scroll bar & jQuery Mousewheel */
				 
				wp_register_script('cspm_mCustomScrollbar_js', $this->plugin_url .'js/'.$min_path.'jquery.mCustomScrollbar'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);		
				wp_register_script('cspm_jquery_mousewheel_js', $this->plugin_url .'js/'.$min_path.'jquery.mousewheel'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);		

				/**
				 * icheck */
				 				
				wp_register_script('cspm_icheck_js', $this->plugin_url .'js/'.$min_path.'jquery.icheck'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);
				
				/**
				 * Progress Bar loader */
				 
				wp_register_script('cspm_nprogress_js', $this->plugin_url .'js/'.$min_path.'nprogress'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);

				/**
				 * Range Slider */
				
				wp_register_script('cspm_rangeSlider_js', $this->plugin_url .'js/'.$min_path.'ion.rangeSlider'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);
				
				/**
				 * Progress Map Script */
				 
				wp_register_script('cspm_progress_map_js', $this->plugin_url .'js/'.$min_path.'progress_map'.$min_prefix.'.js', array( 'jquery' ), $this->plugin_version, true);					
				 
				$localize_script_handle = 'cspm_progress_map_js';
	
			}
			
			/**
			 * Localize the script with new data */
 
			wp_localize_script($localize_script_handle, 'progress_map_vars', $wp_localize_script_args);

			do_action('cspm_after_register_script');
			
		}		
		
		/**
		 * Print Custom CSS in the page header 
		 *
		 * @since 2.5
		 * @Upadated 2.8
		 */
		function cspm_header_script(){
			
			$header_script = '';
			
			/**
			 * Prevent $(document).ready from being fired twice */			 
			
			$header_script .= '<script type="text/javascript">var _CSPM_DONE = {}; var _CSPM_MAP_RESIZED = {}</script>';
			
			echo $header_script;
			
		}
		
		
		/**
		 * Get the value of a setting
		 *
		 * @since 2.4 
		 */
		function cspm_get_setting($section_id, $setting_id, $default_value = ''){
			
			return $this->cspm_setting_exists('cspm_'.$section_id.'_'.$setting_id.'', $this->plugin_settings, $default_value);
			
		}
		

		/**
		 * Check if array_key_exists and if empty() doesn't return false
		 * Replace the empty value with the default value if available 
		 * @empty() return false when the value is (null, 0, "0", "", 0.0, false, array())
		 *
		 * @since 2.4 
		 */
		function cspm_setting_exists($key, $array, $default = ''){
			
			$array_value = isset($array[$key]) ? $array[$key] : $default;
			
			$setting_value = empty($array_value) ? $default : $array_value;
			
			return $setting_value;
			
		}
		
		
		/**
		 * Create the "Add Location" meta box 
		 */
		function cspm_meta_box(){ 
				
			foreach($this->post_types as $post_type){
					
				add_meta_box(
					'cspm_meta_box_form',
					esc_html__('Progress Map: Add Locations', 'cspm'),
					array(&$this, 'cspm_meta_box_form'),
					$post_type,
					'advanced'
				);
				
			}

		}
		
		
		/**
		 * Create the "Add Location" form
		 * 
		 * Updated 2.8
		 */
		function cspm_meta_box_form(){
	
			global $post;
	
			wp_nonce_field($this->plugin_path, 'cspm_meta_box_form_nonce');
			
			$cspml_output = '';
			
			$cspml_output .= '<style>';
			
				$cspml_output .= 'div.cspm_latLng_container{width:48%; float:left;}';
				$cspml_output .= 'div.cspm_latLng_container:nth-child(odd){border-right:1px solid #ededed; margin-right:10px;}';
				$cspml_output .= '@media (max-width: 768px) {div.cspm_latLng_container{width:100%;}}';
				
			$cspml_output .= '</style>';		
			
			$cspml_output .= '<div style="padding:5px 0 10px 0; margin:5px 0;">';
				
				/**
				 * Address Field */
				 
				$cspml_output .= '<div class="cspm_latLng_container">';
				
					$cspml_output .= '<div class="no_address_found"></div>';
					
					$cspml_output .= '<label for="'.CSPM_ADDRESS_FIELD.'" style="font-weight:bold; padding:5px 50px 10px 0; width:97%; display:block; box-sizing:border-box;">'.esc_html__('Enter an address', 'cspm').'</label>';
						
						$cspml_output .= '<input type="text" name="'.CSPM_ADDRESS_FIELD.'" id="'.CSPM_ADDRESS_FIELD.'" value="'.get_post_meta($post->ID, CSPM_ADDRESS_FIELD, true).'" style="width:79%; margin:0 5px 5px 0; float:left; height:30px;" />';
						
						$cspml_output .= '<input type="button" class="button tagadd button-large" id="codespacing_search_address" value="'.esc_html__('Search', 'cspm').'" style="float:left;" />';
						
						$cspml_output .= '<div style="clear:both"></div>';
						
						/**
						 * Map */
						
						$cspml_output .= '<div id="location_container" style="width:97%; margin-top:5px;">'; 
							
							$cspml_output .= '<div id="codespacing_widget_map_container" style="display:block; height:350px; margin:0 auto; border:1px solid #d9d9d9;"></div>';
							
						$cspml_output .= '</div>';						
				
						/**
						 * Custom Icon Field */
						
						$custom_icon_url = get_post_meta($post->ID, CSPM_MARKER_ICON_FIELD, true);
						
						$cspml_output .= '<div style="border-top:1px solid #f5f5f5; padding:10px 0 0 0; margin:10px 0;">
							<label for="'.CSPM_MARKER_ICON_FIELD.'" style="font-weight:bold; padding:5px 50px 10px 0; width:97%; display:block; box-sizing:border-box;">'.esc_html__('Marker Icon', 'cspm').'</label>
							<input type="text" name="'.CSPM_MARKER_ICON_FIELD.'" id="'.CSPM_MARKER_ICON_FIELD.'" value="'.$custom_icon_url.'" class="regular-text">
							<input type="button" name="cspm_upload_marker_icon" id="cspm_upload_marker_icon" class="button-secondary" value="'.esc_html__('Upload', 'cspm').'">
							<img src="'.$custom_icon_url.'" height="20" style="mergin-left:10px; margin-top:5px;" />
							<div style="clear:both"></div>
							<p class="description" style="margin-top:10px;">'.esc_html__('Upload a custom marker icon for this post. This will override the default marker icon and the one selected for this post\'s category in "Marker categories settings".', 'cspm').'</p>
						</div>';			
						
						/**
						 * Checkbox */
						
						$single_post_only = get_post_meta($post->ID, CSPM_SINGLE_POST_IMG_ONLY_FIELD, true);
						
						$checked = ($single_post_only == 'yes') ? ' checked="checked"' : ''; 
						
						$cspml_output .= '<div style="border-bottom:1px solid #f5f5f5; padding-bottom:10px;">
							<input type="checkbox" name="cspm_single_post_marker_img_only" id="cspm_single_post_marker_img_only" '.$checked.'>
							<label for="cspm_single_post_marker_img_only">'.esc_html__('Use the "Marker icon" only on single post maps.', 'cspm').'</label>
						</div>';			
			
				$cspml_output .= '</div>';
				
				$cspml_output .= '<div id="codespacing_locations_latLng_container" class="cspm_latLng_container">';
					
					/**
					 * Main Lat & Lng Fields */
					 
					$cspml_output .= '<div id="codespacing_latLng_fields" style="border-bottom:1px solid #ededed; padding-bottom:10px; margin-bottom:10px;">';
						
						/**
						 * Latitude */
						 
						$cspml_output .= '<div id="codespacing_lat_field" style="float:left; margin-right:16px; width:30%;">';
						
							$cspml_output .= '<label for="'.CSPM_LATITUDE_FIELD.'" style="font-weight:bold; padding:5px 50px 10px 0; width:130px; display:block; float:left; box-sizing:border-box;">'.esc_html__('Latitude', 'cspm').'*</label>';
					
								$cspml_output .= '<input type="text" name="'.CSPM_LATITUDE_FIELD.'" id="'.CSPM_LATITUDE_FIELD.'" value="'.get_post_meta($post->ID, CSPM_LATITUDE_FIELD, true).'" style="width:100%; height:31px; margin:0;" />';
						
						$cspml_output .= '</div>';
					
						/**
						 * Longitude */
						 
						$cspml_output .= '<div id="codespacing_lng_field" style="float:left; width:30%;">';
						
							$cspml_output .= '<label for='.CSPM_LONGITUDE_FIELD.' style="font-weight:bold; padding:5px 50px 10px 0; width:130px; display:block; float:left; box-sizing:border-box;">'.esc_html__('Longitude', 'cspm').'*</label>';
				
								$cspml_output .= '<input type="text" name="'.CSPM_LONGITUDE_FIELD.'" id="'.CSPM_LONGITUDE_FIELD.'" value="'.get_post_meta($post->ID, CSPM_LONGITUDE_FIELD, true).'" style="width:100%; height:31px; margin:0;" />';
						
						$cspml_output .= '</div>';
						
						$cspml_output .= '<div style="width:30%; float:left;"><input type="button" value="'.esc_html__('Get Pinpoint', 'cspm').'" id="codespacing_copypinpoint" class="button button-primary button-large" style="width:100%; margin:33px 0 0 10px;" /></div>';
						
						$cspml_output .= '<div style="clear:both"></div>';
						
						$cspml_output .= '<small style="color:red">(*) '.esc_html__('Mandatory fields', 'cspm').'</small>';
						
						$cspml_output .= '<div style="clear:both"></div>';
						
					$cspml_output .= '</div>';								
					
					/**
					 * Secondary Lat & Lng Field */
					 
					$cspml_output .= '<div>';
						
						/**
						 * Latitudes & Longitudes */
						 
						$cspml_output .= '<label for="'.CSPM_SECONDARY_LAT_LNG_FIELD.'" style="font-weight:bold; padding:5px 50px 10px 0; display:block;">'.esc_html__('Add more locations', 'cspm').'</label>';
			
						$cspml_output .= '<textarea name="'.CSPM_SECONDARY_LAT_LNG_FIELD.'" id="'.CSPM_SECONDARY_LAT_LNG_FIELD.'" style="margin:0 0 5px 0; height:100px; width:100%;">'.get_post_meta($post->ID, CSPM_SECONDARY_LAT_LNG_FIELD, true).'</textarea>';
						
						$cspml_output .= '<input type="button" value="'.esc_html__('Add more locations', 'cspm').'" id="codespacing_secondary_copypinpoint" class="button button-primary button-large" />';
						
						$cspml_output .= '<p style="margin-bottom:10px; color:#666;">';
							
							$cspml_output .= __('This field allows you to display the same post on multiple places on the map. 
							For example, let\'s say that this post is about "McDonald\'s" and that you want to use it to show your website\'s visitors all the locations in your country/city/town...
							where they can find "McDonald\'s". So, instead of creating - for instance - 10 posts with the same content but with different coordinates, this field will allow you to share the same content with all the different locations that points to "McDonald\'s".<br />
							<br /><strong>How to use it?</strong><br /><br />
							1. Insert the coordinates of one location in the fields <strong>Latitude</strong> & <strong>Longitude</strong>.
							<br />
							2. Enter the coordinates of the remaining locations in the field <strong>"Add more locations"</strong> by dragging the marker on the map to the exact location or by entering the location\'s address in the field <strong>"Enter an address"</strong>, then, click on the button <strong>"Add more locations".</strong> <br /><br /> 
							<strong>Note:</strong> All the locations will share the same title, content, link and featured image. Each location represents a new item on the carousel!', 'cspm');
						
						$cspml_output .= '</p>';
						
						$cspml_output .= '<div style="clear:both"></div>';
						
					$cspml_output .= '</div>';
					
				$cspml_output .= '</div>';
				
				$cspml_output .= '<div style="clear:both"></div>';
					
			$cspml_output .= '</div>';
	
			$post_lat = get_post_meta($post->ID, CSPM_LATITUDE_FIELD, true);
			$post_lng = get_post_meta($post->ID, CSPM_LONGITUDE_FIELD, true);

			if(empty($post_lat) && empty($post_lng)){
				
				$explode_map_center = explode(',', $this->center);
				$post_lat = isset($explode_map_center[0]) ? $explode_map_center[0] : 51.53096;
				$post_lng = isset($explode_map_center[1]) ? $explode_map_center[1] : -0.121064;
				
			}
								
			?>
				
			<script type="text/javascript">

			jQuery(document).ready(function($){
											
				var map;
				
				var error_address1 = 'We could not understand the location ';
				var error_address2 = '<br /><u>Suggestions</u>:';
					error_address2 += '<ul>'
						error_address2 += '<li>- Make sure all street and city names are spelled correctly.</li>';
						error_address2 += '<li>- Make sure your address includes a city and state.</li>';
						error_address2 += '<li>- Try entering a zip code.</li>';
					error_address2 += '</ul>';
	
				google.maps.visualRefresh = true;
				
				map = new GMaps({
					el: '#codespacing_widget_map_container',
					lat: <?php echo $post_lat; ?>,
					lng: <?php echo $post_lng; ?>,
					zoom: 9,
				});
	
				map.addMarker({
					lat: <?php echo $post_lat; ?>,
					lng: <?php echo $post_lng; ?>,
					infoWindow: {
						content : '<p style="height:50px; width:150px">Main: <?php echo $post_lat; ?>,<?php echo $post_lng; ?></p>'
					},				
					draggable: true,
					title: 'Main: <?php echo $post_lat; ?>,<?php echo $post_lng; ?>',
				});
										
				<?php 
													
				// Get lat and lng data
				$secondary_latlng = str_replace(' ', '', get_post_meta($post->ID, CSPM_SECONDARY_LAT_LNG_FIELD, true));
				
				// Add secondary coordinates
				if(!empty($secondary_latlng)){
					
					$lats_lngs = explode(']', $secondary_latlng);
					
					foreach($lats_lngs as $single_coordinate){
					
						$strip_coordinates = str_replace(array('[', ']', ' '), '', $single_coordinate);
						
						$coordinates = explode(',', $strip_coordinates);
						
						if(isset($coordinates[0]) && !empty($coordinates[0]) && isset($coordinates[1]) && !empty($coordinates[1])){
							
							$lat = $coordinates[0];
							$lng = $coordinates[1];
						
							?>
	
							map.addMarker({
								lat: <?php echo $lat; ?>,
								lng: <?php echo $lng; ?>,
								infoWindow: {
									content : '<p style="height:50px; width:150px">Secondary: <?php echo $lat; ?>,<?php echo $lng; ?></p>'
								},
								draggable: false,
								title: 'Secondary: <?php echo $lat; ?>,<?php echo $lng; ?>',
							});
							
							<?php
							
						}
						
					}
					
				}
				
				?>
	
				$('input#codespacing_search_address').livequery('click', function(e){
					e.preventDefault();
					GMaps.geocode({
					  address: $('input#<?php echo CSPM_ADDRESS_FIELD ?>').val().trim(),
					  callback: function(results, status){
						if(status=='OK'){						
						  $('.no_address_found').empty();						 
						  var latlng = results[0].geometry.location;
						  map.removeMarkers();
						  map.setCenter(latlng.lat(), latlng.lng());
						  map.addMarker({
							lat: latlng.lat(),
							lng: latlng.lng(),
							draggable: true,
						  });
						}else $('.no_address_found').html(error_address1 + '<strong>' + $('input#<?php echo CSPM_ADDRESS_FIELD ?>').val().trim() + '</strong>' + error_address2);
					  }
					});
					return false;
				});
							  
				$('input#<?php echo CSPM_ADDRESS_FIELD ?>').keypress(function(e){
					if (e.keyCode == 13) {
						e.preventDefault();
						GMaps.geocode({
						  address: $(this).val().trim(),
						  callback: function(results, status){
							if(status=='OK'){	
							  $('.no_address_found').empty();
							  var latlng = results[0].geometry.location;
							  map.removeMarkers();
							  map.setCenter(latlng.lat(), latlng.lng());
							  map.addMarker({
								lat: latlng.lat(),
								lng: latlng.lng(),
								draggable: true,
							  });
							}else $('.no_address_found').html(error_address1 + '<strong>' + $('input#<?php echo CSPM_ADDRESS_FIELD ?>').val().trim() + '</strong>' + error_address2);
						  }
						});
						return false;
					}		
				});
				  
				$('input#codespacing_copypinpoint').livequery('click', function(e){
					e.preventDefault();
					$("input#<?php echo CSPM_LATITUDE_FIELD ?>").val(map.markers[0].getPosition().lat());
					$("input#<?php echo CSPM_LONGITUDE_FIELD ?>").val(map.markers[0].getPosition().lng());
				});
				
				$('#codespacing_secondary_copypinpoint').livequery('click', function(e){
					e.preventDefault();
					var old_value = $("#<?php echo CSPM_SECONDARY_LAT_LNG_FIELD ?>").val();
					$("#<?php echo CSPM_SECONDARY_LAT_LNG_FIELD ?>").val(old_value + '[' + map.markers[0].getPosition().lat() + ',' + map.markers[0].getPosition().lng() + ']');
				});
				
				/**
				 * Add Custom Upload for the field "Marker Icon"
				 * @since 2.8 */
				
				$('#cspm_upload_marker_icon').livequery('click', function(e) {
					e.preventDefault();
					var image = wp.media({ 
						title: 'Upload Image',
						// mutiple: true if you want to upload multiple files at once
						multiple: false
					}).open()
					.on('select', function(e){
						// This will return the selected image from the Media Uploader, the result is an object
						var uploaded_image = image.state().get('selection').first();
						// We convert uploaded_image to a JSON object to make accessing it easier
						// Output to the console uploaded_image
						console.log(uploaded_image);
						var image_url = uploaded_image.toJSON().url;
						// Let's assign the url value to the input field
						$('#<?php echo CSPM_MARKER_ICON_FIELD; ?>').val(image_url);
					});
				});

				/**
				 * Add support for the Autocomplete for the address field
				 * @since 2.8 */
				
				var input = document.getElementById('<?php echo CSPM_ADDRESS_FIELD ?>');
				var autocomplete = new google.maps.places.Autocomplete(input);
											
			});
			
			</script>
				
			<?php 
			
			echo $cspml_output;
				
		}
		
		
		/**
		 * Save the "Add Location" form (lat, lng)
		 *
		 * @updated 2.8
		 */		 
		function cspm_insert_meta_box_fields($post_id, $post){
										
			/**
			 * security verification */
			 
			if(!isset($_POST['cspm_meta_box_form_nonce']) || !wp_verify_nonce($_POST['cspm_meta_box_form_nonce'], $this->plugin_path))
				return '';
			
			/**
			 * pointless if $_POST is empty (this happens on bulk edit) */
			 
			if(empty($_POST))
				return $post_id;
		
			/**
			 * verify quick edit nonce */
			 
			if ( isset( $_POST[ '_inline_edit' ] ) && ! wp_verify_nonce( $_POST[ '_inline_edit' ], 'inlineeditnonce' ) )
				return $post_id;
		
			/**
			 * don't save for autosave */
			 
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;
				
			$post_type = $post->post_type;
			$markers_object = get_option('cspm_markers_array');
			$post_markers_object = array();
			
			/**
			 * Save the address Field */
			 
			if(isset($_POST[CSPM_ADDRESS_FIELD]))
				update_post_meta($post->ID, CSPM_ADDRESS_FIELD, $_POST[CSPM_ADDRESS_FIELD]);	
			
			/**
			 * Save the marker icon Field */
			 
			if(isset($_POST[CSPM_MARKER_ICON_FIELD]))
				update_post_meta($post->ID, CSPM_MARKER_ICON_FIELD, $_POST[CSPM_MARKER_ICON_FIELD]);
			
			
			/**
			 * Save the marker icon display option */
			 
			if(isset($_POST[CSPM_SINGLE_POST_IMG_ONLY_FIELD]) && !empty($_POST[CSPM_SINGLE_POST_IMG_ONLY_FIELD]))
				update_post_meta($post->ID, CSPM_SINGLE_POST_IMG_ONLY_FIELD, 'yes');
			else update_post_meta($post->ID, CSPM_SINGLE_POST_IMG_ONLY_FIELD, 'no');
			
			/**
			 * Save the Lat & Lng Fields */
			 	
			if(isset($_POST[CSPM_LATITUDE_FIELD]) && isset($_POST[CSPM_LONGITUDE_FIELD])){								  
				
				update_post_meta($post->ID, CSPM_LATITUDE_FIELD, $_POST[CSPM_LATITUDE_FIELD]);
				update_post_meta($post->ID, CSPM_LONGITUDE_FIELD, $_POST[CSPM_LONGITUDE_FIELD]);
				
				$post_taxonomy_terms = array();
				
				$post_taxonomies = get_object_taxonomies($post, 'names');	
				
				foreach($post_taxonomies as $taxonomy_name){
					
					$post_taxonomy_terms[$taxonomy_name] = wp_get_post_terms($post->ID, $taxonomy_name, array("fields" => "ids"));
				
				}
	
				$post_markers_object = array('lat' => $_POST[CSPM_LATITUDE_FIELD],
											 'lng' => $_POST[CSPM_LONGITUDE_FIELD],
											 'post_id' => $post->ID,
											 'post_tax_terms' => $post_taxonomy_terms,
											 'is_child' => 'no',
											 'child_markers' => array()
											 );																	 
				
				/**
				 * Secondary latLng */
				 
				if(isset($_POST[CSPM_SECONDARY_LAT_LNG_FIELD])){
					
					$child_markers = array();
														
					update_post_meta($post->ID, CSPM_SECONDARY_LAT_LNG_FIELD, $_POST[CSPM_SECONDARY_LAT_LNG_FIELD]); 
	
					$j = 0;
									
					$lats_lngs = explode(']', $_POST[CSPM_SECONDARY_LAT_LNG_FIELD]);	
							
					foreach($lats_lngs as $single_coordinate){
					
						$strip_coordinates = str_replace(array('[', ']', ' '), '', $single_coordinate);
						
						$coordinates = explode(',', $strip_coordinates);
						
						if(isset($coordinates[0]) && isset($coordinates[1]) && !empty($coordinates[0]) && !empty($coordinates[1])){
							
							$lat = $coordinates[0];
							$lng = $coordinates[1];
							
							$child_markers[] = array('lat' => $lat,
														'lng' => $lng,
														'post_id' => $post->ID,
														'post_tax_terms' => $post_taxonomy_terms,
														'is_child' => 'yes_'.$j
														);
							
							$lat = '';
							$lng = '';
							$j++;
						
						} 
						
						$post_markers_object['child_markers'] = $child_markers;
						
					}
				
				}
				
				$markers_object[$post_type]['post_id_'.$post->ID] = $post_markers_object;
						
				update_option('cspm_markers_array', $markers_object);
				
			}
			
		}
		
			
		/**
		 * Add the Laitude & the Longitude custom columns to the post type list
		 *
		 * @since 2.6.3 
		 */
		function cspm_manage_posts_columns($columns){
			
			$columns['pm_coordinates'] = esc_html__('PM. Coordinates', 'cspm');
				
			return $columns;
			
		}
		
		
		/**
		 * fill our Latitude & Longitude columns with data
		 *
		 * @since 2.6.3 
		 */		 
		function cspm_manage_posts_custom_column( $column_name, $post_id ){
			
			switch( $column_name ) {

				case 'pm_coordinates':
					$latitude = get_post_meta( $post_id, CSPM_LATITUDE_FIELD, true );
					$longitude = get_post_meta( $post_id, CSPM_LONGITUDE_FIELD, true );
					if(!empty($latitude) && !empty($longitude))
						echo '<div id="pm-coordinates-'.$post_id.'">'.$latitude.', '.$longitude.'</div>';
					else echo 'None';
				break;
		
			}
			
		}
		
		
		/** 
		 * This will make sure only our Metaboxes will be displayed in our Custom Post Type
		 *
		 * @since 3.0
		 */
		function cspm_remove_all_metaboxes(){
			
			$type = $this->object_type;
			$metafield_prefix = $this->metafield_prefix;

			/**
			 * Note: Regarding the timing issue, best place to reset the variable [@wp_meta_boxes] is just before it is used. 
			 * Metaboxes are printed via "do_meta_boxes()" function and inside it there are no hooks, however it contains ...
			 * ... "get_user_option( "meta-box-order_$page" )" ...
			 * ... and "get_user_option()" fires the filter "get_user_option_{$option}" so we can use it to perform our cleaning. */

			add_filter('get_user_option_meta-box-order_'.$type, function() use($type, $metafield_prefix){
			  
				global $wp_meta_boxes;
				
				if(get_current_screen()->id === $type){

					/**
					 * Publish Metabox */
					 
					$publish_metabox = $wp_meta_boxes[$type]['side']['core']['submitdiv'];
					
					/**
					 * Author Metabox */
					 
					$author_metabox = $wp_meta_boxes[$type]['normal']['core']['authordiv'];
					
					/**
					 * Progress Map Metaboxes */
					
					$cspm_normal_core_metaboxes_arrays = array();
					
						$cspm_normal_core_metaboxes = apply_filters('cspm_normal_high_metaboxes', array(
							$metafield_prefix.'_pm_cpt_metabox',
							$metafield_prefix.'_pm_metabox',
						));
						
						foreach($cspm_normal_core_metaboxes as $cspm_metabox){
							if(isset($wp_meta_boxes[$type]['normal']['high'][$cspm_metabox]))
								$cspm_normal_core_metaboxes_arrays[] = $wp_meta_boxes[$type]['normal']['high'][$cspm_metabox];
						}
						
					$cspm_side_low_metaboxes_arrays = array();
					
						$cspm_side_low_metaboxes = apply_filters('cspm_side_low_metaboxes', array(
							$metafield_prefix.'_pm_shortcode_widget'
						));
						
						foreach($cspm_side_low_metaboxes as $cspm_metabox){
							if(isset($wp_meta_boxes[$type]['side']['low'][$cspm_metabox]))
								$cspm_side_low_metaboxes_arrays[] = $wp_meta_boxes[$type]['side']['low'][$cspm_metabox];
						}
						
					/**
					 * Edit [@wp_meta_boxes] */
					 
					$wp_meta_boxes[$type] = array(
						'advanced' => array(),
						'side' => array(
							'core' => array(
								'submitdiv' => $publish_metabox
							),
							'low' => $cspm_side_low_metaboxes_arrays,
						),
						'normal' => array(
							'core' => array(
								'authordiv' => $author_metabox
							),
							'high' => $cspm_normal_core_metaboxes_arrays,
						),
					);
					
					return array();
					
				}
			
				/**
				 * [PHP_INT_MAX] is the largest integer supported in this build of PHP. Usually int(2147483647. Available since PHP 5.0.5 */
				
			}, PHP_INT_MAX );
		  
		} 

						
		/**
		 * Add the plugin in the administration menu 
		 */
		function cspm_admin_menu(){	
			
			/**
			 * Plugin settings menu */
			 
			add_menu_page( __( 'Progress map', 'cspm' ), __( 'Progress map', 'cspm' ), 'manage_options', $this->plugin_get_var, array(&$this, 'cspm_settings_page'), $this->plugin_url.'/settings/img/menu-icon.png', '99.2' );
			
			/**
			 * All maps submenu */
			 	
			add_submenu_page( $this->plugin_get_var, __( 'All maps', 'cspm' ), __( 'All maps', 'cspm' ), 'manage_options', 'edit.php?post_type='.$this->object_type, NULL );
			
			/**
			 * Add new map submenu */
			 
			add_submenu_page( $this->plugin_get_var, __( 'Add new map', 'cspm' ), __( 'Add new map', 'cspm' ), 'manage_options', 'post-new.php?post_type='.$this->object_type, NULL );
				
		}
		
		/**
		 * Load plugin text domain
		 *
		 * @since 2.8
		 */
		function cspm_load_plugin_textdomain(){
			
			/**
			 * To translate the plugin, create a new folder in "wp-content/languages" ...
			 * ... and name it "cs-progress-map". Inside "cs-progress-map", paste your .mo & . po files.
			 * The plugin will detect the language of your website and display the appropriate language. */
			 
			$domain = 'cspm';
			
			$locale = apply_filters('plugin_locale', get_locale(), $domain);
		
			load_textdomain($domain, WP_LANG_DIR.'/cs-progress-map/'.$domain.'-'.$locale.'.mo');
	
			load_plugin_textdomain($domain, FALSE, $this->plugin_path.'languages/');
			
		}


		/**
		 * This will display the plugin settings form 
		 */
		function cspm_settings_page(){
								
			/**
			 * Display the plugin settings form */
			 
			echo '<div class="wrap">';
				$this->cspm_wpsf->cspm_settings(); 
			echo '</div>';					
					
		}
		
		
		function cspm_validate_settings($input){	    
		
			// Do your settings validation here
			// Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
			return $input;
			
		}	
		
				
		/**
		 * Add settings link to plugin instalation area 
		 */
		function cspm_add_plugin_action_links($links){
		 
			return array_merge(
				array(
					'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=cs_progress_map_plugin">Settings</a>'
				),
				$links
			);
		 
		}	
		
	
		/**
		 * Add plugin site link to plugin instalation area 
		 */
		function cspm_plugin_meta_links($links, $file){
		 
			$plugin = plugin_basename(__FILE__);
		 
			/**
			 * create the link */
			 
			if ( $file == $plugin ) {
				return array_merge(
					$links,
					array(
						'get_start' => '<a target="_blank" href="http://codespacing.com/wordpress-plugins/progress-map-wordpress-plugin/getting-started-with-progress-map/">'.esc_html__('Getting started', 'cspm').'</a>',
						'documentation' => '<a target="_blank" href="http://codespacing.com/wordpress-plugins/progress-map-wordpress-plugin/progress-map-documentation/">'.esc_html__('Documentation', 'cspm').'</a>'
					)
				);
			}
			
			return $links;
		 
		}


		/**
		 * Alter the list of acceptable file extensions WordPress checks during media uploads 
		 *
		 * @since 2.7 
		 */
		function cspm_custom_upload_mimes($existing_mimes = array()){
			
			$existing_mimes['kml'] = 'application/vnd.google-earth.kml+xml';			
			$existing_mimes['kmz'] = 'application/vnd.google-earth.kmz'; 
			
			return $existing_mimes;
			
		}
		
		
		/**
		 * Display messages in the admin to inform changes or infos about "Progress Map" extensions
		 *
		 * @since 3.0
		 */
		function cspm_about_extensions(){
			
			/**
			 * Make sure to use "List & Filter" 2.0 or upper */
			 	
			if (class_exists('ProgressMapList')){
				
				$ProgressMapList = ProgressMapList::this();
				
				$reflect = new ReflectionClass($ProgressMapList);
				
				$plugin_version = $reflect->getProperty('plugin_version');
				
				if(($plugin_version->isPublic() && $ProgressMapList->plugin_version < 2.0) || !$plugin_version->isPublic()){
				
					echo '<div class="update-nag"><p>';
						_e( '<strong><em><u>"Progress Map v'.$this->plugin_version.'"</u></em> requires that you upgrade the extension <em><u>"List & Filter"</u></em> to the version 2.0 or upper!</strong>', 'cspm' );
					echo '</p></div>';
			
				}
			
			}
	
			/**
			 * Make sure to use "Nearby Places" 2.0 or upper */
			 	
			if (class_exists('CspmNearbyMap')){
				
				$CspmNearbyMap = CspmNearbyMap::this();
				
				$reflect = new ReflectionClass($CspmNearbyMap);
				
				$plugin_version = $reflect->getProperty('plugin_version');
				
				if(($plugin_version->isPublic() && $CspmNearbyMap->plugin_version < 2.0) || !$plugin_version->isPublic()){
				
					echo '<div class="update-nag"><p>';
						_e( '<strong><em><u>"Progress Map v'.$this->plugin_version.'"</u></em> requires that you upgrade the extension <em><u>"Nearby Places"</u></em> to the version 2.0 or upper!</strong>', 'cspm' );
					echo '</p></div>';
			
				}
			
			}
	
		}


		function cspm_before_settings(){	
	
			global $wpsf_settings;
								
			$sections = array();
			
			echo '<div class="codespacing_container" style="padding:0px; margin-top:30px; height:auto; width:800px; position:relative; box-shadow:rgba(0,0,0,.298039) 0 1px 2px -1px">';
				
				echo '<div class="cspm_admin_square_loader"></div>';
				
				echo '<div class="codespacing_header"><img src="'.$this->plugin_url.'settings/img/progress-map.png" /></div>';
				
				echo '<div class="codespacing_menu_container" style="width:auto; float:left; height:auto;">';
					
					echo '<ul class="codespacing_menu">';
						
						if(!empty($wpsf_settings)){
							
							usort($wpsf_settings, array(&$this->cspm_wpsf, 'cspm_sort_array'));
							
							$first_section = $wpsf_settings[0]['section_id'];
							
							foreach($wpsf_settings as $section){
								
								if(isset($section['section_id']) && isset($section['section_title'])){
									
									echo '<li class="codespacing_li" id='.$section['section_id'].'>'.$section['section_title'].'</li>';
									
									$sections[$section['section_id']] = $section['section_title'];								
									
								}
								
							}
								
						}
					
					echo '</ul>';
					
				echo '</div>';
				 
				echo '<div style="width:539px; height:auto; min-height:570px; padding:30px; float:left; border-left: 1px solid #e8ebec; border-top:0px solid #008fed; background:#f7f8f8 url('.$this->plugin_url.'settings/img/bg.png) repeat;">';	
				
		}
		
		function cspm_after_settings(){
				
				echo '<div class="cspm_admin_btm_square_loader"></div>';
				
				echo '</div>';
				
				echo '<div style="clear:both"></div>';
				
			echo '</div>';	
			
			echo '<div class="codespacing_rates_fotter"><a target="_blank" href="http://codecanyon.net/item/progress-map-wordpress-plugin/5581719"><img src="'.$this->plugin_url.'settings/img/rates.jpg" /></a></div>';
			
			if(!class_exists('ProgressMapList') || !class_exists('CspmNearbyMap'))
			
			echo '<div><h3>Extend "Progress Map" with these awesome and powerful add-ons:</h3></div>';
			
			/**
			 * Progress Map List & Filter Ban */
			
			if(!class_exists('ProgressMapList'))
				echo '<div style="float:left; margin-right:20px;"><a target="_blank" href="http://codecanyon.net/item/progress-map-list-filter-wordpress-plugin/16134134?ref=codespacing"><img src="'.$this->plugin_url.'settings/img/list-and-filter-thumb.jpg" /></a></div>';
			
			/**
			 * Nearby Places Ban */
			
			if(!class_exists('CspmNearbyMap'))
				echo '<div style="float:left; margin-right:20px;"><a target="_blank" href="http://codecanyon.net/item/nearby-places-wordpress-plugin/15067875?ref=codespacing"><img src="'.$this->plugin_url.'settings/img/nearby-places-thumb.png" /></a></div>';
			
			echo '<div style="clear:both;"></div>';
			
			echo '<div class="codespacing_copyright">&copy; All rights reserved CodeSpacing. Progress Map '.$this->plugin_version.'</div>';
			
			echo '<div class="codespacing_copyright">&copy; <a target="_blank" href="https://www.freevectormaps.com/world-maps/WRLD-EPS-01-0002?ref=atr">Map of World with Regions - Single Color</a> by <a target="_blank" href="https://www.freevectormaps.com/?ref=atr">FreeVectorMaps.com</a></div>';

		}
		
				
		/**
		 * Register new carousel image sizes
		 *
		 * @since 3.0 
		 */				 			
		function cspm_add_carousel_image_sizes(){
			
			if(function_exists('add_image_size')){
					
				$args = array( 'post_type' => $this->object_type);
				
				$loop = new WP_Query( $args );
				
				while ( $loop->have_posts() ) : $loop->the_post();
					
					$object_id = get_the_id();
					
					$horizontal_image_size = explode(',', get_post_meta( $object_id, $this->metafield_prefix.'_horizontal_image_size', true ));
							
						$horizontal_image_width = isset($horizontal_image_size[0]) ? $horizontal_image_size[0] : 204;
						$horizontal_image_height = isset($horizontal_image_size[1]) ? $horizontal_image_size[1] : 150; 
		
						add_image_size('cspm-horizontal-thumbnail-map'.$object_id, $horizontal_image_width, $horizontal_image_height, true);
					
					$vertical_image_size = explode(',', get_post_meta( $object_id, $this->metafield_prefix.'_vertical_image_size', true ));
							
						$vertical_image_width = isset($vertical_image_size[0]) ? $vertical_image_size[0] : 204;
						$vertical_image_height = isset($vertical_image_size[1]) ? $vertical_image_size[1] : 120; 
					
						add_image_size('cspm-vertical-thumbnail-map'.$object_id, $vertical_image_width, $vertical_image_height, true);
	
				endwhile;
				
			}

		}
		
		
		/**
		 * Returns an element's ID in the current language or in another specified language.
		 *
		 * @since 2.5
		 * @updated 2.8.4
		 */
		function cspm_wpml_object_id($ID, $post_tag, $orginal_val = true, $lang_code = "", $use_with_wpml = "no"){
			
			if(!empty($lang_code))
				$lang_code = apply_filters('wpml_current_language', NULL);
			
			return ($use_with_wpml == 'yes') ? apply_filters('wpml_object_id', $ID, $post_tag, $orginal_val, $lang_code) : $ID;
			
		}
		
		
		/**
		 * Get the Default language of the website
		 *
		 * @since 2.5
		 * @updated 2.8.4
		 */	
		function cspm_wpml_default_lang($use_with_wpml = "no"){
			
			return ($use_with_wpml == 'yes') ? apply_filters('wpml_default_language', NULL ) : '';	
			
		}
		
		
		/**
		 * Register strings for WPML 
		 *
		 * @since 2.5
		 * @updated 2.8.4
		 */
		function cspm_wpml_register_string($name, $value, $use_with_wpml = "no"){
				
			if($use_with_wpml == 'yes' && !empty($name) && !empty($value))
				do_action('wpml_register_single_string', 'Progress map', $name, $value);
			
		}
		
		
		/**
		 * Get registered string from WPML DBB when displaying
		 *
		 * @since 2.5
		 * @updated 2.8.4
		 */
		function cspm_wpml_get_string($name, $value, $use_with_wpml = "no"){
		
			if($use_with_wpml == 'yes' && !empty($name) && !empty($value))
				return apply_filters('wpml_translate_single_string', $value, 'Progress map', $name);
			else return $value;
			
		}


		/**
		 * AJAX function
		 * Get all posts and create a JSON array of markers base on the ...
		 * ... custom fields latitude & longitude + Secondary lat & lng
		 *
		 * @since 2.5
		 */
		function cspm_regenerate_markers($is_ajax = true){
						
			if( class_exists( 'CspmMainMap' ) )
				$CspmMainMap = CspmMainMap::this();
			
			$post_types = $this->post_types;
			
			$meta_values = array(CSPM_LATITUDE_FIELD, CSPM_LONGITUDE_FIELD, CSPM_SECONDARY_LAT_LNG_FIELD);
			
			if(count($post_types) > 0){

				$post_types_markers = array();
				
				/**
				 * Loop throught the post types */
				 
				foreach($post_types as $post_type){
					
					$post_types_markers[$post_type] = array();
					
					/**
					 * Get all the values of the Latitude/Longitude/Secondary coordinates ...
					 * ... where each row in the array contains the value of the custom field and ...
					 * ... the post id related to */
					 
					foreach($meta_values as $meta_value)
						$post_types_markers[$post_type][$meta_value] = $CspmMainMap->cspm_get_meta_values($meta_value, $post_type);
									
					$post_types_markers[$post_type] = array_merge_recursive(
						$post_types_markers[$post_type][CSPM_LATITUDE_FIELD], 
						$post_types_markers[$post_type][CSPM_LONGITUDE_FIELD],
						$post_types_markers[$post_type][CSPM_SECONDARY_LAT_LNG_FIELD]
					);								
																	   
				}
			
				global $wpdb;
				
				$markers_object = $post_taxonomy_terms = array();
				
				/**
				 * Create the map markers object */
				 
				foreach($post_types_markers as $post_type => $posts_and_coordinates){
					
					$i = $j = 0;						
					
					/**
					 * Get post type taxonomies */
					 
					$post_taxonomies = (array) get_object_taxonomies($post_type, 'names');
						if(($key = array_search('post_format', $post_taxonomies)) !== false) {
							unset($post_taxonomies[$key]);
						}
						
					/**
					 * Implode taxonomies to use them in the Mysql IN clause */
					 
					$taxonomies = "'" . implode("', '", $post_taxonomies) . "'";
					
					/**
					 * Directly querying the database is normally frowned upon, but all ...
					 * ... of the API functions will return the full post objects which will
					 * ... suck up lots of memory. This is best, just not as future proof */
					 
					$query = "SELECT t.term_id, tt.taxonomy, tr.object_id FROM $wpdb->terms AS t 
								INNER JOIN $wpdb->term_taxonomy AS tt 
									ON tt.term_id = t.term_id 
								INNER JOIN $wpdb->term_relationships AS tr 
									ON tr.term_taxonomy_id = tt.term_taxonomy_id 
								WHERE tt.taxonomy IN ($taxonomies)";
					
					/**
					 * Run the query. This will get an array of all terms where each term ...
					 * ... is listed with the taxonomy name and the post id */
					 
					$taxonomy_terms_and_posts = $wpdb->get_results( $query, ARRAY_A );
			
					/**
					 * Loop through the terms and order them in a way, the array will have the post_id as key ...
					 * ... inside that array, there will be another array with the key == taxonomy name ...
					 * ... inside that last array, there will be all the terms of a post */
					 
					foreach($taxonomy_terms_and_posts as $term)							
						$post_taxonomy_terms[$term['object_id']][$term['taxonomy']][] = $term['term_id'];
					
					foreach($posts_and_coordinates as $post_id => $post_coordinates){						
						
						if(isset($post_coordinates[CSPM_LATITUDE_FIELD]) && isset($post_coordinates[CSPM_LONGITUDE_FIELD])){
							
							$post_id = str_replace('post_id_', '', $post_id);							
							
							/**
							 * If a taxonomy is not set in the $post_taxonomy_terms array ...
							 * ... it means that the post has no terms available for that taxonomy ...
							 * ... but we still need to create an empty array for that taxonomy in order ...
							 * ... to use it with faceted search */
							 
							foreach($post_taxonomies as $taxonomy_name){
								
								/**
								 * Extend the $post_taxonomy_terms array with an empty array of the not existing taxonomy */
								 
								if(!isset($post_taxonomy_terms[$post_id][$taxonomy_name]))
									$post_taxonomy_terms[$post_id][$taxonomy_name] = array(); 
							
							}
							
							$markers_object[$post_type]['post_id_'.$post_id] = array(
								'lat' => $post_coordinates[CSPM_LATITUDE_FIELD],
								'lng' => $post_coordinates[CSPM_LONGITUDE_FIELD],
								'post_id' => $post_id,
								'post_tax_terms' => $post_taxonomy_terms[$post_id],
								'is_child' => 'no',
								'child_markers' => array()
							);
							
							$i++;
							
							/**
							 * Sencondary latLng */
							 
							if(isset($post_coordinates[CSPM_SECONDARY_LAT_LNG_FIELD]) && !empty($post_coordinates[CSPM_SECONDARY_LAT_LNG_FIELD])){
								
								$child_markers = array();
								
								$lats_lngs = explode(']', $post_coordinates[CSPM_SECONDARY_LAT_LNG_FIELD]);	
										
								foreach($lats_lngs as $single_coordinate){
								
									$strip_coordinates = str_replace(array('[', ']', ' '), '', $single_coordinate);
									
									$coordinates = explode(',', $strip_coordinates);
									
									if(isset($coordinates[0]) && isset($coordinates[1]) && !empty($coordinates[0]) && !empty($coordinates[1])){
										
										$lat = $coordinates[0];
										$lng = $coordinates[1];
										
										$child_markers[] = array(
											'lat' => $lat,
											'lng' => $lng,
											'post_id' => $post_id,
											'post_tax_terms' => $post_taxonomy_terms,
											'is_child' => 'yes_'.$j
										);
																														
										$lat = '';
										$lng = '';
										$j++;
									
									} 
									
									$i++;
									
								}
								
								$markers_object[$post_type]['post_id_'.$post_id]['child_markers'] = $child_markers;
							
							}								
																																					
						}
						
					}
					
				}
														
				/**
				 * Update settings */
				 
				if(count($markers_object) > 0){
					
					update_option('cspm_markers_array', $markers_object);
										
				}
				
			}
			
			if($is_ajax) die();
			
		}
		
		
		/**
		 * Run some settings updates to sync. with the latest version
		 *
		 * @since 2.4 
		 * @updated 3.0
		 * @updated 3.1 [check if it's plugin update or new installation]
		 * @updated 3.2 [fixed issue with "Post in" & "Post not in" + Marker categories images not been imported]
		 */
		function cspm_sync_settings_for_latest_version(){
				
			$opt_group = preg_replace("/[^a-z0-9]+/i", "", basename($this->plugin_path .'settings/cspm.php', '.php'));

			if($this->plugin_version >= 3.0){
				
				/**
				 * It's based on this options that we'll define if the below code has been executed before or not.
				 * The option "cspm_settings_v2" is a backup option for the settings of "Progress Map v2.+"
				 * 
				 * @udpated 3.1 --------
				 * 
				 * This will first check if it's a plugin update or a new installation by looking for the option "cspm_settings" ...
				 * ... then it'll look for the backup option "cspm_settings_v2" to define if we'll excute this code or not.
				 */
				 
				if(get_option($opt_group.'_settings') && !get_option($opt_group.'_settings_v2')){
					
					/**
					 * Get "PM. v2.+" settings */
					 
					$cspm_v2_options = get_option($opt_group.'_settings');
					
					/**
					 * Save/Backup "PM. v2.+" settings just in case */
						
					update_option($opt_group.'_settings_v2', $cspm_v2_options);
				
					/**
					 * Post types */
					
						/**
						 * Get post types selected in the v2.+ fields "Query settings => Main content type" ...
						 * ... & "Query settings => Secondary content types" */
						
						$main_post_type = $this->cspm_setting_exists('cspm_generalsettings_post_type', $cspm_v2_options, 'post');
						$secondary_post_types = $this->cspm_setting_exists('cspm_generalsettings_secondary_post_type', $cspm_v2_options, array());
						
						/**
						 * Save all post types in the new v3.0 field "Plugin settings => Post types" */
						
						$cspm_v2_options['cspm_pluginsettings_post_types'] = array_merge((array) $main_post_type, $secondary_post_types);
						
						/**
						 * Update v2.+ options.
						 * Now, in this step and with this update, we can concider these settings as compatible with "PM v3.0" */
						 
						update_option($opt_group.'_settings', $cspm_v2_options);
						
					/**
					 * == Create a new map with all v2.+ settings as new post of our CPT (Defined in [@object_type]) in v3.0 == */
																				
					/**
					 * Query settings */
					
					$query_settings = array(
						$this->metafield_prefix.'_number_of_items' => $this->cspm_setting_exists('cspm_generalsettings_number_of_items', $cspm_v2_options, ''), 
						$this->metafield_prefix.'_taxonomy_relation_param' => $this->cspm_setting_exists('cspm_generalsettings_taxonomy_relation_param', $cspm_v2_options, ''),
						$this->metafield_prefix.'_custom_fields' => ($this->cspm_setting_exists('cspm_generalsettings_custom_fields', $cspm_v2_options, '')),
						$this->metafield_prefix.'_custom_field_relation_param' => $this->cspm_setting_exists('cspm_generalsettings_custom_field_relation_param', $cspm_v2_options, ''),
						$this->metafield_prefix.'_cache_results' => $this->cspm_setting_exists('cspm_generalsettings_cache_results', $cspm_v2_options, ''),
						$this->metafield_prefix.'_update_post_meta_cache' => $this->cspm_setting_exists('cspm_generalsettings_update_post_meta_cache', $cspm_v2_options, ''),
						$this->metafield_prefix.'_update_post_term_cache' => $this->cspm_setting_exists('cspm_generalsettings_update_post_term_cache', $cspm_v2_options, ''),
						$this->metafield_prefix.'_authors_prefixing' => $this->cspm_setting_exists('cspm_generalsettings_authors_prefixing', $cspm_v2_options, ''),
						$this->metafield_prefix.'_authors' => $this->cspm_setting_exists('cspm_generalsettings_authors', $cspm_v2_options, ''),
						$this->metafield_prefix.'_orderby_param' => $this->cspm_setting_exists('cspm_generalsettings_orderby_param', $cspm_v2_options, ''),
						$this->metafield_prefix.'_orderby_meta_key' => $this->cspm_setting_exists('cspm_generalsettings_orderby_meta_key', $cspm_v2_options, ''),					
						$this->metafield_prefix.'_order_param' => $this->cspm_setting_exists('cspm_generalsettings_order_param', $cspm_v2_options, ''),
					);
						
						/**
						 * "Post In" and "Post not in" 
						 * @since 3.2 */
						 
						$post_in = $this->cspm_setting_exists('cspm_generalsettings_post_in', $cspm_v2_options, '');
						
						if(!empty($post_in))
							$query_settings[$this->metafield_prefix.'_post_in'] = explode(',', $post_in);
						 
						$post_not_in = $this->cspm_setting_exists('cspm_generalsettings_post_not_in', $cspm_v2_options, '');
						
						if(!empty($post_not_in))
							$query_settings[$this->metafield_prefix.'_post_not_in'] = explode(',', $post_not_in);
					
						/**
						 * Build Taxonomies */
						 
						$main_post_type_taxonomies = (array) get_object_taxonomies($main_post_type, 'objects');
							unset($main_post_type_taxonomies['post_format']);
							
						reset($main_post_type_taxonomies); // Set the cursor to 0
						
						if(is_array($main_post_type_taxonomies)){
							
							foreach($main_post_type_taxonomies as $single_taxonomy){
								
								if(isset($single_taxonomy->name)){
								
									$tax_name = $single_taxonomy->name;
									
									$query_settings[$this->metafield_prefix . '_taxonomie_'.$tax_name] = ($this->cspm_setting_exists('cspm_generalsettings_taxonomie_'.$tax_name, $cspm_v2_options, ''));
									$query_settings[$this->metafield_prefix.'_'.$tax_name.'_operator_param'] = $this->cspm_setting_exists('cspm_generalsettings_'.$tax_name.'_operator_param', $cspm_v2_options);
							
								}
								
							}
					
						}
						
						/**
						 * Build statuses */
						 
						$statuses = get_post_stati();
						
						$selected_statuses = array();
						
						if(is_array($statuses)){
							
							foreach($statuses as $status){	
								
								$status_name = $this->cspm_setting_exists('cspm_generalsettings_items_status_'.$status, $cspm_v2_options);
								
								if(!empty($status_name))
									$selected_statuses[] = $status_name;
							
							}
							
						}
						
						$query_settings[$this->metafield_prefix.'_items_status'] = $selected_statuses;
						
					/**
					 * Layout settings */
					
					$layout_settings = array( 
						$this->metafield_prefix.'_main_layout' => $this->cspm_setting_exists('cspm_layoutsettings_main_layout', $cspm_v2_options, ''),
						$this->metafield_prefix.'_layout_type' => $this->cspm_setting_exists('cspm_layoutsettings_layout_type', $cspm_v2_options, ''),
						$this->metafield_prefix.'_layout_fixed_width' => $this->cspm_setting_exists('cspm_layoutsettings_layout_fixed_width', $cspm_v2_options, ''),
						$this->metafield_prefix.'_layout_fixed_height' => $this->cspm_setting_exists('cspm_layoutsettings_layout_fixed_height', $cspm_v2_options, ''),
					);
										
					/**
					 * Map settings */
					 
					$map_settings = array( 
						$this->metafield_prefix.'_map_center' => $this->cspm_setting_exists('cspm_mapsettings_map_center', $cspm_v2_options, ''),
						$this->metafield_prefix.'_initial_map_style' => $this->cspm_setting_exists('cspm_mapsettings_initial_map_style', $cspm_v2_options, ''),
						$this->metafield_prefix.'_map_zoom' => $this->cspm_setting_exists('cspm_mapsettings_map_zoom', $cspm_v2_options, ''),
						$this->metafield_prefix.'_max_zoom' => $this->cspm_setting_exists('cspm_mapsettings_max_zoom', $cspm_v2_options, ''),
						$this->metafield_prefix.'_min_zoom' => $this->cspm_setting_exists('cspm_mapsettings_min_zoom', $cspm_v2_options, ''),
						$this->metafield_prefix.'_zoom_on_doubleclick' => $this->cspm_setting_exists('cspm_mapsettings_zoom_on_doubleclick', $cspm_v2_options, ''),
						$this->metafield_prefix.'_map_draggable' => $this->cspm_setting_exists('cspm_mapsettings_map_draggable', $cspm_v2_options, ''),
						$this->metafield_prefix.'_useClustring' => $this->cspm_setting_exists('cspm_mapsettings_useClustring', $cspm_v2_options, ''),
						$this->metafield_prefix.'_gridSize' => $this->cspm_setting_exists('cspm_mapsettings_gridSize', $cspm_v2_options, ''),
						$this->metafield_prefix.'_autofit' => $this->cspm_setting_exists('cspm_mapsettings_autofit', $cspm_v2_options, ''),
						$this->metafield_prefix.'_traffic_layer' => $this->cspm_setting_exists('cspm_mapsettings_traffic_layer', $cspm_v2_options, ''),
						$this->metafield_prefix.'_transit_layer' => $this->cspm_setting_exists('cspm_mapsettings_transit_layer', $cspm_v2_options, ''),
						$this->metafield_prefix.'_geoIpControl' => $this->cspm_setting_exists('cspm_mapsettings_geoIpControl', $cspm_v2_options, ''),
						$this->metafield_prefix.'_show_user' => $this->cspm_setting_exists('cspm_mapsettings_show_user', $cspm_v2_options, ''),
						$this->metafield_prefix.'_user_marker_icon' => $this->cspm_setting_exists('cspm_mapsettings_user_marker_icon', $cspm_v2_options, ''),
						$this->metafield_prefix.'_user_map_zoom' => $this->cspm_setting_exists('cspm_mapsettings_user_map_zoom', $cspm_v2_options, ''),
						$this->metafield_prefix.'_mapTypeControl' => $this->cspm_setting_exists('cspm_mapsettings_mapTypeControl', $cspm_v2_options, ''),
						$this->metafield_prefix.'_streetViewControl' => $this->cspm_setting_exists('cspm_mapsettings_streetViewControl', $cspm_v2_options, ''),
						$this->metafield_prefix.'_scrollwheel' => $this->cspm_setting_exists('cspm_mapsettings_scrollwheel', $cspm_v2_options, ''),
						$this->metafield_prefix.'_zoomControl' => $this->cspm_setting_exists('cspm_mapsettings_zoomControl', $cspm_v2_options, ''),
						$this->metafield_prefix.'_zoomControlType' => $this->cspm_setting_exists('cspm_mapsettings_zoomControlType', $cspm_v2_options, ''),
						$this->metafield_prefix.'_retinaSupport' => $this->cspm_setting_exists('cspm_mapsettings_retinaSupport', $cspm_v2_options, ''),
						$this->metafield_prefix.'_defaultMarker' => $this->cspm_setting_exists('cspm_mapsettings_defaultMarker', $cspm_v2_options, ''),
						$this->metafield_prefix.'_markerAnimation' => $this->cspm_setting_exists('cspm_mapsettings_markerAnimation', $cspm_v2_options, ''),
						$this->metafield_prefix.'_marker_icon' => $this->cspm_setting_exists('cspm_mapsettings_marker_icon', $cspm_v2_options, ''),
						$this->metafield_prefix.'_marker_anchor_point_option' => $this->cspm_setting_exists('cspm_mapsettings_marker_anchor_point_option', $cspm_v2_options, ''),
						$this->metafield_prefix.'_marker_anchor_point' => $this->cspm_setting_exists('cspm_mapsettings_marker_anchor_point', $cspm_v2_options, ''),
						$this->metafield_prefix.'_big_cluster_icon' => $this->cspm_setting_exists('cspm_mapsettings_big_cluster_icon', $cspm_v2_options, ''),
						$this->metafield_prefix.'_medium_cluster_icon' => $this->cspm_setting_exists('cspm_mapsettings_medium_cluster_icon', $cspm_v2_options, ''),
						$this->metafield_prefix.'_small_cluster_icon' => $this->cspm_setting_exists('cspm_mapsettings_small_cluster_icon', $cspm_v2_options, ''),
						$this->metafield_prefix.'_cluster_text_color' => $this->cspm_setting_exists('cspm_mapsettings_cluster_text_color', $cspm_v2_options, ''),
						$this->metafield_prefix.'_zoom_in_icon' => $this->cspm_setting_exists('cspm_mapsettings_zoom_in_icon', $cspm_v2_options, ''),
						$this->metafield_prefix.'_zoom_in_css' => $this->cspm_setting_exists('cspm_mapsettings_zoom_in_css', $cspm_v2_options, ''),
						$this->metafield_prefix.'_zoom_out_icon' => $this->cspm_setting_exists('cspm_mapsettings_zoom_out_icon', $cspm_v2_options, ''),
						$this->metafield_prefix.'_zoom_out_css' => $this->cspm_setting_exists('cspm_mapsettings_zoom_out_css', $cspm_v2_options, ''),
					);
								
					/**
					 * Map style settings */
					
					$map_style_settings = array(  
						$this->metafield_prefix.'_style_option' => $this->cspm_setting_exists('cspm_mapstylesettings_style_option', $cspm_v2_options, ''),
						$this->metafield_prefix.'_map_style' => $this->cspm_setting_exists('cspm_mapstylesettings_map_style', $cspm_v2_options, ''),
						$this->metafield_prefix.'_custom_style_name' => $this->cspm_setting_exists('cspm_mapstylesettings_custom_style_name', $cspm_v2_options, ''),
						$this->metafield_prefix.'_js_style_array' => $this->cspm_setting_exists('cspm_mapstylesettings_js_style_array', $cspm_v2_options, ''),
					);
					
					/** 
					 * Infobox settings */
					 
					$infobox_settings = array(
						$this->metafield_prefix.'_show_infobox' => $this->cspm_setting_exists('cspm_infoboxsettings_show_infobox', $cspm_v2_options, ''),
						$this->metafield_prefix.'_infobox_type' => $this->cspm_setting_exists('cspm_infoboxsettings_infobox_type', $cspm_v2_options, ''),
						$this->metafield_prefix.'_infobox_display_event' => $this->cspm_setting_exists('cspm_infoboxsettings_infobox_display_event', $cspm_v2_options, ''),
						$this->metafield_prefix.'_remove_infobox_on_mouseout' => $this->cspm_setting_exists('cspm_infoboxsettings_remove_infobox_on_mouseout', $cspm_v2_options, ''),
						$this->metafield_prefix.'_infobox_external_link' => $this->cspm_setting_exists('cspm_infoboxsettings_infobox_external_link', $cspm_v2_options, ''),
					);
					
					/**
					 * Marker categories settings */
					
					$selected_taxonomy = $this->cspm_setting_exists('cspm_markercategoriessettings_marker_taxonomies', $cspm_v2_options, '');
					
					$marker_categories_settings = array(  
						$this->metafield_prefix.'_marker_cats_settings' => $this->cspm_setting_exists('cspm_markercategoriessettings_marker_cats_settings', $cspm_v2_options, ''),
						$this->metafield_prefix.'_marker_categories_taxonomy' => $selected_taxonomy,					
					);
					
						if(!empty($selected_taxonomy)){
							
							$marker_categories_images_array = array();
							
							$marker_categories_images = (array) json_decode(str_replace('tag_', '', $this->cspm_setting_exists('cspm_markercategoriessettings_marker_category_'.$selected_taxonomy, $cspm_v2_options, '')));
							
							if(is_array($marker_categories_images)){
								
								foreach($marker_categories_images as $marker_image){
									
									if(count((array) $marker_image) > 0){
										
										$new_marker_imag = array();
										
										foreach((array) $marker_image as $key => $value)
											$new_marker_imag[$key.'_'.$selected_taxonomy] = $value;
											
									}
									
									$marker_categories_images_array[] = $new_marker_imag;
								
								}
								
							}
								
							$marker_categories_settings[$this->metafield_prefix.'_marker_categories_images'] = $marker_categories_images_array;
						
						}
					
					/**
					 * KML Layers settings */
					 
					$kml_layers_settings = array( 
						$this->metafield_prefix.'_use_kml' => $this->cspm_setting_exists('cspm_kmlsettings_use_kml', $cspm_v2_options, ''),					
					);
						
						$kml_url = $this->cspm_setting_exists('cspm_kmlsettings_kml_file', $cspm_v2_options, '');
						
						$kml_data_array = array(
							'kml_label' => (!empty($kml_url)) ? 'My KML Layer' : '',
							'kml_url' => $kml_url,
							'kml_suppressInfoWindows' => $this->cspm_setting_exists('cspm_kmlsettings_suppressInfoWindows', $cspm_v2_options, ''),
							'kml_preserveViewport' => $this->cspm_setting_exists('cspm_kmlsettings_preserveViewport', $cspm_v2_options, ''),
							'kml_visibility' => 'true',
						);
						
						$kml_layers_settings[$this->metafield_prefix.'_kml_layers'] = array(0 => $kml_data_array);
					
					/**
					 * Overlays (Polylines & Polygons) settings */
					
					$overlays_settings = array( 						
						$this->metafield_prefix.'_draw_polyline' => $this->cspm_setting_exists('cspm_overlayssettings_draw_polyline', $cspm_v2_options, ''),
						$this->metafield_prefix.'_draw_polygon' => $this->cspm_setting_exists('cspm_overlayssettings_draw_polygon', $cspm_v2_options, ''),
					);
						
						/**
						 * Polylines */
						 
						$polylines_array = array();
						
						$polylines = (array) json_decode(str_replace('tag_', '', $this->cspm_setting_exists('cspm_overlayssettings_polylines', $cspm_v2_options, '')));
						
						if(is_array($polylines)){
							
							foreach($polylines as $polyline){
								$polylines_array[] = (array) $polyline;
							}
							
						}
								
						$overlays_settings[$this->metafield_prefix.'_polylines'] = $polylines_array;
						
						/**
						 * Polygons */
						 
						$polygons_array = array();
						
						$polygons = (array) json_decode(str_replace('tag_', '', $this->cspm_setting_exists('cspm_overlayssettings_polygons', $cspm_v2_options, '')));
						
						if(is_array($polygons)){
							
							foreach($polygons as $polygon){
								$polygons_array[] = (array) $polygon;
							}
						
						}
						
						$overlays_settings[$this->metafield_prefix.'_polygons'] = $polygons_array;
					
					/**
					 * Carousel settings */
					
					$carousel_settings = array(  
						$this->metafield_prefix.'_show_carousel' => $this->cspm_setting_exists('cspm_carouselsettings_show_carousel', $cspm_v2_options, ''),
						$this->metafield_prefix.'_carousel_mode' => $this->cspm_setting_exists('cspm_carouselsettings_carousel_mode', $cspm_v2_options, ''),
						$this->metafield_prefix.'_carousel_scroll' => $this->cspm_setting_exists('cspm_carouselsettings_carousel_scroll', $cspm_v2_options, ''),
						$this->metafield_prefix.'_carousel_easing' => $this->cspm_setting_exists('cspm_carouselsettings_carousel_easing', $cspm_v2_options, ''),
						$this->metafield_prefix.'_carousel_animation' => $this->cspm_setting_exists('cspm_carouselsettings_carousel_animation', $cspm_v2_options, ''),
						$this->metafield_prefix.'_carousel_auto' => $this->cspm_setting_exists('cspm_carouselsettings_carousel_auto', $cspm_v2_options, ''),
						$this->metafield_prefix.'_carousel_wrap' => $this->cspm_setting_exists('cspm_carouselsettings_carousel_wrap', $cspm_v2_options, ''),
						$this->metafield_prefix.'_scrollwheel_carousel' => $this->cspm_setting_exists('cspm_carouselsettings_scrollwheel_carousel', $cspm_v2_options, ''),
						$this->metafield_prefix.'_touchswipe_carousel' => $this->cspm_setting_exists('cspm_carouselsettings_touchswipe_carousel', $cspm_v2_options, ''),
						$this->metafield_prefix.'_move_carousel_on' => array_merge(
							(array) $this->cspm_setting_exists('cspm_carouselsettings_move_carousel_on_marker_click', $cspm_v2_options, ''),
							(array) $this->cspm_setting_exists('cspm_carouselsettings_move_carousel_on_marker_hover', $cspm_v2_options, ''),
							(array) $this->cspm_setting_exists('cspm_carouselsettings_move_carousel_on_infobox_hover', $cspm_v2_options, '')
						),
						$this->metafield_prefix.'_carousel_map_zoom' => $this->cspm_setting_exists('cspm_carouselsettings_carousel_map_zoom', $cspm_v2_options, ''),
					);
					
					/**
					 * Carousel style settings */
					
					$carousel_style_settings = array( 
						$this->metafield_prefix.'_carousel_css' => $this->cspm_setting_exists('cspm_carouselstyle_carousel_css', $cspm_v2_options, ''), 
						$this->metafield_prefix.'_arrows_background' => $this->cspm_setting_exists('cspm_carouselstyle_arrows_background', $cspm_v2_options, ''),
						$this->metafield_prefix.'_items_background' => $this->cspm_setting_exists('key', $cspm_v2_options, ''),
						$this->metafield_prefix.'_items_hover_background' => $this->cspm_setting_exists('key', $cspm_v2_options, ''),
					);
					
					/**
					 * Carousel items settings */
					 
					$carousel_items_settings = array( 
						$this->metafield_prefix.'_items_view' => $this->cspm_setting_exists('key', $cspm_v2_options, ''),
						$this->metafield_prefix.'_horizontal_item_size' => $this->cspm_setting_exists('cspm_itemssettings_horizontal_item_size', $cspm_v2_options, ''),
						$this->metafield_prefix.'_horizontal_image_size' => $this->cspm_setting_exists('cspm_itemssettings_horizontal_image_size', $cspm_v2_options, ''),
						$this->metafield_prefix.'_horizontal_details_size' => $this->cspm_setting_exists('cspm_itemssettings_horizontal_details_size', $cspm_v2_options, ''),
						$this->metafield_prefix.'_vertical_item_size' => $this->cspm_setting_exists('cspm_itemssettings_vertical_item_size', $cspm_v2_options, ''),
						$this->metafield_prefix.'_vertical_image_size' => $this->cspm_setting_exists('cspm_itemssettings_vertical_image_size', $cspm_v2_options, ''),
						$this->metafield_prefix.'_vertical_details_size' => $this->cspm_setting_exists('cspm_itemssettings_vertical_details_size', $cspm_v2_options, ''),
						$this->metafield_prefix.'_show_details_btn' => $this->cspm_setting_exists('cspm_itemssettings_show_details_btn', $cspm_v2_options, ''),
						$this->metafield_prefix.'_details_btn_text' => $this->cspm_setting_exists('cspm_itemssettings_details_btn_text', $cspm_v2_options, ''),
						$this->metafield_prefix.'_click_on_title' => $this->cspm_setting_exists('cspm_itemssettings_click_on_title', $cspm_v2_options, ''),
						$this->metafield_prefix.'_external_link' => $this->cspm_setting_exists('cspm_itemssettings_external_link', $cspm_v2_options, ''),
						$this->metafield_prefix.'_items_details' => $this->cspm_setting_exists('cspm_itemssettings_items_details', $cspm_v2_options, ''),
					);
					
					/**
					 * Posts count settings */
					
					$posts_count_settings = array(  
						$this->metafield_prefix.'_show_posts_count' => $this->cspm_setting_exists('cspm_postscountsettings_show_posts_count', $cspm_v2_options, ''),
						$this->metafield_prefix.'_posts_count_clause' => $this->cspm_setting_exists('cspm_postscountsettings_posts_count_clause', $cspm_v2_options, ''),
						$this->metafield_prefix.'_posts_count_color' => $this->cspm_setting_exists('cspm_postscountsettings_posts_count_color', $cspm_v2_options, ''),
					);
					
					/**
					 * Faceted search settings */
					 
					$faceted_search_settings = array( 
						$this->metafield_prefix.'_faceted_search_option' => $this->cspm_setting_exists('cspm_facetedsearchsettings_faceted_search_option', $cspm_v2_options, ''),						
						$this->metafield_prefix.'_faceted_search_multi_taxonomy_option' => $this->cspm_setting_exists('cspm_facetedsearchsettings_faceted_search_multi_taxonomy_option', $cspm_v2_options, ''),
						$this->metafield_prefix.'_faceted_search_drag_map' => $this->cspm_setting_exists('cspm_facetedsearchsettings_faceted_search_drag_map', $cspm_v2_options, ''),
						$this->metafield_prefix.'_faceted_search_input_skin' => $this->cspm_setting_exists('cspm_facetedsearchsettings_faceted_search_input_skin', $cspm_v2_options, ''),
						$this->metafield_prefix.'_faceted_search_input_color' => $this->cspm_setting_exists('cspm_facetedsearchsettings_faceted_search_input_color', $cspm_v2_options, ''),
						$this->metafield_prefix.'_faceted_search_css' => $this->cspm_setting_exists('cspm_facetedsearchsettings_faceted_search_css', $cspm_v2_options, ''),
					);
					
						if(!empty($selected_taxonomy)){
						
							$faceted_search_settings[$this->metafield_prefix.'_faceted_search_taxonomy_'.$selected_taxonomy] = $this->cspm_setting_exists('cspm_facetedsearchsettings_faceted_search_taxonomy_'.$selected_taxonomy, $cspm_v2_options, '');
							
						}
					
					/**
					 * Search form settings */
					 
					$search_form_settings = array( 
						$this->metafield_prefix.'_search_form_option' => $this->cspm_setting_exists('cspm_searchformsettings_search_form_option', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_distance_unit' => $this->cspm_setting_exists('cspm_searchformsettings_sf_distance_unit', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_address_placeholder' => $this->cspm_setting_exists('cspm_searchformsettings_address_placeholder', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_slider_label' => $this->cspm_setting_exists('cspm_searchformsettings_slider_label', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_submit_text' => $this->cspm_setting_exists('cspm_searchformsettings_submit_text', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_search_form_bg_color' => $this->cspm_setting_exists('cspm_searchformsettings_search_form_bg_color', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_no_location_msg' => $this->cspm_setting_exists('cspm_searchformsettings_no_location_msg', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_bad_address_msg' => $this->cspm_setting_exists('cspm_searchformsettings_bad_address_msg', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_bad_address_sug_1' => $this->cspm_setting_exists('cspm_searchformsettings_bad_address_sug_1', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_bad_address_sug_2' => $this->cspm_setting_exists('cspm_searchformsettings_bad_address_sug_2', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_bad_address_sug_3' => $this->cspm_setting_exists('cspm_searchformsettings_bad_address_sug_3', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_circle_option' => $this->cspm_setting_exists('cspm_searchformsettings_circle_option', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_fillColor' => $this->cspm_setting_exists('cspm_searchformsettings_fillColor', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_fillOpacity' => $this->cspm_setting_exists('cspm_searchformsettings_fillOpacity', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_strokeColor' => $this->cspm_setting_exists('cspm_searchformsettings_strokeColor', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_strokeOpacity' => $this->cspm_setting_exists('cspm_searchformsettings_strokeOpacity', $cspm_v2_options, ''),
						$this->metafield_prefix.'_sf_strokeWeight' => $this->cspm_setting_exists('cspm_searchformsettings_strokeWeight', $cspm_v2_options, ''),
					);
					
						$distance = explode(',', $this->cspm_setting_exists('cspm_searchformsettings_sf_search_distances', $cspm_v2_options, array(3,15)));
						
						$search_form_settings[$this->metafield_prefix.'_sf_min_search_distances'] = $this->cspm_setting_exists(0, $distance, '3');
						$search_form_settings[$this->metafield_prefix.'_sf_max_search_distances'] = $this->cspm_setting_exists(1, $distance, '15');
					
					/**
					 * Merge all "Progress Map" settings arrays into one */
					
					$cspm_settings_arrays = array_merge(
						array($this->metafield_prefix.'_post_type' => $main_post_type),
						$query_settings,
						$layout_settings,
						$map_settings,
						$map_style_settings,
						$infobox_settings,
						$marker_categories_settings,
						$kml_layers_settings,
						$overlays_settings,
						$carousel_settings,
						$carousel_style_settings,
						$carousel_items_settings,
						$posts_count_settings,
						$faceted_search_settings,
						$search_form_settings
					);
					
					/**
					 * == List & filter Extension settings == */
					
					if(class_exists('ProgressMapList')){
						
						$cspml_v1_options = get_option('cspml_settings');
					
						/**
						 * Layout settings */
						  
						$list_layout_settings = array( 
							$this->metafield_prefix.'_list_layout' => $this->cspm_setting_exists('cspml_layout_list_layout', $cspml_v1_options, ''),
							$this->metafield_prefix.'_map_height' => $this->cspm_setting_exists('cspml_layout_map_height', $cspml_v1_options, ''),
							$this->metafield_prefix.'_list_height' => $this->cspm_setting_exists('cspml_layout_list_height', $cspml_v1_options, ''),
						);
						
						/**
						 * Options bar settings */
						 
						$options_bar_settings = array( 
							$this->metafield_prefix.'_show_options_bar' => $this->cspm_setting_exists('cspml_options_bar_show_options_bar', $cspml_v1_options, ''),
							$this->metafield_prefix.'_show_view_options' => $this->cspm_setting_exists('cspml_options_bar_show_view_options', $cspml_v1_options, ''),
							$this->metafield_prefix.'_default_view_option' => $this->cspm_setting_exists('cspml_options_bar_default_view_option', $cspml_v1_options, ''),
							$this->metafield_prefix.'_grid_cols' => $this->cspm_setting_exists('cspml_options_bar_grid_cols', $cspml_v1_options, ''),
							$this->metafield_prefix.'_cslf_show_posts_count' => $this->cspm_setting_exists('cspml_options_bar_show_posts_count', $cspml_v1_options, ''),
							$this->metafield_prefix.'_cslf_posts_count_clause' => $this->cspm_setting_exists('cspml_options_bar_posts_count_clause', $cspml_v1_options, ''),
						);
						
						/**
						 * List items settings */
		
						$list_items_settings = array( 
							$this->metafield_prefix.'_listings_title' => $this->cspm_setting_exists('cspml_list_items_listings_title', $cspml_v1_options, ''),					 
							$this->metafield_prefix.'_listings_details' => $this->cspm_setting_exists('cspml_list_items_listings_details', $cspml_v1_options, ''),
							$this->metafield_prefix.'_cslf_click_on_title' => $this->cspm_setting_exists('cspml_list_items_click_on_title', $cspml_v1_options, ''),
							$this->metafield_prefix.'_cslf_click_on_img' => $this->cspm_setting_exists('cspml_list_items_click_on_img', $cspml_v1_options, ''),
							$this->metafield_prefix.'_cslf_external_link' => $this->cspm_setting_exists('cspml_list_items_external_link', $cspml_v1_options, ''),
							$this->metafield_prefix.'_show_fire_pinpoint_btn' => $this->cspm_setting_exists('cspml_list_items_show_fire_pinpoint_btn', $cspml_v1_options, ''),
						);
						
						/**
						 * Sort options settings */
						
						$sort_options_settings = array(  
							$this->metafield_prefix.'_show_sort_option' => $this->cspm_setting_exists('cspml_sort_option_show_sort_option', $cspml_v1_options, ''),
							$this->metafield_prefix.'_sort_options' => $this->cspm_setting_exists('cspml_sort_option_sort_options', $cspml_v1_options, ''),
						);
							 
							$custom_sort_options_array = array();
							
							$custom_sort_options = (array) json_decode(str_replace('tag_', '', $this->cspm_setting_exists('cspml_sort_option_custom_sort_options', $cspml_v1_options, '')));
							
							if(is_array($custom_sort_options)){
								
								foreach($custom_sort_options as $custom_sort_option){
									$custom_sort_options_array[] = (array) $custom_sort_option;
								}
							
							}
							
							$sort_options_settings[$this->metafield_prefix.'_custom_sort_options'] = $custom_sort_options_array;
						
						/**
						 * Pagination settings */
						
						$pagination_settings = array( 
							$this->metafield_prefix.'_posts_per_page' => $this->cspm_setting_exists('cspml_pagiantion_posts_per_page', $cspml_v1_options, ''),						 
							$this->metafield_prefix.'_pagination_position' => $this->cspm_setting_exists('cspml_pagiantion_pagination_position', $cspml_v1_options, ''),
							$this->metafield_prefix.'_pagination_align' => $this->cspm_setting_exists('cspml_pagiantion_pagination_align', $cspml_v1_options, ''),
							$this->metafield_prefix.'_prev_page_text' => $this->cspm_setting_exists('cspml_pagiantion_prev_page_text', $cspml_v1_options, ''),
							$this->metafield_prefix.'_next_page_text' => $this->cspm_setting_exists('cspml_pagiantion_next_page_text', $cspml_v1_options, ''),
							$this->metafield_prefix.'_show_all' => $this->cspm_setting_exists('cspml_pagiantion_show_all', $cspml_v1_options, ''),
						);
						
						/**
						 * Filter search settings */
						
						$filter_search_settings = array(  
							$this->metafield_prefix.'_cslf_faceted_search_option' => $this->cspm_setting_exists('cspml_list_filter_faceted_search_option', $cspml_v1_options, ''),
							$this->metafield_prefix.'_faceted_search_position' => $this->cspm_setting_exists('cspml_list_filter_faceted_search_position', $cspml_v1_options, ''),
							$this->metafield_prefix.'_faceted_search_display_option' => $this->cspm_setting_exists('cspml_list_filter_filter_btns_position', $cspml_v1_options, ''),
							$this->metafield_prefix.'_filter_btns_position' => $this->cspm_setting_exists('key', $cspml_v1_options, ''),
							$this->metafield_prefix.'_cslf_taxonomy_relation_param' => $this->cspm_setting_exists('cspml_list_filter_taxonomy_relation_param', $cspml_v1_options, ''),
							$this->metafield_prefix.'_cslf_custom_field_relation_param' => $this->cspm_setting_exists('cspml_list_filter_custom_field_relation_param', $cspml_v1_options, ''),
							$this->metafield_prefix.'_cslf_filter_btn_text' => $this->cspm_setting_exists('cspml_list_filter_filter_btn_text', $cspml_v1_options, ''),
						);
							
							/**
							 * Taxonomies */
							  
							$taxonomies_array = array();
							
							$taxonomies = (array) json_decode(str_replace('tag_', '', $this->cspm_setting_exists('cspml_list_filter_taxonomies', $cspml_v1_options, '')));
							
							if(is_array($taxonomies)){
								
								foreach($taxonomies as $taxonomy){
									$taxonomies_array[] = (array) $taxonomy;
								}
								
							}
									
							$filter_search_settings[$this->metafield_prefix.'_cslf_taxonomies'] = $taxonomies_array;
							
							/**
							 * Custom Fields */
							  
							$custom_fields_array = array();
							
							$custom_fields = (array) json_decode(str_replace('tag_', '', $this->cspm_setting_exists('cspml_list_filter_custom_fields', $cspml_v1_options, '')));
							
							if(is_array($custom_fields)){
								
								foreach($custom_fields as $custom_field){
									$custom_fields_array[] = (array) $custom_field;
								}
							
							}
							
							$filter_search_settings[$this->metafield_prefix.'_cslf_custom_fields'] = $custom_fields_array;
						
						/**
						 * Merge all "List & Filter" settings arrays into one */
						
						$cspml_settings_arrays = array_merge(
							array($this->metafield_prefix.'_list_ext' => 'on'),					
							$list_layout_settings,
							$options_bar_settings,
							$list_items_settings,
							$sort_options_settings,
							$pagination_settings,
							$filter_search_settings
						);
					
					}else $cspml_settings_arrays = array();
					
					/**
					 * Build wp_insert_post() args */
					 
					$insert_post_args = array(
						'post_type' => $this->object_type,
						'post_status' => 'publish',
						'post_title' => 'Auto generated map based on v2 settings',
						'meta_input' => array_merge(						
							
							/**
							 * Progress Map settings */
							 
							$cspm_settings_arrays,
							
							/**
							 * List & filter settings */
							
							$cspml_settings_arrays
							
						),
					);
								
					wp_insert_post($insert_post_args);

					/**
					 * Regenerate Markers just in case */
					 
					$this->cspm_regenerate_markers(false);	
				
				}
				
			}
						 
		}
		
		
		/**
		 * This will get the current post type in the WordPress Admin
		 * 
		 * @since 3.1
		 */
		function cspm_get_current_post_type() {
			
			global $post, $typenow, $current_screen;
			
			/**
			 * we have a post so we can just get the post type from that */
			 
			if($post && $post->post_type)
				return $post->post_type;
			
			/**
			 * check the global $typenow - set in admin.php */
			 
			elseif($typenow)
				return $typenow;
			
			/**
			 * check the global $current_screen object - set in sceen.php */
			 
			elseif($current_screen && $current_screen->post_type)
				return $current_screen->post_type;
			
			/**
			 * check the post_type querystring */
			 
			elseif(isset($_REQUEST['post_type']))
				return sanitize_key($_REQUEST['post_type']);
			
			/**
			 * lastly check if post ID is in query string */
			 
			elseif(isset($_REQUEST['post']))
				return get_post_type($_REQUEST['post']);
			
			/**
			 * we do not know the post type! */
			 
			else return null;
			
		}
		
 
		/*
		 * This will add a duplicate link to the "All maps" list
		 *
		 * @since 3.2
		 */
		function cspm_duplicate_map_link($actions, $post){
			
			if($post->post_type == $this->object_type && current_user_can('edit_posts')){
				
				$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=cspm_duplicate_map&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this map" rel="permalink" class="cspm_duplicate_map_link">Duplicate this map</a>';
				
			}
			
			return $actions;
			
		}
		
		
		/**
		 * A custom CSS style for our duplicate link
		 *
		 * @since 3.2
		 */
		function cspm_duplicate_map_link_style(){
			
			echo '<style>
				a.cspm_duplicate_map_link{
					padding: 5px 10px 7px 10px;
					background: #FE5E05;
					border-radius: 2px;
					color: #fff;
					box-shadow: rgba(0,0,0,.298039) 0 1px 4px -1px, inset 0 -1px 0 0 rgba(0,0,0,.24);
				}				
				a.cspm_duplicate_map_link:hover{
					background:#FF3902;
				}
			</style>';
		  
		}


		/*
		 * Duplicate a map and redirect to the edit map screen
		 *
		 * @since 3.2
		 */
		function cspm_duplicate_map(){
			
			global $wpdb;
			
			if(!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'cspm_duplicate_map' == $_REQUEST['action'])))
				wp_die('No map to duplicate has been supplied!');
		 
			/*
			 * Nonce verification */
			 
			if(!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename( __FILE__ )))
				return;
		 
			/*
			 * Get the original post id */
			 
			$post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
			
			/*
			 * Get all the original post data */
			 
			$post = get_post($post_id);
		 
			/*
			 * if you don't want current user to be the new post author,
			 * then change next couple of lines to this: $new_post_author = $post->post_author; */
			 
			$current_user = wp_get_current_user();
			$new_post_author = $current_user->ID;
		 
			/*
			 * if post data exists, create the post duplicate */
			 
			if(isset($post) && $post != null){
		 
				/*
				 * new post data array
				 */
				$args = array(
					'comment_status' => $post->comment_status,
					'ping_status'    => $post->ping_status,
					'post_author'    => $new_post_author,
					'post_content'   => $post->post_content,
					'post_excerpt'   => $post->post_excerpt,
					'post_name'      => $post->post_name,
					'post_parent'    => $post->post_parent,
					'post_password'  => $post->post_password,
					'post_status'    => 'publish',
					'post_title'     => 'Duplicate of, "'.$post->post_title.'"',
					'post_type'      => $post->post_type,
					'to_ping'        => $post->to_ping,
					'menu_order'     => $post->menu_order
				);
		 
				/*
				 * Insert the post by wp_insert_post() function */
				 
				$new_post_id = wp_insert_post( $args );
		 
				/*
				 * Duplicate all post meta just in two SQL queries */
				 
				$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
				
				if(count($post_meta_infos) != 0){
					
					$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
					
					foreach ($post_meta_infos as $meta_info){
						
						$meta_key = $meta_info->meta_key;
						
						if($meta_key == '_wp_old_slug')
							continue;
						
						$meta_value = addslashes($meta_info->meta_value);
						
						$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
					
					}
					
					$sql_query .= implode(" UNION ALL ", $sql_query_sel);
					
					$wpdb->query($sql_query);
					
				}
		 
				/*
				 * Finally, redirect to the edit map screen */
				 
				wp_redirect(admin_url('post.php?action=edit&post='.$new_post_id));
				
				exit;
				
			}else wp_die('Map creation failed, could not find the original map: '.$post_id);
		
		}
				
	}
	
}

if(class_exists('CSProgressMap')){
	
	$CSProgressMap = new CSProgressMap();
	$CSProgressMap->cspm_hooks();
	
}
