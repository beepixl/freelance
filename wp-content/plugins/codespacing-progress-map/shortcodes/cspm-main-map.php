<?php
 
if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

if(!class_exists('CspmMainMap')){
	
	class CspmMainMap{
		
		private static $_this;	
		
		private $plugin_path;
		private $plugin_url;
		
		public $plugin_settings = array();
		public $map_settings = array();
		
		public $map_styles_file;
		
		public $metafield_prefix; //@since 3.0
		public $object_type; //@since 3.0
		public $map_object_id = ''; //@since 3.0
		
		/**
		 * Plugin settings */
		 
		public $outer_links_field_name = ''; //@since 2.5	
		public $combine_files = 'seperate'; // @since 2.5	
		public $use_with_wpml = 'no'; //@since 2.6.3
		public $remove_bootstrap = 'enable'; //@since 2.8.2
		public $remove_gmaps_api = array(); //@since 2.8.2 //updated 2.8.5	
		public $remove_google_fonts = 'enable'; //@since 2.8.2	
		
		/**
		 * Query settings */
		 
		public $post_type = '';
		public $post_in = '';		
		public $post_not_in = '';		
		public $cache_results = '';
		public $update_post_meta_cache = '';
		public $update_post_term_cache = '';
		public $orderby_param = '';
		public $orderby_meta_key = '';
		public $order_param = '';
		public $number_of_items = '';		
		public $custom_fields = '';		
		public $custom_field_relation_param = '';
		public $post_status = 'publish'; // @since 2.8.2
		public $authors_prefixing = 'false'; //@since 2.8.6
		public $taxonomy_relation_param = 'AND'; //@since 2.8.6
		public $order_meta_type = ''; //@since 3.0
		
		/**
		 * Layout settings */
		
		public $main_layout = 'mu-cd';	
		public $layout_type = 'full_width';
		public $layout_fixed_width = '700';
		public $layout_fixed_height = '600';
		
		/**
		 * Map settings */
		 
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
		public $markerAnimation = 'pulsating_circle'; // @since 2.5		
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
		 * Map style settings */
		
		public $style_option = 'progress-map';
		public $map_style = 'google-map';	
		public $js_style_array = '';
		public $custom_style_name = 'Custom style'; //@since 2.6.1
		 		 		
		/**
		 * Carousel settings */
				
		public $show_carousel = 'true';
		public $carousel_mode = 'false';
		public $carousel_scroll = '1';
		public $carousel_animation = 'fast';
		public $carousel_easing = 'linear';
		public $carousel_auto = '0';
		public $carousel_wrap = 'circular';	
		public $scrollwheel_carousel = 'false';	
		public $touchswipe_carousel = 'false';
		public $move_carousel_on = array('marker_click', 'marker_hover', 'infobox_hover');	
		public $carousel_map_zoom = '12';
		
		/**
		 * Carousel style */
		 
		public $carousel_css = '';	
		public $arrows_background = '#fff';	
		public $horizontal_left_arrow_icon = '';
		public $horizontal_right_arrow_icon = '';	
		public $vertical_top_arrow_icon = '';
		public $vertical_bottom_arrow_icon = '';
		public $items_background = '#fff';	
		public $items_hover_background = '#fbfbfb';	
		
		/**
		 * Carousel items settings */
		 
		public $items_view = 'listview';
		public $horizontal_item_css = '';
		public $horizontal_title_css = '';
		public $horizontal_details_css = '';
		public $vertical_item_css = '';
		public $vertical_title_css = '';
		public $vertical_details_css = '';
		public $horizontal_item_size = '454,150'; //@updated 2.8
		public $horizontal_item_width = '454'; //@updated 2.8
		public $horizontal_item_height = '150'; //@updated 2.8
		public $horizontal_image_size = '204,150'; //@updated 2.8
		public $horizontal_img_width = '204'; //@updated 2.8
		public $horizontal_img_height = '150'; //@updated 2.8
		public $horizontal_details_size = '250,150'; //@updated 2.8
		public $horizontal_details_width = '250'; //@updated 2.8
		public $horizontal_details_height = '150'; //@updated 2.8		
		public $vertical_item_size = '204,290'; //@updated 2.8
		public $vertical_item_width = '204'; //@updated 2.8
		public $vertical_item_height =  '290'; //@updated 2.8
		public $vertical_image_size = '204,120'; //@updated 2.8
		public $vertical_img_width = '204'; //@updated 2.8
		public $vertical_img_height = '120'; //@updated 2.8
		public $vertical_details_size = '204,170'; //@updated 2.8
		public $vertical_details_width = '204'; //@updated 2.8
		public $vertical_details_height = '170'; //@updated 2.8
		public $show_details_btn = 'yes';
		public $items_title = '';
		public $click_on_title = 'no'; //@since 2.5	
		public $external_link = 'same_window'; //@since 2.5
		public $items_details = '';
		public $details_btn_css = '';
		public $details_btn_text = 'More';	
		public $ellipses = 'yes';							
		
		/**
		 * Posts count settings */
		 
		public $show_posts_count = 'no';
		public $posts_count_clause = '[posts_count] Posts';
		public $posts_count_color = '#333333';
		public $posts_count_style = '';
		
		/**
		 * Marker categories settings */
		 
		public $marker_cats_settings = 'false';
		public $marker_categories_taxonomy = '';
		
		/**
		 * Faceted search settings */
		 
		public $faceted_search_option = 'false';
		public $faceted_search_multi_taxonomy_option = 'true';
		public $faceted_search_input_skin = 'polaris';
		public $faceted_search_input_color = 'blue';
		public $faceted_search_icon = '';
		public $faceted_search_css = '';
		public $faceted_search_drag_map = 'no'; //@since 2.8.2
		public $faceted_search_autocheck = 'false'; //@since 3.0
		public $faceted_autocheck_terms = 'false'; //@since 3.0
		public $faceted_search_display_status = 'close'; //@since 3.0
		
		/**
		 * Search form settings */
		 
		public $search_form_option = 'false';
		public $sf_min_search_distances = '3';
		public $sf_max_search_distances = '50';
		public $sf_distance_unit = 'metric';
		public $address_placeholder = 'Enter City & Province, or Postal code';
		public $slider_label = 'Expand the search area up to';
		public $no_location_msg = 'We could not find any location';
		public $bad_address_msg = 'We could not understand the location';
		public $bad_address_sug_1 = '- Make sure all street and city names are spelled correctly.';
		public $bad_address_sug_2 = '- Make sure your address includes a city and state.';
		public $bad_address_sug_3 = '- Try entering a zip code.';		
		public $submit_text = 'Search';
		public $search_form_icon = '';		
		public $search_form_bg_color = 'rgba(255,255,255,0.95)';
		public $circle_option = 'true';
		public $fillColor = '#189AC9';
		public $fillOpacity = '0.1';
		public $strokeColor = '#189AC9';				
		public $strokeOpacity = '1';
		public $strokeWeight = '1';		
		public $sf_display_status = 'close'; //@since 3.0	
		public $sf_edit_circle = 'true'; //@since 3.0
					
		/**
		 * infobox settings */
		 
		public $show_infobox = 'true'; // @since 2.5		
		public $infobox_type = 'rounded_bubble'; // @since 2.5		
		public $infobox_display_event = 'onload'; // @since 2.5		
		public $infobox_external_link = 'same_window'; // @since 2.5	
		public $remove_infobox_on_mouseout = 'false'; //@since 2.7.4	
				
		/**
		 * KML Layers */
		 
		public $use_kml = 'false'; //@since 2.7
		public $kml_layers = ''; //@since 2.7
		
		/**
		 * Overlays: Polyline */
		 
		public $draw_polyline = 'false'; //@since 2.7
		public $polylines = ''; //@since 2.7		
		
		/**
		 * Overlays: Polygon */
		 
		public $draw_polygon = 'false'; //@since 2.7
		public $polygons = ''; //@since 2.7		
		
		/**
		 * Zoom to country 
		 * @since 3.0 */				
		
		public $zoom_country_option = 'false'; 
		public $zoom_country_display_status = 'close';
		public $country_zoom_or_autofit = 'autofit';
		public $country_zoom_level = '12';
		public $countries_btn_icon = '';
		public $countries_display_lang = 'en';
		public $country_flag = 'true';
		public $countries = array();
		
		/**
		 * Nearby points of interest
		 * @since 3.2 */
		
		public $nearby_places_option = 'false';
		public $np_proximities_display_status = 'close';
		public $np_distance_unit = 'METRIC';
		public $np_radius = '50000';
		public $np_circle_option = 'true';
		public $np_edit_circle = 'true';
		public $np_marker_type = 'default';
		public $show_proximity_icon = 'true';
		public $np_proximities = array();						
		 
		/**
		 * Customize */
		
		public $custom_css = '';
		public $map_horizontal_elements_order = array(); //@since 3.2
		public $map_vertical_elements_order = array(); //@since 3.2
		
		function __construct($atts = array()){

			if (!class_exists('CSProgressMap'))
				return; 
				
			$CSProgressMap = CSProgressMap::this();
			
			extract( wp_parse_args( $atts, array(
				'init' => false, 
				'plugin_settings' => array(),
				'map_settings' => array(), 
				'metafield_prefix' => '',
				'object_type' => '',
			)));

			self::$_this = $this;       
			
			$this->plugin_path = $CSProgressMap->cspm_plugin_path;
			$this->plugin_url = $CSProgressMap->cspm_plugin_url;
				
			$this->map_styles_file = $this->plugin_path.'inc/cspm-map-styles.php';
			
			$this->metafield_prefix = $metafield_prefix;
			$this->object_type = $object_type;
				
			/**
			 * Get plugin settings */
			 
			$this->plugin_settings = $plugin_settings;
			
			if(!$init){
				
				/**
				 * Get all map settings */
				 
				$this->map_settings = $map_settings;
				
				/**
				 * [@map_object_id] | The ID of the map
				 * @since 3.0 */
				 
				$this->map_object_id = isset($this->map_settings['map_object_id']) ? $this->map_settings['map_object_id'] : '';
				
				if(!is_admin()){
				
					/**
					 * Plugin settings */
					
					$this->outer_links_field_name = $this->cspm_get_plugin_setting('outer_links_field_name');
					$this->use_with_wpml = $this->cspm_get_plugin_setting('use_with_wpml');
					$this->combine_files = $this->cspm_get_plugin_setting('combine_files');
					$this->remove_bootstrap = $this->cspm_get_plugin_setting('remove_bootstrap');
					$this->remove_google_fonts = $this->cspm_get_plugin_setting('remove_google_fonts');
					$this->remove_gmaps_api = $this->cspm_get_plugin_setting('remove_gmaps_api', array());

					/**
					 * Query settings */
					 
					$this->post_type = $this->cspm_get_map_option('post_type', ''); 
					$this->number_of_items = $this->cspm_get_map_option('number_of_items');		
					$this->custom_fields = unserialize($this->cspm_get_map_option('custom_fields', serialize(array())));
					$this->custom_field_relation_param = $this->cspm_get_map_option('custom_field_relation_param');
					$this->post_in = unserialize($this->cspm_get_map_option('post_in', serialize(array())));
					$this->post_not_in = unserialize($this->cspm_get_map_option('post_not_in', serialize(array())));
					$this->cache_results = $this->cspm_get_map_option('cache_results');
					$this->update_post_meta_cache = $this->cspm_get_map_option('update_post_meta_cache');
					$this->update_post_term_cache = $this->cspm_get_map_option('update_post_term_cache');
					$this->orderby_param = $this->cspm_get_map_option('orderby_param');
					$this->orderby_meta_key = $this->cspm_get_map_option('orderby_meta_key');
					$this->order_param = $this->cspm_get_map_option('order_param');
					$this->authors_prefixing = $this->cspm_get_map_option('authors_prefixing', 'false'); //@since 2.8.6
					$this->authors = unserialize($this->cspm_get_map_option('authors', serialize(array())));
					$this->taxonomy_relation_param = $this->cspm_get_map_option('taxonomy_relation_param', 'AND'); //@since 2.8.6								
					$this->post_status = unserialize($this->cspm_get_map_option('items_status', serialize('publish')));
					$this->order_meta_type = $this->cspm_get_map_option('order_meta_type'); //@since 3.0
						
					/**
					 * Layout settings */
					 
					$this->main_layout = $this->cspm_get_map_option('main_layout', 'mu-cd');	
					$this->layout_type = $this->cspm_get_map_option('layout_type', 'full_width');
					$this->layout_fixed_width = $this->cspm_get_map_option('layout_fixed_width', '700');
					$this->layout_fixed_height = $this->cspm_get_map_option('layout_fixed_height', '600');
						
					/**
					 * Map settings */
					 
					$this->center = $this->cspm_get_map_option('map_center', '51.53096,-0.121064');			
						$this->wrong_center_point = (strpos($this->center, ',') !== false) ? false : true;
										
					$this->initial_map_style = $this->cspm_get_map_option('initial_map_style', 'ROADMAP');
					$this->zoom = $this->cspm_get_map_option('map_zoom', '12');
					$this->useClustring = $this->cspm_get_map_option('useClustring', 'true');
					$this->gridSize = $this->cspm_get_map_option('gridSize', '60');
					$this->mapTypeControl = $this->cspm_get_map_option('mapTypeControl', 'true');
					$this->streetViewControl = $this->cspm_get_map_option('streetViewControl', 'false');
					$this->scrollwheel = $this->cspm_get_map_option('scrollwheel', 'false');
					$this->zoomControl = $this->cspm_get_map_option('zoomControl', 'true');
					$this->zoomControlType = $this->cspm_get_map_option('zoomControlType', 'customize');
					$this->marker_icon = $this->cspm_get_map_option('marker_icon', $this->plugin_url.'img/pin-blue.png');			
					$this->big_cluster_icon = $this->cspm_get_map_option('big_cluster_icon', $this->plugin_url.'img/big-cluster.png');
					$this->medium_cluster_icon = $this->cspm_get_map_option('medium_cluster_icon', $this->plugin_url.'img/medium-cluster.png');
					$this->small_cluster_icon = $this->cspm_get_map_option('small_cluster_icon', $this->plugin_url.'img/small-cluster.png'); 
					$this->cluster_text_color = $this->cspm_get_map_option('cluster_text_color', '#ffffff');			
					$this->zoom_in_icon = $this->cspm_get_map_option('zoom_in_icon', $this->plugin_url.'img/addition-sign.png');	
					$this->zoom_in_css = $this->cspm_get_map_option('zoom_in_css');	
					$this->zoom_out_icon = $this->cspm_get_map_option('zoom_out_icon', $this->plugin_url.'img/minus-sign.png');	
					$this->zoom_out_css = $this->cspm_get_map_option('zoom_out_css');
					$this->defaultMarker = $this->cspm_get_map_option('defaultMarker');
					$this->retinaSupport = $this->cspm_get_map_option('retinaSupport', 'false');
					$this->geoIpControl = $this->cspm_get_map_option('geoIpControl', 'false');			
					$this->markerAnimation = $this->cspm_get_map_option('markerAnimation', 'pulsating_circle'); // @since 2.5
					$this->marker_anchor_point_option = $this->cspm_get_map_option('marker_anchor_point_option', 'disable'); // @since 2.6.1
					$this->marker_anchor_point = $this->cspm_get_map_option('marker_anchor_point', ''); // @since 2.6.1				
					$this->map_draggable = $this->cspm_get_map_option('map_draggable', 'true'); // @since 2.6.3				
					$this->max_zoom = $this->cspm_get_map_option('max_zoom', 19); // @since 2.6.3
					$this->min_zoom = $this->cspm_get_map_option('min_zoom', 0); // @since 2.6.3
					$this->zoom_on_doubleclick = $this->cspm_get_map_option('zoom_on_doubleclick', 'false'); // @since 2.6.3												
					$this->autofit = $this->cspm_get_map_option('autofit', 'false'); // @since 2.7												
					$this->traffic_layer = $this->cspm_get_map_option('traffic_layer', 'false'); // @since 2.7
					$this->transit_layer = $this->cspm_get_map_option('transit_layer', 'false'); // @since 2.7.4
					$this->show_user = $this->cspm_get_map_option('show_user', 'false'); // @since 2.7.4
					$this->user_marker_icon = $this->cspm_get_map_option('user_marker_icon', ''); // @since 2.7.4
					$this->user_map_zoom = $this->cspm_get_map_option('user_map_zoom', '12'); // @since 2.7.4
					$this->user_circle = $this->cspm_get_map_option('user_circle', '0'); // @since 2.7.4
					$this->user_circle_fillColor = $this->cspm_get_map_option('user_circle_fillColor', '#189AC9'); // @since 3.0
					$this->user_circle_fillOpacity = $this->cspm_get_map_option('user_circle_fillOpacity', '0.1'); // @since 3.0
					$this->user_circle_strokeColor = $this->cspm_get_map_option('user_circle_strokeColor', '#189AC9'); // @since 3.0				
					$this->user_circle_strokeOpacity = $this->cspm_get_map_option('user_circle_strokeOpacity', '1'); // @since 3.0
					$this->user_circle_strokeWeight = $this->cspm_get_map_option('user_circle_strokeWeight', '1'); // @since 3.0						
					$this->recenter_map = $this->cspm_get_map_option('recenter_map', 'true'); // @since 3.0						
					
					/**
					 * KML Layers 
					 * @since 2.7 */
					 
					$this->use_kml = $this->cspm_get_map_option('use_kml', 'false');
					$this->kml_layers = unserialize($this->cspm_get_map_option('kml_layers', ''));	
				
					/**
					 * Overlays: Polyline
					 * @since 2.7 */
	
					$this->draw_polyline = $this->cspm_get_map_option('draw_polyline', 'false');
					$this->polylines = unserialize($this->cspm_get_map_option('polylines', ''));				
					
					/**
					 * Overlays: Polygon
					 * @since 2.7 */
	
					$this->draw_polygon = $this->cspm_get_map_option('draw_polygon', 'false');
					$this->polygons = unserialize($this->cspm_get_map_option('polygons', ''));				
				
					/**
					 * Infobox settings
					 * @since 2.5 */
					
					$this->show_infobox = $this->cspm_get_map_option('show_infobox', 'true');
					$this->infobox_type = $this->cspm_get_map_option('infobox_type', 'rounded_bubble');
					$this->infobox_display_event = $this->cspm_get_map_option('infobox_display_event', 'onload');
					$this->infobox_external_link = $this->cspm_get_map_option('infobox_external_link', 'same_window');
					$this->remove_infobox_on_mouseout = $this->cspm_get_map_option('remove_infobox_on_mouseout', 'false'); //@since 2.7.4
					
					/**
					 * Carousel settings */
					 
					$this->show_carousel = $this->cspm_get_map_option('show_carousel', 'true');
					$this->carousel_scroll = $this->cspm_get_map_option('carousel_scroll', '1');
					$this->carousel_animation = $this->cspm_get_map_option('carousel_animation', 'fast');
					$this->carousel_easing = $this->cspm_get_map_option('carousel_easing', 'linear');
					$this->carousel_auto = $this->cspm_get_map_option('carousel_auto', '0');
					$this->carousel_mode = $this->cspm_get_map_option('carousel_mode', 'false');	
					$this->carousel_wrap = $this->cspm_get_map_option('carousel_wrap', 'circular');	
					$this->scrollwheel_carousel = $this->cspm_get_map_option('scrollwheel_carousel', 'false');	
					$this->touchswipe_carousel = $this->cspm_get_map_option('touchswipe_carousel', 'false');
					$this->carousel_map_zoom = $this->cspm_get_map_option('carousel_map_zoom', '12');
					$this->move_carousel_on = unserialize($this->cspm_get_map_option('move_carousel_on', serialize(array())));	
	
					/**
					 * Carousel style */
					 
					$this->carousel_css = $this->cspm_get_map_option('carousel_css');	
					$this->arrows_background = $this->cspm_get_map_option('arrows_background', '#fff');	
					$this->horizontal_left_arrow_icon = $this->cspm_get_map_option('horizontal_left_arrow_icon');	
					$this->horizontal_right_arrow_icon = $this->cspm_get_map_option('horizontal_right_arrow_icon');	
					$this->vertical_top_arrow_icon = $this->cspm_get_map_option('vertical_top_arrow_icon');	
					$this->vertical_bottom_arrow_icon = $this->cspm_get_map_option('vertical_bottom_arrow_icon');	
					$this->items_background = $this->cspm_get_map_option('items_background', '#fff');	
					$this->items_hover_background = $this->cspm_get_map_option('items_hover_background', '#fbfbfb');	
						
					/**
					 * Carousel Items Settings */
					 
					$this->items_view = $this->cspm_get_map_option('items_view', 'listview');
					$this->show_details_btn = $this->cspm_get_map_option('show_details_btn', 'yes');
					$this->click_on_title = $this->cspm_get_map_option('click_on_title');
					$this->external_link = $this->cspm_get_map_option('external_link', 'same_window');
					$this->details_btn_css = $this->cspm_get_map_option('details_btn_css');
					$this->details_btn_text = $this->cspm_get_map_option('details_btn_text', esc_html__('More', 'cspm'));
					$this->items_title = $this->cspm_get_map_option('items_title');
					$this->items_details = $this->cspm_get_map_option('items_details');
					$this->ellipses = $this->cspm_get_map_option('ellipses', 'yes');
					
						/**
						 * Horizontal */
						 
						$this->horizontal_item_css = $this->cspm_get_map_option('horizontal_item_css');
						$this->horizontal_title_css = $this->cspm_get_map_option('horizontal_title_css');
						$this->horizontal_details_css = $this->cspm_get_map_option('horizontal_details_css');
		
						$this->horizontal_item_size = $this->cspm_get_map_option('horizontal_item_size', '454,150');
							
							if($explode_horizontal_item_size = explode(',', $this->horizontal_item_size)){
								$this->horizontal_item_width = $this->cspm_setting_exists(0, $explode_horizontal_item_size, '454');
								$this->horizontal_item_height = $this->cspm_setting_exists(1, $explode_horizontal_item_size, '150');
							}else{
								$this->horizontal_item_width = '454';
								$this->horizontal_item_height = '150';
							}
						
						$this->horizontal_image_size = $this->cspm_get_map_option('horizontal_image_size', '204,150');
							
							if($explode_horizontal_img_size = explode(',', $this->horizontal_image_size)){
								$this->horizontal_img_width = $this->cspm_setting_exists(0, $explode_horizontal_img_size, '204');
								$this->horizontal_img_height = $this->cspm_setting_exists(1, $explode_horizontal_img_size, '150');
							}else{
								$this->horizontal_img_width = '204';
								$this->horizontal_img_height = '150';
							}
						
						$this->horizontal_details_size = $this->cspm_get_map_option('horizontal_details_size', '250,150');
							
							if($explode_horizontal_details_size = explode(',', $this->horizontal_details_size)){
								$this->horizontal_details_width = $this->cspm_setting_exists(0, $explode_horizontal_details_size, '250');
								$this->horizontal_details_height = $this->cspm_setting_exists(1, $explode_horizontal_details_size, '150');
							}else{
								$this->horizontal_details_width = '250';
								$this->horizontal_details_height = '150';
							}
						
						/**
						 * Vertical */
					
						$this->vertical_item_css = $this->cspm_get_map_option('vertical_item_css');
						$this->vertical_title_css = $this->cspm_get_map_option('vertical_title_css');
						$this->vertical_details_css = $this->cspm_get_map_option('vertical_details_css');
						
						$this->vertical_item_size = $this->cspm_get_map_option('vertical_item_size', '204,290');
							
							if($explode_vertical_item_size = explode(',', $this->vertical_item_size)){
								$this->vertical_item_width = $this->cspm_setting_exists(0, $explode_vertical_item_size, '204');
								$this->vertical_item_height =  $this->cspm_setting_exists(1, $explode_vertical_item_size, '290');
							}else{
								$this->vertica_item_width = '204';
								$this->vertica_item_height = '290';
							}
							
						$this->vertical_image_size = $this->cspm_get_map_option('vertical_image_size', '204,120');			
							
							if($explode_vertical_img_size = explode(',', $this->vertical_image_size)){
								$this->vertical_img_width = $this->cspm_setting_exists(0, $explode_vertical_img_size, '204');
								$this->vertical_img_height = $this->cspm_setting_exists(1, $explode_vertical_img_size, '120');
							}else{
								$this->vertical_img_width = '204';
								$this->vertical_img_height = '120';
							}
							 
						$this->vertical_details_size = $this->cspm_get_map_option('vertical_details_size', '204,170');
							
							if($explode_vertical_details_size = explode(',', $this->vertical_details_size)){
								$this->vertical_details_width = $this->cspm_setting_exists(0, $explode_vertical_details_size, '204');
								$this->vertical_details_height = $this->cspm_setting_exists(1, $explode_vertical_details_size, '170');
							}else{
								$this->vertical_details_width = '204';
								$this->vertical_details_height = '170';
							}
		
					/**
					 * Posts count settings */
					 
					$this->show_posts_count = $this->cspm_get_map_option('show_posts_count', 'no');
					$this->posts_count_clause = $this->cspm_get_map_option('posts_count_clause', '[posts_count] Posts');
					$this->posts_count_color = $this->cspm_get_map_option('posts_count_color', '#333333');
					$this->posts_count_style = $this->cspm_get_map_option('posts_count_style');
		
					/**
					 * Marker categories settings */
					 
					$this->marker_cats_settings = $this->cspm_get_map_option('marker_cats_settings', 'false');
					$this->marker_categories_taxonomy = $this->cspm_get_map_option('marker_categories_taxonomy');

					/**
					 * Faceted search settings */
					 
					$this->faceted_search_option = $this->cspm_get_map_option('faceted_search_option', 'false');
					$this->faceted_search_terms = unserialize($this->cspm_get_map_option('faceted_search_taxonomy_'.$this->marker_categories_taxonomy, serialize(array())));
					$this->faceted_search_multi_taxonomy_option = $this->cspm_get_map_option('faceted_search_multi_taxonomy_option', 'true');
					$this->faceted_search_input_skin = $this->cspm_get_map_option('faceted_search_input_skin', 'polaris');
					$this->faceted_search_input_color = $this->cspm_get_map_option('faceted_search_input_color', 'blue');
					$this->faceted_search_icon = $this->cspm_get_map_option('faceted_search_icon', $this->plugin_url.'img/filter.png');
					$this->faceted_search_css = $this->cspm_get_map_option('faceted_search_css');
					$this->faceted_search_drag_map = $this->cspm_get_map_option('faceted_search_drag_map', 'no'); //@since 2.8.2
					$this->faceted_search_autocheck = $this->cspm_get_map_option('faceted_search_autocheck', 'false'); //@since 3.0
					$this->faceted_autocheck_terms = unserialize($this->cspm_get_map_option('faceted_search_autocheck_taxonomy_'.$this->marker_categories_taxonomy, serialize(array()))); //@since 3.0
					$this->faceted_search_display_status = $this->cspm_get_map_option('faceted_search_display_status', 'close'); //@since 3.0
										
					/**
					 * Search form settings */
					 
					$this->search_form_option = $this->cspm_get_map_option('search_form_option', 'false');
					$this->sf_min_search_distances = $this->cspm_get_map_option('sf_min_search_distances', '3');
					$this->sf_max_search_distances = $this->cspm_get_map_option('sf_max_search_distances', '50');
					$this->sf_distance_unit = $this->cspm_get_map_option('sf_distance_unit', 'metric');
					$this->address_placeholder = $this->cspm_get_map_option('sf_address_placeholder', esc_html__('Enter City & Province, or Postal code', 'cspm'));
					$this->slider_label = $this->cspm_get_map_option('sf_slider_label', esc_html__('Expand the search area up to', 'cspm'));
					$this->no_location_msg = $this->cspm_get_map_option('sf_no_location_msg', esc_html__('We could not find any location', 'cspm'));
					$this->bad_address_msg = $this->cspm_get_map_option('sf_bad_address_msg', esc_html__('We could not understand the location', 'cspm'));
					$this->bad_address_sug_1 = $this->cspm_get_map_option('sf_bad_address_sug_1', esc_html__('- Make sure all street and city names are spelled correctly.', 'cspm'));
					$this->bad_address_sug_2 = $this->cspm_get_map_option('sf_bad_address_sug_2', esc_html__('- Make sure your address includes a city and state.', 'cspm'));
					$this->bad_address_sug_3 = $this->cspm_get_map_option('sf_bad_address_sug_3', esc_html__('- Try entering a zip code.', 'cspm'));
					$this->submit_text = $this->cspm_get_map_option('sf_submit_text', esc_html__('Find it', 'cspm'));
					$this->search_form_icon = $this->cspm_get_map_option('sf_search_form_icon', $this->plugin_url.'img/loup.png');
					$this->search_form_bg_color = $this->cspm_get_map_option('sf_search_form_bg_color', 'rgba(255,255,255,1)');
					$this->circle_option = $this->cspm_get_map_option('sf_circle_option', 'true');
					$this->fillColor = $this->cspm_get_map_option('sf_fillColor', '#189AC9');
					$this->fillOpacity = $this->cspm_get_map_option('sf_fillOpacity', '0.1');
					$this->strokeColor = $this->cspm_get_map_option('sf_strokeColor', '#189AC9');				
					$this->strokeOpacity = $this->cspm_get_map_option('sf_strokeOpacity', '1');
					$this->strokeWeight = $this->cspm_get_map_option('sf_strokeWeight', '1');
					$this->sf_display_status = $this->cspm_get_map_option('sf_display_status', 'close');						
					$this->sf_edit_circle = $this->cspm_get_map_option('sf_edit_circle', 'true'); //@since 3.2
					
					/**
					 * map styles section */
					 
					$this->style_option = $this->cspm_get_map_option('style_option', 'progress-map');
					$this->map_style = $this->cspm_get_map_option('map_style', 'google-map');
					$this->js_style_array = $this->cspm_get_map_option('js_style_array', '');
					$this->custom_style_name = $this->cspm_get_map_option('custom_style_name', 'Custom style'); //@since 2.6.1
		
					/**
					 * Zoom to country 
					 * @since 3.0 */				
					
					$this->zoom_country_option = $this->cspm_get_map_option('zoom_country_option', 'false');
					$this->zoom_country_display_status = $this->cspm_get_map_option('zoom_country_display_status', 'close');
					$this->country_zoom_or_autofit = $this->cspm_get_map_option('country_zoom_or_autofit', 'autofit');
					$this->country_zoom_level = $this->cspm_get_map_option('country_zoom_level', '12');	
					$this->countries_btn_icon = $this->cspm_get_map_option('countries_btn_icon', $this->plugin_url.'img/continents.png');	
					$this->country_flag = $this->cspm_get_map_option('show_country_flag', '12');					
					$this->countries_display_lang = $this->cspm_get_map_option('country_display_language', 'en');
					$this->countries = unserialize($this->cspm_get_map_option('countries', serialize(array())));
		
					/**
					 * Nearby points of interest
					 * @since 3.2 */
					
					$this->nearby_places_option = $this->cspm_get_map_option('nearby_places_option', 'false');
					$this->np_proximities_display_status = $this->cspm_get_map_option('np_proximities_display_status', 'close');
					$this->np_distance_unit = $this->cspm_get_map_option('np_distance_unit', 'METRIC');
					$this->np_radius = $this->cspm_get_map_option('np_radius', '50000');
					$this->np_circle_option =  $this->cspm_get_map_option('np_circle_option', 'true');
					$this->np_edit_circle =  $this->cspm_get_map_option('np_edit_circle', 'true');	
					$this->np_marker_type =  $this->cspm_get_map_option('np_marker_type', 'default');				
					$this->show_proximity_icon = $this->cspm_get_map_option('show_proximity_icon', 'true');
					$this->np_proximities = unserialize($this->cspm_get_map_option('np_proximities', serialize(array())));					
		 
					/**
					 * Custom CSS */
					
					$this->custom_css = $this->cspm_get_map_option('custom_css', '');
					$this->map_horizontal_elements_order = unserialize($this->cspm_get_map_option('map_horizontal_elements_order', serialize(array('zoom_country', 'search_form', 'faceted_search', 'proximities')))); //@since 3.2
					$this->map_vertical_elements_order = unserialize($this->cspm_get_map_option('map_vertical_elements_order', serialize(array('recenter_map', 'geo')))); //@since 3.2
					
					if(empty($this->map_object_id)){
				 	
						/** 
						 * Just in case the map ID wasn't defined in the shortcode.
						 * We'll make sure to disable the following features/options just in case! */
						
						$this->faceted_search_option = $this->show_carousel = $this->search_form_option = 'false';
					
					}
					
				}
		
			}
			
		}
		
	
		static function this(){
			
			return self::$_this;
			
		}
		
		
		function cspm_hooks(){

			/**
			 * "Progress Map" Ajax functions */
						
			add_action('wp_ajax_cspm_load_carousel_item', array(&$this, 'cspm_load_carousel_item'));
			add_action('wp_ajax_nopriv_cspm_load_carousel_item', array(&$this, 'cspm_load_carousel_item'));
			
			add_action('wp_ajax_cspm_infobox_content', array(&$this, 'cspm_infobox_content'));
			add_action('wp_ajax_nopriv_cspm_infobox_content', array(&$this, 'cspm_infobox_content'));
			
			add_action('wp_ajax_cspm_load_clustred_markers_list', array(&$this, 'cspm_load_clustred_markers_list'));
			add_action('wp_ajax_nopriv_cspm_load_clustred_markers_list', array(&$this, 'cspm_load_clustred_markers_list'));
		
			/**
			 * Add main map shortcode */
			 
			add_shortcode('cspm_main_map', array(&$this, 'cspm_main_map_shortcode'));
			add_shortcode('codespacing_progress_map', array(&$this, 'cspm_main_map_shortcode'));//Deprecated @since 3.0
			
		}
		
		
		/**
		 * Get an option from a map options
		 *
		 * @since 3.0 
		 */
		function cspm_get_map_option($option_id, $default_value = ''){
			
			/**
			 * We'll check if the default settings can be found in the array containing the "(shared) plugin settings".
			 * If found, we'll use it. If not found, we'll use the one in [@default_value] instead. */
			 
			$default = $this->cspm_setting_exists($option_id, $this->plugin_settings, $default_value);
			
			return $this->cspm_setting_exists($this->metafield_prefix.'_'.$option_id, $this->map_settings, $default);
			
		}
		
		
		/**
		 * Get the value of a setting
		 *
		 * @since 3.0 
		 */
		function cspm_get_plugin_setting($setting_id, $default_value = ''){
			
			return $this->cspm_setting_exists($setting_id, $this->plugin_settings, $default_value);			
			
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
		 * This will load the styles needed by our shortcode based on its settings
		 *
		 * @since 3.0
		 */
		function cspm_enqueue_styles($combine_files = ''){
			
			$combine_files = (empty($combine_files)) ? $this->combine_files : $combine_files;			
			
			do_action('cspm_before_enqueue_style');
							
			/**
			 * Font Style */
			
			if($this->remove_google_fonts == 'enable')  	
				wp_enqueue_style('cspm_font');

			/**
			 * icheck
			 * Note: Loaded only when using the faceted seach feature */
							
			if($this->faceted_search_option == 'true'){
				
				$icheck_skin = $this->faceted_search_input_skin;
				
				if($this->faceted_search_input_skin != 'polaris' && $this->faceted_search_input_skin != 'futurico')
					$icheck_color = ($this->faceted_search_input_color != 'black') ? $this->faceted_search_input_color : $this->faceted_search_input_skin;
				else $icheck_color = $this->faceted_search_input_skin;
				
				wp_enqueue_style('cspm_icheck_'.$icheck_skin.'_'.$icheck_color.'_css');
				
			}
			
			if($combine_files == "combine"){
					
				wp_enqueue_style('cspm_combined_styles');
							
				$script_handle = 'cspm_combined_styles';			
				
			}else{
				
				/**
				 * Bootstrap */
				
				if($this->remove_bootstrap == 'enable')
					wp_enqueue_style('cspm_bootstrap_css');
				
				/**
				 * jCarousel
				 * Note: Loaded only when using the carousel feature */
				 
				if($this->show_carousel == 'true' && !empty($this->map_object_id))
					wp_enqueue_style('cspm_carousel_css');
					
				/**
				 * Infobox & Carousel loaders */
				 
				wp_enqueue_style('cspm_loading_css');				
				
				/**
				 * Custom Scroll bar
				 * Note: Loaded only when using the clustring feature and/or the faceted seach feature */
				 
				if($this->useClustring == 'true' || $this->faceted_search_option == 'true')
					wp_enqueue_style('cspm_mCustomScrollbar_css');
	
				/**
				 * Range Slider
				 * Note: Loaded only when using the search form feature */
								
				if($this->search_form_option == 'true' && !empty($this->map_object_id)){
					wp_enqueue_style('cspm_rangeSlider_css');
					wp_enqueue_style('cspm_rangeSlider_skin_css');
				}
					
				/** 
				 * Progress Map styles */
				
				wp_enqueue_style('cspm_nprogress_css');
				wp_enqueue_style('cspm_animate_css');
				wp_enqueue_style('cspm_map_css');
							
				$script_handle = 'cspm_map_css';			
				
			}
		
			/**
			 * Add custom header script */
			
			wp_add_inline_style($script_handle, $this->cspm_custom_map_style());			
			
			do_action('cspm_after_enqueue_style');

		}

		
		/** 
		 * This will build the custom CSS needed for this map
		 *
		 * @since 3.0
		 */
		function cspm_custom_map_style(){
				
			$custom_map_style = '';
			
			if($this->show_carousel == 'true' && !empty($this->map_object_id)){
				
				/**
				 * Carousel Style */
				
				if(!empty($this->carousel_css))
					$custom_map_style .= 'div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-container{'. $this->carousel_css.'}';
				
				/** 
				 * Carousel Items Style */
				 
				if($this->items_view == "listview"){
					
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .details_container{width:'.$this->horizontal_details_width.'px;height:'.$this->horizontal_details_height.'px;}';
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .item_img{width:'.$this->horizontal_img_width.'px; height:'.$this->horizontal_img_height.'px;float:left;}';
					
					$xy_position = ($this->carousel_mode == 'false') ? 'left' : 'right';
					
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .details_btn{'.$xy_position.':'.($this->horizontal_details_width-80).'px;top:'.($this->horizontal_details_height-50).'px;}';
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .details_title{width:'.$this->horizontal_details_width.'px;'.$this->horizontal_title_css.'}';
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .details_infos{width:'.$this->horizontal_details_width.'px;'.$this->horizontal_details_css.'}';
					
				}else{
					
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .details_container{width:'.$this->vertical_details_width.'px; height:'.$this->vertical_details_height.'px;}';
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .item_img{width:'.$this->vertical_img_width.'px;height:'.$this->vertical_img_height.'px;}';
					
					$xy_position = ($this->carousel_mode == 'false') ? 'left' : 'right';
					
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .details_btn{'.$xy_position.':'.($this->vertical_details_width-80).'px;top:'.($this->vertical_details_height-50).'px;}';
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .details_title{width:'.$this->vertical_details_width.'px;'.$this->vertical_title_css.'}';
					$custom_map_style .= 'ul#codespacing_progress_map_carousel_map'.$this->map_object_id.' .details_infos{width:'.$this->vertical_details_width.'px;'.$this->vertical_details_css.'}';
					
				}
				
				/**
				 * Horizontal Right Arrow CSS Style */
				
				if(!empty($this->horizontal_right_arrow_icon)){
					
					$custom_map_style .= 'div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-horizontal, div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-horizontal:hover,.jcarousel-skin-default .jcarousel-next-horizontal:focus{background-image: url('.$this->horizontal_right_arrow_icon.') !important;}';
				
				}
				
				/**
				 * Horizontal Left Arrow CSS Style */
				
				if(!empty($this->horizontal_left_arrow_icon)){
				
					$custom_map_style .= 'div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-horizontal, div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-horizontal:hover,.jcarousel-skin-default .jcarousel-prev-horizontal:focus{background-image: url('.$this->horizontal_left_arrow_icon.') !important;}';
					
				}
				
				/**
				 * Vertical Top Arrow CSS Style */
				
				if(!empty($this->vertical_top_arrow_icon)){
							
					$custom_map_style .= 'div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-vertical, div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-vertical:hover,.jcarousel-skin-default .jcarousel-prev-vertical:focus,.jcarousel-skin-default .jcarousel-prev-vertical:active{background-image: url('.$this->vertical_top_arrow_icon.') !important;}';
				
				}
		
				/**
				 * Vertical Bottom Arrow CSS Style */
				
				if(!empty($this->vertical_bottom_arrow_icon)){ 
					
					$custom_map_style .= 'div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-vertical, div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-vertical:hover,.jcarousel-skin-default .jcarousel-next-vertical:focus,.jcarousel-skin-default .jcarousel-next-vertical:active{background-image: url('.$this->vertical_bottom_arrow_icon.') !important;}';
						
				}
										
				/**
				 * Custom Vertical Carousel CSS */
				
				$custom_map_style .= 'div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-container-vertical{height:'.$this->layout_fixed_height.'px !important;}';
											
				/**
				 * Arrows background color */
				 
				$background_color = $this->arrows_background;
				if($background_color != '#fff' && $background_color != '#ffffff'){
					$custom_map_style .= 'div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-horizontal,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-horizontal,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-direction-rtl .jcarousel-next-horizontal, 
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-horizontal:hover,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-horizontal:focus,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-direction-rtl .jcarousel-prev-horizontal,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-horizontal:hover,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-horizontal:focus,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-direction-rtl .jcarousel-next-vertical,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-vertical,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-vertical:hover,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-next-vertical:focus,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-direction-rtl .jcarousel-prev-vertical,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-vertical,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-vertical:hover,
					div#cspm_carousel_container[data-map-id=map'.$this->map_object_id.'] .jcarousel-skin-default .jcarousel-prev-vertical:focus{background-color:'.$background_color.';}';
				}
			}
			
			/**
			 * Zoom-In & Zoom-out CSS Style
			 * Note: This is used for the "Main maps"! */

			if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){
						
				$custom_map_style .= 'div.codespacing_progress_map_area[data-map-id=map'.$this->map_object_id.'] div[class^=codespacing_map_zoom_in]{'.$this->zoom_in_css.'}';
				$custom_map_style .= 'div.codespacing_progress_map_area[data-map-id=map'.$this->map_object_id.'] div[class^=codespacing_map_zoom_out]{'.$this->zoom_out_css.'}';
								
			}
			
			/**
			 * Posts count clause */
			 
			if($this->show_posts_count == 'yes' && !empty($this->map_object_id)){
				
				$custom_map_style .= 'div.codespacing_progress_map_area[data-map-id=map'.$this->map_object_id.'] div.number_of_posts_widget{color:'.$this->posts_count_color.';'.$this->posts_count_style.'}';
					
			}
			
			/**
			 * Faceted search CSS
			 * @updated 2.8 */

			if($this->faceted_search_option == 'true' && !empty($this->faceted_search_css)){
				
				$custom_map_style .= 'div[class^=faceted_search_container_map'.$this->map_object_id.']{background:'.$this->faceted_search_css.'}';
					
			}
			
			/**
			 * Search form CSS*/ 
						
			if($this->search_form_option == 'true'){ 
			
				if(!empty($this->search_form_bg_color))
					$custom_map_style .= 'div[class^=search_form_container_map'.$this->map_object_id.']{background:'.$this->search_form_bg_color.';}';
			
			}
			
			$custom_map_style .= $this->custom_css;
			
			return $custom_map_style;
				
		}
		
		
		/**
		 * This will build an array containing the map object ID and ...
		 * ... the options needed for this map to communicate with our JS file/functoins
		 *
		 * @since 3.0
		 */
		function cspm_localize_map_script(){

			/**
			 * Add text before and/or after the search field value
			 * @since 2.8 */
			 
			$before_search_address = apply_filters('cspm_before_search_address', '', $this->map_object_id);
			$after_search_address = apply_filters('cspm_after_search_address', '', $this->map_object_id);

			$map_script_args = array('map'.$this->map_object_id => array(
				
				/**
				 * All map settings */
				
				'map_object_id' => $this->map_object_id,
				'map_settings' => $this->map_settings,
								
				/**
				 * Query settings */
				
				'number_of_items' => $this->number_of_items,
				
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
				'big_cluster_size' => $this->cspm_get_image_size($this->cspm_get_image_path_from_url($this->big_cluster_icon), $this->retinaSupport),
				'medium_cluster_icon' => $this->medium_cluster_icon,
				'medium_cluster_size' => $this->cspm_get_image_size($this->cspm_get_image_path_from_url($this->medium_cluster_icon), $this->retinaSupport),
				'small_cluster_icon' => $this->small_cluster_icon,
				'small_cluster_size' => $this->cspm_get_image_size($this->cspm_get_image_path_from_url($this->small_cluster_icon), $this->retinaSupport),
				'cluster_text_color' => $this->cluster_text_color,
				'grid_size' => $this->gridSize,
				'retinaSupport' => $this->retinaSupport,
				'initial_map_style' => $this->initial_map_style,
				'markerAnimation' => $this->markerAnimation, //@since 2.5
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
				 * Carousel settings */
				 
				'items_view' => $this->items_view,
				'show_carousel' => $this->show_carousel,
				'carousel_scroll' => $this->carousel_scroll,
				'carousel_wrap' => $this->carousel_wrap,
				'carousel_auto' => $this->carousel_auto,
				'carousel_mode' => $this->carousel_mode,
				'carousel_animation' => $this->carousel_animation,
				'carousel_easing' => $this->carousel_easing,
				'carousel_map_zoom' => $this->carousel_map_zoom,
				'scrollwheel_carousel' => $this->scrollwheel_carousel,
				'touchswipe_carousel' => $this->touchswipe_carousel,
				
				/**
				 * Layout settings */
				
				'layout_fixed_height' => $this->layout_fixed_height,
				
				/**
				 * Carousel items settings */
				 
				'horizontal_item_css' => $this->horizontal_item_css,
				'horizontal_item_width' => $this->horizontal_item_width,
				'horizontal_item_height' => $this->horizontal_item_height,
				'vertical_item_css' => $this->vertical_item_css,
				'vertical_item_width' => $this->vertical_item_width,
				'vertical_item_height' => $this->vertical_item_height,			
				'items_background' => $this->items_background,
				'items_hover_background' => $this->items_hover_background,
				
				/**
				 * Faceted search settings */
				 
				'faceted_search_option' => $this->faceted_search_option,
				'faceted_search_multi_taxonomy_option' => $this->faceted_search_multi_taxonomy_option,
				'faceted_search_input_skin' => $this->faceted_search_input_skin,
				'faceted_search_input_color' => $this->faceted_search_input_color,
				'faceted_search_drag_map' => $this->faceted_search_drag_map, //@since 2.8.2
				
				/**
				 * Posts count settings */
				 
				'show_posts_count' => $this->show_posts_count,
				
				/**
				 * Search form settings */
				 
				'sf_circle_option' => $this->circle_option,
				'fillColor' => $this->fillColor,
				'fillOpacity' => str_replace(',', '.', $this->fillOpacity),
				'strokeColor' => $this->strokeColor,
				'strokeOpacity' => str_replace(',', '.', $this->strokeOpacity),
				'strokeWeight' => $this->strokeWeight,
				'search_form_option' => $this->search_form_option,
				'sf_edit_circle' => $this->sf_edit_circle, //@since 3.2
				
				/**
				 * Geotarget
				 * @since 2.8 */
				 
				'geo' => $this->geoIpControl,
				'show_user' => $this->show_user,
				'user_marker_icon' => $this->user_marker_icon,
				'user_map_zoom' => $this->user_map_zoom,
				'user_circle' => $this->user_circle,
				'user_circle_fillColor' => $this->user_circle_fillColor, //@since 3.0
				'user_circle_fillOpacity' => str_replace(',', '.', $this->user_circle_fillOpacity), //@since 3.0
				'user_circle_strokeColor' => $this->user_circle_strokeColor, //@since 3.0
				'user_circle_strokeOpacity' => str_replace(',', '.', $this->user_circle_strokeOpacity), //@since 3.0
				'user_circle_strokeWeight' => $this->user_circle_strokeWeight, //@since 3.0
				
				/**
				 * Search form settings */
				 
				'before_search_address' => $before_search_address, //@since 2.8, Add text before the search field value
				'after_search_address' => $after_search_address, //@since 2.8, Add text after the search field value
				
				/**
				 * Zoom to country 
				 * @since 3.0 */
				
				'country_zoom_or_autofit' => $this->country_zoom_or_autofit,
				'country_zoom_level' => $this->country_zoom_level,		
				
				/**
				 * Nearby points of interest 
				 * @since 3.2 */
				
				'nearby_places_option' => $this->nearby_places_option,	
				
			));
			
			return $map_script_args;
						
		}
		
		
		/**
		 * This will load the scripts needed by our shortcode based on its settings
		 *
		 * @since 3.0
		 */
		function cspm_enqueue_scripts($combine_files = ''){
			
			$combine_files = (empty($combine_files)) ? $this->combine_files : $combine_files;
			
			/**
			 * jQuery */
			 
			wp_enqueue_script('jquery');				 			

			do_action('cspm_before_enqueue_script');
			
			/**
			 * GMaps API */
			
			if(!in_array('disable_frontend', $this->remove_gmaps_api))				 
				wp_enqueue_script('cspm_google_maps_api');
			
			if($combine_files == "combine"){
			
				wp_enqueue_script('cspm_combined_scripts');	
				
				$localize_script_handle = 'cspm_combined_scripts';			
			
			}else{
				
				/**
				 * GMap3 jQuery Plugin */
				 
				wp_enqueue_script('cspm_gmap3_js');
					
				/**
				 * Live Query */
				 
				wp_enqueue_script('cspm_livequery_js');
				
				/**
				 * Marker Clusterer
				 * Note: Loaded only when using the clustering feature */
				 
				if($this->useClustring == 'true')
					wp_enqueue_script('cspm_markerclusterer_js');
					
				/**
				 * Touche Swipe
				 * Note: Loaded only when using the touchswipe feature */
				 
				if($this->touchswipe_carousel == 'true' && !empty($this->map_object_id))
					wp_enqueue_script('cspm_touchSwipe_js');
					
				/**
				 * jCarousel & jQuery Easing
				 * Note: Loaded only when using the carousel feature */
				 
				if($this->show_carousel == 'true' && !empty($this->map_object_id)){
					
					/**
					 * jCarousel */
					 
					wp_enqueue_script('cspm_jcarousel_js');
					
					/**
					 * jQuery Easing */
					 
					wp_enqueue_script('cspm_easing');
					
				}
					
				/**
				 * Custom Scroll bar & jQuery Mousewheel
				 * Note: Loaded only when using the clustring feature and/or the faceted seach feature */
				 
				if($this->useClustring == 'true' || $this->faceted_search_option == 'true'){
					
					/**
					 * Custom Scroll bar */
				 
					wp_enqueue_script('cspm_mCustomScrollbar_js');
					
					/**
					 * jQuery Mousewheel */
				 
					wp_enqueue_script('cspm_jquery_mousewheel_js');
					
				}
	
				/**
				 * icheck
				 * Note: Loaded only when using the faceted seach feature */
								
				if($this->faceted_search_option == 'true' && !empty($this->map_object_id))
					wp_enqueue_script('cspm_icheck_js');
					
				/**
				 * Progress Bar loader */
				 
				wp_enqueue_script('cspm_nprogress_js');
	
				/**
				 * Range Slider
				 * Note: Loaded when using the search form feature */
								
				if($this->search_form_option == 'true' && !empty($this->map_object_id))
					wp_enqueue_script('cspm_rangeSlider_js');
					
				/**
				 * Progress Map Script */
				 
				wp_enqueue_script('cspm_progress_map_js');
	
				$localize_script_handle = 'cspm_progress_map_js';			
					
			}
			
			/**
			 * Localize the script with new data
			 * 1) We'll get the old data already localized.
			 * 2) Add this map's options to the old data array.
			 * 3) We'll clear the old wp_localize_script(), then, send a new one that contains old & new data. */
			
			if(!empty($this->map_object_id)){
							
				global $wp_scripts;
				
				$progress_map_vars = $wp_scripts->get_data($localize_script_handle, 'data');
				
				$current_progress_map_vars = json_decode(str_replace('var progress_map_vars = ', '', substr($progress_map_vars, 0, -1)), true);
				
				$old_map_script_args = $current_progress_map_vars['map_script_args'];
				
				$new_map_scripts_args = array_merge($old_map_script_args, $this->cspm_localize_map_script());
				
				$current_progress_map_vars['map_script_args'] = $new_map_scripts_args;
				
				$wp_scripts->add_data($localize_script_handle, 'data', '');
				
				wp_localize_script($localize_script_handle, 'progress_map_vars', $current_progress_map_vars);
				 
			}

			do_action('cspm_after_enqueue_script');
			
		}
		
			
		/**
		 * Get the link of the post either with the get_permalink() function ...
		 * ... or the custom field defined by the user
		 *
		 * @since 2.5 
		 */
		function cspm_get_permalink($post_id){
	
			if(!empty($this->outer_links_field_name))
				$the_permalink = get_post_meta($post_id, $this->outer_links_field_name, true);
			
			else $the_permalink = get_permalink($post_id);
			
			return $the_permalink;
			
		}
		
		
		/**
		 * Parse item custom title 
		 */
		function cspm_items_title($atts){
			
			extract( wp_parse_args( $atts, array(
				'post_id' => $post_id, 
				'title' => '', 
				'click_title_option' => false,
				'click_on_title' => $this->click_on_title,
				'external_link' => $this->external_link,
			)));
			
			/**
			 * Custom title structure */
			 
			$post_meta = esc_attr($title);
	
			$the_permalink = ($click_title_option && $click_on_title == 'yes') ? ' href="'.$this->cspm_get_permalink($post_id).'"' : '';
			$target = ($external_link == "same_window") ? '' : ' target="_blank"';
			
			/**
			 * Init vars */
			 
			$items_title = '';		
			$items_title_lenght = 0;
			
			/**
			 * If no custom title is set, call item original title */
			 
			if(empty($post_meta)){						
				
				$items_title = get_the_title($post_id);
				
			/**
			 * If custom title is set ... */
			 
			}else{
				
				// ... Get post metas from custom title structure
				$explode_post_meta = explode('][', $post_meta);
				
				// Loop throught post metas
				foreach($explode_post_meta as $single_post_meta){
					
					// Clean post meta name 
					$single_post_meta = str_replace(array('[', ']'), '', $single_post_meta);
					
					// Get the first two letters from post meta name
					$check_string = substr($single_post_meta, 0, 2);
					
					if(!empty($check_string)){
						
						// Separator case
						if($check_string === 's='){
							
							// Add separator to title
							$items_title .= str_replace('s=', '', $single_post_meta);
						
						// Lenght case	
						}elseif($check_string === 'l='){
							
							// Define title lenght
							$items_title_lenght = str_replace('l=', '', $single_post_meta);
						
						// Empty space case
						}elseif($single_post_meta == '-'){
							
							// Add space to title
							$items_title .= ' ';
						
						// Post metas case		
						}else{
							
							// Add post meta value to title
							$items_title .= get_post_meta($post_id, $single_post_meta, true);
								
						}
					
					}
					
				}
				
				// If custom title is empty (Maybe someone will type something by error), call original title
				if(empty($items_title)) $items_title = get_the_title($post_id);
				
			}
			
			/**
			 * Show title as title lenght is defined */
								 
			/**
			 * Use the function mb_substr() instead of substr()!
			 * mb_substr() is multi-byte safe !
			 *
			 * @since 2.8.6 */
				 
			if($items_title_lenght > 0) $items_title = mb_substr($items_title, 0, $items_title_lenght);
			
			return ($click_title_option) ? '<a'.$the_permalink.''.$target.' title="'.addslashes_gpc($items_title).'">'.addslashes_gpc($items_title).'</a>' : addslashes_gpc($items_title);
			
		}
		
		
		/**
		 * Parse item custom details 
		 *
		 * @updated 2.8.6
		 */
		function cspm_items_details($post_id, $details){
			
			/**
			 * Custom details structure */
			 
			$post_meta = esc_attr($details);		
			
			/**
			 * Init vars */
			 
			$items_details = '';
			$items_title_lenght = 0;
			$items_details_lenght = '100';
					
			$ellipses = '';
			
			if($this->cspm_get_map_option('ellipses') == 'yes')
				$ellipses = '&hellip;';	
									 
			/**
			 * If new structure is set ... */
			 
			if(!empty($post_meta)){
				
				/**
				 * ... Get post metas from custom details structure */
				 
				$explode_post_meta = explode('][', $post_meta);
				
				/**
				 * Loop throught post metas */
				 
				foreach($explode_post_meta as $single_post_meta){
					
					/**
					 * Clean post meta name */
					 
					$single_post_meta = str_replace(array('[', ']'), '', $single_post_meta);
					
					/**
					 * Get the first two letters from post meta name */
					 
					$check_string = substr($single_post_meta, 0, 2);
					$check_taxonomy = substr($single_post_meta, 0, 4);
					$check_content = substr($single_post_meta, 0, 7);
					
					/**
					 * Taxonomy case */
					 
					if(!empty($check_taxonomy) && $check_taxonomy == 'tax='){
						
						/**
						 * Add taxonomy term(s) */
						 
						$taxonomy = str_replace('tax=', '', $single_post_meta);
						$items_details .= implode(', ', wp_get_post_terms($post_id, $taxonomy, array("fields" => "names")));
						
					/**
					 * The content */
					 
					}elseif(!empty($check_content) && $check_content == 'content'){
						
						$explode_content = explode(';', str_replace(' ', '', $single_post_meta));
						
						/**
						 * Get original post details */
						 
						$post_record = get_post($post_id, ARRAY_A);
						
						/**
						 * Post content */
						 
						$post_content = trim(preg_replace('/\s+/', ' ', $post_record['post_content']));
						
						/**
						 * Post excerpt */
						 
						$post_excerpt = trim(preg_replace('/\s+/', ' ', $post_record['post_excerpt']));
						
						/**
						 * Excerpt is recommended */
						 
						$the_content = (!empty($post_excerpt)) ? $post_excerpt : $post_content;
				
						/**
						 * Show excerpt/content as details lenght is defined */
					 
						/**
						 * Use the function mb_substr() instead of substr()!
						 * mb_substr() is multi-byte safe !
						 *
						 * @since 2.8.6 */
				 
						if(isset($explode_content[1]) && $explode_content[1] > 0) 
							$items_details .= mb_substr($the_content, 0, $explode_content[1]).$ellipses;
									
					/**
					 * Separator case */
					 
					}elseif(!empty($check_string) && $check_string == 's='){
						
						/**
						 * Add separator to details */
						 
						$separator = str_replace('s=', '', $single_post_meta);
						
						$separator == 'br' ? $items_details .= '<br />' : $items_details .= $separator;
						
					/**
					 * Meta post title OR Label case */
					 
					}elseif(!empty($check_string) && $check_string == 't='){
						
						/**
						 * Add label to details */
						 
						$items_details .= str_replace('t=', '', $single_post_meta);
						
					/**
					 * Lenght case */
					 
					}elseif(!empty($check_string) && $check_string == 'l='){
						
						/**
						 * Define details lenght */
						 
						$items_details_lenght = str_replace('l=', '', $single_post_meta);
						
					/**
					 * Empty space case */
					 
					}elseif($single_post_meta == '-'){
						
						/**
						 * Add space to details */
						 
						$items_details .= ' ';
						
					/**
					 * Post metas case */
					 
					}else{
	
						/**
						 * Add post metas to details */
						 
						$items_details .= get_post_meta($post_id, $single_post_meta, true);
							
					}
					
				}						
				
			}
			
			/**
			 * If no custom details structure is set ... */
			 
			if(empty($post_meta) || empty($items_details)){
				
				/**
				 * Get original post details */
				 
				$post_record = get_post($post_id, ARRAY_A, 'display');
				
				/**
				 * Post content */
				 
				$post_content = trim(preg_replace('/\s+/', ' ', $post_record['post_content']));
				
				/**
				 * Post excerpt */
				 
				$post_excerpt = trim(preg_replace('/\s+/', ' ', $post_record['post_excerpt']));
				
				/**
				 * Excerpt is recommended */
				 
				$items_details = (!empty($post_excerpt)) ? $post_excerpt : $post_content;
				
				/**
				 * Show excerpt/content as details lenght is defined */
				 
				if($items_details_lenght > 0){
					 
					/**
					 * Use the function mb_substr() instead of substr()!
					 * mb_substr() is multi-byte safe !
					 *
					 * @since 2.8.6 */
				 
					$items_details = mb_substr($items_details, 0, $items_details_lenght).$ellipses;
					
				}
				
			}
			
			return addslashes_gpc($items_details);
			
		}
		
		
		/**
		 * Ajax function: Get Item details 
		 */
		function cspm_load_carousel_item(){
				
			global $wpdb;

			if(class_exists('CSProgressMap'))
				$CSProgressMap = CSProgressMap::this();
			
			/**
			 * Reload map settings */
			 
			$this->map_object_id = esc_attr($_POST['map_object_id']);
			$this->map_settings = $_POST['map_settings'];
				
			/**
			 * Items ID */
			 
			$post_id = esc_attr($_POST['post_id']);
			
			/**
			 * Remove the index for secondary markers. e.g. 551-0 will be 551 */
			 
			if(strpos($post_id, '-') !== false) $post_id = substr($post_id, 0, strpos($post_id, '-'));
			
			/**
			 * View style (horizontal/vertical) */
			 
			$items_view = esc_attr($_POST['items_view']);
					
			/**
			 * Get items title or custom title */
			 
			$item_title = apply_filters(
				'cspm_custom_item_title', 
				stripslashes_deep(
					$this->cspm_items_title(array(
						'post_id' => $post_id, 
						'title' => $this->cspm_get_map_option('items_title'), 
						'click_title_option' => true,
						'click_on_title' => $this->cspm_get_map_option('click_on_title'),
						'external_link' => $this->cspm_get_map_option('external_link'),
					))
				), 
				$post_id
			); 
			
			$item_description = apply_filters(
				'cspm_custom_item_description', 
				stripslashes_deep(
					$this->cspm_items_details($post_id, $this->cspm_get_map_option('items_details'))
				), 
				$post_id
			);
			
			/**
			 * Create items single page link */
			 
			$the_permalink = $this->cspm_get_permalink($post_id);				
			$target = ($this->cspm_get_map_option('external_link') == "same_window") ? '' : ' target="_blank"';
			
			$more_button_text = esc_html__($this->cspm_get_map_option('details_btn_text'), 'cspm');
			$more_button_text = apply_filters('cspm_more_button_text', $more_button_text, $post_id);
			
			$output = '';
			
			/* ========================= */
			/* ==== Horizontal view ==== */
			/* ========================= */
					
			if($items_view == "listview"){
						
				$this->horizontal_image_size = $this->cspm_get_map_option('horizontal_image_size', '204,150');
					
					if($explode_horizontal_img_size = explode(',', $this->horizontal_image_size)){
						$this->horizontal_img_width = $this->cspm_setting_exists(0, $explode_horizontal_img_size, '204');
						$this->horizontal_img_height = $this->cspm_setting_exists(1, $explode_horizontal_img_size, '150');
					}else{
						$this->horizontal_img_width = '204';
						$this->horizontal_img_height = '150';
					}
						
				$parameter = array(
					'class' => 'cspm_border_left_radius',
				);				
				
				/**
				 * Item thumb */

				$image_size = array($this->horizontal_img_width, $this->horizontal_img_height);
				
				$post_thumbnail = apply_filters(
					'cspm_post_thumb', 
					get_the_post_thumbnail($post_id, $image_size, $parameter), 
					$post_id, 
					$this->horizontal_img_width, 
					$this->horizontal_img_height
				);
								
				$output .= '<div class="item_infos">';
								
									
					/* =========================== */
					/* ==== LTR carousel mode ==== */
					/* =========================== */
					
					if($this->cspm_get_map_option('carousel_mode') == 'false'){
						
						/**
						 * Image or Thumb area */
						 
						$output .= '<div class="item_img">';
								
							$output .= $post_thumbnail;
				
						$output .= '</div>';
						
						/**
						 * Details area */
						 
						$output .= '<div class="details_container">';
							
							/**
							 * "More" Button */
							 
							if($this->cspm_get_map_option('show_details_btn') == 'yes')
								$output .= '<div><a class="details_btn cspm_border_radius cspm_border_shadow" href="'.$the_permalink.'" style="'.$this->cspm_get_map_option('details_btn_css').'"'.$target.'>'.$more_button_text.'</a></div>';
							
							/**
							 * Item title */
							 
							$output .= '<div class="details_title">'.$item_title.'</div>';
							
							/**
							 * Items details */
							 
							$output .= '<div class="details_infos">'.$item_description.'</div>';
							
						$output .= '</div>';
									
									
					/* =========================== */
					/* ==== RTL carousel mode ==== */
					/* =========================== */
					
					}else{
					
						/**
						 * Details area */
						 
						$output .= '<div class="details_container">';
							
							/**
							 * "More" Button */
							 
							if($this->cspm_get_map_option('show_details_btn') == 'yes')
								$output .= '<div><a class="details_btn cspm_border_radius cspm_border_shadow" href="'.$the_permalink.'" style="'.$this->cspm_get_map_option('details_btn_css').'"'.$target.'>'.$more_button_text.'</a></div>';
							
							/**
							 * Item title */
							 
							$output .= '<div class="details_title">'.$item_title.'</div>';
							
							/**
							 * Items details */
							 
							$output .= '<div class="details_infos">'.$item_description.'</div>';
							
						$output .= '</div>';
						
						/**
						 * Image or Thumb area */
						 
						$output .= '<div class="item_img">';
								
							$output .= $post_thumbnail;
				
						$output .= '</div>';
					
					}
					
					$output .= '<div style="clear:both"></div>';				
					
				$output .= '</div>';
			
			
			/* ======================= */
			/* ==== Vertical view ==== */
			/* ======================= */
					
			}elseif($items_view == "gridview"){					
							
				$this->vertical_image_size = $this->cspm_get_map_option('vertical_image_size', '204,120');			
					
					if($explode_vertical_img_size = explode(',', $this->vertical_image_size)){
						$this->vertical_img_width = $this->cspm_setting_exists(0, $explode_vertical_img_size, '204');
						$this->vertical_img_height = $this->cspm_setting_exists(1, $explode_vertical_img_size, '120');
					}else{
						$this->vertical_img_width = '204';
						$this->vertical_img_height = '120';
					}
							 
				$parameter = array(
					'class' => 'cspm_border_top_radius',
				);
				
				/**
				 * Item thumb */
				
				$image_size = array($this->vertical_img_width, $this->vertical_img_height);
				
				$post_thumbnail = apply_filters(
					'cspm_post_thumb', 
					get_the_post_thumbnail($post_id, $image_size, $parameter), 
					$post_id, 
					$this->vertical_img_width, 
					$this->vertical_img_height
				);
						
				$output .= '<div class="item_infos">';
					
					/**
					 * Image or Thumb area */
					 
					$output .= '<div class="item_img">';
							
						$output .= $post_thumbnail;
			
					$output .= '</div>';
					
					/**
					 * Details area	*/
					 
					$output .= '<div class="details_container">'; 
						
						/**
						 * "More" Button */
						 
						if($this->cspm_get_map_option('show_details_btn') == 'yes')
							$output .= '<div><a class="details_btn cspm_border_radius cspm_border_shadow" href="'.$the_permalink.'" style="'.$this->cspm_get_map_option('details_btn_css').'"'.$target.'>'.$more_button_text.'</a></div>';
						
						/**
						 * Item title */
						 
						$output .= '<div class="details_title">'.$item_title.'</div>';
						
						/** 
						 * Items details */
						 
						$output .= '<div class="details_infos">'.$item_description.'</div>';
						
					$output .= '</div>';
					
					$output .= '<div style="clear:both"></div>';
					
				$output .= '</div>';
				
			}
		   
			die($output);
					
		}
		
		
	   /**
		* Build the main query
		*
		* @since 2.0 
		* @updated 2.8.2
		* @updated 2.8.6
		* @updated 3.0 [@added @order_meta_type]
		*/
		function cspm_main_query($atts = array()){

			if(class_exists('CSProgressMap'))
				$CSProgressMap = CSProgressMap::this();
			
			extract( wp_parse_args( $atts, array(
				'post_type' => '', 
				'post_status' => '',
				'number_of_posts' => '', 
				'tax_query' => '',
				'tax_query_field' => 'id', // @since 2.6.1
				'tax_query_relation' => '', 
				'cache_results' => '', 
				'update_post_meta_cache' => '',
				'update_post_term_cache' => '',
				'post_in' => '',
				'post_not_in' => '',
				'custom_fields' => '',
				'custom_field_relation' => '',
				'authors' => '',
				'orderby' => '',
				'orderby_meta_key' => '',
				'order_meta_type' => '', //@since 3.0
				'order' => '',
				'optional_latlng' => '',
			)));

			/**
			 * Post type */
			 
			if(empty($post_type)) $post_type = (empty($this->post_type)) ? 'post' : $this->post_type;					
			$post_type_array = explode(',', esc_attr($post_type));
					
			/**
			 * Query limit */
			 
			if(empty($number_of_posts)) 
				$nbr_items = (empty($this->number_of_items)) ? -1 : $this->number_of_items;
			else $nbr_items = esc_attr($number_of_posts);
			
			/**
			 * Status */
			 
			if(empty($post_status)){ 
				$status = $this->post_status;
			}else $status = explode(',', esc_attr($post_status));
			
			/**
			 * Caching */		 
			 
			if(empty($cache_results)) 
				$cache_results = ($this->cache_results == 'true') ? true : false;
			else $cache_results = (esc_attr($cache_results) == 'true') ? true : false;
			
			if(empty($update_post_meta_cache))
				$update_post_meta_cache = ($this->update_post_meta_cache == 'true') ? true : false;
			else $update_post_meta_cache = (esc_attr($update_post_meta_cache) == 'true') ? true : false;
			
			if(empty($update_post_term_cache))
				$update_post_term_cache = ($this->update_post_term_cache == 'true') ? true : false;					
			else $update_post_term_cache = (esc_attr($update_post_term_cache) == 'true') ? true : false;		
			
			/**
			 * Post parameters */
			 
			if(empty($post_in))
				$post_in = $this->post_in;
			else $post_in = explode(',', esc_attr($post_in));
			
			if(empty($post_not_in))
				$post_not_in = $this->post_not_in;		
			else $post_not_in = explode(',', esc_attr($post_not_in));
			
			/**
			 * OrderBy meta key */
			 
			if(empty($orderby)){ 
				$orderby_param = $this->orderby_param;
				$orderby_meta_key = ($orderby_param != 'meta_value' && $orderby_param != 'meta_value_num') ? '' : $this->orderby_meta_key;
				$order_meta_type = ($orderby_param == 'meta_value') ? $this->order_meta_type : ''; //@since 3.0
				$order_param = $this->order_param;
			}else{
				$orderby_param = esc_attr($orderby);
				$orderby_meta_key = esc_attr($orderby_meta_key);
				$order_meta_type = esc_attr($order_meta_type); //@since 3.0
				$order_param = strtoupper(esc_attr($order));
			}
			
			/**
			 * Custom fields */
			 
			if(empty($custom_fields)) 
				$custom_fields = $this->cspm_parse_query_meta_fields($this->custom_fields, $this->custom_field_relation_param, $optional_latlng);
			else $custom_fields = $this->cspm_parse_query_meta_fields(esc_attr($custom_fields), strtoupper(esc_attr($custom_field_relation)), $optional_latlng);
		
			/**
			 * Taxonomies */
			 
			if(empty($tax_query)){
							
				$post_taxonomies = (array) get_object_taxonomies($this->post_type, 'objects');
					unset($post_taxonomies['post_format']); // @since 3.0 | Remove the taxonomy "post_format" from the list.
				
				$taxonomies_array = array();
				
				/**
				 * Loop throught taxonomies	*/
				 
				foreach($post_taxonomies as $single_taxonomie){
					
					/**
					 * Get Taxonomy Name */
					 
					$tax_name = $single_taxonomie->name;
					
					/**
					 * Make sure the taxonomy operator exists */
					
					$taxonomy_operator_param = $this->cspm_get_map_option($tax_name.'_operator_param', 'IN');
					 
					if(!empty($taxonomy_operator_param)){
						
						/**
						 * Get all terms related to this taxonomy */
						
						$terms = unserialize($this->cspm_get_map_option('taxonomie_'.$tax_name, serialize(array())));
						 
						if(is_array($terms) && count($terms) > 0){
							
							// For WPML =====
							$WPML_terms = array();
							foreach($terms as $term)
								$WPML_terms[] = $CSProgressMap->cspm_wpml_object_id($term, $tax_name, false, '', $this->use_with_wpml);
							// @For WPML ====							 
							
							$taxonomies_array[] = array(
								'taxonomy' => $tax_name,
								'field' => 'id',
								'terms' => $WPML_terms,
								'operator' => $taxonomy_operator_param,
						   );
						
						}
						
					}
					
				}
				
				$tax_query = (count($taxonomies_array) == 0) ? array('tax_query' => '') : array('tax_query' => array_merge(array('relation' => $this->taxonomy_relation_param), $taxonomies_array));
	
			}else{
				
				/**
				 * tax_query = "cat_1{1.2.3|IN},cat_2{2.3|NOT IN}"
				 * tax_query_relation = "AND" */
				
				$taxonomies_array = array();
				
				$taxonomy_term_names_and_term_ids = explode(',',  str_replace(' ', '', esc_attr($tax_query))); // array(cat_1{1.2.3|IN}, cat_2{2.3|NOT IN})
				
				if(count($taxonomy_term_names_and_term_ids) > 0){
					
					foreach($taxonomy_term_names_and_term_ids as $single_term_name_and_term_ids){
						
						$term_name = $term_relation = '';
						$term_ids = array();
						
						$term_name_and_term_ids = explode('{', $single_term_name_and_term_ids); // array(cat_1, 1.2.3|IN})
				
						if(isset($term_name_and_term_ids[0])) $term_name = $term_name_and_term_ids[0]; // cat_1
						
						if(isset($term_name_and_term_ids[0])){
							
							$term_ids_and_relation = explode('|', $term_name_and_term_ids[1]); // array(1.2.3, IN})
						
							if(isset($term_ids_and_relation[0])) $term_ids = explode('.', $term_ids_and_relation[0]); // array(1, 2, 3)
							
							if(isset($term_ids_and_relation[0])) $term_relation = str_replace('}', '', $term_ids_and_relation[1]); // IN;
							
						}
						
						if(count($term_ids) > 0){
													
							// For WPML ===== 
							$WPML_terms = array();
							foreach($term_ids as $term)
								$WPML_terms[] = $CSProgressMap->cspm_wpml_object_id($term, $term_name, false, '', $this->use_with_wpml);
							// @For WPML ====
	
							$taxonomies_array[] = array(
								'taxonomy' => $term_name,
								'field' => $tax_query_field, //'id',
								'terms' => $WPML_terms,
								'operator' => strtoupper($term_relation),
						   );
													   
						}
													   
					}
					
				}
				
				$tax_query = (count($taxonomies_array) == 0) ? array('tax_query' => '') : array('tax_query' => array_merge(array('relation' => strtoupper(esc_attr($tax_query_relation))), $taxonomies_array));
				
			}
				
			/**
			 * Authors */
			 
			if(empty($authors)){

				$authors_array = array();
				
				if(isset($this->authors)){
						
					$author_ids = $this->authors;
					
					$authors_condition = $this->authors_prefixing;
					$authors_prefixing = ($authors_condition == 'false') ? '' : '-';
					
					if($authors_prefixing == '-'){
						foreach($author_ids as $author_id)
							$authors_array[] = $authors_prefixing.$author_id;
					}else $authors_array = $author_ids;
					
				}
				
				$authors = (count($authors_array) == 0) ? '' : implode(',', $authors_array);
				
			}else{
							
				$authors_array = explode(',', esc_attr($authors));
				$authors = (count($authors_array) == 0) ? '' : implode(',', $authors_array);
				
			}
			
			/**
			 * Call items ids */
			 
			$query_args = array( 'post_type' => $post_type_array,							
								 'post_status' => $status, 
	
								 'posts_per_page' => $nbr_items, 
								 
								 'tax_query' => $tax_query['tax_query'],
								 
								 'cache_results' => $cache_results,
								 'update_post_meta_cache' => $update_post_meta_cache,
								 'update_post_term_cache' => $update_post_term_cache,
								 
								 'post__in' => $post_in,
								 'post__not_in' => $post_not_in,
								 
								 'meta_query' => $custom_fields['meta_query'],
								 
								 'author' => $authors,
								 
								 'orderby' => $orderby_param,
								 'meta_key' => $orderby_meta_key,
								 'meta_type' => $order_meta_type, //@since 3.0
								 'order' => $order_param,
								 'fields' => 'ids');
			
			$query_args = apply_filters( 'cs_progress_map_main_query', $query_args );
										 
			$query_args = (isset($query_args['fields']) && $query_args['fields'] == 'ids') ? $query_args : $query_args + array('fields' => 'ids');

			/**
			 * Execute query */
			 
			$post_ids = ($this->use_with_wpml == "yes") ? query_posts( $query_args ) : get_posts( $query_args );

			/**
			 * Reset query */
			 
			($this->use_with_wpml == "yes") ? wp_reset_query() : wp_reset_postdata();
			
			return $post_ids;
			
		}
		
	   
	   /**
		* Parse custom fields to use in the main query
		*
		* @since 2.0 
		* @updated 2.8.5
		* @updated 3.0
		*/
		function cspm_parse_query_meta_fields($meta_fields, $relation, $optional_latlng = 'false'){
			
			$custom_fields = array('meta_query' => '');
			
			if(is_array($meta_fields) && count($meta_fields) > 0){
				
				/**
				 * Init meta_query array */
				 
				$meta_query_array = array();
	
				/**
				 * Loop through the custom fields */
				 
				foreach($meta_fields as $single_meta_field){
	
					/**
					 * Init parameters array.
					 * On each turn of the loop, $parameters_array must be empty */
					 
					$parameters_array = array();
				
					if(isset($single_meta_field['custom_field_name']) && !empty($single_meta_field['custom_field_name'])){
						
						/**
						 * Key */
						
						$parameters_array['key'] = $single_meta_field['custom_field_name'];
						
						/**
						 * Value */
						 
						$value_values = isset($single_meta_field['custom_field_values']) ? $single_meta_field['custom_field_values'] : '';
						
						$values_array = explode(',', $value_values);
						
						if(count($values_array) == 1) $parameters_array['value'] = $values_array[0];
						else $parameters_array['value'] = $values_array;
													
						/**
						 * Type */
						
						$parameters_array['type'] = isset($single_meta_field['custom_field_type']) ? $single_meta_field['custom_field_type'] : '';
						
						/**
						 * Compare */
						
						$parameters_array['compare'] = isset($single_meta_field['custom_field_compare_param']) ? $single_meta_field['custom_field_compare_param'] : '';
						
						/**
						 * Associate them to meta_query[] */
						 
						$meta_query_array[] = $parameters_array;	
						
					}
					
				}
			
				/**
				 * Check whether we'll display locations with no coordinates for the extension "List & Filter"
				 * @since 2.8.5 */
				 
				if($optional_latlng == 'false'){
					 
					$meta_query_array[] = array(
						'key' => CSPM_LATITUDE_FIELD,
						'value' => '',
						'compare' => '!='
					);
					
					$meta_query_array[] = array(
						'key' => CSPM_LONGITUDE_FIELD,
						'value' => '',
						'compare' => '!='
					);					 
					
				}
				
				$custom_fields = array('meta_query' => array_merge(array('relation' => $relation), $meta_query_array));
		
			}elseif($optional_latlng == 'false'){
				
				$custom_fields = array(
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => CSPM_LATITUDE_FIELD,
							'value' => '',
							'compare' => '!='
						),
						array(
							'key' => CSPM_LONGITUDE_FIELD,
							'value' => '',
							'compare' => '!='
						)
					)
				);
								
			}

			return $custom_fields;
						
		}


		/**
		 * Main map
		 *
		 * @updated 3.0
		 */
		function cspm_main_map_function($atts = array()){

			/**
			 * Overide the default post_ids array by the shortcode atts post_ids */
			 
			extract( wp_parse_args( $atts, array(
			
				'map_id' => 'initial',
				'post_ids' => '',
			
				/**
				 * Layout
				 * @since 2.8 */
				 
				'layout' => $this->main_layout,
			
				/**
				 * Map Settings */
				 				 
				'center_at' => '',	
				'zoom' => $this->zoom,
				'show_secondary' => 'yes',
				
				/**
				 * Carousel */
				 
				'carousel' => 'yes',
				
				/**
				 * Posts count settings */
				 
				'show_posts_count' => 'yes',
				
				/**
				 * Faceted Search */
				 
				'faceted_search' => 'yes',
				'faceted_search_tax_slug' => esc_attr($this->marker_categories_taxonomy),
				'faceted_search_tax_terms' => '',
				
				/**
				 * Search Form */
				 
				'search_form' => 'yes',
				
				/**
				 * Infobox */
				 
				'infobox_type' => $this->infobox_type, //@since 2.6.3
				'infobox_link_target' => esc_attr($this->infobox_external_link), //@since 2.8.6 | Possible value, "same_window", "new_window" & "disable"

				/**
				 * Geotarget
				 * @since 2.7.4 */
				 
				'geo' => $this->geoIpControl,
				'show_user' => $this->show_user,
				'user_marker' => $this->user_marker_icon,
				'user_map_zoom' => $this->user_map_zoom,
				'user_circle' => $this->user_circle,
				
				/**
				 * Overlays: Polyline, Polygon
				 * @since 2.7 */
				 
				'polyline_objects' => $this->polylines,
				'polygon_objects' => $this->polygons,
				
				/**
				 * [@optional_latlng] Wether we will display all posts event those with no Lat & Lng.
				 * Note: This is only used for the extension "List & Filter"
				 * @since 2.8 */				 
				
				'optional_latlng' => 'false',
				
				/**
				 * [@window_resize] Whether to recenter the Map on window resize or not.
				 * @since 2.8.5 */
				
				'window_resize' => 'yes', // Possible values, "yes" & "no"
			  				
			)));

			$map_id = esc_attr($map_id);
			$map_layout = esc_attr($layout);
			
			/**
			 * Get the terms to use in the faceted search.
			 * This will override the default settings */
			 
			$faceted_search_tax_terms = (empty($faceted_search_tax_terms)) ? array() : explode(',', $faceted_search_tax_terms);
			
			/**
			 * Map Styling */
						
			$map_styles = array();
			
			if($this->style_option == 'progress-map'){
					
				/**
				 *Include the map styles array */				 
		
				if(file_exists($this->map_styles_file))
					$map_styles = include($this->map_styles_file);
						
			}elseif($this->style_option == 'custom-style' && !empty($this->js_style_array)){
				
				$this->map_style = 'custom-style';
				$map_styles = array('custom-style' => array('style' => $this->js_style_array));
				
			}
			
			do_action('cspm_before_main_map_query', $map_id, $atts); // @since 2.6.3
			
			/**
			 * If post ids being pased from the shortcode parameter @post_ids
			 * Check the format of the @post_ids value */
			 
			if(!empty($post_ids)){
				
				$query_post_ids = explode(',', $post_ids);	
					
			}else{
				
				/**
				 * The main query */
									
				$query_post_ids = $this->cspm_main_query(array(
						'optional_latlng' => esc_attr($optional_latlng),
				));
	
			}
				
			$post_type = !empty($post_type) ? esc_attr($post_type) : $this->post_type;
			$post_ids = apply_filters('cspm_main_map_post_ids', $query_post_ids, $map_id, $atts);
	
			/**
			 * Get the center point */
			 
			if(!empty($center_at)){
				
				$center_point = esc_attr($center_at);
				
				/**
				 * If the center point is Lat&Lng coordinates */
				 
				if(strpos($center_point, ',') !== false){
						
					$center_latlng = explode(',', str_replace(' ', '', $center_point));
					
					/**
					 * Get lat and lng data */
					 
					$centerLat = isset($center_latlng[0]) ? $center_latlng[0] : '';
					$centerLng = isset($center_latlng[1]) ? $center_latlng[1] : '';
					
				/**
				 * If the center point is a post id */
				 
				}else{
					
					/**
					 * Center the map on the first post in $post_ids */
					 
					if($center_point == "auto" && count($post_ids) > 0 && isset($post_ids[0]))
						$center_point = $post_ids[0];					
					
					/**
					 * Get lat and lng data */
					 
					$centerLat = get_post_meta($center_point, CSPM_LATITUDE_FIELD, true);
					$centerLng = get_post_meta($center_point, CSPM_LONGITUDE_FIELD, true);
					
			
				}
				
				/**
				 * The center point */
				 
				$center_point = array($centerLat, $centerLng);
			
			}else{
				
				/**
				 * The center point */
				 
				$center_point = explode(',', $this->center);
					
				/**
				 * Get lat and lng data */
				 
				$centerLat = isset($center_latlng[0]) ? $center_latlng[0] : '';
				$centerLng = isset($center_latlng[1]) ? $center_latlng[1] : '';
				
			}
							
			$latLng = '"'.$centerLat.','.$centerLng.'"';

			$zoom = esc_attr($zoom);
			$carousel = ($this->show_carousel == 'true' && esc_attr($carousel) == 'yes') ? 'yes' : 'no';
			$faceted_search = esc_attr($faceted_search);
			$search_form = esc_attr($search_form);
			
			/**
			 * Polyline PHP Objects
			 * @since 2.7 */
			
			$polylines = '';		
			
			if(!empty($polyline_objects) && is_array($polyline_objects))
				$polylines = $this->cspm_build_polyline_objects($polyline_objects);
			
			/**
			 * Polygon PHP Objects
			 * @since 2.7 */
			
			$polygons = '';		
			
			if(!empty($polygon_objects) && is_array($polygon_objects))
				$polygons = $this->cspm_build_polygon_objects($polygon_objects);
			
			?>

			<script type="text/javascript">
	
				jQuery(document).ready(function($) { 		
				
					var map_id = '<?php echo $map_id ?>';
					
					if(typeof _CSPM_DONE === 'undefined' || _CSPM_DONE[map_id] === true) 
						return;
					
					_CSPM_DONE[map_id] = false;
					_CSPM_MAP_RESIZED[map_id] = 0;
					
					/**
					 * Start displaying the Progress Bar loader */
					 
					if(typeof NProgress !== 'undefined'){
						
						NProgress.configure({
						  parent: 'div#codespacing_progress_map_div_'+map_id,
						  showSpinner: true
						});				
						
						NProgress.start();
						
					}
					
					/**
					 * @map_layout, Will contain the layout type of the current map.
					 * This variable was first initialized in "progress_map.js"!
					 * @since 2.8 */
										
					map_layout[map_id] = '<?php echo $map_layout; ?>';

					/**
					 * @infobox_xhr, Will store the ajax requests in order to test if an ajax request ... 
					 * ... will overide "an already sent and non finished" request */
					
					var infobox_xhr; 
					
					/**
					 * @cspm_bubbles, Will store the marker bubbles (post_ids) in the viewport of the map */
					 
					cspm_bubbles[map_id] = []; 
					
					/**
					 * @cspm_child_markers, Will store the status of markers in order to define secondary markers from parent markers */
					 					 
					cspm_child_markers[map_id] = []; 
					
					/**
					 * Will store all the current ajax request (for infoboxes) in order to execute them when they all finish */
					
					cspm_requests[map_id] = []; 
									
					/**
					 * @post_ids_and_categories, Will store the markers categories in order to use with faceted search and to define the marker icon */
					 
					post_ids_and_categories[map_id] = {}; 
					
					/** 
					 * @post_lat_lng_coords, Will store the markers coordinates in order to use them when rewriting the map & the carousel */
					 
					post_lat_lng_coords[map_id] = {}; 
					
					/**
					 * @post_ids_and_child_status, Will store the markers and their child status in order to use when rewriting the carousel */
					 
					post_ids_and_child_status[map_id] = {}; 
					
					/**
					 * @json_markers_data, Will store the markers objects */
					 
					var json_markers_data = [];

					/**
					 * init plugin map */
					 
					var plugin_map_placeholder = 'div#codespacing_progress_map_div_'+map_id;
					var plugin_map = $(plugin_map_placeholder);
					
					/**
					 * Load Map options */
					 
					<?php if(!empty($center_at)){ ?>
						var map_options = cspm_load_map_options('<?php echo $map_id; ?>', false, <?php echo $latLng; ?>, <?php echo $zoom; ?>);
					<?php }else{ ?>
						var map_options = cspm_load_map_options('<?php echo $map_id; ?>', false, null, <?php echo $zoom; ?>);
					<?php } ?>
					
					/**
					 * Activate the new google map visual */
					 
					google.maps.visualRefresh = true;				

					/**
					 * The initial map style */
					 
					var initial_map_style = "<?php echo $this->initial_map_style; ?>";
					
					/**
					 * Enhance the map option with the map type id of the style */
					 
					<?php if(count($map_styles) > 0 && $this->map_style != 'google-map' && isset($map_styles[$this->map_style])){ ?> 
											
						/**
						 * The initial style */
						 
						var map_type_id = cspm_initial_map_style(initial_map_style, true);
			
						/**
						 * Map type control option */
						 
						var mapTypeControlOptions = {mapTypeControlOptions: {
														position: google.maps.ControlPosition.TOP_RIGHT,
														mapTypeIds: [google.maps.MapTypeId.ROADMAP,
																	 google.maps.MapTypeId.SATELLITE,
																	 google.maps.MapTypeId.TERRAIN,
																	 google.maps.MapTypeId.HYBRID,
																	 "custom_style"]				
													}};
													
						var map_options = $.extend({}, map_options, map_type_id, mapTypeControlOptions);
						
					<?php }else{ ?>
											
						/**
						 * The initial style */
						 
						var map_type_id = cspm_initial_map_style(initial_map_style, false);
						var map_options = $.extend({}, map_options, map_type_id);
						
					<?php } ?>				
	
					<?php 
					
					/** 
					 * Determine whether we'll remove the carousel or not.
					 * Note: In this plugin, a map without carousel is called "Light Map"! */
					 
					$light_map = ($map_layout == 'fullscreen-map' || $map_layout == 'fit-in-map' || $this->show_carousel == 'false' || $carousel == 'no' || ($this->layout_type == 'responsive' && $this->cspm_detect_mobile_browser())) ? true : false; ?>
					
					/**
					 * The carousel dimensions & style */
					 
					<?php if(!$light_map && $this->items_view == "listview"){ ?>
					
						var item_width = parseInt(<?php echo $this->horizontal_item_width; ?>);										
						var item_height = parseInt(<?php echo $this->horizontal_item_height; ?>);
						var item_css = "<?php echo $this->horizontal_item_css; ?>";
						var items_background  = "<?php echo $this->items_background; ?>";
						
					<?php }elseif(!$light_map && $this->items_view == "gridview"){ ?>
					
						var item_width = parseInt(<?php echo $this->vertical_item_width; ?>);
						var item_height = parseInt(<?php echo $this->vertical_item_height; ?>);
						var item_css = "<?php echo $this->vertical_item_css; ?>";
						var items_background  = "<?php echo $this->items_background; ?>";
					

					<?php } ?>
					
					<?php 
					
					/**
					 * @polylines_of_post_ids (array) - This will hold the coordinates of all polylines builded from post IDs.
					 * @polygons_of_post_ids (array) - This will hold the coordinates of all polygons builded from post IDs.
					 * @since 2.7 */
					
					$polylines_of_post_ids = $polygons_of_post_ids = array();
					
					/**
					 * @markers_array (array) - Contain all the makers of all post types
					 * @markers_object (array) - Contain all the markers of a given post type */
					 
					$markers_array = get_option('cspm_markers_array');
					$markers_object = isset($markers_array[$post_type]) ? $markers_array[$post_type] : array();
	
					if($light_map){ ?> var light_map = true; <?php }else{ ?> var light_map = false; <?php } 
																
					/**
					 * Count items */
					 
					$count_post = count($post_ids);
						
					$m = $l = 0;
					
					if($count_post > 0){
						
						while($m < $count_post){
							
							$post_id = isset($markers_object['post_id_'.$post_ids[$m]]['post_id']) ? $markers_object['post_id_'.$post_ids[$m]]['post_id'] : '';
															
							$lat = isset($markers_object['post_id_'.$post_ids[$m]]['lat']) ? $markers_object['post_id_'.$post_ids[$m]]['lat'] : '';
							$lng = isset($markers_object['post_id_'.$post_ids[$m]]['lng']) ? $markers_object['post_id_'.$post_ids[$m]]['lng'] : '';						
							$is_child = 'no';
							$child_markers  = isset($markers_object['post_id_'.$post_ids[$m]]['child_markers']) ? $markers_object['post_id_'.$post_ids[$m]]['child_markers'] : array();
							
							if(!empty($post_id) && !empty($lat) && !empty($lng)){
								
								if($this->marker_cats_settings == 'true'){
									
									/**
									 * 1. Get marker category
									 * Note: [@post_categories] | We're not using the function wp_get_post_terms() because it may ...
									 * ... loop throught big amount of markers which will slows down the map. Instead ...
									 * ... we'll use another workaround using the already buitin array that contains all our marker and their categories! */
									 
									$post_categories = isset($markers_object['post_id_'.$post_ids[$m]]['post_tax_terms'][$faceted_search_tax_slug]) ? $markers_object['post_id_'.$post_ids[$m]]['post_tax_terms'][$faceted_search_tax_slug] : array();
									$implode_post_categories = (count($post_categories) == 0) ? '' : implode(',', $post_categories);
									
									/**
									 * 2. Get marker image */
									 
									$marker_img_by_cat = $this->cspm_get_marker_img(
										array(
											'post_id' => $post_id,
											'tax_name' => $this->marker_categories_taxonomy,
											'term_id' => (isset($post_categories[0])) ? $post_categories[0] : '',
										)
									);

									/**
									 * 3. Get marker image size for Retina display */
									 
									$marker_img_size = $this->cspm_get_image_size($this->cspm_get_image_path_from_url($marker_img_by_cat));
								
								}else{
									
									/**
									 * 1. Get marker category */
									 
									$post_categories = array();
									$implode_post_categories = '';
									
									/**
									 * 2. Get marker image */
									 
									$marker_img_by_cat = $this->cspm_get_marker_img(
										array(
											'post_id' => $post_id
										)
									);

									/**
									 * 3. Get marker image size for Retina display */
									 
									$marker_img_size = $this->cspm_get_image_size($this->cspm_get_image_path_from_url($marker_img_by_cat));
								
								}
								
								?>
								
								/**
								 * Create the pin object */
								 								 
							    var marker_object = cspm_new_pin_object(<?php echo $l; ?>, '<?php echo $post_id; ?>', <?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo $implode_post_categories; ?>', map_id, '<?php echo $marker_img_by_cat; ?>', '<?php echo $marker_img_size; ?>', '<?php echo $is_child; ?>');
								json_markers_data.push(marker_object);
																
								<?php 
								
								/**
								 * Create polylines/polygons
								 * @since 2.7 */
								 
								/**
								 * Check for Polylines builded from post IDs and convert the IDs to LatLng coordinates.
								 * @since 2.7 */
								  
								if(is_array($polylines) && array_key_exists('ids', $polylines)){
								
									/**
									 * Loop through all Polylines builded from post IDs */
									 
									foreach($polylines['ids'] as $single_polyline_id => $single_polyline_data){
										
										if(!empty($single_polyline_data['path'])){											
											
											/**
											 * Loop through Polyline post IDs and convert them to LatLng coordinates */
											 
											foreach($single_polyline_data['path'] as $path_post_id){
												
												/**
												 * If the post ID exists on the map, Save the post coordinates */
												 
												if($path_post_id == $post_id)
													$polylines_of_post_ids[$single_polyline_id][$path_post_id] = $lat.','.$lng;
												
											}
												
										}
										
									}
									
								}											
								
								/**
								 * Check for Polygons builded from post IDs and convert the IDs to LatLng coordinates.
								 * @since 2.7 */
								 
								if(is_array($polygons) && array_key_exists('ids', $polygons)){
								
									/**
									 * Loop through all Polygons builded from post IDs */
									 
									foreach($polygons['ids'] as $single_polygon_id => $single_polygon_data){
										
										if(!empty($single_polygon_data['path'])){											
											
											/**
											 * Loop through Polygon post IDs and convert them to LatLng coordinates */
											 
											foreach($single_polygon_data['path'] as $path_post_id){

												/**
												 * If the post ID exists on the map, Save the post coordinates */
												 
												if($path_post_id == $post_id)
													$polygons_of_post_ids[$single_polygon_id][$path_post_id] = $lat.','.$lng;
												
											}
												
										}
										
									}
									
								}											
								
								/**
								 * Proceed for the Child Markers */
								 
								$l++;

								if(count($child_markers) > 0 && esc_attr($show_secondary) == 'yes'){
									
									for($c=0; $c<count($child_markers); $c++){
										
										$post_id = isset($child_markers[$c]['post_id']) ? $child_markers[$c]['post_id'] : '';
										$lat = isset($child_markers[$c]['lat']) ? $child_markers[$c]['lat'] : '';
										$lng = isset($child_markers[$c]['lng']) ? $child_markers[$c]['lng'] : '';						
										$is_child = isset($child_markers[$c]['is_child']) ? $child_markers[$c]['is_child'] : '';									
							
										if(!empty($post_id) && !empty($lat) && !empty($lng)){ ?>

											/**
											 * Create the child pin object */
											 
											var marker_object = cspm_new_pin_object(<?php echo $l; ?>, '<?php echo $post_id.'-'.$c; ?>', <?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo $implode_post_categories; ?>', map_id, '<?php echo $marker_img_by_cat; ?>', '<?php echo $marker_img_size; ?>', '<?php echo $is_child; ?>');
											json_markers_data.push(marker_object);
											
											<?php
																
											$l++;	
																			
										}									
							
									}
									
								}
								 
							}
							
							$m++;
	
						}
						
					}
					
					$show_infoboxes = $this->show_infobox;											
					$move_carousel_on_infobox_hover = (in_array('infobox_hover', $this->move_carousel_on)) ? 'true' : 'false';

					?>
	
					var infobox_div = $('div.cspm_infobox_container[data-map-id='+map_id+']');			
					var show_infobox = '<?php echo $show_infoboxes; ?>';
					var infobox_type = '<?php echo esc_attr($infobox_type); ?>';
					var infobox_display_event = '<?php echo $this->infobox_display_event; ?>';
					var useragent = navigator.userAgent;
					var infobox_loaded = false;
					var clustering_method = false;
					var remove_infobox_on_mouseout = '<?php echo $this->remove_infobox_on_mouseout; ?>';
											
					/**
					 * [@polyline_values] - will store an Object of all available Polylines	
					 * [@polygon_values] - will store an Object of all available Polygons					 				 					 
					 * @since 2.7 */
					 
					var polyline_values = [];
					var polygon_values = [];
					
					/**
					 * [@kml_values] - will store an Object of all available KML layers	
					 * @since 3.0 */
					
					var kml_values = [];
					
					<?php
					
					/**
					 * Build all Polyline JS objects
					 * @since 2.7
					 * @updated 3.0 [Added clickable, URL & Description options]
					 */
					 
					if(is_array($polylines)){
						
						/**
						 * Loop through all Polylines */
						 
						foreach($polylines as $polylines_type => $polylines_type_data){
							
							/**
							 * Loop through each polyline build from post IDs */
							 
							foreach($polylines_type_data as $polyline_id => $polyline_data){
								
								/**
								 * Build Polyline JS Object */
																	
								$polyline_clickable = !empty($polyline_data['clickable']) ? esc_attr($polyline_data['clickable']) : 'false'; //@since 3.0
								$polyline_url = !empty($polyline_data['url']) ? esc_url($polyline_data['url']) : ''; //@since 3.0
								$polyline_url_target = !empty($polyline_data['url_target']) ? esc_url($polyline_data['url_target']) : 'new_window'; //@since 3.0
								$polyline_description = !empty($polyline_data['description']) ? esc_attr($polyline_data['description']) : ''; //@since 3.0
								$polyline_infowindow_maxwidth = !empty($polyline_data['infowindow_maxwidth']) ? esc_attr($polyline_data['infowindow_maxwidth']) : '250'; //@since 3.0
								$polyline_geodesic = !empty($polyline_data['geodesic']) ? esc_attr($polyline_data['geodesic']) : 'false';
								$polyline_color = !empty($polyline_data['color']) ? esc_attr($polyline_data['color']) : '#189AC9';
								$polyline_opacity = !empty($polyline_data['opacity']) ? esc_attr($polyline_data['opacity']) : '1';
								$polyline_weight = !empty($polyline_data['weight']) ? esc_attr($polyline_data['weight']) : '2';
								$polyline_zindex = !empty($polyline_data['zindex']) ? esc_attr($polyline_data['zindex']) : '1'; 
								$polyline_visible = !empty($polyline_data['visible']) ? esc_attr($polyline_data['visible']) : ''; ?>
								
								var polyline_path = []; <?php
								
								$polyline_path = !empty($polyline_data['path']) ? $polyline_data['path'] : array();
								
								/**
								 * Post IDs Polylines */
								 
								if($polylines_type == 'ids'){
								
									foreach($polyline_path as $path_post_id){ 
										
										/**
										 * @polylines_of_post_ids - Already created inside marker objects loop */
										 
										if(isset($polylines_of_post_ids[$polyline_id][$path_post_id])){
										
											$post_id_latlng = $polylines_of_post_ids[$polyline_id][$path_post_id]; ?>
											
											polyline_path.push([<?php echo $post_id_latlng; ?>]); <?php
											
										}
										
									
									} 
								
								/**
							 	* LatLng coordinates Polylines */
							 	
								}elseif($polylines_type == 'latlng'){
									
									foreach($polyline_path as $latlng){ 
										
										if(count(explode(',', $latlng)) == 2){ ?>
											
											polyline_path.push([<?php echo $latlng; ?>]); <?php
											
										}
										
									}
									
								}
								
								?>
								
								if(polyline_path.length > 0){
									
									var polyline_data = {										
										options:{
											path: polyline_path,
											clickable: <?php if($polyline_clickable == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,
											editable: false,
											strokeColor: '<?php echo esc_attr($polyline_color); ?>',
											strokeOpacity: <?php echo esc_attr($polyline_opacity); ?>,
											strokeWeight: <?php echo esc_attr($polyline_weight); ?>,
											geodesic: <?php if($polyline_geodesic == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,
											zIndex: <?php echo esc_attr($polyline_zindex); ?>,
											visible: <?php if($polyline_visible == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,									
										},
										events: {
											click: function(polyline){
												<?php if(!empty($polyline_url)){ ?>
													<?php if($polyline_url_target == 'new_window'){ ?>
														window.location = "<?php echo $polyline_url; ?>";
													<?php }else{ ?>
														window.open("<?php echo $polyline_url; ?>");
													<?php } ?>
												<?php } ?>
											},
											mouseover: function(polyline, event, context){
												<?php if(!empty($polyline_description)){ ?>
												var map = plugin_map.gmap3("get");
												plugin_map.gmap3({
													get:{
														name: "infowindow",
														id: "<?php echo $polyline_id; ?>",
														callback: function(infowindow){
															if(!infowindow){
																plugin_map.gmap3({
																	infowindow:{
																		latLng: event.latLng,
																		id: "<?php echo $polyline_id; ?>",
																		options:{
																			content: '<?php echo $polyline_description; ?>',
																			maxWidth: <?php echo $polyline_infowindow_maxwidth; ?>,
																		}
																	}
																});
															}else{
																infowindow.open(map, polyline);	
															}
														}
													},
												});
												<?php } ?>
											},
											mouseout: function(polyline, event, context){
												<?php if(!empty($polyline_description)){ ?>
												plugin_map.gmap3({
													get:{
														name:"infowindow",
														id: "<?php echo $polyline_id; ?>",
														callback: function(infowindow){
															if(infowindow){
															  infowindow.close();
															}
														}
													}
												});
												<?php } ?>
											},																						
										}										
									};
									
									polyline_values.push(polyline_data);
								
								}
								
								<?php									
								
							}
							
						}
					
					}
					
					/**
					 * Build all Polygon JS objects
					 * @since 2.7
					 * @updated 3.0 [Added clickable, URL & Description options]
					 */
					 
					if(is_array($polygons)){
						
						/**
						 * Loop through all Polylines */
						 
						foreach($polygons as $polygons_type => $polygons_type_data){
							
							/**
							 * Loop through each polygon build from post IDs */
							 
							foreach($polygons_type_data as $polygon_id => $polygon_data){

								/**
								 * Build Polygon JS Object */
							
								$polygon_clickable = !empty($polygon_data['clickable']) ? esc_attr($polygon_data['clickable']) : 'false'; //@since 3.0
								$polygon_url = !empty($polygon_data['url']) ? esc_url($polygon_data['url']) : ''; //@since 3.0
								$polygon_url_target = !empty($polygon_data['url_target']) ? esc_url($polygon_data['url_target']) : 'new_window'; //@since 3.0
								$polygon_description = !empty($polygon_data['description']) ? esc_attr($polygon_data['description']) : ''; //@since 3.0
								$polygon_infowindow_maxwidth = !empty($polygon_data['infowindow_maxwidth']) ? esc_attr($polygon_data['infowindow_maxwidth']) : '250'; //@since 3.0
								$polygon_fill_color = !empty($polygon_data['fill_color']) ? esc_attr($polygon_data['fill_color']) : '#189AC9';
								$polygon_fill_opacity = !empty($polygon_data['fill_opacity']) ? esc_attr($polygon_data['fill_opacity']) : '1';									
								$polygon_geodesic = !empty($polygon_data['geodesic']) ? esc_attr($polygon_data['geodesic']) : 'false';
								$polygon_stroke_color = !empty($polygon_data['stroke_color']) ? esc_attr($polygon_data['stroke_color']) : '#189AC9';
								$polygon_stroke_opacity = !empty($polygon_data['stroke_opacity']) ? esc_attr($polygon_data['stroke_opacity']) : '1';
								$polygon_stroke_weight = !empty($polygon_data['stroke_weight']) ? esc_attr($polygon_data['stroke_weight']) : '2';
								$polygon_stroke_position = !empty($polygon_data['stroke_position']) ? esc_attr($polygon_data['stroke_position']) : 'CENTER';
								$polygon_zindex = !empty($polygon_data['zindex']) ? esc_attr($polygon_data['zindex']) : '1'; 
								$polygon_visible = !empty($polygon_data['visible']) ? esc_attr($polygon_data['visible']) : ''; ?>
								
								var polygon_path = []; <?php
								
								$polygon_path = !empty($polygon_data['path']) ? $polygon_data['path'] : array();

								/**
								 * Post IDs polygons */
								 
								if($polygons_type == 'ids'){
								
									foreach($polygon_path as $path_post_id){ 
										
										/**
										 * @polygons_of_post_ids - Already created inside marker objects loop */

										if(isset($polygons_of_post_ids[$polygon_id][$path_post_id])){
										
											$post_id_latlng = $polygons_of_post_ids[$polygon_id][$path_post_id]; ?>
											
											polygon_path.push([<?php echo $post_id_latlng; ?>]); <?php
											
										}
										
									
									} 
								
								/**
							 	* LatLng coordinates polygons */
							 	
								}elseif($polygons_type == 'latlng'){
									
									foreach($polygon_path as $latlng){ 
										
										if(count(explode(',', $latlng)) == 2){ ?>
											
											polygon_path.push([<?php echo $latlng; ?>]); <?php
											
										}
										
									}
									
								}
								
								?>
								
								if(polygon_path.length > 0){
									
									var polygon_bounds = cspm_get_latLngs_bounds(polygon_path);
									var polygon_center = polygon_bounds.getCenter();

									var polygon_data = {										
										options:{
											paths: polygon_path,
											clickable: <?php if($polygon_clickable == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,
											geodesic: <?php if($polygon_geodesic == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,
											zIndex: <?php echo esc_attr($polygon_zindex); ?>,
											visible: <?php if($polygon_visible == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,
											editable: false,
											strokeColor: '<?php echo esc_attr($polygon_stroke_color); ?>',
											strokeOpacity: <?php echo esc_attr($polygon_fill_opacity); ?>,
											strokeWeight: <?php echo esc_attr($polygon_stroke_weight); ?>,
											fillColor: '<?php echo esc_attr($polygon_fill_color); ?>',
											fillOpacity: <?php echo esc_attr($polygon_fill_opacity); ?>,
											strokePosition: google.maps.StrokePosition['<?php echo esc_attr($polygon_stroke_position); ?>'],
											polygonBounds: polygon_bounds,
											polygonCenter: polygon_center,
										},
										events: {
											click: function(polygon){
												<?php if(!empty($polygon_url)){ ?>
													<?php if($polygon_url_target == 'new_window'){ ?>
														window.location = "<?php echo $polygon_url; ?>";
													<?php }else{ ?>
														window.open("<?php echo $polygon_url; ?>");
													<?php } ?>
												<?php } ?>
											},
											mouseover: function(polygon, event, context){
												<?php if(!empty($polygon_description)){ ?>
												var polygon_center = polygon.polygonCenter;
												var map = plugin_map.gmap3("get");
												plugin_map.gmap3({
													get:{
														name: "infowindow",
														id: "<?php echo $polygon_id; ?>",
														callback: function(infowindow){
															if(!infowindow){
																plugin_map.gmap3({
																	infowindow:{
																		latLng: polygon_center,
																		id: "<?php echo $polygon_id; ?>",
																		options:{
																			content: '<?php echo esc_attr($polygon_description); ?>',
																			maxWidth: <?php echo $polygon_infowindow_maxwidth; ?>,
																		}
																	}
																});
															}else{
																infowindow.open(map, polygon);	
															}
														}
													},
												});
												
												<?php } ?>
											},
											mouseout: function(polygon, event, context){
												<?php if(!empty($polygon_description)){ ?>
												plugin_map.gmap3({
													get:{
														name:"infowindow",
														id: "<?php echo $polygon_id; ?>",
														callback: function(infowindow){
															if(infowindow){
															  infowindow.close();
															}
														}
													}
												});
												<?php } ?>
											},
										}									
									};
	
									polygon_values.push(polygon_data);
									
								}
								
								<?php									
								
							}
							
						}
					
					}
					
					/**
					 * Build all KML layers JS objects
					 * @since 3.0 */
					
					$count_kml_layers = 0;
					 
					if($this->use_kml == 'true' && is_array($this->kml_layers)){
						
						/**
						 * Loop through all KML layers */
						 
						foreach($this->kml_layers as $kml_data){

							/**
							 * Build KML JS Object */
							
							if(isset($kml_data['kml_url']) && !empty($kml_data['kml_url'])){
								
								$kml_url = esc_url($kml_data['kml_url']);
								
								$kml_visibility = !empty($kml_data['kml_visibility']) ? esc_attr($kml_data['kml_visibility']) : 'true';
								
								if($kml_visibility == 'true'){
									
									$kml_name = !empty($kml_data['kml_label']) ? esc_attr($kml_data['kml_label']) : '';
									$kml_suppressInfoWindows = !empty($kml_data['kml_suppressInfoWindows']) ? esc_attr($kml_data['kml_suppressInfoWindows']) : 'false';
									$kml_preserveViewport = !empty($kml_data['kml_preserveViewport']) ? esc_attr($kml_data['kml_preserveViewport']) : 'false';
									$kml_screenOverlays = !empty($kml_data['kml_screenOverlays']) ? esc_attr($kml_data['kml_screenOverlays']) : 'false';
									$kml_zindex = !empty($kml_data['kml_zindex']) ? esc_attr($kml_data['kml_zindex']) : '1'; 
									
									$count_kml_layers++;
									
									?>
									
									var kml_data = {										
										options:{
											url: "<?php echo esc_attr($kml_url); ?>",
											opts:{
												suppressInfoWindows: <?php if($kml_suppressInfoWindows == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,
												preserveViewport: <?php if($kml_preserveViewport == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,												
												screenOverlays: <?php if($kml_screenOverlays == 'false'){ ?> false <?php }else{ ?> true <?php } ?>,
												zIndex: parseInt(<?php echo esc_attr($kml_zindex); ?>),
												kmlName: "<?php echo esc_attr($kml_name); ?>",
											},
											events: {
												status_changed: function(kml){

													var status = kml.status;
													var kmlName = kml.kmlName;
																										
													if(status === "DOCUMENT_NOT_FOUND"){
														alert("The KML Layer "+kmlName+" could not be found. Most likely it is an invalid URL, or the document is not publicly available.");
													
													}else if(status === "DOCUMENT_TOO_LARGE"){
														alert("The KML Layer "+kmlName+" exceeds the file size limits of KmlLayer.");
													
													}else if(status === "FETCH_ERROR"){
														alert("The KML Layer "+kmlName+" could not be fetched.");
													
													}else if(status === "INVALID_DOCUMENT"){
														alert("The KML Layer "+kmlName+" is not a valid KML, KMZ or GeoRSS document.");
													
													}else if(status === "INVALID_REQUEST"){
														alert("The KML Layer "+kmlName+" is invalid.");
													
													}else if(status === "LIMITS_EXCEEDED"){
														alert("The KML Layer "+kmlName+" exceeds the feature limits of KmlLayer.");
													
													}else if(status === "TIMED_OUT"){
														alert("The KML Layer "+kmlName+" could not be loaded within a reasonable amount of time.");
													
													}else if(status === "UNKNOWN"){
														alert("The KML Layer "+kmlName+" failed to load for an unknown reason.");
													}
		
												}
											},
										}
									};

									kml_values.push(kml_data);
									
									<?php
									
								}
								
							}
							
						}
					
					}
										
					?>					
								
					/**
					 * Build the map */

					plugin_map.gmap3({							
						map:{
							options: map_options,							
							onces: {
								tilesloaded: function(map){

									var carousel_output = []; 

									plugin_map.gmap3({ 										
										marker:{
											values: json_markers_data,
											callback: function(markers){
												
												<?php 
																								
												/**
												 * Autofit the map to contain all markers & clusters */

												if($this->autofit == 'true'){ ?>
												
													plugin_map.gmap3({
														get: {
															name: 'marker',
															all:  true,										
														},
														autofit:{}
													});
													
												<?php } ?>
												
												/**
												 * Build the carousel items */
												 
												if(!light_map){
													
													for(var i = 0; i < markers.length; i++){	

														var post_id = markers[i].post_id;
														var is_child = markers[i].is_child;
														var marker_position = markers[i].position;
														
														/**
														 * Convert the LatLng object to array */
														 
														var lat = marker_position.lat();
														var lng = marker_position.lng();											
													
														/**
														 * Create carousel items */
														 
														carousel_output.push('<li id="'+map_id+'_list_items_'+post_id+'" class="'+post_id+' carousel_item_'+(i+1)+'_'+map_id+' cspm_border_radius cspm_border_shadow" data-map-id="'+map_id+'" data-is-child="'+is_child+'" name="'+lat+'_'+lng+'" value="'+(i+1)+'" data-post-id="'+post_id+'" style="width:'+item_width+'px; height:'+item_height+'px; background-color:'+items_background+'; '+item_css+'">');
															carousel_output.push('<div class="cspm_spinner"></div>');							
														carousel_output.push('</li>');
														
														if(i == markers.length-1){
															
															$('ul#codespacing_progress_map_carousel_'+map_id).append(carousel_output.join(''));	
																														
															cspm_init_carousel(null, map_id);
															
														}
														
													}																						
																																					
												}	
												
												<?php 
						
												/**
												 * Geo Targeting
												 * @updated 3.2 [added retina support for user marker icon] */
																		
												if(esc_attr($geo) == "true"){ ?>
													
													if((typeof NProgress !== 'undefined' && NProgress.done()) || typeof NProgress === 'undefined'){
														
														setTimeout(function(){
															
															<?php if(esc_attr($show_user) == 'true'){ ?> 
																var show_user_marker = true; 
															<?php }else{ ?>
																var show_user_marker = false;
															<?php } ?>
															
															<?php
															
															/**
															 * Get marker image size for Retina display
															 * @since 3.2 */
															 
															$marker_img_size = (esc_attr($show_user) == 'true') ? $this->cspm_get_image_size($this->cspm_get_image_path_from_url($user_marker)) : '';
															
															?>
															
															cspm_geolocate(plugin_map, map_id, show_user_marker, '<?php echo esc_attr($user_marker); ?>', '<?php echo esc_attr($marker_img_size); ?>'	, <?php echo esc_attr($user_circle); ?>, <?php echo esc_attr($user_map_zoom); ?>, false);															
															
														}, 1000);
														
													}
												
												<?php } ?>
		
												<?php 
						
												/**
												 * Auto check/select an option/term in the filter
												 * @since 3.0 */
																		
												if($this->faceted_search_option == "true" 
												&& $this->faceted_search_autocheck == "true" 
												&& count($this->faceted_autocheck_terms) > 0){ ?>
														
													setTimeout(function(){

														var inputValues = [];
														
														<?php foreach($this->faceted_autocheck_terms as $key => $val){ ?>
														
															inputValues.push('<?php echo $val; ?>');
														
														<?php } ?>

														/**
														 * Loop throught selected terms and check their related checkboxes or radio button */
														
														for(var i = 0; i < cspm_object_size(inputValues); i++){
																
															var icheck_selector = $('form#faceted_search_form_'+map_id+' input[data-map-id='+map_id+'][data-term-id='+inputValues[i]+']');
															
															if(icheck_selector.is(':visible') && typeof icheck_selector.iCheck === 'function')
																icheck_selector.iCheck('check');
															
														}

														if(typeof $('div.faceted_search_container_'+map_id).mCustomScrollbar === 'function')
															$('form.faceted_search_form[data-map-id='+map_id+'] ul').mCustomScrollbar("scrollTo", 'input[data-map-id='+map_id+'][data-term-id='+inputValues[inputValues.length-1]+']', {timeout: 500});
														
														
													}, 500);
												
												<?php } ?>
														
											},											
											events:{
												mouseover: function(marker, event, elements){
													
													/**
													 * Display the single infobox */
													 
													if(show_infobox == 'true' && infobox_display_event == 'onhover')
														infobox_xhr = cspm_draw_single_infobox(plugin_map, map_id, infobox_div, infobox_type, marker, infobox_xhr, '<?php echo $carousel; ?>');
													
													<?php if(in_array('marker_hover', $this->move_carousel_on)){ ?>
													
														/**
														 * Apply the style for the active item in the carousel */
														 
														if(!light_map){	
															
															var post_id = marker.post_id;
															var is_child = marker.is_child;	
															var i = $('li[id='+map_id+'_list_items_'+post_id+'][data-is-child="'+is_child+'"]').attr('value');	
															
															cspm_call_carousel_item($('ul#codespacing_progress_map_carousel_'+map_id).data('jcarousel'), i);
															cspm_carousel_item_hover_style('li.carousel_item_'+i+'_'+map_id, map_id);
														
														}
													
													<?php } ?>
													
												},	
												mouseout: function(marker, event, elements){

													/**
													 * Hide the infobox */
													 
													if(show_infobox == 'true' && (infobox_display_event == 'onhover' || infobox_display_event == 'onclick') && remove_infobox_on_mouseout == 'true'){
														
														infobox_div.addClass('cspm_animated fadeOutUp');					
														infobox_div.hide().removeClass('cspm_animated fadeOutUp');
														
													}
													
												},
												click: function(marker, event, elements){
													
													var latLng = marker.position;											

													/**
													 * Center the map on that marker */
													
													map.panTo(latLng);
													
													if(show_infobox == 'true')
														cspm_pan_map_to_fit_infobox(plugin_map, map_id, infobox_div);													
															
													/**
													 * Display the single infobox */
													 
													if(json_markers_data.length > 0 && show_infobox == 'true' && infobox_display_event == 'onclick'){
														setTimeout(function(){																										
															infobox_xhr = cspm_draw_single_infobox(plugin_map, map_id, infobox_div, infobox_type, marker, infobox_xhr, '<?php echo $carousel; ?>');
														}, 400);
													}
													
													<?php if(in_array('marker_click', $this->move_carousel_on)){ ?>						
													
														/**
														 * Apply the style for the active item in the carousel */
														 
														if(!light_map){	
															
															var post_id = marker.post_id;
															var is_child = marker.is_child;
															var i = $('li[id='+map_id+'_list_items_'+post_id+'][data-is-child="'+is_child+'"]').attr('value');
														
															cspm_call_carousel_item($('ul#codespacing_progress_map_carousel_'+map_id).data('jcarousel'), i);
															cspm_carousel_item_hover_style('li.carousel_item_'+i+'_'+map_id, map_id);
														
														}
													
													<?php } ?>
													
													<?php 
													
													/**
													 * This will add hover/active style to a list item 
													 * Note: Used only for the extension "List & Filter"
													 * @since 2.8.3 */
													 
													if(class_exists('ProgressMapList')){ ?>

														var post_id = marker.post_id;
	
														if(typeof cspml_animate_list_item == 'function')
															cspml_animate_list_item(map_id, post_id);
																													
													<?php } ?>
													
													<?php
													
													/**
													 * Nearby points of interets.
													 * Add the coordinates of this marker to the list of Proximities ...
													 * ... in order to use them (latLng) to display nearby points of interest ...
													 * ... of that marker 
													 * @since 3.2 */
													 
													if($this->nearby_places_option == 'true'){ ?>
													
														$('li.cspm_proximity_name[data-map-id='+map_id+']').attr('data-marker-latlng', latLng).attr('data-marker-post-id', post_id).removeClass('selected');	
																														
														var map_message_widget = $('div.cspm_map_green_msg_widget[data-map-id='+map_id+']');
														
														map_message_widget.text('<?php esc_html_e('A new location has been selected and can be used to display nearby points of interest!', 'cspm'); ?>').removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});														
														setTimeout(function(){ 
															map_message_widget.removeClass('fadeIn').addClass('fadeOut');
															setTimeout(function(){
																map_message_widget.css({'display':'none'});
															}, 500);
														}, 3000);
																														
														<?php
													}
													
													?>									
													
												}
											}
										}
									});									
									
									<?php
									
									/**
									 * Clustring markers */
									 
									if($this->useClustring == 'true'){ ?>
										clustering_method = true;
										var clusterer = cspm_clustering(plugin_map, map_id, light_map);<?php
									}
									
									/**
									 * Show the Zoom control after the map load */
									 
									if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){ ?>
										$('div.codespacing_map_zoom_in_'+map_id+', div.codespacing_map_zoom_out_'+map_id).show(); <?php 
									}
									
									/**
									 * Show the faceted search after the map load */
									 
									if($faceted_search == "yes" && $this->faceted_search_option == "true" && $this->marker_cats_settings == 'true'){ ?>
										$('div.faceted_search_btn[data-map-id='+map_id+']').show(); 
										<?php if($this->faceted_search_display_status == 'open' || $this->faceted_search_autocheck == 'true'){ ?>
											$('div.faceted_search_btn[data-map-id='+map_id+']').trigger('click');
										<?php } ?>										
									<?php }
								
									/**
									 * Show the search form after the map load */
									 
									if($search_form == "yes" && $this->search_form_option == "true"){ ?> 
										$('div.search_form_btn[data-map-id='+map_id+']').show(); 
										<?php if($this->sf_display_status == 'open'){ ?>
											setTimeout(function(){
												$('div.search_form_btn[data-map-id='+map_id+']').trigger('click');
											}, 100);
										<?php } ?>
									<?php }
									
									if(!$light_map && $map_layout == "map-tglc-bottom"){ ?> $('div.toggle-carousel-bottom').show(); <?php } 
									
									if(!$light_map && $map_layout == "map-tglc-top"){ ?> $('div.toggle-carousel-top').show(); <?php } ?>
								 
									<?php 
			 					
									/**
									 * Show GeoTargeting button
									 * @since 2.8 */
	
									if(esc_attr($geo) == 'true'){ ?>
										$('div.codespacing_geotarget_container[data-map-id='+map_id+']').show();<?php
									} 
									
									?>
									
									<?php
									
									/**
									 * Show zoom to country button after map load 
									 * @since 3.0 */
									 
									if($this->zoom_country_option == 'true'){ ?> 
										$('div.countries_btn[data-map-id='+map_id+']').show(); 
										<?php if($this->zoom_country_display_status == 'open' && $this->faceted_search_autocheck == 'false'){ ?>
											setTimeout(function(){
												$('div.countries_btn[data-map-id='+map_id+']').trigger('click');
											}, 100);
										<?php } ?>
									<?php } ?>
									
									<?php
									
									/**
									 * Show nearby points of interest button after map load 
									 * @since 3.2 */
									 
									if($this->nearby_places_option == 'true'){ ?> 
										$('div.cspm_proximities_btn[data-map-id='+map_id+']').show(); 
										<?php if($this->np_proximities_display_status == 'open'){ ?>
											setTimeout(function(){
												$('div.cspm_proximities_btn[data-map-id='+map_id+']').trigger('click');
											}, 100);
										<?php } ?>
									<?php } ?>
								 
									<?php 
									
									/**
									 * Recenter map button
									 * @since 3.0 */
	
									if(esc_attr($this->recenter_map) == 'true'){ ?>
										$('div.cspm_recenter_map_container[data-map-id='+map_id+']').show();<?php 
									} 
									
									?>
									
									/**
									 * Draw infoboxes (onload event) */
									 
									if(json_markers_data.length > 0 && clustering_method == true && show_infobox == 'true' && infobox_display_event == 'onload'){			
										
										google.maps.event.addListenerOnce(clusterer, 'clusteringend', function(cluster) {																	
											setTimeout(function(){
												cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $this->cspm_infobox(esc_attr($infobox_type), 'multiple', $map_id, $move_carousel_on_infobox_hover, $infobox_link_target); ?>', infobox_type, '<?php echo $carousel; ?>');
												infobox_loaded = true;
											}, 1000);																
										});	
										
									}else if(json_markers_data.length > 0 && clustering_method == false && show_infobox == 'true' && infobox_display_event == 'onload'){
										
										setTimeout(function(){
											cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $this->cspm_infobox(esc_attr($infobox_type), 'multiple', $map_id, $move_carousel_on_infobox_hover, $infobox_link_target); ?>', infobox_type, '<?php echo $carousel; ?>');
											infobox_loaded = true;
										}, 1000);
										
									}else if(json_markers_data.length > 0 && show_infobox == 'true' && infobox_display_event != 'onload'){
										
										infobox_loaded = true;
											
									}

									/**
									 * End the Progress Bar Loader */
									 	
									if(typeof NProgress !== 'undefined')
										NProgress.done();
									
								}
								
							},
							events:{
								click: function(){

									/**
									 * Remove single infobox on map click (onclick, onhover events) */
									 
									if(json_markers_data.length > 0 && show_infobox == 'true' && infobox_display_event != 'onload'){										
										infobox_div.hide();
										infobox_div.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
											infobox_div.hide().removeClass('cspm_animated fadeOutUp');
										});
									}
									
								},
								idle: function(){								
									if(infobox_loaded && !cspm_is_panorama_active(plugin_map)){
										setTimeout(function(){
											if(json_markers_data.length > 0 && show_infobox == 'true' && infobox_display_event == 'onload'){								
												cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $this->cspm_infobox(esc_attr($infobox_type), 'multiple', $map_id, $move_carousel_on_infobox_hover, $infobox_link_target); ?>', infobox_type, '<?php echo $carousel; ?>');
											}
										}, 200);
									}
								},				
								bounds_changed: function(){
									if(json_markers_data.length > 0){
										if(json_markers_data.length > 0 && show_infobox == 'true' && infobox_display_event != 'onload'){
											cspm_set_single_infobox_position(plugin_map, infobox_div);
										}else $('div.cspm_infobox_container[data-map-id='+map_id+']').hide();
									}
								},
								drag: function(){
									if(json_markers_data.length > 0){
										if(show_infobox == 'true' && infobox_display_event != 'onload'){
											cspm_set_single_infobox_position(plugin_map, infobox_div);
										}else $('div.cspm_infobox_container[data-map-id='+map_id+']').hide();
									}
								},
								center_changed: function(){
									setTimeout(function() {
										$('div[class^=cluster_posts_widget]').removeClass('flipInX');
										$('div[class^=cluster_posts_widget]').addClass('cspm_animated flipOutX');
										setTimeout(function() {
											if(typeof $('div.cluster_posts_widget_'+map_id).mCustomScrollbar === 'function'){
												$('div.cluster_posts_widget_'+map_id).mCustomScrollbar("destroy");
											}
										}, 500);
									}, 500);
								}
							}
						},					
												
						<?php 
						
						/**
						 * Draw Polylines
						 * @since 2.7
						 */
						
						if($this->draw_polyline == 'true' && !empty($polyline_objects)){ ?>

							polyline:{
								values: polyline_values
							},
						
						<?php } ?>
						
						<?php 
						
						/**
						 * Draw Polygons
						 * @since 2.7
						 */
						
						if($this->draw_polygon == 'true' && !empty($polygon_objects)){ ?>

							polygon:{
								values: polygon_values
							},
						
						<?php } ?>
						
						<?php 
						
						/**
						 * Display KML Layers
						 * @since 2.7
						 * @updated 3.0 [supports multiple KML layers]
						 */
						 
						if($this->use_kml == 'true' && $count_kml_layers > 0){ ?>
										
							kmllayer:{
								values: kml_values
							},

						<?php } ?>
							
						<?php 
						
						/**
						 * Show the Traffic Layer
						 * @since 2.7
						 */
						
						if($this->traffic_layer == "true"){ ?>
							
							trafficlayer:{},
							
						<?php } ?>
						
						<?php 
						
						/**
						 * Show the Transit Layer
						 * @since 2.7.4
						 */
						
						if($this->transit_layer == "true"){ ?>
							
							transitlayer:{},
							
						<?php } ?>
						 
						<?php 
						
						/**
						 * Set the map style */
						
						if(count($map_styles) > 0 && $this->map_style != 'google-map' && isset($map_styles[$this->map_style])){ ?> 
							<?php $style_title = isset($map_styles[$this->map_style]['title']) ? $map_styles[$this->map_style]['title'] : $this->custom_style_name; ?>
							
							styledmaptype:{
								id: "custom_style",
								options:{
									name: "<?php echo $style_title; ?>",
									alt: "Show <?php echo $style_title; ?>"
								},
								styles: <?php echo $map_styles[$this->map_style]['style']; ?>
							},
							
						<?php } ?>
						 
						<?php 
						
						/**
						 * Echo the post count label */
						
						if($show_posts_count == 'yes' && $this->show_posts_count == 'yes'){ ?>
							
							<?php $widget_top = ($map_layout == "fullscreen-map-top-carousel" || $map_layout == "fit-in-map-top-carousel" || $map_layout == "m-con") ? '10%' : '80%'  ?>
							
							panel:{
								options:{
									content: '<div class="number_of_posts_widget cspm_border_shadow cspm_border_radius"><?php echo $this->cspm_posts_count_clause($l, $map_id); ?></div>',
									middle: true,
									center: true,
									top: '<?php echo $widget_top; ?>',
									right: false,
									bottom: false,
									left:'70%'
								}
							},
							
						<?php } ?>	
						
					});		
					
					/**
					 * Hide/Show UI Controls depending on the streetview visibility */
					
					var mapObject = plugin_map.gmap3('get');
					
					if(typeof mapObject.getStreetView === 'function'){
												
						var streetView = mapObject.getStreetView();
					
						google.maps.event.addListener(streetView, "visible_changed", function(){
							
							if(this.getVisible()){
								
								<?php 
							
								/**
								 * Hide the Zoom control */
								 								
								if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){ ?>
									$('div.codespacing_map_zoom_in_'+map_id+', div.codespacing_map_zoom_out_'+map_id).hide();
								<?php } ?>
								 
								<?php 
																
								/**
								 * Hide the faceted search */

								if($faceted_search == "yes" && $this->faceted_search_option == "true" && $this->marker_cats_settings == "true"){ ?>
									$('div.faceted_search_btn[data-map-id='+map_id+']').hide();
									$('div.faceted_search_container_'+map_id).hide();
								<?php } ?>
								 
								<?php 
																
								/**
								 * Hide the search form */

								if($search_form == "yes" && $this->search_form_option == "true"){ ?>
									$('div.search_form_btn[data-map-id='+map_id+']').hide();
									$('div.search_form_container_'+map_id).hide();									
								<?php } ?>
								 
								<?php 
																
								/**
								 * Hide post count widget */

								if($show_posts_count == 'yes' && $this->show_posts_count == 'yes'){ ?>
									$('div.number_of_posts_widget').hide();
								<?php } ?>
								
								$('div.cspm_infobox_container[data-map-id='+map_id+']').hide();
								 
								<?php 
			 					
								/**
								 * Hide GeoTargeting button
								 * @since 2.8 */

								if(esc_attr($geo) == 'true'){ ?>
									$('div.codespacing_geotarget_container[data-map-id='+map_id+']').hide();
								<?php } ?>
								
								<?php
								
								/**
								 * Hide zoom to country 
								 * @since 3.0 */
								 
								if($this->zoom_country_option == 'true'){ ?> 
									$('div.countries_btn[data-map-id='+map_id+']').hide(); 
									$('div.countries_container_'+map_id).hide();
								<?php } ?>
								 
								<?php 
			 					
								/**
								 * Hide Recenter map button
								 * @since 3.0 */

								if(esc_attr($this->recenter_map) == 'true'){ ?>
									$('div.cspm_recenter_map_container[data-map-id='+map_id+']').hide();
								<?php } ?>
								
								<?php
								
								/**
								 * Hide nearby points of interest
								 * @since 3.2 */
								 
								if($this->nearby_places_option == 'true'){ ?> 
									$('div.cspm_proximities_btn[data-map-id='+map_id+']').hide(); 
									$('div.cspm_proximities_container_'+map_id).hide();
								<?php } ?>
								 
							}else{
								 
								<?php 
																
								/**
								 * Show the Zoom control */

								if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){ ?>
									$('div.codespacing_map_zoom_in_'+map_id+', div.codespacing_map_zoom_out_'+map_id).show();
								<?php } ?>
								 
								<?php 
																
								/**
								 * Show the faceted search */

								if($faceted_search == "yes" && $this->faceted_search_option == "true" && $this->marker_cats_settings == "true"){ ?>
									$('div.faceted_search_btn[data-map-id='+map_id+']').show();
								<?php } ?>
								 
								<?php 
																
								/**
								 * Show the search form */

								if($search_form == "yes" && $this->search_form_option == "true"){ ?>
									$('div.search_form_btn[data-map-id='+map_id+']').show();
								<?php } ?>
								 
								<?php 
																
								/**
								 * Show post count widget */

								if($show_posts_count == 'yes' && $this->show_posts_count == 'yes'){ ?>
									$('div.number_of_posts_widget').show();
								<?php } ?>
								 
								<?php 
			 					
								/**
								 * Show GeoTargeting button
								 * @since 2.8 */

								if(esc_attr($geo) == 'true'){ ?>
									$('div.codespacing_geotarget_container[data-map-id='+map_id+']').show();
								<?php } ?>
								
								<?php
								
								/**
								 * Show zoom to country button
								 * @since 3.0 */
								 
								if($this->zoom_country_option == 'true'){ ?> 
									$('div.countries_btn[data-map-id='+map_id+']').show(); 
								<?php } ?>
								 
								<?php 
			 					
								/**
								 * Show Recenter map button
								 * @since 3.0 */

								if(esc_attr($this->recenter_map) == 'true'){ ?>
									$('div.cspm_recenter_map_container[data-map-id='+map_id+']').show();
								<?php } ?>
								
								<?php
								
								/**
								 * Show nearby points of interest
								 * @since 3.2 */
								 
								if($this->nearby_places_option == 'true'){ ?> 
									$('div.cspm_proximities_btn[data-map-id='+map_id+']').show(); 
								<?php } ?>
								 
								if(json_markers_data.length > 0 && infobox_loaded){
									setTimeout(function(){
										if(show_infobox == 'true' && infobox_display_event == 'onload'){								
											cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $this->cspm_infobox(esc_attr($infobox_type), 'multiple', $map_id, $move_carousel_on_infobox_hover, $infobox_link_target); ?>', infobox_type, '<?php echo $carousel; ?>');
										}
									}, 200);
								}
							}
								
						});
						
					}
					 		
					<?php 
										
					/**
					 * Show error msg when center point is not correct */

					if($this->wrong_center_point){ ?>

						plugin_map.gmap3({
							panel:{
								options:{
									content: '<div class="error_widget"><?php esc_html_e('The map center is incorrect. Please make sure that the Latitude & the Longitude in "Map Settings => Map center" are comma separated!', 'cspm'); ?></div>',
									top: '40%',
									left: '10%',
									right: '10%'								
								}
							}
						}); <?php 
					
					} 
					
					?>
					
					<?php 
					
					/**
					 * Custome zoom controls */
					 
					if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){ ?>
										
						/**
						 * Call zoom-in function */
						 
						cspm_zoom_in('<?php echo $map_id; ?>', $('div.codespacing_map_zoom_in_'+map_id), plugin_map);
					
						/**
						 * Call zoom-out function */
						 
						cspm_zoom_out('<?php echo $map_id; ?>', $('div.codespacing_map_zoom_out_'+map_id), plugin_map); <?php 
					
					} 
					
					?>
					
					<?php 		
					
					/**
					 * Fit map to its container
					 * @since 2.8 */
					
					if($map_layout == 'fit-in-map' || $map_layout == 'fit-in-map-top-carousel'){ ?>
						
						cspm_fitIn_map(map_id);
						$(window).resize(function(){ cspm_fitIn_map(map_id); }); <?php
					
					/**
					 * Fit map to screen size
					 * @since 2.8 */
					
					}else if($map_layout == 'fullscreen-map' || $map_layout == 'fullscreen-map-top-carousel'){ ?>
						
						cspm_fullscreen_map(map_id);
						$(window).resize(function(){ cspm_fullscreen_map(map_id); }); <?php
					
					}

					/**
					 * Resize Carousel when it's a "fullscreen" map or "fit in map"
					 * @since 2.8 */
					
					if($map_layout == 'm-con' || $map_layout == 'fullscreen-map-top-carousel' || $map_layout == 'fit-in-map-top-carousel'){ ?>
					
						cspm_carousel_width(map_id);
						$(window).resize(function(){ cspm_carousel_width(map_id); }); <?php
						
					}	
					
					?>
					 
					<?php 
										
					/**
					 * Recenter the Map on screen resize */

					if(esc_attr($window_resize) == 'yes' && isset($center_point[0]) && !empty($center_point[0]) && isset($center_point[1]) && !empty($center_point[1])){ ?>
						
						/**
						 * Store the window width */
						
						var windowWidth = $(window).width();
	
						$(window).resize(function(){
							
							/**
							 * Check window width has actually changed and it's not just iOS triggering a resize event on scroll */
							 
							if ($(window).width() != windowWidth) {
					
								/**
								 * Update the window width for next time */
								 
								windowWidth = $(window).width();
			
								setTimeout(function(){
									
									var latLng = new google.maps.LatLng(<?php echo $center_point[0]; ?>, <?php echo $center_point[1]; ?>);							
								
									var map = plugin_map.gmap3("get");	
									
									if(typeof map.panTo === 'function')
										map.panTo(latLng);
									
									if(typeof map.setCenter === 'function')

										map.setCenter(latLng);
										
								}, 500);
								
							}
							
						});

					<?php } ?> 
					 
					<?php 
					
					/**
					 * Resolve a problem of Google Maps & jQuery Tabs */
					
					if(!$this->wrong_center_point && !empty($center_point[0]) && !empty($center_point[1])){ ?>					
						
						$(plugin_map_placeholder+':visible').livequery(function(){
							if(_CSPM_MAP_RESIZED[map_id] <= 1){ /* 0 is for the first time loading, 1 is when the user clicks the map tab */
								cspm_center_map_at_point(plugin_map, '<?php echo $map_id ?>', <?php echo $center_point[0]; ?>, <?php echo $center_point[1]; ?>, 'resize');
								_CSPM_MAP_RESIZED[map_id]++;
							}
							cspm_zoom_in_and_out(plugin_map);
						});

					<?php } ?>
					 
					<?php
					
					/**
					 * Add support for the Autocomplete for the address in the search form
					 * @since 2.8 */
					
					if($search_form == "yes" && $this->search_form_option == "true"){ ?>
								
						var input = document.getElementById('cspm_address_'+map_id);
						var autocomplete = new google.maps.places.Autocomplete(input); <?php 
					
					} 
					
					?>
						
					_CSPM_DONE[map_id] = true;
	
				});
			
			</script> 
			
			<?php
			
			$this->cspm_enqueue_styles();
			$this->cspm_enqueue_scripts();			
			
			/**
			 * @since 2.6.3
			 * @updated 2.8.5 */
			 
			$atts_array = apply_filters(
				'cspm_main_map_output_atts',
				array(	
					'map_id' => $map_id,
					'post_ids' => implode(',', $post_ids),
					'faceted_search' => $faceted_search,
					'search_form' => $search_form,					
					'faceted_search_tax_slug' => $faceted_search_tax_slug,
					'faceted_search_tax_terms' => $faceted_search_tax_terms,	
					'geo' => esc_attr($geo),
					'infobox_type' => esc_attr($infobox_type), //@since 2.8.5			
				),
				$atts
			);

			/**
			 * Carousel
			 * @since 2.6.3
			 * @updated 2.8
			 * @updated 2.8.5 
			 * @updated 2.8.6 */
			 
			return apply_filters(
				'cspm_main_map_output', 
				$this->cspm_main_map_output(
					array(
						'map_id' => $map_id,
						'carousel' => $carousel,
						'faceted_search' => $faceted_search,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'search_form' => $search_form,
						'show_infoboxes' => $show_infoboxes,
						'infobox_display_event' => $this->infobox_display_event,
						'infobox_type' => esc_attr($infobox_type), //@since 2.8.5
						'infobox_target_link' => esc_attr($infobox_link_target), //@since 2.8.6
						'map_layout' => $map_layout,
						'geo' => esc_attr($geo),
					)
				),
				$atts_array
			);
			
			return $output;
			
		}
		
		
		/**
		 * Display the carousel
		 *
		 * @since 2.6 
		 * @updated 2.8
		 * @updated 2.8.5
		 * @updated 2.8.6
		 */
		function cspm_main_map_output($atts = array()){
					
			$defaults = array(
				'map_id' => '',
				'carousel' => '',
				'faceted_search' => '',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => '',
				'search_form' => '',
				'show_infoboxes' => '',
				'infobox_display_event' => '',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
				'map_layout' => '',
				'geo' => '',
			);
			
			extract(wp_parse_args($atts, $defaults));

			$layout_style = '';
			
			/**
			 * Define fixed/fullwidth layout height and width */
			 
			if($map_layout != 'fullscreen-map' && $map_layout != 'fit-in-map'){
	
				if($this->layout_type == 'fixed')
					$layout_style = "width:".$this->layout_fixed_width."px; height:".$this->layout_fixed_height."px;";
				else ($map_layout == "mu-cd" || $map_layout == "md-cu") ? $layout_style = "width:100%; height:".($this->layout_fixed_height+20)."px;" 
																		: $layout_style = "width:100%; height:".$this->layout_fixed_height."px;";
	
			}elseif($map_layout == 'fit-in-map'){ 
				
				$layout_style = "width:100%;";
				
			}elseif($map_layout == 'fullscreen-map'){
				
				$layout_style = "display:block; margin:0; padding:0; position:absolute; top:0; right:0; bottom:0; left:0; z-index:9999"; 
			
			}	
						
			$output = '';
			
			/**
			 * Plugin Container */
				
			$output .= '<div class="codespacing_progress_map_area" data-map-id="'.$map_id.'" data-show-infobox="'.$show_infoboxes.'" data-infobox-display-event="'.$infobox_display_event.'" '.apply_filters('cspm_container_custom_atts', '', $map_id).' style="'.$layout_style.'">';
				
				/**
				 * This is usefull to know the page template where the map is displayed in order
				 * to execute hooks or function by template page
				 * @since 2.7.5 */
				 
				if(is_single()){
					$queried_object = get_queried_object();
					$page_id = $queried_object->post_type;		
				}elseif(is_author()){
					$page_id = 'author';
				}else $page_id = get_the_ID();
				
				$output .= '<input type="hidden" name="cspm_map_page_id_'.$map_id.'" id="cspm_map_page_id_'.$map_id.'" value="'.$page_id.'" />';
				
				/**
				 * Plugin's Map */
											
				/* =============================== */
				/* ==== Map-Up, Carousel-Down ==== */
				/* =============================== */
				
				if($map_layout == "mu-cd"){
									
					if($this->items_view == "listview")
						$carousel_height = $this->horizontal_item_height + 8;
						
					elseif($this->items_view == "gridview")
						$carousel_height = $this->vertical_item_height + 8;
					
					/**
					 * Detect Mobile browsers and adjust the map height depending on the result	*/
					 
					if(!$this->cspm_detect_mobile_browser()){
						
						$map_height = ($this->show_carousel == 'true' && $carousel == "yes") ? $this->layout_fixed_height - $carousel_height . 'px' : $this->layout_fixed_height . 'px';
						
					}else $map_height = $this->layout_fixed_height . 'px';
					
					/**
					 * Layout
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_map_up_carousel_down_layout(array(
						'map_id' => $map_id,
						'map_height' => $map_height,								
						'carousel' => $carousel,
						'carousel_height' => $carousel_height,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
									
				/* =============================== */
				/* ==== Map-Down, Carousel-Up ==== */
				/* =============================== */
				
				}elseif($map_layout == "md-cu"){
					
					if($this->items_view == "listview")
						$carousel_height = $this->horizontal_item_height + 8;
						
					elseif($this->items_view == "gridview")
						$carousel_height = $this->vertical_item_height + 8;
					
					/**
					 * Detect Mobile browsers and adjust the map height depending on the result	*/
					 
					if(!$this->cspm_detect_mobile_browser()){
						
						$map_height = ($this->show_carousel == 'true' && $carousel == "yes") ? $this->layout_fixed_height - $carousel_height . 'px' : $this->layout_fixed_height . 'px';
						
					}else $map_height = $this->layout_fixed_height . 'px';
					
					/**
					 * Layout
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_map_down_carousel_up_layout(array(
						'map_id' => $map_id,
						'map_height' => $map_height,								
						'carousel' => $carousel,
						'carousel_height' => $carousel_height,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
									
									
				/* ================================== */
				/* ==== Map-Right, Carousel-Left ==== */
				/* ================================== */
				
				}elseif($map_layout == "mr-cl"){
					
					if($this->items_view == "listview"){
						
						$carousel_width = $this->horizontal_item_width + 8;
						
					}elseif($this->items_view == "gridview"){
						
						$carousel_width = $this->vertical_item_width + 8;
						
					}
					
					/**
					 * Layout
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_map_right_carousel_left_layout(array(
						'map_id' => $map_id,
						'carousel' => $carousel,
						'carousel_width' => $carousel_width,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
									
				/* ================================== */
				/* ==== Map-Left, Carousel-Right ==== */
				/* ================================== */
				
				}elseif($map_layout == "ml-cr"){
					
					if($this->items_view == "listview"){
						
						$carousel_width = $this->horizontal_item_width + 8;
						
					}elseif($this->items_view == "gridview"){
						
						$carousel_width = $this->vertical_item_width + 8;
						
					}
					
					/**
					 * Layout
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_map_left_carousel_right_layout(array(
						'map_id' => $map_id,
						'carousel' => $carousel,
						'carousel_width' => $carousel_width,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
				
				/* ====================== */
				/* ==== Only The Map ==== */
				/* ====================== */
				
				}elseif($map_layout == "fullscreen-map" || $map_layout == "fit-in-map"){
					
					/**
					 * Layout
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_only_map_layout(array(
						'map_id' => $map_id,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
				
				/* ============================================== */
				/* ==== Fullscreen Map/Fit in map & Carousel ==== */
				/* ============================================== */
				
				}elseif($map_layout == "fullscreen-map-top-carousel" || $map_layout == "fit-in-map-top-carousel"){
					
					if($this->items_view == "listview")
						$carousel_height = $this->horizontal_item_height + 8;
						
					elseif($this->items_view == "gridview")
						$carousel_height = $this->vertical_item_height + 8;
					
					/**
					 * Layout
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_full_map_carousel_over_layout(array(
						'map_id' => $map_id,
						'carousel' => $carousel,
						'carousel_height' => $carousel_height,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
					
	
				/* ============================ */
				/* ==== Map, Carousel over ==== */
				/* ============================ */
				
				}elseif($map_layout == "m-con"){
					
					if($this->items_view == "listview")
						$carousel_height = $this->horizontal_item_height + 8;
						
					elseif($this->items_view == "gridview")
						$carousel_height = $this->vertical_item_height + 8;
					
					$map_height = $this->layout_fixed_height . 'px';
					
					/**
					 * Layout
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_map_up_carousel_over_layout(array(
						'map_id' => $map_id,
						'map_height' => $map_height,								
						'carousel' => $carousel,
						'carousel_height' => $carousel_height,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));

	
				/* ======================================== */
				/* ==== Map, Carousel toggled from top ==== */
				/* ======================================== */
				
				}elseif($map_layout == "map-tglc-top"){
					
					$map_height = $this->layout_fixed_height . 'px';
					
					if($this->items_view == "listview")
						$carousel_height = $this->horizontal_item_height + 8;
						
					elseif($this->items_view == "gridview")
						$carousel_height = $this->vertical_item_height + 8;
					
					/**
					 * Layout
					 * @updated 2.8.5 */
						
					$output .= $this->cspm_map_toggle_carousel_top_layout(array(
						'map_id' => $map_id,
						'map_height' => $map_height,								
						'carousel' => $carousel,
						'carousel_height' => $carousel_height,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
					
	
				/* =========================================== */
				/* ==== Map, Carousel toggled from bottom ==== */
				/* =========================================== */
				
				}elseif($map_layout == "map-tglc-bottom"){
					
					$map_height = $this->layout_fixed_height . 'px';
					
					if($this->items_view == "listview")
						$carousel_height = $this->horizontal_item_height + 8;
						
					elseif($this->items_view == "gridview")
						$carousel_height = $this->vertical_item_height + 8;
					
					/**
					 * Layout
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_map_toggle_carousel_bottom_layout(array(
						'map_id' => $map_id,
						'map_height' => $map_height,								
						'carousel' => $carousel,
						'carousel_height' => $carousel_height,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
										
				}
				
			$output .= '</div>';
			
			return $output;
			
		} 
		
		
		/**
		 * Build the Polyline PHP Objects
		 * @return - Array of all polylines
		 *
		 * @since 2.7 
		 * @updated 3.0 [Added clickable, URL, URL Target, Description & infowindow max-width options]
		 */
		function cspm_build_polyline_objects($lines_segments){
			
			$polyline_paths = array();
			
			if(!empty($lines_segments) && is_array($lines_segments)){
				
				/**
				 * Loopt through all available Polylines */
				 
				foreach($lines_segments as $polyline_id => $single_line_segments){
					
					$line_segments_path = (array) $single_line_segments;
					
					$polyline_id = (isset($line_segments_path['polyline_name'])) ? $line_segments_path['polyline_name'] : '';
					$polyline_path = (isset($line_segments_path['polyline_path'])) ? $line_segments_path['polyline_path'] : '';
					
					if(!empty($polyline_id) && !empty($polyline_path)){
						
						$polyline_clickable = (isset($line_segments_path['polyline_clickable'])) ? $line_segments_path['polyline_clickable'] : 'false'; //@since 3.0
						$polyline_url = (isset($line_segments_path['polyline_url'])) ? esc_url($line_segments_path['polyline_url']) : ''; //@since 3.0
						$polyline_url_target = (isset($line_segments_path['polyline_url_target'])) ? esc_url($line_segments_path['polyline_url_target']) : 'new_window'; //@since 3.0
						$polyline_description = (isset($line_segments_path['polyline_description'])) ? esc_attr($line_segments_path['polyline_description']) : ''; //@since 3.0
						$polyline_infowindow_maxwidth = (isset($line_segments_path['polyline_infowindow_maxwidth'])) ? $line_segments_path['polyline_infowindow_maxwidth'] : '250'; //@since 3.0
						$polyline_geodesic = (isset($line_segments_path['polyline_geodesic'])) ? $line_segments_path['polyline_geodesic'] : 'false';
						$polyline_strokeColor = (isset($line_segments_path['polyline_strokeColor'])) ? $line_segments_path['polyline_strokeColor'] : '#189AC9';
						$polyline_strokeOpacity = (isset($line_segments_path['polyline_strokeOpacity'])) ? $line_segments_path['polyline_strokeOpacity'] : '1';
						$polyline_strokeWeight = (isset($line_segments_path['polyline_strokeWeight'])) ? $line_segments_path['polyline_strokeWeight'] : '2';	
						$polyline_zIndex = (isset($line_segments_path['polyline_zIndex'])) ? $line_segments_path['polyline_zIndex'] : '1';				
						$polyline_visibility = (isset($line_segments_path['polyline_visibility'])) ? $line_segments_path['polyline_visibility'] : 'true';
						
						/**
						 * Check if the polyline segments are LatLng coordinate.
						 * If not, line segments must be post IDs. */
						 
						if(strpos($polyline_path, '],[') !== false){
						
							$explode_polyline_path = str_replace('],[', '|', $polyline_path);
							$polyline_paths['latlng'][$polyline_id] = array(
								'path' => explode('|', str_replace(array('[', ']'), '', $explode_polyline_path)),
								'clickable' => $polyline_clickable, //@since 3.0
								'url' => $polyline_url, //@since 3.0
								'url_target' => $polyline_url_target, //@since 3.0
								'description' => $polyline_description, //@since 3.0
								'infowindow_maxwidth' => $polyline_infowindow_maxwidth, //@since 3.0
								'geodesic' => $polyline_geodesic,
								'color' => $polyline_strokeColor,
								'opacity' => str_replace(',', '.', $polyline_strokeOpacity),
								'weight' => $polyline_strokeWeight,
								'zindex' => $polyline_zIndex,
								'visible' => $polyline_visibility,
							);		
							
						}else{
	
							$polyline_paths['ids'][$polyline_id] = array(
								'path' => explode(',', $polyline_path),
								'clickable' => $polyline_clickable, //@since 3.0
								'url' => $polyline_url, //@since 3.0
								'url_target' => $polyline_url_target, //@since 3.0								
								'description' => $polyline_description, //@since 3.0
								'infowindow_maxwidth' => $polyline_infowindow_maxwidth, //@since 3.0																
								'geodesic' => $polyline_geodesic,
								'color' => $polyline_strokeColor,
								'opacity' => str_replace(',', '.', $polyline_strokeOpacity),
								'weight' => $polyline_strokeWeight,
								'zindex' => $polyline_zIndex,
								'visible' => $polyline_visibility,
							);					
						
						}
					
					}
					
				}
				
			}
			
			return $polyline_paths;
			
		}
		

		/**
		 * Build the Polygon PHP Objects
		 * @return - Array of all polygons
		 * 
		 * @since 2.7 
		 * @updated 3.0 [Added clickable, URL, URL Target, Description & infowindow max-width options]
		 */		 
		function cspm_build_polygon_objects($lines_segments){
			
			$polygon_paths = array();
			
			if(!empty($lines_segments) && is_array($lines_segments)){
				
				/**
				 * Loopt through all available Polylgons */
				 
				foreach($lines_segments as $polygon_id => $single_line_segments){
										
					$line_segments_path = (array) $single_line_segments;

					$polygon_id = (isset($line_segments_path['polygon_name'])) ? $line_segments_path['polygon_name'] : '';
					$polygon_path = (isset($line_segments_path['polygon_path'])) ? $line_segments_path['polygon_path'] : '';

					if(!empty($polygon_id) && !empty($polygon_path)){
						
						$polygon_clickable = (isset($line_segments_path['polygon_clickable'])) ? $line_segments_path['polygon_clickable'] : 'false'; //@since 3.0
						$polygon_url = (isset($line_segments_path['polygon_url'])) ? esc_url($line_segments_path['polygon_url']) : ''; //@since 3.0
						$polygon_url_target = (isset($line_segments_path['polygon_url_target'])) ? esc_url($line_segments_path['polygon_url_target']) : 'new_window'; //@since 3.0						
						$polygon_description = (isset($line_segments_path['polygon_description'])) ? esc_attr($line_segments_path['polygon_description']) : ''; //@since 3.0
						$polygon_infowindow_maxwidth = (isset($line_segments_path['polygon_infowindow_maxwidth'])) ? $line_segments_path['polygon_infowindow_maxwidth'] : '250'; //@since 3.0						
						$polygon_fillColor = (isset($line_segments_path['polygon_fillColor'])) ? $line_segments_path['polygon_fillColor'] : '#189AC9';
						$polygon_fillOpacity = (isset($line_segments_path['polygon_fillOpacity'])) ? $line_segments_path['polygon_fillOpacity'] : '1';
						$polygon_geodesic = (isset($line_segments_path['polygon_geodesic'])) ? $line_segments_path['polygon_geodesic'] : 'false';
						$polygon_strokeColor = (isset($line_segments_path['polygon_strokeColor'])) ? $line_segments_path['polygon_strokeColor'] : '#189AC9';
						$polygon_strokeOpacity = (isset($line_segments_path['polygon_strokeOpacity'])) ? $line_segments_path['polygon_strokeOpacity'] : '1';
						$polygon_strokeWeight = (isset($line_segments_path['polygon_strokeWeight'])) ? $line_segments_path['polygon_strokeWeight'] : '2';	
						$polygon_strokePosition = (isset($line_segments_path['polygon_strokePosition'])) ? $line_segments_path['polygon_strokePosition'] : 'CENTER';
						$polygon_zIndex = (isset($line_segments_path['polygon_zIndex'])) ? $line_segments_path['polygon_zIndex'] : '1';				
						$polygon_visibility = (isset($line_segments_path['polygon_visibility'])) ? $line_segments_path['polygon_visibility'] : 'true';
						
						/**
						 * Check if the polygon segments are LatLng coordinate.
						 * If not, line segments must be post IDs. */
						 
						if(strpos($polygon_path, '],[') !== false){
						
							$explode_polygon_path = str_replace('],[', '|', $polygon_path);
							$polygon_paths['latlng'][$polygon_id] = array(
								'path' => explode('|', str_replace(array('[', ']'), '', $explode_polygon_path)),
								'fill_color' => $polygon_fillColor,
								'clickable' => $polygon_clickable, //@since 3.0
								'url' => $polygon_url, //@since 3.0
								'url_target' => $polygon_url_target, //@since 3.0
								'description' => $polygon_description, //@since 3.0	
								'infowindow_maxwidth' => $polygon_infowindow_maxwidth, //@since 3.0																							
								'fill_opacity' => str_replace(',', '.', $polygon_fillOpacity),
								'geodesic' => $polygon_geodesic,
								'stroke_color' => $polygon_strokeColor,
								'stroke_opacity' => str_replace(',', '.', $polygon_strokeOpacity),
								'stroke_position' => $polygon_strokePosition,
								'stroke_weight' => $polygon_strokeWeight,
								'zindex' => $polygon_zIndex,
								'visible' => $polygon_visibility,
							);		
							
						}else{
	
							$polygon_paths['ids'][$polygon_id] = array(
								'path' => explode(',', $polygon_path),
								'clickable' => $polygon_clickable, //@since 3.0
								'url' => $polygon_url, //@since 3.0
								'url_target' => $polygon_url_target, //@since 3.0
								'description' => $polygon_description, //@since 3.0	
								'infowindow_maxwidth' => $polygon_infowindow_maxwidth, //@since 3.0																																						
								'fill_color' => $polygon_fillColor,
								'fill_opacity' => str_replace(',', '.', $polygon_fillOpacity),
								'geodesic' => $polygon_geodesic,
								'stroke_color' => $polygon_strokeColor,
								'stroke_opacity' => str_replace(',', '.', $polygon_strokeOpacity),
								'stroke_position' => $polygon_strokePosition,
								'stroke_weight' => $polygon_strokeWeight,
								'zindex' => $polygon_zIndex,
								'visible' => $polygon_visibility,
							);					
						
						}
					
					}
					
				}
				
			}
			
			return $polygon_paths;
			
		}		
				
				
		/**
		 * Create the infobox of the marker
		 *
		 * @since 2.5 
		 * @updated 2.7 
		 * @updated 2.8.6
		 */
		function cspm_infobox($infobox_type, $status, $map_id, $move_carousel_on_infobox_hover = 'true', $infobox_link_target = 'same_window'){

			$output = $style = '';
			
			if($infobox_type == 'square_bubble' || $infobox_type == 'rounded_bubble')
				$style = 'style="width:60px; height:60px;"';
				
			elseif($infobox_type == 'cspm_type1')
				$style = 'style="width:380px; height:120px;"';
				
			elseif($infobox_type == 'cspm_type2')
				$style = 'style="width:180px; height:180px;"';
				
			elseif($infobox_type == 'cspm_type3')
				$style = 'style="width:250px; height:50px;"';
				
			elseif($infobox_type == 'cspm_type4')
				$style = 'style="width:250px; height:50px;"';
				
			elseif($infobox_type == 'cspm_type5')
				$style = 'style="width:400px; height:300px;"';
				
			$output .= '<div class="cspm_infobox_container cspm_border_shadow cspm_infobox_'.$status.' cspm_infobox_'.$map_id.' '.$infobox_type.'" '.$style.' data-map-id="'.$map_id.'" data-move-carousel="'.$move_carousel_on_infobox_hover.'" data-infobox-link-target="'.$infobox_link_target.'">';
				$output .= '<div class="blue_cloud"></div>';
				$output .= '<div class="cspm_arrow_down '.$infobox_type.'"></div>';
			$output .= '</div>';
			
			return $output;
			
		}
		
		
		/**
		 * Draw the infobox content
		 *
		 * @since 2.5 
		 * @updated 2.7 
		 * @updated 2.8.6
		 */
		function cspm_infobox_content(){
	
			$post_id = esc_attr($_POST['post_id']);
			$infobox_type = esc_attr($_POST['infobox_type']);
			$map_id = esc_attr($_POST['map_id']);
			$status = esc_attr($_POST['status']);
			$carousel = esc_attr($_POST['carousel']);		
			$infobox_link_target = esc_attr($_POST['infobox_link_target']);	//@since 2.8.6	
			
			/**
			 * Reload map settings */
			 
			$this->map_object_id = esc_attr($_POST['map_object_id']);
			$this->map_settings = $_POST['map_settings'];
				
			$no_title = array(); // Infoboxes to display with no title
			$no_link = array(); // Infobox to display whit no link
			$no_description = array('square_bubble', 'rounded_bubble', 'cspm_type2', 'cspm_type3', 'cspm_type4'); // Infoboxes to display with no description
			$no_image = array('cspm_type4'); // Infoboxes to display with no image
			
			if(!in_array($infobox_type, $no_title)){
				
				$item_title = apply_filters(
					'cspm_custom_infobox_title', 
					stripslashes_deep(
						$this->cspm_items_title(array(
							'post_id' => $post_id, 
							'title' => $this->cspm_get_map_option('items_title'), 
							'click_title_option' => false,
							'click_on_title' => $this->cspm_get_map_option('click_on_title'),
							'external_link' => $this->cspm_get_map_option('external_link'),
						))
					), 
					$post_id
				); 
				
			}
			
			if(!in_array($infobox_type, $no_description)){
				
				$item_description = apply_filters(
					'cspm_custom_infobox_description', 
					stripslashes_deep(
						$this->cspm_items_details($post_id, $this->cspm_get_map_option('items_details'))
					), 
					$post_id
				);
				
			}
			
			if(!in_array($infobox_type, $no_link)) 
				$the_permalink = $this->cspm_get_permalink($post_id);
			
			if(!in_array($infobox_type, $no_image)){
				
				/**
				 * Infobox CSS style */
				 
				if($infobox_type == 'square_bubble' || $infobox_type == 'rounded_bubble')
					$parameter = array( 'style' => "width:50px; height:50px;" );
					
				elseif($infobox_type == 'cspm_type1')
					$parameter = array( 'style' => "width:160px; height:120px;" );
					
				elseif($infobox_type == 'cspm_type2')
					$parameter = array( 'style' => "width:180px; height:132px;" );
					
				elseif($infobox_type == 'cspm_type3' || $infobox_type == 'cspm_type5')
					$parameter = array( 'style' => "width:70px; height:50px;" );
					
				elseif($infobox_type == 'cspm_type4')
					$parameter = array();
				
				/**
				 * Get Infobox Image */
				
				$infobox_image_size = has_image_size('cspm-horizontal-thumbnail-map'.$this->map_object_id) 
					? 'cspm-horizontal-thumbnail-map'.$this->map_object_id
					: 'cspm-horizontal-thumbnail-map';

				if($infobox_type == 'square_bubble' || $infobox_type == 'rounded_bubble'){
					
					$infobox_thumb = get_the_post_thumbnail($post_id, 'cspm-marker-thumbnail', $parameter);
					
				}elseif($infobox_type == 'cspm_type1'){
				
					$infobox_thumb = get_the_post_thumbnail($post_id, $infobox_image_size, $parameter);
					
				}else $infobox_thumb = get_the_post_thumbnail($post_id, $infobox_image_size, $parameter);
				
				if(empty($infobox_thumb))
					$infobox_thumb = get_the_post_thumbnail($post_id, $infobox_image_size, $parameter);

			}
			
			$post_thumbnail = apply_filters('cspm_infobox_thumb', $infobox_thumb, $post_id, $infobox_type, $parameter);

			$target = ($infobox_link_target == 'new_window') ? ' target="_blank"' : ''; 
			$the_post_link = ($infobox_link_target == 'disable') ? $item_title : '<a href="'.$the_permalink.'" title="'.$item_title.'"'.$target.'>'.$item_title.'</a>'; 
			
			$output = '';
			
			$output .= '<div class="cspm_infobox_content_container '.$status.' infobox_'.$map_id.' '.$infobox_type.'" data-map-id="'.$map_id.'" data-post-id="'.$post_id.'" data-show-carousel="'.$carousel.'">';
				
				if($infobox_type == 'square_bubble' || $infobox_type == 'rounded_bubble'){
					
					$output .= '<div class="cspm_infobox_img">';
						$output .= ($infobox_link_target != 'disable') ? '<a href="'.$the_permalink.'" title="'.$item_title.'"'.$target.'>'.$post_thumbnail.'</a>' : $post_thumbnail;
					$output .= '</div>';
					$output .= '<div class="cspm_arrow_down '.$infobox_type.'"></div>';
					
				}elseif($infobox_type == 'cspm_type1'){
					
					$output .= '<div class="cspm_infobox_img">'.$post_thumbnail.'</div>';
					$output .= '<div class="cspm_infobox_content">';
						$output .= '<div class="title">'.$the_post_link.'</div>';
						$output .= '<div class="description">'.$item_description.'</div>';
					$output .= '</div>';
					$output .= '<div style="clear:both"></div>';
					$output .= '<div class="cspm_arrow_down"></div>';
					
				}elseif($infobox_type == 'cspm_type2'){
									
					$output .= '<div class="cspm_infobox_img">'.$post_thumbnail.'</div>';
					$output .= '<div class="cspm_infobox_content">';
						$output .= '<div class="title">'.$the_post_link.'</div>';
					$output .= '</div>';
					$output .= '<div class="cspm_arrow_down"></div>';
					
				}elseif($infobox_type == 'cspm_type3'){
									
					$output .= '<div class="cspm_infobox_img">'.$post_thumbnail.'</div>';
					$output .= '<div class="cspm_infobox_content">';
						$output .= '<div class="title">'.$the_post_link.'</div>';
					$output .= '</div>';
					$output .= '<div class="cspm_arrow_down"></div>';
					
				}elseif($infobox_type == 'cspm_type4'){
									
					$output .= '<div class="cspm_infobox_content">';
						$output .= '<div class="title">'.$the_post_link.'</div>';
					$output .= '</div>';
					$output .= '<div class="cspm_arrow_down"></div>';
				
				/**
				 * @since 2.7 */
				 	
				}elseif($infobox_type == 'cspm_type5'){

					$output .= '<div class="cspm_infobox_content">';
						$output .= '<div>';
							$output .= '<div class="cspm_infobox_img">'.$post_thumbnail.'</div>';
							$output .= '<div class="title">'.$the_post_link.'</div>';
						$output .= '</div><div style="clear:both"></div>';
						$output .= '<div class="description">';
							$post_record = get_post($post_id, ARRAY_A, 'display');
							$post_content = trim(preg_replace('/\s+/', ' ', $post_record['post_content']));
							$output .= apply_filters('cspm_large_infobox_content', $post_content, $post_id);
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div style="clear:both"></div>';
					$output .= '<div class="cspm_arrow_down"></div>';
					
				}
			
			$output .= '</div>';
			
			die($output);
			
		}
		
		
		/**
		 * This contains all the UI elements that will be displayed in the map
		 *
		 * @updated 2.8.5
		 * @updated 2.8.6
		 * @updated 3.0 [Added "Zoom to country" feature]
		 * @updated 3.2 [Added map elements display order + Error message widget]
		 */
		function cspm_map_interface_element($atts = array(), $extensions = array()){			
			
			extract( wp_parse_args( $atts, array(
				'map_id' => 'initial',		
				'carousel' => '',
				'faceted_search' => '',
				'search_form' => '',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
				'extensions' => $extensions,
			)));

			$output = '';
			
			/**
			 * Message widgets that appears on the top right corner of the map to display "Errors/Infos/Warnings"
			 * @since 3.2 */
			
			$output .= '<div class="cspm_map_red_msg_widget cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'"></div>';
			$output .= '<div class="cspm_map_green_msg_widget cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'"></div>';

			/**
			 * This dot appears when the pin is fired */
			 
			$output .= '<div id="pulsating_holder" class="'.$map_id.'_pulsating"><div class="dot"></div></div>';
	
			/**
			 * Single Infobox
			 * @updated 2.8.5 */

			if($this->show_infobox == 'true' && $this->infobox_display_event != 'onload')
				$output .= $this->cspm_infobox($infobox_type, 'single', $map_id, 'true', $infobox_target_link);
			
			/**
			 * Zoom Control */
			 
			if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){
			
				$output .= '<div class="codespacing_zoom_container">';
					$output .= '<div class="codespacing_map_zoom_in_'.$map_id.' cspm_zoom_in_control cspm_border_shadow cspm_border_top_radius" title="'.__('Zoom in', 'cspm').'">';
						$output .= '<img src="'.$this->zoom_in_icon.'" />';
					$output .= '</div>';
					$output .= '<div class="codespacing_map_zoom_out_'.$map_id.' cspm_zoom_out_control cspm_border_shadow cspm_border_bottom_radius" title="'.__('Zoom out', 'cspm').'">';
						$output .= '<img src="'.$this->zoom_out_icon.'" />';
					$output .= '</div>';
				$output .= '</div>';
			
			}
			
			/**
			 * Cluster Posts widget	*/
			 
			$output .= '<div class="cluster_posts_widget_'.$map_id.' cspm_border_shadow"><div class="blue_cloud"></div></div>';
			
			/**
			 * [@top_positions] | Contains all available top positions (in pixels). Listed from lower to upper.
			 * A top position refers to an available position on the map where an element can be displayed.
			 */
			
			$top_positions = apply_filters('cspm_map_elements_top_positions', array('115px', '165px'), str_replace('map', '', $map_id));
				
				foreach($this->map_vertical_elements_order as $display_order){
					
					if($display_order == 'recenter_map'){
						
						/**
						 * Recenter the map
						 * @since 3.0 */
						 
						if($this->recenter_map == 'true'){
							
							$top_position = array_shift($top_positions);
							
							$recenter_btn_img = apply_filters('cspm_recenter_map_btn_img', $this->plugin_url.'img/recenter.png', str_replace('map', '', $map_id));
							
							$output .= '<div class="cspm_recenter_map_container" data-map-id="'.$map_id.'" style="top:'.$top_position.'">';
								$output .= '<div class="cspm_recenter_map_btn_'.$map_id.' cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'" title="'.__('Recenter the map', 'cspm').'">';
									$output .= '<img src="'.$recenter_btn_img.'" />';
								$output .= '</div>';					
							$output .= '</div>';
						
						}
					
					}elseif($display_order == 'geo'){
						
						/**
						 * Geo targeting
						 * @since 2.8 */
						 
						if($geo == 'true'){
							
							$top_position = array_shift($top_positions);
							
							$geo_btn_img = apply_filters('cspm_geo_btn_img', $this->plugin_url.'img/geoloc.png', str_replace('map', '', $map_id));
							
							$output .= '<div class="codespacing_geotarget_container" data-map-id="'.$map_id.'" style="top:'.$top_position.'">';
								$output .= '<div class="codespacing_map_geotarget_'.$map_id.' cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'" title="'.__('Show your position', 'cspm').'">';
									$output .= '<img src="'.$geo_btn_img.'" />';
								$output .= '</div>';
							$output .= '</div>';
						
						}
						
					}
					
				}
			
			/**
			 * [@left_positions] | Contains all available left positions (in pixels). Listed from lower to upper.
			 * A left position refers to an available position on the map where an element can be displayed.
			 */
				
			$left_positions = apply_filters('cspm_map_elements_left_positions', array('60px', '110px', '160px', '210px'), str_replace('map', '', $map_id));
			
				foreach($this->map_horizontal_elements_order as $display_order){
					
					if($display_order == 'zoom_country'){
						
						/**
						 * Zoom to country
						 * @since 3.0 */
								
						if($this->zoom_country_option == 'true'){
												
							$left_position = array_shift($left_positions);
												
							$output .= $this->cspm_countries_list($map_id, $left_position);
						
						}
				
					}elseif($display_order == 'search_form'){
						
						/**
						 * Search form */
						 
						if($search_form == "yes" && $this->search_form_option == "true"){
												
							$left_position = array_shift($left_positions);
							
							$output .= $this->cspm_search_form(array(
								'map_id' => $map_id,
								'carousel' => $carousel,
								'left_position' => $left_position,
								'extensions' => $extensions,
							));
						}
				
					}elseif($display_order == 'faceted_search'){
						
						/**
						 * Faceted search
						 * @updated 2.8.5 */
						 
						if($faceted_search == "yes" && $this->faceted_search_option == "true" && $this->marker_cats_settings == "true"){
												
							$left_position = array_shift($left_positions);
							
							$output .= $this->cspm_faceted_search(array(
								'map_id' => $map_id,
								'carousel' => $carousel,
								'faceted_search_tax_slug' => $faceted_search_tax_slug,
								'faceted_search_tax_terms' => $faceted_search_tax_terms,
								'left_position' => $left_position,
								'extensions' => $extensions,
							));
						
						}
						
					}elseif($display_order == 'proximities'){
						
						/**
						 * Nearby Points of interest
						 * @since 3.2 */
						
						if($this->nearby_places_option == 'true'){
																			
							$left_position = array_shift($left_positions);
												
							$output .= $this->cspm_get_proximities_list(array(
								'map_id' => $map_id,
								'left_position' => $left_position,
								'extensions' => $extensions,
							)); //'<div class="cspm_proximities_btn" data-map-id="'.$map_id.'" data-proximity-id="amusement_park">Amusement park</div>';
							//$output .= '<div id="cspm_place_name_container_'.$map_id.'" class="cspm_place_name_container geoloc_'.$geoloc.' cspm_border_radius cspm_border_shadow text-center"></div>';
						
						}
						
					}
				
				}
				
			/**
			 * An available hook to display extra elements to the map 
			 * @since 2.8.5 */
			 	
			$output .= apply_filters('cspm_add_map_interface_element', '');
										
			return $output;
							
		}
		
		
		/**
		 * Map-up, Carousel-down layout
		 * 
		 * @updated 2.8.5 
		 * @updated 2.8.6
		 */		 
		function cspm_map_up_carousel_down_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',
				'map_height' => '',
				'carousel' => 'yes',		
				'carousel_height' => '',				
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
		
			$output = '<div class="row" style="margin:0; padding:0;">';
					
				/**
				 * An available hook to display extra elements before the map container
				 * @since 2.8.5 */
				
				$output .= apply_filters('cspm_before_map', '');
								
				/**
				 * Map */
				 
				$output .= '<div class="'.apply_filters('cspm_map_container_classes', 'col-lg-12 col-xs-12 col-sm-12 col-md-12').'" style="position:relative; overflow:hidden; margin:0; padding:0;">';
				
					/**
					 * Interface elements
					 * @updated 2.8.5 
					 * @updated 2.8.6 */
					 
					$output .= $this->cspm_map_interface_element(array(
						'map_id' => $map_id,		
						'carousel' => $carousel,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));

					$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" style="height:'.$map_height.';"></div>';
				
				$output .= '</div>';
					
				/**
				 * An available hook to display extra elements after the map container
				 * @since 2.8.5 */
				
				$output .= apply_filters('cspm_after_map', '');
								
			$output .= '</div>';
				
			$output .= '<div class="row" style="margin:0; padding:0">';
			
				/**
				 * An available hook to display extra elements before the carousel
				 * @since 2.8.5 */
				
				$output .= apply_filters('cspm_before_carousel', '');
										
				/**
				 * Carousel */
				 
				if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){
					
					$output .= '<div id="cspm_carousel_container" data-map-id="'.$map_id.'" class="col-lg-12 col-xs-12 col-sm-12 col-md-12" style="margin:0; padding:0; height:auto;">';
					
						$output .= '<ul id="codespacing_progress_map_carousel_'.$map_id.'" class="jcarousel-skin-default" style="height:'.$carousel_height.'px;"></ul>';
					
					$output .= '</div>';
				
				}
			
				/**
				 * An available hook to display extra elements after the carousel
				 * @since 2.8.5 */
				
				$output .= apply_filters('cspm_after_carousel', '');
			
			$output .= '</div>';		
			
			return $output;
			
		}


		/**
		 * Map-down, Carousel-up layout
		 * 
		 * @updated 2.8.5 
		 */		 
		function cspm_map_down_carousel_up_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',
				'map_height' => '',						
				'carousel' => 'yes',
				'carousel_height' => '',
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
		
			$output = '<div class="row" style="margin:0; padding:0">';
			
				/**
				 * An available hook to display extra elements before the carousel
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_carousel', '');
										
				/**
				 * Carousel */
				 
				if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){

					$output .= '<div id="cspm_carousel_container" data-map-id="'.$map_id.'" class="col-lg-12 col-xs-12 col-sm-12 col-md-12" style="margin:0; padding:0; height:auto;">';
						
						$output .= '<ul id="codespacing_progress_map_carousel_'.$map_id.'" class="jcarousel-skin-default" style="height:'.$carousel_height.'px;"></ul>';
					
					$output .= '</div>';
					
				}
			
				/**
				 * An available hook to display extra elements after the carousel
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_after_carousel', '');
										
			$output .= '</div>';	
			
			$output .= '<div class="row" style="margin:0; padding:0">';
		
				/**
				 * An available hook to display extra elements before the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_map', '');
																				
				/**
				 * Map */
				 
				$output .= '<div class="'.apply_filters('cspm_map_container_classes', 'col-lg-12 col-xs-12 col-sm-12 col-md-12').'" style="position:relative; overflow:hidden; margin:0; padding:0;">';
		
					/** 
					 * Interface elements
					 * @updated 2.8.5 
					 * @updated 2.8.6 */
					 
					$output .= $this->cspm_map_interface_element(array(
						'map_id' => $map_id,		
						'carousel' => $carousel,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
					
					$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" style="height:'.$map_height.';"></div>';
				
				$output .= '</div>';
			
				/**
				 * An available hook to display extra elements after the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_after_map', '');
																		
			$output .= '</div>';
			
			return $output;
			
		}
		
		
		/**
		 * Map-right, Carousel-left layout 
		 * 
		 * @updated 2.8.5
		 * @updated 2.8.6 
		 */		 
		function cspm_map_right_carousel_left_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',				
				'carousel' => 'yes',
				'carousel_width' => '',
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
		
			$output = '<div style="width:100%; height:100%; margin:0; padding:0;">';
				
				if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){
					
					$map_width = 'auto';
					$margin_left = 'margin-left:'.($carousel_width+20).'px;';
					
				}else{
					
					$map_width = '100%';
					$margin_left = '';
					
				}
			
				/**
				 * An available hook to display extra elements before the carousel
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_carousel', '');
														
				if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){ 
					
					/**
					 * Carousel */
					 
					$output .= '<div id="cspm_carousel_container" data-map-id="'.$map_id.'" style="position:absolute; width:auto; height:auto;">';
						
						$output .= '<ul id="codespacing_progress_map_carousel_'.$map_id.'" class="jcarousel-skin-default" style="width:'.$carousel_width.'px; height:'.$this->layout_fixed_height.'px"></ul>';
					
					$output .= '</div>';
					
				}
			
				/**
				 * An available hook to display extra elements before the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_map', '');
														
				/**
				 * Map */
				 
				$output .= '<div style="height:'.$this->layout_fixed_height.'px; width:'.$map_width.'; position:relative; overflow:hidden; '.$margin_left.'">';
				
					/**
					 * Interface elements
					 * @updated 2.8.5 
					 * @updated 2.8.6 */
					 
					$output .= $this->cspm_map_interface_element(array(
						'map_id' => $map_id,		
						'carousel' => $carousel,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
					
					$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" class="gmap3" style="width:100%; height:100%"></div>';
				
				$output .= '</div>';
			
				/**
				 * An available hook to display extra elements after the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_after_map', '');
										
			$output .= '</div>';
			
			return $output;
			
		}
		
		
		/**
		 * Map-left, Carousel-right layout 
		 * 
		 * @updated 2.8.5 
		 * @updated 2.8.6
		 */		 
		function cspm_map_left_carousel_right_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',
				'carousel' => 'yes',
				'carousel_width' => '',				
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
			
			$output = '<div style="width:100%; height:100%; margin:0; padding:0;">';
				
				if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){
					
					$map_width = 'auto';
					$margin_right = 'margin-right:'.($carousel_width+20).'px;';
					
				}else{
					
					$map_width = '100%';
					$margin_right = '';
					
				}
			
				/**
				 * An available hook to display extra elements before the carousel
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_carousel', '');
										
				if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){
					
					/**
					 * Carousel */
					 
					$output .= '<div id="cspm_carousel_container" data-map-id="'.$map_id.'" style="float:right; width:auto; height:auto;">';
						
						$output .= '<ul id="codespacing_progress_map_carousel_'.$map_id.'" class="jcarousel-skin-default" style="width:'.$carousel_width.'px; height:'.$this->layout_fixed_height.'px"></ul>';
					
					$output .= '</div>';
					
				}
			
				/**
				 * An available hook to display extra elements before the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_map', '');
										
				/**
				 * Map */
				 
				$output .= '<div style="height:'.$this->layout_fixed_height.'px; width:'.$map_width.'; position:relative; overflow:hidden; '.$margin_right.'">';
				
					/**
					 * Interface elements
					 * @updated 2.8.5 
					 * @updated 2.8.6 */
					 
					$output .= $this->cspm_map_interface_element(array(
						'map_id' => $map_id,		
						'carousel' => $carousel,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
					
					$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" class="gmap3" style="width:100%; height:100%"></div>';
				
				$output .= '</div>';
			
				/**
				 * An available hook to display extra elements after the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_after_map', '');
										
			$output .= '</div>';
			
			return $output;
			
		}
		
		
		/**
		 * Fullscreen & Fit in map
		 *
		 * @since 2.0
		 * @updated 2.8.5 
		 * @updated 2.8.6
		 */		 
		function cspm_only_map_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
		
			$output = '';
			
			/**
			 * An available hook to display extra elements before the map container
			 * @since 2.8.5 */
			
			$output .= apply_filters('cspm_before_map', '');
										
			/**
			 * Interface elements
			 * @updated 2.8.5
			 * @updated 2.8.6 */
			 
			$output .= $this->cspm_map_interface_element(array(
				'map_id' => $map_id,		
				'carousel' => 'no',
				'faceted_search' => $faceted_search,
				'search_form' => $search_form,
				'faceted_search_tax_slug' => $faceted_search_tax_slug,
				'faceted_search_tax_terms' => $faceted_search_tax_terms,
				'geo' => $geo,
				'infobox_type' => $infobox_type, //@since 2.8.5
				'infobox_target_link' => $infobox_target_link, //@since 2.8.6
			));
					
			/**
			 * Map */
			 
			$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" class="gmap3" style="width:100%; height:100%"></div>';
			
			/**
			 * An available hook to display extra elements after the map container
			 * @since 2.8.5 */
			
			$output .= apply_filters('cspm_after_map', '');
										
			return $output;
			
		}
		
		
		/** 
		 * Map-up, Carousel-over layout
		 *
		 * @since 2.3
		 * @updated 2.8.5
		 * @updated 2.8.6
		 */		 
		function cspm_map_up_carousel_over_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',
				'map_height' => '',		
				'carousel_height' => '',
				'carousel' => 'yes',
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
		
			$output = '<div class="row" style="margin:0; padding:0">';
			
				/**
				 * An available hook to display extra elements before the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_map', '');
										
				/**
				 * Map Container */
				 
				$output .= '<div class="'.apply_filters('cspm_map_container_classes', 'col-lg-12 col-xs-12 col-sm-12 col-md-12').'" style="position:relative; margin:0; padding:0;">';
										
					/**
					 * Interface elements
					 * @updated 2.8.5 
					 * @updated 2.8.6 */
					 
					$output .= $this->cspm_map_interface_element(array(
						'map_id' => $map_id,		
						'carousel' => $carousel,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
					
					/**
					 * Carousel */
					 
					if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){
						
						$output .= '<div id="cspm_carousel_container" class="codespacing_progress_map_carousel_on_top" data-map-id="'.$map_id.'">';
						
							$output .= '<ul id="codespacing_progress_map_carousel_'.$map_id.'" class="jcarousel-skin-default" style="height:'.$carousel_height.'px;"></ul>';
						
						$output .= '</div>';
					
					}
					
					/**
					 * Map */
					 
					$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" style="height:'.$map_height.'"></div>';
				
				$output .= '</div>';
			
				/**
				 * An available hook to display extra elements after the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_after_map', '');
										
			$output .= '</div>';
			
			return $output;
			
		}
		
		
		/**
		 * Fullscreen & Fit in map with carousel
		 *
		 * @since 2.3
		 * @updated 2.8.5 
		 * @updated 2.8.6
		 */		 
		function cspm_full_map_carousel_over_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',				
				'carousel' => 'yes',
				'carousel_height' => '',
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
		
			$output = '';
										
			/**
			 * Interface elements
			 * @updated 2.8.5 
			 * @updated 2.8.6 */
			 
			$output .= $this->cspm_map_interface_element(array(
				'map_id' => $map_id,		
				'carousel' => $carousel,
				'faceted_search' => $faceted_search,
				'search_form' => $search_form,
				'faceted_search_tax_slug' => $faceted_search_tax_slug,
				'faceted_search_tax_terms' => $faceted_search_tax_terms,
				'geo' => $geo,
				'infobox_type' => $infobox_type, //@since 2.8.5
				'infobox_target_link' => $infobox_target_link, //@since 2.8.6
			));
					
			/**
			 * Carousel */
			 
			if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){
				
				$output .= '<div id="cspm_carousel_container" class="codespacing_progress_map_carousel_on_top col col-lg-12 col-xs-12 col-sm-12 col-md-12" data-map-id="'.$map_id.'">';
				
					$output .= '<ul id="codespacing_progress_map_carousel_'.$map_id.'" class="jcarousel-skin-default" style="height:'.$carousel_height.'px;"></ul>';
				
				$output .= '</div>';
			
			}
			
			/**
			 * An available hook to display extra elements before the map container
			 * @since 2.8.5 */
			
			$output .= apply_filters('cspm_before_map', '');
										
			/**
			 * Map */
			 
			$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" class="gmap3" style="width:100%; height:100%"></div>';
			
			/**
			 * An available hook to display extra elements after the map container
			 * @since 2.8.5 */
			
			$output .= apply_filters('cspm_after_map', '');
										
			return $output;
			
		}
		
		
		/**
		 * Map, Toggle-Carousel-top layout
		 *
		 * @since 2.4
		 * @updated 2.8.5 
		 * @updated 2.8.6
		 */		 
		function cspm_map_toggle_carousel_top_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',
				'map_height' => '',
				'carousel' => 'yes',	
				'carousel_height' => '',				
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
		
			$output = '<div class="row" style="margin:0; padding:0">';
			
				/**
				 * An available hook to display extra elements before the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_map', '');
										
				/**
				 * Map Container */
				 
				$output .= '<div class="'.apply_filters('cspm_map_container_classes', 'col-lg-12 col-xs-12 col-sm-12 col-md-12').'" style="position:relative; margin:0; padding:0;">';
											
					/**
					 * Interface elements
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_map_interface_element(array(
						'map_id' => $map_id,		
						'carousel' => $carousel,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
					
					/**
					 * Carousel */
					 
					if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){
						
						$output .= '<div class="cspm_toggle_carousel_horizontal_top" style="width:100%;">';
							
							$output .= '<div id="cspm_carousel_container" data-map-id="'.$map_id.'" style="display:none;">';
								
								$output .= '<ul id="codespacing_progress_map_carousel_'.$map_id.'" class="jcarousel-skin-default" style="height:'.$carousel_height.'px;"></ul>';
								 
							$output .= '</div>';
								
							$output .= '<div class="toggle-carousel-top cspm_border_bottom_radius cspm_border_shadow" data-map-id="'.$map_id.'">'.apply_filters('cspm_toggle_carousel_text', esc_html__('Toggle carousel', 'cspm')).'</div>';
							
						$output .= '</div>';
					
					}
					
					/**
					 * Map */
					 
					$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" style="height:'.$map_height.'"></div>';
				
				$output .= '</div>';
			
				/**
				 * An available hook to display extra elements after the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_after_map', '');
										
			$output .= '</div>';
			
			return $output;
			
		}
		
		
		/**
		 * Map, Toggle-Carousel-bottom layout
		 *
		 * @since 2.4
		 * @updated 2.8.5 
		 * @updated 2.8.6
		 */		 
		function cspm_map_toggle_carousel_bottom_layout($atts = array()){
				
			extract( wp_parse_args( $atts, array(
				'map_id' => '',
				'map_height' => '',
				'carousel' => 'yes',		
				'carousel_height' => '',				
				'faceted_search' => 'yes',
				'search_form' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => array(),
				'geo' => 'true',
				'infobox_type' => '', //@since 2.8.5
				'infobox_target_link' => '', //@since 2.8.6
			)));
		
			$output = '<div class="row" style="margin:0; padding:0">';
			
				/**
				 * An available hook to display extra elements before the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_before_map', '');
														
				/**
				 * Map Container */
				 
				$output .= '<div class="'.apply_filters('cspm_map_container_classes', 'col-lg-12 col-xs-12 col-sm-12 col-md-12').'" style="position:relative; margin:0; padding:0;">';
										
					/**
					 * Interface elements
					 * @updated 2.8.5 */
					 
					$output .= $this->cspm_map_interface_element(array(
						'map_id' => $map_id,		
						'carousel' => $carousel,
						'faceted_search' => $faceted_search,
						'search_form' => $search_form,
						'faceted_search_tax_slug' => $faceted_search_tax_slug,
						'faceted_search_tax_terms' => $faceted_search_tax_terms,
						'geo' => $geo,
						'infobox_type' => $infobox_type, //@since 2.8.5
						'infobox_target_link' => $infobox_target_link, //@since 2.8.6
					));
					
					/**
					 * Map */
					 
					$output .= '<div id="codespacing_progress_map_div_'.$map_id.'" style="height:'.$map_height.';"></div>';
					
					/**
					 * Carousel */
					 
					if($this->show_carousel == 'true' && $carousel == "yes" && !$this->cspm_detect_mobile_browser()){
						
						$output .= '<div class="cspm_toggle_carousel_horizontal_bottom" style="width:100%;">';
								
							$output .= '<div class="toggle-carousel-bottom cspm_border_top_radius cspm_border_shadow" data-map-id="'.$map_id.'">'.apply_filters('cspm_toggle_carousel_text', esc_html__('Toggle carousel', 'cspm')).'</div>';
							
							$output .= '<div id="cspm_carousel_container" data-map-id="'.$map_id.'" style="display:none;">';
								
								$output .= '<ul id="codespacing_progress_map_carousel_'.$map_id.'" class="jcarousel-skin-default" style="height:'.$carousel_height.'px;"></ul>';
								 
							$output .= '</div>';
						
						$output .= '</div>';
					
					}
				
				$output .= '</div>';
			
				/**
				 * An available hook to display extra elements after the map container
				 * @since 2.8.5 */
			 	
				$output .= apply_filters('cspm_after_map', '');
														
			$output .= '</div>';
			
			return $output;
			
		}

				
		/**
		 * The widget that contains the posts count
		 *
		 * @since 2.1
		 * @updated 3.2 [replaced cspm_wpml_get_string() by esc_html__()]
		 */
		function cspm_posts_count_clause($count, $map_id){
			
			$posts_count_clause = $this->posts_count_clause;
			
			return str_replace('[posts_count]', '<span class="the_count_'.$map_id.'">'.$count.'</span>', esc_html__($posts_count_clause, 'cspm'));
			
		}
		
		
		/**
		 * Get marker image when markers are displayed by category
		 *
		 * @since 2.4
		 * @updated 2.8
		 * @updated 3.0 [Added single map option]
		 */
		function cspm_get_marker_img($atts = array()){
		
			$defaults = array(
				
				'post_id' => '',
				
				/**
				 * Define if the map is a single map or not
				 * @since 3.0 */
				 
				'is_single' => false,
				
				/**
				 * The taxonomy name to which a post is connected via a term */
				 
				'tax_name' => '',
				
				/**
				 * The term ID to which a post is connected */
				 
				'term_id' => '',
				
				'default_marker_icon' => $this->marker_icon,
			);
			
			extract(wp_parse_args($atts, $defaults));
				
			$marker_image = $default_marker_icon;
						
			/**
			 * The Primary marker is the one set for a post in the "Add/Edit post" page.
			 * If a post have a primary marker, this means that we want to display this marker no matter what marker is used for the taxonomy term. */
			 
			$primary_marker_img = (!empty($post_id)) ? get_post_meta($post_id, CSPM_MARKER_ICON_FIELD, true) : '';
						
			$single_post_img_only = get_post_meta($post_id, CSPM_SINGLE_POST_IMG_ONLY_FIELD, true);
			
			if(!empty($primary_marker_img) && ($is_single || (!$is_single && $single_post_img_only != 'yes'))){
				
				$marker_image = $primary_marker_img;
		
			/**
			 * Check if the user wants to display custom marker for each category of post */
			 
			}elseif($this->marker_cats_settings == 'true'){
								
				$marker_categories_images = unserialize($this->cspm_get_map_option('marker_categories_images', serialize(array())));				
				$marker_categories_images_array = array();
				
				foreach($marker_categories_images as $marker_category_img){				
					if(isset($marker_category_img['marker_img_category_'.$tax_name]))
						$marker_categories_images_array[$marker_category_img['marker_img_category_'.$tax_name]] = $marker_category_img;							
				}
					
				if(is_array($marker_categories_images_array) && count($marker_categories_images_array) > 0){
					
					if(isset($marker_categories_images_array[$term_id])){
						
						$marker_object = $marker_categories_images_array[$term_id];
						
						if(isset($marker_object['marker_img_path_'.$tax_name]))
							$marker_image = $marker_object['marker_img_path_'.$tax_name];
							
					}
					
				}
				
			}
				
			return $marker_image;
			
		}
		
		
		/**
		 * Get image path from its URL
		 *
		 * @since 2.4
		 * @Updated 2.7.2 
		 */
		function cspm_get_image_path_from_url($url){
			
			if(!empty($url)){
				
				/**
				 * [@wp_content_directory_url] & [@wp_content_directory_name]
				 * Get the wp-content folder name dynamicaly as some users may change its name to a custom name
				 * @since 2.7.2 */
				 
				$wp_content_directory_url = explode('/', WP_CONTENT_URL);
				$wp_content_directory_name = is_array($wp_content_directory_url) ? array_pop($wp_content_directory_url) : 'wp-content';
				
				$exploded_url = explode($wp_content_directory_name, $url);
				
				if(isset($exploded_url[1]))
					return WP_CONTENT_DIR.$exploded_url[1];
				
				else return false;		
				
			}else return false;
				
		}
		
		
		/**
		 * Get image size by image URL
		 *
		 * @since 2.4
		 */
		function cspm_get_image_size($path, $retina = "false"){
			
			if(!empty($path) && file_exists($path)){
				
				$img_size = getimagesize($path);
				
				if(isset($img_size[0]) && isset($img_size[1])){
					
					return $retina == "false" ? $img_size[0].'x'.$img_size[1] : ($img_size[0]/2).'x'.($img_size[1]/2);
					
				}else return '';
	
			}else return '';
			
		}
		
		
		/**
		 * Load the markers clustred inside a small area on the map
		 *
		 * @since 2.5
		 * updated 2.8.6
		 */
		function cspm_load_clustred_markers_list(){
			
			$post_ids = $_POST['post_ids'];
			$light_map = esc_attr($_POST['light_map']);
			
			/**
			 * Reload map settings */
			 
			$this->map_object_id = esc_attr($_POST['map_object_id']);
			$this->map_settings = $_POST['map_settings'];
				
			$this->items_title = $this->cspm_get_map_option('items_title');
			$this->infobox_external_link = $this->cspm_get_map_option('infobox_external_link');
			
			/**
			 * List */
			 		
			$output = '<ul>';
			
				foreach($post_ids as $post_id){
					
					$item_title = apply_filters(
						'cspm_custom_item_title', 
						stripslashes_deep(
							$this->cspm_items_title(array(
								'post_id' => $post_id, 
								'title' => $this->cspm_get_map_option('items_title'), 
								'click_title_option' => false,
								'click_on_title' => $this->cspm_get_map_option('click_on_title'),
								'external_link' => $this->cspm_get_map_option('external_link'),
							))
						), 
						$post_id
					); 
					
					$parameter = array(
						'style' => "width:70px; height:50px;"
					);
				
					$infobox_image_size = has_image_size('cspm-horizontal-thumbnail-map'.$this->map_object_id) 
						? 'cspm-horizontal-thumbnail-map'.$this->map_object_id
						: 'cspm-horizontal-thumbnail-map';
					
					$post_thumbnail = get_the_post_thumbnail($post_id, $infobox_image_size, $parameter);
					$the_permalink  = ($light_map == 'true') ? ' href="'.$this->cspm_get_permalink($post_id).'"' : '';
					$the_permalink .= ($light_map == 'true' && $this->cspm_get_map_option('infobox_external_link') == 'new_window') ? ' target="_blank"' : '';
					
					$output .= '<li id="'.$post_id.'">';
						$output .= $post_thumbnail;
						$output .= '<a'.$the_permalink.'>'.$item_title.'</a>';
						$output .= '<div style="clear:both"></div>';
					$output .= '</li>';
		
				}
			
			$output .= '</ul>';
			
			die($output);
			
		}
		
		
		/**
		 * Create the faceted search form to filter markers and posts
		 *
		 * @since 2.1
		 * @updated 2.8.5
		 * @updated 3.0 [added $left_position]
		 */
		function cspm_faceted_search($atts = array()){

			if(class_exists('CSProgressMap'))
				$CSProgressMap = CSProgressMap::this();
			
			$defaults = array(
				'map_id' => '',
				'carousel' => 'yes',
				'faceted_search_tax_slug' => '',
				'faceted_search_tax_terms' => '',
				'left_position' => '',
				'extensions' => array(),
			);
			
			extract(wp_parse_args($atts, $defaults));
				
			$output = '';
				
			$faceted_search_icon = apply_filters('cspm_faceted_search_icon', $this->faceted_search_icon, str_replace('map', '', $map_id));
							
			$output .= '<div class="faceted_search_btn cspm_border_shadow cspm_border_radius" id="'.$map_id.'" data-map-id="'.$map_id.'" style="left:'.$left_position.'">';
				$output .= '<img src="'.$faceted_search_icon.'" alt="'.esc_html__('Filter', 'cspm').'" title="'.esc_html__('Filter', 'cspm').'" />';
			$output .= '</div>';

			$output .= '<div class="faceted_search_container_'.$map_id.' cspm_border_shadow cspm_border_radius" style="left:'.$left_position.'">';
				
				$output .= '<form action="" method="post" class="faceted_search_form" id="faceted_search_form_'.$map_id.'" data-map-id="'.$map_id.'" data-ext="'.apply_filters('cspm_ext_name', '', $extensions).'">';
					
					$output .= '<input type="hidden" name="map_id" value="'.$map_id.'" />';
					$output .= '<input type="hidden" name="show_carousel" value="'.$carousel.'" />';
					
					$output .= '<ul>';

						/**
						 * Get the taxonomy name from the marker categories settings */
						 
						if(!empty($faceted_search_tax_slug)){
								
							/**
							 * Get Taxonomy Name */
							 
							$tax_name = $faceted_search_tax_slug;
							
							if(empty($faceted_search_tax_terms)){
								
								$terms = $this->faceted_search_terms;
								
							}else $terms = $faceted_search_tax_terms;
							
							if(is_array($terms) && count($terms) > 0){
								
								foreach($terms as $term_id){
									
									// For WPML =====
									$term_id = $CSProgressMap->cspm_wpml_object_id($term_id, $tax_name, false, '', $this->use_with_wpml);
									// @For WPML ====
									
									if($term = get_term($term_id, $tax_name)){
										
										$term_name = isset($term->name) ? $term->name : '';
									
										if($this->faceted_search_multi_taxonomy_option == 'true'){
											
											$output .= '<li>';				
												$output .= '<input type="checkbox" name="'.$tax_name.'___'.$term_id.'[]" id="'.$map_id.'_'.$tax_name.'___'.$term_id.'" value="'.$term_id.'" data-map-id="'.$map_id.'" data-show-carousel="'.$carousel.'" data-taxonomy="'.$tax_name.'" data-term-id="'.$term_id.'" class="faceted_input '.$map_id.' '.$carousel.'">';
												$output .= '<label for="'.$map_id.'_'.$tax_name.'___'.$term_id.'">'.$term_name.'</label>';
												$output .= '<div style="clear:both"></div>';												
											$output .= '</li>';
											
										}else{
											
											$output .= '<li>';				
												$output .= '<input type="radio" name="'.$tax_name.'" id="'.$map_id.'_'.$tax_name.'_'.$term_id.'" value="'.$term_id.'" data-map-id="'.$map_id.'" data-show-carousel="'.$carousel.'" data-taxonomy="'.$tax_name.'" data-term-id="'.$term_id.'" class="faceted_input '.$map_id.' '.$carousel.'">';
												$output .= '<label for="'.$map_id.'_'.$tax_name.'_'.$term_id.'">'.$term_name.'</label>';
												$output .= '<div style="clear:both"></div>';												
											$output .= '</li>';
											
										}
										
									}
									
								}
								
							}else $output .= '<li>'.__('No option found!', 'cspm').'</li>';
							
						}
											
					$output .= '</ul>';
								
				$output .= '</form>';			
				 	
				$output .= '<div class="reset_map_list_'.$map_id.' cspm_reset_btn cspm_border_shadow cspm_border_radius" id="'.$map_id.'" data-map-id="'.$map_id.'">';
					$output .= esc_html('Reset', 'cspm').'<img src="'.apply_filters('cspm_fs_refresh_img', $this->plugin_url.'img/refresh-proximity.png', str_replace('map', '', $map_id)).'" />';
				$output .= '</div>';
					
			$output .= '</div>';
			
			return $output;
			
		}
		
		
		/**
		 * Create the search form
		 *
		 * @since 2.4 
		 * @updated 2.8.5*
		 * @updated 3.0 [added {@left_position}, {@open_btn}, {@custom_css}]
		 * @updated 3.2 [replaced cspm_wpml_get_string() by esc_html__()]
		 */
		function cspm_search_form($atts = array()){

			$defaults = array(
				'map_id' => '',
				'carousel' => 'yes',
				'left_position' => '', //@since 3.0
				'open_btn' => true, //@since 3.0
				'custom_css' => '', //@since 3.0
				'extensions' => array(),
			);
			
			extract(wp_parse_args($atts, $defaults));
				
			$distance_unit = ($this->sf_distance_unit == 'metric') ? "Km" : "Miles";
			$min_search_distances = $this->sf_min_search_distances;
			$max_search_distances = $this->sf_max_search_distances;
			
			/**
			 * @WPML String translate */
 
			$address_placeholder = esc_html__($this->address_placeholder, 'cspm');
			$slider_label = esc_html__($this->slider_label, 'cspm');
			$no_location_msg = esc_html__($this->no_location_msg, 'cspm');
			$bad_address_msg = esc_html__($this->bad_address_msg, 'cspm');
			$bad_address_sug_1 = esc_html__($this->bad_address_sug_1, 'cspm');
			$bad_address_sug_2 = esc_html__($this->bad_address_sug_2, 'cspm');
			$bad_address_sug_3 = esc_html__($this->bad_address_sug_3, 'cspm');			
			$submit_text = esc_html__($this->submit_text, 'cspm');
			
			$output = '';
			
			if($open_btn){
				
				$search_form_icon = apply_filters('cspm_search_form_icon', $this->search_form_icon, str_replace('map', '', $map_id));
				
				$output .= '<div class="search_form_btn cspm_border_shadow cspm_border_radius" id="'.$map_id.'" data-map-id="'.$map_id.'" style="left:'.$left_position.'">';
					$output .= '<img src="'.$search_form_icon.'" alt="'.esc_html__('Search', 'cspm').'" title="'.esc_html__('Search', 'cspm').'" />';
				$output .= '</div>';
			
			}
			
			$output .= '<div class="search_form_container_'.$map_id.' cspm_border_shadow cspm_border_radius" style="left:'.$left_position.'; '.$custom_css.'">';
			
				$output .= '<div class="cspm_search_form_notice cspm_border_shadow cspm_border_radius"><div>'.$no_location_msg.'</div></div>';
				
				$output .= '<div class="cspm_search_form_error cspm_border_shadow cspm_border_radius"><strong>'.$bad_address_msg.'</strong><ul><li>'.$bad_address_sug_1.'</li><li>'.$bad_address_sug_2.'</li><li>'.$bad_address_sug_3.'</li></ul></div>';
				
				$output .= '<form action="" method="post" class="search_form" id="search_form_'.$map_id.'" onsubmit="return false;">';
					
					$output .= '<div class="cspm_search_form_row">';
						$output .= '<div class="cspm_search_input_text_container input cspm_border_shadow cspm_border_radius">';
							$output .= '<input type="text" name="cspm_address" id="cspm_address_'.$map_id.'" value="" placeholder="'.$address_placeholder.'" />';
							$output .= '<img src="'.apply_filters('cspm_sf_radius_img', $this->plugin_url.'img/placeholder.png', str_replace('map', '', $map_id)).'" />';
						$output .= '</div>';
					$output .= '</div>';
					
					$output .= '<div class="cspm_expand_search_area">';
						$output .= '<div class="cspm_search_label_container">';
							$output .= '<img src="'.apply_filters('cspm_sf_radius_img', $this->plugin_url.'img/radius.png', str_replace('map', '', $map_id)).'" />';
							$output .= '<label>'.$slider_label.'</label>';
						$output .= '</div>';
						$output .= '<div class="cspm_search_slider_container">';
							$output .= '<input type="text" name="cspm_distance" class="cspm_sf_slider_range" data-map-id="'.$map_id.'" data-min="'.$min_search_distances.'" data-max="'.$max_search_distances.'" data-postfix=" '.$distance_unit.'" data-keyboard="true" data-keyboard-step="0.1" />';
							$output .= '<input type="hidden" name="cspm_distance_unit" value="'.$this->sf_distance_unit.'" />';
							$output .= '<input type="hidden" name="cspm_decimals_distance" value="" />';
						$output .= '</div>';
					$output .= '</div>';
					
					$output .= '<div class="cspm_search_btns_container">';
						$output .= '<div class="cspm_submit_search_form_'.$map_id.' cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'" data-show-carousel="'.$carousel.'" data-ext="'.apply_filters('cspm_ext_name', '', $extensions).'">';
							$output .= $submit_text.'<img src="'.apply_filters('cspm_sf_loup_img', $this->plugin_url.'img/search-loup.png', str_replace('map', '', $map_id)).'" />';
						$output .= '</div>';
						$output .= '<div class="cspm_reset_search_form_'.$map_id.' cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'" data-show-carousel="'.$carousel.'" data-ext="'.apply_filters('cspm_ext_name', '', $extensions).'">';
							$output .= '<img src="'.apply_filters('cspm_sf_refresh_img', $this->plugin_url.'img/refresh-circular-arrow.png', str_replace('map', '', $map_id)).'" />';
						$output .= '</div>';
						$output .= '<div style="clear:both"></div>';						
					$output .= '</div>';
					
				$output .= '</form>';	
			
			$output .= '</div>';

			return $output;
			
		}
		
		
		/**
		 * Detect mobile browser
		 *
		 * @since 2.4
		 * @updated 2.7
		 * @updated 2.8.5
		 */
		function cspm_detect_mobile_browser(){

			if($this->layout_type == 'responsive'){
				
				if(!class_exists('Mobile_Detect')) //@since 2.8.5
					require_once $this->plugin_path.'libs/Mobile_Detect.php';
				
				$detect = new Mobile_Detect;

				return $detect->isMobile() ? true : false;
			
			}else return false;
			
		}
				
		
		/**
		 * Get All Values of A Custom Field Key 
		 * @key: The meta_key of the post meta
		 * @type: The name of the custom post type
		 *
		 * @since 2.5 
		 */
		function cspm_get_meta_values( $key = '', $post_type = 'post' ) {
			
			global $wpdb;
			
			if( empty( $key ) )
				return;
			
			$rows = $wpdb->get_results( $wpdb->prepare( "
				SELECT pm.meta_value, p.ID FROM {$wpdb->postmeta} pm
				LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = '%s' 
				AND pm.meta_value != '' 
				AND p.post_type = '%s'
			", $key, $post_type ), ARRAY_A );
			
			$results = array();
			
			foreach($rows as $row)
				$results['post_id_'.$row['ID']] = array($key => $row['meta_value']);
			
			return $results;
			
		}
		
		
		/**
		 * This will display a Dropdown list of certain countries on the map.
		 * This function is related to the feature "Zoom to country".
		 * Note: Some countries may not be found. Check https://fr.wikipedia.org/wiki/ISO_3166
		 *
		 * @since 3.0
		 */
		function cspm_countries_list($map_id, $left_position){
			
			/**
			 * Get all countries in English language */
			 
			$all_en_countries = $this->cspm_get_en_countries();
			
			/** 
			 * Get all countries based on selected language in "[@countries_display_lang]" */
			 
			$all_countries = $this->cspm_get_countries();
			
			/**
			 * Get selected countries */
			 
			$selected_countries = $this->countries;
			
			$output = '';
			
			$countries_btn_icon = apply_filters('cspm_countries_btn_icon', $this->countries_btn_icon, str_replace('map', '', $map_id));
			
			$output .= '<div class="countries_btn cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'" style="left:'.$left_position.'">';
				
				$output .= '<img src="'.$countries_btn_icon.'" alt="'.esc_html__('Countries', 'cspm').'" title="'.esc_html__('Countries', 'cspm').'" />';
			
			$output .= '</div>';
		
			$output .= '<div class="countries_container_'.$map_id.' cspm_border_shadow cspm_border_radius" style="left:'.$left_position.'">';
				
				$output .= '<ul>';
					
					if(count($selected_countries) > 0){
					
						foreach($selected_countries as $country_code){
							
							if(array_key_exists($country_code, $all_countries)){
								
								$output .= '<li class="cspm_country_name" data-map-id="'.$map_id.'" data-country-name="'.$all_en_countries[$country_code].'"  data-country-code="'.$country_code.'">';
									
									if(in_array($this->country_flag, array('true', 'only'))){
										
										$flags_dir_path = apply_filters('cspm_flags_dir_path', $this->plugin_path.'img/flags/', str_replace('map', '', $map_id));
										$flags_dir_url = apply_filters('cspm_flags_dir_url', $this->plugin_url.'img/flags/', str_replace('map', '', $map_id));
										
										$img_path = apply_filters('cspm_country_flag_path', $flags_dir_path.strtoupper($country_code).'.png', $country_code, str_replace('map', '', $map_id));
										$img_url = apply_filters('cspm_country_flag_url', $flags_dir_url.strtoupper($country_code).'.png', $country_code, str_replace('map', '', $map_id));
										
										if(file_exists($img_path))
											$output .= '<img src="'.$img_url.'" class="'.$this->country_flag.'" />';
										
									}
									
									if($this->country_flag != 'only')
										$output.= $all_countries[$country_code];
									
								$output .= '</li>';	
								
							}
						
						}
					
					}else $output .= __('No country found!', 'cspm');
					
				$output .= '</ul>';
				
			$output .= '</div>';			
			
			return $output;
			
		}
		
		
		/**
		 * This will return a list of all countries in English Language
		 *
		 * @since 3.0
		 */
		function cspm_get_en_countries(){
			
			if(file_exists($this->plugin_path . 'inc/countries/en/country.php')){

				$countries = include($this->plugin_path . 'inc/countries/en/country.php');
				
				return $countries;
				
			}else return array();
			
		}

		
		/**
		 * This will return a list of all countries based on given language
		 *
		 * @since 3.0
		 */
		function cspm_get_countries(){
			
			$display_lang = $this->countries_display_lang;
			
			if(file_exists($this->plugin_path . 'inc/countries/'.$display_lang.'/country.php')){

				$countries = include($this->plugin_path . 'inc/countries/'.$display_lang.'/country.php');
				
				return $countries;
				
			}else return $this->cspm_get_en_countries();
			
		}
			
		
		/**
		 * Build the list of proximities
		 *
		 * @since 3.2
		 */
		function cspm_get_proximities_list($atts = array()){

			$defaults = array(
				'map_id' => '',
				'left_position' => '',
				'open_btn' => true,
				'custom_css' => '',
				'extensions' => array(),
			);
			
			extract(wp_parse_args($atts, $defaults));
			
			$output = '';
			
			if($open_btn){
				
				$proximities_icon = apply_filters('cspm_proximities_icon', $this->plugin_url.'img/proximities.png', str_replace('map', '', $map_id));
				
				$output .= '<div class="cspm_proximities_btn cspm_border_shadow cspm_border_radius" id="'.$map_id.'" data-map-id="'.$map_id.'" style="left:'.$left_position.'">';
					$output .= '<img src="'.$proximities_icon.'" alt="'.esc_html__('Nearby points of interest', 'cspm').'" title="'.esc_html__('Nearby points of interest', 'cspm').'" />';
				$output .= '</div>';
			
			}
			
			$output .= '<div class="cspm_proximities_container_'.$map_id.' cspm_border_shadow cspm_border_radius" style="left:'.$left_position.'; '.$custom_css.'">';
				
				$driving_icon = apply_filters('cspm_driving_icon', $this->plugin_url.'img/car.png', str_replace('map', '', $map_id)); 
				$driving_inactive_icon = apply_filters('cspm_driving_icon', $this->plugin_url.'img/car-blue.png', str_replace('map', '', $map_id)); 
				
				$transit_icon = apply_filters('cspm_transit_icon', $this->plugin_url.'img/train.png', str_replace('map', '', $map_id)); 
				$transit_inactive_icon = apply_filters('cspm_transit_icon', $this->plugin_url.'img/train-blue.png', str_replace('map', '', $map_id)); 
				
				$walking_icon = apply_filters('cspm_walking_icon', $this->plugin_url.'img/walk.png', str_replace('map', '', $map_id)); 
				$walking_inactive_icon = apply_filters('cspm_walking_icon', $this->plugin_url.'img/walk-blue.png', str_replace('map', '', $map_id)); 
				
				$bicycling_icon = apply_filters('cspm_bicycling_icon', $this->plugin_url.'img/bicycle.png', str_replace('map', '', $map_id)); 
				$bicycling_inactive_icon = apply_filters('cspm_bicycling_icon', $this->plugin_url.'img/bicycle-blue.png', str_replace('map', '', $map_id)); 
				
				$output .= '<div class="cspm_proximities_list_container">';
					$output .= '<ul>';
						
						$proximities_name = $this->cspm_proximities_names();
						
						foreach($this->np_proximities as $proximity_id){
							
							if(isset($proximities_name[$proximity_id])){
								
								$output .= '<li class="cspm_proximity_name" 
									data-proximity-id="'.$proximity_id.'" 
									data-proximity-name="'.$proximities_name[$proximity_id].'" 
									data-map-id="'.$map_id.'" 
									data-distance-unit="'.$this->np_distance_unit.'" 
									data-initial-radius="'.$this->np_radius.'" 
									data-radius="'.$this->np_radius.'" 
									data-min-radius="50" 
									data-max-radius="'.$this->np_radius.'" 
									data-travel-mode="DRIVING" 
									data-min-radius-attempt="0" 
									data-max-radius-attempt="0"
									data-draw-circle="'.$this->np_circle_option.'"
									data-edit-circle="'.$this->np_edit_circle.'"
									data-marker-type="'.$this->np_marker_type.'">';
																
									if(in_array($this->show_proximity_icon, array('true', 'only'))){
										
										$nearby_imgs_dir_path = apply_filters('cspm_nearby_imgs_dir_path', $this->plugin_path.'img/nearby/', str_replace('map', '', $map_id));
										$nearby_imgs_dir_url = apply_filters('cspm_nearby_imgs_dir_url', $this->plugin_url.'img/nearby/', str_replace('map', '', $map_id));
										
										$img_path = apply_filters('cspm_nearby_img_path', $nearby_imgs_dir_path.$proximity_id.'.png', $proximity_id, str_replace('map', '', $map_id));
										$img_url = apply_filters('cspm_nearby_img_url', $nearby_imgs_dir_url.$proximity_id.'.png', $proximity_id, str_replace('map', '', $map_id));
										
										if(file_exists($img_path))
											$output .= '<img src="'.$img_url.'" title="'.$proximities_name[$proximity_id].'" />';
									
									}
									
									if($this->show_proximity_icon != 'only')			
										$output .= $proximities_name[$proximity_id];
									
								$output .= '</li>';
								
							}
							
						}
					
					$output .= '</ul>';
				$output .= '</div>';
				
				/**
				 * Travel modes */
				 
				$output .= '<div class="cspm_swicth_np_mode_container">';
					
					$output .= '<div class="cspm_switch_np_travel_mode cspm_border_shadow cspm_border_radius active" data-map-id="'.$map_id.'" data-travel-mode="DRIVING" data-origin="" data-destination="" data-distance-unit="'.$this->np_distance_unit.'">';
						$output .= '<img src="'.$driving_icon.'" title="'.esc_html__('Driving', 'cspm').'" data-active-src="'.$driving_icon.'" data-inactive-src="'.$driving_inactive_icon.'" />';
					$output .= '</div>';
					
					$output .= '<div class="cspm_switch_np_travel_mode cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'" data-travel-mode="TRANSIT" data-origin="" data-destination="" data-distance-unit="'.$this->np_distance_unit.'">';
						$output .= '<img src="'.$transit_inactive_icon.'" title="'.esc_html__('Transit', 'cspm').'" data-active-src="'.$transit_icon.'" data-inactive-src="'.$transit_inactive_icon.'" />';
					$output .= '</div>';
					
					$output .= '<div class="cspm_switch_np_travel_mode cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'" data-travel-mode="WALKING" data-origin="" data-destination="" data-distance-unit="'.$this->np_distance_unit.'">';
						$output .= '<img src="'.$walking_inactive_icon.'" title="'.esc_html__('Walking', 'cspm').'" data-active-src="'.$walking_icon.'" data-inactive-src="'.$walking_inactive_icon.'" />';
					$output .= '</div>';
					
					$output .= '<div class="cspm_switch_np_travel_mode cspm_border_shadow cspm_border_radius last" data-map-id="'.$map_id.'" data-travel-mode="BICYCLING" data-origin="" data-destination="" data-distance-unit="'.$this->np_distance_unit.'">';
						$output .= '<img src="'.$bicycling_inactive_icon.'" title="'.esc_html__('Bicycling', 'cspm').'" data-active-src="'.$bicycling_icon.'" data-inactive-src="'.$bicycling_inactive_icon.'" />';
					$output .= '</div>';
					
					$output .= '<div style="clear:both"></div>';
					
				$output .= '</div>';
				
				$output .= '<div style="clear:both;"></div>';
				
				/**
				 * Reset */
				 	
				$output .= '<div class="cspm_reset_proximities cspm_reset_btn cspm_border_shadow cspm_border_radius" data-map-id="'.$map_id.'">';
					$output .= esc_html('Reset', 'cspm').'<img src="'.apply_filters('cspm_np_refresh_img', $this->plugin_url.'img/refresh-proximity.png', str_replace('map', '', $map_id)).'" />';
				$output .= '</div>';
					
			$output .= '</div>';

			return $output;
			
		}
		
		
		/**
		 * Build the list of all places
		 *
		 * @since 1.1
		 */
		function cspm_proximities_names(){
			
			$places_array = array(
				'accounting' => esc_html__('Accounting', 'cspm'),
				'airport' => esc_html__('Airport', 'cspm'),
				'amusement_park' => esc_html__('Amusement park', 'cspm'),
				'aquarium' => esc_html__('Aquarium', 'cspm'),
				'art_gallery' => esc_html__('Art gallery', 'cspm'),
				'atm' => esc_html__('Atm', 'cspm'),
				'bakery' => esc_html__('Bakery', 'cspm'),
				'bank' => esc_html__('Bank', 'cspm'),
				'bar' => esc_html__('Bar', 'cspm'),
				'beauty_salon' => esc_html__('Beauty salon', 'cspm'),
				'bicycle_store' => esc_html__('Bicycle store', 'cspm'),
				'book_store' => esc_html__('Book store', 'cspm'),
				'bowling_alley' => esc_html__('Bowling alley', 'cspm'),
				'bus_station' => esc_html__('Bus station', 'cspm'),
				'cafe' => esc_html__('Cafe', 'cspm'),
				'campground' => esc_html__('Campground', 'cspm'),
				'car_dealer' => esc_html__('Car dealer', 'cspm'),
				'car_rental' => esc_html__('Car rental', 'cspm'),
				'car_repair' => esc_html__('Car repair', 'cspm'),
				'car_wash' => esc_html__('Car wash', 'cspm'),
				'casino' => esc_html__('Casino', 'cspm'),
				'cemetery' => esc_html__('Cemetery', 'cspm'),
				'church' => esc_html__('Church', 'cspm'),
				'city_hall' => esc_html__('City hall', 'cspm'),
				'clothing_store' => esc_html__('Clothing store', 'cspm'),
				'convenience_store' => esc_html__('Convenience store', 'cspm'),
				'courthouse' => esc_html__('Courthouse', 'cspm'),
				'dentist' => esc_html__('Dentist', 'cspm'),
				'department_store' => esc_html__('Department store', 'cspm'),
				'doctor' => esc_html__('Doctor', 'cspm'),
				'electrician' => esc_html__('Electrician', 'cspm'),
				'electronics_store' => esc_html__('Electronics store', 'cspm'),
				'embassy' => esc_html__('Embassy', 'cspm'),
				'establishment' => esc_html__('Establishment', 'cspm'),
				'finance' => esc_html__('Finance', 'cspm'),
				'fire_station' => esc_html__('Fire station', 'cspm'),
				'florist' => esc_html__('Florist', 'cspm'),
				'food' => esc_html__('Food', 'cspm'),
				'funeral_home' => esc_html__('Funeral home', 'cspm'),
				'furniture_store' => esc_html__('Furniture store', 'cspm'),
				'gas_station' => esc_html__('Gas station', 'cspm'),
				'general_contractor' => esc_html__('General contractor', 'cspm'),
				'grocery_or_supermarket' => esc_html__('Grocery or supermarket', 'cspm'),
				'gym' => esc_html__('Gym', 'cspm'),
				'hair_care' => esc_html__('Hair care', 'cspm'),
				'hardware_store' => esc_html__('Hardware store', 'cspm'),
				'health' => esc_html__('Health', 'cspm'),
				'hindu_temple' => esc_html__('Hindu temple', 'cspm'),
				'home_goods_store' => esc_html__('Home goods store', 'cspm'),
				'hospital' => esc_html__('Hospital', 'cspm'),
				'insurance_agency' => esc_html__('Insurance agency', 'cspm'),
				'jewelry_store' => esc_html__('Jewelry store', 'cspm'),
				'laundry' => esc_html__('Laundry', 'cspm'),
				'lawyer' => esc_html__('Lawyer', 'cspm'),
				'library' => esc_html__('Library', 'cspm'),
				'liquor_store' => esc_html__('Liquor store', 'cspm'),
				'local_government_office' => esc_html__('Local government office', 'cspm'),
				'locksmith' => esc_html__('Locksmith', 'cspm'),
				'lodging' => esc_html__('Lodging', 'cspm'),
				'meal_delivery' => esc_html__('Meal delivery', 'cspm'),
				'meal_takeaway' => esc_html__('Meal takeaway', 'cspm'),
				'mosque' => esc_html__('Mosque', 'cspm'),
				'movie_rental' => esc_html__('Movie rental', 'cspm'),
				'movie_theater' => esc_html__('Movie theater', 'cspm'),
				'moving_company' => esc_html__('Moving company', 'cspm'),
				'museum' => esc_html__('Museum', 'cspm'),
				'night_club' => esc_html__('Night club', 'cspm'),
				'painter' => esc_html__('Painter', 'cspm'),
				'park' => esc_html__('Park', 'cspm'),
				'parking' => esc_html__('Parking', 'cspm'),
				'pet_store' => esc_html__('Pet store', 'cspm'),
				'pharmacy' => esc_html__('Pharmacy', 'cspm'),
				'physiotherapist' => esc_html__('Physiotherapist', 'cspm'),
				'place_of_worship' => esc_html__('Place of worship', 'cspm'),
				'plumber' => esc_html__('Plumber', 'cspm'),
				'police' => esc_html__('Police', 'cspm'),
				'post_office' => esc_html__('Post office', 'cspm'),
				'real_estate_agency' => esc_html__('Real estate agency', 'cspm'),
				'restaurant' => esc_html__('Restaurant', 'cspm'),
				'roofing_contractor' => esc_html__('Roofing contractor', 'cspm'),
				'rv_park' => esc_html__('Rv park', 'cspm'),
				'school' => esc_html__('School', 'cspm'),
				'shoe_store' => esc_html__('Shoe store', 'cspm'),
				'shopping_mall' => esc_html__('Shopping mall', 'cspm'),
				'spa' => esc_html__('Spa', 'cspm'),
				'stadium' => esc_html__('Stadium', 'cspm'),
				'storage' => esc_html__('Storage', 'cspm'),
				'store' => esc_html__('Store', 'cspm'),
				'subway_station' => esc_html__('Subway station', 'cspm'),
				'synagogue' => esc_html__('Synagogue', 'cspm'),
				'taxi_stand' => esc_html__('Taxi stand', 'cspm'),
				'train_station' => esc_html__('Train station', 'cspm'),
				'travel_agency' => esc_html__('Travel agency', 'cspm'),
				'university' => esc_html__('University', 'cspm'),
				'veterinary_care' => esc_html__('Veterinary care', 'cspm'),
				'zoo' => esc_html__('Zoo', 'cspm'),
			);
			
			return $places_array;
				
		}

		
		/**
		 * This will display the map with all the settings it needs.
		 * The function will get the custom map ID and all its settings.
		 *
		 * @since 3.0
		 */
		function cspm_main_map_shortcode($atts){
			
			extract( shortcode_atts( array(
			
				/**
				 * [@id] The ID of the map */
				 				 
				'id' => '',
			
				/**
				 * [@center_at] Change the default map center to a given "Lat,Lng" coordinates or a post ID */
				 				 
				'center_at' => '',	
			
				/**
				 * [@post_ids] Display certains posts by providing their IDs (comma separated) */
				 				 
				'post_ids' => '',
				
				'optional_latlng' => 'false',
				
				/**
				 * [@list_ext] Used for the extension "List & Filter"
				 * Wheather to override the Ext. from the shortocode */
				 
				'list_ext' => 'yes',		
				
				/**
				 * [@window_resize] Whether to recenter the Map on window resize or not.
				 * @since 2.8.5 */
				
				'window_resize' => 'yes', // Possible values, "yes" & "no"			  																
			  
			), $atts, 'cspm_main_map' ) ); 						
			
			/**
			 * Get the post metadata (all custom fields) */
			
			$map_id = esc_attr($id);
			
			if(!empty($map_id)){
				
				/**
				 * Get this map post type & status */
				
				$map_post_type = get_post_type($id);
				 
				$map_status = get_post_status($id);
				
				/**
				 * Should match our CPT and any status than "trash" */
				 
				if($map_post_type == $this->object_type && $map_status && $map_status != 'trash'){
					
					/**
					 * Get all the custom fields related to that object/map/post */
					 
					$map_custom_fields = array_map(
						function($value){
							return isset($value[0]) ? $value[0] : $value;
						}, 
						get_post_custom($map_id)
					);
				
					/** 
					 * From all custom fields, we'll extract all the keys that starts with our custom fields prefix */
					 
					$map_settings = array_intersect_key(
						$map_custom_fields, 
						array_flip(
							preg_grep('/^'.$this->metafield_prefix.'/', array_keys($map_custom_fields))
						)
					);
				
					$map_settings['map_object_id'] = $map_id;
					
					/**
					 * Hook to change map settings */
					 
					$map_settings = apply_filters('cspm_map_settings', $map_settings, $map_id, $this->metafield_prefix);
					
					/**
					 * Re-init "Progress Map" Class with (admin/default) plugin settings & map settings */
						
					$CspmMainMap = new CspmMainMap(array(
						'init' => false, 
						'plugin_settings' => $this->plugin_settings,
						'map_settings' => $map_settings,
						'metafield_prefix' => $this->metafield_prefix,
					));
										
						$main_map_args = array(
							'map_id' => 'map'.$map_id,
							'window_resize' => esc_attr($window_resize),
							'center_at' => esc_attr($center_at),	
							'post_ids' => esc_attr($post_ids),
							'optional_latlng' => esc_attr($optional_latlng),	
						);
						
						/** 
						 * Init "List & Filter" extension class and create shortcode attributes to use with "PM" */
		
						$cspm_list_ext = ($list_ext != 'yes') ? 'no' : $this->cspm_setting_exists($this->metafield_prefix.'_list_ext', $map_settings, '');
						
						if($cspm_list_ext == 'on' && class_exists("CspmListFilter")){
		
							$CspmListFilter = new CspmListFilter(array(
								'init' => false, 
								'plugin_settings' => $this->plugin_settings,
								'map_settings' => $map_settings,
								'metafield_prefix' => $this->metafield_prefix,
							));
							
							$main_map_args['list_ext'] = 'yes';			
						
							/**
							 * force to hide "Progress Map" carousel */
							 
							$main_map_args['carousel'] = 'no'; 
							
							/**
							 * force to hide "Progress Map" post count */
							 
							$main_map_args['show_posts_count'] = 'no'; 
							
						}
					
					/**
					 * Display the map
					 * Note: Do not use [$this] to call the main map function, use [$CspmMainMap] instead! */
					
					return $CspmMainMap->cspm_main_map_function($main_map_args);
					
				}else{
					
					?><script>console.log('PROGRESS MAP: The map with ID "<?php echo $map_id; ?>" is no longer available or don\'t really exist! Check your trash from "Progress Map => All Maps => Trash", you might have deleted the map accidentally!');</script><?php
					
					return '<strong>'.__('Map not available!', 'cspm').'</strong>';
					
				}
				
			}else{
					
				?><script>console.log('PROGRESS MAP: You should provide the ID of the map to display! Edit your page and add the ID of the map to the shortcode using the attribute "id". Example: [cspm_main_map id="???"]');</script><?php
				
				return '<strong>'.__('Map not available!', 'cspm').'</strong>';
				
			}
					
		}
			
	}

}	
